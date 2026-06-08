<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\Product;
use App\Models\CashFlow;
use App\Models\Debt;
use App\Models\Customer;
use App\Models\User;
use App\Notifications\StokKritisNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use Midtrans\Config;
use Midtrans\Snap;
use Illuminate\Http\JsonResponse;

class TransaksiController extends Controller
{
    public function index()
    {
        $storeId = auth()->user()->store_id;

        $sales = Sale::where('store_id', $storeId)
            ->with(['details.product', 'user', 'store'])
            ->latest()
            ->paginate(15);

        $receipts = $sales->getCollection()->map(fn($s) => [
            'id'             => $s->id,
            'invoice_number' => $s->invoice_number,
            'date'           => $s->created_at->format('d M Y, H:i'),
            'cashier'        => $s->user->name,
            'store_name'     => $s->store->name,
            'store_address'  => $s->store->address ?? '',
            'footer'         => $s->store->footer_note ?? 'Terima kasih telah berbelanja!',
            'total_price'    => (float) $s->total_price,
            'payment_label'  => match($s->payment_method) {
                'cash'     => 'Tunai',
                'qris'     => 'QRIS',
                'transfer' => 'Transfer Bank',
                default    => ucfirst($s->payment_method ?? '-'),
            },
            'status_label'   => match($s->payment_status) {
                'paid'    => 'Lunas',
                'debt'    => 'Hutang',
                'pending' => 'Pending',
                default   => $s->payment_status,
            },
            'details' => $s->details->map(fn($d) => [
                'id'            => $d->id,
                'product_name'  => $d->product->name,
                'quantity'      => $d->quantity,
                'price_at_sale' => (float) $d->price_at_sale,
                'subtotal'      => (float) $d->subtotal,
            ])->values()->all(),
        ])->values()->all();

        $products = Product::where('store_id', $storeId)
            ->withSum('batches', 'stock')
            ->with(['batches' => fn($q) => $q->orderByDesc('id')])
            ->get()
            ->filter(fn($p) => ($p->batches_sum_stock ?? 0) > 0)
            ->map(fn($p) => [
                'id'    => $p->id,
                'name'  => $p->name,
                'unit'  => $p->unit,
                'stock' => (int) ($p->batches_sum_stock ?? 0),
                'price' => (float) optional($p->batches->first())->selling_price ?? 0,
            ])
            ->values()
            ->all();

        $customers = Customer::where('store_id', $storeId)->get();

        return view('transaksi.index', compact('sales', 'receipts', 'products', 'customers'));
    }

    public function store(Request $request): JsonResponse
    {
        $this->validateStoreRequest($request);

        $storeId = auth()->user()->store_id;
        $txDate  = Carbon::parse($request->transaction_date);
        $total   = 0;
        $lines   = [];

        // 1. Kalkulasi Stok & Harga (Validasi Limit Dihapus agar bisa sampai 0 atau negatif)
        foreach ($request->items as $item) {
            $product = Product::with('batches')->findOrFail($item['product_id']);
            
            // Ambil batch dengan metode FIFO (First In First Out)
            $batch = $product->batches()->where('stock', '>', 0)->orderBy('id', 'asc')->first();

            // Jika semua batch sudah habis/0, ambil batch terakhir sebagai tempat pengurangan (bisa minus)
            if (!$batch) {
                $batch = $product->batches()->orderByDesc('id')->first();
            }

            // Jika produk belum pernah diisi stoknya sama sekali
            if (!$batch) {
                return response()->json(['message' => "Data stok untuk {$product->name} belum ada sama sekali di database."], 422);
            }

            $price    = (float) $batch->selling_price;
            $subtotal = $price * (int) $item['qty'];
            $total   += $subtotal;

            $lines[] = [
                'product_id'    => $product->id,
                'product_name'  => $product->name,
                'unit'          => $product->unit,
                'quantity'      => (int) $item['qty'],
                'price_at_sale' => $price,
                'subtotal'      => $subtotal,
                'batch'         => $batch,
                'min_stock'     => $product->min_stock ?? 5,
                'product_model' => $product // Disimpan untuk menghitung total stok nanti
            ];
        }

        $paymentMethod = $request->payment_status === 'debt' ? null : $request->payment_method;
        $paymentStatus = $request->payment_status;
        $isMidtrans    = false;

        if ($paymentStatus === 'paid' && in_array($paymentMethod, ['qris', 'transfer'])) {
            $paymentStatus = 'pending';
            $isMidtrans    = true;
        }

        DB::beginTransaction();
        try {
            // 2. Buat Pelanggan Baru (Jika diceklis)
            $customerId = $this->handleCustomer($request, $storeId);

            // 3. Buat Invoice
            $invoiceNumber = $this->generateInvoiceNumber($storeId, $txDate);

            $sale = Sale::create([
                'store_id'       => $storeId,
                'user_id'        => auth()->id(),
                'invoice_number' => $invoiceNumber,
                'total_price'    => $total,
                'payment_method' => $paymentMethod,
                'payment_status' => $paymentStatus,
                'created_at'     => $txDate,
                'updated_at'     => $txDate
            ]);

            // 4. Simpan Detail Transaksi & Pengurangan Stok
            $barangKritisTransaksiIni = [];
            foreach ($lines as $line) {
                SaleDetail::create([
                    'sale_id'       => $sale->id,
                    'product_id'    => $line['product_id'],
                    'quantity'      => $line['quantity'],
                    'price_at_sale' => $line['price_at_sale'],
                    'subtotal'      => $line['subtotal'],
                ]);
                
                // Kurangi stok (Sistem mengizinkan nilai stok menyentuh 0 atau bahkan negatif)
                $line['batch']->decrement('stock', $line['quantity']);
                
                // Hitung TOTAL sisa stok dari produk ini (termasuk semua batch jika ada)
                $sisaStok = $line['product_model']->batches()->sum('stock');
                $batasKritis = $line['min_stock'];

                // Jika setelah transaksi total stok menyentuh batas kritis atau habis
                if ($sisaStok <= $batasKritis) {
                    $barangKritisTransaksiIni[] = (object) [
                        'name'              => $line['product_name'],
                        'batches_sum_stock' => $sisaStok,
                        'unit'              => $line['unit'],
                        'min_stock'         => $batasKritis,
                        'status'            => $sisaStok <= 0 ? 'Habis' : 'Menipis',
                    ];
                }
            }

            // 5. Cek Limit dan Kirim Notifikasi Instan (Data akurat sesuai skema sebelumnya)
            $this->processStockNotifications($barangKritisTransaksiIni, $storeId);

            // 6. Catat Data Hutang
            if ($request->payment_status === 'debt') {
                Debt::create([
                    'store_id'          => $storeId,
                    'sale_id'           => $sale->id,
                    'customer_id'       => $customerId,
                    'amount'            => $total,
                    'remaining_balance' => $total,
                    'due_date'          => $request->due_date,
                    'status'            => 'unpaid',
                ]);
            }

            // 7. Catat CashFlow
            if ($paymentStatus === 'paid' && $paymentMethod === 'cash') {
                CashFlow::create([
                    'store_id'     => $storeId,
                    'type'         => 'in',
                    'category'     => 'Penjualan',
                    'amount'       => $total,
                    'description'  => 'Penjualan ' . $invoiceNumber,
                    'reference_id' => $sale->id,
                ]);
            }

            // 8. Konfigurasi Midtrans Snap
            $snapToken = null;
            if ($isMidtrans) {
                $snapToken = $this->generateMidtransSnapToken($paymentMethod, $total, $invoiceNumber);
                $sale->update(['midtrans_snap_token' => $snapToken]);
            }

            DB::commit();

            return response()->json([
                'status'     => $isMidtrans ? 'requires_payment' : 'success',
                'message'    => "Transaksi {$invoiceNumber} berhasil dibuat.",
                'snap_token' => $snapToken
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $storeId = auth()->user()->store_id;
        $sale = Sale::where('store_id', $storeId)->findOrFail($id);

        DB::beginTransaction();
        try {
            foreach ($sale->details as $detail) {
                $detail->product->batches()->latest('id')->first()?->increment('stock', $detail->quantity);
            }

            $sale->details()->delete();
            Debt::where('sale_id', $sale->id)->delete();
            CashFlow::where('reference_id', $sale->id)->delete();
            $sale->delete();

            DB::commit();

            return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('transaksi.index')->with('error', 'Gagal menghapus transaksi: ' . $e->getMessage());
        }
    }

    /**
     * Validasi Request Utama
     */
    private function validateStoreRequest(Request $request): void
    {
        $request->validate([
            'items'              => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.qty'        => 'required|integer|min:1',
            'payment_method'     => 'nullable|in:cash,qris,transfer',
            'payment_status'     => 'required|in:paid,debt',
            'transaction_date'   => 'required|date',
            'customer_id'        => 'nullable|exists:customers,id',
            'customer_name'      => 'nullable|string|max:255',
            'customer_phone'     => 'nullable|string|max:20',
            'customer_address'   => 'nullable|string',
        ]);

        if ($request->payment_status === 'debt') {
            $request->validate([
                'due_date'        => 'required|date',
                'is_new_customer' => 'required|boolean',
            ]);

            if ($request->is_new_customer && empty($request->customer_name)) {
                abort(response()->json(['message' => 'Nama pelanggan baru wajib diisi untuk hutang.'], 422));
            }
            if (!$request->is_new_customer && empty($request->customer_id)) {
                abort(response()->json(['message' => 'Pelanggan wajib dipilih untuk hutang.'], 422));
            }
        }
    }

    /**
     * Menangani logika penciptaan atau pemilihan customer
     */
    private function handleCustomer(Request $request, int $storeId): ?int
    {
        if ($request->payment_status === 'debt' && $request->is_new_customer) {
            $newCustomer = Customer::create([
                'store_id' => $storeId,
                'name'     => $request->customer_name,
                'phone'    => $request->customer_phone,
                'address'  => $request->customer_address,
            ]);
            return $newCustomer->id;
        }
        return $request->customer_id;
    }

    /**
     * Men-generate nomor invoice urut per hari
     */
    private function generateInvoiceNumber(int $storeId, Carbon $txDate): string
    {
        $lastSale = Sale::where('store_id', $storeId)
            ->whereDate('created_at', $txDate->toDateString())
            ->latest('id')
            ->first();

        $seq = $lastSale ? (int) substr($lastSale->invoice_number, -4) + 1 : 1;
        $invoiceNumber = 'INV-' . $txDate->format('Ymd') . '-' . str_pad((string)$seq, 4, '0', STR_PAD_LEFT);

        while (Sale::where('invoice_number', $invoiceNumber)->exists()) {
            $seq++;
            $invoiceNumber = 'INV-' . $txDate->format('Ymd') . '-' . str_pad((string)$seq, 4, '0', STR_PAD_LEFT);
        }

        return $invoiceNumber;
    }

    /**
     * Mengirim notifikasi stok kritis jika melebihi batas limit
     */
    private function processStockNotifications(array $barangKritisTransaksiIni, int $storeId): void
    {
        if (count($barangKritisTransaksiIni) === 0) {
            return;
        }

        $owner = User::where('store_id', $storeId)->where('role', 'owner')->first();
        if (!$owner) {
            return;
        }

        $hariIni = Carbon::now()->format('Y-m-d');
        $cacheKey = "notif_stok_instan_{$owner->id}_{$hariIni}";
        $jumlahNotifHariIni = (int) Cache::get($cacheKey, 0);

        if ($jumlahNotifHariIni < 3) {
            $owner->notify(new StokKritisNotification(collect($barangKritisTransaksiIni)));
            Cache::put($cacheKey, $jumlahNotifHariIni + 1, Carbon::now()->endOfDay());
        }
    }

    /**
     * Memproses token Midtrans
     */
    private function generateMidtransSnapToken(?string $paymentMethod, float $total, string $invoiceNumber): string
    {
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $enabledPayments = [];
        if ($paymentMethod === 'qris') {
            $enabledPayments = ['other_qris', 'gopay', 'shopeepay']; 
        } elseif ($paymentMethod === 'transfer') {
            $enabledPayments = ['bank_transfer'];
        }

        $params = [
            'transaction_details' => [
                'order_id'     => $invoiceNumber,
                'gross_amount' => $total,
            ],
            'customer_details' => [
                'first_name' => 'Bapak',
                'last_name'  => 'Fernando',
                'phone'      => 'UD. Purnama Sakti',
            ],
        ];

        if (!empty($enabledPayments)) {
            $params['enabled_payments'] = $enabledPayments;
        }

        return Snap::getSnapToken($params);
    }
}
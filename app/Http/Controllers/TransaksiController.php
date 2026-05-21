<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\Product;
use App\Models\CashFlow;
use App\Models\Debt;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Midtrans\Config;
use Midtrans\Snap;

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

        // Ambil data pelanggan untuk form hutang
        $customers = Customer::where('store_id', $storeId)->get();

        return view('transaksi.index', compact('sales', 'receipts', 'products', 'customers'));
    }

    public function store(Request $request)
    {
        // Validasi dasar
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

        // Validasi khusus untuk transaksi hutang
        if ($request->payment_status === 'debt') {
            $request->validate([
                'due_date'           => 'required|date',
                'is_new_customer'    => 'required|boolean',
            ]);

            // Jika hutang, harus ada customer (baru atau existing)
            if ($request->is_new_customer) {
                if (!$request->customer_name) {
                    return response()->json(['message' => 'Nama pelanggan baru wajib diisi untuk hutang.'], 422);
                }
            } else {
                if (!$request->customer_id) {
                    return response()->json(['message' => 'Pelanggan wajib dipilih untuk hutang.'], 422);
                }
            }
        }

        $storeId = auth()->user()->store_id;
        $txDate  = Carbon::parse($request->transaction_date);
        $total   = 0;
        $lines   = [];

        // 1. Kalkulasi Stok & Harga
        foreach ($request->items as $item) {
            $product = Product::with('batches')->findOrFail($item['product_id']);
            $batch   = $product->batches()->where('stock', '>', 0)->orderByDesc('id')->first();

            if (!$batch) {
                return response()->json(['message' => "Stok {$product->name} habis."], 422);
            }
            if ($batch->stock < $item['qty']) {
                return response()->json(['message' => "Stok {$product->name} tidak cukup (tersedia: {$batch->stock})."], 422);
            }

            $price    = (float) $batch->selling_price;
            $subtotal = $price * (int) $item['qty'];
            $total   += $subtotal;

            $lines[] = [
                'product_id'    => $product->id,
                'quantity'      => (int) $item['qty'],
                'price_at_sale' => $price,
                'subtotal'      => $subtotal,
                'batch'         => $batch,
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
            $customerId = $request->customer_id;
            if ($request->payment_status === 'debt' && $request->is_new_customer) {
                $newCustomer = Customer::create([
                    'store_id' => $storeId,
                    'name'     => $request->customer_name,
                    'phone'    => $request->customer_phone,
                    'address'  => $request->customer_address,
                ]);
                $customerId = $newCustomer->id;
            }

            // 3. Buat Invoice - gunakan ID terakhir dari tabel sales (lebih aman untuk menghindari tabrakan)
            $lastSale = Sale::where('store_id', $storeId)
                ->whereDate('created_at', $txDate->toDateString())
                ->latest('id')
                ->first();

            $seq = $lastSale ? (int) substr($lastSale->invoice_number, -4) + 1 : 1;
            $invoiceNumber = 'INV-' . $txDate->format('Ymd') . '-' . str_pad($seq, 4, '0', STR_PAD_LEFT);

            // Jika nomor invoice sudah ada (misal karena data manual atau reset), cari seq berikutnya
            while (Sale::where('invoice_number', $invoiceNumber)->exists()) {
                $seq++;
                $invoiceNumber = 'INV-' . $txDate->format('Ymd') . '-' . str_pad($seq, 4, '0', STR_PAD_LEFT);
            }

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

            // 4. Simpan Detail Transaksi
            foreach ($lines as $line) {
                SaleDetail::create([
                    'sale_id'       => $sale->id,
                    'product_id'    => $line['product_id'],
                    'quantity'      => $line['quantity'],
                    'price_at_sale' => $line['price_at_sale'],
                    'subtotal'      => $line['subtotal'],
                ]);
                $line['batch']->decrement('stock', $line['quantity']);
            }

            // 5. Catat Data Hutang ke Tabel Debts
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

            // 6. Catat CashFlow (Khusus Tunai Lunas)
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

            // 7. Konfigurasi Midtrans Snap
            $snapToken = null;
            if ($isMidtrans) {
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

                $snapToken = Snap::getSnapToken($params);

                $sale->update([
                    'midtrans_snap_token' => $snapToken
                ]);
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
}
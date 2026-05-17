<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\Product;
use App\Models\CashFlow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
                default    => ucfirst($s->payment_method),
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

        return view('transaksi.index', compact('sales', 'receipts', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'items'              => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.qty'        => 'required|integer|min:1',
            'payment_method'     => 'required|in:cash,qris,transfer',
            'payment_status'     => 'required|in:paid,debt',
            'transaction_date'   => 'required|date',
        ]);

        $storeId = auth()->user()->store_id;
        $txDate  = Carbon::parse($request->transaction_date);
        $total   = 0;
        $lines   = [];

        foreach ($request->items as $item) {
            $product = Product::with('batches')->findOrFail($item['product_id']);
            $batch   = $product->batches()->where('stock', '>', 0)->orderByDesc('id')->first();

            if (!$batch) {
                return back()->withErrors(['items' => "Stok {$product->name} habis."]);
            }
            if ($batch->stock < $item['qty']) {
                return back()->withErrors(['items' => "Stok {$product->name} tidak cukup (tersedia: {$batch->stock})."]);
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

        $seq = Sale::where('store_id', $storeId)->whereDate('created_at', $txDate->toDateString())->count() + 1;
        $invoiceNumber = 'INV-' . $txDate->format('Ymd') . '-' . str_pad($seq, 4, '0', STR_PAD_LEFT);

        $sale = Sale::create([
            'store_id'       => $storeId,
            'user_id'        => auth()->id(),
            'invoice_number' => $invoiceNumber,
            'total_price'    => $total,
            'payment_method' => $request->payment_method,
            'payment_status' => $request->payment_status,
        ]);

        // Override created_at dengan tanggal transaksi yang dipilih user
        DB::table('sales')->where('id', $sale->id)->update(['created_at' => $txDate]);
        $sale->created_at = $txDate;

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

        if ($request->payment_status === 'paid') {
            CashFlow::create([
                'store_id'     => $storeId,
                'type'         => 'in',
                'category'     => 'Penjualan',
                'amount'       => $total,
                'description'  => 'Penjualan ' . $invoiceNumber,
                'reference_id' => $sale->id,
            ]);
        }

        return redirect()->route('transaksi.index')->with('success', "Transaksi {$invoiceNumber} berhasil disimpan.");
    }

    public function destroy(Sale $transaksi)
    {
        abort_if($transaksi->store_id !== auth()->user()->store_id, 403);
        $transaksi->delete();
        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil dihapus.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductBatch;
use App\Models\Supplier;
use Illuminate\Http\Request;

class StokController extends Controller
{
    public function index(Request $request)
    {
        $storeId = auth()->user()->store_id;

        // Tabel utama: produk dengan total stok teragregasi
        $products = Product::where('store_id', $storeId)
            ->withSum('batches', 'stock')
            ->when($request->filled('search'), fn ($q) =>
                $q->where('name', 'like', "%{$request->search}%")
            )
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        // Stats kartu (dari semua produk tanpa pagination)
        $allStats = Product::where('store_id', $storeId)
            ->withSum('batches', 'stock')
            ->get();

        $totalStok = $allStats->sum(fn ($p) => $p->batches_sum_stock ?? 0);

        $menipis = $allStats->filter(fn ($p) =>
            ($p->batches_sum_stock ?? 0) > 0 && ($p->batches_sum_stock ?? 0) <= $p->min_stock
        )->count();

        $habis = $allStats->filter(fn ($p) =>
            ($p->batches_sum_stock ?? 0) == 0
        )->count();

        $productsList = Product::where('store_id', $storeId)->orderBy('name')->get();
        $suppliers    = Supplier::where('store_id', $storeId)->orderBy('name')->get();

        return view('stok.index', compact(
            'products', 'totalStok', 'menipis', 'habis', 'productsList', 'suppliers'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id'     => 'required|exists:products,id',
            'supplier_id'    => 'nullable|exists:suppliers,id',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price'  => 'required|numeric|min:0',
            'stock'          => 'required|integer|min:0',
            'expiry_date'    => 'nullable|date',
        ]);

        ProductBatch::create($validated);

        return back()->with('success', 'Stok berhasil ditambahkan.');
    }

    public function update(Request $request, ProductBatch $stok)
    {
        $validated = $request->validate([
            'product_id'     => 'required|exists:products,id',
            'supplier_id'    => 'nullable|exists:suppliers,id',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price'  => 'required|numeric|min:0',
            'stock'          => 'required|integer|min:0',
            'expiry_date'    => 'nullable|date',
        ]);

        $stok->update($validated);

        return back()->with('success', 'Stok berhasil diperbarui.');
    }

    public function destroy(ProductBatch $stok)
    {
        $stok->delete();

        return back()->with('success', 'Data stok berhasil dihapus.');
    }
}

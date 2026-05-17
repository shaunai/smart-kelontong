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
        $storeId    = auth()->user()->store_id;
        $productIds = Product::where('store_id', $storeId)->pluck('id');

        $batches = ProductBatch::whereIn('product_id', $productIds)
            ->with(['product', 'supplier'])
            ->when($request->filled('search'), fn ($q) =>
                $q->whereHas('product', fn ($q2) =>
                    $q2->where('name', 'like', "%{$request->search}%")
                )
            )
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        $totalStok = ProductBatch::whereIn('product_id', $productIds)->sum('stock');

        $productStoks = Product::where('store_id', $storeId)
            ->withSum('batches', 'stock')
            ->get();

        $menipis = $productStoks->filter(fn ($p) =>
            ($p->batches_sum_stock ?? 0) > 0 && ($p->batches_sum_stock ?? 0) <= $p->min_stock
        )->count();

        $habis = $productStoks->filter(fn ($p) =>
            ($p->batches_sum_stock ?? 0) == 0
        )->count();

        $products  = Product::where('store_id', $storeId)->orderBy('name')->get();
        $suppliers = Supplier::where('store_id', $storeId)->orderBy('name')->get();

        return view('stok.index', compact(
            'batches', 'totalStok', 'menipis', 'habis', 'products', 'suppliers'
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

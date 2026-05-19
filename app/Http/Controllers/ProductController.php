<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $storeId = auth()->user()->store_id;

        $products = Product::where('store_id', $storeId)
            ->with(['parent', 'batches' => fn ($q) => $q->with('supplier')->latest()])
            ->withSum('batches', 'stock')
            ->when($request->filled('search'), fn ($q) =>
                $q->where(fn ($q2) =>
                    $q2->where('name', 'like', "%{$request->search}%")
                       ->orWhere('sku', 'like', "%{$request->search}%")
                )
            )
            ->when($request->filled('category'), fn ($q) =>
                $q->where('category', $request->category)
            )
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        $parentProducts = Product::where('store_id', $storeId)
            ->whereNull('parent_id')
            ->orderBy('name')
            ->get();

        $categories = Product::where('store_id', $storeId)
            ->whereNotNull('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return view('produk.index', compact('products', 'parentProducts', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'sku'            => 'nullable|string|max:100',
            'category'       => 'nullable|string|max:100',
            'name'           => 'required|string|max:255',
            'unit'           => 'required|string|max:50',
            'conversion_qty' => 'required|integer|min:1',
            'min_stock'      => 'required|integer|min:0',
            'parent_id'      => 'nullable|exists:products,id',
        ]);

        $validated['store_id'] = auth()->user()->store_id;

        Product::create($validated);

        return back()->with('success', 'Produk berhasil ditambahkan.');
    }

    public function update(Request $request, Product $produk)
    {
        $validated = $request->validate([
            'sku'            => 'nullable|string|max:100',
            'category'       => 'nullable|string|max:100',
            'name'           => 'required|string|max:255',
            'unit'           => 'required|string|max:50',
            'conversion_qty' => 'required|integer|min:1',
            'min_stock'      => 'required|integer|min:0',
            'parent_id'      => 'nullable|exists:products,id',
        ]);

        $produk->update($validated);

        return back()->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $produk)
    {
        $produk->delete();

        return back()->with('success', 'Produk berhasil dihapus.');
    }
}

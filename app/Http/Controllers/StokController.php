<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductBatch;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Tambahan: Import DB facade untuk transaksi aman

class StokController extends Controller
{
    public function index(Request $request)
    {
        $storeId = auth()->user()->store_id;

        // Tabel utama: produk dengan opsi filter tipe (Semua, Grosir, Eceran)
        $products = Product::where('store_id', $storeId)
            ->withSum('batches', 'stock')
            ->when($request->filled('search'), fn ($q) =>
                $q->where('name', 'like', "%{$request->search}%")
            )
            // TAMBAHAN FILTER TIPE SATUAN
            ->when($request->query('type') === 'grosir', fn ($q) =>
                $q->whereNull('parent_id') // Produk induk tidak punya parent_id
            )
            ->when($request->query('type') === 'eceran', fn ($q) =>
                $q->whereNotNull('parent_id') // Produk eceran pasti punya parent_id
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

    // FUNGSI BARU: Fitur Bongkar Kemasan (Dus -> Pcs)
    public function bongkarStok(Request $request, $childProductId)
    {
        $childProduct = Product::findOrFail($childProductId);
        
        // Pastikan produk ini memang merupakan eceran dari produk lain
        if (!$childProduct->parent_id) {
            return back()->with('error', 'Produk ini tidak memiliki produk induk (kemasan besar).');
        }

        $parentProduct = Product::findOrFail($childProduct->parent_id);

        // 1. Cek total stok Parent (Dus/Karton)
        $parentStock = $parentProduct->batches()->sum('stock');
        if ($parentStock < 1) {
            return back()->with('error', 'Stok ' . $parentProduct->unit . ' sudah habis! Tidak bisa dibongkar.');
        }

        // 2. Gunakan DB Transaction agar data tidak korup (jika error, semua batal)
        DB::transaction(function () use ($childProduct, $parentProduct) {
            
            // Ambil batch Parent (Dus) yang stoknya masih ada (FIFO - First In First Out)
            $parentBatch = $parentProduct->batches()->where('stock', '>', 0)->orderBy('created_at', 'asc')->first();
            
            // Kurangi 1 dari stok Parent (Dus)
            $parentBatch->decrement('stock', 1);

            // Tambahkan stok Child (Pcs) ke batch baru
            // Modal per Pcs dihitung dari Modal 1 Dus dibagi jumlah isinya
            $childProduct->batches()->create([
                'stock'          => $childProduct->conversion_qty,
                'purchase_price' => $parentBatch->purchase_price / $childProduct->conversion_qty,
                
                // Ambil harga jual eceran dari master data produk, fallback ke 0 jika kosong
                'selling_price'  => $childProduct->selling_price ?? 0, 
                
                'supplier_id'    => $parentBatch->supplier_id,
                'expiry_date'    => $parentBatch->expiry_date, // Turunkan tgl expired dari kemasan dus ke eceran
            ]);
        });

        return back()->with('success', 'Berhasil membongkar 1 ' . $parentProduct->unit . ' menjadi ' . $childProduct->conversion_qty . ' ' . $childProduct->unit);
    }
}
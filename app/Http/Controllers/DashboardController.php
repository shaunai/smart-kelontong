<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Sale;       // Wajib ditambahkan
use App\Models\CashFlow;   // Wajib ditambahkan
use Carbon\Carbon;         // Wajib ditambahkan

class DashboardController extends Controller
{
    public function index()
    {
        $storeId = auth()->user()->store_id;
        $today = Carbon::today();

        // 1. --- LOGIKA STOK (Sama seperti kode Anda sebelumnya) ---
        $products = Product::where('store_id', $storeId)
            ->with('batches')
            ->get()
            ->map(function ($product) {
                $totalStock = $product->batches->sum('stock');
                $product->total_stock = $totalStock;
                return $product;
            });

        $stockAlerts = $products->filter(function ($product) {
            return $product->total_stock < (2 * $product->min_stock);
        })->sortBy('total_stock')->values();

        $criticalCount = $products->filter(function ($product) {
            return $product->total_stock < $product->min_stock;
        })->count();

        $totalStock = $products->sum('total_stock');

        // 2. --- LOGIKA KEUANGAN & TRANSAKSI HARI INI (Baru) ---
        // Pemasukan: Total dari transaksi penjualan yang lunas hari ini
        $pemasukanHariIni = Sale::where('store_id', $storeId)
            ->whereDate('created_at', $today)
            ->where('payment_status', 'paid')
            ->sum('total_price');

        // Pengeluaran: Total dari input kas bertipe 'out' hari ini
        $pengeluaranHariIni = CashFlow::where('store_id', $storeId)
            ->whereDate('created_at', $today)
            ->where('type', 'out') 
            ->sum('amount');

        // Total Transaksi: Jumlah nota/invoice yang berhasil hari ini
        $totalTransaksi = Sale::where('store_id', $storeId)
            ->whereDate('created_at', $today)
            ->where('payment_status', 'paid')
            ->count();

        // 3. --- LEMPAR KE VIEW ---
        return view('dashboard', compact(
            'stockAlerts', 
            'criticalCount', 
            'totalStock',
            'pemasukanHariIni',
            'pengeluaranHariIni',
            'totalTransaksi'
        ));
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\CashFlow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index()
    {
        // 1. Ambil ID Toko & Set Waktu Hari Ini
        $storeId = auth()->user()->store_id;
        $today = Carbon::today();

        // 2. Hitung Pemasukan Hari Ini (Total dari transaksi yang lunas)
        $pemasukanHariIni = Sale::where('store_id', $storeId)
            ->whereDate('created_at', $today)
            ->where('payment_status', 'paid')
            ->sum('total_price');

        // 3. Hitung Pengeluaran Hari Ini (Dari tabel CashFlow yang tipenya 'out' / keluar)
        // Sesuaikan 'out' dengan enum/value di database Anda
        $pengeluaranHariIni = CashFlow::where('store_id', $storeId)
            ->whereDate('created_at', $today)
            ->where('type', 'out') 
            ->sum('amount');

        // 4. Hitung Laba Bersih Hari Ini
        $labaHariIni = $pemasukanHariIni - $pengeluaranHariIni;

        // 5. Hitung Jumlah & Rata-rata Transaksi Hari Ini
        $salesToday = Sale::where('store_id', $storeId)
            ->whereDate('created_at', $today)
            ->where('payment_status', 'paid');
            
        $jumlahTransaksi = $salesToday->count();
        $rataRataTransaksi = $jumlahTransaksi > 0 ? $salesToday->avg('total_price') : 0;

        // 6. Ambil 1 Produk Terlaris HARI INI
        $produkTerlaris = SaleDetail::whereHas('sale', function ($query) use ($storeId, $today) {
                $query->where('store_id', $storeId)
                      ->whereDate('created_at', $today)
                      ->where('payment_status', 'paid');
            })
            ->select('product_id', DB::raw('SUM(quantity) as total_qty'))
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->with('product') // Mengambil data relasi tabel produk
            ->first();

        // 7. Ambil 5 Produk Terlaris MINGGU INI (7 Hari Terakhir)
        $startOfWeek = Carbon::now()->subDays(7)->startOfDay();
        $top5Minggu = SaleDetail::whereHas('sale', function ($query) use ($storeId, $startOfWeek) {
                $query->where('store_id', $storeId)
                      ->where('created_at', '>=', $startOfWeek)
                      ->where('payment_status', 'paid');
            })
            ->select('product_id', DB::raw('SUM(quantity) as total_qty'))
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->with('product')
            ->get();

        // 8. Siapkan Data untuk Chart.js (Grafik Penjualan per Jam - Format Pcs)
        // Mengambil jam dari field created_at (hanya mendukung database MySQL)
        $chartData = SaleDetail::whereHas('sale', function ($query) use ($storeId, $today) {
                $query->where('store_id', $storeId)
                      ->whereDate('created_at', $today)
                      ->where('payment_status', 'paid');
            })
            ->select(
                DB::raw('HOUR(created_at) as hour'),
                DB::raw('SUM(quantity) as total_pcs')
            )
            ->groupBy('hour')
            ->pluck('total_pcs', 'hour')
            ->toArray();

        $chartLabels = [];
        $chartValues = [];

        // Asumsi jam operasional toko: Jam 08:00 s/d 22:00
        // Jika toko Anda buka 24 jam, ubah menjadi for ($i = 0; $i <= 23; $i++)
        for ($i = 8; $i <= 22; $i++) {
            $chartLabels[] = str_pad($i, 2, '0', STR_PAD_LEFT) . ':00';
            
            // Jika di jam tersebut ada penjualan, masukkan datanya. Jika tidak, set 0.
            $chartValues[] = $chartData[$i] ?? 0;
        }

        // 9. Lempar semua variabel ke View Laporan
        return view('laporan.index', compact(
            'pemasukanHariIni',
            'pengeluaranHariIni',
            'labaHariIni',
            'rataRataTransaksi',
            'jumlahTransaksi',
            'produkTerlaris',
            'today',
            'chartLabels',
            'chartValues',
            'top5Minggu'
        ));
    }
}
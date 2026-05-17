<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\CashFlow;
use App\Exports\LaporanExport;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{
    public function index()
    {
        $storeId = auth()->user()->store_id;
        $today   = Carbon::today();
        $week    = [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()];

        $pemasukanHariIni = CashFlow::where('store_id', $storeId)
            ->where('type', 'in')
            ->whereDate('created_at', $today)
            ->sum('amount');

        $rataRataTransaksi = Sale::where('store_id', $storeId)
            ->whereDate('created_at', $today)
            ->avg('total_price') ?? 0;

        $jumlahTransaksi = Sale::where('store_id', $storeId)
            ->whereDate('created_at', $today)
            ->count();

        $produkTerlaris = SaleDetail::whereHas('sale', fn($q) =>
                $q->where('store_id', $storeId)->whereDate('created_at', $today)
            )
            ->select('product_id', DB::raw('SUM(quantity) as total_qty'))
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->with('product')
            ->first();

        $chartData = SaleDetail::whereHas('sale', fn($q) =>
                $q->where('store_id', $storeId)->whereDate('created_at', $today)
            )
            ->select('product_id', DB::raw('SUM(quantity) as total_qty'))
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->limit(7)
            ->with('product')
            ->get();

        $chartLabels = $chartData->pluck('product.name')->toArray();
        $chartValues = $chartData->pluck('total_qty')->toArray();

        $top5Minggu = SaleDetail::whereHas('sale', fn($q) =>
                $q->where('store_id', $storeId)
                  ->whereBetween('created_at', $week)
            )
            ->select('product_id', DB::raw('SUM(quantity) as total_qty'))
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->with('product')
            ->get();

        return view('laporan.index', compact(
            'pemasukanHariIni',
            'rataRataTransaksi',
            'jumlahTransaksi',
            'produkTerlaris',
            'chartLabels',
            'chartValues',
            'top5Minggu',
            'today',
        ));
    }

    public function export()
    {
        $storeId  = auth()->user()->store_id;
        $filename = 'laporan-penjualan-' . now()->format('Ymd-His') . '.xlsx';

        return Excel::download(new LaporanExport($storeId), $filename);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class DashboardController extends Controller
{
    public function index()
    {
        $storeId = auth()->user()->store_id;

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

        return view('dashboard', compact('stockAlerts', 'criticalCount', 'totalStock'));
    }
}

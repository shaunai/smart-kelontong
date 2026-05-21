<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StokController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CashFlowController;
use App\Http\Controllers\HutangController;
use App\Http\Controllers\Api\MidtransCallbackController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');
    
Route::post('/midtrans-callback', [MidtransCallbackController::class, 'callback']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('produk', ProductController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::resource('transaksi', TransaksiController::class)->only(['index', 'store', 'destroy']);
    Route::resource('stok', StokController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/export', [LaporanController::class, 'export'])->name('laporan.export');
    Route::resource('supplier', SupplierController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::post('/kas', [CashFlowController::class, 'store'])->name('kas.store');
    Route::get('/hutang', [HutangController::class, 'index'])->name('hutang.index');
    Route::post('/hutang/{hutang}/bayar', [HutangController::class, 'pay'])->name('hutang.pay');
});
require __DIR__.'/auth.php';

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
use App\Http\Controllers\TokoSettingController;
use App\Http\Middleware\RoleMiddleware; // <-- Tambahkan import middleware di sini

Route::get('/', function () {
    return view('welcome');
});

Route::post('/midtrans-callback', [MidtransCallbackController::class, 'callback']);

// ======== GRUP 1: AKSES UMUM (OWNER & CASHIER BISA MASUK) ========
Route::middleware(['auth'])->group(function () {
    
    // Dashboard (Logika pembatasan metrik diatur di view/controller)
    Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('verified')->name('dashboard');

    // Profile Bawaan
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Transaksi (Kasir & Owner sama-sama punya akses penuh)
    Route::resource('transaksi', TransaksiController::class)->only(['index', 'store', 'destroy']);
    
    // Hutang & Kasbon (Kasir diizinkan input dan lihat)
    Route::post('/kas', [CashFlowController::class, 'store'])->name('kas.store');
    Route::get('/hutang', [HutangController::class, 'index'])->name('hutang.index');
    Route::post('/hutang/{hutang}/bayar', [HutangController::class, 'pay'])->name('hutang.pay');

    // Stok (KHUSUS READ-ONLY: Kasir hanya bisa melihat tabel stok & alert)
    Route::get('/stok', [StokController::class, 'index'])->name('stok.index');


    // ======== GRUP 2: AKSES KHUSUS OWNER ========
    // Semua route di dalam grup ini akan dicegat jika rolenya bukan 'owner'
    Route::middleware([RoleMiddleware::class . ':owner'])->group(function () {
        
        // Manajemen Produk (Penuh)
        Route::resource('produk', ProductController::class)->only(['index', 'store', 'update', 'destroy']);
        
        // Manajemen Stok Lanjutan (Hanya Owner yang bisa tambah, edit, dan hapus)
        Route::resource('stok', StokController::class)->only(['store', 'update', 'destroy']);
        // Route khusus untuk fitur bongkar kemasan (Dus -> Pcs)
        Route::post('/stok/{child_id}/bongkar', [StokController::class, 'bongkarStok'])->name('stok.bongkar');

        // Manajemen Supplier
        Route::resource('supplier', SupplierController::class)->only(['index', 'store', 'update', 'destroy']);

        // Laporan Keuangan (Lihat & Ekspor)
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/export', [LaporanController::class, 'export'])->name('laporan.export');

        // Pengaturan Toko
        Route::get('/pengaturan-toko', [TokoSettingController::class, 'edit'])->name('toko.settings.edit');
        Route::put('/pengaturan-toko', [TokoSettingController::class, 'update'])->name('toko.settings.update');
        
        //Manajemen Pengguna (Kasir)
        Route::post('/pengaturan-toko/kasir', [TokoSettingController::class, 'storeKasir'])->name('toko.kasir.store');
        Route::delete('/pengaturan-toko/kasir/{id}', [TokoSettingController::class, 'destroyKasir'])->name('toko.kasir.destroy');
    });
});

require __DIR__.'/auth.php';
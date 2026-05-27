<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Models\User;
use App\Notifications\StokKritisNotification;

class CekStokKritis extends Command
{
    protected $signature = 'stok:cek-kritis';
    protected $description = 'Kirim rekap email stok kritis harian ke owner';

    public function handle()
    {
        $batasKritis = 5;

        // Ambil produk dan total stoknya
        $semuaProduk = Product::withSum('batches', 'stock')->get();

        // Saring produk yang stoknya 5 atau kurang
        $produkKritis = $semuaProduk->filter(function ($produk) use ($batasKritis) {
            return ($produk->batches_sum_stock ?? 0) <= $batasKritis;
        });

        if ($produkKritis->isNotEmpty()) {
            // Kelompokkan per toko agar email dikirim ke owner masing-masing
            foreach ($produkKritis->groupBy('store_id') as $storeId => $daftarProduk) {
                $owner = User::where('store_id', $storeId)->where('role', 'owner')->first();
                if ($owner) {
                    $owner->notify(new StokKritisNotification($daftarProduk));
                }
            }
        }
    }
}
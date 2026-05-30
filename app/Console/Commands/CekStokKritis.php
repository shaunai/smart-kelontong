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
        $this->info('Checking for critical stock...');

        try {
            // Ambil produk dan total stoknya
            $semuaProduk = Product::withSum('batches', 'stock')->get();
            $this->info('Total products checked: ' . $semuaProduk->count());

            // Saring produk yang stoknya menipis atau habis sesuai min_stock setiap produk
            $produkKritis = $semuaProduk->filter(function ($produk) {
                $threshold = $produk->min_stock ?? 5;
                return ($produk->batches_sum_stock ?? 0) <= $threshold;
            });

            $this->info('Critical stock products found: ' . $produkKritis->count());

            if ($produkKritis->isNotEmpty()) {
                // Kelompokkan per toko agar email dikirim ke owner masing-masing
                foreach ($produkKritis->groupBy('store_id') as $storeId => $daftarProduk) {
                    $owner = User::where('store_id', $storeId)->where('role', 'owner')->first();
                    if ($owner) {
                        $this->info('Sending notification to: ' . $owner->name . ' (' . $owner->email . ')');

                        $plainProduk = $daftarProduk->sortBy('batches_sum_stock')->map(fn ($produk) => (object) [
                            'name' => $produk->name,
                            'unit' => $produk->unit,
                            'batches_sum_stock' => $produk->batches_sum_stock ?? 0,
                            'min_stock' => $produk->min_stock ?? 5,
                        ]);

                        $owner->notify(new StokKritisNotification($plainProduk));
                        $this->info('✓ Notification sent to ' . $owner->email);
                    } else {
                        $this->warn('No owner found for store_id: ' . $storeId);
                    }
                }
            } else {
                $this->info('No critical stock found.');
            }
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
            $this->error($e->getTraceAsString());
        }
    }
}
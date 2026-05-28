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
        $this->info('Checking for critical stock...');

        try {
            // Ambil produk dan total stoknya
            $semuaProduk = Product::withSum('batches', 'stock')->get();
            $this->info('Total products checked: ' . $semuaProduk->count());

            // Saring produk yang stoknya 5 atau kurang
            $produkKritis = $semuaProduk->filter(function ($produk) use ($batasKritis) {
                return ($produk->batches_sum_stock ?? 0) <= $batasKritis;
            });

            $this->info('Critical stock products found: ' . $produkKritis->count());

            if ($produkKritis->isNotEmpty()) {
                // Kelompokkan per toko agar email dikirim ke owner masing-masing
                foreach ($produkKritis->groupBy('store_id') as $storeId => $daftarProduk) {
                    $owner = User::where('store_id', $storeId)->where('role', 'owner')->first();
                    if ($owner) {
                        $this->info('Sending notification to: ' . $owner->name . ' (' . $owner->email . ')');

                        $plainProduk = $daftarProduk->map(fn ($produk) => (object) [
                            'name' => $produk->name,
                            'unit' => $produk->unit,
                            'batches_sum_stock' => $produk->batches_sum_stock ?? 0,
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
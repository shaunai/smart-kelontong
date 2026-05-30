<?php

namespace App\Console\Commands;

use App\Models\ProductBatch;
use App\Models\User;
use App\Notifications\ProdukKadaluwarsaNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CekProdukKadaluwarsa extends Command
{
    protected $signature = 'produk:cek-kadaluwarsa';
    protected $description = 'Kirim notifikasi untuk produk yang akan kadaluwarsa dalam 30 hari ke depan.';

    public function handle()
    {
        $batasTanggal = Carbon::now()->addDays(30)->endOfDay();
        $this->info('Mengecek batch produk yang akan kadaluwarsa sampai ' . $batasTanggal->toDateString() . '...');

        try {
            $batchKadaluwarsa = ProductBatch::with('product')
                ->whereNotNull('expiry_date')
                ->whereDate('expiry_date', '<=', $batasTanggal)
                ->whereHas('product', fn ($query) => $query->whereNotNull('store_id'))
                ->get();

            $this->info('Batch kadaluwarsa ditemukan: ' . $batchKadaluwarsa->count());

            if ($batchKadaluwarsa->isEmpty()) {
                $this->info('Tidak ada batch produk yang akan kadaluwarsa dalam 30 hari ke depan.');
                return 0;
            }

            foreach ($batchKadaluwarsa->groupBy(fn ($batch) => $batch->product->store_id) as $storeId => $batches) {
                $owner = User::where('store_id', $storeId)->where('role', 'owner')->first();

                if (! $owner) {
                    $this->warn('Owner tidak ditemukan untuk store_id: ' . $storeId);
                    continue;
                }

                $this->info('Mengirim notifikasi ke ' . $owner->email . ' untuk store_id ' . $storeId);
                $owner->notify(new ProdukKadaluwarsaNotification($batches));
            }

            $this->info('Selesai mengirim notifikasi produk kadaluwarsa.');
            return 0;
        } catch (\Exception $e) {
            $this->error('Terjadi kesalahan: ' . $e->getMessage());
            return 1;
        }
    }
}

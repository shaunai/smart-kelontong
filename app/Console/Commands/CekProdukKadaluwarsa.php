<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ProductBatch;
use App\Models\User;
use App\Notifications\ProdukKadaluwarsaNotification;
use Carbon\Carbon;

class CekProdukKadaluwarsa extends Command
{
    protected $signature = 'produk:cek-kadaluwarsa';
    protected $description = 'Kirim rekap email produk mendekati atau sudah kadaluwarsa ke owner';

    public function handle()
    {
        $batasHari = 30;
        $batasWaktu = Carbon::now()->addDays($batasHari)->toDateString();

        // Ambil batch yang punya expiry_date dan stok > 0, exp <= 30 hari dari sekarang
        $batchKadaluwarsa = ProductBatch::with('product')
            ->whereNotNull('expiry_date')
            ->where('stock', '>', 0)
            ->whereDate('expiry_date', '<=', $batasWaktu)
            ->orderBy('expiry_date', 'asc')
            ->get();

        if ($batchKadaluwarsa->isEmpty()) {
            $this->info('Tidak ada produk yang mendekati kadaluwarsa.');
            return;
        }

        // Kelompokkan per toko via relasi product->store_id
        $perToko = $batchKadaluwarsa->groupBy(fn($batch) => $batch->product->store_id ?? null);

        foreach ($perToko as $storeId => $daftarBatch) {
            if (!$storeId) continue;

            $owner = User::where('store_id', $storeId)->where('role', 'owner')->first();
            if ($owner) {
                $owner->notify(new ProdukKadaluwarsaNotification($daftarBatch));
                $this->info("Email kadaluwarsa terkirim ke: {$owner->email} ({$daftarBatch->count()} batch)");
            }
        }
    }
}

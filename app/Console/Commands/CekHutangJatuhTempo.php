<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Debt;
use App\Models\User;
use App\Notifications\HutangJatuhTempoNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB; // Tambahkan DB facade untuk query nama toko

class CekHutangJatuhTempo extends Command
{
    protected $signature = 'hutang:cek-jatuh-tempo';
    protected $description = 'Kirim rekap email piutang jatuh tempo ke owner';

    public function handle()
    {
        $batasWaktu = Carbon::now()->addDays(3)->toDateString();

        $hutangMendesak = Debt::with('customer')
            ->where('status', 'unpaid')
            ->whereDate('due_date', '<=', $batasWaktu)
            ->get();

        if ($hutangMendesak->isNotEmpty()) {
            foreach ($hutangMendesak->groupBy('store_id') as $storeId => $daftarHutang) {
                // Cari owner dari toko tersebut
                $owner = User::where('store_id', $storeId)->where('role', 'owner')->first();
                
                if ($owner) {
                    // Ambil nama toko dari database dengan aman
                    $namaToko = DB::table('stores')->where('id', $storeId)->value('name') ?? 'Sistem Smart-Klontong';

                    // Lempar $daftarHutang dan $namaToko ke dalam Notification
                    $owner->notify(new HutangJatuhTempoNotification($daftarHutang, $namaToko));
                }
            }
        }
    }
}
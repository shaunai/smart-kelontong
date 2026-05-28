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
        $this->info('Checking for debts due within 3 days...');

        try {
            $hutangMendesak = Debt::with('customer')
                ->where('status', 'unpaid')
                ->whereDate('due_date', '<=', $batasWaktu)
                ->get();

            $this->info('Debts found: ' . $hutangMendesak->count());

            if ($hutangMendesak->isNotEmpty()) {
                foreach ($hutangMendesak->groupBy('store_id') as $storeId => $daftarHutang) {
                    // Cari owner dari toko tersebut
                    $owner = User::where('store_id', $storeId)->where('role', 'owner')->first();
                    
                    if ($owner) {
                        // Ambil nama toko dari database dengan aman
                        $namaToko = DB::table('stores')->where('id', $storeId)->value('name') ?? 'Sistem Smart-Klontong';

                        $this->info('Sending notification to: ' . $owner->name . ' (' . $owner->email . ')');
                        // Lempar $daftarHutang dan $namaToko ke dalam Notification
                        $owner->notify(new HutangJatuhTempoNotification($daftarHutang, $namaToko));
                        $this->info('✓ Notification sent to ' . $owner->email);
                    } else {
                        $this->warn('No owner found for store_id: ' . $storeId);
                    }
                }
            } else {
                $this->info('No debts due within 3 days.');
            }
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
            $this->error($e->getTraceAsString());
        }
    }
}
<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Jadwal Pengingat Stok (dikirim setiap jam 07:30 pagi sebelum toko buka)
Schedule::command('stok:cek-kritis')->dailyAt('07:30');

// Jadwal Pengingat Piutang (dikirim setiap jam 08:00 pagi)
Schedule::command('hutang:cek-jatuh-tempo')->dailyAt('08:00');

// Jadwal Pengingat Kadaluwarsa (dikirim setiap hari Senin jam 08:30)
Schedule::command('produk:cek-kadaluwarsa')->weeklyOn(1, '08:30');
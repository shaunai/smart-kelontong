<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Carbon\Carbon;

class ProdukKadaluwarsaNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $daftarKadaluwarsa;

    public function __construct($daftarKadaluwarsa)
    {
        $this->daftarKadaluwarsa = $daftarKadaluwarsa;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $jumlah = $this->daftarKadaluwarsa->count();

        $mail = (new MailMessage)
            ->subject("Peringatan: $jumlah Batch Produk Mendekati Kadaluwarsa!")
            ->greeting('Halo, ' . $notifiable->name)
            ->line("Sistem mendeteksi ada $jumlah batch produk yang akan kadaluwarsa dalam 30 hari ke depan.")
            ->line('Berikut rinciannya:');

        $batasTampil = 10;
        foreach ($this->daftarKadaluwarsa->take($batasTampil) as $batch) {
            $namaProduk = $batch->product->name ?? 'Produk Tidak Diketahui';
            $tglExp     = Carbon::parse($batch->expiry_date)->format('d M Y');
            $sisaHari   = Carbon::now()->diffInDays($batch->expiry_date, false);
            $sisaStok   = $batch->stock;

            $keterangan = $sisaHari <= 0
                ? "**SUDAH KADALUWARSA**"
                : "sisa $sisaHari hari";

            $mail->line("- **{$namaProduk}** | Exp: {$tglExp} ({$keterangan}) | Stok: {$sisaStok}");
        }

        if ($jumlah > $batasTampil) {
            $sisaTampil = $jumlah - $batasTampil;
            $mail->line("*...dan {$sisaTampil} batch lainnya.*");
        }

        return $mail
            ->action('Buka Manajemen Stok', url('/stok'))
            ->line('Harap segera cek dan pisahkan produk yang sudah atau hampir kadaluwarsa.');
    }
}

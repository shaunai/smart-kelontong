<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Carbon\Carbon;

class ProdukKadaluwarsaNotification extends Notification
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
            ->subject("Peringatan: $jumlah batch produk akan kadaluwarsa")
            ->greeting('Halo ' . $notifiable->name . ',')
            ->line("Sistem mendeteksi ada $jumlah batch produk yang akan kadaluwarsa dalam 30 hari ke depan.")
            ->line('Mohon segera cek stok dan pisahkan produk yang sudah mendekati tanggal kadaluwarsa.');

        foreach ($this->daftarKadaluwarsa->take(10) as $batch) {
            $namaProduk = $batch->product->name ?? 'Produk tidak diketahui';
            $tglExp = Carbon::parse($batch->expiry_date)->translatedFormat('d F Y');
            $sisaHari = Carbon::now()->diffInDays($batch->expiry_date, false);
            $status = $sisaHari <= 0 ? 'SUDAH KADALUWARSA' : "sisa $sisaHari hari";
            $mail->line("- {$namaProduk} | Exp: {$tglExp} | {$status} | Stok: {$batch->stock}");
        }

        if ($jumlah > 10) {
            $mail->line('...dan ' . ($jumlah - 10) . ' batch lainnya.');
        }

        return $mail
            ->action('Buka Pengelolaan Produk', url('/produk'))
            ->line('Terima kasih telah menggunakan Smart Klontong.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'count' => $this->daftarKadaluwarsa->count(),
        ];
    }
}

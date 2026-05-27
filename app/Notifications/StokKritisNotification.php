<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class StokKritisNotification extends Notification implements ShouldQueue
{
    use Queueable;


    protected $daftarStokKritis;

    public function __construct($daftarStokKritis)
    {
        $this->daftarStokKritis = $daftarStokKritis;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $jumlahBarang = $this->daftarStokKritis->count();

        $mail = (new MailMessage)
            ->subject("Peringatan: Ada $jumlahBarang Barang Berstatus Stok Kritis!")
            ->greeting('Halo, ' . $notifiable->name)
            ->line("Sistem mendeteksi ada $jumlahBarang barang di toko yang stoknya sudah menyentuh batas kritis (5 unit atau kurang).")
            ->line('Berikut rinciannya:');

        $batasTampil = 10;
        foreach ($this->daftarStokKritis->take($batasTampil) as $produk) {
            $sisaStok = $produk->batches_sum_stock ?? 0;
            $mail->line("- **{$produk->name}** | Sisa: {$sisaStok} {$produk->unit}");
        }

        if ($jumlahBarang > $batasTampil) {
            $sisaTampil = $jumlahBarang - $batasTampil;
            $mail->line("*...dan {$sisaTampil} barang lainnya.*");
        }

        return $mail
            ->action('Buka Manajemen Stok', url('/stok'))
            ->line('Harap segera lakukan pemesanan ulang (restock) ke supplier.');
    }
}
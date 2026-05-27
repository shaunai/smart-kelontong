<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;
use Carbon\Carbon;

class HutangJatuhTempoNotification extends Notification
{
    use Queueable;

    protected $hutangMendesak;
    protected $namaToko;

    public function __construct($hutangMendesak, $namaToko)
    {
        $this->hutangMendesak = $hutangMendesak;
        $this->namaToko = $namaToko;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $jumlahHutang = $this->hutangMendesak->count();

        $mail = (new MailMessage)
            ->subject("Peringatan: $jumlahHutang Piutang Segera Jatuh Tempo!")
            ->greeting('Halo, ' . $notifiable->name)
            ->line("Sistem mendeteksi ada $jumlahHutang tagihan pelanggan yang akan jatuh tempo dalam waktu 3 hari ke depan (atau kurang).")
            ->line('Berikut rinciannya:');

        $batasTampil = 5;
        foreach ($this->hutangMendesak->take($batasTampil) as $debt) {
            $namaPelanggan = $debt->customer->name ?? 'Tanpa Nama';
            $tglTempo = Carbon::parse($debt->due_date)->format('d M Y');
            $sisaHutang = 'Rp ' . number_format($debt->remaining_balance, 0, ',', '.');
            
            // Cetak teks rincian hutang
            $mail->line(new HtmlString("&#8226; <strong>{$namaPelanggan}</strong> | Tempo: {$tglTempo} | Sisa: {$sisaHutang}"));
            
            // Cek apakah ada nomor telepon
            if (!empty($debt->customer->phone)) {
                $cleanPhone = preg_replace('/[^0-9]/', '', $debt->customer->phone);
                
                if (str_starts_with($cleanPhone, '0')) {
                    $cleanPhone = '62' . substr($cleanPhone, 1);
                }

                $pesan = urlencode("Halo {$namaPelanggan}, dari {$this->namaToko}. Kami menginformasikan bahwa tagihan sebesar {$sisaHutang} akan jatuh tempo pada {$tglTempo}. Mohon persiapkan pelunasannya ya. Terima kasih!");

                // PERBAIKAN: HTML ditulis dalam satu baris penuh tanpa Enter/Indentasi agar tidak dibaca sebagai teks Markdown
                $waButton = '<div style="text-align: center; padding-top: 8px; padding-bottom: 16px;"><a href="https://wa.me/' . $cleanPhone . '?text=' . $pesan . '" style="background-color: #25D366; color: #ffffff; padding: 6px 16px; border-radius: 4px; text-decoration: none; font-size: 13px; font-weight: bold; display: inline-block;">💬 Hubungi WA</a></div>';

                $mail->line(new HtmlString($waButton));
            } else {
                $mail->line(new HtmlString('<div style="margin-bottom: 16px;"></div>'));
            }
        }

        if ($jumlahHutang > $batasTampil) {
            $sisaTampil = $jumlahHutang - $batasTampil;
            $mail->line("*...dan {$sisaTampil} tagihan lainnya.*");
        }

        return $mail
            ->action('Buka Manajemen Piutang', url('/hutang'))
            ->line('Harap segera persiapkan tindak lanjut penagihan.');
    }
}
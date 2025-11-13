<?php

namespace App\Notifications;

use App\Models\Sanksi;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SanksiDijadwalkanNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $sanksi;

    public function __construct(Sanksi $sanksi)
    {
        $this->sanksi = $sanksi;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Notifikasi Sanksi Dijadwalkan')
            ->greeting('Halo ' . $notifiable->nama)
            ->line('Sanksi telah dijadwalkan untuk siswa ' . $this->sanksi->pelanggaran->siswa->nama_siswa)
            ->line('Nama Sanksi: ' . $this->sanksi->nama_sanksi)
            ->line('Tanggal Mulai: ' . $this->sanksi->tanggal_mulai)
            ->line('Tanggal Selesai: ' . $this->sanksi->tanggal_selesai)
            ->line('Status: ' . $this->sanksi->status_sanksi)
            ->action('Lihat Detail', url('/pelanggaran'))
            ->line('Harap segera menindaklanjuti.');
    }
}

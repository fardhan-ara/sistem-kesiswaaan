<?php

namespace App\Notifications;

use App\Models\Pelanggaran;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PelanggaranBaruNotification extends Notification
{
    use Queueable;

    protected $pelanggaran;

    public function __construct(Pelanggaran $pelanggaran)
    {
        $this->pelanggaran = $pelanggaran;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Notifikasi Pelanggaran Baru')
            ->greeting('Halo ' . $notifiable->nama)
            ->line('Siswa ' . $this->pelanggaran->siswa->nama_siswa . ' telah melakukan pelanggaran.')
            ->line('Jenis Pelanggaran: ' . $this->pelanggaran->jenisPelanggaran->nama_pelanggaran)
            ->line('Poin: ' . $this->pelanggaran->poin)
            ->line('Keterangan: ' . ($this->pelanggaran->keterangan ?? '-'))
            ->action('Lihat Detail', url('/pelanggaran'))
            ->line('Terima kasih.');
    }
}

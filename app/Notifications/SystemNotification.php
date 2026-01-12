<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class SystemNotification extends Notification
{
    use Queueable;

    protected $title;
    protected $message;
    protected $type; // info, success, warning, danger
    protected $actionUrl;
    protected $actionText;

    /**
     * Buat instance notifikasi baru
     */
    public function __construct($title, $message, $type = 'info', $actionUrl = null, $actionText = null)
    {
        $this->title = $title;
        $this->message = $message;
        $this->type = $type;
        $this->actionUrl = $actionUrl;
        $this->actionText = $actionText;
    }

    /**
     * Channel notifikasi: database (disimpan di DB)
     */
    public function via($notifiable): array
    {
        return ['database'];
    }

    /**
     * Data yang disimpan ke database dalam format JSON
     */
    public function toArray($notifiable): array
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
            'type' => $this->type,
            'action_url' => $this->actionUrl,
            'action_text' => $this->actionText,
            'created_at' => now()->toDateTimeString()
        ];
    }
}

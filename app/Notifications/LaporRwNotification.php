<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class LaporRwNotification extends Notification
{
    use Queueable;

    public $info;

    /**
     * Create a new notification instance.
     */
    public function __construct($info)
    {
        $this->info         = $info;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'link' => url('/laporrw/'.$this->info->slug),
            'judul' => $this->info->judul,
            'deskripsi' => Str::limit(strip_tags($this->info->deskripsi), 100, '...'),
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'link' => url('/laporrw/'.$this->info->slug),
            'judul' => $this->info->judul,
            'deskripsi' => Str::limit(strip_tags($this->info->deskripsi), 100, '...'),
        ];
    }
}

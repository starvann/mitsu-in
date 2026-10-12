<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewUserRegistered extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public User $newUser) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => $this->newUser->role . '_registered',
            'user_id' => $this->newUser->id,
            'nama' => $this->newUser->nama,
            'email' => $this->newUser->email,
            'no_hp' => $this->newUser->no_hp,
            'message' => "User baru mendaftar: " . $this->newUser->nama,
            'url' => route('view_user', $this->newUser->id)
        ];
    }
}

<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentProofUpdated extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public User $user) {}

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
            'type' => 'payment_proof_updated',
            'user_id' => $this->user->id,
            'nama' => $this->user->nama,
            'email' => $this->user->email,
            'no_hp' => $this->user->no_hp,
            'message' => "User memperbarui bukti pembayaran: " . $this->user->nama,
            'url' => route('view_payment', $this->user->id)
        ];
    }
}

<?php

namespace App\Listeners;

use App\Events\PaymentProofUpdated;
use App\Models\User;
use App\Notifications\PaymentProofUpdated as NotificationsPaymentProofUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class NotifyAdminOfUpdatedPaymentProof
{
    /**
     * Create the event listener.
     */
    public function __construct() {}

    /**
     * Handle the event.
     */
    public function handle(PaymentProofUpdated $event): void
    {
        $admins = User::where('role', 'admn')->get();
        Notification::send($admins, new NotificationsPaymentProofUpdated($event->user));
    }
}

<?php

namespace App\Listeners;

use App\Events\PaymentStatusChanged;
use App\Notifications\PaymentRejectedNotification;
use App\Notifications\PaymentVerifiedNotification;

class SendPaymentStatusNotification
{
    public function handle(PaymentStatusChanged $event): void
    {
        $payment = $event->payment;
        $payment->load('order.user');

        $user = $payment->order->user;
        if (!$user) {
            return;
        }

        // Kirim notifikasi sesuai status
        if ($event->newStatus === 'paid') {
            $user->notify(new PaymentVerifiedNotification($payment));
        } elseif ($event->newStatus === 'failed') {
            $user->notify(new PaymentRejectedNotification($payment));
        }

        // Catatan: activity log dicatat di controller sebelum event di-fire,
        // bukan di sini, agar tidak terjadi duplikasi log.
    }
}

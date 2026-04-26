<?php

namespace App\Listeners;

use App\Events\OrderStatusChanged;
use App\Notifications\OrderStatusChangedNotification;

class SendOrderStatusNotification
{
    public function handle(OrderStatusChanged $event): void
    {
        $order = $event->order;
        $order->load('user');

        // Kirim notifikasi ke customer
        if ($order->user) {
            $order->user->notify(new OrderStatusChangedNotification(
                $order,
                $event->oldStatus,
                $event->newStatus
            ));
        }

        // Catatan: activity log dicatat di controller sebelum event di-fire,
        // bukan di sini, agar tidak terjadi duplikasi log.
    }
}

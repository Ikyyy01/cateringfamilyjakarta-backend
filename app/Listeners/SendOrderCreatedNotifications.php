<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Models\User;
use App\Notifications\NewOrderAdminNotification;
use App\Notifications\OrderCreatedNotification;

class SendOrderCreatedNotifications
{
    public function handle(OrderCreated $event): void
    {
        $order = $event->order;
        $order->load('user');

        // Kirim notifikasi ke customer
        if ($order->user) {
            $order->user->notify(new OrderCreatedNotification($order));
        }

        // Kirim notifikasi ke semua admin
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new NewOrderAdminNotification($order));
        }

        // Catatan: activity log dicatat di controller sebelum event di-fire,
        // bukan di sini, agar tidak terjadi duplikasi log.
    }
}

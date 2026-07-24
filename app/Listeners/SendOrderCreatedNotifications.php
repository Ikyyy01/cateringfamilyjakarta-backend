<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Models\User;
use App\Notifications\NewOrderAdminNotification;
use App\Notifications\OrderCreatedNotification;
use App\Services\FonnteService;

class SendOrderCreatedNotifications
{
    public function __construct(
        private FonnteService $fonnteService
    ) {}

    public function handle(OrderCreated $event): void
    {
        $order = $event->order;
        $order->load('user');

        if ($order->user) {
            $order->user->notify(new OrderCreatedNotification($order));
        }

        $this->fonnteService->sendOrderCreatedMessage($order);

        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new NewOrderAdminNotification($order));
        }
    }
}

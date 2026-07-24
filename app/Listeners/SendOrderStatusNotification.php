<?php

namespace App\Listeners;

use App\Events\OrderStatusChanged;
use App\Notifications\OrderStatusChangedNotification;
use App\Services\FonnteService;

class SendOrderStatusNotification
{
    public function __construct(
        private FonnteService $fonnteService
    ) {}

    public function handle(OrderStatusChanged $event): void
    {
        $order = $event->order;
        $order->load('user');

        if ($order->user) {
            $order->user->notify(new OrderStatusChangedNotification(
                $order,
                $event->oldStatus,
                $event->newStatus
            ));
        }

        $this->fonnteService->sendOrderStatusMessage($order);
    }
}

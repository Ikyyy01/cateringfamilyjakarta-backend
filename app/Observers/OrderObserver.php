<?php

namespace App\Observers;

use App\Models\ActivityLog;
use App\Models\Order;

class OrderObserver
{
    public function created(Order $order): void
    {
        ActivityLog::log(
            action:      'created',
            model:       $order,
            description: "Pesanan baru #{$order->order_number} dibuat oleh {$this->buyer($order)}.",
            newValues:   $order->only(['order_number', 'status', 'total_price', 'event_date']),
        );
    }

    public function updated(Order $order): void
    {
        $dirty = $order->getDirty();
        if (empty($dirty)) return;

        $old = array_intersect_key($order->getOriginal(), $dirty);

        // Pesan khusus untuk perubahan status
        $description = isset($dirty['status'])
            ? "Status pesanan #{$order->order_number} berubah dari [{$old['status']}] ke [{$dirty['status']}]."
            : "Pesanan #{$order->order_number} diperbarui.";

        ActivityLog::log(
            action:      'updated',
            model:       $order,
            description: $description,
            oldValues:   $old,
            newValues:   $dirty,
        );
    }

    public function deleted(Order $order): void
    {
        ActivityLog::log(
            action:      'deleted',
            model:       $order,
            description: "Pesanan #{$order->order_number} dihapus.",
        );
    }

    private function buyer(Order $order): string
    {
        return $order->nama_pemesan ?? $order->user?->name ?? 'Guest';
    }
}

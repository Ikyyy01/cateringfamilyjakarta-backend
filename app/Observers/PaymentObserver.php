<?php

namespace App\Observers;

use App\Models\ActivityLog;
use App\Models\Payment;

class PaymentObserver
{
    public function created(Payment $payment): void
    {
        ActivityLog::log(
            action:      'created',
            model:       $payment,
            description: "Pembayaran dibuat untuk pesanan #{$payment->order?->order_number} — metode: {$payment->method}.",
            newValues:   $payment->only(['order_id', 'amount', 'method', 'status']),
        );
    }

    public function updated(Payment $payment): void
    {
        $dirty = $payment->getDirty();
        if (empty($dirty)) return;

        $old = array_intersect_key($payment->getOriginal(), $dirty);

        $description = isset($dirty['status'])
            ? "Status pembayaran pesanan #{$payment->order?->order_number} berubah dari [{$old['status']}] ke [{$dirty['status']}]."
            : "Pembayaran pesanan #{$payment->order?->order_number} diperbarui.";

        ActivityLog::log(
            action:      'updated',
            model:       $payment,
            description: $description,
            oldValues:   $old,
            newValues:   $dirty,
        );
    }
}

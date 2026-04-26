<?php

namespace App\Events;

use App\Models\Payment;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PaymentStatusChanged
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Payment $payment,
        public string $newStatus // 'paid', 'failed'
    ) {}
}

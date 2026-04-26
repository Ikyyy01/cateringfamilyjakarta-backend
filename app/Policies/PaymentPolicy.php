<?php

namespace App\Policies;

use App\Models\Payment;
use App\Models\User;

class PaymentPolicy
{
    // Pemilik pesanan atau admin bisa lihat pembayaran
    public function view(User $user, Payment $payment): bool
    {
        return $user->isAdmin() || $user->id === $payment->order->user_id;
    }

    // Hanya admin yang bisa verifikasi pembayaran
    public function verify(User $user, Payment $payment): bool
    {
        return $user->isAdmin();
    }

    // Hanya admin yang bisa tolak pembayaran
    public function reject(User $user, Payment $payment): bool
    {
        return $user->isAdmin();
    }

    // Pemilik pesanan bisa upload bukti bayar
    public function upload(User $user, Payment $payment): bool
    {
        return $user->id === $payment->order->user_id;
    }
}

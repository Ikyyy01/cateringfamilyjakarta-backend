<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    // Customer hanya bisa lihat pesanan miliknya sendiri
    public function view(User $user, Order $order): bool
    {
        return $user->isAdmin() || $user->id === $order->user_id;
    }

    // Hanya admin yang bisa update status pesanan
    public function updateStatus(User $user, Order $order): bool
    {
        return $user->isAdmin();
    }

    // Customer bisa batalkan pesanan sendiri (hanya jika masih pending)
    public function cancel(User $user, Order $order): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->id === $order->user_id && $order->status === 'pending';
    }

    // Customer bisa review pesanan yang sudah selesai
    public function review(User $user, Order $order): bool
    {
        return $user->id === $order->user_id
            && $order->status === 'completed'
            && !$order->review;
    }
}

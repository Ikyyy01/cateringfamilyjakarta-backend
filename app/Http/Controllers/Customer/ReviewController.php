<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReviewRequest;
use App\Models\Order;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(StoreReviewRequest $request, Order $order)
    {
        // Hanya pemilik pesanan yang sudah selesai yang bisa review
        abort_if($order->user_id !== Auth::id(), 403);
        abort_if($order->status !== 'completed', 403, 'Pesanan harus berstatus selesai untuk memberikan review.');
        abort_if($order->review !== null, 409, 'Pesanan ini sudah memiliki review.');

        Review::create([
            'order_id' => $order->id,
            'user_id'  => Auth::id(),
            'rating'   => $request->rating,
            'comment'  => $request->comment,
        ]);

        return redirect()->route('customer.orders.show', $order)
            ->with('success', 'Terima kasih atas review Anda!');
    }
}

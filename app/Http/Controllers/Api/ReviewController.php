<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    // POST /api/v1/orders/{order}/review — Submit review
    public function store(Request $request, Order $order)
    {
        if ($order->user_id !== $request->user()->id) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }

        if ($order->status !== 'completed') {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan harus berstatus selesai untuk memberikan review.',
            ], 422);
        }

        if ($order->review) {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan ini sudah memiliki review.',
            ], 409);
        }

        $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $review = Review::create([
            'order_id' => $order->id,
            'user_id'  => $request->user()->id,
            'rating'   => $request->rating,
            'comment'  => $request->comment,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Terima kasih atas review Anda!',
            'data'    => $review,
        ], 201);
    }
}

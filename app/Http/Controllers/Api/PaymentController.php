<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    // GET /api/v1/orders/{order}/payment — Lihat info pembayaran (guest bisa)
    public function show(Request $request, Order $order)
    {
        $order->load('payment');

        if (!$order->payment) {
            Payment::create([
                'order_id' => $order->id,
                'amount'   => $order->total_price,
                'method'   => 'qris',
                'status'   => 'unpaid',
            ]);
            $order->load('payment');
        }

        return response()->json([
            'success' => true,
            'data'    => [
                'order_number'    => $order->order_number,
                'nama_pemesan'    => $order->nama_pemesan_display,
                'total_price'     => $order->total_price,
                'formatted_total' => $order->formatted_total,
                'payment'         => $order->payment,
            ],
        ]);
    }

    // POST /api/v1/orders/{order}/payment/upload — Upload bukti pembayaran (guest bisa)
    public function upload(Request $request, Order $order)
    {
        if (!in_array($order->status, ['pending', 'confirmed'])) {
            return response()->json([
                'success' => false,
                'message' => 'Status pesanan tidak memungkinkan upload pembayaran.',
            ], 422);
        }

        $request->validate([
            'proof_image'    => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'method'         => 'required|in:transfer,qris,cash',
            'bank_name'      => 'nullable|string|max:100',
            'account_number' => 'nullable|string|max:50',
        ]);

        $path = $request->file('proof_image')->store('payments', 'public');

        $order->payment()->updateOrCreate(
            ['order_id' => $order->id],
            [
                'amount'         => $order->total_price,
                'method'         => $request->method,
                'status'         => 'pending_verification',
                'proof_image'    => $path,
                'bank_name'      => $request->bank_name,
                'account_number' => $request->account_number,
                'paid_at'        => now(),
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Bukti pembayaran berhasil dikirim. Menunggu verifikasi admin.',
            'data'    => $order->fresh()->load('payment'),
        ]);
    }
}

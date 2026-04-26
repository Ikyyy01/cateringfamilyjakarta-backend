<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\UploadPaymentRequest;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function show(Order $order)
    {
        // Bisa akses kalau login sebagai pemilik, atau punya session order ini
        $isOwner   = Auth::check() && $order->user_id === Auth::id();
        $isSession = session('last_order_id') == $order->id;
        abort_if(!$isOwner && !$isSession, 403);

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

        return view('customer.payment', compact('order'));
    }

    public function upload(UploadPaymentRequest $request, Order $order)
    {
        $isOwner   = Auth::check() && $order->user_id === Auth::id();
        $isSession = session('last_order_id') == $order->id;
        abort_if(!$isOwner && !$isSession, 403);

        $path   = $request->file('proof_image')->store('payments', 'public');
        $method = $request->input('method', 'qris');

        $order->payment()->updateOrCreate(
            ['order_id' => $order->id],
            [
                'amount'         => $order->total_price,
                'method'         => $method,
                'status'         => 'pending_verification',
                'proof_image'    => $path,
                'bank_name'      => $request->input('bank_name'),
                'account_number' => $request->input('account_number'),
                'paid_at'        => now(),
            ]
        );

        return redirect()->route('customer.payment.show', $order)
            ->with('success', 'Bukti pembayaran berhasil dikirim! Menunggu verifikasi admin.');
    }
}

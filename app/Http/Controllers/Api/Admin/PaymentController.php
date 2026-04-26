<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Events\PaymentStatusChanged;
use App\Models\ActivityLog;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    // GET /api/v1/admin/payments
    public function index(Request $request)
    {
        $query = Payment::with('order.user')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $payments = $query->paginate(15);

        return response()->json([
            'success' => true,
            'data'    => $payments,
        ]);
    }

    // POST /api/v1/admin/payments/{payment}/verify
    public function verify(Payment $payment)
    {
        $payment->update([
            'status'      => 'paid',
            'verified_at' => now(),
        ]);

        $payment->order->update(['status' => 'confirmed']);

        ActivityLog::log(
            'payment_verified',
            $payment,
            'Pembayaran pesanan #' . $payment->order->order_number . ' diverifikasi.'
        );

        event(new PaymentStatusChanged($payment, 'paid'));

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran berhasil diverifikasi.',
        ]);
    }

    // POST /api/v1/admin/payments/{payment}/reject
    public function reject(Payment $payment)
    {
        $payment->update(['status' => 'failed']);

        ActivityLog::log(
            'payment_rejected',
            $payment,
            'Pembayaran pesanan #' . $payment->order->order_number . ' ditolak.'
        );

        event(new PaymentStatusChanged($payment, 'failed'));

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran berhasil ditolak.',
        ]);
    }
}

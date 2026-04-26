<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Events\PaymentStatusChanged;
use App\Models\Payment;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with('order.user')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $payments = $query->paginate(10)->withQueryString();
        return view('admin.payments.index', compact('payments'));
    }

    public function verify(Payment $payment)
    {
        $payment->update([
            'status'      => 'paid',
            'verified_at' => now(),
        ]);

        $payment->order->update(['status' => 'confirmed']);

        // Catat activity log SEKALI di sini (bukan di listener)
        ActivityLog::log(
            'payment_verified',
            $payment,
            'Pembayaran pesanan #' . $payment->order->order_number . ' verified'
        );

        // Fire event — listener hanya kirim notifikasi
        event(new PaymentStatusChanged($payment, 'paid'));

        return redirect()->back()->with('success', 'Pembayaran berhasil diverifikasi!');
    }

    public function reject(Payment $payment)
    {
        $payment->update(['status' => 'failed']);

        // Catat activity log SEKALI di sini (bukan di listener)
        ActivityLog::log(
            'payment_rejected',
            $payment,
            'Pembayaran pesanan #' . $payment->order->order_number . ' rejected'
        );

        // Fire event — listener hanya kirim notifikasi
        event(new PaymentStatusChanged($payment, 'failed'));

        return redirect()->back()->with('success', 'Pembayaran ditolak.');
    }
}

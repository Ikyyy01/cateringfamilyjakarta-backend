<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Events\OrderStatusChanged;
use App\Models\Order;
use App\Models\CustomMenu;
use App\Models\ActivityLog;
use App\Notifications\CustomMenuStatusNotification;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('user', 'payment')->latest();

        // Filter search
        if ($request->filled('search')) {
            $search = '%' . $request->search . '%';
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', $search)
                  ->orWhere('nama_pemesan', 'like', $search)
                  ->orWhereHas('user', fn($u) => $u->where('name', 'like', $search)
                                                     ->orWhere('phone', 'like', $search));
            });
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter tanggal
        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        $orders = $query->paginate(15)->withQueryString();
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load('user', 'orderItems.menu', 'customMenus', 'payment', 'review');
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status'           => 'required|in:pending,confirmed,processing,delivered,completed,cancelled',
            'cancelled_reason' => 'required_if:status,cancelled|nullable|string|max:500',
        ]);

        $oldStatus = $order->status;

        $updateData = ['status' => $request->status];
        if ($request->status === 'cancelled') {
            $updateData['cancelled_reason'] = $request->cancelled_reason;
        }

        $order->update($updateData);

        // Catat activity log SEKALI di sini (bukan di listener)
        ActivityLog::log(
            'status_changed',
            $order,
            'Status pesanan #' . $order->order_number . ' diubah dari ' . $oldStatus . ' ke ' . $request->status,
            ['status' => $oldStatus],
            ['status' => $request->status]
        );

        // Fire event — listener hanya kirim notifikasi
        event(new OrderStatusChanged($order, $oldStatus, $request->status));

        return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui!');
    }

    public function approveCustomMenu(Request $request, CustomMenu $customMenu)
    {
        $request->validate([
            'estimated_price' => 'required|numeric|min:0',
            'admin_notes'     => 'nullable|string',
        ]);

        $customMenu->update([
            'status'          => 'approved',
            'estimated_price' => $request->estimated_price,
            'subtotal'        => $request->estimated_price * $customMenu->pax,
            'admin_notes'     => $request->admin_notes,
            'approved_at'     => now(),
        ]);

        $customMenu->order->recalculateTotal();

        // Kirim notifikasi ke customer
        $customMenu->load('order.user');
        if ($customMenu->order->user) {
            $customMenu->order->user->notify(new CustomMenuStatusNotification($customMenu, 'approved'));
        }

        // Log aktivitas
        ActivityLog::log('approved', $customMenu, 'Custom menu "' . $customMenu->item_name . '" disetujui.');

        return redirect()->back()->with('success', 'Custom menu berhasil disetujui!');
    }

    public function rejectCustomMenu(Request $request, CustomMenu $customMenu)
    {
        $customMenu->update([
            'status'      => 'rejected',
            'admin_notes' => $request->admin_notes,
        ]);

        // Kirim notifikasi ke customer
        $customMenu->load('order.user');
        if ($customMenu->order->user) {
            $customMenu->order->user->notify(new CustomMenuStatusNotification($customMenu, 'rejected'));
        }

        ActivityLog::log('rejected', $customMenu, 'Custom menu "' . $customMenu->item_name . '" ditolak.');

        return redirect()->back()->with('success', 'Custom menu berhasil ditolak.');
    }
}

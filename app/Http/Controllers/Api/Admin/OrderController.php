<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Events\OrderStatusChanged;
use App\Models\ActivityLog;
use App\Models\CustomMenu;
use App\Models\Order;
use App\Notifications\CustomMenuStatusNotification;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // GET /api/v1/admin/orders
    public function index(Request $request)
    {
        $query = Order::with('user', 'payment')->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('nama_pemesan', 'like', "%{$search}%")
                  ->orWhere('no_hp', 'like', "%{$search}%")
                  ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        $orders = $query->paginate(15);

        // Append nama_pemesan_display to each order for Vue to use
        $orders->getCollection()->transform(function ($order) {
            $order->append('nama_pemesan_display');
            return $order;
        });

        return response()->json([
            'success' => true,
            'data'    => $orders,
        ]);
    }

    // GET /api/v1/admin/orders/{order}
    public function show(Order $order)
    {
        $order->load('user', 'orderItems.menu', 'customMenus', 'payment', 'review');
        $order->append('nama_pemesan_display');

        return response()->json([
            'success' => true,
            'data'    => $order,
        ]);
    }

    // PATCH /api/v1/admin/orders/{order}/status
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status'           => 'required|in:pending,confirmed,processing,delivered,completed,cancelled',
            'cancelled_reason' => 'required_if:status,cancelled|nullable|string|max:500',
        ]);

        $oldStatus  = $order->status;
        $updateData = ['status' => $request->status];

        if ($request->status === 'cancelled') {
            $updateData['cancelled_reason'] = $request->cancelled_reason;
        }

        $order->update($updateData);

        ActivityLog::log(
            'status_changed',
            $order,
            'Status pesanan #' . $order->order_number . ' diubah dari ' . $oldStatus . ' ke ' . $request->status,
            ['status' => $oldStatus],
            ['status' => $request->status]
        );

        event(new OrderStatusChanged($order, $oldStatus, $request->status));

        return response()->json([
            'success' => true,
            'message' => 'Status pesanan berhasil diperbarui.',
            'data'    => $order->fresh(),
        ]);
    }

    // POST /api/v1/admin/custom-menus/{customMenu}/approve
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

        $customMenu->load('order.user');
        if ($customMenu->order->user) {
            $customMenu->order->user->notify(new CustomMenuStatusNotification($customMenu, 'approved'));
        }

        ActivityLog::log('approved', $customMenu, 'Custom menu "' . $customMenu->item_name . '" disetujui.');

        return response()->json([
            'success' => true,
            'message' => 'Custom menu berhasil disetujui.',
        ]);
    }

    // POST /api/v1/admin/custom-menus/{customMenu}/reject
    public function rejectCustomMenu(Request $request, CustomMenu $customMenu)
    {
        $customMenu->update([
            'status'      => 'rejected',
            'admin_notes' => $request->admin_notes,
        ]);

        $customMenu->load('order.user');
        if ($customMenu->order->user) {
            $customMenu->order->user->notify(new CustomMenuStatusNotification($customMenu, 'rejected'));
        }

        ActivityLog::log('rejected', $customMenu, 'Custom menu "' . $customMenu->item_name . '" ditolak.');

        return response()->json([
            'success' => true,
            'message' => 'Custom menu berhasil ditolak.',
        ]);
    }
}

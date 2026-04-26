<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Events\OrderCreated;
use App\Events\OrderStatusChanged;
use App\Models\ActivityLog;
use App\Models\Coupon;
use App\Models\CustomMenu;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\PriceConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    // GET /api/v1/orders
    public function index(Request $request)
    {
        $orders = Order::where('user_id', $request->user()->id)
            ->with('orderItems', 'payment')
            ->latest()
            ->paginate(10);

        return response()->json(['success' => true, 'data' => $orders]);
    }

    // GET /api/v1/orders/{order}
    public function show(Request $request, Order $order)
    {
        if ($request->user() && $order->user_id && $order->user_id !== $request->user()->id) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }

        $order->load('orderItems.menu', 'customMenus', 'payment', 'review');

        return response()->json(['success' => true, 'data' => $order]);
    }

    // POST /api/v1/orders
    public function store(Request $request)
    {
        $request->validate([
            'nama_pemesan'  => 'required|string|max:255',
            'no_hp'         => 'required|string|max:20',
            'event_date'    => 'required|date|after:today',
            'event_address' => 'required|string',
            'event_city'    => 'required|string',
            'distance_km'   => 'required|numeric|min:0',
            'notes'         => 'nullable|string',
            'coupon_code'   => 'nullable|string',
            'items'             => 'required|array|min:1',
            'items.*.menu_id'   => 'required|exists:menus,id',
            'items.*.menu_name' => 'required|string',
            'items.*.price'     => 'required|numeric|min:0',
            'items.*.pax'       => 'required|integer|min:1',
            'custom_menus'              => 'nullable|array',
            'custom_menus.*.item_name'  => 'required_with:custom_menus|string',
            'custom_menus.*.pax'        => 'required_with:custom_menus|integer|min:1',
        ]);

        return DB::transaction(function () use ($request) {
            $serviceFee  = PriceConfig::getValue('service_fee', 5000);
            $deliveryFee = PriceConfig::calculateDeliveryFee($request->distance_km);
            $subtotal    = collect($request->items)->sum(fn($item) => $item['price'] * $item['pax']);

            $discount = 0;
            $couponId = null;
            if ($request->filled('coupon_code')) {
                $coupon = Coupon::where('code', strtoupper($request->coupon_code))->first();
                if ($coupon && $coupon->isValid()) {
                    $discount = $coupon->calculateDiscount($subtotal);
                    $couponId = $coupon->id;
                    $coupon->increment('used_count');
                }
            }

            $order = Order::create([
                'user_id'       => $request->user()?->id,
                'nama_pemesan'  => $request->nama_pemesan,
                'no_hp'         => $request->no_hp,
                'order_number'  => Order::generateOrderNumber(),
                'event_date'    => $request->event_date,
                'event_address' => $request->event_address,
                'event_city'    => $request->event_city,
                'distance_km'   => $request->distance_km,
                'total_pax'     => collect($request->items)->sum('pax'),
                'subtotal'      => $subtotal,
                'delivery_fee'  => $deliveryFee,
                'service_fee'   => $serviceFee,
                'discount'      => $discount,
                'total_price'   => $subtotal + $deliveryFee + $serviceFee - $discount,
                'notes'         => $request->notes,
                'coupon_id'     => $couponId,
            ]);

            foreach ($request->items as $item) {
                OrderItem::create([
                    'order_id'  => $order->id,
                    'menu_id'   => $item['menu_id'],
                    'menu_name' => $item['menu_name'],
                    'price'     => $item['price'],
                    'pax'       => $item['pax'],
                    'subtotal'  => $item['price'] * $item['pax'],
                ]);
            }

            if ($request->filled('custom_menus')) {
                foreach ($request->custom_menus as $custom) {
                    if (!empty($custom['item_name'])) {
                        CustomMenu::create([
                            'order_id'    => $order->id,
                            'item_name'   => $custom['item_name'],
                            'description' => $custom['description'] ?? null,
                            'pax'         => $custom['pax'],
                        ]);
                    }
                }
            }

            Payment::create([
                'order_id' => $order->id,
                'amount'   => $order->total_price,
                'method'   => 'qris',
                'status'   => 'unpaid',
            ]);

            ActivityLog::log('created', $order, 'Pesanan baru #' . $order->order_number . ' dibuat.');
            event(new OrderCreated($order));

            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil dibuat.',
                'data'    => $order->load('orderItems', 'payment'),
            ], 201);
        });
    }

    // POST /api/v1/orders/{order}/confirm-received
    public function confirmReceived(Request $request, Order $order)
    {
        if ($order->user_id && $request->user()?->id !== $order->user_id) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }

        if ($order->status !== 'delivered') {
            return response()->json([
                'success' => false,
                'message' => 'Konfirmasi hanya bisa dilakukan saat pesanan berstatus "Dikirim".',
            ], 422);
        }

        $oldStatus = $order->status;
        $order->update(['status' => 'completed']);

        ActivityLog::log('status_changed', $order,
            'Pelanggan mengkonfirmasi pesanan #' . $order->order_number . ' sudah diterima.',
            ['status' => $oldStatus], ['status' => 'completed']
        );

        event(new OrderStatusChanged($order, $oldStatus, 'completed'));

        return response()->json([
            'success' => true,
            'message' => 'Terima kasih! Pesanan telah dikonfirmasi selesai.',
            'data'    => $order->fresh(),
        ]);
    }

    // POST /api/v1/orders/{order}/cancel
    public function cancel(Request $request, Order $order)
    {
        if ($order->user_id && $request->user()?->id !== $order->user_id) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }

        if ($order->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan hanya bisa dibatalkan saat masih pending.',
            ], 422);
        }

        $oldStatus = $order->status;
        $order->update([
            'status'           => 'cancelled',
            'cancelled_reason' => $request->input('reason', 'Dibatalkan oleh pelanggan'),
        ]);

        ActivityLog::log('status_changed', $order,
            'Status pesanan #' . $order->order_number . ' diubah ke cancelled',
            ['status' => $oldStatus], ['status' => 'cancelled']
        );

        event(new OrderStatusChanged($order, $oldStatus, 'cancelled'));

        return response()->json(['success' => true, 'message' => 'Pesanan berhasil dibatalkan.']);
    }
}

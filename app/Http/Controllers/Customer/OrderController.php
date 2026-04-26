<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Events\OrderCreated;
use App\Events\OrderStatusChanged;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\CustomMenu;
use App\Models\Coupon;
use App\Models\PriceConfig;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function checkout()
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('customer.menu')->with('error', 'Keranjang kosong!');
        }
        $serviceFee = PriceConfig::getValue('service_fee', 50000);
        return view('customer.checkout', compact('cart', 'serviceFee'));
    }

    public function store(StoreOrderRequest $request)
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('customer.menu')->with('error', 'Keranjang kosong!');
        }

        return DB::transaction(function () use ($request, $cart) {
            $serviceFee  = PriceConfig::getValue('service_fee', 50000);
            $deliveryFee = PriceConfig::calculateDeliveryFee($request->distance_km);
            $subtotal    = collect($cart)->sum(fn($item) => $item['price'] * $item['pax']);

            // Hitung diskon kupon
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

            // Kalau sudah login pakai user_id, kalau tidak buat guest user
            $userId = Auth::id();

            if (!$userId) {
                // Cari user berdasarkan phone, atau buat baru
                $user = User::firstOrCreate(
                    ['phone' => $request->no_hp],
                    [
                        'name'     => $request->nama_pemesan,
                        'email'    => 'guest_' . uniqid() . '@cateringfamilyjakarta.com',
                        'password' => bcrypt(str()->random(16)),
                        'role'     => 'customer',
                    ]
                );
                $userId = $user->id;
            }

            $order = Order::create([
                'user_id'       => $userId,
                'nama_pemesan'  => $request->nama_pemesan,
                'no_hp'         => $request->no_hp,
                'order_number'  => Order::generateOrderNumber(),
                'event_date'    => $request->event_date,
                'event_address' => $request->event_address,
                'event_city'    => $request->event_city,
                'distance_km'   => $request->distance_km,
                'total_pax'     => collect($cart)->sum('pax'),
                'subtotal'      => $subtotal,
                'delivery_fee'  => $deliveryFee,
                'service_fee'   => $serviceFee,
                'discount'      => $discount,
                'total_price'   => $subtotal + $deliveryFee + $serviceFee - $discount,
                'notes'         => $request->notes,
                'coupon_id'     => $couponId,
            ]);

            foreach ($cart as $item) {
                OrderItem::create([
                    'order_id'  => $order->id,
                    'menu_id'   => $item['menu_id'],
                    'menu_name' => $item['name'],
                    'price'     => $item['price'],
                    'pax'       => $item['pax'],
                    'subtotal'  => $item['price'] * $item['pax'],
                ]);
            }

            if ($request->has('custom_menus')) {
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

            // Simpan order_number di session supaya bisa lihat status tanpa login
            session(['last_order_number' => $order->order_number]);
            session(['last_order_id'     => $order->id]);

            session()->forget('cart');

            // Catat activity log SEKALI di sini (bukan di listener)
            ActivityLog::log('created', $order, 'Pesanan baru #' . $order->order_number . ' dibuat.');

            // Fire event — listener hanya kirim notifikasi
            event(new OrderCreated($order));

            return redirect()->route('customer.payment.show', $order)
                ->with('success', 'Pesanan berhasil dibuat! Silakan selesaikan pembayaran.');
        });
    }

    public function index()
    {
        $orders = Order::where('user_id', Auth::id())->latest()->paginate(10);
        return view('customer.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        // Bisa akses kalau login sebagai pemilik, atau punya session order ini
        $isOwner   = Auth::check() && $order->user_id === Auth::id();
        $isSession = session('last_order_id') == $order->id;
        abort_if(!$isOwner && !$isSession, 403);

        $order->load('orderItems.menu', 'customMenus', 'payment', 'review');
        return view('customer.orders.show', compact('order'));
    }

    // Customer bisa batalkan pesanan sendiri (hanya kalau masih pending)
    public function cancel(Request $request, Order $order)
    {
        $isOwner = Auth::check() && $order->user_id === Auth::id();
        $isSession = session('last_order_id') == $order->id;
        abort_if(!$isOwner && !$isSession, 403);
        abort_if($order->status !== 'pending', 403, 'Pesanan hanya bisa dibatalkan saat masih pending.');

        $oldStatus = $order->status;

        $order->update([
            'status'           => 'cancelled',
            'cancelled_reason' => $request->input('reason', 'Dibatalkan oleh pelanggan'),
        ]);

        // Catat activity log SEKALI di sini (bukan di listener)
        ActivityLog::log(
            'status_changed',
            $order,
            'Status pesanan #' . $order->order_number . ' diubah dari ' . $oldStatus . ' ke cancelled',
            ['status' => $oldStatus],
            ['status' => 'cancelled']
        );

        event(new OrderStatusChanged($order, $oldStatus, 'cancelled'));

        return redirect()->route('customer.orders.show', $order)
            ->with('success', 'Pesanan berhasil dibatalkan.');
    }
}

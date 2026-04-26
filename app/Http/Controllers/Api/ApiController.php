<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Menu;
use App\Models\Order;
use App\Models\PriceConfig;
use App\Models\Review;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ApiController extends Controller
{
    // GET /api/v1/menus
    public function menus(Request $request): JsonResponse
    {
        $query = Menu::active()->with('category');

        if ($request->filled('kategori')) {
            $query->whereHas('category', fn($q) => $q->where('slug', $request->kategori));
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('cari') || $request->filled('search')) {
            $keyword = $request->cari ?? $request->search;
            $query->where('name', 'like', '%' . $keyword . '%');
        }

        $limit = min((int) ($request->limit ?? 12), 100);
        $menus = $query->paginate($limit);

        return response()->json(['success' => true, 'data' => $menus]);
    }

    // GET /api/v1/menus/{menu}
    public function menuDetail(Menu $menu): JsonResponse
    {
        if (!$menu->is_active) {
            return response()->json(['success' => false, 'message' => 'Menu tidak tersedia.'], 404);
        }
        return response()->json(['success' => true, 'data' => $menu->load('category')]);
    }

    // GET /api/v1/categories
    public function categories(): JsonResponse
    {
        $categories = Category::active()->withCount('menus')->get();
        return response()->json(['success' => true, 'data' => $categories]);
    }

    // GET /api/v1/price-config
    public function priceConfig(): JsonResponse
    {
        $configs = PriceConfig::all();
        return response()->json(['success' => true, 'data' => $configs]);
    }

    // GET /api/v1/stats  — statistik publik untuk homepage
    public function stats(): JsonResponse
    {
        $avgRating      = round(Review::avg('rating') ?? 0, 1);
        $totalReviews   = Review::count();
        $totalCompleted = Order::where('status', 'completed')->count();
        $totalMenus     = Menu::active()->count();

        return response()->json([
            'success' => true,
            'data'    => [
                'total_completed' => $totalCompleted,
                'total_menus'     => $totalMenus,
                'avg_rating'      => $avgRating ?: 4.8,
                'total_reviews'   => $totalReviews,
            ],
        ]);
    }

    // GET /api/v1/settings/qris — publik, untuk PaymentPage.vue
    public function qrisPublic(): JsonResponse
    {
        $path        = AppSetting::get('qris_image');
        $whatsapp    = AppSetting::get('whatsapp_number', '6281234567890');
        $qrisUrl     = null;

        if ($path && Storage::disk('public')->exists($path)) {
            $qrisUrl = Storage::disk('public')->url($path);
        }

        return response()->json([
            'success' => true,
            'data'    => [
                'qris_url'         => $qrisUrl,
                'whatsapp_number'  => $whatsapp,
            ],
        ]);
    }

    // POST /api/v1/coupons/check
    public function checkCoupon(Request $request): JsonResponse
    {
        $request->validate(['code' => 'required|string', 'subtotal' => 'required|numeric|min:0']);

        $coupon = Coupon::where('code', strtoupper($request->code))->first();

        if (!$coupon) {
            return response()->json(['success' => false, 'message' => 'Kode kupon tidak ditemukan.'], 404);
        }
        if (!$coupon->isValid()) {
            return response()->json(['success' => false, 'message' => 'Kupon sudah tidak berlaku atau habis.'], 422);
        }
        if ($request->subtotal < $coupon->min_order) {
            return response()->json([
                'success' => false,
                'message' => 'Minimum order untuk kupon ini adalah Rp ' . number_format($coupon->min_order, 0, ',', '.'),
            ], 422);
        }

        $discount = $coupon->calculateDiscount($request->subtotal);

        return response()->json([
            'success'  => true,
            'message'  => 'Kupon valid!',
            'data'     => [
                'code'        => $coupon->code,
                'description' => $coupon->description,
                'type'        => $coupon->type,
                'value'       => $coupon->value,
                'discount'    => $discount,
            ],
        ]);
    }

    // GET /api/v1/track
    public function track(Request $request): JsonResponse
    {
        if (!$request->filled('kode')) {
            return response()->json(['success' => false, 'message' => 'Parameter kode wajib diisi.'], 422);
        }

        $order = Order::with('orderItems.menu', 'customMenus', 'payment', 'user')
            ->where('order_number', strtoupper(trim($request->kode)))
            ->first();

        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Pesanan tidak ditemukan. Periksa kembali kode pesanan Anda.'], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => [
                'id'               => $order->id,
                'order_number'     => $order->order_number,
                'status'           => $order->status,
                'status_label'     => $order->status_label,
                'nama_pemesan'     => $order->nama_pemesan ?? $order->user?->name,
                'no_hp'            => $order->no_hp,
                'event_date'       => $order->event_date->format('d M Y'),
                'event_address'    => $order->event_address,
                'event_city'       => $order->event_city,
                'total_pax'        => $order->total_pax,
                'total_price'      => $order->total_price,
                'total'            => $order->formatted_total,
                'subtotal'         => $order->subtotal,
                'delivery_fee'     => $order->delivery_fee,
                'service_fee'      => $order->service_fee,
                'discount'         => $order->discount,
                'items_count'      => $order->orderItems->count(),
                'cancelled_reason' => $order->cancelled_reason,
                'payment'          => $order->payment ? [
                    'status'       => $order->payment->status,
                    'status_label' => $order->payment->status_label,
                    'method'       => $order->payment->method,
                ] : null,
                'created_at'       => $order->created_at->format('d M Y H:i'),
            ],
        ]);
    }

    // GET /api/v1/reviews
    public function reviews(): JsonResponse
    {
        $reviews = Review::with('user:id,name', 'order:id,order_number')
            ->latest()->take(10)->get()
            ->map(fn($r) => [
                'rating'       => $r->rating,
                'comment'      => $r->comment,
                'customer'     => $r->user?->name ?? 'Anonim',
                'order_number' => $r->order?->order_number,
                'date'         => $r->created_at->format('d M Y'),
            ]);

        return response()->json(['success' => true, 'data' => $reviews]);
    }
}

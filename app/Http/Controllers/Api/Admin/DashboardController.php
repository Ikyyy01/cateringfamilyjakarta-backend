<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use App\Models\Menu;
use App\Models\Review;
use App\Models\CustomMenu;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // GET /api/v1/admin/dashboard?period=7|30|90
    public function index(Request $request)
    {
        $period = (int) $request->input('period', 7);
        if (!in_array($period, [7, 30, 90])) {
            $period = 7;
        }

        $stats = [
            'total_orders'         => Order::count(),
            'total_pending'        => Order::where('status', 'pending')->count(),
            'total_completed'      => Order::where('status', 'completed')->count(),
            'total_revenue'        => (int) Order::where('status', 'completed')->sum('total_price'),
            'total_customers'      => User::where('role', 'customer')->count(),
            'total_menus'          => Menu::where('is_active', true)->count(),
            'pending_payments'     => Payment::where('status', 'pending_verification')->count(),
            'pending_custom_menus' => CustomMenu::where('status', 'pending')->count(),
        ];

        // Chart: sesuai period
        $chartLabels    = [];
        $chartData      = [];
        $chartCompleted = [];
        $chartRevenue   = [];

        for ($i = $period - 1; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);

            // Label: "01 Jan" untuk 7h, "Jan '25" untuk 30h+
            if ($period === 7) {
                $chartLabels[] = $date->translatedFormat('d M');
            } elseif ($period === 30) {
                $chartLabels[] = $date->translatedFormat('d M');
            } else {
                // 90 hari: group per minggu
                if ($i % 7 === 0 || $i === $period - 1) {
                    $chartLabels[] = $date->translatedFormat('d M');
                } else {
                    $chartLabels[] = '';
                }
            }

            $chartData[]      = Order::whereDate('created_at', $date)->count();
            $chartCompleted[] = Order::whereDate('created_at', $date)->where('status', 'completed')->count();
            $chartRevenue[]   = (int) Order::whereDate('created_at', $date)->where('status', 'completed')->sum('total_price');
        }

        // Status distribution
        $statusDist = [];
        foreach (['pending', 'confirmed', 'processing', 'delivered', 'completed', 'cancelled'] as $status) {
            $count = Order::where('status', $status)->count();
            if ($count > 0) $statusDist[$status] = $count;
        }

        $recentOrders = Order::with('user', 'payment')
            ->latest()
            ->take(5)
            ->get()
            ->map(fn($o) => [
                'id'             => $o->id,
                'order_number'   => $o->order_number,
                'customer'       => $o->nama_pemesan_display,
                'total_price'    => $o->total_price,
                'total'          => $o->formatted_total,
                'status'         => $o->status,
                'status_label'   => $o->status_label,
                'payment_status' => $o->payment?->status,
                'created_at'     => $o->created_at->format('d M Y H:i'),
            ]);

        // Rating & stats untuk homepage
        $avgRating    = round(Review::avg('rating') ?? 0, 1);
        $totalReviews = Review::count();

        return response()->json([
            'success' => true,
            'data'    => [
                'stats'          => $stats,
                'period'         => $period,
                'recent_orders'  => $recentOrders,
                'chart_labels'   => $chartLabels,
                'chart_data'     => $chartData,
                'chart_completed'=> $chartCompleted,
                'chart_revenue'  => $chartRevenue,
                'status_dist'    => $statusDist,
                'avg_rating'     => $avgRating,
                'total_reviews'  => $totalReviews,
            ],
        ]);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Menu;
use App\Models\User;
use App\Models\Payment;
use App\Models\CustomMenu;
use App\Models\Review;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalOrders        = Order::count();
        $totalPending       = Order::where('status', 'pending')->count();
        $totalCompleted     = Order::where('status', 'completed')->count();
        $totalRevenue       = Payment::where('status', 'paid')->sum('amount');
        $totalMenus         = Menu::where('is_active', true)->count();
        $totalCustomers     = User::where('role', 'customer')->count();
        $pendingPayments    = Payment::where('status', 'pending_verification')->count();
        $pendingCustomMenus = CustomMenu::where('status', 'pending')->count();
        $recentOrders       = Order::with('user', 'payment')->latest()->take(7)->get();

        // Statistik tambahan
        $avgRating    = Review::avg('rating') ?? 0;
        $totalReviews = Review::count();

        // Chart: pesanan 7 hari terakhir
        $chartOrders = Order::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as completed')
            )
            ->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get();

        // Fill missing days
        $chartLabels = [];
        $chartData   = [];
        $chartCompleted = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $chartLabels[] = now()->subDays($i)->format('d M');
            $found = $chartOrders->firstWhere('date', $date);
            $chartData[]      = $found ? $found->total : 0;
            $chartCompleted[] = $found ? $found->completed : 0;
        }

        // Chart: revenue 7 hari terakhir
        $chartRevenue = Payment::select(
                DB::raw('DATE(paid_at) as date'),
                DB::raw('SUM(amount) as total')
            )
            ->where('status', 'paid')
            ->where('paid_at', '>=', now()->subDays(6)->startOfDay())
            ->groupBy(DB::raw('DATE(paid_at)'))
            ->orderBy('date')
            ->get();

        $chartRevenueData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $found = $chartRevenue->firstWhere('date', $date);
            $chartRevenueData[] = $found ? (float) $found->total : 0;
        }

        // Status distribution
        $statusDist = Order::select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        return view('admin.dashboard', compact(
            'totalOrders', 'totalPending', 'totalCompleted',
            'totalRevenue', 'totalMenus', 'totalCustomers',
            'pendingPayments', 'pendingCustomMenus', 'recentOrders',
            'avgRating', 'totalReviews',
            'chartLabels', 'chartData', 'chartCompleted',
            'chartRevenueData', 'statusDist'
        ));
    }
}

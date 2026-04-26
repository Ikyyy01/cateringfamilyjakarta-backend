<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $totalOrders    = $user->orders()->count();
        $activeOrders   = $user->orders()->whereNotIn('status', ['completed', 'cancelled'])->count();
        $completedOrders = $user->orders()->where('status', 'completed')->count();
        $totalSpent     = $user->orders()
            ->where('status', 'completed')
            ->sum('total_price');

        $recentOrders = $user->orders()
            ->with('payment')
            ->latest()
            ->take(5)
            ->get();

        $pendingPayments = $user->orders()
            ->whereHas('payment', fn($q) => $q->whereIn('status', ['unpaid', 'pending_verification']))
            ->with('payment')
            ->latest()
            ->take(3)
            ->get();

        return view('customer.dashboard', compact(
            'totalOrders', 'activeOrders', 'completedOrders',
            'totalSpent', 'recentOrders', 'pendingPayments'
        ));
    }
}

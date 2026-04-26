<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'customer')->withCount('orders');

        // Search
        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($sub) use ($q) {
                $sub->where('name',  'like', "%$q%")
                    ->orWhere('email', 'like', "%$q%")
                    ->orWhere('phone', 'like', "%$q%");
            });
        }

        $customers = $query->latest()->paginate(15)->withQueryString();

        $totalCustomers = User::where('role', 'customer')->count();
        $newThisMonth   = User::where('role', 'customer')
                              ->whereMonth('created_at', now()->month)
                              ->whereYear('created_at', now()->year)
                              ->count();

        return view('admin.customers.index', compact('customers', 'totalCustomers', 'newThisMonth'));
    }

    public function show(User $user)
    {
        abort_if($user->role !== 'customer', 404);

        $orders = $user->orders()
                       ->with('payment')
                       ->latest()
                       ->paginate(10);

        $totalSpent    = $user->orders()
                              ->whereHas('payment', fn($q) => $q->where('status', 'paid'))
                              ->with('payment')
                              ->get()
                              ->sum(fn($o) => $o->payment->amount ?? 0);

        $totalOrders   = $user->orders()->count();
        $totalCompleted = $user->orders()->where('status', 'completed')->count();

        return view('admin.customers.show', compact('user', 'orders', 'totalSpent', 'totalOrders', 'totalCompleted'));
    }

    public function destroy(User $user)
    {
        abort_if($user->role !== 'customer', 404);

        // Jangan hapus kalau masih punya pesanan aktif
        $activeOrders = $user->orders()
                             ->whereNotIn('status', ['completed', 'cancelled'])
                             ->count();

        if ($activeOrders > 0) {
            return redirect()->back()->with('error', 'Tidak bisa menghapus pelanggan yang masih memiliki pesanan aktif.');
        }

        $user->delete();
        return redirect()->route('admin.customers.index')->with('success', 'Pelanggan berhasil dihapus.');
    }
}

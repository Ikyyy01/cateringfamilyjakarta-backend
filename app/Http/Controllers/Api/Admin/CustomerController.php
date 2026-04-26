<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'customer')->withCount('orders');

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($sub) use ($q) {
                $sub->where('name',  'like', "%$q%")
                    ->orWhere('email', 'like', "%$q%")
                    ->orWhere('phone', 'like', "%$q%");
            });
        }

        $customers = $query->latest()->paginate(15);

        return response()->json(['success' => true, 'data' => $customers]);
    }

    public function show(User $user)
    {
        abort_if($user->role !== 'customer', 404);

        $orders = $user->orders()->with('payment')->latest()->paginate(10);

        $totalSpent = $user->orders()
            ->whereHas('payment', fn($q) => $q->where('status', 'paid'))
            ->with('payment')->get()
            ->sum(fn($o) => $o->payment->amount ?? 0);

        return response()->json([
            'success' => true,
            'data' => [
                'user'            => $user,
                'orders'          => $orders,
                'total_spent'     => $totalSpent,
                'total_orders'    => $user->orders()->count(),
                'total_completed' => $user->orders()->where('status', 'completed')->count(),
            ],
        ]);
    }

    public function destroy(User $user)
    {
        abort_if($user->role !== 'customer', 404);

        $activeOrders = $user->orders()
            ->whereNotIn('status', ['completed', 'cancelled'])->count();

        if ($activeOrders > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak bisa menghapus pelanggan yang masih memiliki pesanan aktif.',
            ], 422);
        }

        $user->delete();

        return response()->json(['success' => true, 'message' => 'Pelanggan berhasil dihapus.']);
    }
}

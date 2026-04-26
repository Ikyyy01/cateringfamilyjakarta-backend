<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class TrackController extends Controller
{
    public function index(Request $request)
    {
        $order = null;

        if ($request->filled('kode')) {
            $order = Order::with('user', 'orderItems.menu', 'customMenus', 'payment')
                ->where('order_number', strtoupper(trim($request->kode)))
                ->first();
        }

        return view('customer.track', compact('order'));
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function show(Order $order)
    {
        $order->load('user', 'orderItems.menu', 'customMenus', 'payment');

        return view('pdf.invoice', compact('order'));
    }

    public function download(Order $order)
    {
        $order->load('user', 'orderItems.menu', 'customMenus', 'payment');

        // Render view dengan layout print-friendly yang memicu auto-print
        return view('pdf.invoice', ['order' => $order, 'autoPrint' => true]);
    }
}

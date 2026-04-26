<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->start_date ?? now()->startOfMonth()->toDateString();
        $endDate   = $request->end_date ?? now()->toDateString();

        $orders = Order::with('user', 'payment')
            ->whereBetween('created_at', [$startDate, $endDate . ' 23:59:59'])
            ->latest()
            ->get();

        $totalRevenue   = $orders->where('status', 'completed')->sum('total_price');
        $totalOrders    = $orders->count();
        $totalCompleted = $orders->where('status', 'completed')->count();
        $totalCancelled = $orders->where('status', 'cancelled')->count();

        return view('admin.reports.index', compact(
            'orders', 'totalRevenue', 'totalOrders',
            'totalCompleted', 'totalCancelled', 'startDate', 'endDate'
        ));
    }

    // Export laporan ke CSV
    public function export(Request $request)
    {
        $startDate = $request->start_date ?? now()->startOfMonth()->toDateString();
        $endDate   = $request->end_date ?? now()->toDateString();

        $orders = Order::with('user', 'payment')
            ->whereBetween('created_at', [$startDate, $endDate . ' 23:59:59'])
            ->latest()
            ->get();

        $filename = 'laporan_pesanan_' . $startDate . '_' . $endDate . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($orders) {
            $file = fopen('php://output', 'w');

            // BOM for UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Header
            fputcsv($file, [
                'No. Pesanan', 'Tanggal', 'Pelanggan', 'No. HP',
                'Kota', 'Total Pax', 'Subtotal', 'Ongkir',
                'Service Fee', 'Diskon', 'Total', 'Status',
                'Status Bayar', 'Metode Bayar'
            ]);

            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->order_number,
                    $order->created_at->format('d/m/Y H:i'),
                    $order->nama_pemesan_display,
                    $order->no_hp ?? $order->user->phone ?? '-',
                    $order->event_city,
                    $order->total_pax,
                    $order->subtotal,
                    $order->delivery_fee,
                    $order->service_fee,
                    $order->discount ?? 0,
                    $order->total_price,
                    $order->status_label,
                    $order->payment->status_label ?? 'Belum Bayar',
                    $order->payment->method_label ?? '-',
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }
}

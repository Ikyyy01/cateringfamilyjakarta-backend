@extends('layouts.app')
@section('title', 'Riwayat Pesanan — Catering Family Jakarta')

@section('content')
<div class="container py-5">
    <div class="section-title mb-1">Riwayat <span>Pesanan</span></div>
    <div class="section-divider"></div>

    @if($orders->count())
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No. Pesanan</th>
                            <th>Tanggal Acara</th>
                            <th>Total Pax</th>
                            <th>Total Harga</th>
                            <th>Status</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <td>
                                <div class="fw-700 text-danger">{{ $order->order_number }}</div>
                                <div class="text-muted" style="font-size:.75rem">{{ $order->created_at->format('d M Y') }}</div>
                            </td>
                            <td class="fw-600">{{ $order->event_date->format('d M Y') }}</td>
                            <td><span class="fw-600">{{ $order->total_pax }}</span> <span class="text-muted small">pax</span></td>
                            <td class="fw-800 text-danger">{{ $order->formatted_total }}</td>
                            <td>
                                <span class="status-badge status-{{ $order->status }}">
                                    {{ $order->status_label }}
                                </span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('customer.orders.show', $order) }}"
                                   class="btn btn-sm btn-outline-danger rounded-pill px-3">
                                    <i class="bi bi-eye me-1"></i>Detail
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="mt-4 d-flex justify-content-center">
        {{ $orders->links('pagination::bootstrap-5') }}
    </div>
    @else
    <div class="card text-center py-5 px-3" style="max-width:420px; margin:0 auto">
        <div style="font-size:4rem; opacity:.4">📋</div>
        <h5 class="mt-3 fw-800">Belum ada pesanan</h5>
        <p class="text-muted small mb-4">Yuk, mulai pesan catering untuk acara Anda!</p>
        <a href="{{ route('customer.menu') }}" class="btn btn-danger rounded-pill px-4 fw-700">
            <i class="bi bi-grid me-2"></i>Lihat Menu
        </a>
    </div>
    @endif
</div>
@endsection

@extends('layouts.admin')
@section('title', 'Laporan Pesanan')
@section('page-title', 'Laporan Pesanan')

@section('content')

{{-- Filter Tanggal --}}
<div class="card mb-4">
    <div class="card-body py-3">
        <form method="GET" action="{{ route('admin.reports.index') }}" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label fw-600 small mb-1">Dari Tanggal</label>
                <input type="date" name="start_date" class="form-control form-control-sm rounded-3"
                       value="{{ $startDate }}">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-600 small mb-1">Sampai Tanggal</label>
                <input type="date" name="end_date" class="form-control form-control-sm rounded-3"
                       value="{{ $endDate }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-danger btn-sm rounded-pill px-4 w-100">
                    <i class="bi bi-filter me-1"></i>Filter
                </button>
            </div>
            <div class="col-md-4 text-end text-muted small">
                Periode: <strong>{{ \Carbon\Carbon::parse($startDate)->format('d M Y') }}</strong>
                s/d <strong>{{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</strong>
            </div>
        </form>
    </div>
</div>

{{-- Stat Cards --}}
<div class="row g-4 mb-4">
    <div class="col-6 col-xl-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#C0392B,#E74C3C)">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-value">{{ $totalOrders }}</div>
                    <div class="stat-label">Total Pesanan</div>
                </div>
                <div class="stat-icon">🛍️</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#27AE60,#2ECC71)">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-value">{{ $totalCompleted }}</div>
                    <div class="stat-label">Pesanan Selesai</div>
                </div>
                <div class="stat-icon">✅</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#E67E22,#F39C12)">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-value">{{ $totalCancelled }}</div>
                    <div class="stat-label">Dibatalkan</div>
                </div>
                <div class="stat-icon">❌</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#8E44AD,#9B59B6)">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-value" style="font-size:1.4rem">
                        Rp {{ number_format($totalRevenue/1000000, 1) }}jt
                    </div>
                    <div class="stat-label">Total Pendapatan</div>
                </div>
                <div class="stat-icon">💰</div>
            </div>
        </div>
    </div>
</div>

{{-- Tabel Laporan --}}
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-table me-2"></i>Detail Laporan Pesanan</span>
        <span class="text-muted small">{{ $orders->count() }} pesanan</span>
    </div>
    <div class="card-body p-0">
        @if($orders->count())
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No. Pesanan</th>
                        <th>Pelanggan</th>
                        <th>Tanggal Acara</th>
                        <th>Total Pax</th>
                        <th>Total</th>
                        <th>Pembayaran</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    <tr>
                        <td>
                            <a href="{{ route('admin.orders.show', $order) }}"
                               class="fw-700 text-danger text-decoration-none">
                                {{ $order->order_number }}
                            </a>
                            <div class="text-muted" style="font-size:.75rem">
                                {{ $order->created_at->format('d M Y') }}
                            </div>
                        </td>
                        <td>
                            <div class="fw-600">{{ $order->user->name ?? '-' }}</div>
                            <div class="text-muted small">{{ $order->user->phone ?? '' }}</div>
                        </td>
                        <td>{{ $order->event_date->format('d M Y') }}</td>
                        <td>{{ $order->total_pax }} pax</td>
                        <td class="fw-700 text-danger">{{ $order->formatted_total }}</td>
                        <td>
                            @if($order->payment)
                                <span class="status-badge badge-{{ $order->payment->status }}">
                                    {{ match($order->payment->status) {
                                        'unpaid'               => 'Belum Bayar',
                                        'pending_verification' => 'Verifikasi',
                                        'paid'                 => 'Lunas',
                                        'failed'               => 'Gagal',
                                        default                => '-'
                                    } }}
                                </span>
                            @else
                                <span class="text-muted small">—</span>
                            @endif
                        </td>
                        <td>
                            <span class="status-badge badge-{{ $order->status }}">{{ $order->status_label }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="table-light">
                    <tr>
                        <td colspan="4" class="fw-700 text-end">Total Pendapatan (Selesai):</td>
                        <td class="fw-800 text-danger">
                            Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                        </td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        @else
        <div class="text-center py-5 text-muted">
            <div style="font-size:3rem">📊</div>
            <p class="mt-2">Tidak ada data pada periode ini</p>
        </div>
        @endif
    </div>
</div>

@endsection

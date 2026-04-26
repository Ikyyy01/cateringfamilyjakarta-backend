@extends('layouts.admin')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@push('styles')
<style>
    /* ── Stat Cards ── */
    .stat-card-v2 {
        background: #fff;
        border-radius: 14px;
        padding: 18px 16px;
        box-shadow: 0 1px 8px rgba(0,0,0,.06);
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 12px;
        transition: transform .2s, box-shadow .2s;
        height: 100%;
    }
    .stat-card-v2:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0,0,0,.09);
    }
    .stat-icon-v2 {
        width: 40px; height: 40px;
        border-radius: 11px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1rem;
        flex-shrink: 0;
    }
    .stat-icon-v2.red    { background: #FEE2E2; color: #C0392B; }
    .stat-icon-v2.orange { background: #FEF3C7; color: #D97706; }
    .stat-icon-v2.green  { background: #D1FAE5; color: #059669; }
    .stat-icon-v2.purple { background: #EDE9FE; color: #7C3AED; }
    .stat-icon-v2.blue   { background: #DBEAFE; color: #2563EB; }
    .stat-icon-v2.teal   { background: #CCFBF1; color: #0D9488; }

    .stat-value-v2 {
        font-size: 1.4rem; font-weight: 800;
        color: #111827; line-height: 1;
    }
    .stat-label-v2 {
        font-size: .72rem; font-weight: 600;
        color: #9CA3AF; margin-top: 3px;
    }

    /* ── Panel Card ── */
    .panel-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,.06);
        overflow: hidden;
    }
    .panel-card-header {
        padding: 18px 22px 14px;
        border-bottom: 1px solid #F3F4F6;
        display: flex; align-items: center; justify-content: space-between;
    }
    .panel-card-title {
        font-size: .9rem; font-weight: 800; color: #111827; margin: 0;
    }
    .panel-card-sub {
        font-size: .73rem; color: #9CA3AF; margin-top: 2px;
    }
    .panel-card-body { padding: 20px 22px; }

    /* ── Hero Banner ── */
    .hero-banner {
        border-radius: 16px;
        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 55%, #0f3460 100%);
        padding: 26px 28px;
        color: #fff;
        position: relative; overflow: hidden;
    }
    .hero-banner::before {
        content: '';
        position: absolute; right: -50px; top: -50px;
        width: 220px; height: 220px; border-radius: 50%;
        background: rgba(255,255,255,.04);
    }
    .hero-banner::after {
        content: '';
        position: absolute; right: 30px; bottom: -70px;
        width: 160px; height: 160px; border-radius: 50%;
        background: rgba(192,57,43,.12);
    }
    .hero-stat-pill {
        background: rgba(255,255,255,.09);
        border-radius: 12px;
        padding: 10px 14px;
        text-align: center;
        flex: 1;
    }

    /* ── Notif Bar ── */
    .notif-item {
        display: flex; align-items: center; gap: 14px;
        padding: 13px 16px;
        border-radius: 12px;
        text-decoration: none; color: inherit;
        transition: opacity .15s;
    }
    .notif-item:hover { opacity: .85; color: inherit; }
    .notif-item.warning {
        background: #FFFBEB;
        border: 1px solid #FDE68A;
    }
    .notif-item.info {
        background: #EFF6FF;
        border: 1px solid #BFDBFE;
    }
    .notif-icon {
        width: 38px; height: 38px; border-radius: 10px; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center;
        font-size: 1rem;
    }
    .notif-icon.warning { background: #FEF3C7; color: #D97706; }
    .notif-icon.info    { background: #DBEAFE; color: #2563EB; }

    /* ── Orders Table ── */
    .orders-tbl th {
        font-size: .68rem; text-transform: uppercase;
        letter-spacing: .8px; color: #9CA3AF;
        font-weight: 700; padding: 10px 16px;
        border: none; background: #FAFAFA;
    }
    .orders-tbl td {
        padding: 13px 16px;
        border-color: #F9FAFB;
        vertical-align: middle;
    }
    .orders-tbl tbody tr:last-child td { border-bottom: none; }

    /* ── Quick Action ── */
    .qa-item {
        display: flex; align-items: center; gap: 13px;
        padding: 13px 14px; border-radius: 12px;
        text-decoration: none; color: #374151;
        border: 1.5px solid #F3F4F6;
        transition: all .18s;
    }
    .qa-item:hover {
        border-color: var(--cfj-primary);
        background: #FEF2F2;
        color: var(--cfj-primary);
    }
    .qa-icon {
        width: 40px; height: 40px; border-radius: 11px; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center; font-size: .95rem;
    }

    /* ── Info System ── */
    .info-row {
        display: flex; justify-content: space-between; align-items: center;
        padding: 10px 0; font-size: .82rem;
        border-bottom: 1px solid #F9FAFB;
    }
    .info-row:last-child { border-bottom: none; }
    .info-key { color: #9CA3AF; font-weight: 600; }
    .info-val { font-weight: 700; color: #111827; }
</style>
@endpush

@section('content')

{{-- ── Header ── --}}
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <p class="mb-0" style="font-size:.75rem; color:#9CA3AF; font-weight:600">
            Panel Admin &rsaquo; Dashboard
        </p>
        <h5 class="fw-800 mb-0 mt-1" style="color:#111827">Dashboard</h5>
    </div>
    <a href="{{ route('admin.orders.index') }}"
       class="btn btn-danger rounded-pill px-4 fw-700 d-flex align-items-center gap-2"
       style="font-size:.85rem">
        <i class="bi bi-bag-check"></i> Kelola Pesanan
    </a>
</div>

{{-- ── Notif Bars ── --}}
@if($pendingPayments > 0 || $pendingCustomMenus > 0)
<div class="row g-3 mb-4">
    @if($pendingPayments > 0)
    <div class="col-md-6">
        <a href="{{ route('admin.payments.index') }}" class="notif-item warning">
            <div class="notif-icon warning"><i class="bi bi-credit-card-2-front"></i></div>
            <div class="flex-grow-1">
                <div class="fw-700" style="font-size:.84rem">{{ $pendingPayments }} Pembayaran Menunggu Verifikasi</div>
                <div style="font-size:.73rem; color:#9CA3AF">Klik untuk verifikasi sekarang</div>
            </div>
            <i class="bi bi-chevron-right text-warning"></i>
        </a>
    </div>
    @endif
    @if($pendingCustomMenus > 0)
    <div class="col-md-6">
        <a href="{{ route('admin.orders.index') }}" class="notif-item info">
            <div class="notif-icon info"><i class="bi bi-pencil-square"></i></div>
            <div class="flex-grow-1">
                <div class="fw-700" style="font-size:.84rem">{{ $pendingCustomMenus }} Custom Menu Belum Disetujui</div>
                <div style="font-size:.73rem; color:#9CA3AF">Klik untuk review pesanan</div>
            </div>
            <i class="bi bi-chevron-right text-primary"></i>
        </a>
    </div>
    @endif
</div>
@endif

{{-- ── Stat Cards ── --}}
@php
$stats = [
    ['Total Pesanan',      $totalOrders,    'red',    'bi-bag-check',       'Semua waktu'],
    ['Menunggu',           $totalPending,   'orange', 'bi-hourglass-split', 'Perlu tindakan'],
    ['Selesai',            $totalCompleted, 'green',  'bi-check-circle',    'Berhasil dikirim'],
    ['Pendapatan',         'Rp '.number_format($totalRevenue/1000000,1).'jt', 'purple', 'bi-cash-coin', 'Dari pesanan lunas'],
    ['Menu Aktif',         $totalMenus,     'blue',   'bi-grid',            'Tampil ke pelanggan'],
    ['Total Pelanggan',    $totalCustomers, 'teal',   'bi-people',          'Terdaftar di sistem'],
];
@endphp

<div class="row g-3 mb-4">
    @foreach($stats as [$label, $value, $color, $icon, $sub])
    <div class="col-6 col-md-4 col-xl-2">
        <div class="stat-card-v2">
            <div class="stat-icon-v2 {{ $color }}">
                <i class="bi {{ $icon }}"></i>
            </div>
            <div>
                <div class="stat-value-v2">{{ $value }}</div>
                <div class="stat-label-v2">{{ $label }}</div>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- ── Hero + Chart ── --}}
<div class="row g-4 mb-4">

    {{-- Hero --}}
    <div class="col-lg-4">
        <div class="hero-banner h-100">
            <div class="position-relative" style="z-index:1">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="d-flex align-items-center justify-content-center rounded-3"
                         style="width:44px;height:44px;background:rgba(255,255,255,.12)">
                        <i class="bi bi-shop text-white" style="font-size:1.3rem"></i>
                    </div>
                    <div>
                        <div style="font-size:.7rem; color:rgba(255,255,255,.5); font-weight:600; letter-spacing:.5px">SISTEM AKTIF</div>
                        <div class="fw-800" style="font-size:.95rem">Catering Family Jakarta</div>
                    </div>
                </div>
                <p style="font-size:.82rem; color:rgba(255,255,255,.6); line-height:1.7; margin-bottom:20px">
                    Sistem pemesanan catering berbasis web untuk meningkatkan efisiensi proses pemesanan.
                </p>
                <div class="d-flex gap-2 mb-4">
                    @foreach([
                        ['Pending', $totalPending,   'bi-hourglass-split'],
                        ['Selesai', $totalCompleted, 'bi-check-circle'],
                        ['Revenue', 'Rp'.number_format($totalRevenue/1000000,1).'jt', 'bi-cash-coin'],
                    ] as [$k, $v, $ic])
                    <div class="hero-stat-pill">
                        <div><i class="bi {{ $ic }}" style="font-size:.9rem; color:rgba(255,255,255,.6)"></i></div>
                        <div class="fw-800 mt-1" style="font-size:.92rem">{{ $v }}</div>
                        <div style="font-size:.68rem; color:rgba(255,255,255,.45)">{{ $k }}</div>
                    </div>
                    @endforeach
                </div>
                <a href="{{ route('home') }}" target="_blank"
                   class="btn btn-sm rounded-pill px-4 fw-700"
                   style="background:rgba(255,255,255,.15); color:#fff; border:1px solid rgba(255,255,255,.2); font-size:.8rem">
                    <i class="bi bi-globe me-1"></i> Lihat Website
                </a>
            </div>
        </div>
    </div>

    {{-- Chart --}}
    <div class="col-lg-8">
        <div class="panel-card h-100">
            <div class="panel-card-header">
                <div>
                    <p class="panel-card-title">Ringkasan Pesanan</p>
                    <p class="panel-card-sub">7 hari terakhir</p>
                </div>
                <div class="d-flex gap-3" style="font-size:.75rem">
                    <span class="d-flex align-items-center gap-1 text-muted">
                        <span style="width:8px;height:8px;border-radius:50%;background:#C0392B;display:inline-block"></span> Total
                    </span>
                    <span class="d-flex align-items-center gap-1 text-muted">
                        <span style="width:8px;height:8px;border-radius:50%;background:#E67E22;display:inline-block"></span> Selesai
                    </span>
                </div>
            </div>
            <div class="panel-card-body">
                <canvas id="salesChart" height="170"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- ── Tabel + Sidebar ── --}}
<div class="row g-4">

    {{-- Pesanan Terbaru --}}
    <div class="col-lg-8">
        <div class="panel-card">
            <div class="panel-card-header">
                <div>
                    <p class="panel-card-title">Pesanan Terbaru</p>
                    <p class="panel-card-sub">Daftar pesanan masuk terkini</p>
                </div>
                <a href="{{ route('admin.orders.index') }}"
                   class="btn btn-sm btn-outline-danger rounded-pill px-3 fw-600"
                   style="font-size:.78rem">Lihat Semua</a>
            </div>
            @if($recentOrders->count())
            <div class="table-responsive">
                <table class="table orders-tbl mb-0">
                    <thead>
                        <tr>
                            <th>No. Pesanan</th>
                            <th>Pelanggan</th>
                            <th>Total</th>
                            <th>Bayar</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentOrders as $order)
                        <tr>
                            <td>
                                <a href="{{ route('admin.orders.show', $order) }}"
                                   class="fw-700 text-danger text-decoration-none"
                                   style="font-size:.82rem">{{ $order->order_number }}</a>
                                <div class="text-muted" style="font-size:.7rem">
                                    {{ $order->created_at->format('d M Y') }}
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center fw-800 text-white flex-shrink-0"
                                         style="width:30px;height:30px;font-size:.7rem;background:linear-gradient(135deg,var(--cfj-primary),var(--cfj-secondary))">
                                        {{ strtoupper(substr($order->nama_pemesan_display, 0, 1)) }}
                                    </div>
                                    <span class="fw-600" style="font-size:.83rem">{{ Str::limit($order->nama_pemesan_display, 16) }}</span>
                                </div>
                            </td>
                            <td class="fw-700 text-danger" style="font-size:.83rem">{{ $order->formatted_total }}</td>
                            <td>
                                @if($order->payment)
                                    <span class="status-badge badge-{{ $order->payment->status }}" style="font-size:.68rem">
                                        {{ match($order->payment->status) {
                                            'unpaid'               => 'Belum Bayar',
                                            'pending_verification' => 'Verifikasi',
                                            'paid'                 => 'Lunas',
                                            'failed'               => 'Gagal',
                                            default                => '-'
                                        } }}
                                    </span>
                                @else
                                    <span class="text-muted" style="font-size:.82rem">—</span>
                                @endif
                            </td>
                            <td>
                                <span class="status-badge badge-{{ $order->status }}" style="font-size:.68rem">
                                    {{ $order->status_label }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-5 text-muted">
                <i class="bi bi-clipboard" style="font-size:2.5rem; opacity:.25"></i>
                <p class="mt-3 small fw-600">Belum ada pesanan masuk</p>
            </div>
            @endif
        </div>
    </div>

    {{-- Sidebar kanan --}}
    <div class="col-lg-4 d-flex flex-column gap-4">

        {{-- Aksi Cepat --}}
        <div class="panel-card">
            <div class="panel-card-header">
                <p class="panel-card-title">
                    <i class="bi bi-lightning-charge text-warning me-1"></i> Aksi Cepat
                </p>
            </div>
            <div class="panel-card-body d-flex flex-column gap-2">
                @foreach([
                    [route('admin.orders.index'),   'bi-bag-check',   'red',    'Kelola Pesanan',   'Lihat & proses pesanan'],
                    [route('admin.payments.index'), 'bi-credit-card', 'orange', 'Verifikasi Bayar', 'Cek bukti pembayaran'],
                    [route('admin.menus.create'),   'bi-plus-circle', 'green',  'Tambah Menu',      'Tambah item baru'],
                    [route('admin.reports.index'),  'bi-bar-chart',   'purple', 'Laporan',          'Lihat pendapatan'],
                ] as [$url, $icon, $color, $title, $desc])
                <a href="{{ $url }}" class="qa-item">
                    <div class="qa-icon stat-icon-v2 {{ $color }}">
                        <i class="bi {{ $icon }}"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-700" style="font-size:.83rem">{{ $title }}</div>
                        <div style="font-size:.72rem; color:#9CA3AF">{{ $desc }}</div>
                    </div>
                    <i class="bi bi-chevron-right" style="font-size:.75rem; color:#D1D5DB"></i>
                </a>
                @endforeach
            </div>
        </div>

        {{-- Info Sistem --}}
        <div class="panel-card">
            <div class="panel-card-header">
                <p class="panel-card-title">
                    <i class="bi bi-info-circle text-secondary me-1"></i> Info Sistem
                </p>
            </div>
            <div class="panel-card-body">
                @foreach([
                    ['Framework', 'Laravel '.app()->version()],
                    ['PHP',       phpversion()],
                    ['Database',  'MySQL'],
                    ['Tanggal',   now()->format('d M Y')],
                    ['Waktu',     now()->format('H:i') . ' WIB'],
                ] as [$k, $v])
                <div class="info-row">
                    <span class="info-key">{{ $k }}</span>
                    <span class="info-val">{{ $v }}</span>
                </div>
                @endforeach
                <div class="info-row">
                    <span class="info-key">Rating</span>
                    <span class="info-val">
                        <i class="bi bi-star-fill" style="color:#F59E0B;font-size:.8rem"></i>
                        {{ number_format($avgRating, 1) }} ({{ $totalReviews }} review)
                    </span>
                </div>
                <div class="info-row" style="border-bottom:none">
                    <span class="info-key">Status</span>
                    <span class="badge rounded-pill px-3"
                          style="background:#D1FAE5; color:#065F46; font-size:.72rem; font-weight:700">
                        <i class="bi bi-circle-fill me-1" style="font-size:.4rem; vertical-align:middle"></i>Online
                    </span>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- ── Chart Pendapatan ── --}}
<div class="row g-4 mb-4 mt-0">
    <div class="col-lg-8">
        <div class="panel-card">
            <div class="panel-card-header">
                <div>
                    <p class="panel-card-title">Tren Pendapatan</p>
                    <p class="panel-card-sub">7 hari terakhir</p>
                </div>
            </div>
            <div class="panel-card-body">
                <canvas id="revenueChart" height="130"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="panel-card h-100">
            <div class="panel-card-header">
                <p class="panel-card-title">Distribusi Status</p>
            </div>
            <div class="panel-card-body d-flex align-items-center justify-content-center">
                <canvas id="statusChart" height="220"></canvas>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
(function() {
    // ── Data dari controller ──
    const labels       = @json($chartLabels);
    const totalData    = @json($chartData);
    const completedData = @json($chartCompleted);
    const revenueData  = @json($chartRevenueData);
    const statusDist   = @json($statusDist);

    // ── Chart Pesanan ──
    const ctx = document.getElementById('salesChart').getContext('2d');
    const gradRed = ctx.createLinearGradient(0, 0, 0, 240);
    gradRed.addColorStop(0, 'rgba(192,57,43,.18)');
    gradRed.addColorStop(1, 'rgba(192,57,43,0)');
    const gradOrange = ctx.createLinearGradient(0, 0, 0, 240);
    gradOrange.addColorStop(0, 'rgba(230,126,34,.15)');
    gradOrange.addColorStop(1, 'rgba(230,126,34,0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Total Pesanan',
                    data: totalData,
                    borderColor: '#C0392B',
                    backgroundColor: gradRed,
                    borderWidth: 2, fill: true, tension: 0.45,
                    pointRadius: 3.5, pointBackgroundColor: '#C0392B',
                    pointBorderColor: '#fff', pointBorderWidth: 2,
                },
                {
                    label: 'Pesanan Selesai',
                    data: completedData,
                    borderColor: '#E67E22',
                    backgroundColor: gradOrange,
                    borderWidth: 2, fill: true, tension: 0.45,
                    pointRadius: 3.5, pointBackgroundColor: '#E67E22',
                    pointBorderColor: '#fff', pointBorderWidth: 2,
                }
            ]
        },
        options: {
            responsive: true, maintainAspectRatio: true,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#111827', titleColor: '#fff',
                    bodyColor: 'rgba(255,255,255,.65)', padding: 12, cornerRadius: 10,
                    callbacks: { label: ctx => '  ' + ctx.dataset.label + ': ' + ctx.parsed.y + ' pesanan' }
                }
            },
            scales: {
                x: { grid: { display: false }, border: { display: false }, ticks: { font: { size: 11 }, color: '#9CA3AF' } },
                y: { grid: { color: '#F9FAFB' }, border: { display: false }, ticks: { font: { size: 11 }, color: '#9CA3AF', stepSize: 1 }, beginAtZero: true }
            }
        }
    });

    // ── Chart Revenue ──
    const ctx2 = document.getElementById('revenueChart').getContext('2d');
    const gradGreen = ctx2.createLinearGradient(0, 0, 0, 200);
    gradGreen.addColorStop(0, 'rgba(16,185,129,.2)');
    gradGreen.addColorStop(1, 'rgba(16,185,129,0)');

    new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Pendapatan',
                data: revenueData,
                backgroundColor: 'rgba(16,185,129,.7)',
                borderColor: '#10B981',
                borderWidth: 1,
                borderRadius: 6,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#111827', padding: 12, cornerRadius: 10,
                    callbacks: {
                        label: ctx => '  Rp ' + new Intl.NumberFormat('id-ID').format(ctx.parsed.y)
                    }
                }
            },
            scales: {
                x: { grid: { display: false }, border: { display: false }, ticks: { font: { size: 11 }, color: '#9CA3AF' } },
                y: {
                    grid: { color: '#F9FAFB' }, border: { display: false },
                    ticks: {
                        font: { size: 10 }, color: '#9CA3AF',
                        callback: val => 'Rp ' + (val/1000000).toFixed(1) + 'jt'
                    },
                    beginAtZero: true
                }
            }
        }
    });

    // ── Chart Status Distribusi ──
    const statusLabels = {
        pending: 'Menunggu', confirmed: 'Dikonfirmasi', processing: 'Diproses',
        delivered: 'Dikirim', completed: 'Selesai', cancelled: 'Dibatalkan'
    };
    const statusColors = {
        pending: '#F59E0B', confirmed: '#3B82F6', processing: '#6366F1',
        delivered: '#8B5CF6', completed: '#10B981', cancelled: '#EF4444'
    };
    const sLabels = Object.keys(statusDist).map(k => statusLabels[k] || k);
    const sData   = Object.values(statusDist);
    const sColors = Object.keys(statusDist).map(k => statusColors[k] || '#9CA3AF');

    new Chart(document.getElementById('statusChart'), {
        type: 'doughnut',
        data: {
            labels: sLabels,
            datasets: [{
                data: sData,
                backgroundColor: sColors,
                borderWidth: 2,
                borderColor: '#fff',
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: true,
            cutout: '65%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { font: { size: 11, weight: '600' }, padding: 12, usePointStyle: true, pointStyleWidth: 8 }
                }
            }
        }
    });
})();
</script>
@endpush

@extends('layouts.app')
@section('title', 'Dashboard Saya — Catering Family Jakarta')

@section('content')
<div class="container py-5">

    <div class="mb-4">
        <h4 class="fw-800 mb-1">Halo, {{ Auth::user()->name }}! 👋</h4>
        <p class="text-muted">Selamat datang di dashboard pemesanan Anda.</p>
    </div>

    {{-- Stat Cards --}}
    <div class="row g-3 mb-5">
        @foreach([
            ['Total Pesanan',    $totalOrders,    '#FEE2E2', '#C0392B', 'bi-bag-check'],
            ['Pesanan Aktif',    $activeOrders,   '#FEF3C7', '#D97706', 'bi-hourglass-split'],
            ['Pesanan Selesai',  $completedOrders,'#D1FAE5', '#059669', 'bi-check-circle'],
            ['Total Belanja',    'Rp '.number_format($totalSpent/1000000,1).'jt', '#EDE9FE', '#7C3AED', 'bi-cash-coin'],
        ] as [$label, $val, $bg, $color, $icon])
        <div class="col-6 col-md-3">
            <div class="card text-center p-4">
                <div class="d-flex align-items-center justify-content-center rounded-3 mx-auto mb-3"
                     style="width:48px;height:48px;background:{{ $bg }}">
                    <i class="bi {{ $icon }}" style="color:{{ $color }};font-size:1.2rem"></i>
                </div>
                <div class="fw-800 mb-1" style="font-size:1.4rem;color:{{ $color }}">{{ $val }}</div>
                <div class="text-muted small fw-600">{{ $label }}</div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="row g-4">

        {{-- Pesanan Terbaru --}}
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span class="fw-700"><i class="bi bi-clock-history me-2 text-danger"></i>Pesanan Terbaru</span>
                    <a href="{{ route('customer.orders.index') }}"
                       class="btn btn-sm btn-outline-danger rounded-pill px-3" style="font-size:.8rem">
                        Lihat Semua
                    </a>
                </div>
                <div class="card-body p-0">
                    @if($recentOrders->count())
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>No. Pesanan</th>
                                    <th>Tanggal Acara</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentOrders as $order)
                                <tr>
                                    <td>
                                        <div class="fw-700 text-danger" style="font-size:.85rem">{{ $order->order_number }}</div>
                                        <div class="text-muted" style="font-size:.73rem">{{ $order->created_at->format('d M Y') }}</div>
                                    </td>
                                    <td class="small fw-600">{{ $order->event_date->format('d M Y') }}</td>
                                    <td class="fw-700 text-danger small">{{ $order->formatted_total }}</td>
                                    <td>
                                        <span class="badge rounded-pill px-3"
                                              style="font-size:.75rem;font-weight:700;
                                                     background:{{ match($order->status) {
                                                         'pending'=>'#FFF3CD','confirmed'=>'#CCE5FF',
                                                         'processing'=>'#D1ECF1','delivered'=>'#E2E3E5',
                                                         'completed'=>'#D4EDDA','cancelled'=>'#F8D7DA',
                                                         default=>'#E2E3E5'
                                                     } }};
                                                     color:{{ match($order->status) {
                                                         'pending'=>'#856404','confirmed'=>'#004085',
                                                         'processing'=>'#0c5460','delivered'=>'#383d41',
                                                         'completed'=>'#155724','cancelled'=>'#721c24',
                                                         default=>'#383d41'
                                                     } }}">
                                            {{ $order->status_label }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('customer.orders.show', $order) }}"
                                           class="btn btn-sm btn-outline-secondary rounded-pill px-3"
                                           style="font-size:.78rem">Detail</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-bag" style="font-size:2.5rem;opacity:.2"></i>
                        <p class="mt-3 small fw-600">Belum ada pesanan</p>
                        <a href="{{ route('customer.menu') }}"
                           class="btn btn-sm btn-danger rounded-pill px-4">Pesan Sekarang</a>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Kanan: Bayar & Aksi Cepat --}}
        <div class="col-lg-4 d-flex flex-column gap-4">

            {{-- Pembayaran Pending --}}
            @if($pendingPayments->count())
            <div class="card border-warning">
                <div class="card-header fw-700" style="background:#FFFBEB;border-color:#FDE68A">
                    <i class="bi bi-exclamation-triangle-fill text-warning me-2"></i>Perlu Dibayar
                </div>
                <div class="card-body">
                    @foreach($pendingPayments as $order)
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                        <div>
                            <div class="fw-700" style="font-size:.83rem">{{ $order->order_number }}</div>
                            <div class="text-danger fw-700 small">{{ $order->formatted_total }}</div>
                        </div>
                        <a href="{{ route('customer.payment.show', $order) }}"
                           class="btn btn-sm btn-warning rounded-pill px-3 fw-700" style="font-size:.78rem">
                            Bayar
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Aksi Cepat --}}
            <div class="card">
                <div class="card-header fw-700">
                    <i class="bi bi-lightning-charge text-warning me-2"></i>Aksi Cepat
                </div>
                <div class="card-body d-flex flex-column gap-2">
                    @foreach([
                        [route('customer.menu'),         'bi-grid',          'Lihat Katalog Menu'],
                        [route('customer.cart'),         'bi-bag2',          'Keranjang Saya'],
                        [route('customer.orders.index'), 'bi-clock-history', 'Riwayat Pesanan'],
                        [route('customer.track'),        'bi-search',        'Lacak Pesanan'],
                    ] as [$url, $icon, $label])
                    <a href="{{ $url }}"
                       class="d-flex align-items-center gap-3 p-3 rounded-3 text-decoration-none text-dark"
                       style="border:1.5px solid #F3F4F6;transition:all .18s"
                       onmouseover="this.style.borderColor='#C0392B';this.style.background='#FEF2F2'"
                       onmouseout="this.style.borderColor='#F3F4F6';this.style.background=''">
                        <div class="d-flex align-items-center justify-content-center rounded-2"
                             style="width:36px;height:36px;background:#FEE2E2;color:#C0392B;flex-shrink:0">
                            <i class="bi {{ $icon }}"></i>
                        </div>
                        <span class="fw-600" style="font-size:.85rem">{{ $label }}</span>
                        <i class="bi bi-chevron-right ms-auto text-muted" style="font-size:.75rem"></i>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')
@section('title', 'Lacak Pesanan — Catering Family Jakarta')

@section('content')
<div class="container py-5" style="max-width:720px">

    {{-- Hero --}}
    <div class="text-center mb-5">
        <div style="font-size:3rem; filter:drop-shadow(0 4px 12px rgba(192,57,43,.15))">🔍</div>
        <h3 class="fw-800 mt-3 mb-2">Lacak Status Pesanan</h3>
        <p class="text-muted">Masukkan kode pesanan untuk melihat status terkini</p>
    </div>

    {{-- Form Cari --}}
    <div class="card mb-5 shadow-sm">
        <div class="card-body p-4">
            <form action="{{ route('customer.track') }}" method="GET">
                <label class="form-label fw-700 small">Kode Pesanan</label>
                <div class="input-group input-group-lg">
                    <span class="input-group-text bg-light border-end-0">
                        <i class="bi bi-upc-scan text-danger"></i>
                    </span>
                    <input type="text" name="kode"
                           class="form-control border-start-0 ps-0"
                           placeholder="Contoh: CFJ-20260414-0001"
                           value="{{ request('kode') }}" required
                           style="text-transform:uppercase; letter-spacing:1.5px; font-weight:700">
                    <button type="submit" class="btn btn-danger px-4 fw-700 rounded-end-3">
                        Lacak
                    </button>
                </div>
                <div class="form-text mt-2">
                    <i class="bi bi-info-circle me-1"></i>
                    Kode pesanan dikirimkan saat pemesanan berhasil dibuat.
                </div>
            </form>
        </div>
    </div>

    {{-- Hasil --}}
    @if(isset($order))
    <div class="card shadow-sm mb-4">
        <div class="card-header d-flex justify-content-between align-items-center py-3">
            <div>
                <span class="fw-800 text-danger">{{ $order->order_number }}</span>
                <div class="text-muted" style="font-size:.75rem; margin-top:2px">
                    Dipesan: {{ $order->created_at->format('d M Y') }}
                </div>
            </div>
            <span class="status-badge status-{{ $order->status }}">{{ $order->status_label }}</span>
        </div>
        <div class="card-body">

            {{-- Timeline --}}
            @php
                $steps = [
                    'pending'    => ['label' => 'Pesanan Masuk',    'icon' => '📋'],
                    'confirmed'  => ['label' => 'Dikonfirmasi',     'icon' => '✅'],
                    'processing' => ['label' => 'Diproses',         'icon' => '👨‍🍳'],
                    'delivered'  => ['label' => 'Dikirim',          'icon' => '🚚'],
                    'completed'  => ['label' => 'Selesai',          'icon' => '🎉'],
                ];
                $statuses   = array_keys($steps);
                $currentIdx = $order->status === 'cancelled' ? -1 : array_search($order->status, $statuses);
            @endphp

            @if($order->status === 'cancelled')
            <div class="alert alert-danger rounded-3 mb-4 d-flex align-items-center gap-2">
                <i class="bi bi-x-circle-fill fs-5 flex-shrink-0"></i>
                <div>
                    <strong>Pesanan Dibatalkan</strong>
                    @if($order->cancelled_reason) — {{ $order->cancelled_reason }} @endif
                </div>
            </div>
            @else
            <div class="mb-5 mt-2">
                <div class="position-relative d-flex justify-content-between">
                    {{-- Track line background --}}
                    <div class="position-absolute"
                         style="height:3px; background:#E9ECEF; top:21px; left:24px; right:24px; z-index:0"></div>
                    {{-- Track line progress --}}
                    @if($currentIdx > 0)
                    <div class="position-absolute"
                         style="height:3px; background:linear-gradient(90deg,var(--cfj-primary),var(--cfj-secondary));
                                top:21px; left:24px; z-index:1;
                                width:calc({{ ($currentIdx / (count($steps)-1)) * 100 }}% - 48px + {{ ($currentIdx / (count($steps)-1)) * 48 }}px);
                                transition:width .5s ease;"></div>
                    @endif

                    @foreach($steps as $key => $step)
                    @php $idx = array_search($key, $statuses); $done = $idx <= $currentIdx; @endphp
                    <div class="text-center position-relative" style="z-index:2; flex:1">
                        <div class="d-flex align-items-center justify-content-center mx-auto rounded-circle mb-2"
                             style="width:42px; height:42px; font-size:1.1rem;
                                    background:{{ $done ? 'linear-gradient(135deg,var(--cfj-primary),var(--cfj-secondary))' : '#E9ECEF' }};
                                    box-shadow:{{ $done ? '0 4px 12px rgba(192,57,43,.3)' : 'none' }};
                                    transition:all .3s">
                            @if($done)
                                {{ $step['icon'] }}
                            @else
                                <span style="font-size:.75rem; color:#aaa; font-weight:700">{{ $idx+1 }}</span>
                            @endif
                        </div>
                        <div class="fw-{{ $done ? '700' : '500' }}"
                             style="font-size:.72rem; color:{{ $done ? 'var(--cfj-primary)' : '#aaa' }}; line-height:1.3">
                            {{ $step['label'] }}
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Info Grid --}}
            <div class="row g-3 mb-4">
                @foreach([
                    ['Pemesan', $order->user->name ?? '-'],
                    ['Tanggal Acara', $order->event_date->format('d M Y')],
                    ['Total Pax', $order->total_pax . ' pax'],
                    ['Total Bayar', $order->formatted_total],
                ] as [$label, $value])
                <div class="col-6 col-md-3">
                    <div class="text-muted small">{{ $label }}</div>
                    <div class="fw-700 mt-1">{{ $value }}</div>
                </div>
                @endforeach
            </div>

            <div class="mb-4 p-3 rounded-3" style="background:#F8F9FA">
                <div class="text-muted small mb-1"><i class="bi bi-geo-alt me-1"></i>Alamat Pengiriman</div>
                <div class="fw-600">{{ $order->event_address }}, {{ $order->event_city }}</div>
            </div>

            {{-- Item List --}}
            <div class="border rounded-3 overflow-hidden mb-4">
                <div class="bg-light px-3 py-2 fw-700 small border-bottom">
                    <i class="bi bi-bag2 me-2 text-danger"></i>Item Pesanan
                </div>
                @foreach($order->orderItems as $item)
                <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom">
                    <div>
                        <div class="fw-600 small">{{ $item->menu_name }}</div>
                        <div class="text-muted" style="font-size:.78rem">
                            {{ $item->pax }} pax × Rp {{ number_format($item->price, 0, ',', '.') }}
                        </div>
                    </div>
                    <div class="fw-700 text-danger small">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</div>
                </div>
                @endforeach

                @foreach($order->customMenus as $custom)
                <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom"
                     style="background:#FFFDF5">
                    <div>
                        <div class="fw-600 small">
                            ✏️ {{ $custom->item_name }}
                            <span class="badge ms-1" style="background:#FFF3CD;color:#856404;font-size:.68rem">Custom</span>
                        </div>
                        <div class="text-muted" style="font-size:.78rem">{{ $custom->pax }} pax</div>
                    </div>
                    <div>
                        @if($custom->status === 'approved')
                            <span class="fw-700 text-danger small">Rp {{ number_format($custom->subtotal, 0, ',', '.') }}</span>
                        @elseif($custom->status === 'rejected')
                            <span class="badge" style="background:#F8D7DA;color:#721c24">Ditolak</span>
                        @else
                            <span class="badge" style="background:#FFF3CD;color:#856404;font-size:.72rem">Menunggu harga</span>
                        @endif
                    </div>
                </div>
                @endforeach

                {{-- Total Breakdown --}}
                <div class="px-3 py-3 bg-light">
                    @foreach([
                        ['Subtotal', 'Rp ' . number_format($order->subtotal, 0, ',', '.')],
                        ['Ongkos Kirim', 'Rp ' . number_format($order->delivery_fee, 0, ',', '.')],
                        ['Biaya Layanan', 'Rp ' . number_format($order->service_fee, 0, ',', '.')],
                    ] as [$label, $val])
                    <div class="d-flex justify-content-between small text-muted mb-1">
                        <span>{{ $label }}</span><span>{{ $val }}</span>
                    </div>
                    @endforeach
                    <div class="d-flex justify-content-between fw-800 mt-2 pt-2 border-top">
                        <span>Total</span>
                        <span class="text-danger">{{ $order->formatted_total }}</span>
                    </div>
                </div>
            </div>

            {{-- Status Pembayaran --}}
            @if($order->payment)
            <div class="alert rounded-3 mb-0 d-flex align-items-center gap-2
                {{ $order->payment->status === 'paid' ? 'alert-success' :
                   ($order->payment->status === 'pending_verification' ? 'alert-warning' : 'alert-secondary') }}">
                <i class="bi bi-credit-card fs-5 flex-shrink-0"></i>
                <div>
                    <strong>Pembayaran:</strong>
                    {{ match($order->payment->status) {
                        'unpaid'               => 'Belum Bayar',
                        'pending_verification' => 'Bukti sedang diverifikasi admin',
                        'paid'                 => '✅ Lunas',
                        'failed'               => 'Pembayaran ditolak',
                        default                => '-'
                    } }}
                    @if($order->payment->status === 'unpaid')
                        — <a href="{{ route('customer.payment.show', $order) }}" class="alert-link fw-700">Bayar sekarang</a>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>

    @elseif(request('kode'))
    <div class="card shadow-sm text-center py-5">
        <div style="font-size:3rem; opacity:.4">❌</div>
        <h5 class="fw-800 mt-3">Pesanan Tidak Ditemukan</h5>
        <p class="text-muted">
            Kode <strong>{{ strtoupper(request('kode')) }}</strong> tidak ditemukan.<br>
            Pastikan kode yang dimasukkan sudah benar.
        </p>
    </div>
    @endif

    <div class="text-center mt-5">
        <a href="{{ route('customer.menu') }}" class="btn btn-outline-danger rounded-pill px-4 fw-600">
            <i class="bi bi-grid me-2"></i>Lihat Menu
        </a>
    </div>
</div>
@endsection

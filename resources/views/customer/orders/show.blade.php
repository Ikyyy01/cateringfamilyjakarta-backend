@extends('layouts.app')
@section('title', 'Detail Pesanan — ' . $order->order_number)

@section('content')
<div class="container py-5">
    <div class="d-flex align-items-center gap-3 mb-4">
        @auth
        <a href="{{ route('customer.orders.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill">
            <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
        @else
        <a href="{{ route('customer.track') }}?kode={{ $order->order_number }}" class="btn btn-outline-secondary btn-sm rounded-pill">
            <i class="bi bi-arrow-left me-1"></i>Lacak Pesanan
        </a>
        @endauth
        <div>
            <h5 class="fw-800 mb-0">{{ $order->order_number }}</h5>
            <span class="text-muted small">Dipesan {{ $order->created_at->format('d M Y, H:i') }}</span>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">

            {{-- Status Timeline --}}
            <div class="card mb-4">
                <div class="card-header"><i class="bi bi-clock-history me-2"></i>Status Pesanan</div>
                <div class="card-body">
                    @php
                        $steps = ['pending'=>'Menunggu','confirmed'=>'Dikonfirmasi','processing'=>'Diproses','delivered'=>'Dikirim','completed'=>'Selesai'];
                        $statuses = array_keys($steps);
                        $currentIdx = array_search($order->status, $statuses);
                    @endphp
                    @if($order->status !== 'cancelled')
                    <div class="d-flex align-items-center justify-content-between position-relative">
                        <div class="position-absolute top-50 start-0 end-0 translate-middle-y bg-light" style="height:3px;z-index:0"></div>
                        @foreach($steps as $key => $label)
                        @php $idx = array_search($key, $statuses); @endphp
                        <div class="text-center position-relative" style="z-index:1">
                            <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2 fw-700"
                                 style="width:36px;height:36px;font-size:.85rem;
                                        background:{{ $idx <= $currentIdx ? '#C0392B' : '#e9ecef' }};
                                        color:{{ $idx <= $currentIdx ? '#fff' : '#aaa' }}">
                                {{ $idx + 1 }}
                            </div>
                            <div class="small fw-600 {{ $idx <= $currentIdx ? 'text-danger' : 'text-muted' }}">{{ $label }}</div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="alert alert-danger mb-0">
                        <i class="bi bi-x-circle me-2"></i>Pesanan dibatalkan.
                        @if($order->cancelled_reason) Alasan: {{ $order->cancelled_reason }} @endif
                    </div>
                    @endif
                </div>
            </div>

            {{-- Item Pesanan --}}
            <div class="card mb-4">
                <div class="card-header"><i class="bi bi-bag2 me-2"></i>Item Pesanan</div>
                <div class="card-body p-0">
                    @foreach($order->orderItems as $item)
                    <div class="d-flex align-items-center gap-3 p-3 border-bottom">
                        <div class="d-flex align-items-center justify-content-center bg-light rounded-3 flex-shrink-0"
                             style="width:52px;height:52px;font-size:1.6rem">🍛</div>
                        <div class="flex-grow-1">
                            <div class="fw-700">{{ $item->menu_name }}</div>
                            <div class="text-muted small">Rp {{ number_format($item->price, 0, ',', '.') }} × {{ $item->pax }} pax</div>
                        </div>
                        <div class="fw-800 text-danger">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</div>
                    </div>
                    @endforeach

                    @foreach($order->customMenus as $custom)
                    <div class="d-flex align-items-center gap-3 p-3 border-bottom bg-light">
                        <div class="d-flex align-items-center justify-content-center bg-warning-subtle rounded-3 flex-shrink-0"
                             style="width:52px;height:52px;font-size:1.6rem">✏️</div>
                        <div class="flex-grow-1">
                            <div class="fw-700">{{ $custom->item_name }}
                                <span class="badge bg-warning text-dark ms-1 small">Custom</span>
                            </div>
                            <div class="text-muted small">{{ $custom->pax }} pax
                                @if($custom->description) · {{ $custom->description }} @endif
                            </div>
                            @if($custom->admin_notes)
                            <div class="text-info small"><i class="bi bi-info-circle me-1"></i>{{ $custom->admin_notes }}</div>
                            @endif
                        </div>
                        <div class="text-end">
                            @if($custom->status === 'approved')
                                <div class="fw-800 text-danger">Rp {{ number_format($custom->subtotal, 0, ',', '.') }}</div>
                                <span class="badge bg-success-subtle text-success">Disetujui</span>
                            @elseif($custom->status === 'rejected')
                                <span class="badge bg-danger-subtle text-danger">Ditolak</span>
                            @else
                                <span class="badge bg-warning-subtle text-warning">Menunggu</span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Pembayaran --}}
            @if($order->payment)
            <div class="card">
                <div class="card-header"><i class="bi bi-credit-card me-2"></i>Pembayaran</div>
                <div class="card-body">
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <div class="text-muted small">Metode</div>
                            <div class="fw-700 text-capitalize">{{ $order->payment->method }}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-muted small">Status</div>
                            <span class="status-badge status-{{ $order->payment->status }}">
                                {{ match($order->payment->status) {
                                    'unpaid'               => 'Belum Bayar',
                                    'pending_verification' => 'Menunggu Verifikasi',
                                    'paid'                 => 'Lunas',
                                    'failed'               => 'Gagal',
                                    default => '-'
                                } }}
                            </span>
                        </div>
                        @if($order->payment->paid_at)
                        <div class="col-md-4">
                            <div class="text-muted small">Dibayar</div>
                            <div class="fw-700">{{ $order->payment->paid_at->format('d M Y') }}</div>
                        </div>
                        @endif
                    </div>
                    @if(in_array($order->payment->status, ['unpaid','failed']))
                    <a href="{{ route('customer.payment.show', $order) }}" class="btn btn-danger rounded-pill px-4">
                        <i class="bi bi-credit-card me-2"></i>Bayar Sekarang
                    </a>
                    @endif
                </div>
            </div>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header"><i class="bi bi-geo-alt me-2"></i>Detail Acara</div>
                <div class="card-body">
                    <div class="mb-2"><span class="text-muted small d-block">Tanggal</span><strong>{{ $order->event_date->format('d M Y') }}</strong></div>
                    <div class="mb-2"><span class="text-muted small d-block">Kota</span><strong>{{ $order->event_city }}</strong></div>
                    <div class="mb-2"><span class="text-muted small d-block">Alamat</span><strong>{{ $order->event_address }}</strong></div>
                    <div><span class="text-muted small d-block">Total Pax</span><strong>{{ $order->total_pax }} pax</strong></div>
                </div>
            </div>
            <div class="card">
                <div class="card-header"><i class="bi bi-receipt me-2"></i>Rincian Harga</div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2 text-muted small">
                        <span>Subtotal Menu</span>
                        <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2 text-muted small">
                        <span>Ongkos Kirim</span>
                        <span>Rp {{ number_format($order->delivery_fee, 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2 text-muted small">
                        <span>Biaya Layanan</span>
                        <span>Rp {{ number_format($order->service_fee, 0, ',', '.') }}</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between fw-800 fs-5">
                        <span>Total</span>
                        <span class="text-danger">{{ $order->formatted_total }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

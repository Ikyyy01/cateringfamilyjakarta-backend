@extends('layouts.admin')
@section('title', 'Detail Pesanan — ' . $order->order_number)
@section('page-title', 'Detail Pesanan')

@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
        <i class="bi bi-arrow-left me-1"></i>Kembali
    </a>
    <div>
        <h5 class="fw-800 mb-0 text-danger">{{ $order->order_number }}</h5>
        <span class="text-muted small">{{ $order->created_at->format('d M Y, H:i') }}</span>
    </div>
    <span class="status-badge badge-{{ $order->status }} ms-auto">{{ $order->status_label }}</span>
</div>

<div class="row g-4">

    {{-- KIRI --}}
    <div class="col-lg-8">

        {{-- Item Pesanan --}}
        <div class="card mb-4">
            <div class="card-header"><i class="bi bi-bag2 me-2 text-danger"></i>Item Pesanan</div>
            <div class="card-body p-0">
                @foreach($order->orderItems as $item)
                <div class="d-flex align-items-center gap-3 p-3 border-bottom">
                    <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                         style="width:52px;height:52px;background:linear-gradient(135deg,#fff5f3,#fff0e8);font-size:1.6rem">🍛</div>
                    <div class="flex-grow-1">
                        <div class="fw-700">{{ $item->menu_name }}</div>
                        <div class="text-muted small">Rp {{ number_format($item->price, 0, ',', '.') }} × {{ $item->pax }} pax</div>
                    </div>
                    <div class="fw-800 text-danger">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Custom Menus --}}
        @if($order->customMenus->count())
        <div class="card mb-4">
            <div class="card-header"><i class="bi bi-pencil-square me-2 text-danger"></i>Custom Menu</div>
            <div class="card-body p-0">
                @foreach($order->customMenus as $custom)
                <div class="p-4 border-bottom">
                    <div class="d-flex align-items-start justify-content-between gap-3 mb-2">
                        <div class="flex-grow-1">
                            <div class="fw-700">{{ $custom->item_name }}</div>
                            <div class="text-muted small">
                                {{ $custom->pax }} pax
                                @if($custom->description) · {{ $custom->description }} @endif
                            </div>
                        </div>
                        <span class="status-badge {{ $custom->status === 'approved' ? 'badge-completed' : ($custom->status === 'rejected' ? 'badge-cancelled' : 'badge-pending') }}">
                            {{ $custom->status === 'approved' ? 'Disetujui' : ($custom->status === 'rejected' ? 'Ditolak' : 'Menunggu') }}
                        </span>
                    </div>

                    @if($custom->status === 'pending')
                    <div class="p-3 rounded-3 mt-3" style="background:#F8F9FA; border:1px solid #E9ECEF">
                        <p class="fw-700 small mb-3">Tentukan harga & respons:</p>
                        <div class="row g-2">
                            <div class="col-lg-7">
                                <form action="{{ route('admin.custom-menu.approve', $custom) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <div class="d-flex gap-2">
                                        <input type="number" name="estimated_price"
                                               class="form-control form-control-sm rounded-3"
                                               placeholder="Harga (Rp)" min="0" required>
                                        <input type="text" name="admin_notes"
                                               class="form-control form-control-sm rounded-3"
                                               placeholder="Catatan (opsional)">
                                        <button type="submit" class="btn btn-success btn-sm rounded-pill px-3 text-nowrap">
                                            <i class="bi bi-check-lg me-1"></i>Setujui
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <div class="col-lg-5">
                                <form action="{{ route('admin.custom-menu.reject', $custom) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <div class="d-flex gap-2">
                                        <input type="text" name="admin_notes"
                                               class="form-control form-control-sm rounded-3"
                                               placeholder="Alasan penolakan">
                                        <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3 text-nowrap"
                                                onclick="return confirm('Tolak custom menu ini?')">
                                            <i class="bi bi-x-lg me-1"></i>Tolak
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @elseif($custom->status === 'approved')
                    <div class="d-flex justify-content-between mt-2">
                        <span class="text-muted small">{{ $custom->admin_notes }}</span>
                        <span class="fw-800 text-danger">Rp {{ number_format($custom->subtotal, 0, ',', '.') }}</span>
                    </div>
                    @elseif($custom->status === 'rejected')
                    <div class="mt-1 text-muted small">
                        <i class="bi bi-info-circle me-1"></i>{{ $custom->admin_notes }}
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Update Status --}}
        <div class="card">
            <div class="card-header"><i class="bi bi-arrow-repeat me-2 text-danger"></i>Update Status Pesanan</div>
            <div class="card-body">
                <form action="{{ route('admin.orders.status', $order) }}" method="POST" class="d-flex gap-3 align-items-end">
                    @csrf @method('PATCH')
                    <div class="flex-grow-1">
                        <label class="form-label fw-700 small mb-1">Status Baru</label>
                        <select name="status" class="form-select rounded-3">
                            @foreach([
                                'pending'    => 'Pending',
                                'confirmed'  => 'Dikonfirmasi',
                                'processing' => 'Diproses',
                                'delivered'  => 'Dikirim',
                                'completed'  => 'Selesai',
                                'cancelled'  => 'Dibatalkan',
                            ] as $val => $label)
                            <option value="{{ $val }}" {{ $order->status === $val ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-danger rounded-pill px-4 fw-700">
                        <i class="bi bi-check-lg me-1"></i>Update
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- KANAN --}}
    <div class="col-lg-4">

        {{-- Pelanggan --}}
        <div class="card mb-4">
            <div class="card-header"><i class="bi bi-person me-2 text-danger"></i>Pelanggan</div>
            <div class="card-body">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="d-flex align-items-center justify-content-center rounded-circle fw-800 text-white flex-shrink-0"
                         style="width:44px;height:44px;font-size:1rem;background:linear-gradient(135deg,var(--cfj-primary),var(--cfj-secondary))">
                        {{ strtoupper(substr($order->user->name ?? 'U', 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <div class="fw-700 text-truncate">{{ $order->user->name ?? '-' }}</div>
                        <div class="text-muted small text-truncate">{{ $order->user->email ?? '' }}</div>
                    </div>
                </div>
                @if($order->user->phone ?? false)
                <div class="text-muted small d-flex align-items-center gap-2">
                    <i class="bi bi-telephone text-success"></i>{{ $order->user->phone }}
                </div>
                @endif
            </div>
        </div>

        {{-- Detail Acara --}}
        <div class="card mb-4">
            <div class="card-header"><i class="bi bi-calendar-event me-2 text-danger"></i>Detail Acara</div>
            <div class="card-body">
                @foreach([
                    ['Tanggal Acara', $order->event_date->format('d M Y')],
                    ['Kota', $order->event_city],
                    ['Jarak', $order->distance_km . ' km'],
                    ['Total Pax', $order->total_pax . ' pax'],
                ] as [$label, $val])
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted small">{{ $label }}</span>
                    <span class="fw-700 small text-end">{{ $val }}</span>
                </div>
                @endforeach
                <div class="mt-2 pt-2 border-top">
                    <div class="text-muted small mb-1">Alamat</div>
                    <div class="fw-600 small">{{ $order->event_address }}</div>
                </div>
                @if($order->notes)
                <div class="mt-3 p-2 rounded-2 small text-muted" style="background:#F8F9FA">
                    <i class="bi bi-chat-text me-1"></i>{{ $order->notes }}
                </div>
                @endif
            </div>
        </div>

        {{-- Rincian Harga --}}
        <div class="card">
            <div class="card-header"><i class="bi bi-receipt me-2 text-danger"></i>Rincian Harga</div>
            <div class="card-body">
                @foreach([
                    ['Subtotal Menu', 'Rp ' . number_format($order->subtotal, 0, ',', '.')],
                    ['Ongkos Kirim', 'Rp ' . number_format($order->delivery_fee, 0, ',', '.')],
                    ['Biaya Layanan', 'Rp ' . number_format($order->service_fee, 0, ',', '.')],
                ] as [$label, $val])
                <div class="d-flex justify-content-between mb-2 text-muted small">
                    <span>{{ $label }}</span><span>{{ $val }}</span>
                </div>
                @endforeach
                <hr class="my-2">
                <div class="d-flex justify-content-between fw-800" style="font-size:1.05rem">
                    <span>Total</span>
                    <span class="text-danger">{{ $order->formatted_total }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

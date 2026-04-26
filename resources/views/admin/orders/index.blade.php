@extends('layouts.admin')
@section('title', 'Kelola Pesanan')
@section('page-title', 'Kelola Pesanan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-800 mb-0">Daftar Pesanan</h5>
        <p class="text-muted small mb-0">Semua pesanan masuk dari pelanggan</p>
    </div>
    <div class="text-muted small">
        Total: <strong>{{ $orders->total() }}</strong> pesanan
    </div>
</div>

{{-- Filter & Search --}}
<div class="card mb-4">
    <div class="card-body py-3">
        <form method="GET" action="{{ route('admin.orders.index') }}" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-600 small mb-1">Cari Pesanan</label>
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control rounded-end-3"
                           placeholder="Nama pelanggan / kode pesanan…"
                           value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-600 small mb-1">Status</label>
                <select name="status" class="form-select form-select-sm rounded-3">
                    <option value="">Semua Status</option>
                    @foreach(['pending'=>'Pending','confirmed'=>'Dikonfirmasi','processing'=>'Diproses','delivered'=>'Dikirim','completed'=>'Selesai','cancelled'=>'Dibatalkan'] as $val => $label)
                        <option value="{{ $val }}" {{ request('status') == $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label fw-600 small mb-1">Dari Tanggal</label>
                <input type="date" name="from" class="form-control form-control-sm rounded-3"
                       value="{{ request('from') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label fw-600 small mb-1">Sampai</label>
                <input type="date" name="to" class="form-control form-control-sm rounded-3"
                       value="{{ request('to') }}">
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-danger btn-sm rounded-pill w-100">
                    <i class="bi bi-filter"></i>
                </button>
            </div>
        </form>
        @if(request()->hasAny(['search','status','from','to']))
        <div class="mt-2">
            <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                <i class="bi bi-x me-1"></i>Reset Filter
            </a>
        </div>
        @endif
    </div>
</div>

<div class="card">
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
                        <th>Total Harga</th>
                        <th>Pembayaran</th>
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    <tr>
                        <td>
                            <span class="fw-700 text-danger">{{ $order->order_number }}</span>
                            <div class="text-muted" style="font-size:.75rem">{{ $order->created_at->format('d M Y') }}</div>
                        </td>
                        <td>
                            <div class="fw-600">{{ $order->user->name ?? '-' }}</div>
                            <div class="text-muted small">{{ $order->user->phone ?? '' }}</div>
                        </td>
                        <td>
                            <div class="fw-600">{{ $order->event_date->format('d M Y') }}</div>
                            <div class="text-muted small">{{ $order->event_city }}</div>
                        </td>
                        <td><span class="fw-600">{{ $order->total_pax }}</span> <span class="text-muted small">pax</span></td>
                        <td class="fw-700 text-danger">{{ $order->formatted_total }}</td>
                        <td>
                            @if($order->payment)
                                <span class="status-badge badge-{{ $order->payment->status }}">
                                    {{ match($order->payment->status) {
                                        'unpaid' => 'Belum Bayar',
                                        'pending_verification' => 'Verifikasi',
                                        'paid' => 'Lunas',
                                        'failed' => 'Gagal',
                                        default => '-'
                                    } }}
                                </span>
                            @else
                                <span class="text-muted small">—</span>
                            @endif
                        </td>
                        <td>
                            <span class="status-badge badge-{{ $order->status }}">{{ $order->status_label }}</span>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.orders.show', $order) }}"
                               class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                <i class="bi bi-eye me-1"></i>Detail
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-3 d-flex justify-content-between align-items-center">
            <span class="text-muted small">Menampilkan {{ $orders->firstItem() }}–{{ $orders->lastItem() }} dari {{ $orders->total() }} pesanan</span>
            {{ $orders->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
        @else
        <div class="text-center py-5 text-muted">
            <div style="font-size:3rem">📋</div>
            <p class="mt-2">Tidak ada pesanan ditemukan</p>
            @if(request()->hasAny(['search','status','from','to']))
                <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-danger rounded-pill px-3">Reset Filter</a>
            @endif
        </div>
        @endif
    </div>
</div>
@endsection

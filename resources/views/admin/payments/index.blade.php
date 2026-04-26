@extends('layouts.admin')
@section('title', 'Kelola Pembayaran')
@section('page-title', 'Kelola Pembayaran')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-800 mb-0">Daftar Pembayaran</h5>
        <p class="text-muted small mb-0">Verifikasi bukti pembayaran dari pelanggan</p>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        @if($payments->count())
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>No. Pesanan</th>
                        <th>Pelanggan</th>
                        <th>Jumlah</th>
                        <th>Metode</th>
                        <th>Status</th>
                        <th>Bukti Transfer</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($payments as $payment)
                    <tr>
                        <td>
                            <a href="{{ route('admin.orders.show', $payment->order) }}"
                               class="fw-700 text-decoration-none text-danger">
                                {{ $payment->order->order_number ?? '-' }}
                            </a>
                        </td>
                        <td>
                            <div class="fw-600">{{ $payment->order->user->name ?? '-' }}</div>
                            <div class="text-muted small">{{ $payment->created_at->format('d M Y') }}</div>
                        </td>
                        <td class="fw-700">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                        <td class="text-capitalize">{{ $payment->method }}</td>
                        <td>
                            <span class="status-badge badge-{{ $payment->status }}">
                                {{ match($payment->status) {
                                    'unpaid'               => 'Belum Bayar',
                                    'pending_verification' => 'Menunggu Verifikasi',
                                    'paid'                 => 'Lunas',
                                    'failed'               => 'Gagal',
                                    default                => '-'
                                } }}
                            </span>
                        </td>
                        <td>
                            @if($payment->proof_image)
                                <a href="{{ Storage::url($payment->proof_image) }}" target="_blank"
                                   class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                                    <i class="bi bi-image me-1"></i>Lihat
                                </a>
                            @else
                                <span class="text-muted small">—</span>
                            @endif
                        </td>
                        <td class="text-end">
                            @if($payment->status === 'pending_verification')
                            <form action="{{ route('admin.payments.verify', $payment) }}" method="POST" class="d-inline">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-success rounded-pill px-3 me-1"
                                        onclick="return confirm('Verifikasi pembayaran ini?')">
                                    <i class="bi bi-check-lg me-1"></i>Verifikasi
                                </button>
                            </form>
                            <form action="{{ route('admin.payments.reject', $payment) }}" method="POST" class="d-inline">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3"
                                        onclick="return confirm('Tolak pembayaran ini?')">
                                    <i class="bi bi-x-lg me-1"></i>Tolak
                                </button>
                            </form>
                            @else
                                <span class="text-muted small">—</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-3 d-flex justify-content-center">
            {{ $payments->links('pagination::bootstrap-5') }}
        </div>
        @else
        <div class="text-center py-5 text-muted">
            <div style="font-size:3rem">💳</div>
            <p class="mt-2">Belum ada data pembayaran</p>
        </div>
        @endif
    </div>
</div>
@endsection

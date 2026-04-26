@extends('layouts.admin')
@section('title', 'Detail Pelanggan — ' . $user->name)
@section('page-title', 'Detail Pelanggan')

@section('content')

{{-- Back + Header --}}
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('admin.customers.index') }}"
       class="btn btn-sm btn-outline-secondary rounded-pill px-3">
        <i class="bi bi-arrow-left me-1"></i>Kembali
    </a>
    <div>
        <h5 class="fw-800 mb-0">{{ $user->name }}</h5>
        <span class="text-muted small">Bergabung {{ $user->created_at->format('d M Y') }}</span>
    </div>
</div>

<div class="row g-4">

    {{-- Kiri: Info + Pesanan --}}
    <div class="col-lg-8">

        {{-- Stat Cards --}}
        <div class="row g-3 mb-4">
            @foreach([
                ['Total Pesanan',  $totalOrders,    '#FEE2E2', '#C0392B', 'bi-bag-check'],
                ['Selesai',        $totalCompleted, '#D1FAE5', '#059669', 'bi-check-circle'],
                ['Total Belanja',  'Rp '.number_format($totalSpent/1000,0,',','.').'rb', '#EDE9FE', '#7C3AED', 'bi-cash-coin'],
            ] as [$label, $val, $bg, $color, $icon])
            <div class="col-4">
                <div class="card text-center p-3">
                    <div class="d-flex align-items-center justify-content-center rounded-3 mx-auto mb-2"
                         style="width:40px;height:40px;background:{{ $bg }};color:{{ $color }}">
                        <i class="bi {{ $icon }}"></i>
                    </div>
                    <div class="fw-800" style="font-size:1.2rem;color:{{ $color }}">{{ $val }}</div>
                    <div class="text-muted small">{{ $label }}</div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Riwayat Pesanan --}}
        <div class="card">
            <div class="card-header fw-700">
                <i class="bi bi-clock-history me-2 text-danger"></i>Riwayat Pesanan
            </div>
            <div class="card-body p-0">
                @if($orders->count())
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>No. Pesanan</th>
                                <th>Tanggal Acara</th>
                                <th>Total</th>
                                <th>Bayar</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                            <tr>
                                <td>
                                    <div class="fw-700 text-danger" style="font-size:.83rem">
                                        {{ $order->order_number }}
                                    </div>
                                    <div class="text-muted" style="font-size:.7rem">
                                        {{ $order->created_at->format('d M Y') }}
                                    </div>
                                </td>
                                <td class="fw-600 small">{{ $order->event_date->format('d M Y') }}</td>
                                <td class="fw-700 text-danger small">{{ $order->formatted_total }}</td>
                                <td>
                                    @if($order->payment)
                                    <span class="status-badge badge-{{ $order->payment->status }}"
                                          style="font-size:.68rem">
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
                                    <span class="status-badge badge-{{ $order->status }}"
                                          style="font-size:.68rem">
                                        {{ $order->status_label }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.orders.show', $order) }}"
                                       class="btn btn-sm btn-outline-primary rounded-pill px-2"
                                       style="font-size:.75rem">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-3 d-flex justify-content-center">
                    {{ $orders->links('pagination::bootstrap-5') }}
                </div>
                @else
                <div class="text-center py-4 text-muted">
                    <i class="bi bi-clipboard" style="font-size:2rem;opacity:.3"></i>
                    <p class="small mt-2">Belum ada pesanan</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Kanan: Profil --}}
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header fw-700">
                <i class="bi bi-person me-2 text-danger"></i>Profil Pelanggan
            </div>
            <div class="card-body">
                {{-- Avatar --}}
                <div class="text-center mb-4">
                    <div class="d-flex align-items-center justify-content-center rounded-circle fw-800 text-white mx-auto mb-2"
                         style="width:72px;height:72px;font-size:1.8rem;background:linear-gradient(135deg,#C0392B,#E67E22)">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div class="fw-800">{{ $user->name }}</div>
                    <div class="text-muted small">Pelanggan</div>
                </div>

                {{-- Info --}}
                @foreach([
                    ['bi-envelope',   'Email',   $user->email],
                    ['bi-telephone',  'No. HP',  $user->phone ?? '—'],
                    ['bi-geo-alt',    'Alamat',  $user->address ?? '—'],
                    ['bi-calendar3',  'Bergabung', $user->created_at->format('d M Y')],
                ] as [$icon, $label, $val])
                <div class="d-flex gap-3 mb-3 pb-3 border-bottom">
                    <div class="d-flex align-items-center justify-content-center rounded-2 flex-shrink-0"
                         style="width:34px;height:34px;background:#FEE2E2;color:#C0392B">
                        <i class="bi {{ $icon }} small"></i>
                    </div>
                    <div>
                        <div class="text-muted" style="font-size:.7rem;font-weight:600">{{ $label }}</div>
                        <div class="fw-700 small">{{ $val }}</div>
                    </div>
                </div>
                @endforeach

                {{-- Tombol WA --}}
                @if($user->phone)
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $user->phone) }}"
                   target="_blank"
                   class="btn btn-success w-100 rounded-pill fw-700 mt-1">
                    <i class="bi bi-whatsapp me-2"></i>Hubungi via WhatsApp
                </a>
                @endif

                {{-- Hapus --}}
                @if($totalOrders == 0)
                <form action="{{ route('admin.customers.destroy', $user) }}" method="POST"
                      class="mt-2"
                      onsubmit="return confirm('Hapus pelanggan {{ $user->name }}? Tindakan ini tidak bisa dibatalkan.')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger w-100 rounded-pill fw-700">
                        <i class="bi bi-trash me-2"></i>Hapus Pelanggan
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>

</div>
@endsection

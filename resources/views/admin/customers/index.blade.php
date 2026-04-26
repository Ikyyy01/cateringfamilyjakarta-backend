@extends('layouts.admin')
@section('title', 'Kelola Pelanggan')
@section('page-title', 'Kelola Pelanggan')

@section('content')

{{-- Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-800 mb-0">Data Pelanggan</h5>
        <p class="text-muted small mb-0">Semua pelanggan yang terdaftar di sistem</p>
    </div>
    <div class="d-flex gap-3">
        <div class="text-center px-4 py-2 rounded-3" style="background:#FEE2E2">
            <div class="fw-800 text-danger" style="font-size:1.3rem">{{ $totalCustomers }}</div>
            <div class="text-muted small">Total</div>
        </div>
        <div class="text-center px-4 py-2 rounded-3" style="background:#D1FAE5">
            <div class="fw-800 text-success" style="font-size:1.3rem">{{ $newThisMonth }}</div>
            <div class="text-muted small">Bulan Ini</div>
        </div>
    </div>
</div>

{{-- Search --}}
<div class="card mb-4">
    <div class="card-body py-3">
        <form method="GET" action="{{ route('admin.customers.index') }}" class="row g-2 align-items-end">
            <div class="col-md-5">
                <label class="form-label fw-600 small mb-1">Cari Pelanggan</label>
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control rounded-end-3"
                           placeholder="Nama, email, atau no. HP..."
                           value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-danger btn-sm rounded-pill w-100 fw-600">
                    <i class="bi bi-search me-1"></i>Cari
                </button>
            </div>
            @if(request('search'))
            <div class="col-auto">
                <a href="{{ route('admin.customers.index') }}"
                   class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                    <i class="bi bi-x me-1"></i>Reset
                </a>
            </div>
            @endif
        </form>
    </div>
</div>

{{-- Table --}}
<div class="card">
    <div class="card-body p-0">
        @if($customers->count())
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Pelanggan</th>
                        <th>No. HP</th>
                        <th>Total Pesanan</th>
                        <th>Bergabung</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($customers as $customer)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="d-flex align-items-center justify-content-center rounded-circle fw-800 text-white flex-shrink-0"
                                     style="width:40px;height:40px;font-size:.9rem;background:linear-gradient(135deg,#C0392B,#E67E22)">
                                    {{ strtoupper(substr($customer->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="fw-700">{{ $customer->name }}</div>
                                    <div class="text-muted small">{{ $customer->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($customer->phone)
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $customer->phone) }}"
                                   target="_blank" class="text-decoration-none text-success fw-600">
                                    <i class="bi bi-whatsapp me-1"></i>{{ $customer->phone }}
                                </a>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-danger-subtle text-danger rounded-pill px-3 fw-700">
                                {{ $customer->orders_count }} pesanan
                            </span>
                        </td>
                        <td class="text-muted small">{{ $customer->created_at->format('d M Y') }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.customers.show', $customer) }}"
                               class="btn btn-sm btn-outline-primary rounded-pill px-3 me-1">
                                <i class="bi bi-eye me-1"></i>Detail
                            </a>
                            @if($customer->orders_count == 0)
                            <form action="{{ route('admin.customers.destroy', $customer) }}"
                                  method="POST" class="d-inline"
                                  onsubmit="return confirm('Hapus pelanggan {{ $customer->name }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-3 d-flex justify-content-between align-items-center">
            <span class="text-muted small">
                Menampilkan {{ $customers->firstItem() }}–{{ $customers->lastItem() }}
                dari {{ $customers->total() }} pelanggan
            </span>
            {{ $customers->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
        @else
        <div class="text-center py-5 text-muted">
            <div style="font-size:3rem">👥</div>
            <p class="mt-2 fw-600">
                @if(request('search'))
                    Tidak ada pelanggan dengan kata kunci "{{ request('search') }}"
                @else
                    Belum ada pelanggan terdaftar
                @endif
            </p>
        </div>
        @endif
    </div>
</div>

@endsection

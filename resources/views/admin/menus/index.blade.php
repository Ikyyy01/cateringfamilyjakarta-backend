@extends('layouts.admin')
@section('title', 'Kelola Menu')
@section('page-title', 'Kelola Menu')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-800 mb-0">Daftar Menu</h5>
        <p class="text-muted small mb-0">Kelola semua menu dan paket catering</p>
    </div>
    <a href="{{ route('admin.menus.create') }}" class="btn btn-danger rounded-pill px-4">
        <i class="bi bi-plus-lg me-2"></i>Tambah Menu
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        @if($menus->count())
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Menu</th>
                        <th>Kategori</th>
                        <th>Harga/Pax</th>
                        <th>Min–Maks Pax</th>
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($menus as $menu)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                @if($menu->image)
                                    <img src="{{ Storage::url($menu->image) }}" alt=""
                                         class="rounded-3" style="width:48px;height:48px;object-fit:cover">
                                @else
                                    <div class="d-flex align-items-center justify-content-center bg-light rounded-3"
                                         style="width:48px;height:48px;font-size:1.5rem">🍛</div>
                                @endif
                                <div>
                                    <div class="fw-700">{{ $menu->name }}</div>
                                    <div class="text-muted small">{{ Str::limit($menu->description, 50) }}</div>
                                </div>
                            </div>
                        </td>
                        <td><span class="badge bg-danger-subtle text-danger rounded-pill">{{ $menu->category->name ?? '-' }}</span></td>
                        <td class="fw-700 text-danger">{{ $menu->formatted_price }}</td>
                        <td class="text-muted">{{ $menu->min_pax }} – {{ $menu->max_pax }} pax</td>
                        <td>
                            @if($menu->is_active)
                                <span class="status-badge badge-completed">Aktif</span>
                            @else
                                <span class="status-badge badge-cancelled">Nonaktif</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.menus.edit', $menu) }}"
                               class="btn btn-sm btn-outline-primary rounded-pill me-1">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('admin.menus.destroy', $menu) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Hapus menu {{ $menu->name }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-3 d-flex justify-content-center">
            {{ $menus->links('pagination::bootstrap-5') }}
        </div>
        @else
        <div class="text-center py-5 text-muted">
            <div style="font-size:3rem">🍽️</div>
            <p class="mt-2">Belum ada menu. <a href="{{ route('admin.menus.create') }}" class="text-danger">Tambah sekarang</a></p>
        </div>
        @endif
    </div>
</div>
@endsection

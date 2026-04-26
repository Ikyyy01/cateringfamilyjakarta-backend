@extends('layouts.app')
@section('title', 'Katalog Menu — Catering Family Jakarta')

@push('styles')
<style>
    .filter-btn {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 7px 18px; border-radius: 50px; font-size: .85rem; font-weight: 600;
        text-decoration: none; transition: all .2s; border: 2px solid transparent;
    }
    .filter-btn-active {
        background: var(--cfj-primary); color: #fff !important;
        border-color: var(--cfj-primary);
        box-shadow: 0 4px 14px rgba(192,57,43,.3);
    }
    .filter-btn-inactive {
        background: #fff; color: #666 !important;
        border-color: #e8e4e0;
    }
    .filter-btn-inactive:hover { border-color: var(--cfj-primary); color: var(--cfj-primary) !important; }
    .menu-grid-section { min-height: 400px; }
</style>
@endpush

@section('content')
<div class="container py-5">

    {{-- Header --}}
    <div class="row mb-1 align-items-end">
        <div class="col">
            <p class="text-muted small mb-1">Temukan pilihan terbaik untuk acara Anda</p>
            <div class="section-title">Katalog <span>Menu</span></div>
            <div class="section-divider"></div>
        </div>
        <div class="col-auto text-muted small d-none d-md-block">
            {{ $menus->total() }} menu tersedia
        </div>
    </div>

    {{-- Filter Kategori --}}
    @if(isset($categories) && $categories->count())
    <div class="d-flex gap-2 flex-wrap mb-4 pb-2">
        <a href="{{ route('customer.menu') }}"
           class="filter-btn {{ !request('kategori') ? 'filter-btn-active' : 'filter-btn-inactive' }}">
            <i class="bi bi-grid-fill" style="font-size:.8rem"></i> Semua
        </a>
        @foreach($categories as $cat)
        <a href="{{ route('customer.menu', ['kategori' => $cat->slug]) }}"
           class="filter-btn {{ request('kategori') == $cat->slug ? 'filter-btn-active' : 'filter-btn-inactive' }}">
            {{ $cat->name }}
        </a>
        @endforeach
    </div>
    @endif

    {{-- Menu Grid --}}
    <div class="menu-grid-section">
        @if($menus->count())
        <div class="row g-4">
            @foreach($menus as $menu)
            <div class="col-md-6 col-lg-4">
                <div class="card menu-card h-100">
                    @if($menu->image)
                        <img src="{{ Storage::url($menu->image) }}" alt="{{ $menu->name }}">
                    @else
                        <div class="menu-placeholder">
                            <span class="menu-placeholder-icon">
                                @php
                                    $slug = $menu->category->slug ?? '';
                                    echo match(true) {
                                        str_contains($slug,'nasi') => '🍱',
                                        str_contains($slug,'aqiqah') => '🐑',
                                        str_contains($slug,'snack') => '🍢',
                                        str_contains($slug,'minuman') => '🥤',
                                        default => '🍛'
                                    };
                                @endphp
                            </span>
                        </div>
                    @endif
                    <div class="card-body d-flex flex-column">
                        <span class="badge bg-danger-subtle text-danger rounded-pill mb-2 align-self-start small fw-600">
                            {{ $menu->category->name ?? '' }}
                        </span>
                        <h6 class="fw-800 mb-1">{{ $menu->name }}</h6>
                        <p class="text-muted small mb-3 flex-grow-1" style="line-height:1.6">
                            {{ Str::limit($menu->description, 90) }}
                        </p>
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div>
                                <span class="fw-800 text-danger fs-5">{{ $menu->formatted_price }}</span>
                                <span class="text-muted small">/pax</span>
                            </div>
                            @if($menu->category && $menu->category->slug === 'nasi-box')
                                <span class="badge bg-light text-secondary border" style="font-size:.75rem">
                                    Min. {{ $menu->min_pax }} pax
                                </span>
                            @endif
                        </div>
                        <form action="{{ route('customer.cart.add') }}" method="POST">
                            @csrf
                            <input type="hidden" name="menu_id" value="{{ $menu->id }}">
                            <div class="input-group input-group-sm">
                                <input type="number" name="pax" class="form-control"
                                       style="border-radius: 50px 0 0 50px"
                                       value="{{ $menu->category && $menu->category->slug === 'nasi-box' ? $menu->min_pax : 1 }}"
                                       min="{{ $menu->category && $menu->category->slug === 'nasi-box' ? $menu->min_pax : 1 }}"
                                       max="{{ $menu->max_pax }}"
                                       placeholder="Pax">
                                <button type="submit" class="btn btn-danger fw-600"
                                        style="border-radius: 0 50px 50px 0; padding: 0 16px">
                                    <i class="bi bi-cart-plus me-1"></i>Tambah
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-5 d-flex justify-content-center">
            {{ $menus->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>

        @else
        <div class="text-center py-5">
            <div style="font-size:4rem; opacity:.4">🍽️</div>
            <h5 class="mt-3 text-muted fw-700">Belum ada menu tersedia</h5>
            <p class="text-muted small">
                @if(request('kategori'))
                    Tidak ada menu di kategori ini.
                    <a href="{{ route('customer.menu') }}" class="text-danger">Lihat semua menu</a>
                @else
                    Menu akan segera ditambahkan.
                @endif
            </p>
        </div>
        @endif
    </div>
</div>
@endsection

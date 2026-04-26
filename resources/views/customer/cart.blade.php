@extends('layouts.app')
@section('title', 'Keranjang — Catering Family Jakarta')

@section('content')
<div class="container py-5">
    <div class="section-title mb-1">Keranjang <span>Pesanan</span></div>
    <div class="section-divider"></div>

    @if(empty($cart))
        <div class="card text-center py-5 px-3" style="max-width:480px; margin:0 auto">
            <div style="font-size:5rem; opacity:.5">🛒</div>
            <h5 class="mt-3 fw-800">Keranjang masih kosong</h5>
            <p class="text-muted small mb-4">Tambahkan menu favorit kamu dulu ya!</p>
            <a href="{{ route('customer.menu') }}" class="btn btn-danger rounded-pill px-4 fw-700">
                <i class="bi bi-grid me-2"></i>Lihat Menu
            </a>
        </div>
    @else
    <div class="row g-4">

        {{-- Item List --}}
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>
                        <i class="bi bi-bag2 me-2 text-danger"></i>
                        Item Pesanan
                        <span class="badge bg-danger-subtle text-danger rounded-pill ms-1">{{ count($cart) }}</span>
                    </span>
                    <form action="{{ route('customer.cart.clear') }}" method="POST">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger rounded-pill px-3"
                                onclick="return confirm('Kosongkan semua keranjang?')">
                            <i class="bi bi-trash me-1"></i>Kosongkan
                        </button>
                    </form>
                </div>
                <div class="card-body p-0">
                    @foreach($cart as $key => $item)
                    @php
                        $isNasiBox = isset($item['category']) && $item['category'] === 'nasi-box';
                        $minPax    = $isNasiBox ? 10 : 1;
                    @endphp
                    <div class="d-flex align-items-center gap-3 p-4 border-bottom">
                        {{-- Thumbnail --}}
                        <div class="flex-shrink-0 rounded-3 d-flex align-items-center justify-content-center overflow-hidden"
                             style="width:72px; height:72px; background:linear-gradient(135deg,#fff5f3,#fff0e8)">
                            <span style="font-size:2rem">🍛</span>
                        </div>
                        {{-- Info --}}
                        <div class="flex-grow-1 min-w-0">
                            <h6 class="fw-800 mb-1 text-truncate">{{ $item['name'] }}</h6>
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <span class="text-danger fw-700 small">
                                    Rp {{ number_format($item['price'], 0, ',', '.') }}/pax
                                </span>
                                @if($isNasiBox)
                                    <span class="badge bg-light text-muted border" style="font-size:.7rem">
                                        Min. {{ $minPax }} pax
                                    </span>
                                @endif
                            </div>
                        </div>
                        {{-- Qty Control --}}
                        <div style="min-width:140px">
                            <form action="{{ route('customer.cart.update', $key) }}" method="POST">
                                @csrf @method('PATCH')
                                <div class="input-group input-group-sm">
                                    <button type="button" class="btn btn-outline-secondary"
                                        onclick="
                                            let i=this.nextElementSibling;
                                            let min=parseInt(i.getAttribute('min'));
                                            let val=parseInt(i.value);
                                            if(val>min){i.value=val-1; this.closest('form').submit();}
                                        ">
                                        <i class="bi bi-dash"></i>
                                    </button>
                                    <input type="number" name="pax"
                                           value="{{ $item['pax'] }}"
                                           min="{{ $minPax }}"
                                           class="form-control text-center fw-700"
                                           onchange="
                                               let min=parseInt(this.getAttribute('min'));
                                               if(parseInt(this.value)<min) this.value=min;
                                               this.closest('form').submit();
                                           ">
                                    <button type="button" class="btn btn-outline-secondary"
                                        onclick="let i=this.previousElementSibling; i.value=parseInt(i.value)+1; this.closest('form').submit()">
                                        <i class="bi bi-plus"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                        {{-- Subtotal --}}
                        <div class="text-end" style="min-width:110px">
                            <div class="fw-800 text-danger">
                                Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                            </div>
                            <div class="text-muted" style="font-size:.72rem">{{ $item['pax'] }} pax</div>
                        </div>
                        {{-- Remove --}}
                        <form action="{{ route('customer.cart.remove', $key) }}" method="POST" class="flex-shrink-0">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    class="btn btn-sm btn-light text-muted rounded-circle d-flex align-items-center justify-content-center"
                                    style="width:34px;height:34px"
                                    title="Hapus item">
                                <i class="bi bi-x"></i>
                            </button>
                        </form>
                    </div>
                    @endforeach
                </div>
            </div>
            <a href="{{ route('customer.menu') }}" class="btn btn-outline-secondary rounded-pill px-4 mt-3 fw-600">
                <i class="bi bi-arrow-left me-2"></i>Tambah Menu Lain
            </a>
        </div>

        {{-- Ringkasan --}}
        <div class="col-lg-4">
            <div class="card sticky-top" style="top:80px">
                <div class="card-header">
                    <i class="bi bi-receipt me-2 text-danger"></i>Ringkasan Pesanan
                </div>
                <div class="card-body">
                    @php $subtotal = collect($cart)->sum('subtotal'); @endphp
                    @foreach($cart as $item)
                    <div class="d-flex justify-content-between text-muted small mb-2">
                        <span class="text-truncate me-2" style="max-width:160px">
                            {{ Str::limit($item['name'], 22) }} ×{{ $item['pax'] }}
                        </span>
                        <span class="flex-shrink-0">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</span>
                    </div>
                    @endforeach
                    <hr class="my-3">
                    <div class="d-flex justify-content-between fw-800 mb-1">
                        <span>Subtotal</span>
                        <span class="text-danger">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                    <p class="text-muted small mb-4" style="font-size:.78rem; line-height:1.5">
                        * Ongkir & biaya layanan dihitung saat checkout
                    </p>
                    <a href="{{ route('customer.orders.checkout') }}"
                       class="btn btn-danger w-100 rounded-pill fw-700 py-2">
                        Lanjut Checkout <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

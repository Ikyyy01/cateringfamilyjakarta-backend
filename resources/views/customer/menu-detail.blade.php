@extends('layouts.app')
@section('title', $menu->name . ' — Catering Family Jakarta')

@section('content')
<div class="container py-5">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb" style="font-size:.85rem">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-danger text-decoration-none">Beranda</a></li>
            <li class="breadcrumb-item"><a href="{{ route('customer.menu') }}" class="text-danger text-decoration-none">Menu</a></li>
            @if($menu->category)
            <li class="breadcrumb-item">
                <a href="{{ route('customer.menu', ['kategori' => $menu->category->slug]) }}"
                   class="text-danger text-decoration-none">{{ $menu->category->name }}</a>
            </li>
            @endif
            <li class="breadcrumb-item active text-muted">{{ $menu->name }}</li>
        </ol>
    </nav>

    <div class="row g-5 align-items-start">

        {{-- Gambar --}}
        <div class="col-lg-6">
            @if($menu->image)
                <img src="{{ Storage::url($menu->image) }}" alt="{{ $menu->name }}"
                     class="img-fluid rounded-4 shadow-sm w-100"
                     style="max-height:420px; object-fit:cover">
            @else
                <div class="rounded-4 d-flex align-items-center justify-content-center position-relative overflow-hidden"
                     style="height:380px; background:linear-gradient(135deg,#fff5f3,#fff0e8)">
                    <div style="position:absolute;inset:0;
                        background-image:radial-gradient(circle at 25% 30%,rgba(192,57,43,.07) 0%,transparent 55%),
                                         radial-gradient(circle at 75% 70%,rgba(230,126,34,.09) 0%,transparent 55%)">
                    </div>
                    <span style="font-size:7rem; filter:drop-shadow(0 8px 24px rgba(0,0,0,.12)); position:relative; z-index:1">
                        @php
                            $slug = $menu->category->slug ?? '';
                            echo match(true) {
                                str_contains($slug,'nasi')   => '🍱',
                                str_contains($slug,'aqiqah') => '🐑',
                                str_contains($slug,'snack')  => '🍢',
                                str_contains($slug,'prasm')  => '🍽️',
                                default                      => '🍛'
                            };
                        @endphp
                    </span>
                </div>
            @endif
        </div>

        {{-- Detail --}}
        <div class="col-lg-6">
            @php $isNasiBox = $menu->category && $menu->category->slug === 'nasi-box'; @endphp

            <span class="badge bg-danger-subtle text-danger rounded-pill px-3 py-2 mb-3 fw-600">
                {{ $menu->category->name ?? '' }}
            </span>
            <h2 class="fw-800 mb-3" style="font-size:clamp(1.5rem,3vw,2rem)">{{ $menu->name }}</h2>
            <p class="text-muted mb-4" style="line-height:1.8; font-size:.95rem">{{ $menu->description }}</p>

            {{-- Info Harga --}}
            <div class="rounded-3 p-4 mb-4" style="background:#FFF9F5; border:1.5px solid #F5EBE4">
                <div class="row g-3 text-center">
                    <div class="{{ $isNasiBox ? 'col-4' : 'col-6' }}">
                        <div class="fw-800 text-danger" style="font-size:1.4rem">{{ $menu->formatted_price }}</div>
                        <div class="text-muted small fw-600 mt-1">Per Pax</div>
                    </div>
                    @if($isNasiBox)
                    <div class="col-4">
                        <div class="fw-800" style="font-size:1.4rem">{{ $menu->min_pax }}</div>
                        <div class="text-muted small fw-600 mt-1">Min. Pax</div>
                    </div>
                    @endif
                    <div class="{{ $isNasiBox ? 'col-4' : 'col-6' }}">
                        <div class="fw-800" style="font-size:1.4rem">{{ $menu->max_pax }}</div>
                        <div class="text-muted small fw-600 mt-1">Maks. Pax</div>
                    </div>
                </div>
            </div>

            {{-- Form Pesan --}}
            <form action="{{ route('customer.cart.add') }}" method="POST">
                @csrf
                <input type="hidden" name="menu_id" value="{{ $menu->id }}">
                <label class="fw-700 mb-2 d-block">Jumlah Pax</label>
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="input-group" style="max-width:180px">
                        <button type="button" class="btn btn-outline-secondary"
                            onclick="let i=document.getElementById('paxInput'); let min=parseInt(i.min); let v=parseInt(i.value); if(v>min){i.value=v-1; updateTotal();}">
                            <i class="bi bi-dash"></i>
                        </button>
                        <input type="number" name="pax" class="form-control text-center fw-700"
                               id="paxInput"
                               value="{{ $isNasiBox ? $menu->min_pax : 1 }}"
                               min="{{ $isNasiBox ? $menu->min_pax : 1 }}"
                               max="{{ $menu->max_pax }}"
                               oninput="updateTotal()">
                        <button type="button" class="btn btn-outline-secondary"
                            onclick="let i=document.getElementById('paxInput'); if(parseInt(i.value)<parseInt(i.max)){i.value=parseInt(i.value)+1; updateTotal();}">
                            <i class="bi bi-plus"></i>
                        </button>
                    </div>
                    <div>
                        <div class="text-muted small">Total estimasi:</div>
                        <div class="fw-800 text-danger fs-5" id="totalEst">{{ $menu->formatted_price }}</div>
                    </div>
                </div>
                <button type="submit" class="btn btn-danger btn-lg rounded-pill px-5 fw-700 w-100">
                    <i class="bi bi-cart-plus me-2"></i>Tambah ke Keranjang
                </button>
                <a href="{{ route('customer.menu', ['kategori' => $menu->category->slug ?? '']) }}"
                   class="btn btn-outline-secondary btn-lg rounded-pill px-4 w-100 mt-2 fw-600">
                    <i class="bi bi-arrow-left me-2"></i>Kembali ke {{ $menu->category->name ?? 'Menu' }}
                </a>
            </form>
        </div>
    </div>

    {{-- Menu Terkait --}}
    @if(isset($relatedMenus) && $relatedMenus->count())
    <div class="mt-5 pt-4 border-top">
        <h5 class="fw-800 mb-4">Menu <span class="text-danger">Terkait</span></h5>
        <div class="row g-4">
            @foreach($relatedMenus as $related)
            <div class="col-md-4">
                <div class="card menu-card h-100">
                    @if($related->image)
                        <img src="{{ Storage::url($related->image) }}" alt="{{ $related->name }}">
                    @else
                        <div class="menu-placeholder">
                            <span class="menu-placeholder-icon">🍛</span>
                        </div>
                    @endif
                    <div class="card-body d-flex flex-column">
                        <h6 class="fw-800 mb-1">{{ $related->name }}</h6>
                        <p class="text-muted small mb-3 flex-grow-1">{{ Str::limit($related->description, 80) }}</p>
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="fw-800 text-danger">{{ $related->formatted_price }}</span>
                                <span class="text-muted small">/pax</span>
                            </div>
                            <a href="{{ route('customer.menu.detail', $related) }}"
                               class="btn btn-sm btn-danger rounded-pill px-3 fw-600">
                                Lihat
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

</div>

@push('scripts')
<script>
const price = {{ $menu->price }};
function updateTotal() {
    const val = parseInt(document.getElementById('paxInput').value) || 0;
    document.getElementById('totalEst').textContent = 'Rp ' + (price * val).toLocaleString('id-ID');
}
updateTotal();
</script>
@endpush
@endsection

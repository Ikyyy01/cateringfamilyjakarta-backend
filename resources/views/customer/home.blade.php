@extends('layouts.app')
@section('title', 'Beranda — Catering Family Jakarta')

@push('styles')
<style>
/* ── Reset & Base ── */
:root {
    --primary: #C0392B;
    --primary-dark: #a93226;
    --secondary: #E67E22;
    --dark: #1a1a2e;
    --light-bg: #FFF9F5;
}

/* ══════════════════════════════════════════
   HERO
══════════════════════════════════════════ */
.hero-wrap {
    background: #fff;
    position: relative;
    overflow: hidden;
    padding: 0;
    min-height: 92vh;
    display: flex;
    align-items: center;
}
.hero-bg-shape {
    position: absolute;
    top: 0; right: 0;
    width: 55%;
    height: 100%;
    background: linear-gradient(160deg, #FFF5F2 0%, #FFF0E0 100%);
    clip-path: polygon(12% 0%, 100% 0%, 100% 100%, 0% 100%);
    z-index: 0;
}
.hero-bg-dots {
    position: absolute;
    top: 0; right: 0;
    width: 55%;
    height: 100%;
    background-image: radial-gradient(circle, rgba(192,57,43,.08) 1.5px, transparent 1.5px);
    background-size: 28px 28px;
    clip-path: polygon(12% 0%, 100% 0%, 100% 100%, 0% 100%);
    z-index: 0;
}
.hero-content { position: relative; z-index: 1; }
.hero-badge {
    display: inline-flex; align-items: center; gap: 8px;
    background: #FFF0EE;
    border: 1.5px solid #FFD5CC;
    color: var(--primary);
    border-radius: 50px;
    padding: 7px 18px;
    font-size: .8rem; font-weight: 700;
    letter-spacing: .3px;
    margin-bottom: 22px;
}
.hero-badge .dot { width: 7px; height: 7px; border-radius: 50%; background: var(--primary); animation: pulse-dot 1.5s ease-in-out infinite; }
@keyframes pulse-dot { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:.5;transform:scale(1.3)} }
.hero-title {
    font-size: clamp(2.2rem, 5vw, 3.4rem);
    font-weight: 800;
    line-height: 1.15;
    color: #111827;
    margin-bottom: 20px;
}
.hero-title .highlight {
    color: var(--primary);
    position: relative;
    display: inline-block;
}
.hero-title .highlight::after {
    content: '';
    position: absolute;
    bottom: 2px; left: 0;
    width: 100%; height: 6px;
    background: linear-gradient(90deg, var(--primary), var(--secondary));
    border-radius: 4px;
    opacity: .25;
    z-index: -1;
}
.hero-desc { font-size: 1.05rem; color: #6B7280; line-height: 1.75; margin-bottom: 32px; }
.hero-cta-primary {
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    color: #fff; border: none;
    padding: 14px 32px; border-radius: 50px;
    font-weight: 700; font-size: .95rem;
    text-decoration: none;
    display: inline-flex; align-items: center; gap: 8px;
    box-shadow: 0 8px 24px rgba(192,57,43,.35);
    transition: transform .2s, box-shadow .2s;
}
.hero-cta-primary:hover { color:#fff; transform: translateY(-2px); box-shadow: 0 12px 32px rgba(192,57,43,.4); }
.hero-cta-ghost {
    background: transparent;
    color: #374151; border: 2px solid #E5E7EB;
    padding: 13px 28px; border-radius: 50px;
    font-weight: 700; font-size: .95rem;
    text-decoration: none;
    display: inline-flex; align-items: center; gap: 8px;
    transition: all .2s;
}
.hero-cta-ghost:hover { border-color: var(--primary); color: var(--primary); }
.hero-stats {
    display: flex; gap: 32px; margin-top: 40px;
    padding-top: 28px;
    border-top: 1px solid #F3F4F6;
}
.hero-stat-num { font-size: 1.5rem; font-weight: 800; color: #111827; line-height: 1; }
.hero-stat-lbl { font-size: .73rem; color: #9CA3AF; font-weight: 600; margin-top: 4px; }
.hero-img-wrap {
    position: relative; z-index: 1;
    display: flex; align-items: center; justify-content: center;
}
.hero-food-circle {
    width: 380px; height: 380px;
    border-radius: 50%;
    background: linear-gradient(135deg, rgba(192,57,43,.08), rgba(230,126,34,.12));
    display: flex; align-items: center; justify-content: center;
    position: relative;
    box-shadow: 0 0 0 20px rgba(192,57,43,.04), 0 0 0 40px rgba(192,57,43,.025);
}
.hero-food-img { font-size: 9rem; filter: drop-shadow(0 20px 40px rgba(0,0,0,.15)); }
.hero-float-card {
    position: absolute;
    background: #fff;
    border-radius: 16px;
    padding: 12px 16px;
    box-shadow: 0 8px 32px rgba(0,0,0,.12);
    display: flex; align-items: center; gap: 10px;
    font-size: .8rem; font-weight: 700; color: #111827;
    white-space: nowrap;
}
.hero-float-card .fc-icon {
    width: 36px; height: 36px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem; flex-shrink: 0;
}
.hero-float-card.card-1 { top: 10%; left: -20px; }
.hero-float-card.card-2 { bottom: 15%; right: -20px; }
.hero-float-card.card-3 { top: 50%; left: -40px; }
@media (max-width: 991px) {
    .hero-bg-shape, .hero-bg-dots { width: 100%; clip-path: none; opacity: .4; }
    .hero-food-circle { width: 260px; height: 260px; }
    .hero-food-img { font-size: 6rem; }
    .hero-float-card.card-1, .hero-float-card.card-3 { left: 0; }
    .hero-float-card.card-2 { right: 0; }
}

/* ══════════════════════════════════════════
   STATS BAR
══════════════════════════════════════════ */
.stats-bar {
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    padding: 28px 0;
}
.stats-bar .stat-item { text-align: center; color: #fff; }
.stats-bar .stat-num { font-size: 1.7rem; font-weight: 800; line-height: 1; }
.stats-bar .stat-lbl { font-size: .75rem; opacity: .8; margin-top: 4px; font-weight: 600; }
.stats-bar .stat-sep {
    width: 1px; background: rgba(255,255,255,.2);
    align-self: stretch; margin: 8px 0;
}

/* ══════════════════════════════════════════
   CATEGORIES
══════════════════════════════════════════ */
.section-label {
    font-size: .75rem; font-weight: 800;
    letter-spacing: 1.2px; text-transform: uppercase;
    color: var(--primary); margin-bottom: 8px;
}
.section-heading {
    font-size: clamp(1.6rem, 3vw, 2.2rem);
    font-weight: 800; color: #111827; margin-bottom: 6px;
}
.section-heading span { color: var(--primary); }
.section-sub { color: #9CA3AF; font-size: .9rem; }
.section-line {
    width: 48px; height: 4px;
    background: linear-gradient(90deg, var(--primary), var(--secondary));
    border-radius: 4px; margin: 14px 0 0;
}

.cat-card {
    background: #fff;
    border-radius: 20px;
    padding: 22px 16px;
    text-align: center;
    box-shadow: 0 2px 12px rgba(0,0,0,.05);
    cursor: pointer;
    transition: all .25s;
    border: 2px solid transparent;
    text-decoration: none; color: inherit;
    display: block;
}
.cat-card:hover {
    border-color: var(--primary);
    transform: translateY(-4px);
    box-shadow: 0 12px 32px rgba(192,57,43,.15);
    color: var(--primary);
}
.cat-icon-wrap {
    width: 64px; height: 64px; border-radius: 18px;
    background: linear-gradient(135deg, #FFF0EE, #FFF5E8);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.8rem; margin: 0 auto 12px;
    transition: transform .25s;
}
.cat-card:hover .cat-icon-wrap { transform: scale(1.1); background: linear-gradient(135deg, #FFE4E0, #FFEDDB); }
.cat-name { font-size: .82rem; font-weight: 700; }
.cat-count { font-size: .72rem; color: #9CA3AF; margin-top: 2px; }

/* ══════════════════════════════════════════
   MENU CARDS
══════════════════════════════════════════ */
.menu-card-v2 {
    background: #fff;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 2px 16px rgba(0,0,0,.06);
    transition: transform .25s, box-shadow .25s;
    height: 100%;
    display: flex; flex-direction: column;
}
.menu-card-v2:hover { transform: translateY(-6px); box-shadow: 0 16px 40px rgba(192,57,43,.14); }
.menu-card-v2-img {
    height: 210px; overflow: hidden; position: relative; flex-shrink: 0;
}
.menu-card-v2-img img { width: 100%; height: 100%; object-fit: cover; transition: transform .4s; }
.menu-card-v2:hover .menu-card-v2-img img { transform: scale(1.06); }
.menu-card-v2-img .menu-badge {
    position: absolute; top: 12px; left: 12px;
    background: var(--primary); color: #fff;
    border-radius: 50px; font-size: .68rem; font-weight: 700;
    padding: 4px 12px;
}
.menu-card-v2-img .menu-fav {
    position: absolute; top: 12px; right: 12px;
    width: 32px; height: 32px; border-radius: 50%;
    background: #fff; display: flex; align-items: center; justify-content: center;
    font-size: .85rem; color: #E74C3C;
    box-shadow: 0 2px 8px rgba(0,0,0,.1);
}
.menu-placeholder-v2 {
    width: 100%; height: 100%;
    background: linear-gradient(135deg, #FFF5F3, #FFF0E8);
    display: flex; align-items: center; justify-content: center;
    font-size: 4rem;
}
.menu-card-v2-body { padding: 18px 20px 20px; flex: 1; display: flex; flex-direction: column; }
.menu-card-v2-cat { font-size: .7rem; font-weight: 700; color: var(--primary); text-transform: uppercase; letter-spacing: .6px; margin-bottom: 6px; }
.menu-card-v2-name { font-size: 1rem; font-weight: 800; color: #111827; margin-bottom: 6px; }
.menu-card-v2-desc { font-size: .8rem; color: #9CA3AF; line-height: 1.6; flex: 1; margin-bottom: 14px; }
.menu-card-v2-footer { display: flex; align-items: center; justify-content: space-between; }
.menu-card-v2-price { font-size: 1.15rem; font-weight: 800; color: var(--primary); }
.menu-card-v2-price small { font-size: .72rem; color: #9CA3AF; font-weight: 500; }
.btn-order {
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    color: #fff; border: none;
    padding: 9px 20px; border-radius: 50px;
    font-size: .8rem; font-weight: 700;
    text-decoration: none;
    display: inline-flex; align-items: center; gap: 6px;
    transition: opacity .2s, transform .2s;
    white-space: nowrap;
}
.btn-order:hover { color: #fff; opacity: .9; transform: translateY(-1px); }

/* ══════════════════════════════════════════
   HOW IT WORKS
══════════════════════════════════════════ */
.how-section { background: #fff; }
.step-card {
    text-align: center; position: relative; padding: 0 12px;
}
.step-circle {
    width: 80px; height: 80px; border-radius: 50%;
    background: linear-gradient(135deg, #FFF0EE, #FFF5E8);
    border: 2.5px dashed #FFD5CC;
    display: flex; align-items: center; justify-content: center;
    font-size: 2rem; margin: 0 auto 18px;
    position: relative; transition: all .3s;
}
.step-card:hover .step-circle {
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    border-color: transparent;
    transform: scale(1.08);
    box-shadow: 0 8px 24px rgba(192,57,43,.3);
}
.step-num {
    position: absolute; top: -6px; right: -6px;
    width: 24px; height: 24px; border-radius: 50%;
    background: var(--primary); color: #fff;
    font-size: .68rem; font-weight: 800;
    display: flex; align-items: center; justify-content: center;
    border: 2px solid #fff;
}
.step-arrow {
    position: absolute; top: 40px; right: -12px;
    font-size: 1.2rem; color: #E5E7EB;
    transform: translateY(-50%);
}
@media (max-width: 767px) { .step-arrow { display: none; } }
.step-title { font-size: .92rem; font-weight: 800; color: #111827; margin-bottom: 6px; }
.step-desc { font-size: .78rem; color: #9CA3AF; line-height: 1.6; }

/* ══════════════════════════════════════════
   WHY US
══════════════════════════════════════════ */
.why-card {
    display: flex; align-items: flex-start; gap: 16px;
    background: #fff;
    border-radius: 16px;
    padding: 22px;
    box-shadow: 0 2px 12px rgba(0,0,0,.05);
    height: 100%;
    transition: transform .2s, box-shadow .2s;
}
.why-card:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(192,57,43,.1); }
.why-icon {
    width: 48px; height: 48px; border-radius: 14px; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.3rem;
}
.why-icon.red    { background: #FEE2E2; }
.why-icon.orange { background: #FEF3C7; }
.why-icon.green  { background: #D1FAE5; }
.why-icon.blue   { background: #DBEAFE; }
.why-title { font-size: .9rem; font-weight: 800; color: #111827; margin-bottom: 4px; }
.why-desc  { font-size: .78rem; color: #9CA3AF; line-height: 1.6; margin: 0; }

/* ══════════════════════════════════════════
   CTA BANNER
══════════════════════════════════════════ */
.cta-banner {
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 55%, #0f3460 100%);
    border-radius: 28px;
    padding: 60px 48px;
    position: relative; overflow: hidden;
}
.cta-banner::before {
    content: '';
    position: absolute; right: -60px; top: -60px;
    width: 280px; height: 280px; border-radius: 50%;
    background: rgba(192,57,43,.12);
}
.cta-banner::after {
    content: '';
    position: absolute; left: -40px; bottom: -40px;
    width: 200px; height: 200px; border-radius: 50%;
    background: rgba(230,126,34,.08);
}
.cta-banner-title { font-size: clamp(1.6rem, 3vw, 2.4rem); font-weight: 800; color: #fff; margin-bottom: 12px; }
.cta-banner-sub { color: rgba(255,255,255,.65); font-size: .95rem; line-height: 1.7; }
.btn-cta-white {
    background: #fff;
    color: var(--primary); border: none;
    padding: 14px 32px; border-radius: 50px;
    font-weight: 800; font-size: .95rem;
    text-decoration: none;
    display: inline-flex; align-items: center; gap: 8px;
    box-shadow: 0 8px 24px rgba(0,0,0,.2);
    transition: transform .2s, box-shadow .2s;
}
.btn-cta-white:hover { color: var(--primary); transform: translateY(-2px); box-shadow: 0 12px 32px rgba(0,0,0,.25); }
.btn-cta-ghost {
    background: rgba(255,255,255,.1);
    color: #fff; border: 2px solid rgba(255,255,255,.2);
    padding: 13px 28px; border-radius: 50px;
    font-weight: 700; font-size: .95rem;
    text-decoration: none;
    display: inline-flex; align-items: center; gap: 8px;
    transition: all .2s;
}
.btn-cta-ghost:hover { color:#fff; background: rgba(255,255,255,.18); }

/* ══════════════════════════════════════════
   TESTIMONIALS
══════════════════════════════════════════ */
.testi-card {
    background: #fff;
    border-radius: 20px;
    padding: 24px;
    box-shadow: 0 2px 16px rgba(0,0,0,.06);
    height: 100%;
    position: relative;
}
.testi-quote {
    position: absolute; top: 18px; right: 20px;
    font-size: 2.5rem; color: #FEE2E2;
    font-family: Georgia, serif; line-height: 1;
}
.testi-stars { color: #F59E0B; font-size: .9rem; letter-spacing: 1px; margin-bottom: 10px; }
.testi-text { font-size: .85rem; color: #6B7280; line-height: 1.7; margin-bottom: 16px; }
.testi-avatar {
    width: 40px; height: 40px; border-radius: 50%; flex-shrink: 0;
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-weight: 800; font-size: .85rem;
}
.testi-name { font-size: .85rem; font-weight: 700; color: #111827; }
.testi-role { font-size: .72rem; color: #9CA3AF; }
</style>
@endpush

@section('content')

{{-- ══════════════════════════════════════════
     HERO
══════════════════════════════════════════ --}}
<section class="hero-wrap">
    <div class="hero-bg-shape"></div>
    <div class="hero-bg-dots"></div>
    <div class="container py-5">
        <div class="row align-items-center g-5">
            {{-- Left --}}
            <div class="col-lg-6 hero-content">
                <div class="hero-badge">
                    <span class="dot"></span>
                    Melayani Seluruh Wilayah Jakarta
                </div>
                <h1 class="hero-title">
                    Catering Lezat untuk<br>Setiap Momen <span class="highlight">Spesial</span>
                </h1>
                <p class="hero-desc">
                    Nasi box berkualitas & paket aqiqah lengkap dengan cita rasa terbaik.<br>
                    Pesan online, terima di lokasi Anda — tepat waktu, selalu!
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="{{ route('customer.menu') }}" class="hero-cta-primary">
                        <i class="bi bi-grid-fill"></i> Lihat Menu
                    </a>
                    <a href="{{ route('customer.cart') }}" class="hero-cta-ghost">
                        <i class="bi bi-bag-check"></i> Keranjang Saya
                    </a>
                </div>
                <div class="hero-stats">
                    @foreach([[
                        \App\Models\Order::where('status','completed')->count() . '+',
                        'Pesanan Selesai'
                    ],[
                        number_format($avgRating, 1) . '★',
                        'Rating Pelanggan'
                    ],[
                        '10+',
                        'Tahun Pengalaman'
                    ]] as [$num,$lbl])
                    <div>
                        <div class="hero-stat-num">{{ $num }}</div>
                        <div class="hero-stat-lbl">{{ $lbl }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
            {{-- Right --}}
            <div class="col-lg-6 d-none d-lg-block">
                <div class="hero-img-wrap">
                    <div class="hero-food-circle">
                        <div class="hero-food-img">🍱</div>
                    </div>
                    {{-- Float cards --}}
                    <div class="hero-float-card card-1">
                        <div class="fc-icon" style="background:#D1FAE5">🚚</div>
                        <div>
                            <div>Antar ke Lokasi</div>
                            <div style="font-size:.7rem;color:#9CA3AF;font-weight:500">Seluruh area Jakarta</div>
                        </div>
                    </div>
                    <div class="hero-float-card card-2">
                        <div class="fc-icon" style="background:#FEE2E2">⭐</div>
                        <div>
                            <div>Rating {{ number_format($avgRating, 1) }}/5</div>
                            <div style="font-size:.7rem;color:#9CA3AF;font-weight:500">dari {{ $totalReviews }} review</div>
                        </div>
                    </div>
                    <div class="hero-float-card card-3">
                        <div class="fc-icon" style="background:#FEF3C7">🍛</div>
                        <div>
                            <div>Menu Segar</div>
                            <div style="font-size:.7rem;color:#9CA3AF;font-weight:500">Dimasak tiap hari</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════
     STATS BAR
══════════════════════════════════════════ --}}
<div class="stats-bar">
    <div class="container">
        <div class="d-flex justify-content-center align-items-center gap-4 flex-wrap">
            @foreach([
                [App\Models\Order::where('status','completed')->count() . '+', 'Pesanan Sukses', 'bi-bag-check-fill'],
                [App\Models\Menu::where('is_active',true)->count() . '+',  'Menu Pilihan',   'bi-grid-fill'],
                [number_format($avgRating, 1) . '★', 'Rating Rata-rata','bi-star-fill'],
                ['10+',  'Tahun Melayani', 'bi-award-fill'],
            ] as [$num, $lbl, $icon])
            <div class="stat-item px-4">
                <div class="d-flex align-items-center justify-content-center gap-2 mb-1">
                    <i class="bi {{ $icon }}" style="font-size:1rem;opacity:.8"></i>
                    <div class="stat-num">{{ $num }}</div>
                </div>
                <div class="stat-lbl">{{ $lbl }}</div>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════
     KATEGORI
══════════════════════════════════════════ --}}
<section class="py-5 mt-2">
    <div class="container">
        <div class="row align-items-end mb-4">
            <div class="col">
                <div class="section-label">Kategori</div>
                <h2 class="section-heading">Pilih <span>Kategori</span> Favorit</h2>
                <div class="section-line"></div>
            </div>
            <div class="col-auto">
                <a href="{{ route('customer.menu') }}" class="btn btn-sm btn-outline-danger rounded-pill px-4 fw-700">
                    Semua Menu <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
        <div class="row g-3">
            @php
            $categories = [
                ['🍱','Nasi Box','Paket lengkap'],
                ['🍗','Ayam Bakar','& Goreng'],
                ['🥩','Kambing','Aqiqah & Sate'],
                ['🥗','Prasmanan','Buffet lengkap'],
                ['🍢','Snack Box','Kudapan seru'],
                ['🍰','Dessert','Penutup manis'],
            ];
            @endphp
            @foreach($categories as [$icon, $name, $sub])
            <div class="col-4 col-md-2">
                <a href="{{ route('customer.menu') }}" class="cat-card">
                    <div class="cat-icon-wrap">{{ $icon }}</div>
                    <div class="cat-name">{{ $name }}</div>
                    <div class="cat-count">{{ $sub }}</div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════
     FEATURED MENU
══════════════════════════════════════════ --}}
@if(isset($featuredMenus) && $featuredMenus->count())
<section class="py-5" style="background:#F9FAFB">
    <div class="container">
        <div class="row align-items-end mb-4">
            <div class="col">
                <div class="section-label">Menu Terpopuler</div>
                <h2 class="section-heading">Menu <span>Unggulan</span> Kami</h2>
                <div class="section-line"></div>
            </div>
            <div class="col-auto">
                <a href="{{ route('customer.menu') }}" class="btn btn-sm btn-outline-danger rounded-pill px-4 fw-700">
                    Lihat Semua <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
        <div class="row g-4">
            @foreach($featuredMenus as $menu)
            <div class="col-md-6 col-lg-4">
                <div class="menu-card-v2">
                    <div class="menu-card-v2-img">
                        @if($menu->image)
                            <img src="{{ Storage::url($menu->image) }}" alt="{{ $menu->name }}">
                        @else
                            <div class="menu-placeholder-v2">🍛</div>
                        @endif
                        @if($menu->category)
                            <span class="menu-badge">{{ $menu->category->name }}</span>
                        @endif
                        <div class="menu-fav"><i class="bi bi-heart-fill"></i></div>
                    </div>
                    <div class="menu-card-v2-body">
                        <div class="menu-card-v2-cat">{{ $menu->category->name ?? 'Catering' }}</div>
                        <div class="menu-card-v2-name">{{ $menu->name }}</div>
                        <div class="menu-card-v2-desc">{{ Str::limit($menu->description, 75) }}</div>
                        <div class="menu-card-v2-footer">
                            <div>
                                <div class="menu-card-v2-price">
                                    {{ $menu->formatted_price }}
                                    <small>/pax</small>
                                </div>
                            </div>
                            <a href="{{ route('customer.menu.detail', $menu) }}" class="btn-order">
                                <i class="bi bi-cart-plus"></i> Pesan
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ══════════════════════════════════════════
     HOW IT WORKS
══════════════════════════════════════════ --}}
<section class="how-section py-5">
    <div class="container">
        <div class="text-center mb-5">
            <div class="section-label">Mudah & Cepat</div>
            <h2 class="section-heading">Cara <span>Pesan</span></h2>
            <div class="section-line mx-auto"></div>
            <p class="section-sub mt-3">Hanya 4 langkah, pesanan langsung diproses</p>
        </div>
        <div class="row g-4 justify-content-center">
            @php
            $steps = [
                ['🛒','Pilih Menu','Jelajahi katalog dan tambahkan menu ke keranjang belanja'],
                ['📝','Isi Detail','Lengkapi data acara, tanggal, dan alamat pengiriman'],
                ['💳','Bayar via QRIS','Bayar mudah dengan scan QRIS lalu upload bukti transfer'],
                ['🚚','Terima Pesanan','Pesanan dikirim tepat waktu ke lokasi acara Anda'],
            ];
            @endphp
            @foreach($steps as $i => [$icon, $title, $desc])
            <div class="col-6 col-md-3">
                <div class="step-card">
                    <div class="step-circle">
                        {{ $icon }}
                        <span class="step-num">{{ $i+1 }}</span>
                    </div>
                    @if($i < 3)
                        <div class="step-arrow d-none d-md-block"><i class="bi bi-chevron-right"></i></div>
                    @endif
                    <div class="step-title">{{ $title }}</div>
                    <p class="step-desc">{{ $desc }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════
     WHY US
══════════════════════════════════════════ --}}
<section class="py-5" style="background:#F9FAFB">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-4">
                <div class="section-label">Keunggulan Kami</div>
                <h2 class="section-heading">Kenapa Pilih <span>Kami?</span></h2>
                <div class="section-line"></div>
                <p class="section-sub mt-3 mb-4">
                    Kami berkomitmen memberikan pengalaman catering terbaik — dari kualitas makanan hingga ketepatan pengiriman.
                </p>
                <a href="{{ route('customer.about') }}" class="hero-cta-primary" style="display:inline-flex">
                    Pelajari Lebih <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            <div class="col-lg-8">
                <div class="row g-3">
                    @foreach([
                        ['red',    'bi-fire',         'Masak Segar Tiap Hari',   'Dimasak fresh setiap hari dengan bahan pilihan berkualitas tanpa pengawet.'],
                        ['orange', 'bi-truck',         'Antar ke Lokasi',         'Pengiriman tepat waktu ke seluruh area Jakarta oleh tim berpengalaman.'],
                        ['green',  'bi-patch-check',   'Harga Terjangkau',        'Paket mulai Rp 35.000/pax, cocok untuk semua kalangan dan acara.'],
                        ['blue',   'bi-phone',         'Pesan 100% Online',       'Sistem pemesanan mudah, cepat, dan status bisa dipantau realtime.'],
                    ] as [$color, $icon, $title, $desc])
                    <div class="col-md-6">
                        <div class="why-card">
                            <div class="why-icon {{ $color }}">
                                <i class="bi {{ $icon }}" style="font-size:1.3rem;color:{{ $color=='red'?'#C0392B':($color=='orange'?'#D97706':($color=='green'?'#059669':'#2563EB')) }}"></i>
                            </div>
                            <div>
                                <div class="why-title">{{ $title }}</div>
                                <p class="why-desc">{{ $desc }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════
     TESTIMONI
══════════════════════════════════════════ --}}
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <div class="section-label">Testimoni</div>
            <h2 class="section-heading">Kata <span>Pelanggan</span> Kami</h2>
            <div class="section-line mx-auto"></div>
            @if($totalReviews > 0)
                <p class="section-sub mt-3">
                    <i class="bi bi-star-fill" style="color:#F59E0B"></i>
                    Rating rata-rata <strong>{{ number_format($avgRating, 1) }}</strong> dari {{ $totalReviews }} review
                </p>
            @endif
        </div>
        <div class="row g-4">
            @forelse($latestReviews as $review)
            <div class="col-md-4">
                <div class="testi-card">
                    <div class="testi-quote">"</div>
                    <div class="testi-stars">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }}"
                               style="color:{{ $i <= $review->rating ? '#F59E0B' : '#E5E7EB' }}"></i>
                        @endfor
                    </div>
                    <p class="testi-text">{{ $review->comment ?: 'Pelayanan sangat memuaskan!' }}</p>
                    <div class="d-flex align-items-center gap-3">
                        <div class="testi-avatar">{{ strtoupper(substr($review->user->name ?? '?', 0, 1)) }}</div>
                        <div>
                            <div class="testi-name">{{ $review->user->name ?? 'Pelanggan' }}</div>
                            <div class="testi-role">Pesanan #{{ $review->order->order_number ?? '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            {{-- Fallback: hardcoded testimonials if no reviews yet --}}
            @foreach([
                ['Anisa R.', 'Ibu Rumah Tangga', 'Pesan untuk acara arisan, makanannya enak banget dan datang tepat waktu. Tamunya pada happy semua!', 5],
                ['Budi S.',  'Event Organizer',   'Sudah beberapa kali pakai untuk event kantor. Konsisten enak, porsi pas, dan harga bersahabat.', 5],
                ['Dina K.',  'Pelanggan Tetap',   'Paket aqiqahnya komplit dan tasty. Prosesnya gampang banget, pesan online langsung beres!', 5],
            ] as [$name, $role, $text, $stars])
            <div class="col-md-4">
                <div class="testi-card">
                    <div class="testi-quote">"</div>
                    <div class="testi-stars">{{ str_repeat('★', $stars) }}</div>
                    <p class="testi-text">{{ $text }}</p>
                    <div class="d-flex align-items-center gap-3">
                        <div class="testi-avatar">{{ substr($name, 0, 1) }}</div>
                        <div>
                            <div class="testi-name">{{ $name }}</div>
                            <div class="testi-role">{{ $role }}</div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
            @endforelse
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════
     CTA BANNER
══════════════════════════════════════════ --}}
<section class="py-5">
    <div class="container">
        <div class="cta-banner">
            <div class="row align-items-center position-relative" style="z-index:1">
                <div class="col-lg-7 mb-4 mb-lg-0">
                    <div class="section-label" style="color:rgba(255,255,255,.6)">Mulai Sekarang</div>
                    <h2 class="cta-banner-title">
                        Siap Buat Acara Anda<br>
                        <span style="color:#FFD700">Lebih Berkesan?</span>
                    </h2>
                    <p class="cta-banner-sub">
                        Percayakan kebutuhan catering Anda kepada kami.<br>
                        Kualitas terjamin, harga bersahabat, pengiriman tepat waktu.
                    </p>
                </div>
                <div class="col-lg-5 d-flex flex-wrap gap-3 justify-content-lg-end">
                    <a href="{{ route('customer.menu') }}" class="btn-cta-white">
                        <i class="bi bi-cart-plus"></i> Pesan Sekarang
                    </a>
                    <a href="{{ route('customer.about') }}" class="btn-cta-ghost">
                        Tentang Kami
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

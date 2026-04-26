<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Catering Family Jakarta')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --cfj-primary:      #C0392B;
            --cfj-primary-dark: #a93226;
            --cfj-secondary:    #E67E22;
            --cfj-bg:           #FAFAFA;
        }
        *, *::before, *::after { box-sizing: border-box; }
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background: #fff; color: #111827; }

        /* ════════════════════════════════
           NAVBAR
        ════════════════════════════════ */
        .cfj-navbar {
            background: rgba(255,255,255,.96);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(0,0,0,.07);
            padding: 0;
            position: sticky; top: 0; z-index: 1000;
            height: 64px;
            display: flex; align-items: center;
        }
        .cfj-navbar .container {
            display: flex; align-items: center; justify-content: space-between;
            height: 100%;
        }

        /* Brand */
        .nav-brand {
            display: flex; align-items: center; gap: 10px;
            text-decoration: none; flex-shrink: 0;
        }
        .nav-brand-icon {
            width: 36px; height: 36px; border-radius: 10px;
            background: linear-gradient(135deg, var(--cfj-primary), var(--cfj-secondary));
            display: flex; align-items: center; justify-content: center;
            font-size: 1rem; flex-shrink: 0;
        }
        .nav-brand-name {
            font-size: .95rem; font-weight: 800; color: #111827; line-height: 1.2;
        }
        .nav-brand-name small {
            font-size: .65rem; color: #9CA3AF; font-weight: 500; display: block;
        }

        /* Nav links */
        .nav-links {
            display: flex; align-items: center; gap: 2px;
        }
        .nav-links a {
            font-size: .85rem; font-weight: 600; color: #6B7280;
            text-decoration: none; padding: 7px 14px; border-radius: 8px;
            transition: all .18s;
        }
        .nav-links a:hover { color: #111827; background: #F3F4F6; }
        .nav-links a.active { color: var(--cfj-primary); background: #FFF0EE; }

        /* Right actions */
        .nav-actions { display: flex; align-items: center; gap: 8px; }

        /* Keranjang button */
        .cart-btn {
            position: relative;
            width: 40px; height: 40px; border-radius: 10px;
            background: #FFF0EE;
            display: flex; align-items: center; justify-content: center;
            text-decoration: none;
            transition: all .18s;
            flex-shrink: 0;
        }
        .cart-btn:hover { background: var(--cfj-primary); }
        .cart-btn:hover .cart-icon { color: #fff; }
        .cart-icon {
            font-size: 1.15rem;
            color: var(--cfj-primary);
            line-height: 1;
            display: flex; align-items: center; justify-content: center;
        }
        .cart-badge {
            position: absolute; top: -4px; right: -4px;
            min-width: 18px; height: 18px;
            background: var(--cfj-primary); color: #fff;
            border-radius: 50px; font-size: .6rem; font-weight: 800;
            display: flex; align-items: center; justify-content: center;
            border: 2px solid #fff; padding: 0 4px;
            line-height: 1;
        }

        /* User dropdown */
        .nav-user-btn {
            display: flex; align-items: center; gap: 8px;
            padding: 6px 12px 6px 6px; border-radius: 50px;
            border: 1.5px solid #E5E7EB; background: #fff;
            cursor: pointer; transition: all .18s;
        }
        .nav-user-btn:hover { border-color: var(--cfj-primary); background: #FFF0EE; }
        .nav-user-avatar {
            width: 28px; height: 28px; border-radius: 50%;
            background: linear-gradient(135deg, var(--cfj-primary), var(--cfj-secondary));
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-size: .72rem; font-weight: 800; flex-shrink: 0;
        }
        .nav-user-name { font-size: .82rem; font-weight: 700; color: #111827; }
        .dropdown-menu {
            border: none; border-radius: 14px;
            box-shadow: 0 8px 32px rgba(0,0,0,.12), 0 0 0 1px rgba(0,0,0,.05);
            padding: 8px; min-width: 200px; margin-top: 8px !important;
        }
        .dropdown-item {
            border-radius: 8px; padding: 9px 12px;
            font-size: .85rem; font-weight: 600; color: #374151;
            transition: all .15s;
        }
        .dropdown-item:hover { background: #F3F4F6; color: #111827; }
        .dropdown-item.text-danger:hover { background: #FEE2E2; color: var(--cfj-primary); }
        .dropdown-divider { margin: 6px 0; border-color: #F3F4F6; }

        /* Mobile hamburger */
        .nav-hamburger {
            width: 40px; height: 40px; border-radius: 10px;
            background: #F3F4F6; border: none;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.2rem; color: #374151; cursor: pointer;
            transition: all .18s;
        }
        .nav-hamburger:hover { background: #E5E7EB; }

        /* ════════════════════════════════
           OFFCANVAS MOBILE NAV
        ════════════════════════════════ */
        .offcanvas { border: none; }
        .offcanvas-header { border-bottom: 1px solid #F3F4F6; padding: 18px 20px; }
        .offcanvas-body { padding: 16px; }
        .mobile-nav-link {
            display: flex; align-items: center; gap: 12px;
            padding: 11px 14px; border-radius: 10px;
            text-decoration: none; color: #374151;
            font-size: .88rem; font-weight: 600;
            transition: all .15s; margin-bottom: 2px;
        }
        .mobile-nav-link:hover, .mobile-nav-link.active {
            background: #FFF0EE; color: var(--cfj-primary);
        }
        .mobile-nav-link i { font-size: 1rem; width: 20px; text-align: center; }

        /* ════════════════════════════════
           SHARED SECTION STYLES
        ════════════════════════════════ */
        .section-title {
            font-size: clamp(1.7rem, 3.5vw, 2.4rem);
            font-weight: 800; color: #111827;
        }
        .section-title span { color: var(--cfj-primary); }
        .section-divider {
            width: 48px; height: 4px;
            background: linear-gradient(90deg, var(--cfj-primary), var(--cfj-secondary));
            border-radius: 4px; margin: 12px 0 24px;
        }

        /* ════════════════════════════════
           MENU CARD
        ════════════════════════════════ */
        .menu-card {
            border: none; border-radius: 20px;
            box-shadow: 0 2px 16px rgba(0,0,0,.07);
            transition: transform .25s, box-shadow .25s;
            overflow: hidden;
        }
        .menu-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 16px 40px rgba(192,57,43,.13);
        }
        .menu-card img { width: 100%; height: 200px; object-fit: cover; }
        .menu-placeholder {
            width: 100%; height: 200px;
            background: linear-gradient(135deg, #FFF5F3, #FFF0E8);
            display: flex; align-items: center; justify-content: center;
        }
        .menu-placeholder-icon { font-size: 4rem; }

        /* ════════════════════════════════
           STATUS BADGES
        ════════════════════════════════ */
        .status-badge {
            padding: 4px 12px; border-radius: 50px;
            font-size: .76rem; font-weight: 700; display: inline-block;
        }
        .status-pending    { background: #FFF3CD; color: #856404; }
        .status-confirmed  { background: #CCE5FF; color: #004085; }
        .status-processing { background: #D1ECF1; color: #0c5460; }
        .status-delivered  { background: #E2E3E5; color: #383d41; }
        .status-completed  { background: #D4EDDA; color: #155724; }
        .status-cancelled  { background: #F8D7DA; color: #721c24; }
        .status-paid       { background: #D4EDDA; color: #155724; }
        .status-unpaid     { background: #FFF3CD; color: #856404; }
        .status-pending_verification { background: #CCE5FF; color: #004085; }
        .status-failed     { background: #F8D7DA; color: #721c24; }

        /* ════════════════════════════════
           BUTTONS
        ════════════════════════════════ */
        .btn-danger { background: var(--cfj-primary); border-color: var(--cfj-primary); }
        .btn-danger:hover { background: var(--cfj-primary-dark); border-color: var(--cfj-primary-dark); }

        /* ════════════════════════════════
           FOOTER
        ════════════════════════════════ */
        .cfj-footer {
            background: #0F172A;
            color: rgba(255,255,255,.5);
            padding: 64px 0 0;
            margin-top: 80px;
        }
        .cfj-footer h6 {
            color: #fff; font-weight: 700; font-size: .82rem;
            letter-spacing: .8px; text-transform: uppercase; margin-bottom: 18px;
        }
        .cfj-footer a {
            color: rgba(255,255,255,.45); text-decoration: none;
            font-size: .85rem; transition: color .18s; display: block; margin-bottom: 10px;
        }
        .cfj-footer a:hover { color: var(--cfj-secondary); }
        .footer-brand-name {
            font-size: 1rem; font-weight: 800; color: #fff;
        }
        .footer-brand-sub { font-size: .75rem; color: rgba(255,255,255,.35); }
        .footer-icon {
            width: 38px; height: 38px; border-radius: 10px; flex-shrink: 0;
            background: linear-gradient(135deg, var(--cfj-primary), var(--cfj-secondary));
            display: flex; align-items: center; justify-content: center; font-size: 1.1rem;
        }
        .footer-social {
            width: 36px; height: 36px; border-radius: 9px;
            background: rgba(255,255,255,.07); border: 1px solid rgba(255,255,255,.1);
            display: flex; align-items: center; justify-content: center;
            color: rgba(255,255,255,.5); text-decoration: none; font-size: .95rem;
            transition: all .18s;
        }
        .footer-social:hover {
            background: var(--cfj-primary); border-color: var(--cfj-primary);
            color: #fff;
        }
        .footer-bottom {
            border-top: 1px solid rgba(255,255,255,.07);
            padding: 20px 0; margin-top: 52px;
        }
        .footer-contact-item {
            display: flex; align-items: flex-start; gap: 10px;
            font-size: .83rem; margin-bottom: 12px;
        }
        .footer-contact-item i {
            margin-top: 2px; flex-shrink: 0;
            color: var(--cfj-secondary); font-size: .9rem;
        }

        /* ════════════════════════════════
           MISC
        ════════════════════════════════ */
        .fw-600 { font-weight: 600 !important; }
        .fw-700 { font-weight: 700 !important; }
        .fw-800 { font-weight: 800 !important; }

        /* Toast notifications */
        .toast-stack {
            position: fixed; bottom: 24px; right: 24px; z-index: 9999;
            display: flex; flex-direction: column; gap: 10px;
            max-width: 360px;
        }
        .cfj-toast {
            display: flex; align-items: center; gap: 12px;
            padding: 14px 16px; border-radius: 14px;
            box-shadow: 0 8px 32px rgba(0,0,0,.14);
            font-size: .85rem; font-weight: 600;
            animation: slideUp .3s ease;
        }
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .cfj-toast.success { background: #ECFDF5; color: #065F46; border: 1px solid #A7F3D0; }
        .cfj-toast.error   { background: #FEF2F2; color: #991B1B; border: 1px solid #FECACA; }
        .cfj-toast i { font-size: 1.1rem; flex-shrink: 0; }
        .cfj-toast .btn-close { margin-left: auto; }
    </style>
    @stack('styles')
</head>
<body>

{{-- ════════════════════════════════
     NAVBAR
════════════════════════════════ --}}
<header class="cfj-navbar">
    <div class="container">

        {{-- Brand --}}
        <a href="{{ route('home') }}" class="nav-brand">
            <div class="nav-brand-icon">🍱</div>
            <div class="nav-brand-name">
                Catering Family Jakarta
                <small>Pesan Online · Antar ke Lokasi</small>
            </div>
        </a>

        {{-- Nav links (desktop) --}}
        <nav class="nav-links d-none d-lg-flex">
            <a href="{{ route('home') }}"
               class="{{ request()->routeIs('home') ? 'active' : '' }}">Beranda</a>
            <a href="{{ route('customer.menu') }}"
               class="{{ request()->routeIs('customer.menu*') ? 'active' : '' }}">Menu</a>
            <a href="{{ route('customer.about') }}"
               class="{{ request()->routeIs('customer.about') ? 'active' : '' }}">Tentang Kami</a>
            <a href="{{ route('customer.track') }}"
               class="{{ request()->routeIs('customer.track') ? 'active' : '' }}">Lacak Pesanan</a>
        </nav>

        {{-- Right actions --}}
        <div class="nav-actions">

            {{-- Keranjang --}}
            @php $cartCount = count(session('cart', [])); @endphp
            <a href="{{ route('customer.cart') }}" class="cart-btn">
                <span class="cart-icon">
                    <i class="bi bi-bag2"></i>
                </span>
                @if($cartCount > 0)
                    <span class="cart-badge">{{ $cartCount }}</span>
                @endif
            </a>

            {{-- User (kalau login) --}}
            @auth
                @if(auth()->user()->role !== 'admin')
                <div class="dropdown d-none d-md-block">
                    <div class="nav-user-btn" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="nav-user-avatar">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <span class="nav-user-name">{{ Str::limit(auth()->user()->name, 12) }}</span>
                        <i class="bi bi-chevron-down" style="font-size:.7rem; color:#9CA3AF"></i>
                    </div>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="{{ route('dashboard') }}">
                                <i class="bi bi-person-circle me-2 text-muted"></i>Dashboard Saya
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('customer.orders.index') }}">
                                <i class="bi bi-clock-history me-2 text-muted"></i>Riwayat Pesanan
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="bi bi-box-arrow-right me-2"></i>Keluar
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
                @endif
            @endauth

            {{-- Mobile hamburger --}}
            <button class="nav-hamburger d-lg-none"
                    data-bs-toggle="offcanvas" data-bs-target="#mobileNav">
                <i class="bi bi-list"></i>
            </button>
        </div>
    </div>
</header>

{{-- ════════════════════════════════
     MOBILE NAV OFFCANVAS
════════════════════════════════ --}}
<div class="offcanvas offcanvas-end" id="mobileNav" tabindex="-1">
    <div class="offcanvas-header">
        <div class="d-flex align-items-center gap-2">
            <div class="nav-brand-icon" style="width:30px;height:30px;font-size:.85rem;border-radius:8px">🍱</div>
            <span style="font-size:.9rem;font-weight:800;color:#111827">Catering Family Jakarta</span>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body">
        <div style="font-size:.7rem;font-weight:700;color:#9CA3AF;letter-spacing:.8px;text-transform:uppercase;padding:4px 14px 8px">Menu</div>
        <a href="{{ route('home') }}" class="mobile-nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
            <i class="bi bi-house"></i> Beranda
        </a>
        <a href="{{ route('customer.menu') }}" class="mobile-nav-link {{ request()->routeIs('customer.menu*') ? 'active' : '' }}">
            <i class="bi bi-grid"></i> Menu
        </a>
        <a href="{{ route('customer.about') }}" class="mobile-nav-link {{ request()->routeIs('customer.about') ? 'active' : '' }}">
            <i class="bi bi-info-circle"></i> Tentang Kami
        </a>
        <a href="{{ route('customer.track') }}" class="mobile-nav-link {{ request()->routeIs('customer.track') ? 'active' : '' }}">
            <i class="bi bi-search"></i> Lacak Pesanan
        </a>
        <a href="{{ route('customer.cart') }}" class="mobile-nav-link">
            <i class="bi bi-bag2"></i> Keranjang
            @if($cartCount > 0)
                <span class="ms-auto badge rounded-pill"
                      style="background:var(--cfj-primary);font-size:.72rem">{{ $cartCount }}</span>
            @endif
        </a>

        @auth
            @if(auth()->user()->role !== 'admin')
            <div class="mt-3 pt-3" style="border-top:1px solid #F3F4F6">
                <div style="font-size:.7rem;font-weight:700;color:#9CA3AF;letter-spacing:.8px;text-transform:uppercase;padding:4px 14px 8px">Akun Saya</div>
                <a href="{{ route('dashboard') }}" class="mobile-nav-link">
                    <i class="bi bi-person-circle"></i> Dashboard Saya
                </a>
                <a href="{{ route('customer.orders.index') }}" class="mobile-nav-link">
                    <i class="bi bi-clock-history"></i> Riwayat Pesanan
                </a>
                <div class="px-2 mt-2">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                                class="w-100 btn btn-sm rounded-3 fw-700"
                                style="border:1.5px solid #FEE2E2;color:var(--cfj-primary);background:#FFF5F5;padding:9px">
                            <i class="bi bi-box-arrow-right me-2"></i>Keluar
                        </button>
                    </form>
                </div>
            @endif
        @endauth
    </div>
</div>

{{-- ════════════════════════════════
     TOAST NOTIFICATIONS
════════════════════════════════ --}}
@if(session('success') || session('error'))
<div class="toast-stack">
    @if(session('success'))
    <div class="cfj-toast success" id="toastSuccess">
        <i class="bi bi-check-circle-fill"></i>
        <span>{{ session('success') }}</span>
        <button type="button" class="btn-close btn-close-sm ms-auto"
                onclick="this.closest('.cfj-toast').remove()"></button>
    </div>
    @endif
    @if(session('error'))
    <div class="cfj-toast error" id="toastError">
        <i class="bi bi-exclamation-circle-fill"></i>
        <span>{{ session('error') }}</span>
        <button type="button" class="btn-close btn-close-sm ms-auto"
                onclick="this.closest('.cfj-toast').remove()"></button>
    </div>
    @endif
</div>
<script>
    // Auto dismiss toast after 4 seconds
    setTimeout(() => {
        document.querySelectorAll('.cfj-toast').forEach(el => {
            el.style.transition = 'opacity .4s';
            el.style.opacity = '0';
            setTimeout(() => el.remove(), 400);
        });
    }, 4000);
</script>
@endif

{{-- ════════════════════════════════
     CONTENT
════════════════════════════════ --}}
@yield('content')

{{-- ════════════════════════════════
     FOOTER
════════════════════════════════ --}}
<footer class="cfj-footer">
    <div class="container">
        <div class="row g-5">

            {{-- Brand --}}
            <div class="col-lg-4">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="footer-icon">🍱</div>
                    <div>
                        <div class="footer-brand-name">Catering Family Jakarta</div>
                        <div class="footer-brand-sub">Catering Jakarta Terpercaya</div>
                    </div>
                </div>
                <p style="font-size:.84rem; line-height:1.85; max-width:280px">
                    Melayani kebutuhan catering nasi box dan paket aqiqah untuk seluruh wilayah Jakarta dengan cita rasa terbaik dan harga terjangkau.
                </p>
                <div class="d-flex gap-2 mt-4">
                    <a href="https://wa.me/6281234567890" class="footer-social" title="WhatsApp">
                        <i class="bi bi-whatsapp"></i>
                    </a>
                    <a href="#" class="footer-social" title="Instagram">
                        <i class="bi bi-instagram"></i>
                    </a>
                    <a href="#" class="footer-social" title="Facebook">
                        <i class="bi bi-facebook"></i>
                    </a>
                </div>
            </div>

            {{-- Navigasi --}}
            <div class="col-6 col-lg-2">
                <h6>Navigasi</h6>
                <a href="{{ route('home') }}">Beranda</a>
                <a href="{{ route('customer.menu') }}">Katalog Menu</a>
                <a href="{{ route('customer.about') }}">Tentang Kami</a>
                <a href="{{ route('customer.track') }}">Lacak Pesanan</a>
            </div>

            {{-- Layanan --}}
            <div class="col-6 col-lg-2">
                <h6>Layanan</h6>
                <a href="{{ route('customer.menu', ['kategori'=>'nasi-box']) }}">Nasi Box</a>
                <a href="{{ route('customer.menu', ['kategori'=>'aqiqah']) }}">Paket Aqiqah</a>
                <a href="{{ route('customer.cart') }}">Keranjang</a>
                <a href="{{ route('customer.orders.checkout') }}">Checkout</a>
            </div>

            {{-- Kontak --}}
            <div class="col-lg-4">
                <h6>Kontak & Jam Operasional</h6>
                <div class="footer-contact-item">
                    <i class="bi bi-whatsapp"></i>
                    <span>+62 812-3456-7890</span>
                </div>
                <div class="footer-contact-item">
                    <i class="bi bi-envelope"></i>
                    <span>info@cateringfamilyjakarta.com</span>
                </div>
                <div class="footer-contact-item">
                    <i class="bi bi-geo-alt"></i>
                    <span>Jakarta, Indonesia</span>
                </div>

                <div class="mt-4 p-3 rounded-3" style="background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.08)">
                    <div style="font-size:.75rem;font-weight:700;color:#fff;margin-bottom:10px">
                        <i class="bi bi-clock me-1" style="color:var(--cfj-secondary)"></i>Jam Operasional
                    </div>
                    @foreach([
                        ['Senin – Jumat', '08.00 – 17.00', false],
                        ['Sabtu',         '08.00 – 15.00', false],
                        ['Minggu',        'Tutup',          true],
                    ] as [$day, $hours, $closed])
                    <div class="d-flex justify-content-between" style="font-size:.8rem;margin-bottom:6px">
                        <span>{{ $day }}</span>
                        <span style="font-weight:700;color:{{ $closed ? 'var(--cfj-primary)' : '#fff' }}">
                            {{ $hours }}
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>

        {{-- Footer Bottom --}}
        <div class="footer-bottom d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
            <p class="mb-0" style="font-size:.8rem">
                &copy; {{ date('Y') }} Catering Family Jakarta. Semua hak dilindungi.
            </p>
            <p class="mb-0" style="font-size:.78rem">
                Dibuat dengan ❤️ untuk PI Universitas Gunadarma
            </p>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>

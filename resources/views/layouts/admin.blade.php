<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — Catering Family Jakarta</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --sidebar-w: 256px;
            --cfj-primary: #C0392B;
            --cfj-primary-dark: #a93226;
            --cfj-secondary: #E67E22;
            --sidebar-hover: rgba(255,255,255,.07);
            --topbar-h: 60px;
        }
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background: #F2F4F8; }

        /* ── Topbar ── */
        .topbar {
            background: rgba(255,255,255,.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            height: var(--topbar-h);
            padding: 0 28px;
            box-shadow: 0 1px 0 rgba(0,0,0,.06);
            display: flex; align-items: center; justify-content: space-between;
            position: sticky; top: 0; z-index: 100;
        }

        /* ── Sidebar ── */
        .sidebar {
            position: fixed; top: 0; left: 0;
            width: var(--sidebar-w); height: 100vh;
            background: linear-gradient(180deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            z-index: 1000; overflow-y: auto;
            transition: transform .3s ease;
            display: flex; flex-direction: column;
            box-shadow: 4px 0 24px rgba(0,0,0,.15);
        }
        .sidebar::-webkit-scrollbar { width: 4px; }
        .sidebar::-webkit-scrollbar-track { background: transparent; }
        .sidebar::-webkit-scrollbar-thumb { background: rgba(255,255,255,.1); border-radius: 4px; }

        .sidebar-brand {
            padding: 20px 20px 16px;
            border-bottom: 1px solid rgba(255,255,255,.07);
            display: flex; align-items: center; gap: 12px; text-decoration: none;
        }
        .sidebar-brand-icon {
            width: 38px; height: 38px; border-radius: 10px; flex-shrink: 0;
            background: linear-gradient(135deg, var(--cfj-primary), var(--cfj-secondary));
            display: flex; align-items: center; justify-content: center; font-size: 1.1rem;
        }
        .sidebar-brand-text strong { display: block; color: #fff; font-size: .9rem; font-weight: 800; }
        .sidebar-brand-text small { color: rgba(255,255,255,.45); font-size: .72rem; }

        .sidebar-nav { padding: 16px 12px; flex: 1; }
        .sidebar-section {
            font-size: .65rem; font-weight: 800; letter-spacing: 1.2px;
            text-transform: uppercase; color: rgba(255,255,255,.3);
            padding: 16px 10px 6px; margin-top: 4px;
        }
        .sidebar-section:first-child { padding-top: 4px; }

        .sidebar-link {
            display: flex; align-items: center; gap: 11px;
            padding: 9px 12px; border-radius: 10px;
            color: rgba(255,255,255,.6);
            text-decoration: none; font-weight: 600; font-size: .875rem;
            transition: all .2s; margin-bottom: 2px;
        }
        .sidebar-link i { font-size: 1rem; width: 18px; text-align: center; flex-shrink: 0; }
        .sidebar-link:hover { background: var(--sidebar-hover); color: #fff; }
        .sidebar-link.active {
            background: linear-gradient(135deg, var(--cfj-primary), #e05c4b);
            color: #fff;
            box-shadow: 0 4px 14px rgba(192,57,43,.35);
        }
        .sidebar-link .badge-count {
            margin-left: auto;
            background: rgba(255,255,255,.15);
            color: #fff;
            font-size: .68rem; font-weight: 700;
            padding: 2px 7px; border-radius: 50px;
        }
        .sidebar-link.active .badge-count { background: rgba(255,255,255,.25); }

        .sidebar-footer {
            padding: 12px; border-top: 1px solid rgba(255,255,255,.07);
        }
        .sidebar-user {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 12px; border-radius: 10px;
            background: rgba(255,255,255,.05);
        }
        .sidebar-user-avatar {
            width: 34px; height: 34px; border-radius: 50%; flex-shrink: 0;
            background: linear-gradient(135deg, var(--cfj-primary), var(--cfj-secondary));
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-weight: 800; font-size: .85rem;
        }
        .sidebar-user-name { color: #fff; font-size: .82rem; font-weight: 700; line-height: 1.3; }
        .sidebar-user-role { color: rgba(255,255,255,.4); font-size: .7rem; }

        /* ── Main Content ── */
        .main-content {
            margin-left: var(--sidebar-w);
            min-height: 100vh;
            display: flex; flex-direction: column;
        }
        .page-content { padding: 28px; flex: 1; }

        /* ── Cards ── */
        .card { border: none; border-radius: 14px; box-shadow: 0 1px 12px rgba(0,0,0,.06); }
        .card-header {
            background: transparent;
            border-bottom: 1px solid #f0f0f0;
            padding: 16px 20px;
            font-weight: 700; font-size: .88rem;
        }

        /* ── Stat Cards ── */
        .stat-card {
            border-radius: 14px; padding: 22px 20px;
            color: #fff; position: relative; overflow: hidden;
        }
        .stat-card::before {
            content: ''; position: absolute;
            right: -24px; top: -24px;
            width: 110px; height: 110px; border-radius: 50%;
            background: rgba(255,255,255,.08);
        }
        .stat-card::after {
            content: ''; position: absolute;
            right: 20px; bottom: -30px;
            width: 80px; height: 80px; border-radius: 50%;
            background: rgba(255,255,255,.05);
        }

        /* ── Tables ── */
        .table { font-size: .875rem; }
        .table th {
            font-weight: 700; color: #888;
            font-size: .72rem; text-transform: uppercase; letter-spacing: .6px;
            padding: 12px 16px;
        }
        .table td { padding: 12px 16px; vertical-align: middle; }
        .table-hover tbody tr:hover { background: #FAFAFE; }

        /* ── Status Badges ── */
        .status-badge { padding: 4px 12px; border-radius: 50px; font-size: .76rem; font-weight: 700; }
        .badge-pending    { background: #FFF3CD; color: #856404; }
        .badge-confirmed  { background: #CCE5FF; color: #004085; }
        .badge-processing { background: #D1ECF1; color: #0c5460; }
        .badge-delivered  { background: #E2E3E5; color: #383d41; }
        .badge-completed  { background: #D4EDDA; color: #155724; }
        .badge-cancelled  { background: #F8D7DA; color: #721c24; }
        .badge-paid       { background: #D4EDDA; color: #155724; }
        .badge-unpaid     { background: #FFF3CD; color: #856404; }
        .badge-pending_verification { background: #CCE5FF; color: #004085; }
        .badge-failed     { background: #F8D7DA; color: #721c24; }

        /* ── Misc ── */
        .alert { border-radius: 12px; border: none; }
        .btn-danger { background: var(--cfj-primary); border-color: var(--cfj-primary); }
        .btn-danger:hover { background: var(--cfj-primary-dark); border-color: var(--cfj-primary-dark); }
        .fw-600 { font-weight: 600 !important; }
        .fw-700 { font-weight: 700 !important; }
        .fw-800 { font-weight: 800 !important; }

        /* ── Responsive ── */
        @media (max-width: 991px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .main-content { margin-left: 0; }
            .page-content { padding: 20px 16px; }
        }
    </style>
    @stack('styles')
</head>
<body>

{{-- ══════════ SIDEBAR ══════════ --}}
<aside class="sidebar" id="sidebar">

    {{-- Brand --}}
    <a href="{{ route('admin.dashboard') }}" class="sidebar-brand">
        @if(file_exists(public_path('images/logo.png')))
            <img src="{{ asset('images/logo.png') }}" alt="Logo"
                 style="width:38px;height:38px;border-radius:10px;object-fit:cover">
        @else
            <div class="sidebar-brand-icon">🍱</div>
        @endif
        <div class="sidebar-brand-text">
            <strong>Catering Family</strong>
            <small>Panel Admin</small>
        </div>
    </a>

    {{-- Navigation --}}
    <nav class="sidebar-nav">

        <div class="sidebar-section">Utama</div>
        <a href="{{ route('admin.dashboard') }}"
           class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>

        <div class="sidebar-section">Kelola Data</div>

        <a href="{{ route('admin.orders.index') }}"
           class="sidebar-link {{ request()->routeIs('admin.orders*') ? 'active' : '' }}">
            <i class="bi bi-bag-check"></i> Pesanan
        </a>

        <a href="{{ route('admin.payments.index') }}"
           class="sidebar-link {{ request()->routeIs('admin.payments*') ? 'active' : '' }}">
            <i class="bi bi-credit-card"></i> Pembayaran
            @php $pendingPay = \App\Models\Payment::where('status','pending_verification')->count(); @endphp
            @if($pendingPay > 0)
                <span class="badge-count">{{ $pendingPay }}</span>
            @endif
        </a>

        <a href="{{ route('admin.menus.index') }}"
           class="sidebar-link {{ request()->routeIs('admin.menus*') ? 'active' : '' }}">
            <i class="bi bi-card-list"></i> Menu
        </a>

        <a href="{{ route('admin.categories.index') }}"
           class="sidebar-link {{ request()->routeIs('admin.categories*') ? 'active' : '' }}">
            <i class="bi bi-grid"></i> Kategori
        </a>

        <a href="{{ route('admin.customers.index') }}"
           class="sidebar-link {{ request()->routeIs('admin.customers*') ? 'active' : '' }}">
            <i class="bi bi-people"></i> Pelanggan
        </a>

        <a href="{{ route('admin.coupons.index') }}"
           class="sidebar-link {{ request()->routeIs('admin.coupons*') ? 'active' : '' }}">
            <i class="bi bi-ticket-perforated"></i> Kupon/Promo
        </a>

        <a href="{{ route('admin.reviews.index') }}"
           class="sidebar-link {{ request()->routeIs('admin.reviews*') ? 'active' : '' }}">
            <i class="bi bi-chat-square-text"></i> Review
            @php $newReviews = \App\Models\Review::where('created_at', '>=', now()->subDays(7))->count(); @endphp
            @if($newReviews > 0)
                <span class="badge-count">{{ $newReviews }}</span>
            @endif
        </a>

        <div class="sidebar-section">Laporan & Lainnya</div>

        <a href="{{ route('admin.reports.index') }}"
           class="sidebar-link {{ request()->routeIs('admin.reports*') ? 'active' : '' }}">
            <i class="bi bi-bar-chart-line"></i> Laporan Pesanan
        </a>

        <a href="{{ route('admin.activity-logs.index') }}"
           class="sidebar-link {{ request()->routeIs('admin.activity-logs*') ? 'active' : '' }}">
            <i class="bi bi-clock-history"></i> Activity Log
        </a>

        <a href="{{ route('admin.settings.index') }}"
           class="sidebar-link {{ request()->routeIs('admin.settings*') ? 'active' : '' }}">
            <i class="bi bi-gear"></i> Pengaturan Harga
        </a>

        <a href="{{ route('home') }}" target="_blank" class="sidebar-link">
            <i class="bi bi-globe"></i> Lihat Website
        </a>

        <form action="{{ route('logout') }}" method="POST" class="m-0">
            @csrf
            <button type="submit"
                    class="sidebar-link w-100 border-0 bg-transparent text-start"
                    style="cursor:pointer">
                <i class="bi bi-box-arrow-left"></i> Keluar
            </button>
        </form>

    </nav>

    {{-- User Info --}}
    <div class="sidebar-footer">
        <div class="sidebar-user">
            <div class="sidebar-user-avatar">
                {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
            </div>
            <div class="min-w-0">
                <div class="sidebar-user-name text-truncate">{{ auth()->user()->name }}</div>
                <div class="sidebar-user-role">Administrator</div>
            </div>
        </div>
    </div>

</aside>

{{-- ══════════ MAIN ══════════ --}}
<div class="main-content">

    {{-- Topbar --}}
    <div class="topbar">
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-sm btn-light d-lg-none rounded-2 border-0"
                    onclick="document.getElementById('sidebar').classList.toggle('show')"
                    style="width:36px;height:36px;padding:0">
                <i class="bi bi-list fs-5"></i>
            </button>
            <div>
                <div class="fw-700" style="font-size:.95rem">@yield('page-title', 'Dashboard')</div>
            </div>
        </div>
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('home') }}" target="_blank"
               class="btn btn-sm btn-outline-secondary rounded-pill px-3 d-none d-md-flex align-items-center gap-1">
                <i class="bi bi-globe" style="font-size:.8rem"></i>
                <span style="font-size:.8rem">Website</span>
            </a>
            <div class="d-flex align-items-center gap-2">
                <div class="d-flex align-items-center justify-content-center rounded-circle fw-800 text-white flex-shrink-0"
                     style="width:32px;height:32px;background:linear-gradient(135deg,var(--cfj-primary),var(--cfj-secondary));font-size:.8rem">
                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                </div>
                <span class="text-muted small d-none d-sm-inline">{{ auth()->user()->name }}</span>
            </div>
        </div>
    </div>

    {{-- Flash Messages --}}
    <div class="page-content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4 d-flex align-items-center gap-2">
                <i class="bi bi-check-circle-fill flex-shrink-0"></i>
                <div>{{ session('success') }}</div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-4 d-flex align-items-center gap-2">
                <i class="bi bi-exclamation-circle-fill flex-shrink-0"></i>
                <div>{{ session('error') }}</div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>
</div>

{{-- Overlay mobile --}}
<div id="sidebarOverlay"
     style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:999"
     onclick="document.getElementById('sidebar').classList.remove('show'); this.style.display='none'">
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Toggle sidebar on mobile
    document.querySelector('[onclick*="sidebar"]')?.addEventListener('click', function() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        overlay.style.display = sidebar.classList.contains('show') ? 'block' : 'none';
    });

    // Auto dismiss alerts after 4 seconds
    setTimeout(() => {
        document.querySelectorAll('.alert-dismissible').forEach(el => {
            el.style.transition = 'opacity .4s';
            el.style.opacity = '0';
            setTimeout(() => el.remove(), 400);
        });
    }, 4000);

    // Global delete confirmation
    document.querySelectorAll('form[data-confirm]').forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!confirm(this.dataset.confirm || 'Yakin ingin menghapus data ini?')) {
                e.preventDefault();
            }
        });
    });
</script>
@stack('scripts')
</body>
</html>

@extends('layouts.app')
@section('title', 'Tentang Kami — Catering Family Jakarta')

@push('styles')
<style>
    .stat-pill {
        background: linear-gradient(135deg, var(--cfj-primary), var(--cfj-secondary));
        color: #fff; border-radius: 20px; padding: 28px 20px;
        text-align: center; height: 100%;
    }
    .contact-item {
        display: flex; align-items: flex-start; gap: 14px;
        padding: 14px 0; border-bottom: 1px solid #f0ece8;
    }
    .contact-item:last-child { border-bottom: none; }
    .contact-icon {
        width: 40px; height: 40px; border-radius: 10px; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center; font-size: 1.1rem;
    }
</style>
@endpush

@section('content')

{{-- Hero --}}
<section style="background:linear-gradient(135deg,#FFF5F3,#FFF0E8); padding:56px 0 40px">
    <div class="container text-center">
        <div style="font-size:3.5rem; margin-bottom:16px; filter:drop-shadow(0 4px 12px rgba(192,57,43,.2))">🍱</div>
        <div class="section-title">Tentang <span>Kami</span></div>
        <div class="section-divider mx-auto"></div>
        <p class="text-muted mx-auto" style="max-width:560px; line-height:1.8; font-size:1.02rem">
            Catering Family Jakarta hadir untuk memenuhi kebutuhan katering Anda dengan cita rasa terbaik dan harga yang terjangkau.
        </p>
    </div>
</section>

<div class="container py-5">

    {{-- Stats --}}
    <div class="row g-4 mb-5">
        @foreach([
            ['500+','🛍️','Pesanan Selesai'],
            ['4.8★','⭐','Rating Pelanggan'],
            ['10+','👨‍🍳','Tahun Pengalaman'],
            ['5','🏙️','Wilayah Jakarta'],
        ] as [$val, $icon, $label])
        <div class="col-6 col-md-3">
            <div class="stat-pill">
                <div style="font-size:1.8rem; margin-bottom:6px">{{ $icon }}</div>
                <div class="fw-800" style="font-size:1.6rem">{{ $val }}</div>
                <div style="font-size:.82rem; opacity:.85; margin-top:4px">{{ $label }}</div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Visi Misi --}}
    <div class="row g-4 mb-5">
        <div class="col-md-6">
            <div class="card h-100 p-4" style="border-left:4px solid var(--cfj-primary) !important">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div style="width:44px;height:44px;border-radius:12px;background:#FFF0EE;display:flex;align-items:center;justify-content:center;font-size:1.3rem">🎯</div>
                    <h5 class="fw-800 mb-0 text-danger">Visi</h5>
                </div>
                <p class="text-muted mb-0" style="line-height:1.8">
                    Menjadi penyedia layanan catering terpercaya di Jakarta yang mengutamakan kualitas, kebersihan, dan kepuasan pelanggan di setiap hidangan.
                </p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card h-100 p-4" style="border-left:4px solid var(--cfj-secondary) !important">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div style="width:44px;height:44px;border-radius:12px;background:#FFF5E8;display:flex;align-items:center;justify-content:center;font-size:1.3rem">🚀</div>
                    <h5 class="fw-800 mb-0" style="color:var(--cfj-secondary)">Misi</h5>
                </div>
                <ul class="text-muted mb-0 ps-3" style="line-height:2">
                    <li>Menyediakan makanan segar & berkualitas setiap hari</li>
                    <li>Memberikan pelayanan cepat, ramah, dan profesional</li>
                    <li>Terus berinovasi dalam menu dan sistem pemesanan</li>
                </ul>
            </div>
        </div>
    </div>

    {{-- Kenapa Kami --}}
    <div class="mb-5">
        <h4 class="fw-800 mb-4 text-center">Mengapa Memilih <span class="text-danger">Kami?</span></h4>
        <div class="row g-3">
            @foreach([
                ['🌿','Bahan Segar & Halal','Semua bahan dipilih dengan teliti, segar setiap hari, dan bersertifikat halal.'],
                ['⏱️','Tepat Waktu','Kami berkomitmen mengantarkan pesanan sesuai jadwal yang telah disepakati.'],
                ['💬','Layanan Responsif','Tim kami siap membantu dan merespons pertanyaan Anda dengan cepat.'],
                ['🎨','Menu Beragam','Dari nasi box harian hingga paket aqiqah lengkap untuk berbagai acara.'],
                ['🔒','Pembayaran Aman','Transaksi melalui QRIS resmi yang aman dan terverifikasi.'],
                ['📊','Transparan','Status pesanan dan pembayaran bisa dipantau secara real-time.'],
            ] as [$icon, $title, $desc])
            <div class="col-md-6 col-lg-4">
                <div class="d-flex gap-3 p-3 rounded-3 h-100" style="background:#FAFAFA; border:1px solid #F0ECE8">
                    <div style="width:44px;height:44px;border-radius:12px;background:#FFF0EE;display:flex;align-items:center;justify-content:center;font-size:1.3rem;flex-shrink:0">
                        {{ $icon }}
                    </div>
                    <div>
                        <div class="fw-700 mb-1">{{ $title }}</div>
                        <div class="text-muted small" style="line-height:1.6">{{ $desc }}</div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Kontak --}}
    <div class="row g-4 align-items-stretch">
        <div class="col-md-6">
            <div class="card h-100 p-4">
                <h5 class="fw-800 mb-4">📞 Hubungi Kami</h5>
                <div class="contact-item">
                    <div class="contact-icon" style="background:#E8F9EE"><i class="bi bi-whatsapp text-success fs-5"></i></div>
                    <div>
                        <div class="fw-700 small">WhatsApp</div>
                        <div class="text-muted">+62 812-3456-7890</div>
                    </div>
                </div>
                <div class="contact-item">
                    <div class="contact-icon" style="background:#FFF0EE"><i class="bi bi-envelope text-danger fs-5"></i></div>
                    <div>
                        <div class="fw-700 small">Email</div>
                        <div class="text-muted">info@cateringfamilyjakarta.com</div>
                    </div>
                </div>
                <div class="contact-item">
                    <div class="contact-icon" style="background:#FFF0EE"><i class="bi bi-geo-alt text-danger fs-5"></i></div>
                    <div>
                        <div class="fw-700 small">Lokasi</div>
                        <div class="text-muted">Jakarta, Indonesia</div>
                    </div>
                </div>
                <div class="contact-item">
                    <div class="contact-icon" style="background:#F0F4FF"><i class="bi bi-clock text-primary fs-5"></i></div>
                    <div>
                        <div class="fw-700 small">Jam Operasional</div>
                        <div class="text-muted">Sen–Jum 08.00–17.00 · Sab 08.00–15.00</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card h-100 p-4 d-flex flex-column justify-content-center text-center"
                 style="background:linear-gradient(135deg,#C0392B,#E67E22)">
                <div style="font-size:3rem; margin-bottom:16px">🍽️</div>
                <h4 class="fw-800 text-white mb-3">Siap Memesan?</h4>
                <p class="text-white mb-4" style="opacity:.9; line-height:1.7">
                    Jangan tunda lagi! Pesan sekarang dan buat acara Anda semakin berkesan.
                </p>
                <a href="{{ route('customer.menu') }}"
                   class="btn btn-light rounded-pill px-5 fw-700 text-danger mx-auto">
                    <i class="bi bi-cart-plus me-2"></i>Pesan Sekarang
                </a>
            </div>
        </div>
    </div>

</div>
@endsection

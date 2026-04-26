@extends('layouts.app')
@section('title', 'Pembayaran — ' . $order->order_number)

@push('styles')
<style>
    .payment-step { display:flex; align-items:flex-start; gap:14px; padding:14px 0; }
    .payment-step:not(:last-child) { border-bottom:1px solid #f5efe9; }
    .step-num {
        width:34px; height:34px; border-radius:50%; flex-shrink:0;
        display:flex; align-items:center; justify-content:center;
        background:linear-gradient(135deg,var(--cfj-primary),var(--cfj-secondary));
        color:#fff; font-weight:800; font-size:.85rem;
    }
    .upload-zone {
        border:2px dashed #ddd; border-radius:14px; padding:32px;
        text-align:center; cursor:pointer; transition:all .2s;
        background:#fafafa;
    }
    .upload-zone:hover { border-color:var(--cfj-primary); background:#FFF9F5; }
    .upload-zone.has-file { border-color:var(--cfj-primary); background:#FFF9F5; }
</style>
@endpush

@section('content')
<div class="container py-5" style="max-width:700px">

    {{-- Header --}}
    <div class="text-center mb-5">
        <div style="font-size:3rem; filter:drop-shadow(0 4px 12px rgba(192,57,43,.2))">💳</div>
        <h4 class="fw-800 mt-3 mb-1">Selesaikan Pembayaran</h4>
        <p class="text-muted">
            Pesanan <strong class="text-danger">{{ $order->order_number }}</strong>
        </p>
    </div>

    {{-- SUDAH LUNAS --}}
    @if($order->payment && $order->payment->status === 'paid')
    <div class="card text-center p-5">
        <div style="font-size:3.5rem; margin-bottom:16px">✅</div>
        <h5 class="fw-800 mb-2">Pembayaran Lunas!</h5>
        <p class="text-muted mb-4" style="line-height:1.7">
            Terima kasih! Pesanan Anda sedang diproses oleh tim kami.<br>
            Kami akan menghubungi Anda jika ada pembaruan.
        </p>
        <div class="d-flex gap-2 justify-content-center">
            <a href="{{ route('customer.track') }}?kode={{ $order->order_number }}"
               class="btn btn-danger rounded-pill px-4 fw-700">
                <i class="bi bi-search me-2"></i>Lacak Pesanan
            </a>
        </div>
    </div>

    {{-- MENUNGGU VERIFIKASI --}}
    @elseif($order->payment && $order->payment->status === 'pending_verification')
    <div class="card text-center p-5">
        <div style="font-size:3.5rem; margin-bottom:16px">⏳</div>
        <h5 class="fw-800 mb-2">Bukti Sedang Diverifikasi</h5>
        <p class="text-muted mb-4" style="line-height:1.7">
            Tim kami akan memverifikasi pembayaran Anda dalam <strong>1×24 jam</strong>.<br>
            Pantau status pesanan secara berkala.
        </p>
        <a href="{{ route('customer.track') }}?kode={{ $order->order_number }}"
           class="btn btn-outline-danger rounded-pill px-4 fw-700 mx-auto">
            <i class="bi bi-search me-2"></i>Lacak Status Pesanan
        </a>
    </div>

    {{-- BELUM BAYAR --}}
    @else

    {{-- Tagihan --}}
    <div class="card mb-4 text-white text-center"
         style="background:linear-gradient(135deg,var(--cfj-primary),var(--cfj-secondary))">
        <div class="card-body py-4">
            <div class="small opacity-80 mb-1">Total yang harus dibayar</div>
            <div class="fw-800 mb-1" style="font-size:2rem">
                Rp {{ number_format($order->total_price, 0, ',', '.') }}
            </div>
            <div class="small opacity-75">Termasuk semua biaya layanan & ongkir</div>
        </div>
    </div>

    {{-- QRIS --}}
    <div class="card mb-4">
        <div class="card-header text-center fw-700 py-3">
            <i class="bi bi-qr-code me-2 text-danger"></i>Scan QRIS untuk Membayar
        </div>
        <div class="card-body text-center py-4">
            @if(file_exists(public_path('images/qris.png')))
                <img src="{{ asset('images/qris.png') }}" alt="QRIS"
                     class="img-fluid rounded-3 shadow-sm" style="max-width:260px">
            @else
                <div class="d-flex align-items-center justify-content-center rounded-3 mx-auto"
                     style="width:260px;height:260px;background:#f8f9fa;border:2px dashed #dee2e6">
                    <div class="text-muted text-center">
                        <div style="font-size:3.5rem">📱</div>
                        <div class="small mt-2 fw-600">Gambar QRIS<br>akan tampil di sini</div>
                        <div class="small text-danger mt-1">Simpan ke:<br><code>public/images/qris.png</code></div>
                    </div>
                </div>
            @endif
            <div class="mt-3">
                <span class="badge px-3 py-2 rounded-pill fw-600"
                      style="background:#E8F9EE; color:#155724; font-size:.8rem">
                    <i class="bi bi-shield-check me-1"></i>QRIS Resmi Catering Family Jakarta
                </span>
            </div>
        </div>
        <div class="card-footer bg-light py-3">
            <div class="row g-3 text-center">
                @foreach([['📱','Buka app e-wallet atau m-banking'],['📷','Scan QR code di atas'],['✅','Bayar & upload bukti di bawah']] as [$icon, $label])
                <div class="col-4">
                    <div style="font-size:1.4rem">{{ $icon }}</div>
                    <div class="small text-muted mt-1" style="font-size:.75rem; line-height:1.4">{{ $label }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Upload Bukti --}}
    <div class="card mb-4">
        <div class="card-header fw-700 py-3">
            <i class="bi bi-upload me-2 text-danger"></i>Upload Bukti Pembayaran
        </div>
        <div class="card-body">
            <form action="{{ route('customer.payment.upload', $order) }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- Preview --}}
                <div id="previewContainer" class="mb-3 text-center" style="display:none">
                    <img id="previewImg" src="" alt="Preview"
                         class="img-fluid rounded-3 shadow-sm" style="max-height:220px">
                </div>

                {{-- Upload Zone --}}
                <div class="upload-zone mb-3" id="uploadZone" onclick="document.getElementById('proofInput').click()">
                    <div id="uploadPrompt">
                        <div style="font-size:2.5rem; opacity:.4; margin-bottom:8px">🖼️</div>
                        <div class="fw-700 small mb-1">Klik untuk pilih foto</div>
                        <div class="text-muted" style="font-size:.78rem">JPG, PNG, JPEG — Maks. 2MB</div>
                    </div>
                    <div id="uploadSuccess" style="display:none">
                        <i class="bi bi-check-circle-fill text-success fs-3 mb-2 d-block"></i>
                        <div class="fw-700 small text-success" id="fileName"></div>
                    </div>
                </div>
                <input type="file" name="proof_image" id="proofInput"
                       class="@error('proof_image') is-invalid @enderror"
                       accept="image/*" required style="display:none">
                @error('proof_image')
                    <div class="text-danger small mb-2">{{ $message }}</div>
                @enderror

                <div class="mb-4">
                    <label class="form-label fw-700 small">Catatan (opsional)</label>
                    <input type="text" name="payment_notes" class="form-control rounded-3"
                           placeholder="Contoh: Transfer dari BCA atas nama Budi">
                </div>

                <button type="submit" class="btn btn-danger w-100 rounded-pill fw-700 py-2">
                    <i class="bi bi-send me-2"></i>Kirim Bukti Pembayaran
                </button>
            </form>
        </div>
    </div>

    <div class="alert alert-info rounded-3 small">
        <i class="bi bi-info-circle-fill me-2"></i>
        Pembayaran diverifikasi admin dalam <strong>1×24 jam</strong>.
        Pertanyaan? Hubungi WhatsApp <strong>+62 812-3456-7890</strong>.
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
document.getElementById('proofInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (!file) return;
    const zone = document.getElementById('uploadZone');
    zone.classList.add('has-file');
    document.getElementById('uploadPrompt').style.display = 'none';
    document.getElementById('uploadSuccess').style.display = 'block';
    document.getElementById('fileName').textContent = file.name;
    const reader = new FileReader();
    reader.onload = function(e) {
        document.getElementById('previewImg').src = e.target.result;
        document.getElementById('previewContainer').style.display = 'block';
    };
    reader.readAsDataURL(file);
});
</script>
@endpush

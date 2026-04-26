@extends('layouts.app')
@section('title', 'Checkout — Catering Family Jakarta')

@push('styles')
<style>
    .step-indicator { display:flex; align-items:center; gap:0; margin-bottom:32px; }
    .step-item { display:flex; flex-direction:column; align-items:center; flex:1; position:relative; }
    .step-item:not(:last-child)::after {
        content:''; position:absolute; top:16px; left:60%; width:80%; height:2px;
        background:#e9ecef; z-index:0;
    }
    .step-circle {
        width:32px; height:32px; border-radius:50%; display:flex; align-items:center; justify-content:center;
        font-size:.8rem; font-weight:800; z-index:1; border:2px solid #e9ecef; background:#fff; color:#aaa;
    }
    .step-circle.active { background:var(--cfj-primary); border-color:var(--cfj-primary); color:#fff; }
    .step-circle.done { background:#D4EDDA; border-color:#28a745; color:#155724; }
    .step-label { font-size:.72rem; font-weight:600; color:#aaa; margin-top:6px; text-align:center; }
    .step-label.active { color:var(--cfj-primary); }
</style>
@endpush

@section('content')
<div class="container py-5">
    <div class="section-title mb-1">Checkout <span>Pesanan</span></div>
    <div class="section-divider"></div>

    <form action="{{ route('customer.orders.store') }}" method="POST" id="checkoutForm">
        @csrf
        <div class="row g-4">

            {{-- KIRI --}}
            <div class="col-lg-7">

                {{-- Data Pemesan --}}
                <div class="card mb-4">
                    <div class="card-header d-flex align-items-center gap-2">
                        <span class="d-flex align-items-center justify-content-center rounded-circle text-white fw-800"
                              style="width:26px;height:26px;background:var(--cfj-primary);font-size:.75rem">1</span>
                        Data Pemesan
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-700 small">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" name="nama_pemesan"
                                       class="form-control rounded-3 @error('nama_pemesan') is-invalid @enderror"
                                       value="{{ old('nama_pemesan', auth()->user()->name ?? '') }}"
                                       placeholder="Masukkan nama lengkap" required>
                                @error('nama_pemesan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-700 small">No. HP / WhatsApp <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-whatsapp text-success"></i></span>
                                    <input type="text" name="no_hp"
                                           class="form-control rounded-end-3 @error('no_hp') is-invalid @enderror"
                                           value="{{ old('no_hp', auth()->user()->phone ?? '') }}"
                                           placeholder="08123456789" required>
                                    @error('no_hp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Detail Acara --}}
                <div class="card mb-4">
                    <div class="card-header d-flex align-items-center gap-2">
                        <span class="d-flex align-items-center justify-content-center rounded-circle text-white fw-800"
                              style="width:26px;height:26px;background:var(--cfj-primary);font-size:.75rem">2</span>
                        Detail Acara
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-700 small">Tanggal Acara <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-calendar3 text-danger"></i></span>
                                    <input type="date" name="event_date"
                                           class="form-control rounded-end-3 @error('event_date') is-invalid @enderror"
                                           value="{{ old('event_date') }}"
                                           min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                                    @error('event_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-700 small">Kota / Area <span class="text-danger">*</span></label>
                                <select name="event_city" class="form-select rounded-3 @error('event_city') is-invalid @enderror" required>
                                    <option value="">— Pilih kota —</option>
                                    @foreach(['Jakarta Pusat','Jakarta Barat','Jakarta Timur','Jakarta Selatan','Jakarta Utara'] as $kota)
                                        <option value="{{ $kota }}" {{ old('event_city') == $kota ? 'selected' : '' }}>{{ $kota }}</option>
                                    @endforeach
                                </select>
                                @error('event_city')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-700 small">Alamat Lengkap <span class="text-danger">*</span></label>
                                <textarea name="event_address" rows="3"
                                          class="form-control rounded-3 @error('event_address') is-invalid @enderror"
                                          placeholder="Jalan, RT/RW, Kelurahan, Kecamatan…" required>{{ old('event_address') }}</textarea>
                                @error('event_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-700 small">Jarak dari Pusat (km) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" name="distance_km" id="distanceInput"
                                           class="form-control rounded-start-3 @error('distance_km') is-invalid @enderror"
                                           value="{{ old('distance_km', 0) }}" min="0" step="0.5" required>
                                    <span class="input-group-text bg-light">km</span>
                                    @error('distance_km')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="form-text small"><i class="bi bi-info-circle me-1 text-danger"></i>Gratis ongkir radius 5 km, setelah itu Rp 5.000/km</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-700 small">Catatan Tambahan</label>
                                <input type="text" name="notes" class="form-control rounded-3"
                                       placeholder="Alergi, permintaan khusus…" value="{{ old('notes') }}">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Custom Menu --}}
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-2">
                            <span class="d-flex align-items-center justify-content-center rounded-circle bg-secondary text-white fw-800"
                                  style="width:26px;height:26px;font-size:.75rem">3</span>
                            Custom Menu
                            <span class="badge bg-light text-muted border ms-1" style="font-size:.72rem">Opsional</span>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-3" id="addCustom">
                            <i class="bi bi-plus-lg me-1"></i>Tambah
                        </button>
                    </div>
                    <div class="card-body">
                        <p class="text-muted small mb-3" style="line-height:1.6">
                            <i class="bi bi-lightbulb me-1 text-warning"></i>
                            Punya permintaan menu khusus? Admin akan menghubungi Anda untuk konfirmasi harga.
                        </p>
                        <div id="customContainer"></div>
                    </div>
                </div>
            </div>

            {{-- KANAN — Ringkasan --}}
            <div class="col-lg-5">
                <div class="card sticky-top" style="top:80px">
                    <div class="card-header d-flex align-items-center gap-2">
                        <i class="bi bi-receipt text-danger"></i> Ringkasan Pesanan
                    </div>
                    <div class="card-body">
                        @php
                            $subtotal = collect($cart)->sum('subtotal');
                            $totalPax = collect($cart)->sum('pax');
                        @endphp

                        <div class="mb-3">
                            @foreach($cart as $item)
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted small text-truncate me-2" style="max-width:160px">
                                    {{ Str::limit($item['name'], 22) }} ×{{ $item['pax'] }} pax
                                </span>
                                <span class="small fw-600">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</span>
                            </div>
                            @endforeach
                        </div>
                        <hr class="my-3">

                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">Subtotal ({{ $totalPax }} pax)</span>
                            <span class="fw-700">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">Ongkos Kirim</span>
                            <span class="fw-700" id="deliveryFeeDisplay">Rp 0</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted small">Biaya Layanan</span>
                            <span class="fw-700">Rp {{ number_format($serviceFee, 0, ',', '.') }}</span>
                        </div>
                        <hr class="my-3">
                        <div class="d-flex justify-content-between fw-800 mb-1" style="font-size:1.05rem">
                            <span>Total</span>
                            <span class="text-danger" id="totalDisplay">
                                Rp {{ number_format($subtotal + $serviceFee, 0, ',', '.') }}
                            </span>
                        </div>
                        <p class="text-muted mb-4" style="font-size:.75rem; line-height:1.5">
                            * Belum termasuk custom menu (jika ada)
                        </p>

                        <button type="submit" class="btn btn-danger w-100 rounded-pill fw-700 py-2 mb-2">
                            <i class="bi bi-bag-check me-2"></i>Buat Pesanan
                        </button>
                        <a href="{{ route('customer.cart') }}"
                           class="btn btn-outline-secondary w-100 rounded-pill fw-600 py-2">
                            <i class="bi bi-arrow-left me-1"></i>Kembali ke Keranjang
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
const subtotal   = {{ collect($cart)->sum('subtotal') }};
const serviceFee = {{ $serviceFee }};
const feePerKm   = 5000;
const freeRadius = 5;

function calcDelivery(km) {
    return km <= freeRadius ? 0 : (km - freeRadius) * feePerKm;
}
function formatRp(n) {
    return 'Rp ' + Math.round(n).toLocaleString('id-ID');
}

document.getElementById('distanceInput').addEventListener('input', function() {
    const km  = parseFloat(this.value) || 0;
    const del = calcDelivery(km);
    document.getElementById('deliveryFeeDisplay').textContent = formatRp(del);
    document.getElementById('totalDisplay').textContent       = formatRp(subtotal + del + serviceFee);
});

let customCount = 0;
document.getElementById('addCustom').addEventListener('click', function() {
    const i   = customCount++;
    const div = document.createElement('div');
    div.className = 'rounded-3 p-3 mb-3';
    div.style.cssText = 'background:#FFF9F5; border:1.5px solid #F5EBE4';
    div.innerHTML = `
        <div class="d-flex justify-content-between align-items-center mb-3">
            <span class="fw-700 small text-danger">Menu Custom #${i+1}</span>
            <button type="button" class="btn btn-sm text-muted border-0 bg-transparent p-0"
                onclick="this.closest('.rounded-3').remove()">
                <i class="bi bi-x-lg" style="font-size:.85rem"></i>
            </button>
        </div>
        <div class="row g-2">
            <div class="col-8">
                <input type="text" name="custom_menus[${i}][item_name]"
                       class="form-control form-control-sm rounded-3" placeholder="Nama menu" required>
            </div>
            <div class="col-4">
                <input type="number" name="custom_menus[${i}][pax]"
                       class="form-control form-control-sm rounded-3" placeholder="Pax" min="1" value="10" required>
            </div>
            <div class="col-12">
                <input type="text" name="custom_menus[${i}][description]"
                       class="form-control form-control-sm rounded-3"
                       placeholder="Deskripsi / keterangan (opsional)">
            </div>
        </div>`;
    document.getElementById('customContainer').appendChild(div);
});
</script>
@endpush
@endsection

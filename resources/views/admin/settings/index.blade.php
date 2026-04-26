@extends('layouts.admin')
@section('title', 'Pengaturan Harga')
@section('page-title', 'Pengaturan')

@section('content')
<div class="mb-4">
    <h5 class="fw-800 mb-0">Pengaturan Harga</h5>
    <p class="text-muted small mb-0">Konfigurasi biaya layanan dan ongkos kirim</p>
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header"><i class="bi bi-gear me-2 text-danger"></i>Konfigurasi Harga</div>
            <div class="card-body">
                @if(session('success'))
                <div class="alert alert-success rounded-3 py-2 mb-3">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                </div>
                @endif

                <form action="{{ route('admin.settings.update') }}" method="POST">
                    @csrf @method('PATCH')
                    @foreach($configs as $config)
                    <div class="mb-4">
                        <label class="form-label fw-700">{{ $config->label }}</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">Rp</span>
                            <input type="number"
                                   name="configs[{{ $config->key }}]"
                                   class="form-control rounded-end-3"
                                   value="{{ $config->value }}"
                                   min="0" step="1000" required>
                        </div>
                        <div class="text-muted small mt-1">
                            @if($config->key === 'service_fee')
                                Biaya tetap per transaksi pemesanan
                            @elseif($config->key === 'delivery_fee_per_km')
                                Dikalikan dengan jarak (km) yang diisi pelanggan saat checkout
                            @elseif($config->key === 'min_order_pax')
                                Minimum pax untuk kategori Nasi Box
                            @endif
                        </div>
                    </div>
                    @endforeach

                    <button type="submit" class="btn btn-danger rounded-pill px-5 fw-700">
                        <i class="bi bi-save me-2"></i>Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card">
            <div class="card-header"><i class="bi bi-calculator me-2 text-danger"></i>Simulasi Perhitungan</div>
            <div class="card-body">
                <p class="text-muted small mb-3">Contoh perhitungan harga pesanan:</p>

                @php
                    $serviceFee  = $configs->firstWhere('key', 'service_fee')?->value ?? 50000;
                    $deliveryFee = $configs->firstWhere('key', 'delivery_fee_per_km')?->value ?? 5000;
                @endphp

                <div class="p-3 rounded-3 mb-3" style="background:#FFF5F5; border:1px solid #FECACA">
                    <div class="fw-700 small text-danger mb-3">Contoh: Nasi Box 50 pax, jarak 10 km</div>
                    @foreach([
                        ['Subtotal Menu (Rp 35.000 × 50)', 'Rp ' . number_format(35000 * 50, 0, ',', '.')],
                        ['Ongkos Kirim (10 km × Rp ' . number_format($deliveryFee, 0, ',', '.') . ')', 'Rp ' . number_format($deliveryFee * 10, 0, ',', '.')],
                        ['Biaya Layanan', 'Rp ' . number_format($serviceFee, 0, ',', '.')],
                    ] as [$label, $val])
                    <div class="d-flex justify-content-between text-muted small mb-2">
                        <span>{{ $label }}</span><span>{{ $val }}</span>
                    </div>
                    @endforeach
                    <hr class="my-2">
                    <div class="d-flex justify-content-between fw-800">
                        <span>TOTAL</span>
                        <span class="text-danger">Rp {{ number_format(35000 * 50 + $deliveryFee * 10 + $serviceFee, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="p-3 rounded-3" style="background:#F0FDF4; border:1px solid #BBF7D0">
                    <div class="fw-700 small text-success mb-2"><i class="bi bi-lightbulb me-1"></i>Tips Penetapan Harga</div>
                    <ul class="text-muted small mb-0 ps-3">
                        <li class="mb-1">Biaya layanan disarankan Rp 25.000–Rp 75.000</li>
                        <li class="mb-1">Ongkos kirim per KM disarankan Rp 3.000–Rp 8.000</li>
                        <li>Minimum pax biasanya 10–20 pax untuk efisiensi</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

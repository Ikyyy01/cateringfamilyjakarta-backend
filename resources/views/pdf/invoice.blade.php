<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $order->order_number }} - Catering Family Jakarta</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; font-size: 14px; color: #333; background: #fff; }
        .invoice { max-width: 800px; margin: 0 auto; padding: 30px; }
        .header { display: flex; justify-content: space-between; align-items: start; margin-bottom: 30px; border-bottom: 3px solid #e74c3c; padding-bottom: 20px; }
        .company h1 { color: #e74c3c; font-size: 24px; margin-bottom: 5px; }
        .company p { color: #666; font-size: 12px; }
        .invoice-info { text-align: right; }
        .invoice-info h2 { color: #e74c3c; font-size: 28px; margin-bottom: 10px; }
        .invoice-info p { font-size: 13px; color: #666; }
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 25px; }
        .info-box { background: #f8f9fa; padding: 15px; border-radius: 8px; }
        .info-box h3 { color: #e74c3c; font-size: 14px; margin-bottom: 8px; text-transform: uppercase; }
        .info-box p { font-size: 13px; margin-bottom: 3px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        thead { background: #e74c3c; color: white; }
        th { padding: 10px 12px; text-align: left; font-size: 13px; }
        td { padding: 10px 12px; border-bottom: 1px solid #eee; font-size: 13px; }
        tr:nth-child(even) { background: #f8f9fa; }
        .text-right { text-align: right; }
        .totals { width: 350px; margin-left: auto; }
        .totals table { margin-bottom: 0; }
        .totals td { border-bottom: 1px solid #ddd; }
        .totals tr:last-child td { font-weight: bold; font-size: 16px; color: #e74c3c; border-bottom: none; border-top: 2px solid #e74c3c; }
        .footer { margin-top: 40px; padding-top: 20px; border-top: 1px solid #eee; text-align: center; color: #999; font-size: 12px; }
        .status-badge { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; }
        .status-pending { background: #fff3cd; color: #856404; }
        .status-confirmed { background: #d1ecf1; color: #0c5460; }
        .status-processing { background: #cce5ff; color: #004085; }
        .status-delivered { background: #e2e3e5; color: #383d41; }
        .status-completed { background: #d4edda; color: #155724; }
        .status-cancelled { background: #f8d7da; color: #721c24; }
        @media print {
            body { background: white; }
            .no-print { display: none; }
            .invoice { padding: 0; }
        }
    </style>
</head>
<body>
    <div class="invoice">
        {{-- Print button --}}
        <div class="no-print" style="text-align: right; margin-bottom: 15px;">
            <button onclick="window.print()" style="background: #e74c3c; color: white; border: none; padding: 10px 25px; border-radius: 5px; cursor: pointer; font-size: 14px;">
                🖨️ Cetak / Simpan PDF
            </button>
            <a href="{{ url()->previous() }}" style="margin-left: 10px; color: #666; text-decoration: none;">← Kembali</a>
        </div>

        {{-- Header --}}
        <div class="header">
            <div class="company">
                <h1>🍱 Catering Family Jakarta</h1>
                <p>Jl. Contoh Alamat No. 123, Jakarta Timur</p>
                <p>📞 0812-3456-7890 | ✉️ info@cateringfamilyjakarta.com</p>
            </div>
            <div class="invoice-info">
                <h2>INVOICE</h2>
                <p><strong>{{ $order->order_number }}</strong></p>
                <p>{{ $order->created_at->format('d M Y') }}</p>
                <p><span class="status-badge status-{{ $order->status }}">{{ $order->status_label }}</span></p>
            </div>
        </div>

        {{-- Info Grid --}}
        <div class="info-grid">
            <div class="info-box">
                <h3>Pemesan</h3>
                <p><strong>{{ $order->nama_pemesan_display }}</strong></p>
                <p>📞 {{ $order->no_hp ?? $order->user->phone ?? '-' }}</p>
                <p>✉️ {{ $order->user->email ?? '-' }}</p>
            </div>
            <div class="info-box">
                <h3>Detail Acara</h3>
                <p>📅 {{ $order->event_date->format('d M Y') }}</p>
                <p>📍 {{ $order->event_address }}</p>
                <p>🏙️ {{ $order->event_city }} ({{ $order->distance_km }} km)</p>
            </div>
        </div>

        {{-- Items Table --}}
        <table>
            <thead>
                <tr>
                    <th style="width: 40px">No</th>
                    <th>Menu</th>
                    <th class="text-right" style="width: 100px">Harga/pax</th>
                    <th class="text-right" style="width: 80px">Pax</th>
                    <th class="text-right" style="width: 130px">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->orderItems as $i => $item)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $item->menu_name }}</td>
                    <td class="text-right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                    <td class="text-right">{{ $item->pax }}</td>
                    <td class="text-right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach

                @foreach($order->customMenus->where('status', 'approved') as $custom)
                <tr>
                    <td>{{ $loop->iteration + $order->orderItems->count() }}</td>
                    <td>{{ $custom->item_name }} <em>(Custom)</em></td>
                    <td class="text-right">Rp {{ number_format($custom->estimated_price, 0, ',', '.') }}</td>
                    <td class="text-right">{{ $custom->pax }}</td>
                    <td class="text-right">Rp {{ number_format($custom->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Totals --}}
        <div class="totals">
            <table>
                <tr>
                    <td>Subtotal</td>
                    <td class="text-right">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Ongkos Kirim</td>
                    <td class="text-right">Rp {{ number_format($order->delivery_fee, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Biaya Layanan</td>
                    <td class="text-right">Rp {{ number_format($order->service_fee, 0, ',', '.') }}</td>
                </tr>
                @if($order->discount > 0)
                <tr>
                    <td>Diskon</td>
                    <td class="text-right" style="color: #27ae60;">- Rp {{ number_format($order->discount, 0, ',', '.') }}</td>
                </tr>
                @endif
                <tr>
                    <td>TOTAL</td>
                    <td class="text-right">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>

        {{-- Payment Info --}}
        @if($order->payment)
        <div class="info-box" style="margin-top: 20px;">
            <h3>Info Pembayaran</h3>
            <p>Metode: {{ $order->payment->method_label }}</p>
            <p>Status: {{ $order->payment->status_label }}</p>
            @if($order->payment->paid_at)
            <p>Dibayar: {{ $order->payment->paid_at->format('d M Y H:i') }}</p>
            @endif
        </div>
        @endif

        {{-- Notes --}}
        @if($order->notes)
        <div class="info-box" style="margin-top: 15px;">
            <h3>Catatan</h3>
            <p>{{ $order->notes }}</p>
        </div>
        @endif

        {{-- Footer --}}
        <div class="footer">
            <p>Terima kasih telah mempercayakan Catering Family Jakarta untuk acara Anda!</p>
            <p>Invoice ini dihasilkan secara otomatis dan sah tanpa tanda tangan.</p>
        </div>
    </div>

@if(!empty($autoPrint))
<script>
    window.addEventListener('load', function() {
        setTimeout(function() { window.print(); }, 500);
    });
</script>
@endif
</body>
</html>

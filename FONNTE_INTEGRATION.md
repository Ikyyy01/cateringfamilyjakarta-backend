# WhatsApp API Fonnte Integration

Integrasi WhatsApp API Fonnte untuk mengirim notifikasi otomatis ke customer saat order dibuat dan status berubah.

## Setup

### 1. Daftar Fonnte
- Buka https://fonnte.com
- Scan QR dengan WhatsApp Business atau WhatsApp personal
- Copy token dari dashboard

### 2. Konfigurasi Environment
Edit `.env`:
```
FONNTE_TOKEN=9ywWMAHQSujRvJHyKj2V
FONNTE_URL=https://api.fonnte.com/send
```

## Fitur

### Order Dibuat
Ketika customer membuat pesanan, mereka otomatis dapat pesan WhatsApp:
```
Halo [Nama],
Pesanan Anda berhasil dibuat.
No. lacak: CFJ-20260627-0001
Status: Menunggu Konfirmasi
Total: Rp 150.000
Tanggal acara: 27-06-2026
Silakan simpan nomor lacak ini untuk cek status pesanan.
```

### Status Pesanan Berubah
Ketika admin mengubah status order, customer dapat pesan:
```
Halo [Nama],
Status pesanan Anda telah diperbarui.
No. lacak: CFJ-20260627-0001
Status terbaru: Diproses
Silakan gunakan nomor lacak ini untuk cek status pesanan.
```

## Implementasi

### Service: `app/Services/FonnteService.php`
- `sendOrderCreatedMessage()`: Kirim saat order dibuat
- `sendOrderStatusMessage()`: Kirim saat status berubah
- `sendMessage()`: Generic send (dengan normalisasi nomor)
- `normalizeTarget()`: Otomatis ubah format nomor WA ke internasional

### Listeners
- `app/Listeners/SendOrderCreatedNotifications.php`: Integrasi saat `OrderCreated` event
- `app/Listeners/SendOrderStatusNotification.php`: Integrasi saat `OrderStatusChanged` event

### Config
- `config/services.php`: Penyimpanan token dan URL endpoint

## Catatan

- Nomor HP otomatis dinormalisasi ke format internasional (62...)
- Pengiriman non-blocking — tidak menghentikan alur order
- Error handling dengan logging — jika gagal tidak merusak order
- Kompatibel dengan sistem notifikasi email/database existing

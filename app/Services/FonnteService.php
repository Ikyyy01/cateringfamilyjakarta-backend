<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FonnteService
{
    public function sendOrderCreatedMessage(Order $order): void
    {
        $message = implode("\n", [
            'Halo ' . $order->nama_pemesan_display . ',',
            'Pesanan Anda berhasil dibuat.',
            'No. lacak: ' . $order->order_number,
            'Status: ' . $order->status_label,
            'Total: ' . $order->formatted_total,
            'Tanggal acara: ' . $order->event_date?->format('d-m-Y'),
            'Silakan simpan nomor lacak ini untuk cek status pesanan.',
        ]);

        $this->sendMessage($order->no_hp, $message, [
            'order_id' => $order->id,
            'type' => 'order_created',
        ]);
    }

    public function sendOrderStatusMessage(Order $order): void
    {
        $message = implode("\n", [
            'Halo ' . $order->nama_pemesan_display . ',',
            'Status pesanan Anda telah diperbarui.',
            'No. lacak: ' . $order->order_number,
            'Status terbaru: ' . $order->status_label,
            'Silakan gunakan nomor lacak ini untuk cek status pesanan.',
        ]);

        $this->sendMessage($order->no_hp, $message, [
            'order_id' => $order->id,
            'type' => 'order_status_changed',
            'status' => $order->status,
        ]);
    }

    public function sendMessage(?string $target, string $message, array $context = []): void
    {
        $token = (string) config('services.fonnte.token');
        $url = (string) config('services.fonnte.url');
        $target = $this->normalizeTarget($target);

        if ($token === '' || $target === null) {
            return;
        }

        try {
            $response = Http::asForm()
                ->withHeaders([
                    'Authorization' => $token,
                ])
                ->timeout(15)
                ->post($url, [
                    'target' => $target,
                    'message' => $message,
                    'countryCode' => '62',
                ]);

            if ($response->failed()) {
                Log::warning('Gagal mengirim WhatsApp via Fonnte.', [
                    ...$context,
                    'target' => $target,
                    'response_status' => $response->status(),
                    'response_body' => $response->body(),
                ]);
            }
        } catch (\Throwable $e) {
            Log::warning('Error saat mengirim WhatsApp via Fonnte.', [
                ...$context,
                'target' => $target,
                'error' => $e->getMessage(),
            ]);
        }
    }

    private function normalizeTarget(?string $target): ?string
    {
        if (!$target) {
            return null;
        }

        $normalized = preg_replace('/\D+/', '', $target);

        if (!$normalized) {
            return null;
        }

        if (str_starts_with($normalized, '0')) {
            $normalized = '62' . substr($normalized, 1);
        }

        if (str_starts_with($normalized, '8')) {
            $normalized = '62' . $normalized;
        }

        return $normalized;
    }
}

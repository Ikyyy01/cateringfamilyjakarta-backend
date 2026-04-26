<?php

namespace App\Console\Commands;

use App\Models\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class AutoCancelUnpaidOrders extends Command
{
    protected $signature   = 'orders:auto-cancel {--dry-run : Preview saja tanpa mengubah data}';
    protected $description = 'Batalkan pesanan yang belum dibayar lebih dari 24 jam';

    public function handle(): int
    {
        $cutoff = Carbon::now()->subHours(24);

        // Pesanan pending, dibuat > 24 jam lalu,
        // tidak punya payment berstatus paid atau pending_verification
        $orders = Order::where('status', 'pending')
            ->where('created_at', '<', $cutoff)
            ->whereDoesntHave('payment', fn($q) =>
                $q->whereIn('status', ['paid', 'pending_verification'])
            )
            ->get();

        if ($orders->isEmpty()) {
            $this->info('Tidak ada pesanan yang perlu dibatalkan.');
            return self::SUCCESS;
        }

        $isDryRun = $this->option('dry-run');

        $this->info(($isDryRun ? '[DRY RUN] ' : '') .
            "Ditemukan {$orders->count()} pesanan yang akan dibatalkan:");

        foreach ($orders as $order) {
            $this->line("  • #{$order->order_number} — dibuat {$order->created_at->diffForHumans()}");

            if (!$isDryRun) {
                $order->update([
                    'status'           => 'cancelled',
                    'cancelled_reason' => 'Otomatis dibatalkan karena tidak ada pembayaran dalam 24 jam.',
                ]);
            }
        }

        if (!$isDryRun) {
            $this->info("✅ {$orders->count()} pesanan berhasil dibatalkan.");
        }

        return self::SUCCESS;
    }
}

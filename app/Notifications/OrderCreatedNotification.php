<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Order $order
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        // Gunakan URL frontend Vue (SPA), bukan Laravel web route
        $frontendUrl = rtrim(env('FRONTEND_URL', 'http://localhost:5173'), '/');
        $orderUrl    = $frontendUrl . '/customer/orders/' . $this->order->id;

        return (new MailMessage)
            ->subject('Pesanan Baru #' . $this->order->order_number)
            ->greeting('Halo ' . $notifiable->name . '!')
            ->line('Pesanan Anda berhasil dibuat.')
            ->line('Nomor Pesanan: **' . $this->order->order_number . '**')
            ->line('Tanggal Acara: ' . $this->order->event_date->format('d M Y'))
            ->line('Total: ' . $this->order->formatted_total)
            ->action('Lihat Pesanan', $orderUrl)
            ->line('Silakan lakukan pembayaran untuk mengonfirmasi pesanan Anda.')
            ->salutation('Terima kasih, Catering Family Jakarta');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'order_id'     => $this->order->id,
            'order_number' => $this->order->order_number,
            'message'      => 'Pesanan baru #' . $this->order->order_number . ' berhasil dibuat.',
            'total'        => $this->order->total_price,
        ];
    }
}

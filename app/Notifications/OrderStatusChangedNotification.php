<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderStatusChangedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Order $order,
        public string $oldStatus,
        public string $newStatus
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $statusLabel = $this->order->status_label;

        return (new MailMessage)
            ->subject('Update Pesanan #' . $this->order->order_number . ' - ' . $statusLabel)
            ->greeting('Halo ' . $notifiable->name . '!')
            ->line('Status pesanan Anda telah diperbarui.')
            ->line('Nomor Pesanan: **' . $this->order->order_number . '**')
            ->line('Status Baru: **' . $statusLabel . '**')
            ->action('Lihat Pesanan', route('customer.orders.show', $this->order))
            ->salutation('Terima kasih, Catering Family Jakarta');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'order_id'     => $this->order->id,
            'order_number' => $this->order->order_number,
            'old_status'   => $this->oldStatus,
            'new_status'   => $this->newStatus,
            'message'      => 'Pesanan #' . $this->order->order_number . ' - Status: ' . $this->order->status_label,
        ];
    }
}

<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewOrderAdminNotification extends Notification implements ShouldQueue
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
        return (new MailMessage)
            ->subject('🔔 Pesanan Baru #' . $this->order->order_number)
            ->greeting('Halo Admin!')
            ->line('Ada pesanan baru yang masuk.')
            ->line('Nomor Pesanan: **' . $this->order->order_number . '**')
            ->line('Pemesan: ' . ($this->order->user->name ?? 'Guest'))
            ->line('Tanggal Acara: ' . $this->order->event_date->format('d M Y'))
            ->line('Total: ' . $this->order->formatted_total)
            ->action('Lihat di Admin Panel', route('admin.orders.show', $this->order))
            ->salutation('Catering Family Jakarta System');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'order_id'     => $this->order->id,
            'order_number' => $this->order->order_number,
            'message'      => 'Pesanan baru dari ' . ($this->order->user->name ?? 'Guest') . ' - ' . $this->order->formatted_total,
        ];
    }
}

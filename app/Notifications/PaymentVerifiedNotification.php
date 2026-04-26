<?php

namespace App\Notifications;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentVerifiedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Payment $payment
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $order = $this->payment->order;

        return (new MailMessage)
            ->subject('Pembayaran Diverifikasi - Pesanan #' . $order->order_number)
            ->greeting('Halo ' . $notifiable->name . '!')
            ->line('Pembayaran Anda telah **diverifikasi**.')
            ->line('Nomor Pesanan: **' . $order->order_number . '**')
            ->line('Jumlah: **' . $this->payment->formatted_amount . '**')
            ->action('Lihat Pesanan', route('customer.orders.show', $order))
            ->line('Pesanan Anda sedang diproses. Terima kasih!')
            ->salutation('Salam, Catering Family Jakarta');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'payment_id'   => $this->payment->id,
            'order_number' => $this->payment->order->order_number,
            'message'      => 'Pembayaran pesanan #' . $this->payment->order->order_number . ' telah diverifikasi.',
            'amount'       => $this->payment->amount,
        ];
    }
}

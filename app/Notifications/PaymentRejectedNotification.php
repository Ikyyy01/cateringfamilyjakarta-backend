<?php

namespace App\Notifications;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentRejectedNotification extends Notification implements ShouldQueue
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
            ->subject('Pembayaran Ditolak - Pesanan #' . $order->order_number)
            ->greeting('Halo ' . $notifiable->name . ',')
            ->line('Mohon maaf, pembayaran Anda untuk pesanan berikut **ditolak**.')
            ->line('Nomor Pesanan: **' . $order->order_number . '**')
            ->line('Silakan unggah ulang bukti pembayaran yang valid.')
            ->action('Unggah Ulang', route('customer.payment.show', $order))
            ->salutation('Salam, Catering Family Jakarta');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'payment_id'   => $this->payment->id,
            'order_number' => $this->payment->order->order_number,
            'message'      => 'Pembayaran pesanan #' . $this->payment->order->order_number . ' ditolak. Silakan unggah ulang.',
        ];
    }
}

<?php

namespace App\Notifications;

use App\Models\CustomMenu;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CustomMenuStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public CustomMenu $customMenu,
        public string $status // 'approved' or 'rejected'
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $order     = $this->customMenu->order;
        $isApproved = $this->status === 'approved';
        $subject   = $isApproved ? 'Custom Menu Disetujui' : 'Custom Menu Ditolak';

        $mail = (new MailMessage)
            ->subject($subject . ' - Pesanan #' . $order->order_number)
            ->greeting('Halo ' . $notifiable->name . '!')
            ->line('Custom menu **"' . $this->customMenu->item_name . '"** telah ' .
                ($isApproved ? '**disetujui**' : '**ditolak**') . '.');

        if ($isApproved && $this->customMenu->estimated_price) {
            $mail->line('Harga per porsi: Rp ' . number_format($this->customMenu->estimated_price, 0, ',', '.'));
        }

        if ($this->customMenu->admin_notes) {
            $mail->line('Catatan admin: ' . $this->customMenu->admin_notes);
        }

        return $mail
            ->action('Lihat Pesanan', route('customer.orders.show', $order))
            ->salutation('Salam, Catering Family Jakarta');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'custom_menu_id' => $this->customMenu->id,
            'order_number'   => $this->customMenu->order->order_number,
            'item_name'      => $this->customMenu->item_name,
            'status'         => $this->status,
            'message'        => 'Custom menu "' . $this->customMenu->item_name . '" ' .
                ($this->status === 'approved' ? 'disetujui' : 'ditolak'),
        ];
    }
}

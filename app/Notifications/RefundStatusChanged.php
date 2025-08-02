<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Refund;

class RefundStatusChanged extends Notification
{
    use Queueable;

    protected $refund;

    public function __construct(Refund $refund)
    {
        $this->refund = $refund;
    }

    public function via($notifiable)
    {
        // Có thể qua email, lưu database, SMS, v.v.
        return ['mail',];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Cập nhật trạng thái hoàn tiền')
                    ->greeting('Xin chào ' . $notifiable->fullname . ',')
                    ->line('Yêu cầu hoàn tiền #' . $this->refund->id . ' của bạn đã được cập nhật trạng thái: ' . ucfirst($this->refund->status))
                    ->action('Xem chi tiết', url(route('refunds.show', $this->refund->id)))
                    ->line('Cảm ơn bạn đã sử dụng dịch vụ!');
    }

    public function toDatabase($notifiable)
    {
        return [
            'refund_id' => $this->refund->id,
            'order_id'  => $this->refund->order_id,
            'status'    => $this->refund->status,
            'message'   => 'Yêu cầu hoàn tiền của bạn đã chuyển sang trạng thái: ' . $this->refund->status,
        ];
    }
}

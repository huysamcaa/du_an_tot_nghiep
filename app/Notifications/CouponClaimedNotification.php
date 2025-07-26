<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CouponClaimedNotification extends Notification
{
    use Queueable;

    // Thông tin mã giảm giá
    protected $coupon;

    /**
     * Tạo một instance mới của thông báo.
     *
     * @param \App\Models\Coupon $coupon
     * @return void
     */
    public function __construct($coupon)
    {
        $this->coupon = $coupon;
    }

    /**
     * Chỉ định các kênh gửi thông báo.
     *
     * @param  mixed  $notifiable
     * @return array<int, string>
     */
    public function via($notifiable)
    {
        return ['mail', 'database']; // Gửi qua mail và lưu vào database
    }

    /**
     * Lấy thông báo qua mail.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Mã Giảm Giá Mới Được Nhận!')
            ->line('Bạn đã nhận mã giảm giá thành công: ' . $this->coupon->code)
            ->line('Tiêu đề: ' . $this->coupon->title)
            ->line('Giảm giá: ' . ($this->coupon->discount_type === 'percent' ? $this->coupon->discount_value . '%' : $this->coupon->discount_value . ' VND'))
            ->action('Sử dụng Mã Giảm Giá', url('/coupons/' . $this->coupon->id))
            ->line('Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi!');
    }

    /**
     * Lưu thông báo vào database.
     *
     * @param  mixed  $notifiable
     * @return array<string, mixed>
     */
    public function toDatabase($notifiable)
    {
        return [
            'coupon_id' => $this->coupon->id,
            'message' => '🎉 Bạn đã nhận mã giảm giá ' . $this->coupon->code . ' thành công! Giảm ngay ' . $this->coupon->discount_value . '% cho đơn hàng tiếp theo.',
        ];
    }
}

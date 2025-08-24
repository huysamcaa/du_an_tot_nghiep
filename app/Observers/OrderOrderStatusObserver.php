<?php

namespace App\Observers;

use App\Models\Admin\OrderOrderStatus;
use App\Models\Shared\Order;

class OrderOrderStatusObserver
{
    public function created(OrderOrderStatus $status): void
    {
        // 5 = Hoàn thành (tăng hạng), 6 = Đã hoàn tiền (có thể hạ hạng)
        if (in_array((int) $status->order_status_id, [5, 6], true)) {
            $order = Order::find($status->order_id);
            $order?->user?->refreshGroup();
        }
    }
}

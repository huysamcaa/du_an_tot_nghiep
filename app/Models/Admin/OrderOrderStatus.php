<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class OrderOrderStatus extends Model
{
    protected $table = 'order_order_status';
    protected $guarded = [];

    protected static function booted()
    {
        static::created(function ($orderStatus) {
            // Chỉ xử lý khi là trạng thái xác nhận (ID=2)
            if ($orderStatus->order_status_id == 2) {
                DB::transaction(function () use ($orderStatus) {
                    $order = $orderStatus->order()->with('items')->first();
                    
                    // Chỉ xử lý đơn hàng COD (payment_id=1)
                    if ($order && $order->payment_id == 1) {
                        // Kiểm tra xem đã từng có trạng thái xác nhận nào trước đó chưa
                        $hasPreviousConfirmation = self::where('order_id', $order->id)
                            ->where('order_status_id', 2)
                            ->where('id', '<', $orderStatus->id)
                            ->exists();
                        
                        // Nếu chưa có trạng thái xác nhận nào trước đó thì trừ stock
                        if (!$hasPreviousConfirmation) {
                            $orderStatus->reduceStockForOrder($order);
                        }
                    }
                });
            }
        });
    }

    public function order()
    {
        return $this->belongsTo(\App\Models\Shared\Order::class, 'order_id');
    }

    public function orderStatus()
    {
        return $this->belongsTo(OrderStatus::class, 'order_status_id');
    }

    /**
     * Trừ stock cho đơn hàng (cả sản phẩm và biến thể)
     */
    protected function reduceStockForOrder($order)
    {
        foreach ($order->items as $item) {
            try {
                // Trừ stock cho biến thể sản phẩm nếu có
                if ($item->product_variant_id) {
                    ProductVariant::where('id', $item->product_variant_id)
                        ->where('stock', '>=', $item->quantity)
                        ->decrement('stock', $item->quantity);
                } 
                // Hoặc trừ stock cho sản phẩm chính nếu không có biến thể
                else if ($item->product_id) {
                    Product::where('id', $item->product_id)
                        ->where('stock', '>=', $item->quantity)
                        ->decrement('stock', $item->quantity);
                }
            } catch (\Exception $e) {
                Log::error("Lỗi khi trừ stock cho đơn hàng {$order->id}: " . $e->getMessage());
                continue;
            }
        }
        
        // Ghi log trừ stock thành công
        Log::info("Đã trừ stock cho đơn hàng COD {$order->id} khi xác nhận");
    }
}
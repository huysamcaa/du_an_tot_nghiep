<?php
namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\Shared\Order;
use App\Models\Admin\OrderOrderStatus;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule)
    {
        // Xóa đơn hàng chưa thanh toán sau 10 phút
        $schedule->call(function () {
            $expiredOrders = Order::where('is_paid', 0)
                ->where('created_at', '<', now()->subMinutes(10))
                ->get();
            
            foreach ($expiredOrders as $order) {
                DB::transaction(function () use ($order) {
                    // Cập nhật trạng thái đơn hàng trước khi xóa
                    OrderOrderStatus::create([
                        'order_id' => $order->id,
                        'order_status_id' => 5, // Đã hủy
                        'modified_by' => 5, // System
                        'notes' => 'Tự động hủy do quá hạn thanh toán'
                    ]);
                    
                    // Xóa các order items trước
                    $order->items()->delete();
                    
                    // Sau đó xóa order
                    $order->delete();
                });
            }
        })->everyFiveMinutes()->name('clean_unpaid_orders')->withoutOverlapping();
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}
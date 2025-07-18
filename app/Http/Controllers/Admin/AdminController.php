<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Shared\Order;
use App\Models\Admin\Product;
use Illuminate\Support\Facades\DB;
use App\Models\Admin\OrderOrderStatus;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Lấy các order_id đã hoàn thành (Đã giao hàng, trạng thái hiện tại)
        $completedOrderIds = OrderOrderStatus::where('order_status_id', 5)
            ->where('is_current', 1)
            ->pluck('order_id');

        // Lấy doanh thu theo ngày
        $revenueByDay = Order::whereIn('id', $completedOrderIds)
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total_amount) as total'))
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get();

        // Các thống kê khác
        $revenue = Order::whereIn('id', $completedOrderIds)->sum('total_amount');
        $orderCount = Order::count();
        $productCount = \App\Models\Admin\Product::count();
        $userCount = \App\Models\User::count();

        return view('admin.dashboard', compact(
            'revenue',
            'orderCount',
            'productCount',
            'userCount',
            'revenueByDay'
        ));
    }
    public function manageUsers()
    {
        $users = User::all(); // Lấy tất cả user
        return view('admin.users', compact('users')); // Trang quản lý người dùng
    }
}

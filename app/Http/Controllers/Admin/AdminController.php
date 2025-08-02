<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Shared\Order;
use App\Models\Admin\Product;
use Illuminate\Support\Facades\DB;
use App\Models\Admin\OrderOrderStatus;
use App\Models\Admin\Comment;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        $year = $request->get('year', date('Y'));
        $month = $request->get('month', date('m'));
        $view = $request->get('view', 'month'); // month | week

        // Lấy các order_id đã hoàn thành
        $completedOrderIds = OrderOrderStatus::where('order_status_id', 5)
            ->where('is_current', 1)
            ->pluck('order_id');

        if ($view == 'month') {
            // Doanh thu theo tháng của năm
            $revenueByPeriod = collect(range(1, 12))->map(function ($m) use ($completedOrderIds, $year) {
                $total = Order::whereIn('id', $completedOrderIds)
                    ->whereYear('created_at', $year)
                    ->whereMonth('created_at', $m)
                    ->sum('total_amount');
                return [
                    'label' => "Tháng $m",
                    'total' => $total,
                ];
            });
        } elseif ($view === 'week') {
            // Doanh thu theo tuần trong 1 tháng
            $weeks = [
                [1, 7],
                [8, 14],
                [15, 21],
                [22, 31]
            ];
            $revenueByPeriod = collect($weeks)->map(function ($range, $i) use ($completedOrderIds, $year, $month) {
                $total = Order::whereIn('id', $completedOrderIds)
                    ->whereYear('created_at', $year)
                    ->whereMonth('created_at', $month)
                    ->whereDay('created_at', '>=', $range[0])
                    ->whereDay('created_at', '<=', $range[1])
                    ->sum('total_amount');
                return [
                    'label' => "Tuần " . ($i + 1),
                    'total' => $total,
                ];
            });
        } elseif ($view === 'day') {
            // Doanh thu theo ngày trong tháng
            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
            $revenueByPeriod = collect(range(1, $daysInMonth))->map(function ($day) use ($completedOrderIds, $year, $month) {
                $total = Order::whereIn('id', $completedOrderIds)
                    ->whereYear('created_at', $year)
                    ->whereMonth('created_at', $month)
                    ->whereDay('created_at', $day)
                    ->sum('total_amount');
                return [
                    'label' => "Ngày $day",
                    'total' => $total,
                ];
            });
        }


        // Danh sách năm
        $years = Order::select(DB::raw('YEAR(created_at) as year'))
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        // Danh sách tháng (1-12)
        $months = range(1, 12);

        // Các thống kê khác
        $revenue = Order::whereIn('id', $completedOrderIds)->sum('total_amount');
        $orderCount = Order::count();
        $productCount = Product::count();
        $userCount = User::count();
        $orderStatusStats = OrderOrderStatus::where('is_current', 1)
            ->join('order_statuses', 'order_order_status.order_status_id', '=', 'order_statuses.id')
            ->select('order_statuses.name', DB::raw('COUNT(*) as total')) // Sửa lại thành COUNT(*)
            ->groupBy('order_statuses.name')
            ->get();
        $topCustomers = Order::whereIn('id', $completedOrderIds)
            ->select('user_id', DB::raw('SUM(total_amount) as total'))
            ->groupBy('user_id')
            ->with('user')
            ->orderByDesc('total')
            ->limit(5)
            ->get();
        $topProductsByComments = Comment::select('product_id', DB::raw('COUNT(*) as total'))
            ->whereHas('product')
            ->groupBy('product_id')
            ->orderByDesc('total')
            ->with('product')
            ->limit(5)
            ->get();
        $topProductsByFavorites = DB::table('wishlists')
            ->join('products', 'wishlists.product_id', '=', 'products.id')
            ->select('products.id', 'products.name', DB::raw('COUNT(wishlists.id) as total'))
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total')
            ->limit(5)
            ->get();
        $topProductsBySales = DB::table('orders')
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->whereIn('orders.id', $completedOrderIds) // phải thêm "orders.id"
            ->select('products.id', 'products.name', DB::raw('SUM(order_items.quantity) as total'))
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'revenue',
            'orderCount',
            'productCount',
            'userCount',
            'revenueByPeriod',
            'orderStatusStats',
            'topCustomers',
            'topProductsByComments',
            'topProductsByFavorites',
            'topProductsBySales',
            'years',
            'months',
            'year',
            'month',
            'view'
        ));
    }

    public function manageUsers()
    {
        $users = User::all(); // Lấy tất cả user
        return view('admin.users', compact('users')); // Trang quản lý người dùng
    }
}

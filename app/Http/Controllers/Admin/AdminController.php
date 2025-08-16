<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Shared\Order;
use App\Models\Shared\OrderItem;
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
        $view = $request->get('view', 'month');
        $fromDate = $request->get('from_date');
        $toDate = $request->get('to_date');
        // Lấy các order_id đã hoàn thành
        $orderCompletedStatusId = DB::table('order_statuses')->where('name', 'Đã hoàn thành')->value('id');

        $completedOrderIds = DB::table('orders')
            ->join('order_order_status', function ($join) use ($orderCompletedStatusId) {
                $join->on('orders.id', '=', 'order_order_status.order_id')
                    ->where('order_order_status.order_status_id', $orderCompletedStatusId)
                    ->where('order_order_status.is_current', 1);
            })
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->select('orders.id')
            ->distinct()
            ->pluck('id');



        if ($fromDate && $toDate) {
            $revenueByPeriod = Order::whereIn('id', $completedOrderIds)
                ->whereDate('created_at', '>=', $fromDate)
                ->whereDate('created_at', '<=', $toDate)
                ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total_amount) as total'))
                ->groupBy('date')
                ->orderBy('date')
                ->get()
                ->map(function ($item) {
                    return [
                        'label' => $item->date,
                        'total' => $item->total
                    ];
                });
        } elseif ($view == 'month') {
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

        $revenueToday = Order::whereIn('id', $completedOrderIds)
            ->whereDate('created_at', now()->toDateString())
            ->sum('total_amount');
        $revenueYesterday = Order::whereIn('id', $completedOrderIds)
            ->whereDate('created_at', now()->subDay()->toDateString())
            ->sum('total_amount');
        $revenueMonth = Order::whereIn('id', $completedOrderIds)
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->sum('total_amount');
        // Danh sách năm
        $years = Order::select(DB::raw('YEAR(created_at) as year'))
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        // Danh sách tháng (1-12)
        $months = range(1, 12);

        // Các thống kê khác
        $revenue = Order::whereIn('id', $completedOrderIds)->sum('total_amount');
        $orderCount = Order::whereIn('id', $completedOrderIds)->count();
        $userCount = User::count();
        $orderStatusStats = OrderOrderStatus::where('order_order_status.is_current', 1)
            ->join('order_statuses', 'order_order_status.order_status_id', '=', 'order_statuses.id')
            ->join('orders', 'orders.id', '=', 'order_order_status.order_id')
            ->select('order_statuses.id', 'order_statuses.name', DB::raw('COUNT(*) as total'))
            ->groupBy('order_statuses.id', 'order_statuses.name')
            ->get();
        $topCustomers = Order::whereHas('user') // loại các đơn hàng có user đã bị xóa
            ->whereHas('orderOrderStatuses', function ($query) {
                $query->where('order_order_status.order_status_id', 5)
                    ->where('order_order_status.is_current', 1);
            })
            ->selectRaw('user_id, COUNT(*) as total_orders, SUM(total_amount) as total_amount')
            ->with('user')
            ->groupBy('user_id')
            ->orderByDesc('total_amount')
            ->limit(5)
            ->get()
            ->map(function ($order) {
                $lastOrder = Order::where('user_id', $order->user_id)
                    ->latest()
                    ->first();

                if ($lastOrder) {
                    $status = DB::table('order_order_status')
                        ->join('order_statuses', 'order_order_status.order_status_id', '=', 'order_statuses.id')
                        ->where('order_order_status.order_id', $lastOrder->id)
                        ->where('order_order_status.is_current', 1)
                        ->value('order_statuses.name');

                    $order->last_order_status = $status ?? null;
                } else {
                    $order->last_order_status = null;
                }

                return $order;
            });
        $topProductsBySales = DB::table('orders')
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->whereIn('orders.id', $completedOrderIds)
            ->select('products.id', 'products.name', 'products.thumbnail', DB::raw('SUM(order_items.quantity) as total'))
            ->groupBy('products.id', 'products.name', 'products.thumbnail')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $topProductsByFavorites = DB::table('wishlists')
            ->join('products', 'wishlists.product_id', '=', 'products.id')
            ->select('products.id', 'products.name', 'products.thumbnail', DB::raw('COUNT(wishlists.id) as total'))
            ->groupBy('products.id', 'products.name', 'products.thumbnail')
            ->orderByDesc('total')
            ->limit(5)
            ->get();
        // So sánh doanh thu hôm nay và hôm qua
        $revenueChange = $revenueYesterday > 0
            ? (($revenueToday - $revenueYesterday) / $revenueYesterday) * 100
            : ($revenueToday > 0 ? 100 : 0);

        // So sánh số đơn hàng hôm nay và hôm qua
        $orderToday = Order::whereIn('id', $completedOrderIds)
            ->whereDate('created_at', now()->toDateString())
            ->count();
        $orderYesterday = Order::whereIn('id', $completedOrderIds)
            ->whereDate('created_at', now()->subDay()->toDateString())
            ->count();
        $orderChange = $orderYesterday > 0
            ? (($orderToday - $orderYesterday) / $orderYesterday) * 100
            : ($orderToday > 0 ? 100 : 0);

        // So sánh sản phẩm tháng này và tháng trước
        $productThisMonth = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereIn('orders.id', $completedOrderIds)
            ->whereMonth('orders.created_at', date('m'))
            ->whereYear('orders.created_at', date('Y'))
            ->sum('order_items.quantity');

        $productLastMonth = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereIn('orders.id', $completedOrderIds)
            ->whereMonth('orders.created_at', date('m', strtotime('-1 month')))
            ->whereYear('orders.created_at', date('Y', strtotime('-1 month')))
            ->sum('order_items.quantity');

        // % thay đổi sản phẩm bán ra
        $productChange = $productLastMonth > 0
            ? (($productThisMonth - $productLastMonth) / $productLastMonth) * 100
            : ($productThisMonth > 0 ? 100 : 0);
        $productCount = OrderItem::whereIn('order_id', $completedOrderIds)
            ->distinct('order_id')  // đếm số order khác nhau
            ->count('order_id');


        // So sánh khách hàng tháng này và tháng trước
        $userLastMonth = User::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->count();
        $userThisMonth = User::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        $userChange = $userLastMonth > 0
            ? (($userThisMonth - $userLastMonth) / $userLastMonth) * 100
            : ($userThisMonth > 0 ? 100 : 0);

        return view('admin.dashboard', compact(
            'revenue',
            'orderCount',
            'userCount',
            'revenueByPeriod',
            'orderStatusStats',
            'topCustomers',
            'topProductsByFavorites',
            'topProductsBySales',
            'years',
            'months',
            'year',
            'month',
            'view',
            'fromDate',
            'toDate',
            'revenueToday',
            'revenueMonth',
            'revenueYesterday',
            'revenueChange',
            'orderChange',
            'productChange',
            'userChange',
            'productCount',
            'productChange'
        ));
    }

    public function manageUsers()
    {
        $users = User::all(); // Lấy tất cả user
        return view('admin.users', compact('users')); // Trang quản lý người dùng
    }
}

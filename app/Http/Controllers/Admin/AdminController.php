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
        $from = $fromDate ? \Carbon\Carbon::parse($fromDate) : null;
        $to   = $toDate   ? \Carbon\Carbon::parse($toDate)   : null;
        $diffInDays = $from && $to ? $from->diffInDays($to) : null;

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
            $diffInDays = $from->diffInDays($to);

            if ($diffInDays > 90) {
                // Gom theo tháng
                $revenueByPeriod = Order::whereIn('id', $completedOrderIds)
                    ->whereBetween('created_at', [$fromDate, $toDate])
                    ->select(DB::raw('DATE_FORMAT(created_at, "%Y-%m") as period'), DB::raw('SUM(total_amount) as total'))
                    ->groupBy('period')
                    ->orderBy('period')
                    ->get()
                    ->map(function ($item) {
                        return [
                            'label' => \Carbon\Carbon::createFromFormat('Y-m', $item->period)->format('m/Y'),
                            'total' => $item->total

                        ];
                    });
            } else {
                // Gom theo ngày
                $revenueByPeriod = Order::whereIn('id', $completedOrderIds)
                    ->whereBetween('created_at', [$fromDate, $toDate])
                    ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total_amount) as total'))
                    ->groupBy('date')
                    ->orderBy('date')
                    ->get()
                    ->map(function ($item) {
                        return [
                            'label' => \Carbon\Carbon::parse($item->date)->format('d/m/Y'), // ✅ đổi lại dd/mm/yyyy
                            'total' => $item->total
                        ];
                    });
            }
        } elseif ($view === 'month') {
            $revenueByPeriod = collect(range(1, 12))->map(function ($m) use ($completedOrderIds, $year) {
                $total = Order::whereIn('id', $completedOrderIds)
                    ->whereYear('created_at', $year)
                    ->whereMonth('created_at', $m)
                    ->sum('total_amount');
                return [
                    'label' => "Tháng $m/$year",
                    'total' => $total,
                ];
            });
        } elseif ($view === 'week') {
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
                    'label' => "Tuần " . ($i + 1) . "/$month/$year",
                    'total' => $total,
                ];
            });
        } elseif ($view === 'day') {
            $revenueByPeriod = Order::whereIn('id', $completedOrderIds)
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total_amount) as total'))
                ->groupBy('date')
                ->orderBy('date')
                ->get()
                ->map(function ($item) {
                    return [
                        'label' => \Carbon\Carbon::parse($item->date)->format('d/m/Y'),
                        'total' => $item->total,
                    ];
                });
        }

        $revenueToday = Order::whereIn('id', $completedOrderIds)
            ->whereDate('created_at', now()->toDateString())
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
        $baseOrders = Order::whereIn('id', $completedOrderIds);

        if ($fromDate && $toDate) {
            $baseOrders->whereBetween('created_at', [$fromDate, $toDate]);
        } else {
            $baseOrders->whereYear('created_at', $year)
                ->whereMonth('created_at', $month);
        }

        $revenue    = $baseOrders->sum('total_amount');
        $orderCount = $baseOrders->count();
        // Đếm user theo ngày đăng ký
        $userCount = User::when($fromDate && $toDate, function ($q) use ($fromDate, $toDate) {
            $q->whereBetween('created_at', [$fromDate, $toDate]);
        }, function ($q) use ($year, $month) {
            $q->whereYear('created_at', $year)
                ->whereMonth('created_at', $month);
        })
            ->count();

        // Đếm sản phẩm theo ngày tạo
        $productCount = Product::when($fromDate && $toDate, function ($q) use ($fromDate, $toDate) {
            $q->whereBetween('created_at', [$fromDate, $toDate]);
        }, function ($q) use ($year, $month) {
            $q->whereYear('created_at', $year)
                ->whereMonth('created_at', $month);
        })
            ->count();


        $orderStatusStats = OrderOrderStatus::where('order_order_status.is_current', 1)
            ->join('order_statuses', 'order_order_status.order_status_id', '=', 'order_statuses.id')
            ->join('orders', 'orders.id', '=', 'order_order_status.order_id');

        if ($fromDate && $toDate) {
            $orderStatusStats->whereBetween('orders.created_at', [$fromDate, $toDate]);
        } else {
            $orderStatusStats->whereYear('orders.created_at', $year)
                ->whereMonth('orders.created_at', $month);
        }

        $orderStatusStats = $orderStatusStats
            ->select('order_statuses.id', 'order_statuses.name', DB::raw('COUNT(*) as total'))
            ->groupBy('order_statuses.id', 'order_statuses.name')
            ->get();
        $customerOrders = Order::whereHas('orderOrderStatuses', function ($query) use ($orderCompletedStatusId) {
            $query->where('order_status_id', $orderCompletedStatusId)
                ->where('is_current', 1);
        })
            ->whereMonth('created_at', $month)
            ->select('user_id', DB::raw('COUNT(*) as total_orders'), DB::raw('SUM(total_amount) as total_amount'))
            ->with('user:id,name,avatar')
            ->groupBy('user_id')
            ->orderByDesc('total_amount')
            ->limit(5)
            ->get();

        // lấy danh sách user_id
        $userIds = $customerOrders->pluck('user_id');

        // lấy last status của mỗi user một lần duy nhất
        $lastStatuses = DB::table('orders as o')
            ->join('order_order_status as oos', 'o.id', '=', 'oos.order_id')
            ->join('order_statuses as os', 'oos.order_status_id', '=', 'os.id')
            ->select('o.user_id', 'os.name as status')
            ->whereIn('o.user_id', $userIds)
            ->where('oos.is_current', 1)
            ->whereIn('o.id', function ($q) {
                $q->select(DB::raw('MAX(id)'))
                    ->from('orders')
                    ->groupBy('user_id');
            })
            ->pluck('status', 'user_id');

        // gắn lại cho collection
        $topCustomers = $customerOrders->map(function ($order) use ($lastStatuses) {
            $order->last_order_status = $lastStatuses[$order->user_id] ?? null;
            return $order;
        });
        $topProductsBySales = DB::table('orders')
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->whereIn('orders.id', $completedOrderIds)
            ->whereMonth('orders.created_at', $month)
            ->select('products.id', 'products.name', 'products.thumbnail', DB::raw('SUM(order_items.quantity) as total'))
            ->groupBy('products.id', 'products.name', 'products.thumbnail')
            ->orderByDesc('total')
            ->limit(5)
            ->get();
        $topProductsByFavorites = DB::table('wishlists')
            ->join('products', 'wishlists.product_id', '=', 'products.id')
            ->whereMonth('wishlists.created_at', $month)
            ->select('products.id', 'products.name', 'products.thumbnail', DB::raw('COUNT(wishlists.id) as total'))
            ->groupBy('products.id', 'products.name', 'products.thumbnail')
            ->orderByDesc('total')
            ->limit(5)
            ->get();
        if ($request->ajax()) {

            return response()->json([
                'periodLabels' => $revenueByPeriod->pluck('label'),
                'periodData'   => $revenueByPeriod->pluck('total'),
                'statusLabels' => $orderStatusStats->pluck('name'),
                'statusData'   => $orderStatusStats->pluck('total'),
                'revenue'      => $revenue,
                'orderCount' => $orderCount,
                'userCount' => $userCount,
                'productCount' => $productCount,
                'topCustomers' => $topCustomers->map(function ($item) {
                    return [
                        'id' => $item->user->id ?? null,
                        'name' => $item->user->name ?? null,
                        'avatar' => $item->user->avatar ?? null,
                        'total_amount' => $item->total_amount,
                        'total_orders' => $item->total_orders,
                        'last_order_status' => $item->last_order_status ?? null,
                    ];
                }),
                'topProductsBySales' => $topProductsBySales,
                'topProductsByFavorites' => $topProductsByFavorites,
                'revenueToday' => $revenueToday,
            ]);
        }

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
            'productCount',

        ));
    }

    public function manageUsers()
    {
        $users = User::all(); // Lấy tất cả user
        return view('admin.users', compact('users')); // Trang quản lý người dùng
    }
}

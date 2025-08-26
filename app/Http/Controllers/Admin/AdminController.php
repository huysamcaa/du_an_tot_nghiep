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

use Carbon\Carbon;

class AdminController extends Controller
{
    private function applyDateFilter($query, $from, $to, $year, $month, $table = 'orders')
    {
        if ($from && $to) {
            return $query->whereBetween("$table.created_at", [$from, $to]);
        } elseif ($year) {
            $query->whereYear("$table.created_at", $year);
            if ($month) {
                $query->whereMonth("$table.created_at", $month);
            }
            return $query;
        } elseif ($month) {
            return $query->whereMonth("$table.created_at", $month);
        }

        return $query;
    }
    public function dashboard(Request $request)
    {
        $year = $request->get('year', date('Y'));
        $month = $request->get('month', date('m'));
        $view = $request->get('view', 'month');
        $fromDate = $request->get('from_date');
        $toDate = $request->get('to_date');
        $from = $fromDate ? Carbon::parse($fromDate)->startOfDay() : null;
        $to   = $toDate   ? Carbon::parse($toDate)->endOfDay()   : null;
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
            if ($diffInDays <= 30) {
                // Gom theo ngày
                $revenueByPeriod = Order::whereIn('id', $completedOrderIds)
                    ->whereBetween('created_at', [$from, $to])
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
            } elseif ($diffInDays <= 365) {
                // Gom theo tháng
                $revenueByPeriod = Order::whereIn('id', $completedOrderIds)
                    ->whereBetween('created_at', [$from, $to])
                    ->select(DB::raw('DATE_FORMAT(created_at, "%Y-%m") as period'), DB::raw('SUM(total_amount) as total'))
                    ->groupBy('period')
                    ->orderBy('period')
                    ->get()
                    ->map(function ($item) {
                        return [
                            'label' => \Carbon\Carbon::createFromFormat('Y-m', $item->period)->format('m/Y'),
                            'total' => $item->total,
                        ];
                    });
            } else {
                // Gom theo năm
                $revenueByPeriod = Order::whereIn('id', $completedOrderIds)
                    ->whereBetween('created_at', [$from, $to])
                    ->select(DB::raw('YEAR(created_at) as year'), DB::raw('SUM(total_amount) as total'))
                    ->groupBy('year')
                    ->orderBy('year')
                    ->get()
                    ->map(function ($item) {
                        return [
                            'label' => $item->year,
                            'total' => $item->total,
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
        $allTimeOrders = Order::whereIn('id', $completedOrderIds);
        if ($from && $to) {
            $allTimeOrders->whereBetween('created_at', [$from, $to]);
        }
        $revenue    = $allTimeOrders->sum('total_amount');
        $orderCount = $allTimeOrders->count();


        $userCount = User::when($from && $to, function ($q) use ($from, $to) {
            $q->whereBetween('created_at', [$from, $to]);
        })
            ->count();
        // Đếm sản phẩm theo ngày tạo
        $productCount = Product::when($from && $to, function ($q) use ($from, $to) {
            $q->whereBetween('created_at', [$from, $to]);
        })

            ->count();
        $orderStatusStats = OrderOrderStatus::where('order_order_status.is_current', 1)
            ->join('order_statuses', 'order_order_status.order_status_id', '=', 'order_statuses.id')
            ->join('orders', 'orders.id', '=', 'order_order_status.order_id');

        if ($from && $to) {
            $orderStatusStats->whereBetween('orders.created_at', [$from, $to]);
        } else {
            $orderStatusStats->whereYear('orders.created_at', $year)
                ->whereMonth('orders.created_at', $month);
        }
        $orderStatusStats = $orderStatusStats
            ->select('order_statuses.id', 'order_statuses.name', DB::raw('COUNT(*) as total'))
            ->groupBy('order_statuses.id', 'order_statuses.name')
            ->get();
        $customerOrders = Order::whereIn('id', $completedOrderIds)
            ->when($from && $to, function ($q) use ($from, $to) {
                $q->whereBetween('created_at', [$from, $to]);
            }, function ($q) use ($year, $month) {
                $q->whereYear('created_at', $year)
                    ->whereMonth('created_at', $month);
            })
            ->select('user_id', DB::raw('COUNT(*) as total_orders'), DB::raw('SUM(total_amount) as total_amount'))
            ->with('user:id,name,avatar')
            ->groupBy('user_id')
            ->orderByDesc('total_amount')
            ->limit(5)
            ->get();
        // Lấy last status cho top customers
        $userIds = $customerOrders->pluck('user_id');
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
        $topCustomers = $customerOrders
            ->filter(fn($order) => $order->user !== null) // loại bỏ user đã xóa
            ->map(function ($order) use ($lastStatuses) {
                $order->last_order_status = $lastStatuses[$order->user_id] ?? null;
                return $order;
            });
        // --- Top Products By Sales ---
        $topProductsBySales = DB::table('orders')
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->whereIn('orders.id', $completedOrderIds)
            ->select('products.id', 'products.name', 'products.thumbnail', DB::raw('SUM(order_items.quantity) as total'))
            ->groupBy('products.id', 'products.name', 'products.thumbnail')
            ->orderByDesc('total')
            ->limit(5);

        $topProductsBySales = $this->applyDateFilter($topProductsBySales, $fromDate, $toDate, $year, $month, 'orders')->get();

        // --- Top Products By Favorites ---
        $topProductsByFavorites = DB::table('wishlists')
            ->join('products', 'wishlists.product_id', '=', 'products.id')
            ->select('products.id', 'products.name', 'products.thumbnail', DB::raw('COUNT(wishlists.id) as total'))
            ->groupBy('products.id', 'products.name', 'products.thumbnail')
            ->orderByDesc('total')
            ->limit(5);

        $topProductsByFavorites = $this->applyDateFilter($topProductsByFavorites, $fromDate, $toDate, $year, $month, 'wishlists')->get();

        // --- Top Coupon Users ---
        $topCoupons = DB::table('orders')
            ->join('coupons', 'orders.coupon_id', '=', 'coupons.id')
            ->join('order_order_status', function ($join) use ($orderCompletedStatusId) {
                $join->on('orders.id', '=', 'order_order_status.order_id')
                    ->where('order_order_status.order_status_id', $orderCompletedStatusId)
                    ->where('order_order_status.is_current', 1);
            })
            ->select(
                'coupons.code',
                'coupons.discount_type',
                'coupons.discount_value',
                DB::raw('COUNT( orders.id) as total_uses'),
                DB::raw('SUM(orders.total_amount) as total_revenue')
            )
            ->groupBy('coupons.id', 'coupons.code', 'coupons.discount_type', 'coupons.discount_value')
            ->orderByDesc('total_revenue')
            ->limit(5);

        $topCoupons = $this->applyDateFilter($topCoupons, $fromDate, $toDate, $year, $month, 'orders')->get();
        // Doanh thu theo kênh thanh toán
        $paymentRevenue = Order::select('payment_id', DB::raw('SUM(total_amount) as total'))
            ->whereIn('id', $completedOrderIds) // chỉ lấy đơn hoàn thành
            ->groupBy('payment_id')
            ->pluck('total', 'payment_id');

        // Map payment_id sang tên
        $paymentMethods = [
            2 => 'COD',
            3 => 'MoMo',
            4 => 'VNPay',
        ];

        // Chuẩn hóa dữ liệu
        $paymentStats = Order::whereIn('id', $completedOrderIds)
            ->when($from && $to, function ($q) use ($from, $to) {
                $q->whereBetween('orders.created_at', [$from, $to]);
            }, function ($q) use ($year, $month) {
                $q->whereYear('orders.created_at', $year)
                    ->whereMonth('orders.created_at', $month);
            })
            ->select(
                'payment_id',
                DB::raw('SUM(total_amount) as total_amount'),
                DB::raw('COUNT(*) as total_orders')
            )
            ->groupBy('payment_id')
            ->get();


        // Chuẩn hóa dữ liệu
        $paymentStats = $paymentStats->map(function ($item) use ($paymentMethods) {
            return [
                'method'       => $paymentMethods[$item->payment_id] ?? 'Khác',
                'total_amount' => $item->total_amount,
                'total_orders' => $item->total_orders,
            ];
        });
        if ($request->ajax()) {

            return response()->json([
                'periodLabels' => $revenueByPeriod->pluck('label'),
                'periodData'   => $revenueByPeriod->pluck('total'),
                'statusLabels' => $orderStatusStats->pluck('name'),
                'statusData'   => $orderStatusStats->pluck('total'),
                'paymentLabels' => $paymentStats->pluck('method'),
                'paymentData'   => $paymentStats->pluck('total_orders'),
                'revenue'      => $revenue,
                'orderCount' => $orderCount,
                'userCount' => $userCount,
                'productCount' => $productCount,
                'paymentStats' => $paymentStats,
                'topCoupons' => $topCoupons,

                'topCustomers' => $topCustomers->map(function ($item) {
                    return [
                        'id' => $item->user->id ?? null,
                        'name' => $item->user->name ?? null,
                        'avatar' => $item->user->avatar ? asset('storage/' . $item->user->avatar) : asset('assets/images/default.png'),
                        'total_amount' => $item->total_amount,
                        'total_orders' => $item->total_orders,
                        'last_order_status' => $item->last_order_status ?? null,
                    ];
                }),
                'topProductsBySales' => $topProductsBySales->map(function ($item) {
                    $item->thumbnail_url = $item->thumbnail ? asset('storage/' . $item->thumbnail) : asset('assets/admin/img/product/no-image.png');
                    return $item;
                }),
                'topProductsByFavorites' => $topProductsByFavorites->map(function ($item) {
                    $item->thumbnail_url = $item->thumbnail ? asset('storage/' . $item->thumbnail) : asset('assets/admin/img/product/no-image.png');
                    return $item;
                }),
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
            'paymentStats',
            'topCoupons',
        ));
    }

    public function manageUsers()
    {
        $users = User::all(); // Lấy tất cả user
        return view('admin.users', compact('users')); // Trang quản lý người dùng
    }
}

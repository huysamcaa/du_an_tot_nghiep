<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
class UserController extends Controller
{
    public function index(Request $request)
    {
        $perPage        = (int) $request->input('perPage', 10);
        $search         = trim((string) $request->input('search'));

        $status         = $request->input('status');        // '', active, locked
        $role           = $request->input('role');          // '', user, admin
        $userGroup      = $request->input('user_group');    // '', guest, member, vip
        $gender         = $request->input('gender');        // '', male, female
        $emailVerified  = $request->input('email_verified'); // '', yes, no
        $hasOrders      = $request->input('has_orders');     // '', yes, no
        $hasReviews     = $request->input('has_reviews');    // '', yes, no

        $createdFrom    = $request->input('created_from');  // YYYY-MM-DD
        $createdTo      = $request->input('created_to');    // YYYY-MM-DD
        $birthdayFrom   = $request->input('birthday_from'); // YYYY-MM-DD
        $birthdayTo     = $request->input('birthday_to');   // YYYY-MM-DD

        $sort           = $request->input('sort', 'created_desc'); // created_desc|created_asc|name_asc|name_desc

        $query = \App\Models\User::query();

        // Tìm kiếm
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone_number', 'like', "%{$search}%");
            });
        }

        // Trạng thái
        if (in_array($status, ['active', 'locked'], true)) {
            $query->where('status', $status);
        }

        // Vai trò
        if (in_array($role, ['user', 'admin'], true)) {
            $query->where('role', $role);
        }
      // Giới tính
        if (in_array($gender, ['male', 'female'], true)) {
            $query->where('gender', $gender);
        }

        // Email verified
        if ($emailVerified === 'yes') {
            $query->whereNotNull('email_verified_at');
        } elseif ($emailVerified === 'no') {
            $query->whereNull('email_verified_at');
        }

        // Có đơn hàng / không
        if ($hasOrders === 'yes') {
            $query->whereHas('orders');
        } elseif ($hasOrders === 'no') {
            $query->whereDoesntHave('orders');
        }

        // Có đánh giá / không
        if ($hasReviews === 'yes') {
            $query->whereHas('reviews');
        } elseif ($hasReviews === 'no') {
            $query->whereDoesntHave('reviews');
        }
        // Sort
        match ($sort) {
            'created_asc' => $query->orderBy('created_at', 'asc'),
            'name_asc'    => $query->orderBy('name', 'asc'),
            'name_desc'   => $query->orderBy('name', 'desc'),
            default       => $query->orderBy('created_at', 'desc'),
        };

        $users = $query->paginate($perPage)->appends($request->query());

        return view('admin.users.index', compact('users'));
    }
    public function locked()
    {
        $users = User::where('status', 'locked')->get(); // Giả sử status = 'locked' là bị khóa
        return view('admin.users.locked', compact('users'));
    }
    public function unlock($id)
    {
        $user = User::findOrFail($id);
        $user->status = 'active'; // hoặc null nếu mặc định là chưa khóa
        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'Đã mở khóa tài khoản.');
    }
    public function lock(User $user)
    {
        if ($user->id == Auth :: id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Bạn không thể tự khóa tài khoản đang đăng nhập.');
        }
        $user->status = 'locked';
        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'Tài khoản đã bị khóa.');
    }
    public function show(User $user)
    {
        $ordersQuery = $user->orders();

        // Tổng số đơn
        $ordersTotal = (clone $ordersQuery)->count();

        // Đơn hoàn tất = đã thanh toán & không hoàn
        $completedFilter = function ($q) {
            $q->where('is_paid', 1)
                ->where(function ($qq) {
                    $qq->whereNull('is_refund')->orWhere('is_refund', 0);
                });
        };

        $ordersCompleted = (clone $ordersQuery)->where($completedFilter)->count();

        // Tổng chi tiêu từ user này
        $spentTotal = (clone $ordersQuery)->where($completedFilter)->sum('total_amount');

        // 5 đơn gần nhất (không lọc, chỉ để hiển thị lịch sử)
        $recentOrders = (clone $ordersQuery)->latest('created_at')->limit(5)->get();

        // Coupon: tổng & đã dùng (pivot.used_at)
        $user->load(['coupons' => function ($q) {
            $q->withTrashed();
        }]);
        $couponsTotal = $user->coupons->count();
        $couponsUsed  = $user->coupons()->wherePivotNotNull('used_at')->count();

        $stats = [
            'orders_total'     => $ordersTotal,
            'orders_completed' => $ordersCompleted,
            'spent_total'      => $spentTotal,
            'coupons_total'    => $couponsTotal,
            'coupons_used'     => $couponsUsed,
        ];

        return view('admin.users.show', compact('user', 'stats', 'recentOrders'));
    }
}

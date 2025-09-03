<?php

namespace App\Http\Controllers\Admin;

use Log;
use App\Models\Coupon;
use Illuminate\Http\Request;
use App\Models\Admin\Product;
use App\Models\Admin\Category;
use App\Models\CouponRestriction;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;


use Illuminate\Support\Facades\Schema;

class CouponController extends Controller
{
    public function index(Request $request)
    {
        $perPage      = max(1, (int) $request->input('perPage', 10));
        $search       = trim((string) $request->input('search'));
        $isActive     = $request->input('is_active');        // '', '1', '0'
        $discountType = $request->input('discount_type');    // '', 'percent', 'fixed'
        $startDate    = $request->input('start_date');       // YYYY-MM-DD
        $endDate      = $request->input('end_date');         // YYYY-MM-DD

        $query = Coupon::query();

        // Tìm kiếm theo mã / tiêu đề
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                    ->orWhere('title', 'like', "%{$search}%");
            });
        }

        // Trạng thái hoạt động
        if ($isActive === '1') {
            $query->where('is_active', 1);
        } elseif ($isActive === '0') {
            $query->where('is_active', 0);
        }

        // Loại mã
        if (in_array($discountType, ['percent', 'fixed'], true)) {
            $query->where('discount_type', $discountType);
        }

        // Lọc theo ngày bắt đầu/kết thúc (đã rút gọn)
        if ($startDate && $endDate && $endDate < $startDate) {
            // Hoán đổi nếu nhập ngược
            [$startDate, $endDate] = [$endDate, $startDate];
        }
        if ($startDate) {
            $query->whereDate('start_date', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('end_date', '<=', $endDate);
        }

        $coupons = $query
            ->orderByDesc('created_at')
            ->paginate($perPage)
            ->appends($request->query());

        return view('admin.coupons.index', compact('coupons'));
    }





    public function create()
    {
        $categories = Category::all();
        $products = Product::all();

        return view('admin.coupons.create', compact('categories', 'products'));
    }

    public function store(Request $request)
    {

        $this->validateForm($request);

        $coupon = Coupon::create($this->couponData($request));

        // Nếu có dữ liệu hạn chế, lưu vào bảng restriction
        if ($this->hasRestrictionData($request)) {
            $data = $this->restrictionData($request);
            $data['coupon_id'] = $coupon->id;
            $coupon->restriction()->create($data);
        }
        if ($coupon->is_notified) {
            // Lần đầu tạo: upsert để đảm bảo chỉ có 1 bản ghi/thông báo cho mỗi user-coupon
            $this->upsertCouponNotification($coupon, true);
        }


        return redirect()->route('admin.coupon.index')->with('success', 'Mã giảm giá đã được tạo thành công!');
    }

    public function edit($id)
    {
        $coupon = Coupon::findOrFail($id);
        $restriction = $coupon->restriction;
        // Lấy tất cả danh mục và sản phẩm để hiển thị trong form
        $categories = Category::all();
        $products = Product::all();

        return view('admin.coupons.edit', compact('coupon', 'restriction', 'categories', 'products'));
    }

    public function update(Request $request, $id)
    {
        $this->validateForm($request, $id);

        // Cập nhật mã giảm giá
        $coupon = Coupon::findOrFail($id);
        $coupon->update($this->couponData($request));

        // Cập nhật hoặc tạo mới dữ liệu restriction
        $data = $this->restrictionData($request);
        $data['coupon_id'] = $coupon->id;
        if ($coupon->restriction) {
            $coupon->restriction->update($data);
        } else {
            $coupon->restriction()->create($data);
        }

        if ($coupon->is_notified) {
            // Khi SỬA: chỉ cập nhật lại message của thông báo cũ, không tạo bản ghi mới
            $this->upsertCouponNotification($coupon, true);
        }
        return redirect()->route('admin.coupon.index')->with('success', 'Mã giảm giá đã được cập nhật thành công!');
    }

    public function destroy($id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->delete();

        return redirect()->route('admin.coupon.index')->with('success', 'Mã giảm giá đã được xóa thành công!');
    }


    // Thêm phương thức gửi thông báo
    protected function sendCouponNotification($coupon)
    {
        $users = \App\Models\User::all();

        foreach ($users as $user) {
            // Ghi vào DB notification
            DB::table('notifications')->insert([
                'user_id' => $user->id,
                'coupon_id' => $coupon->id,
                'message' => 'Mã giảm giá mới đã có sẵn! Sử dụng mã ' . $coupon->code . ' để giảm '
                    . $coupon->discount_value
                    . ($coupon->discount_type === 'percent' ? '%' : '₫')
                    . ' cho đơn hàng tiếp theo của bạn!',

                'type' => 1,
                'read' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    protected function validateForm(Request $request, $id = null)
    {
        if ($id && !$request->filled('discount_type')) {
            $coupon = Coupon::find($id);
            if ($coupon) {
                $request->merge(['discount_type' => $coupon->discount_type]);
            }
        }

        $rules = [
            'code' => ['nullable', Rule::unique('coupons', 'code')->ignore($id)],
            'title' => 'required|string|max:50',
            'description' => 'nullable|string|max:255',
            'discount_value' => 'required|numeric|min:0|max:99999999.99',
            'discount_type' => 'required|in:percent,fixed',
            'usage_limit' => 'nullable|integer|min:0',
            'user_group' => 'nullable|in:guest,member,vip',
            'is_expired' => 'boolean',
            'is_active' => 'boolean',
            'is_notified' => 'boolean',
            'start_date'       => 'nullable|date|required_if:is_expired,1',
            'end_date'         => 'nullable|date|required_if:is_expired,1|after_or_equal:start_date',
            'min_order_value' => 'nullable|numeric|min:0',
            'max_discount_value' => 'nullable|numeric|min:0',
        ];

        $messages = [
            'code.unique' => 'Mã giảm giá đã tồn tại.',
            'title.required' => 'Vui lòng nhập tiêu đề.',
            'discount_value.required' => 'Vui lòng nhập giá trị giảm.',
            'discount_value.numeric' => 'Giá trị giảm phải là số.',
            'discount_value.min' => 'Giá trị giảm không được nhỏ hơn 0.',
            'discount_value.max' => 'Giá trị giảm không được vượt quá 99,999,999.99.',
            'discount_type.required' => 'Vui lòng chọn kiểu giảm giá.',
            'discount_type.in' => 'Kiểu giảm giá không hợp lệ.',
            'usage_limit.integer' => 'Giới hạn sử dụng phải là số nguyên.',
            'usage_limit.min' => 'Giới hạn sử dụng không được nhỏ hơn 0.',
            'user_group.in' => 'Nhóm người dùng không hợp lệ.',
            'start_date.date' => 'Ngày bắt đầu không hợp lệ.',
            'end_date.date' => 'Ngày kết thúc không hợp lệ.',
            'min_order_value.min' => 'Giá trị đơn hàng tối thiểu không được nhỏ hơn 0.',
            'max_discount_value.min' => 'Số tiền giảm tối đa không được nhỏ hơn 0.',

        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        $validator->after(function ($validator) use ($request, $id) {
            $type = $request->input('discount_type');
            $discountValue = $this->sanitizeNumber($request->input('discount_value'));
            $maxDiscountValue = $this->sanitizeNumber($request->input('max_discount_value'));

            // Nếu giảm phần trăm
            if ($type === 'percent') {
                if ($discountValue > 100) {
                    $validator->errors()->add('discount_value', 'Giá trị phần trăm không được vượt quá 100%.');
                }

                if (!is_null($maxDiscountValue) && $maxDiscountValue <= 0) {
                    $validator->errors()->add('max_discount_value', 'Số tiền giảm tối đa phải lớn hơn 0 khi chọn giảm theo phần trăm.');
                }
            }

            // Nếu giảm cố định (fixed amount)
            if ($type === 'fixed') {
                if (!is_null($maxDiscountValue)) {
                    $validator->errors()->add('max_discount_value', 'Không cần nhập "Số tiền giảm tối đa" khi giảm giá cố định.');
                }
            }

            // Kiểm tra ngày
            if (
                $request->filled('start_date') && $request->filled('end_date') &&
                strtotime($request->input('end_date')) < strtotime($request->input('start_date'))
            ) {
                $validator->errors()->add('end_date', 'Ngày kết thúc phải sau ngày bắt đầu.');
            }
        });


        $validator->validate();
    }






   protected function couponData(Request $request)
{
    $data = $request->only([
        'title',
        'description',
        'discount_type',
        'usage_limit',
        'user_group',
        'start_date',
        'end_date',
    ]);

    $data['code'] = $request->filled('code')
        ? trim($request->input('code'))
        : $this->generateRandomCode();

    // Checkbox -> boolean
    $data['is_expired']  = $request->boolean('is_expired');  // = "Có thời hạn"
    $data['is_active']   = $request->boolean('is_active');
    $data['is_notified'] = $request->boolean('is_notified');

    // Chuẩn hoá số
    $data['discount_value'] = $this->sanitizeNumber($request->input('discount_value'));

    // Nếu không có thời hạn -> reset ngày
    if (!$data['is_expired']) {
        $data['start_date'] = null;
        $data['end_date']   = null;
    }

    return $data;
}





    protected function restrictionData(Request $request)
    {
        $validProducts = $request->input('valid_products', []);

        // Tìm danh mục từ các sản phẩm được chọn
        $validCategories = \App\Models\Admin\Product::whereIn('id', $validProducts)
            ->pluck('category_id')
            ->unique()
            ->filter()
            ->values()
            ->toArray();

        return [
            'min_order_value'    => $this->sanitizeNumber($request->input('min_order_value')),
            'max_discount_value' => $this->sanitizeNumber($request->input('max_discount_value')),
            'valid_categories'   => $validCategories,
            'valid_products'     => array_map('intval', (array) $validProducts),
        ];
    }



    protected function hasRestrictionData(Request $request)
    {
        return $request->hasAny([
            'min_order_value',
            'max_discount_value',
            'valid_categories',
            'valid_products',
        ]);
    }
    public function trashed(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('perPage', 10);

        $coupons = Coupon::onlyTrashed()
            ->when($search, function ($query, $search) {
                $query->where('code', 'like', '%' . $search . '%')
                    ->orWhere('title', 'like', '%' . $search . '%');
            })
            ->orderByDesc('deleted_at')
            ->paginate($perPage);

        return view('admin.coupons.trashed', compact('coupons', 'search', 'perPage'));
    }
    public function restore($id)
    {
        $coupon = Coupon::onlyTrashed()->findOrFail($id);
        $coupon->restore();

        return redirect()->route('admin.coupon.trashed')->with('success', 'Mã giảm giá đã được khôi phục thành công!');
    }
    public function show($id)
    {
        $coupon = Coupon::with('restriction')->findOrFail($id);

        // Phạm vi áp dụng
        $categories = Category::whereIn('id', $coupon->restriction->valid_categories ?? [])->get();
        $products   = Product::whereIn('id', $coupon->restriction->valid_products ?? [])->get();

        // ====== THỐNG KÊ ======
        $claimedTotal = DB::table('coupon_user')->where('coupon_id', $coupon->id)->count();

        $usedTotal = DB::table('coupon_user')
            ->where('coupon_id', $coupon->id)
            ->where(function ($q) {
                $q->whereNotNull('used_at')->orWhereNotNull('order_id');
            })
            ->count();

        $unusedTotal = max(0, $claimedTotal - $usedTotal);

        $revenue = (float) DB::table('coupon_user')
            ->where('coupon_id', $coupon->id)
            ->sum('discount_applied');

        $usageRate = $claimedTotal > 0 ? round($usedTotal * 100 / $claimedTotal, 1) : 0.0;

        $groupCounts = DB::table('coupon_user')
            ->select('user_group', DB::raw('COUNT(*) as cnt'))
            ->where('coupon_id', $coupon->id)
            ->groupBy('user_group')
            ->pluck('cnt', 'user_group')
            ->toArray();

        $groupBreakdown = [
            'guest'  => $groupCounts['guest']  ?? 0,
            'member' => $groupCounts['member'] ?? 0,
            'vip'    => $groupCounts['vip']    ?? 0,
            'khác'   => ($groupCounts[''] ?? 0) + ($groupCounts[null] ?? 0),
        ];

        $timeline = DB::table('coupon_user')
            ->select(DB::raw('DATE(COALESCE(used_at, created_at)) as d'), DB::raw('COUNT(*) as cnt'))
            ->where('coupon_id', $coupon->id)
            ->where(function ($q) {
                $q->whereNotNull('used_at')->orWhereNotNull('order_id');
            })
            ->groupBy('d')
            ->orderBy('d')
            ->get();

        $timelineLabels = $timeline->pluck('d')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d/m'))->values();
        $timelineValues = $timeline->pluck('cnt')->values();

        // ====== Fallback cột orders cho các truy vấn join ======
        $hasOrderCode   = Schema::hasColumn('orders', 'code');
        $hasOrderStatus = Schema::hasColumn('orders', 'status') || Schema::hasColumn('orders', 'current_status');

        // cột tổng tiền có thể là total_amount | total | grand_total
        $totalCol = null;
        foreach (['total_amount', 'total', 'grand_total'] as $col) {
            if (Schema::hasColumn('orders', $col)) {
                $totalCol = $col;
                break;
            }
        }

        // ---- recentUsage (join users + orders) ----
        $recentSelects = [
            'cu.id',
            'cu.user_id',
            'cu.order_id',
            'cu.code',
            'cu.discount_applied',
            'cu.used_at',
            'cu.created_at',
            'u.name as user_name',
            'u.email as user_email',
        ];
        $recentSelects[] = $hasOrderCode   ? DB::raw('o.code as order_code')         : DB::raw('NULL as order_code');
        if (Schema::hasColumn('orders', 'status')) {
            $recentSelects[] = DB::raw('o.status as order_status');
        } elseif (Schema::hasColumn('orders', 'current_status')) {
            $recentSelects[] = DB::raw('o.current_status as order_status');
        } else {
            $recentSelects[] = DB::raw('NULL as order_status');
        }
        $recentSelects[] = $totalCol ? DB::raw("o.$totalCol as order_total") : DB::raw('NULL as order_total');

        $recentUsage = DB::table('coupon_user as cu')
            ->leftJoin('users as u', 'u.id', '=', 'cu.user_id')
            ->leftJoin('orders as o', 'o.id', '=', 'cu.order_id')
            ->select($recentSelects)
            ->where('cu.coupon_id', $coupon->id)
            ->orderByRaw('COALESCE(cu.used_at, cu.updated_at, cu.created_at) DESC')
            ->limit(10)
            ->get();

        // ---- ordersUsingCoupon (chỉ các đơn có order_id) ----
        $ordersSelects = ['o.id', 'cu.discount_applied', 'cu.used_at'];
        $ordersSelects[] = $hasOrderCode ? 'o.code' : DB::raw('NULL as code');

        if (Schema::hasColumn('orders', 'status')) {
            $ordersSelects[] = DB::raw('o.status as status');
        } elseif (Schema::hasColumn('orders', 'current_status')) {
            $ordersSelects[] = DB::raw('o.current_status as status');
        } else {
            $ordersSelects[] = DB::raw('NULL as status');
        }

        $ordersSelects[] = $totalCol ? DB::raw("o.$totalCol as total_amount") : DB::raw('NULL as total_amount');

        $ordersUsingCoupon = DB::table('coupon_user as cu')
            ->join('orders as o', 'o.id', '=', 'cu.order_id')
            ->select($ordersSelects)
            ->where('cu.coupon_id', $coupon->id)
            ->orderByDesc('cu.used_at')
            ->limit(10)
            ->get();

        $stats = [
            'claimed_total' => $claimedTotal,
            'used_total'    => $usedTotal,
            'unused_total'  => $unusedTotal,
            'usage_rate'    => $usageRate,
            'revenue'       => $revenue,
        ];

        return view('admin.coupons.show', compact(
            'coupon',
            'categories',
            'products',
            'stats',
            'groupBreakdown',
            'timelineLabels',
            'timelineValues',
            'recentUsage',
            'ordersUsingCoupon'
        ));
    }


    protected function generateRandomCode($length = 8)
    {
        do {
            $code = strtoupper(str()->random($length));
        } while (Coupon::where('code', $code)->exists());

        return $code;
    }

    protected function sanitizeNumber($value)
    {
        if (is_null($value)) return null;

        // Nếu value là số rồi thì giữ nguyên
        if (is_numeric($value)) {
            return (float) $value;
        }

        // Nếu có cả dấu ',' và '.' => xác định định dạng kiểu quốc tế hay Việt Nam
        if (strpos($value, ',') !== false && strpos($value, '.') !== false) {
            if (strpos($value, ',') > strpos($value, '.')) {
                // VD: "1.234,56" (format Việt) => bỏ dấu . rồi thay , bằng .
                $value = str_replace('.', '', $value);
                $value = str_replace(',', '.', $value);
            } else {
                // VD: "1,234.56" (format US) => bỏ dấu , (thousands), giữ .
                $value = str_replace(',', '', $value);
            }
        } elseif (strpos($value, ',') !== false) {
            // Chỉ có dấu phẩy => giả định là định dạng Việt => thay , thành .
            $value = str_replace(',', '.', $value);
        } else {
            // Trường hợp chỉ có dấu chấm, coi như hợp lệ
            $value = $value;
        }

        return floatval($value);
    }
    // 1) Sinh nội dung thông báo đồng nhất
    protected function buildCouponMessage(\App\Models\Coupon $coupon): string
    {
        $value = $coupon->discount_value . ($coupon->discount_type === 'percent' ? '%' : '₫');

        $parts = [
            "Mã {$coupon->code} - {$coupon->title}",
            "Giảm: {$value}",
        ];

        if ($coupon->start_date) $parts[] = "Bắt đầu: {$coupon->start_date}";
        if ($coupon->end_date)   $parts[] = "Kết thúc: {$coupon->end_date}";

        return '📣 ' . implode(' | ', $parts);
    }

    // 2) Upsert thông báo theo (user_id, coupon_id, type)
    // - Nếu đã có: chỉ cập nhật message (+ optionally đặt lại read=0)
    // - Nếu chưa có: tạo mới
    protected function upsertCouponNotification(\App\Models\Coupon $coupon, bool $resetRead = true): void
    {
        $users = \App\Models\User::all();
        $message = $this->buildCouponMessage($coupon);

        foreach ($users as $user) {
            DB::table('notifications')->updateOrInsert(
                [
                    'user_id'   => $user->id,
                    'coupon_id' => $coupon->id,
                    'type'      => 1, // giữ đúng “type” bạn đang dùng cho coupon
                ],
                [
                    'message'    => $message,
                    'read'       => $resetRead ? 0 : DB::raw('read'), // set về chưa đọc để user thấy có cập nhật
                    'updated_at' => now(),
                    'created_at' => now(), // chỉ tác dụng khi insert
                ]
            );
        }
    }
    public function bulkRestore(Request $request)
    {
        $ids = array_filter((array) $request->input('ids', []), 'is_numeric');
        if (empty($ids)) return back()->with('success', 'Không có mục nào được chọn.');

        $restored = Coupon::onlyTrashed()->whereIn('id', $ids)->restore();
        return back()->with('success', "Đã khôi phục {$restored} mã.");
    }

    public function bulkDestroy(Request $request)
    {
        // Lấy danh sách id hợp lệ
        $ids = array_filter((array) $request->input('ids', []), 'is_numeric');

        if (empty($ids)) {
            return back()->with('warning', 'Không có mục nào được chọn.');
        }

        // Soft delete hàng loạt
        $deleted = Coupon::whereIn('id', $ids)->delete();

        return back()->with('success', "Đã xóa thành công {$deleted} mã giảm giá.");
    }
}

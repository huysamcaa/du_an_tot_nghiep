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
use App\Mail\CouponPromotionMail;
use Illuminate\Support\Facades\Mail;

class CouponController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('perPage', 10); // Mặc định 10 nếu không chọn

        $coupons = Coupon::when($search, function ($query, $search) {
            $query->where('code', 'like', '%' . $search . '%')
                ->orWhere('title', 'like', '%' . $search . '%');
        })
            ->orderByDesc('created_at')
            ->paginate($perPage) //
            ->appends(['perPage' => $perPage, 'search' => $search]); // Giữ lại filter khi chuyển trang

        return view('admin.coupons.index', compact('coupons', 'search', 'perPage'));
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

        // Gửi thông báo cho người dùng khi mã giảm giá mới được tạo, chỉ khi is_notified là true
        if ($coupon->is_notified) {
            $this->sendCouponNotification($coupon);
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

        // Gửi thông báo khi mã giảm giá được cập nhật, chỉ khi is_notified là true
        if ($coupon->is_notified) {
            $this->sendCouponNotification($coupon);
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
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
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

            if ($type === 'percent' && $discountValue > 100) {
                $validator->errors()->add('discount_value', 'Giá trị phần trăm không được vượt quá 100%.');
            }

            if ($type === 'fixed' && $discountValue !== null && $maxDiscountValue !== null && $maxDiscountValue > $discountValue) {
                $validator->errors()->add('max_discount_value', 'Số tiền giảm tối đa không được lớn hơn giá trị giảm.');
            }

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

    $data['is_expired'] = $request->has('is_expired');
    $data['is_active'] = $request->has('is_active');
    $data['is_notified'] = $request->has('is_notified');

    // ✅ Sử dụng hàm sanitizeNumber chuẩn hóa
    $data['discount_value'] = $this->sanitizeNumber($request->input('discount_value'));

    return $data;
}




    protected function restrictionData(Request $request)
    {
        $validProducts = $request->input('valid_products', []);

        // Tìm danh mục từ các sản phẩm được chọn
        $validCategories = Product::whereIn('id', $validProducts)
            ->pluck('category_id')
            ->unique()
            ->filter() // loại bỏ null nếu có
            ->values()
            ->toArray();

        return [
            'min_order_value' => $request->input('min_order_value'),
            'max_discount_value' => $request->input('max_discount_value'),
            'valid_categories' => $validCategories,
            'valid_products' => array_map('intval', (array) $validProducts),
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

        $categories = Category::whereIn('id', $coupon->restriction->valid_categories ?? [])->get();
        $products = Product::whereIn('id', $coupon->restriction->valid_products ?? [])->get();

        return view('admin.coupons.show', compact('coupon', 'categories', 'products'));
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


}

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
        $perPage = $request->input('perPage', 10);

        $coupons = Coupon::when($search, function ($query, $search) {
            $query->where('code', 'like', '%' . $search . '%')
                  ->orWhere('title', 'like', '%' . $search . '%');
        })
        ->orderByDesc('created_at')
        ->paginate($perPage);

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
            'user_id'    => $user->id,
            'coupon_id'  => $coupon->id,
            'message'    => 'Mã giảm giá mới đã có sẵn! Sử dụng mã ' . $coupon->code . ' để giảm ' . $coupon->discount_value . '% cho đơn hàng tiếp theo của bạn!',
            'type'       => 1,
            'read'       => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}

    protected function validateForm(Request $request, $id = null)
    {
        $rules = [
            'code' => ['required', Rule::unique('coupons', 'code')->ignore($id)],
            'title' => 'required|string|max:50',
            'description' => 'nullable|string|max:255',
            'discount_value' => 'required',
            'discount_type' => 'required|in:percent,fixed',
            'usage_limit' => 'nullable|integer',
            'user_group' => 'nullable|in:guest,member,vip',
            'is_expired' => 'boolean',
            'is_active' => 'boolean',
            'is_notified' => 'boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
        ];

        $messages = [
            'code.required' => 'Vui lòng nhập mã giảm giá.',
            'code.unique' => 'Mã giảm giá đã tồn tại.',
            'title.required' => 'Vui lòng nhập tiêu đề.',
            'title.max' => 'Tiêu đề không được vượt quá 50 ký tự.',
            'description.max' => 'Mô tả không được vượt quá 255 ký tự.',
            'discount_value.required' => 'Vui lòng nhập giá trị giảm.',
            'discount_type.required' => 'Vui lòng chọn kiểu giảm giá.',
            'discount_type.in' => 'Kiểu giảm giá không hợp lệ.',
            'usage_limit.integer' => 'Giới hạn sử dụng phải là số nguyên.',
            'user_group.in' => 'Nhóm người dùng không hợp lệ.',
            'start_date.date' => 'Ngày bắt đầu không hợp lệ.',
            'end_date.date' => 'Ngày kết thúc không hợp lệ.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

       $validator->after(function ($validator) use ($request) {
    $rawDiscount = $request->input('discount_value');
    $discountValue = (float) str_replace(',', '.', str_replace('.', '', $rawDiscount));
    $type = $request->input('discount_type');

    // Kiểm tra discount_value
    if (!is_numeric($discountValue)) {
        $validator->errors()->add('discount_value', 'Giá trị giảm không hợp lệ.');
    } else {
        if ($discountValue < 0) {
            $validator->errors()->add('discount_value', 'Giá trị giảm không được nhỏ hơn 0.');
        }

        if ($type === 'percent' && $discountValue > 100) {
            $validator->errors()->add('discount_value', 'Giá trị phần trăm không được vượt quá 100%.');
        }

        if ($type === 'fixed' && $discountValue < 1) {
            $validator->errors()->add('discount_value', 'Số tiền giảm phải lớn hơn 0.');
        }
    }

    // Kiểm tra min_order_value và max_discount_value không âm
    $minOrderValue = $request->input('min_order_value');
    $maxDiscountValue = $request->input('max_discount_value');

    if (!is_null($minOrderValue) && $minOrderValue < 0) {
        $validator->errors()->add('min_order_value', 'Giá trị đơn hàng tối thiểu không được nhỏ hơn 0.');
    }

    if (!is_null($maxDiscountValue)) {
        if ($maxDiscountValue < 0) {
            $validator->errors()->add('max_discount_value', 'Giá trị giảm tối đa không được nhỏ hơn 0.');
        } elseif ($discountValue > 0 && $maxDiscountValue > $discountValue) {
            $validator->errors()->add('max_discount_value', 'Giá trị giảm tối đa không được lớn hơn giá trị giảm.');
        }
    }
});


        $validator->validate();
    }

   protected function couponData(Request $request)
{
    $raw = $request->input('discount_value');
    $normalized = str_replace(',', '.', str_replace('.', '', $raw));

    $data = $request->only([
        'code', 'title', 'description', 'discount_type',
        'usage_limit', 'user_group', 'start_date', 'end_date',
    ]);

    // Xử lý riêng checkbox: nếu không có thì gán false
    $data['is_expired'] = $request->has('is_expired');
    $data['is_active'] = $request->has('is_active');
    $data['is_notified'] = $request->has('is_notified');

    $data['discount_value'] = (float) $normalized;

    return $data;
}


    protected function restrictionData(Request $request)
    {
        $validCategories = $request->input('valid_categories', []);
        $validProducts = $request->input('valid_products', []);

        return [
            'min_order_value'    => $request->input('min_order_value'),
            'max_discount_value' => $request->input('max_discount_value'),
            'valid_categories'   => array_map('intval', (array)$validCategories),
            'valid_products'     => array_map('intval', (array)$validProducts),
        ];
    }

    protected function hasRestrictionData(Request $request)
    {
        return $request->hasAny([
            'min_order_value', 'max_discount_value',
            'valid_categories', 'valid_products',
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

}

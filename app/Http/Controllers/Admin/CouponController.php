<?php

namespace App\Http\Controllers\Admin;

use App\Models\Coupon;
use App\Models\Admin\Product;
use App\Models\Admin\Category;
use Illuminate\Http\Request;
use App\Models\CouponRestriction;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
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

       if ($this->hasRestrictionData($request)) {
    $data = $this->restrictionData($request);
    $data['coupon_id'] = $coupon->id;
    $coupon->restriction()->create($data);
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

        $coupon = Coupon::findOrFail($id);
        $coupon->update($this->couponData($request));

       $data = $this->restrictionData($request);
$data['coupon_id'] = $coupon->id;

if ($coupon->restriction) {
    $coupon->restriction->update($data);
} else {
    $coupon->restriction()->create($data);
}


        return redirect()->route('admin.coupon.index')->with('success', 'Mã giảm giá đã được cập nhật thành công!');
    }

    public function destroy($id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->delete();

        return redirect()->route('admin.coupon.index')->with('success', 'Mã giảm giá đã được xóa thành công!');
    }
protected function validateForm(Request $request, $id = null)
{
    $rules = [
        'code' => 'required|unique:coupons,code,' . ($id ?? 'NULL'),
        'title' => 'required',
        'description' => 'nullable|string',
        'discount_value' => 'required|numeric',
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
        'discount_value.required' => 'Vui lòng nhập giá trị giảm.',
        'discount_value.numeric' => 'Giá trị giảm phải là số.',
        'discount_type.required' => 'Vui lòng chọn kiểu giảm giá.',
        'discount_type.in' => 'Kiểu giảm giá không hợp lệ.',
        'usage_limit.integer' => 'Giới hạn sử dụng phải là số nguyên.',
        'user_group.in' => 'Nhóm người dùng không hợp lệ.',
        'start_date.date' => 'Ngày bắt đầu không hợp lệ.',
        'end_date.date' => 'Ngày kết thúc không hợp lệ.',
    ];

    $validator = Validator::make($request->all(), $rules, $messages);

    $validator->after(function ($validator) use ($request) {
        $value = (int) $request->input('discount_value');
        $type = $request->input('discount_type');

        if ($type === 'percent' && ($value < 0 || $value > 100)) {
            $validator->errors()->add('discount_value', 'Giá trị phần trăm phải nằm trong khoảng từ 0 đến 100.');
        }

        if ($type === 'fixed' && $value < 1) {
            $validator->errors()->add('discount_value', 'Số tiền giảm phải lớn hơn 0.');
        }
    });

    $validator->validate(); // sẽ tự động redirect nếu có lỗi
}

    protected function couponData(Request $request)
    {
        $data = $request->only([
            'code', 'title', 'description', 'discount_type',
            'usage_limit', 'user_group', 'is_expired', 'is_active',
            'is_notified', 'start_date', 'end_date',
        ]);

        $data['discount_value'] = (int) $request->input('discount_value');

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
}

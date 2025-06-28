<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\CouponRestriction;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    // Hiển thị danh sách mã giảm giá
    public function index()
    {
        $coupons = Coupon::paginate(10);  // Dùng paginate để phân trang
        return view('admin.coupons.index', compact('coupons'));
    }

    // Hiển thị form tạo mã giảm giá mới
    public function create()
    {
        return view('admin.coupons.create');
    }

    // Lưu mã giảm giá mới
  public function store(Request $request)
{
    // Kiểm tra dữ liệu được gửi lên
    // dd($request->all()); // Debug xem dữ liệu có hợp lệ không

    // Xác thực dữ liệu đầu vào
    $request->validate([
        'code' => 'required|unique:coupons,code', // Mã giảm giá phải duy nhất
        'title' => 'required', // Tiêu đề mã giảm giá là bắt buộc
        'discount_value' => 'required|numeric', // Giá trị giảm giá phải là số
        'discount_type' => 'required|in:percent,fixed', // Kiểu giảm giá phải là 'percent' hoặc 'fixed'
        'is_active' => 'required|boolean', // Trạng thái kích hoạt (boolean)
    ]);

    // Chuyển đổi giá trị 'is_active' thành boolean (true/false)
    $is_active = $request->boolean('is_active');  // Sử dụng phương thức boolean

    // Lưu mã giảm giá vào bảng coupons
    $coupon = Coupon::create([
        'code' => $request->input('code'),
        'title' => $request->input('title'),
        'description' => $request->input('description', ''), // Gán mặc định là chuỗi trống nếu không có
        'discount_value' => $request->input('discount_value'),
        'discount_type' => $request->input('discount_type'),
        'usage_limit' => $request->input('usage_limit', 0), // Mặc định là 0 nếu không có
        'usage_count' => 0, // Mặc định là 0
        'user_group' => $request->input('user_group', 'guest'), // Mặc định là 'guest' nếu không có
        'is_expired' => $request->input('is_expired', 0), // Mặc định là 0 nếu không có
        'is_active' => $is_active,  // Lưu giá trị boolean (true/false)
        'is_notified' => 0, // Mặc định là 0 (chưa thông báo)
        'start_date' => $request->input('start_date'),
        'end_date' => $request->input('end_date'),
    ]);

    // Nếu có ràng buộc, lưu vào bảng coupon_restrictions
    if ($request->has('valid_categories') || $request->has('valid_products')) {
        $restriction = new CouponRestriction([
            'coupon_id' => $coupon->id,
            'min_order_value' => $request->input('min_order_value', 0), // Mặc định là 0 nếu không có
            'max_discount_value' => $request->input('max_discount_value', 0), // Mặc định là 0 nếu không có
            'valid_categories' => json_encode($request->input('valid_categories', [])), // Mặc định là mảng rỗng
            'valid_products' => json_encode($request->input('valid_products', [])), // Mặc định là mảng rỗng
        ]);
        $coupon->restriction()->save($restriction);
    }

    return redirect()->route('admin.coupon.index')->with('success', 'Mã giảm giá đã được tạo thành công!');
}
    // Hiển thị form chỉnh sửa mã giảm giá
    public function edit($id)
    {
        $coupon = Coupon::findOrFail($id);
        $restriction = $coupon->restriction;  // Lấy các ràng buộc của mã giảm giá
        return view('admin.coupons.edit', compact('coupon', 'restriction'));
    }

    // Cập nhật mã giảm giá
  public function update(Request $request, $id)
{
    $coupon = Coupon::findOrFail($id);

    // Kiểm tra dữ liệu được gửi lên
    // dd($request->all());  // Debug xem dữ liệu có hợp lệ không

    // Xác thực dữ liệu đầu vào
    $request->validate([
        'code' => 'required|unique:coupons,code,' . $id, // Tránh trùng mã giảm giá
        'title' => 'required',
        'discount_value' => 'required|numeric',
        'discount_type' => 'required|in:percent,fixed',
        'is_active' => 'required|boolean',
    ]);

    // Chuyển đổi giá trị 'is_active' thành boolean (true/false)
    $is_active = $request->boolean('is_active');  // Sử dụng phương thức boolean

    // Cập nhật mã giảm giá
    $coupon->update([
        'code' => $request->input('code'),
        'title' => $request->input('title'),
        'discount_value' => $request->input('discount_value'),
        'discount_type' => $request->input('discount_type'),
        'is_active' => $is_active,  // Lưu giá trị boolean (true/false)
        'start_date' => $request->input('start_date'),
        'end_date' => $request->input('end_date'),
    ]);

    // Cập nhật các ràng buộc nếu có
    if ($coupon->restriction) {
        $coupon->restriction->update([
            'min_order_value' => $request->input('min_order_value', 0),
            'max_discount_value' => $request->input('max_discount_value', 0),
            'valid_categories' => json_encode($request->input('valid_categories', [])),
            'valid_products' => json_encode($request->input('valid_products', [])),
        ]);
    }

    return redirect()->route('admin.coupon.index')->with('success', 'Mã giảm giá đã được cập nhật thành công!');
}

    // Xóa mã giảm giá
    public function destroy($id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->delete();  // Xóa mã giảm giá và các ràng buộc liên quan
        return redirect()->route('admin.coupon.index')->with('success', 'Mã giảm giá đã được xóa thành công!');
    }
}

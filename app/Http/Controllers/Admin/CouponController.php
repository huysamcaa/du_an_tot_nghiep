<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\CouponRestriction;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    // Hiển thị danh sách mã giảm giá với tính năng tìm kiếm và phân trang
    public function index(Request $request)
    {
        // Lấy từ khóa tìm kiếm từ yêu cầu (nếu có)
        $search = $request->input('search');
        $perPage = $request->input('perPage', 10); // Lấy số lượng bản ghi mỗi trang (mặc định là 10)

        // Áp dụng tìm kiếm và phân trang
        $coupons = Coupon::when($search, function ($query, $search) {
            return $query->where('code', 'like', '%' . $search . '%')
                         ->orWhere('title', 'like', '%' . $search . '%');
        })
        ->paginate($perPage); // Phân trang theo số lượng bản ghi mỗi trang

        return view('admin.coupons.index', compact('coupons', 'search', 'perPage'));
    }

    // Hiển thị form tạo mã giảm giá mới
    public function create()
    {
        return view('admin.coupons.create');
    }

    // Lưu mã giảm giá mới
    public function store(Request $request)
    {
        // Xác thực dữ liệu đầu vào
        $request->validate([
            'code' => 'required|unique:coupons,code',
            'title' => 'required',
            'discount_value' => 'required|numeric',
            'discount_type' => 'required|in:percent,fixed',
            'is_active' => 'required|boolean',
        ]);

        $is_active = $request->boolean('is_active'); // Chuyển giá trị boolean

        $coupon = Coupon::create([
            'code' => $request->input('code'),
            'title' => $request->input('title'),
            'discount_value' => $request->input('discount_value'),
            'discount_type' => $request->input('discount_type'),
            'is_active' => $is_active,
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
        ]);

        // Nếu có ràng buộc, lưu vào bảng coupon_restrictions
        if ($request->has('valid_categories') || $request->has('valid_products')) {
            $restriction = new CouponRestriction([
                'coupon_id' => $coupon->id,
                'valid_categories' => json_encode($request->input('valid_categories', [])),
                'valid_products' => json_encode($request->input('valid_products', [])),
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

        // Xác thực dữ liệu đầu vào
        $request->validate([
            'code' => 'required|unique:coupons,code,' . $id,
            'title' => 'required',
            'discount_value' => 'required|numeric',
            'discount_type' => 'required|in:percent,fixed',
            'is_active' => 'required|boolean',
        ]);

        $is_active = $request->boolean('is_active');  // Chuyển giá trị boolean

        $coupon->update([
            'code' => $request->input('code'),
            'title' => $request->input('title'),
            'discount_value' => $request->input('discount_value'),
            'discount_type' => $request->input('discount_type'),
            'is_active' => $is_active,
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
        ]);

        if ($coupon->restriction) {
            $coupon->restriction->update([
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

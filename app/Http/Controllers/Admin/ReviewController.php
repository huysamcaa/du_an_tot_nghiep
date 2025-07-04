<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Admin\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        // Khởi tạo truy vấn với mối quan hệ và sắp xếp mới nhất
        $query = Review::with(['user', 'product', 'order', 'multimedia'])->latest();

        // Kiểm tra nếu có giá trị tìm kiếm trong yêu cầu
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function($query) use ($search) {
                    $query->where('fullname', 'like', "%$search%"); // Tìm kiếm theo tên người dùng
                })
                ->orWhereHas('product', function($query) use ($search) {
                    $query->where('name', 'like', "%$search%"); // Tìm kiếm theo tên sản phẩm
                })
                ->orWhere('review_text', 'like', "%$search%"); // Tìm kiếm theo nội dung đánh giá (thay content bằng review_text)
            });
        }

        // Paginate kết quả với 10 mục mỗi trang
        $reviews = $query->paginate(10);

        return view('admin.reviews.index', compact('reviews'));
    }

    public function approve($id)
    {
        $review = Review::findOrFail($id);
        $review->update(['is_active' => 1, 'reason' => null]);
        return back()->with('success', 'Đã duyệt đánh giá.');
    }

    public function reject(Request $request, $id)
    {
        $request->validate(['reason' => 'required|string']);
        $review = Review::findOrFail($id);
        $review->update(['is_active' => 0, 'reason' => $request->reason]);
        return back()->with('success', 'Đánh giá bị từ chối.');
    }
}

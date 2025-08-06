<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Admin\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
{
    $query = Review::with(['user', 'product', 'order', 'multimedia'])
        ->leftJoin('users', 'reviews.user_id', '=', 'users.id')
        ->orderByRaw('users.id IS NULL')           // User tồn tại lên trước
        ->orderBy('reviews.created_at', 'asc')    // Mới nhất lên đầu
        ->select('reviews.*');                     // Tránh xung đột cột khi join

    if ($request->has('search')) {
        $search = $request->get('search');
        $query->where(function ($q) use ($search) {
            $q->where('users.fullname', 'like', "%$search%")              // từ join
              ->orWhereHas('product', function($query) use ($search) {
                  $query->where('name', 'like', "%$search%");
              })
              ->orWhere('review_text', 'like', "%$search%");
        });
    }

$perPage = $request->get('perPage', 10); // lấy từ request, mặc định 10
$reviews = $query->paginate($perPage)->appends(['perPage' => $perPage]);


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

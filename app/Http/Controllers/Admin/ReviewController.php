<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Admin\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
   public function index(Request $request)
{
    $perPage = $request->input('perPage', 10);
    $search = $request->input('search');

    $query = Review::with(['user', 'product', 'order', 'multimedia'])
        ->leftJoin('users', 'reviews.user_id', '=', 'users.id')
        ->select('reviews.*')
        ->orderByRaw('users.id IS NULL') // User tồn tại lên trước
        ->orderBy('reviews.created_at', 'desc'); // Mới nhất lên đầu

    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('users.fullname', 'like', "%{$search}%")
                ->orWhereHas('product', function ($sub) use ($search) {
                    $sub->where('name', 'like', "%{$search}%");
                })
                ->orWhere('review_text', 'like', "%{$search}%");
        });
    }

    $reviews = $query->paginate($perPage)->withQueryString();

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

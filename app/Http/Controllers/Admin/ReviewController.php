<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::with(['user', 'product', 'order', 'multimedia'])->latest()->get();
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

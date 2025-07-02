<?php
namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    // Hiển thị tất cả đánh giá của người dùng
    public function index()
    {
        $reviews = Review::with(['product', 'order', 'multimedia'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('client.reviews.index', compact('reviews'));
    }

    // Form chỉnh sửa đánh giá
    public function edit($id)
    {
        $review = Review::with(['product', 'order', 'multimedia'])
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('client.reviews.edit', compact('review'));
    }

    // Lưu cập nhật đánh giá
    public function update(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review_text' => 'required|string|max:1000',
        ]);

        $review = Review::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $review->update([
            'rating' => $request->rating,
            'review_text' => $request->review_text,
            'is_active' => null, // Chờ admin duyệt lại sau khi sửa
            'reason' => null,
        ]);

        return redirect()->route('client.reviews.index')->with('success', 'Đánh giá cập nhật thành công và chờ duyệt.');
    }
       public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'content' => 'required|string|max:1000',
        ]);

        Review::create([
            'user_id' => auth()->id(),
            'product_id' => $request->product_id,
            'rating' => $request->rating,
            'content' => $request->content,
            'status' => 'pending', // Đánh giá chờ duyệt
        ]);

        return back()->with('success', 'Cảm ơn bạn đã đánh giá! Đánh giá sẽ được duyệt sớm.');
    }
}

<?php
namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
 use App\Models\Product;
use App\Models\Order;

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

   

public function create($order_id, $product_id)
{
    // Kiểm tra xem đã có đánh giá chưa
    $existing = Review::where('user_id', Auth::id())
        ->where('order_id', $order_id)
        ->where('product_id', $product_id)
        ->first();

    if ($existing) {
        return redirect()->route('client.reviews.edit', $existing->id);
    }

    $product = Product::findOrFail($product_id);
    $order = Order::findOrFail($order_id);

    return view('client.reviews.create', compact('product', 'order'));
}

public function store(Request $request)
{
    $request->validate([
        'rating' => 'required|integer|min:1|max:5',
        'review_text' => 'required|string|max:1000',
        'product_id' => 'required|exists:products,id',
        'order_id' => 'required|exists:orders,id',
    ]);

    // Tránh tạo trùng
    $existing = Review::where('user_id', Auth::id())
        ->where('order_id', $request->order_id)
        ->where('product_id', $request->product_id)
        ->first();

    if ($existing) {
        return redirect()->route('client.reviews.edit', $existing->id);
    }

    Review::create([
        'user_id' => Auth::id(),
        'product_id' => $request->product_id,
        'order_id' => $request->order_id,
        'rating' => $request->rating,
        'review_text' => $request->review_text,
        'is_active' => null, // chờ admin duyệt
    ]);

    return redirect()->route('client.reviews.index')->with('success', 'Gửi đánh giá thành công. Đang chờ duyệt.');
}

}

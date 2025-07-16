<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Review;
use App\Models\Admin\ReviewMultimedia;
use App\Models\Admin\Product;
use App\Models\Shared\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
class ReviewController extends Controller
{
    public function index()
    {
      $reviews = Review::with(['product', 'multimedia'])
    ->where('user_id', Auth::id())
    ->whereHas('product', function ($q) {
        $q->where('is_active', 1); //  chỉ lấy sản phẩm đang hiển thị
    })
    ->where('is_active', 1)
    ->latest()
    ->paginate(10);


        return view('client.reviews.index', compact('reviews'));
    }

    public function create(Product $product)
    {
        return view('client.reviews.create', compact('product'));
    }

    public function store(Request $request)
{
    $request->validate([
        'product_id' => 'required|exists:products,id',
        'rating' => 'required|integer|min:1|max:5',
          'review_text' => 'required|string|min:10|max:500',
        'media.*' => 'nullable|file|mimes:jpg,jpeg,png,mp4|max:5120'
    ]);

    $user = Auth::user();
    $productId = $request->product_id;

    // Kiểm tra người dùng đã từng mua sản phẩm chưa
    $order = Order::where('user_id', $user->id)
        ->whereHas('items', function ($q) use ($productId) {
            $q->where('product_id', $productId);
        })
        ->latest()
        ->first();

    if (!$order) {
        return redirect()->back()->withErrors(['Bạn chỉ có thể đánh giá sản phẩm đã mua.']);
    }

    // Tạo đánh giá
    $review = Review::create([
        'product_id' => $productId,
        'order_id' => $order->id, // Gán order_id tự động
        'user_id' => $user->id,
        'rating' => $request->rating,
        'review_text' => $request->review_text,
        'is_active' => null, // Chờ duyệt
    ]);

    // Xử lý media nếu có
 if ($request->hasFile('media')) {
    foreach ($request->file('media') as $file) {
        $path = $file->store('review_multimedia', 'public');

        // Tự động phân loại kiểu file: image hoặc video
        $type = Str::contains($file->getMimeType(), 'video') ? 'video' : 'image';

        ReviewMultimedia::create([
            'review_id' => $review->id,
            'file' => $path,
            'file_type' => $type, // ✅ phù hợp ENUM
            'mime_type' => $file->getMimeType()
        ]);
    }
}

    return redirect()->route('client.reviews.index')
        ->with('success', 'Đánh giá của bạn đã được gửi và đang chờ duyệt.');
}

}

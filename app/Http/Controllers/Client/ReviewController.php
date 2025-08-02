<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Review;
use App\Models\Shared\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Admin\ReviewMultimedia;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::with(['product', 'multimedia'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('client.reviews.index', compact('reviews'));
    }

    // Hiển thị danh sách sản phẩm chờ đánh giá
   public function pending()
{
    $userId = auth()->id();

    // Lấy đơn hàng đã hoàn thành
    $completedOrders = Order::where('user_id', $userId)
        ->whereHas('currentStatus.orderStatus', function ($q) {
            $q->where('name', 'Đã hoàn thành'); // Nếu DB là "Hoàn thành" thì chỉnh lại
        })
        ->with(['items.product'])
        ->get();

    // Tạo collection chờ đánh giá
    $pendingReviews = collect();

    foreach ($completedOrders as $order) {
        foreach ($order->items as $item) {
            if (!$item->product) continue; // Bỏ sản phẩm bị xóa

            $alreadyReviewed = Review::where('product_id', $item->product->id)
                ->where('order_id', $order->id)
                ->where('user_id', $userId)
                ->exists();

            if (!$alreadyReviewed) {
                $pendingReviews->push([
                    'product' => $item->product,
                    'order_id' => $order->id,
                ]);
            }
        }
    }

    // Trả về view, $pendingReviews là Collection
    return view('client.reviews.pending', compact('pendingReviews'));
}


    // Hiển thị form tạo đánh giá
    public function create($order_id, $product_id)
    {
        // Kiểm tra trạng thái đơn hàng
        $orderIsCompleted = DB::table('order_order_status')
            ->where('order_id', $order_id)
            ->where('order_status_id', 5)
            ->where('is_current', 1)
            ->exists();

        if (!$orderIsCompleted) {
            return redirect()->back()->with('error', 'Chỉ có thể đánh giá sản phẩm trong đơn hàng đã hoàn thành.');
        }

        // Tiếp tục logic hiển thị form nếu cần
        return view('client.reviews.create', compact('order_id', 'product_id'));
    }

    // Lưu đánh giá
   public function store(Request $request)
{
    // Kiểm tra trạng thái đơn hàng đã hoàn thành
    $orderIsCompleted = DB::table('order_order_status')
        ->where('order_id', $request->order_id)
        ->where('order_status_id', 5) // Trạng thái "đã hoàn thành"
        ->where('is_current', 1)
        ->exists();

    if (!$orderIsCompleted) {
        return redirect()->back()->with('error', 'Chỉ có thể đánh giá sản phẩm trong đơn hàng đã hoàn thành.');
    }

    // Kiểm tra xem đã đánh giá sản phẩm này trong đơn này chưa
    $alreadyReviewed = Review::where('product_id', $request->product_id)
        ->where('order_id', $request->order_id)
        ->where('user_id', Auth::id())
        ->exists();

    if ($alreadyReviewed) {
        return redirect()
            ->route('client.orders.purchase.history')
            ->with('error', 'Bạn đã đánh giá sản phẩm này rồi.');
    }

    //
    $request->validate(
        [
            'rating' => 'required|integer|min:1|max:5',
            'review_text' => 'required|string|min:10|max:500',
            'product_id' => 'required|exists:products,id',
            'order_id' => 'required|exists:orders,id',
            'media.*' => 'nullable|file|mimes:jpg,jpeg,png,mp4|max:5120',
        ],
        [
            'rating.required' => 'Vui lòng chọn số sao đánh giá.',
            'rating.integer' => 'Số sao phải là một số nguyên.',
            'rating.min' => 'Số sao thấp nhất là 1.',
            'rating.max' => 'Số sao cao nhất là 5.',

            'review_text.required' => 'Vui lòng nhập nội dung đánh giá.',
            'review_text.min' => 'Nội dung đánh giá phải có ít nhất 10 ký tự.',
            'review_text.max' => 'Nội dung đánh giá không được vượt quá 500 ký tự.',

            'product_id.required' => 'Thiếu thông tin sản phẩm.',
            'product_id.exists' => 'Sản phẩm không tồn tại.',

            'order_id.required' => 'Thiếu thông tin đơn hàng.',
            'order_id.exists' => 'Đơn hàng không tồn tại.',

            'media.*.file' => 'File tải lên không hợp lệ.',
            'media.*.mimes' => 'Chỉ chấp nhận ảnh JPG, JPEG, PNG hoặc video MP4.',
            'media.*.max' => 'Mỗi file không được vượt quá 5MB.',
        ]
    );

    // Lưu đánh giá mới với trạng thái CHỜ DUYỆT
    $review = new Review();
    $review->user_id = Auth::id();
    $review->product_id = $request->product_id;
    $review->order_id = $request->order_id;
    $review->rating = $request->rating;
    $review->review_text = $request->review_text;
    $review->is_active = null;
    $review->reason = null;
    $review->save();

    // Lưu file đa phương tiện nếu có
    if ($request->hasFile('media')) {
        foreach ($request->file('media') as $file) {
            $path = $file->store('reviews', 'public');
            ReviewMultimedia::create([
                'review_id' => $review->id,
                'file' => $path,
                'file_type' => Str::contains($file->getMimeType(), 'video') ? 'video' : 'image',
                'mime_type' => $file->getMimeType(),
            ]);
        }
    }

    //
    return redirect()->route('client.orders.purchase.history')
    ->with('success', 'Đánh giá của bạn đã được gửi và đang chờ duyệt.')
    ->with('review_submitted', true);

}

}

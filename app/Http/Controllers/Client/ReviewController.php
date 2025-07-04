<?php
namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Review;
use App\Models\Admin\ReviewMultimedia;
use App\Models\Admin\Product;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::with('product', 'multimedia')
            ->where('user_id', Auth::id())
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
            'review_text' => 'required|string|max:1000',
            'media.*' => 'nullable|file|mimes:jpg,jpeg,png,mp4|max:5120'
        ]);

        $review = Review::create([
            'product_id' => $request->product_id,
            'order_id' => null,
            'user_id' => Auth::id(),
            'rating' => $request->rating,
            'review_text' => $request->review_text,
            'is_active' => 0
        ]);

        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $file) {
                $path = $file->store('review_multimedia', 'public');
                ReviewMultimedia::create([
                    'review_id' => $review->id,
                    'file' => $path,
                    'file_type' => $file->getClientOriginalExtension(),
                    'mime_type' => $file->getMimeType()
                ]);
            }
        }

        return redirect()->route('client.reviews.index')->with('success', 'Đánh giá của bạn đã được gửi và đang chờ duyệt.');
    }
}

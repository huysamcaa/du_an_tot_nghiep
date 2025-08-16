<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Admin\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class ReviewController extends Controller
{
public function index(Request $request)
{
    $perPage    = (int) $request->input('perPage', 10);
    $search     = trim((string) $request->input('search'));

    // Bộ lọc mới
    $status     = $request->input('status');           // pending|approved|rejected|''
    $rating     = $request->input('rating');           // 1..5 hoặc ''
    $ratingMin  = $request->input('rating_min');       // 1..5 hoặc ''
    $ratingMax  = $request->input('rating_max');       // 1..5 hoặc ''
    $hasMedia   = $request->input('has_media');        // yes|no|''
    $dateFrom   = $request->input('date_from');        // YYYY-MM-DD
    $dateTo     = $request->input('date_to');          // YYYY-MM-DD

    $query = Review::with(['user', 'product', 'order', 'multimedia'])
        ->leftJoin('users', 'reviews.user_id', '=', 'users.id')
        ->select('reviews.*')
        ->orderByRaw('users.id IS NULL') // user tồn tại lên trước
        ->orderBy('reviews.created_at', 'desc');

    // Tìm kiếm
    if ($search !== '') {
        $query->where(function ($q) use ($search) {
            $q->where('users.name', 'like', "%{$search}%")
              ->orWhere('review_text', 'like', "%{$search}%")
              ->orWhereHas('product', function ($sub) use ($search) {
                  $sub->where('name', 'like', "%{$search}%");
              });
        });
    }

    // Trạng thái: pending=null, approved=1, rejected=0
    if ($status === 'pending') {
        $query->whereNull('reviews.is_active');
    } elseif ($status === 'approved') {
        $query->where('reviews.is_active', 1);
    } elseif ($status === 'rejected') {
        $query->where('reviews.is_active', 0);
    }

    // Điểm sao: chính xác hoặc khoảng
    if (is_numeric($rating)) {
        $query->where('reviews.rating', (int) $rating);
    } else {
        if (is_numeric($ratingMin)) {
            $query->where('reviews.rating', '>=', (int) $ratingMin);
        }
        if (is_numeric($ratingMax)) {
            $query->where('reviews.rating', '<=', (int) $ratingMax);
        }
    }

    // Có media / không
    if ($hasMedia === 'yes') {
        $query->whereHas('multimedia');
    } elseif ($hasMedia === 'no') {
        $query->whereDoesntHave('multimedia');
    }

    // Khoảng ngày tạo
    if ($dateFrom) {
        $query->whereDate('reviews.created_at', '>=', $dateFrom);
    }
    if ($dateTo) {
        $query->whereDate('reviews.created_at', '<=', $dateTo);
    }

    $reviews = $query->paginate($perPage)->appends($request->query());

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
    $validator = Validator::make(
        $request->all(),
        ['reason' => 'required|string'],
        ['reason.required' => 'Vui lòng nhập lý do',
         'reason.string'   => 'Lý do phải là chuỗi hợp lệ']
    );

    if ($validator->fails()) {
        return back()
            ->withErrors($validator)
            ->withInput()
            ->with('reject_id', $id);
    }

    $review = Review::findOrFail($id);
    $review->update(['is_active' => 0, 'reason' => $request->reason]);

    return back()->with('success', 'Đánh giá bị từ chối.');
}
}

<?php
namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::with(['product', 'order', 'multimedia'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('client.reviews.index', compact('reviews'));
    }
}

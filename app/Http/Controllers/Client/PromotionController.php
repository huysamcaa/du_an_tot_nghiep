<?php

namespace App\Http\Controllers\Client;

use App\Models\Promotion;
use App\Http\Controllers\Controller;
use App\Models\Admin\Category;

class PromotionController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        $today = now()->format('Y-m-d');
        $promotions = Promotion::where('start_date', '<=', $today)
                               ->where('end_date', '>=', $today)
                               ->orderByDesc('start_date')
                               ->get();

        return view('client.promotions.index', compact('promotions'));
    }

    public function show(Promotion $promotion)
    {
        return view('client.promotions.show', compact('promotion'));
    }
}

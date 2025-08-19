<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Models\Admin\Product;
use App\Http\Controllers\Controller;
use App\Models\Admin\Category;
use App\Models\Blog;
use App\Models\Admin\Review;
class HomeController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::where('is_active', 1)
            ->latest()
            ->paginate(8);

        $categories = Category::where('is_active', 1)
            ->withCount('products')
            ->orderBy('ordinal')
            ->get();

        $blogs = Blog::latest()->take(3)->get();

        $reviews = Review::with('user')
            ->where('is_active', 1)
            ->where('rating', 5)
            ->latest()
            ->take(6)
            ->get();

        if ($request->ajax()) {
            return view('client.components.products-list', compact('products'))->render();
        }

        return view('client.home', compact('products', 'categories', 'blogs','reviews'));
    }
}

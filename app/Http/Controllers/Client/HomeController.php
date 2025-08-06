<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Models\Admin\Product;
use App\Http\Controllers\Controller;
use App\Models\Admin\Category;
use App\Models\Blog;

class HomeController extends Controller
{
    public function index()
    {
        $products = Product::where('is_active', 1)
            ->latest()
            ->take(8)
            ->get();

        $categories = Category::where('is_active', 1)
              ->withCount('products')
            ->orderBy('ordinal')
            ->get();

         $blogs = Blog::latest()->take(3)->get();


        return view('client.home', compact('products', 'categories','blogs'));
    }
}

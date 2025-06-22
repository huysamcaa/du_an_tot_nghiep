<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Models\Admin\Product;
use App\Http\Controllers\Controller;
use App\Models\Admin\Category;

class HomeController extends Controller
{
    public function index()
    {
        $products = Product::latest()->take(8)->get(); 

        $categories = Category::where('is_active', 1)
            ->orderBy('ordinal')
            ->get();

        return view('client.home', compact('products','categories'));
    }
}
<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Admin\Category;

class CategoryClientController extends Controller
{
    public function index()
    {
        $categories = Category::where('is_active', 1)
            ->whereNull('parent_id')
            ->with('children') // load danh mục con nếu có
            ->orderBy('ordinal')
            ->get();

        return view('client.categories.index', compact('categories'));
    }
}

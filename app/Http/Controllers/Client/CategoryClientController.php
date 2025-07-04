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
        ->with('children')
        ->orderBy('ordinal')
        ->get();

    // Lấy danh mục đầu tiên (nếu có)
    $currentCategory = $categories->first();

    $products = [];

    if ($currentCategory) {
        $categoryIds = [$currentCategory->id];
        if ($currentCategory->children->count()) {
            $categoryIds = array_merge($categoryIds, $currentCategory->children->pluck('id')->toArray());
        }

        $products = $currentCategory->products()
            ->where('is_active', 1)
            ->paginate(9);
    }

    return view('client.categories.index', compact('categories', 'products', 'currentCategory'));
}


  public function showCategory()
{
    // Lấy các danh mục cha và con cho sidebar
    $categories = Category::where('is_active', 1)
        ->whereNull('parent_id')
        ->with('children')
        ->orderBy('ordinal')
        ->get();

    // Lấy danh mục đầu tiên làm current (hoặc null nếu không có)
    $currentCategory = $categories->first();

    // Lấy sản phẩm thuộc danh mục hiện tại, phân trang 12 mục mỗi trang
    $products = $currentCategory
        ? $currentCategory->products()->where('is_active', 1)->paginate(12)
        : collect();

    // Trả về view show với đủ 3 biến
    return view('client.categories.show', compact('categories', 'currentCategory', 'products'));
}



}

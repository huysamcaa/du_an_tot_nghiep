<?php
namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Admin\Category;
use App\Models\Admin\Product;

class ProductController extends Controller
{
   public function listByCategory($id)
{
    $category = Category::with('products')->findOrFail($id);
    $products = $category->products()->paginate(12);

    $categories = Category::where('is_active', 1)
        ->whereNull('parent_id')
        ->with('children')
        ->orderBy('ordinal')
        ->get();

    $currentCategory = $category;

    return view('client.categories.show', compact('categories', 'currentCategory', 'products'));
}


public function show($id)
{
    $product = Product::with(['variants.attributeValues.attribute'])->findOrFail($id);

    return view('client.products.show', compact('product'));
}

}

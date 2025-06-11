<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Models\Admin\Product;
use App\Models\Admin\Category;
use App\Http\Controllers\Controller;

// Controller để xử lý chi tiết sản phẩm
class ProductDetailController extends Controller
{
  public function show($id)
{
    $product = Product::findOrFail($id);
    $category = $product->category;
    return view('client.productDetal.detal', compact('product','category'));
}

}

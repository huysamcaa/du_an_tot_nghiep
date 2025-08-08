<?php
namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Product;

class SearchController extends Controller
{
    public function search(Request $request)
{
    $keyword = $request->input('keyword');

    $products = Product::where('name', 'like', '%' . $keyword . '%')
                ->orWhere('description', 'like', '%' . $keyword . '%')
                ->paginate(8);

    return view('client.pages.search_result', compact('products', 'keyword'));
}
}

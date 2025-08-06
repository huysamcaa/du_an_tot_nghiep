<?php

namespace App\Http\Controllers\Client;

use App\Models\Brand;
use Illuminate\Http\Request;
use App\Models\Admin\Product;
use App\Models\Admin\Category;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{

    public function index(Request $request)
    {
        // 1. Sidebar: load tất cả categories (cha + con)
        $categories = Category::where('is_active', 1)
            ->with('children')
            ->orderBy('ordinal')
            ->get();

        // 2. Đọc filter từ URL
        $min              = max(0, (int)$request->query('price_min', 0));
        $max              = min(5000000, (int)$request->query('price_max', 5000000));
        $selectedSize     = $request->query('size', null);
        $selectedColor    = $request->query('color', null);
        $selectedBrand    = $request->query('brand', null);
        $selectedCategory = $request->query('category_id', null);
        $sort             = $request->query('sort', null);

        // 3. Base product query (price)
        $productsQuery = Product::where('is_active', 1)
            ->where(function ($q) use ($min, $max) {
                $q->where(function ($q2) use ($min, $max) {
                    $q2->whereNotNull('sale_price')
                        ->whereBetween('sale_price', [$min, $max]);
                })->orWhere(function ($q2) use ($min, $max) {
                    $q2->whereNull('sale_price')
                        ->whereBetween('price', [$min, $max]);
                });
            });

        // 4. Size filter
        if ($selectedSize) {
            $productsQuery->whereHas('variants.attributeValues', function ($q) use ($selectedSize) {
                $q->where('value', $selectedSize)
                    ->whereHas('attribute', fn($aq) => $aq->where('slug', 'size'));
            });
        }

        // 5. Color filter
        if ($selectedColor) {
            $productsQuery->whereHas('variants.attributeValues', function ($q) use ($selectedColor) {
                $q->where('hex', $selectedColor)
                    ->whereHas('attribute', fn($aq) => $aq->where('slug', 'color'));
            });
        }

        // 6. Brand filter
        if ($selectedBrand) {
            $productsQuery->where('brand_id', $selectedBrand);
        }

        // 7. Category filter
        if ($selectedCategory) {
        $productsQuery->where('category_id', $selectedCategory);
        }

        // 8. Sort
        switch ($sort) {
            case 'price_desc':
                $productsQuery->orderByRaw('COALESCE(sale_price, price) DESC');
                break;
            case 'price_asc':
                $productsQuery->orderByRaw('COALESCE(sale_price, price) ASC');
                break;
            case 'newest':
                $productsQuery->orderBy('created_at', 'desc');
                break;
            default:
                $productsQuery->orderBy('id', 'desc');
        }

        // 9. Eager‑load + paginate, giữ query string
        $products = $productsQuery
            ->with([
                'brand',
                'galleries',
                'reviews',
                'variants.attributeValues.attribute',
                'categories'
            ])
            ->paginate(12)
            ->appends($request->only([
                'price_min',
                'price_max',
                'size',
                'color',
                'brand',
                'category_id',
                'sort'
            ]));

        // 10. Build sidebar filters lists

        $availableSizes = Product::where('is_active', 1)
            ->with('variants.attributeValues.attribute')
            ->get()
            ->flatMap(fn($p) => $p->variants->pluck('attributeValues')->flatten(1))
            ->filter(fn($av) => $av->attribute->slug === 'size')
            ->pluck('value')->unique()->values()->toArray();

        $availableColors = Product::where('is_active', 1)
            ->with('variants.attributeValues.attribute')
            ->get()
            ->flatMap(fn($p) => $p->variants->pluck('attributeValues')->flatten(1))
            ->filter(fn($av) => $av->attribute->slug === 'color')
            ->pluck('hex')->filter()->unique()->values()->toArray();

        $availableBrands = Brand::where('is_active', 1)
    ->whereHas('products', fn($q) => $q->where('is_active', 1))
    ->withCount(['products' => fn($q) => $q->where('is_active', 1)])
    ->get()
    ->pluck('name', 'id')
    ->toArray();
        // 11. Return view
        return view('client.categories.index', compact(
            'categories',
            'products',
            'min',
            'max',
            'availableSizes',
            'selectedSize',
            'availableColors',
            'selectedColor',
            'availableBrands',
            'selectedBrand',
            'selectedCategory',
            'sort'
        ));
    }
}

<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Product;
use App\Models\Admin\Category;
use App\Models\Brand;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        // 1. Sidebar: load tất cả categories
        $categories = Category::where('is_active', 1)
            ->with('children')
            ->orderBy('ordinal')
            ->get();

        // 2. Lấy keyword tìm kiếm
        $keyword = $request->input('keyword');

        // 3. Lấy filter từ URL
        $min              = max(0, (int)$request->query('price_min', 0));
        $max              = min(5000000, (int)$request->query('price_max', 5000000));
        $selectedSize     = $request->query('size', null);
        $selectedColor    = $request->query('color', null);
        $selectedBrand    = $request->query('brand', null);
        $selectedCategory = $request->query('category_id', null);
        $sort             = $request->query('sort', null);

        // 4. Base product query (search + price filter từ bảng variants)
        $productsQuery = Product::where('is_active', 1)
            ->where(function ($q) use ($keyword) {
                $q->where('name', 'like', '%' . $keyword . '%')
                    ->orWhere('description', 'like', '%' . $keyword . '%');
            })
            ->whereHas('variants', function ($q) use ($min, $max) {
                $q->where(function ($q2) use ($min, $max) {
                    $q2->whereNotNull('sale_price')
                        ->whereBetween('sale_price', [$min, $max]);
                })->orWhere(function ($q2) use ($min, $max) {
                    $q2->whereNull('sale_price')
                        ->whereBetween('price', [$min, $max]);
                });
            });

        // 5. Size filter
        if ($selectedSize) {
            $productsQuery->whereHas('variants.attributeValues', function ($q) use ($selectedSize) {
                $q->where('value', $selectedSize)
                    ->whereHas('attribute', fn($aq) => $aq->where('slug', 'size'));
            });
        }

        // 6. Color filter
        if ($selectedColor) {
            $productsQuery->whereHas('variants.attributeValues', function ($q) use ($selectedColor) {
                $q->where('hex', $selectedColor)
                    ->whereHas('attribute', fn($aq) => $aq->where('slug', 'color'));
            });
        }

        // 7. Brand filter
        if ($selectedBrand) {
            $productsQuery->where('brand_id', $selectedBrand);
        }

        // 8. Category filter
        if ($selectedCategory) {
            $productsQuery->where('category_id', $selectedCategory);
        }

        // 9. Sort theo giá variants hoặc mặc định
        switch ($sort) {
            case 'price_desc':
                $productsQuery->withMin('variants', 'price')
                    ->withMin('variants', 'sale_price')
                    ->orderByRaw('COALESCE(MIN(product_variants.sale_price), MIN(product_variants.price)) DESC');
                break;
            case 'price_asc':
                $productsQuery->withMin('variants', 'price')
                    ->withMin('variants', 'sale_price')
                    ->orderByRaw('COALESCE(MIN(product_variants.sale_price), MIN(product_variants.price)) ASC');
                break;
            case 'newest':
                $productsQuery->orderBy('created_at', 'desc');
                break;
            default:
                $productsQuery->orderBy('id', 'desc');
        }

        // 10. Lấy sản phẩm + giữ query string
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
                'keyword',
                'price_min',
                'price_max',
                'size',
                'color',
                'brand',
                'category_id',
                'sort'
            ]));

        // 11. Build sidebar filter lists
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

        // 12. Trả về view
        return view('client.pages.search_result', compact(
            'categories',
            'products',
            'keyword',
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

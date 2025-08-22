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
        // Set giá trị mặc định nếu không có trên URL
        $min = (int)$request->query('price_min', 0);
        $max = (int)$request->query('price_max', 5000000);
        $selectedSize = $request->query('size', null);
        $selectedColor = $request->query('color', null);
        $selectedBrand = $request->query('brand', null);
        $selectedCategory = $request->query('category_id', null);
        $sort = $request->query('sort', null);

        // 3. Base product query
        $productsQuery = Product::where('is_active', 1);

        // 4. PRICE filter: Áp dụng luôn trên biến thể, với giá trị mặc định hoặc từ URL
        $productsQuery->whereHas('variants', function ($q) use ($min, $max) {
            $q->whereBetween('price', [$min, $max]);
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

        // 9. Sort
        switch ($sort) {
            case 'price_desc':
                // Sắp xếp theo giá biến thể thấp nhất/cao nhất
                $productsQuery->orderByRaw('(SELECT COALESCE(MIN(sale_price), MIN(price)) FROM product_variants WHERE product_id = products.id) DESC');
                break;
            case 'price_asc':
                // Sắp xếp theo giá biến thể thấp nhất/cao nhất
                $productsQuery->orderByRaw('(SELECT COALESCE(MIN(sale_price), MIN(price)) FROM product_variants WHERE product_id = products.id) ASC');
                break;
            case 'newest':
                $productsQuery->orderBy('created_at', 'desc');
                break;
            default:
                $productsQuery->orderBy('id', 'desc');
        }

        // 10. Eager-load + paginate, giữ query string
        $products = $productsQuery
            ->with([
                'brand',
                'galleries',
                'reviews',
                'variants.attributeValues.attribute',
                'categories'
            ])
            ->paginate(12)
            ->appends($request->except(['page']));

        // 11. Build sidebar filters lists
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

        // 12. Return view
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

    public function show(Request $request, $slug)
    {
        // 1. Lấy category theo slug
        $selectedCategory = Category::where('slug', $slug)->firstOrFail();

        // 2. Sidebar: load tất cả categories
        $categories = Category::where('is_active', 1)
            ->with('children')
            ->orderBy('ordinal')
            ->get();

        // 3. Đọc filter từ URL
        // Set giá trị mặc định nếu không có trên URL
        $min = (int)$request->query('price_min', 0);
        $max = (int)$request->query('price_max', 5000000);
        $selectedSize = $request->query('size', null);
        $selectedColor = $request->query('color', null);
        $selectedBrand = $request->query('brand', null);
        $selectedCategory = $selectedCategory->id; // ép luôn id của category từ slug
        $sort = $request->query('sort', null);

        // 4. Base product query: lọc theo category_id
        $productsQuery = Product::where('is_active', 1)
            ->where('category_id', $selectedCategory);

        // 5. PRICE filter: Áp dụng luôn trên biến thể, với giá trị mặc định hoặc từ URL
        $productsQuery->whereHas('variants', function ($q) use ($min, $max) {
            $q->whereBetween('price', [$min, $max]);
        });


        // 6. Size filter
        if ($selectedSize) {
            $productsQuery->whereHas('variants.attributeValues', function ($q) use ($selectedSize) {
                $q->where('value', $selectedSize)
                    ->whereHas('attribute', fn($aq) => $aq->where('slug', 'size'));
            });
        }

        // 7. Color filter
        if ($selectedColor) {
            $productsQuery->whereHas('variants.attributeValues', function ($q) use ($selectedColor) {
                $q->where('hex', $selectedColor)
                    ->whereHas('attribute', fn($aq) => $aq->where('slug', 'color'));
            });
        }

        // 8. Brand filter
        if ($selectedBrand) {
            $productsQuery->where('brand_id', $selectedBrand);
        }

        // 9. Sort
        switch ($sort) {
            case 'price_desc':
                // Sắp xếp theo giá biến thể thấp nhất/cao nhất
                $productsQuery->orderByRaw('(SELECT COALESCE(MIN(sale_price), MIN(price)) FROM product_variants WHERE product_id = products.id) DESC');
                break;
            case 'price_asc':
                // Sắp xếp theo giá biến thể thấp nhất/cao nhất
                $productsQuery->orderByRaw('(SELECT COALESCE(MIN(sale_price), MIN(price)) FROM product_variants WHERE product_id = products.id) ASC');
                break;
            case 'newest':
                $productsQuery->orderBy('created_at', 'desc');
                break;
            default:
                $productsQuery->orderBy('id', 'desc');
        }

        // 10. Eager load + paginate
        $products = $productsQuery
            ->with([
                'brand',
                'galleries',
                'reviews',
                'variants.attributeValues.attribute',
                'categories'
            ])
            ->paginate(12)
            ->appends($request->except(['page']));

        // 11. Build sidebar filter lists
        $availableSizes = Product::where('is_active', 1)
            ->where('category_id', $selectedCategory)
            ->with('variants.attributeValues.attribute')
            ->get()
            ->flatMap(fn($p) => $p->variants->pluck('attributeValues')->flatten(1))
            ->filter(fn($av) => $av->attribute->slug === 'size')
            ->pluck('value')->unique()->values()->toArray();

        $availableColors = Product::where('is_active', 1)
            ->where('category_id', $selectedCategory)
            ->with('variants.attributeValues.attribute')
            ->get()
            ->flatMap(fn($p) => $p->variants->pluck('attributeValues')->flatten(1))
            ->filter(fn($av) => $av->attribute->slug === 'color')
            ->pluck('hex')->filter()->unique()->values()->toArray();

        $availableBrands = Brand::where('is_active', 1)
            ->whereHas('products', fn($q) => $q->where('is_active', 1)->where('category_id', $selectedCategory))
            ->withCount(['products' => fn($q) => $q->where('is_active', 1)])
            ->get()
            ->pluck('name', 'id')
            ->toArray();

        // 12. Return view giống index()
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

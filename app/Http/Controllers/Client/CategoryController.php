<?php

namespace App\Http\Controllers\Client;

use App\Models\Brand;
use Illuminate\Http\Request;
use App\Models\Admin\Product;
use App\Models\Admin\Category;
use App\Http\Controllers\Controller;
use App\Models\Admin\AttributeValue;

class CategoryController extends Controller
{
    /**
     * Helper function to get all descendant IDs of a category.
     *
     * @param Category $category
     * @return array
     */
    private function getDescendantIds(Category $category)
    {
        $ids = [$category->id];
        foreach ($category->children as $child) {
            $ids = array_merge($ids, $this->getDescendantIds($child));
        }
        return $ids;
    }

    public function index(Request $request)
    {
        // 1. Sidebar: load tất cả categories (cha + con)
        $categories = Category::where('is_active', 1)
            ->whereNull('parent_id')
            ->with('children')
            ->orderBy('ordinal')
            ->get();

        // 2. Đọc filter từ URL
        $min = (int)$request->query('price_min', 0);
        $max = (int)$request->query('price_max', 5000000);
        $selectedSize = $request->query('size', null);
        $selectedColor = $request->query('color', null);
        $selectedBrand = $request->query('brand', null);
        $selectedCategory = $request->query('category_id', null);
        $sort = $request->query('sort', null);

        // 3. Base product query
        $productsQuery = Product::where('is_active', 1);

        // 4. Category filter
        if ($selectedCategory) {
            $category = Category::with('children')->find($selectedCategory);
            if ($category) {
                $categoryIds = $this->getDescendantIds($category);
                $productsQuery->whereIn('category_id', $categoryIds);
            }
        }

        // 5. PRICE filter
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
                $productsQuery->orderByRaw('(SELECT COALESCE(MIN(sale_price), MIN(price)) FROM product_variants WHERE product_id = products.id) DESC');
                break;
            case 'price_asc':
                $productsQuery->orderByRaw('(SELECT COALESCE(MIN(sale_price), MIN(price)) FROM product_variants WHERE product_id = products.id) ASC');
                break;
            case 'newest':
                $productsQuery->orderBy('created_at', 'desc');
                break;
            default:
                $productsQuery->orderBy('id', 'desc');
        }

        // 10. Eager-load + paginate
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
        // Lấy danh sách sản phẩm đã được lọc theo category để build sidebar
        $filteredProductsForSidebar = Product::where('is_active', 1)
            ->when($selectedCategory, function ($query) use ($selectedCategory) {
                $category = Category::with('children')->find($selectedCategory);
                if ($category) {
                    $categoryIds = $this->getDescendantIds($category);
                    $query->whereIn('category_id', $categoryIds);
                }
            })
            ->get();

        $availableSizes = $filteredProductsForSidebar
            ->flatMap(fn($p) => $p->variants->pluck('attributeValues')->flatten(1))
            ->filter(fn($av) => $av->attribute->slug === 'size')
            ->pluck('value')->unique()->values()->toArray();

        $availableColors = $filteredProductsForSidebar
            ->flatMap(fn($p) => $p->variants->pluck('attributeValues')->flatten(1))
            ->filter(fn($av) => $av->attribute->slug === 'color')
            ->pluck('hex')->filter()->unique()->values()->toArray();

        $availableBrands = Brand::where('is_active', 1)
            ->whereHas('products', function ($q) use ($selectedCategory) {
                $q->where('is_active', 1);
                if ($selectedCategory) {
                    $category = Category::with('children')->find($selectedCategory);
                    if ($category) {
                        $categoryIds = $this->getDescendantIds($category);
                        $q->whereIn('category_id', $categoryIds);
                    }
                }
            })
            ->withCount(['products' => fn($q) => $q->where('is_active', 1)])
            ->get()
            ->pluck('name', 'id')
            ->toArray();

        // Đã sửa: Khởi tạo activeFilters VÀ LẤY TÊN DANH MỤC Ở ĐÂY
        $activeFilters = [];
        if ($selectedCategory) {
            $filteredCategory = Category::find($selectedCategory);
            if ($filteredCategory) {
                $activeFilters['category_id'] = $filteredCategory->name;
            }
        }

        if ($selectedBrand) {
            $activeFilters['brand'] = $availableBrands[$selectedBrand] ?? $selectedBrand;
        }

        if ($selectedSize) {
            $activeFilters['size'] = $selectedSize;
        }

        if ($selectedColor) {
            $colorValue = AttributeValue::where('hex', $selectedColor)
                ->whereHas('attribute', fn($q) => $q->where('slug', 'color'))
                ->value('value');
            $activeFilters['color'] = $colorValue ?? $selectedColor;
        }

        if (request('price_min') || request('price_max')) {
            $activeFilters['price'] = number_format(request('price_min', $min), 0, ',', '.') . '₫ - ' . number_format(request('price_max', $max), 0, ',', '.') . '₫';
        }

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
            'sort',
            'activeFilters' // Thêm biến activeFilters vào compact
        ));
    }
}

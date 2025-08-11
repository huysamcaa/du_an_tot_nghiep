<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Product;
use App\Models\Admin\Category;
use App\Models\Admin\ProductVariant;
use App\Models\Admin\Attribute;
use App\Models\Admin\AttributeValue;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\Shared\OrderItem;

class ProductController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        $products = Product::where('is_active', 1)
            ->with(['variants', 'categories', 'brand'])
            ->withCount(['variants as total_stock' => function ($query) {
                $query->select(DB::raw('SUM(stock)'));
            }])
            ->orderBy('created_at', 'desc')
            ->get();
        return view('admin.products.index', compact('products', 'categories'));
    }
    public function create()
    {
        $categories = Category::all();
        $attributes = Attribute::with('attributeValues')->where('is_active', 1)->get();
        $brands = Brand::where('is_active', 1)->get(); // Lấy danh sách brand đang hoạt động
        return view('admin.products.create', compact('attributes', 'categories', 'brands'));
    }
    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',

            'name' => 'required|string|max:255|unique:products,name',
            'short_description' => 'required|string',
            'description' => 'required|string',

            'thumbnail' => 'required|image|max:2048',
            // 'price' => 'required|numeric',
            'sale_price' => 'nullable|numeric',
            'sale_price_start_at' => 'nullable|date',
            'sale_price_end_at' => 'nullable|date|after_or_equal:sale_price_start_at',
            'is_sale' => 'boolean',
            'is_active' => 'boolean',
            'variants' => 'required_if:has_variants,true|array',
            'variants.*.attribute_id' => 'required|exists:attributes,id',
            'variants.*.attribute_value_id' => 'required|exists:attribute_values,id',
            'variants.*.price' => 'required|numeric',
            'variants.*.sku' => 'nullable|string|max:255',
            'variants.*.thumbnail' => 'nullable|image',
        ]);

        // Xử lý checkbox
        $data['is_sale'] = $request->has('is_sale');
        $data['is_active'] = $request->has('is_active');

        // Upload ảnh chính
        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('uploads/products', 'public');
        }

        // Tạo sản phẩm
        $product = Product::create($data);
        // Xử lý hình ảnh chi tiết
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                if ($image->isValid()) {
                    $path = $image->store('uploads/images', 'public');
                    $product->galleries()->create([
                        'image' => $path,
                    ]);
                }
            }
        }
        // Tạo các biến thể
        if ($request->has('variants')) {
            foreach ($request->variants as $variantData) {
                $variant = new ProductVariant([
                    'price' => $variantData['price'],
                    'stock' => $variantData['stock'],
                    'sku' => $variantData['sku'] ?? null,
                ]);

                // Upload ảnh biến thể nếu có
                if (isset($variantData['thumbnail']) && $variantData['thumbnail'] instanceof \Illuminate\Http\UploadedFile && $variantData['thumbnail']->isValid()) {
                    $variant->thumbnail = $variantData['thumbnail']->store('uploads/variants', 'public');
                } else {
                    $variant->thumbnail = $product->thumbnail;
                }

                // Lưu biến thể
                $product->variants()->save($variant);

                // Gán giá trị thuộc tính cho sản phẩm (nếu có)
                if ($request->has('attribute_value_id')) {
                    $product->attributeValues()->sync($request->input('attribute_value_id'));
                }

                // $variantData['attribute_value_id'] là mảng các id
                if (isset($variantData['attribute_value_id'])) {
                    $variant->attributeValues()->attach($variantData['attribute_value_id']);
                }
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }
        public function edit(Product $product)
        {
            $categories = Category::all();
            $brands = Brand::where('is_active', 1)->get(); // Thêm dòng này
            $product->load(['variants.attributeValues']);
            $colors = AttributeValue::whereHas('attribute', function ($q) {
                $q->where('slug', 'color');
            })->get();

            $sizes = AttributeValue::whereHas('attribute', function ($q) {
                $q->where('slug', 'size');
            })->get();

            return view('admin.products.edit', compact('product', 'colors', 'sizes', 'categories', 'brands'));
        }

        public function update(Request $request, $id)
{
    $product = Product::findOrFail($id);

    // Validate sản phẩm
    $request->validate([
        'name' => 'required|string|max:255',
        'category_id' => 'required|exists:categories,id',
        'brand_id' => 'nullable|exists:brands,id',
        'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        'is_active' => 'nullable|boolean',
    ]);

    // Cập nhật sản phẩm
    $product->name = $request->name;
    $product->category_id = $request->category_id;
    $product->brand_id = $request->brand_id;
    $product->short_description = $request->short_description;
    $product->description = $request->description;
    $product->is_active = $request->has('is_active') ? 1 : 0;
    $product->is_sale = $request->has('is_sale') ? 1 : 0;
    $product->sale_price_start_at = $request->sale_price_start_at;
    $product->sale_price_end_at = $request->sale_price_end_at;

    // Upload ảnh đại diện sản phẩm
    if ($request->hasFile('thumbnail')) {
        if ($product->thumbnail) {
            Storage::disk('public')->delete($product->thumbnail);
        }
        $product->thumbnail = $request->file('thumbnail')->store('uploads/products', 'public');
    }

    $product->save();

    // ===== Xử lý biến thể =====
    if ($request->has('variants')) {
        foreach ($request->variants as $variantData) {
            // Nếu có id => cập nhật biến thể
            if (!empty($variantData['id'])) {
                $variant = ProductVariant::find($variantData['id']);
                if ($variant) {
                    $variant->price = $variantData['price'];
                    $variant->sku = $variantData['sku'] ?? $variant->sku;
                    $variant->stock = $variantData['stock'] ?? $variant->stock;
                    $variant->is_active = isset($variantData['is_active']) ? 1 : 0;

                    // Upload ảnh biến thể
                    if (!empty($variantData['thumbnail']) && $variantData['thumbnail'] instanceof \Illuminate\Http\UploadedFile) {
                        if ($variant->thumbnail && $variant->thumbnail != $product->thumbnail) {
                            Storage::disk('public')->delete($variant->thumbnail);
                        }
                        $variant->thumbnail = $variantData['thumbnail']->store('uploads/variants', 'public');
                    }

                    $variant->save();

                    // Cập nhật thuộc tính (màu + size)
                    $variant->attributeValues()->sync([
                        $variantData['color_id'],
                        $variantData['size_id']
                    ]);
                }
            }
            // Nếu không có id => thêm mới biến thể
            else {
                $variant = new ProductVariant();
                $variant->product_id = $product->id;
                $variant->price = $variantData['price'];
                $variant->sku = $variantData['sku'] ?? null;
                $variant->stock = $variantData['stock'] ?? 0;
                $variant->is_active = isset($variantData['is_active']) ? 1 : 0;

                // Upload ảnh biến thể mới
                if (!empty($variantData['thumbnail']) && $variantData['thumbnail'] instanceof \Illuminate\Http\UploadedFile) {
                    $variant->thumbnail = $variantData['thumbnail']->store('uploads/variants', 'public');
                } else {
                    $variant->thumbnail = $product->thumbnail;
                }

                $variant->save();

                $variant->attributeValues()->attach([
                    $variantData['color_id'],
                    $variantData['size_id']
                ]);
            }
        }
    }

    return redirect()->route('admin.products.index')->with('success', 'Cập nhật sản phẩm thành công');
}



    public function show(Product $product)
    {
        // Load các quan hệ cần thiết
        $product->load([
            'variants.attributeValues.attribute',
            'galleries',
            // 'orderItems.order.customer' // Load cả customer để hiển thị sau này
        ]);

        // Lấy tất cả order items của sản phẩm
        $orderItems = $product->orderItems;



        $orderStats = $product->getOrderStatusStats();

        // Nhóm thống kê theo trạng thái đơn hàng
        $orderStats = $orderItems->groupBy('order.status')
            ->map(function ($items, $status) {
                return [
                    'status' => $status,
                    'order_count' => $items->unique('order_id')->count(),
                    'total_quantity' => $items->sum('quantity'),
                    'total_revenue' => $items->sum(function ($item) {
                        return $item->price * $item->quantity;
                    })
                ];
            })->values();

        // Tính tổng các thống kê
        $totalOrders = $orderStats->sum('order_count');
        $totalSold = $orderStats->sum('total_quantity');
        $totalRevenue = $orderStats->sum('total_revenue');

        // Lấy danh sách đơn hàng gần nhất (10 đơn)
        $recentOrders = $orderItems->sortByDesc('created_at')
            ->take(10)
            ->groupBy('order_id')
            ->map(function ($items) {
                return $items->first(); // Lấy 1 item đại diện cho mỗi đơn
            });

        return view('admin.products.show', compact(
            'product',
            'orderStats',
            'totalOrders',
            'totalSold',
            'totalRevenue',
            'recentOrders'
        ));
    }

    public function destroy(Product $product)
    {
        // Xóa ảnh chính
        // if ($product->thumbnail) {
        //     Storage::disk('public')->delete($product->thumbnail);
        // }

        // // Xóa các ảnh biến thể
        // foreach ($product->variants as $variant) {
        //     if ($variant->thumbnail && $variant->thumbnail != $product->thumbnail) {
        //         Storage::disk('public')->delete($variant->thumbnail);
        //     }
        // }

        // $product->variants()->delete();
        // $product->forceDelete();
        $product->is_active = 0;
        $product->save();
        return redirect()->route('admin.products.index')->with('success', 'Sản phẩm đã được ẩn.');
    }
    /**
     * Tạo SKU tự động cho biến thể
     */
    protected function generateVariantSku(Product $product, array $variantData)
    {
        $color = AttributeValue::find($variantData['color_id']);
        $size = AttributeValue::find($variantData['size_id']);

        $productSku = $product->sku ?: substr(strtoupper(preg_replace('/[^a-z0-9]/i', '', $product->name)), 0, 3);
        $colorCode = substr(strtoupper($color->value), 0, 3);
        $sizeCode = $size->value;

        return $productSku . '-' . $colorCode . '-' . $sizeCode;
    }
    public function adminListByCategory($id)
    {
        $category = Category::findOrFail($id);
        $products = Product::whereHas('categories', function ($query) use ($id) {
            $query->where('categories.id', $id);
        })->with('variants')->get();

        $categories = Category::all();
        return view('admin.products.index', compact('category', 'products', 'categories'));
    }
    // Hiển thị danh sách sản phẩm đã xóa mềm
    public function trashed()
    {
        $products = Product::where('is_active', 0)->get();
        return view('admin.products.trashed', compact('products'));
    }

    // Khôi phục sản phẩm
    public function restore(Product $product)
    {
        $product->is_active = 1;
        $product->save();
        return redirect()->route('admin.products.trashed')->with('success', 'Sản phẩm đã được khôi phục!');
    }

    // Xóa cứng sản phẩm (chỉ khi chưa từng có trong giỏ hàng)
    public function forceDelete($id)
    {
        $product = Product::findOrFail($id);

        // Nếu sản phẩm đã từng có trong đơn hàng, không cho xóa cứng
        if ($product->orderItems()->exists()) {
            return redirect()->back()->with('error', 'Không thể xóa cứng sản phẩm đã có trong đơn hàng!');
        }

        // Xóa ảnh chính nếu có
        if ($product->thumbnail) {
            Storage::disk('public')->delete($product->thumbnail);
        }
        // Xóa các ảnh biến thể
        foreach ($product->variants as $variant) {
            if ($variant->thumbnail && $variant->thumbnail != $product->thumbnail) {
                Storage::disk('public')->delete($product->thumbnail);
            }
        }
        $product->variants()->delete();
        $product->delete();

        return redirect()->route('admin.products.trashed')->with('success', 'Đã xóa vĩnh viễn sản phẩm!');
    }
}

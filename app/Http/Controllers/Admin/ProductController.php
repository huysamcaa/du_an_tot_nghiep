<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Attribute;
use App\Models\Admin\AttributeValue;
use App\Models\Admin\Category;
use App\Models\Admin\Product;
use App\Models\Admin\ProductVariant;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // Lấy tham số từ request
        $perPage = $request->input('perPage', 10);
        $search = $request->input('search');
        $categoryId = $request->input('category_id');
        $brandId = $request->input('brand_id');

        $query = Product::with(['category', 'brand'])
            ->withCount([
                'variants as total_stock' => function ($q) {
                    $q->select(DB::raw('SUM(stock)'));
                }
            ]);

        // Áp dụng tìm kiếm nếu có
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhereHas('category', function ($cat) use ($search) {
                        $cat->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('brand', function ($brand) use ($search) {
                        $brand->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Áp dụng lọc theo danh mục nếu có
        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        // Áp dụng lọc theo thương hiệu nếu có
        if ($brandId) {
            $query->where('brand_id', $brandId);
        }

        $products = $query->orderByDesc('created_at')->paginate($perPage)->withQueryString();

        // Lấy danh sách danh mục và thương hiệu để truyền sang view
        $categories = Category::orderBy('name')->get();
        $brands = Brand::orderBy('name')->get();

        return view('admin.products.index', compact('products', 'categories', 'brands'));
    }

    public function create()
    {
        $categories = Category::all();
        $attributes = Attribute::with('attributeValues')->where('is_active', 1)->get();
        $brands     = Brand::where('is_active', 1)->get(); // Lấy danh sách brand đang hoạt động
        return view('admin.products.create', compact('attributes', 'categories', 'brands'));
    }
    public function store(Request $request)
{
    $data = $request->validate([
        'category_id'                   => 'required|exists:categories,id',
        'brand_id'                      => 'required|exists:brands,id',

        'name'                          => 'required|string|max:255|unique:products,name',
        'short_description'             => 'required|string',
        'description'                   => 'required|string',

        'thumbnail'                     => 'required|image|max:2048',
        'is_active'                     => 'boolean',
        'has_variants'                  => 'boolean', // Thêm trường này nếu bạn muốn kiểm soát việc có biến thể hay không từ form

        'variants'                      => 'required_if:has_variants,true|array',
        'variants.*.attribute_id'       => 'required|exists:attributes,id',
        'variants.*.attribute_value_id' => 'required|exists:attribute_values,id',
        'variants.*.price'              => 'required|numeric|min:0', // Đảm bảo giá không âm
        'variants.*.stock'              => 'required|integer|min:0', // Thêm validation cho 'stock'
        'variants.*.sku'                => 'nullable|string|max:255|unique:product_variants,sku', // sku có thể là duy nhất
        'variants.*.thumbnail'          => 'nullable|image|max:2048', // Validation riêng cho thumbnail biến thể
        'variants.*.sale_price'         => 'nullable|numeric|lt:variants.*.price', // Giá sale phải nhỏ hơn giá gốc
        'variants.*.sale_price_start_at' => 'nullable|date',
        'variants.*.sale_price_end_at'  => 'nullable|date|after_or_equal:variants.*.sale_price_start_at',
        'variants.*.is_sale'            => 'boolean',
    ]);

    // Xử lý checkbox cho sản phẩm chính
    $data['is_active'] = $request->has('is_active');
    $data['has_variants'] = $request->has('has_variants'); // Lấy giá trị của has_variants

    // Upload ảnh chính
    if ($request->hasFile('thumbnail')) {
        $data['thumbnail'] = $request->file('thumbnail')->store('uploads/products', 'public');
    }

    // Tạo sản phẩm
    $product = Product::create($data);

    // Xử lý hình ảnh chi tiết (Galleries)
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
    if ($request->has('variants') && $data['has_variants']) { // Chỉ tạo biến thể nếu has_variants là true
        foreach ($request->variants as $variantData) {
            $variant = new ProductVariant([
                'price'               => $variantData['price'],
                'stock'               => $variantData['stock'], // Gán giá trị 'stock'
                'sku'                 => $variantData['sku'] ?? null,
                'sale_price'          => $variantData['sale_price'] ?? null,
                'sale_price_start_at' => $variantData['sale_price_start_at'] ?? null,
                'sale_price_end_at'   => $variantData['sale_price_end_at'] ?? null,
                'is_sale'             => isset($variantData['is_sale']) && $variantData['is_sale'], // Xử lý checkbox is_sale cho biến thể
            ]);

            // Upload ảnh biến thể nếu có, nếu không thì dùng ảnh chính của sản phẩm
            if (isset($variantData['thumbnail']) && $variantData['thumbnail'] instanceof \Illuminate\Http\UploadedFile && $variantData['thumbnail']->isValid()) {
                $variant->thumbnail = $variantData['thumbnail']->store('uploads/variants', 'public');
            } else {
                $variant->thumbnail = $product->thumbnail; // Mặc định dùng ảnh chính của sản phẩm
            }

            // Lưu biến thể
            $product->variants()->save($variant);

            // Gán giá trị thuộc tính cho biến thể
            // Đây là nơi bạn attach các attribute_value_id cho từng biến thể
            if (isset($variantData['attribute_value_id'])) {
                // Đảm bảo rằng attribute_value_id có thể là một mảng hoặc một giá trị đơn lẻ tùy thuộc vào cấu trúc của bạn.
                // Nếu là một mảng:
                if (is_array($variantData['attribute_value_id'])) {
                    $variant->attributeValues()->attach($variantData['attribute_value_id']);
                } else {
                    $variant->attributeValues()->attach([$variantData['attribute_value_id']]);
                }
            }
        }
    }

    return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
}
    public function edit(Product $product)
    {
        $categories = Category::all();
        $brands     = Brand::where('is_active', 1)->get(); // Thêm dòng này
        $product->load(['variants.attributeValues']);
        $colors = AttributeValue::whereHas('attribute', function ($q) {
            $q->where('slug', 'color');
        })->get();

        $sizes = AttributeValue::whereHas('attribute', function ($q) {
            $q->where('slug', 'size');
        })->get();

        return view('admin.products.edit', compact('product', 'colors', 'sizes', 'categories', 'brands'));
    }

    public function update(Request $request, Product $product)
    {
        // Eager load relationships for the check
        $product->load(['cartItems', 'orderItems']);

        // Prevent product name change if it exists in any cart or order
        if (
            $request->name !== $product->name &&
            ($product->cartItems()->exists() || $product->orderItems()->exists())
        ) {
            return redirect()->back()->with('error', 'Không thể đổi tên sản phẩm đã có trong giỏ hàng hoặc đơn hàng!');
        }

        // Validate incoming data
        $validatedData = $request->validate([
            'category_id'           => 'required|exists:categories,id',
            'brand_id'              => 'required|exists:brands,id',
            'name'                  => 'required|string|max:255|unique:products,name,' . $product->id,
            'short_description'     => 'required|string',
            'description'           => 'required|string',
            'thumbnail'             => 'nullable|image|max:2048',
            'is_sale'               => 'boolean',
            'is_active'             => 'boolean',
            'sale_price_start_at'   => 'nullable|date',
            'sale_price_end_at'     => 'nullable|date|after_or_equal:sale_price_start_at',

            // Validation for existing variants
            'variants'              => 'nullable|array',
            'variants.*.price'      => 'required_with:variants|numeric|min:0',
            'variants.*.stock'      => 'required_with:variants|integer|min:0',
            'variants.*.sku'        => 'nullable|string|unique:product_variants,sku,' . implode(',', array_keys($request->input('variants', []))) . ',id', // Corrected unique rule
            'variants.*.thumbnail'  => 'nullable|image|max:2048',
            'variants.*.is_active'  => 'boolean',
            'variants.*.is_sale'    => 'boolean',
            'variants.*.sale_price_start_at' => 'nullable|date',
            'variants.*.sale_price_end_at' => 'nullable|date|after_or_equal:variants.*.sale_price_start_at',
            'variants.*.delete'     => 'nullable|boolean', // For soft delete or marking for deletion

            // Validation for new variants
            'new_variants'          => 'nullable|array',
            'new_variants.*.color_id' => 'nullable|exists:attribute_values,id',
            'new_variants.*.size_id' => 'nullable|exists:attribute_values,id',
            'new_variants.*.price'  => 'required_with:new_variants|numeric|min:0',
            'new_variants.*.stock'  => 'required_with:new_variants|integer|min:0',
            'new_variants.*.thumbnail' => 'nullable|image|max:2048',
            'new_variants.*.sku'    => 'nullable|string|unique:product_variants,sku',
            'new_variants.*.is_sale' => 'boolean',
            'new_variants.*.sale_price_start_at' => 'nullable|date',
            'new_variants.*.sale_price_end_at' => 'nullable|date|after_or_equal:new_variants.*.sale_price_start_at',
        ]);

        // Process main product data
        $productData = [
            'category_id'       => $validatedData['category_id'],
            'brand_id'          => $validatedData['brand_id'],
            'name'              => $validatedData['name'],
            'short_description' => $validatedData['short_description'],
            'description'       => $validatedData['description'],
            // Checkboxes are correctly handled by Laravel's boolean validation if present.
            // If they are not present (checkbox unticked), their values won't be in $validatedData.
            // So, we explicitly set them based on request->has()
            'is_sale'           => $request->has('is_sale'),
            'is_active'         => $request->has('is_active'),
            'sale_price_start_at' => $validatedData['sale_price_start_at'] ?? null,
            'sale_price_end_at'   => $validatedData['sale_price_end_at'] ?? null,
        ];

        // Handle main product thumbnail upload
        if ($request->hasFile('thumbnail')) {
            if ($product->thumbnail) {
                Storage::disk('public')->delete($product->thumbnail);
            }
            $productData['thumbnail'] = $request->file('thumbnail')->store('uploads/products', 'public');
        }

        // Update product information
        $product->update($productData);

        // Handle product gallery images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $imageFile) {
                $path = $imageFile->store('uploads/gallery', 'public');
                $product->images()->create(['path' => $path]);
            }
        }

        // --- VARIANT HANDLING LOGIC ---

        // 1. Update existing variants and mark for deletion
        if ($request->has('variants')) {
            $existingVariantIds = [];
            foreach ($request->variants as $id => $variantData) {
                $variant = $product->variants()->find($id);

                if ($variant) {
                    // Handle variant deletion
                    if (isset($variantData['delete']) && (bool)$variantData['delete']) {
                        if ($variant->thumbnail && $variant->thumbnail != $product->thumbnail) {
                            Storage::disk('public')->delete($variant->thumbnail);
                        }
                        $variant->delete();
                        continue; // Skip further processing for this deleted variant
                    }

                    // Prepare variant data for update
                    $variantUpdateData = [
                        'price'               => $variantData['price'],
                        'sku'                 => $variantData['sku'] ?? null,
                        'stock'               => $variantData['stock'] ?? 0,
                        'is_active'           => isset($variantData['is_active']) ? 1 : 0,
                        // **CORRECTED:** Get sale fields from variantData, not general request
                        'is_sale'             => isset($variantData['is_sale']) ? 1 : 0,
                        'sale_price_start_at' => $variantData['sale_price_start_at'] ?? null,
                        'sale_price_end_at'   => $variantData['sale_price_end_at'] ?? null,
                    ];

                    // Handle variant thumbnail upload
                    if ($request->hasFile("variants.{$id}.thumbnail")) {
                        if ($variant->thumbnail && $variant->thumbnail != $product->thumbnail) {
                            Storage::disk('public')->delete($variant->thumbnail);
                        }
                        $variantUpdateData['thumbnail'] = $request->file("variants.{$id}.thumbnail")->store('uploads/variants', 'public');
                    }

                    // Update variant information
                    $variant->update($variantUpdateData);
                    $existingVariantIds[] = $id;
                }
            }
            // Delete variants that were removed from the form (not in existingVariantIds)
            $product->variants()->whereNotIn('id', $existingVariantIds)->delete();
        } else {
            // If no 'variants' array is sent, delete all existing variants for the product
            $product->variants()->delete();
        }

        // 2. Add new variants
        if ($request->has('new_variants')) {
            foreach ($request->new_variants as $manualIndex => $newVariantData) {
                // Ensure price and stock are present, otherwise skip
                if (empty($newVariantData['price']) && empty($newVariantData['stock'])) {
                    continue;
                }

                // **CORRECTED:** Removed array_filter here to prevent loss of 0 or false values
                $newVariant = new ProductVariant([
                    'price'     => $newVariantData['price'] ?? 0,
                    'stock'     => $newVariantData['stock'] ?? 0,
                    'sku'       => $newVariantData['sku'] ?? null,
                    'is_active' => isset($newVariantData['is_active']) ? 1 : 0, // Should be based on new variant's data
                    'is_sale'   => isset($newVariantData['is_sale']) ? 1 : 0, // Based on new variant's data
                    'sale_price_start_at' => $newVariantData['sale_price_start_at'] ?? null,
                    'sale_price_end_at'   => $newVariantData['sale_price_end_at'] ?? null,
                ]);

                // Handle thumbnail for new variant
                if ($request->hasFile("new_variants.{$manualIndex}.thumbnail")) {
                    $newVariant->thumbnail = $request->file("new_variants.{$manualIndex}.thumbnail")->store('uploads/variants', 'public');
                } else {
                    $newVariant->thumbnail = $product->thumbnail; // Default to product thumbnail if none provided
                }

                $product->variants()->save($newVariant);

                // Attach attributes (color and size) to the new variant
                $attributeValues = [];
                if (!empty($newVariantData['color_id'])) {
                    $attributeValues[] = $newVariantData['color_id'];
                }
                if (!empty($newVariantData['size_id'])) {
                    $attributeValues[] = $newVariantData['size_id'];
                }
                if (!empty($attributeValues)) {
                    $newVariant->attributeValues()->attach($attributeValues);
                }
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Cập nhật sản phẩm thành công!');
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
                    'status'         => $status,
                    'order_count'    => $items->unique('order_id')->count(),
                    'total_quantity' => $items->sum('quantity'),
                    'total_revenue'  => $items->sum(function ($item) {
                        return $item->price * $item->quantity;
                    }),
                ];
            })->values();

        // Tính tổng các thống kê
        $totalOrders  = $orderStats->sum('order_count');
        $totalSold    = $orderStats->sum('total_quantity');
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
        $size  = AttributeValue::find($variantData['size_id']);

        $productSku = $product->sku ?: substr(strtoupper(preg_replace('/[^a-z0-9]/i', '', $product->name)), 0, 3);
        $colorCode  = substr(strtoupper($color->value), 0, 3);
        $sizeCode   = $size->value;

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
            return redirect()->back()->with('error', 'Không thể xóa sản phẩm đã có trong đơn hàng!');
        }

        if ($product->stock > 0) {
            return redirect()->back()->with('error', 'Không thể xóa sản phẩm  vẫn còn số lượng!');
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

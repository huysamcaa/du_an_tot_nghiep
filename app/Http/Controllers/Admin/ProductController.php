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
        $categories = Category::whereDoesntHave('children')->get();
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
            // 'price' => 'required|numeric',
            'sale_price'                    => 'nullable|numeric',
            'sale_price_start_at'           => 'nullable|date',
            'sale_price_end_at'             => 'nullable|date|after_or_equal:sale_price_start_at',
            'is_sale'                       => 'boolean',
            'is_active'                     => 'boolean',
            'variants'                      => 'required_if:has_variants,true|array',
            'variants.*.attribute_id'       => 'required|exists:attributes,id',
            'variants.*.attribute_value_id' => 'required|exists:attribute_values,id',
            'variants.*.price'              => 'required|numeric',
            'variants.*.thumbnail'          => 'nullable|image',
        ]);

        // Xử lý checkbox
        $data['is_sale']   = $request->has('is_sale');
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
                    'sku'   => $variantData['sku'] ?? null,
                    'is_active' => $variantData['is_active'] ?? 1,

                ]);

                // Upload ảnh biến thể nếu có
                if (isset($variantData['thumbnail']) && $variantData['thumbnail'] instanceof \Illuminate\Http\UploadedFile  && $variantData['thumbnail']->isValid()) {
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
        $categories = Category::whereDoesntHave('children')->get();
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
    $product->load(['cartItems', 'orderItems']);

    // Ngăn đổi tên nếu đã có trong giỏ hoặc đơn hàng
    if (
        $request->name !== $product->name &&
        ($product->cartItems()->exists() || $product->orderItems()->exists())
    ) {
        return redirect()->back()->with('error', 'Không thể đổi tên sản phẩm đã có trong giỏ hàng hoặc đơn hàng!');
    }

    // Validate dữ liệu
    $data = $request->validate([
        'category_id'           => 'required|exists:categories,id',
        'brand_id'              => 'required|exists:brands,id',
        'name'                  => 'required|string|max:255|unique:products,name,' . $product->id,
        'short_description'     => 'required|string',
        'description'           => 'required|string',
        'thumbnail'             => 'nullable|image|max:2048',
        'is_sale'               => 'boolean',
        'is_active'             => 'boolean',

        // Validation cho biến thể cũ
        'variants'              => 'nullable|array',
        'variants.*.price'      => 'required_with:variants|numeric|min:0',
        'variants.*.stock'      => 'required_with:variants|integer|min:0',
        // 'variants.*.sku'        => 'nullable|string|unique:product_variants,sku,' . implode(',', array_keys($request->input('variants', []))),
        'variants.*.thumbnail'  => 'nullable|image|max:2048',
        'variants.*.is_active'  => 'boolean',

        // Validation cho biến thể mới
        'new_variants'          => 'nullable|array',
        'new_variants.*.color_id'  => 'nullable|exists:attribute_values,id',
        'new_variants.*.size_id'   => 'nullable|exists:attribute_values,id',
        'new_variants.*.price'     => 'required_with:new_variants|numeric|min:0',
        'new_variants.*.stock'     => 'required_with:new_variants|integer|min:0',
        'new_variants.*.thumbnail' => 'nullable|image|max:2048',
        'new_variants.*.sku'       => 'nullable|string|unique:product_variants,sku',

        'variants.*.sale_price'           => 'nullable|numeric|min:0',
        'variants.*.sale_price_start_at'  => 'nullable|date',
        'variants.*.sale_price_end_at'    => 'nullable|date|after_or_equal:variants.*.sale_price_start_at',
        'variants.*.is_sale'              => 'boolean',
    ]);

     $categoryId = $request->input('category_id');
    $category = Category::find($categoryId);

    if ($category->hasChildren()) {
        return redirect()->back()->withInput()->with('error', 'Không thể gán sản phẩm vào danh mục cha có danh mục con.');
    }

    // Xử lý các checkbox
    $data['is_sale']   = $request->has('is_sale');
    $data['is_active'] = $request->has('is_active');

    // Tải ảnh đại diện mới nếu có
    if ($request->hasFile('thumbnail')) {
        if ($product->thumbnail) {
            Storage::disk('public')->delete($product->thumbnail);
        }
        $data['thumbnail'] = $request->file('thumbnail')->store('uploads/products', 'public');
    }

    // Cập nhật thông tin sản phẩm
    $product->update($data);

    // Xử lý bộ sưu tập ảnh (gallery)
    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $imageFile) {
            $path = $imageFile->store('uploads/gallery', 'public');
            $product->galleries()->create(['image' => $path]);
        }
    }

    // --- LOGIC XỬ LÝ BIẾN THỂ ---

    // 1. Cập nhật biến thể hiện có
    if ($request->has('variants')) {
        $existingVariantIds = [];
        foreach ($request->variants as $id => $variantData) {
            $variant = $product->variants()->find($id);

            // Xử lý xóa biến thể
            if ($variant && isset($variantData['delete'])) {
                if ($variant->thumbnail && $variant->thumbnail != $product->thumbnail) {
                    Storage::disk('public')->delete($variant->thumbnail);
                }
                $variant->delete();
                continue; // Bỏ qua các thao tác cập nhật khác cho biến thể này
            }

            // Cập nhật thông tin
            if ($variant) {
                $variant->update([
                    'price'     => $variantData['price'],
                    
                    'stock'     => $variantData['stock'] ?? 0,
                    'is_active' => isset($variantData['is_active']) ? 1 : 0,
                    'sale_price' => $variantData['sale_price'] ?? null,
                    'sale_price_start_at' => $variantData['sale_price_start_at'] ?? null,
                    'sale_price_end_at' => $variantData['sale_price_end_at'] ?? null,
                    'is_sale'              => isset($variantData['is_sale']) ? 1 : 0,
                ]);

                // Xử lý ảnh biến thể
                if ($request->hasFile("variants.{$id}.thumbnail")) {
                    if ($variant->thumbnail && $variant->thumbnail != $product->thumbnail) {
                        Storage::disk('public')->delete($variant->thumbnail);
                    }
                    $variant->thumbnail = $request->file("variants.{$id}.thumbnail")->store('uploads/variants', 'public');
                    $variant->save();
                }

                $existingVariantIds[] = $id;
            }
        }
        // Xóa các biến thể đã bị loại bỏ khỏi form
        $product->variants()->whereNotIn('id', $existingVariantIds)->delete();
    } else {
        // Nếu không có variants nào được gửi lên, xóa hết biến thể cũ
        $product->variants()->delete();
    }


    // 2. Thêm biến thể mới
    if ($request->has('new_variants')) {
        foreach ($request->new_variants as $newVariantData) {
            // Lọc bỏ các trường rỗng để tránh lưu giá trị null/rỗng
            $newVariantData = array_filter($newVariantData);

            // Bỏ qua nếu không có đủ dữ liệu cần thiết
            if (empty($newVariantData['price']) && empty($newVariantData['stock'])) {
                continue;
            }

            $newVariant = new ProductVariant([
                'price'     => $newVariantData['price'] ?? 0,
                'stock'     => $newVariantData['stock'] ?? 0,
                'sku'       => $newVariantData['sku'] ?? null,
                'is_active' => true, // Mặc định là active khi tạo mới
            ]);

            // Tải ảnh cho biến thể mới
            if (!empty($newVariantData['thumbnail']) && $newVariantData['thumbnail'] instanceof \Illuminate\Http\UploadedFile) {
                $newVariant->thumbnail = $newVariantData['thumbnail']->store('uploads/variants', 'public');
            } else {
                $newVariant->thumbnail = $product->thumbnail;
            }

            $product->variants()->save($newVariant);

            // Gắn thuộc tính (màu và size) cho biến thể mới
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
    
    // Tính tổng stock từ các biến thể
    $totalStock = $product->variants->sum('stock');

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
        'totalStock', // Thêm biến totalStock vào compact
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

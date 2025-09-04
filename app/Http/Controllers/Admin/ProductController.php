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
        // Validate dữ liệu
        $data = $request->validate([
            'category_id'           => 'required|exists:categories,id',
            'brand_id'              => 'required|exists:brands,id',
            'name'                  => 'required|string|max:255|unique:products,name',
            'short_description'     => 'required|string',
            'description'           => 'required|string',
            'thumbnail'             => 'required|image|max:2048',
            'is_sale'               => 'boolean',
            'is_active'             => 'boolean',

            // Validation cho biến thể
            'variants'                      => 'required|array|min:1',
            'variants.*.attribute_value_id' => 'required|exists:attribute_values,id',
            'variants.*.price'              => 'required|numeric',
            'variants.*.stock'              => 'required|integer|min:0',
            'variants.*.thumbnail'          => 'nullable|image',
            'variants.*.is_active'          => 'boolean',
            'variants.*.is_sale'            => 'boolean',
            'variants.*.sale_price'         => 'nullable|numeric|min:0|lt:variants.*.price',
            'variants.*.sale_price_start_at' => 'nullable|date',
            'variants.*.sale_price_end_at'  => 'nullable|date|after_or_equal:variants.*.sale_price_start_at',

            'images' => 'nullable|array',
            'images.*' => 'image|max:2048',
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

        // Xử lý bộ sưu tập ảnh (gallery)
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $imageFile) {
                $path = $imageFile->store('uploads/gallery', 'public');
                $product->galleries()->create(['image' => $path]);
            }
        }

        // Tạo các biến thể và gắn thuộc tính
        if ($request->has('variants')) {
            foreach ($request->variants as $variantData) {
                // Xử lý is_sale
                $isSale = $variantData['is_sale'] ?? false;

                // Xử lý ảnh biến thể
                $variantThumbnail = null;
                if (isset($variantData['thumbnail']) && $variantData['thumbnail'] instanceof \Illuminate\Http\UploadedFile) {
                    $variantThumbnail = $variantData['thumbnail']->store('uploads/variants', 'public');
                } else {
                    $variantThumbnail = $product->thumbnail;
                }

                // Tạo biến thể
                $variant = $product->variants()->create([
                    'price'                 => $variantData['price'],
                    'stock'                 => $variantData['stock'],
                    'is_active'             => $variantData['is_active'] ?? 1,
                    'thumbnail'             => $variantThumbnail,
                    'is_sale'               => $isSale,
                    'sale_price'            => $isSale ? ($variantData['sale_price'] ?? null) : null,
                    'sale_price_start_at'   => $isSale ? ($variantData['sale_price_start_at'] ?? null) : null,
                    'sale_price_end_at'     => $isSale ? ($variantData['sale_price_end_at'] ?? null) : null,
                ]);

                // Gắn thuộc tính (màu và size)
                if (isset($variantData['attribute_value_id'])) {
                    $variant->attributeValues()->attach($variantData['attribute_value_id']);
                }
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Thêm sản phẩm thành công.');
    }

    public function edit(Product $product)
    {
        $categories = Category::whereDoesntHave('children')->get();
        $brands     = Brand::where('is_active', 1)->get();
        // Lấy tất cả các thuộc tính đang hoạt động để sử dụng trong form
        $attributes = Attribute::with('attributeValues')->where('is_active', 1)->get();

        $product->load(['variants.attributeValues', 'galleries']);

        return view('admin.products.edit', compact('product', 'attributes', 'categories', 'brands'));
    }

    public function update(Request $request, Product $product)
    {
        $product->load(['cartItems', 'orderItems']);

        // // Ngăn đổi tên nếu đã có trong giỏ hoặc đơn hàng
        // if (
        //     $request->name !== $product->name &&
        //     ($product->cartItems()->exists() || $product->orderItems()->exists())
        // ) {
        //     return redirect()->back()->with('error', 'Không thể đổi tên sản phẩm đã có trong giỏ hàng hoặc đơn hàng!');
        // }

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
            'variants'                      => 'nullable|array',
            'variants.*.price'              => 'required_with:variants|numeric|min:0',
            'variants.*.stock'              => 'required:variants|integer|min:0',
            'variants.*.thumbnail'          => 'nullable|image|max:2048',
            'variants.*.is_active'          => 'boolean',
            'variants.*.sale_price'         => 'nullable|numeric|min:0',
            'variants.*.is_sale'            => 'boolean',
            'variants.*.sale_price_start_at' => 'nullable|date',
            'variants.*.sale_price_end_at'  => 'nullable|date|after_or_equal:variants.*.sale_price_start_at',

            // Validation cho biến thể mới
            // Validation cho biến thể mới
            'new_variants'                          => 'nullable|array',
            'new_variants.*.price'                  => 'required_with:new_variants|numeric|min:0',
            'new_variants.*.stock'                  => 'required_with:new_variants|integer|min:0',
            'new_variants.*.thumbnail'              => 'nullable|image|max:2048',
            'new_variants.*.is_sale'                => 'boolean',
            'new_variants.*.sale_price'             => 'nullable|numeric|min:0',
            'new_variants.*.sale_price_start_at'    => 'nullable|date',
            'new_variants.*.sale_price_end_at'      => 'nullable|date|after_or_equal:new_variants.*.sale_price_start_at',
            'new_variants.*.attribute_values'       => 'nullable|array|min:1',
            'new_variants.*.attribute_values.*'     => 'exists:attribute_values,id',
            // Validation linh hoạt cho các thuộc tính mới
            'new_variants.*.attribute_values'       => 'nullable|array',
            'new_variants.*.attribute_values.*'     => 'exists:attribute_values,id',

            'images' => 'nullable|array',
            'images.*' => 'image|max:2048',
            'kept_images' => 'nullable|string'
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

        // --- LOGIC XỬ LÝ BỘ SƯU TẬP ẢNH (GALLERY) ---
        $keptImageIds = explode(',', $request->input('kept_images', ''));
        $keptImageIds = array_filter($keptImageIds);

        $existingImageIds = $product->galleries->pluck('id')->map(fn($id) => (string) $id)->toArray();

        $imagesToDeleteIds = array_diff($existingImageIds, $keptImageIds);

        if (!empty($imagesToDeleteIds)) {
            $galleriesToDelete = $product->galleries()->whereIn('id', $imagesToDeleteIds)->get();
            foreach ($galleriesToDelete as $gallery) {
                if (Storage::disk('public')->exists($gallery->image)) {
                    Storage::disk('public')->delete($gallery->image);
                }
                $gallery->delete();
            }
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $imageFile) {
                $path = $imageFile->store('uploads/gallery', 'public');
                $product->galleries()->create(['image' => $path]);
            }
        }

        // --- LOGIC XỬ LÝ BIẾN THỂ ---

        // 1. Cập nhật biến thể hiện có
        if ($request->has('variants')) {
            $keptVariantIds = [];
            foreach ($request->variants as $id => $variantData) {
                $variant = $product->variants()->find($id);

                if ($variant) {
                    $isSale = $variantData['is_sale'] ?? false;

                    if ($request->hasFile("variants.{$id}.thumbnail")) {
                        if ($variant->thumbnail && $variant->thumbnail != $product->thumbnail) {
                            Storage::disk('public')->delete($variant->thumbnail);
                        }
                        $variant->thumbnail = $request->file("variants.{$id}.thumbnail")->store('uploads/variants', 'public');
                    }

                    $variant->update([
                        'price'                 => $variantData['price'],
                        'stock'                 => $variantData['stock'] ?? 0,
                        'is_active'             => $variantData['is_active'] ?? 0,
                        'is_sale'               => $isSale,
                        'sale_price'            => $isSale ? ($variantData['sale_price'] ?? null) : null,
                        'sale_price_start_at'   => $isSale ? ($variantData['sale_price_start_at'] ?? null) : null,
                        'sale_price_end_at'     => $isSale ? ($variantData['sale_price_end_at'] ?? null) : null,
                    ]);

                    $keptVariantIds[] = $id;
                }
            }

            $variantsToDelete = $product->variants()->whereNotIn('id', $keptVariantIds)->get();
            foreach ($variantsToDelete as $variant) {
                if ($variant->thumbnail && $variant->thumbnail != $product->thumbnail) {
                    Storage::disk('public')->delete($variant->thumbnail);
                }
                $variant->delete();
            }
        } else {
            $variantsToDelete = $product->variants()->get();
            foreach ($variantsToDelete as $variant) {
                if ($variant->thumbnail && $variant->thumbnail != $product->thumbnail) {
                    Storage::disk('public')->delete($variant->thumbnail);
                }
                $variant->delete();
            }
        }

        // 2. Thêm biến thể mới
        // 2. Thêm biến thể mới
        if ($request->has('new_variants')) {
            // Lấy tất cả các tổ hợp thuộc tính của các biến thể hiện có
            $existingCombinations = $product->variants->map(function ($variant) {
                return $variant->attributeValues->pluck('id')->sort()->implode(',');
            });

            foreach ($request->new_variants as $newVariantData) {
                if (empty($newVariantData['price']) && empty($newVariantData['stock'])) {
                    continue;
                }

                // Sắp xếp các giá trị thuộc tính để so sánh
                $newCombination = collect($newVariantData['attribute_values'])->sort()->implode(',');

                // Kiểm tra trùng lặp
                if ($existingCombinations->contains($newCombination)) {
                    // Nếu trùng, bỏ qua và thông báo lỗi
                    return redirect()->back()->with('error', 'Không thể thêm biến thể trùng lặp. Vui lòng kiểm tra lại các biến thể hiện có.');
                }

                $isSale = $newVariantData['is_sale'] ?? false;

                // Tải ảnh cho biến thể mới
                $variantThumbnail = null;
                if (!empty($newVariantData['thumbnail']) && $newVariantData['thumbnail'] instanceof \Illuminate\Http\UploadedFile) {
                    $variantThumbnail = $newVariantData['thumbnail']->store('uploads/variants', 'public');
                } else {
                    $variantThumbnail = $product->thumbnail;
                }

                $newVariant = $product->variants()->create([
                    'price'                 => $newVariantData['price'] ?? 0,
                    'stock'                 => $newVariantData['stock'] ?? 0,
                    'is_active'             => true,
                    'thumbnail'             => $variantThumbnail,
                    'is_sale'               => $isSale,
                    'sale_price'            => $isSale ? ($newVariantData['sale_price'] ?? null) : null,
                    'sale_price_start_at'   => $isSale ? ($newVariantData['sale_price_start_at'] ?? null) : null,
                    'sale_price_end_at'     => $isSale ? ($newVariantData['sale_price_end_at'] ?? null) : null,
                ]);

                // Gắn thuộc tính cho biến thể mới một cách linh hoạt
                if (isset($newVariantData['attribute_values'])) {
                    $newVariant->attributeValues()->attach($newVariantData['attribute_values']);
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
        $product->is_active = 0;
        $product->save();
        return redirect()->route('admin.products.index')->with('success', 'Sản phẩm đã được ẩn.');
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

        // // Nếu sản phẩm đã từng có trong đơn hàng, không cho xóa cứng
        // if ($product->orderItems()->exists()) {
        //     return redirect()->back()->with('error', 'Không thể xóa sản phẩm đã có trong đơn hàng!');
        // }

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

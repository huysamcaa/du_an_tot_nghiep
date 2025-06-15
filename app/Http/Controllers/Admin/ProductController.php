<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Product;
use App\Models\Admin\Category;
use App\Models\Admin\ProductVariant;
use App\Models\Admin\Attribute;
use App\Models\Admin\AttributeValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['variants'])->orderBy('created_at', 'desc')->get();
        return view('admin.products.index', compact('products'));
    }
    

    public function create()
    {
         $categories = Category::all();
        $attributes = Attribute::with('attributeValues')->where('is_active', 1)->get();
    return view('admin.products.create', compact('attributes','categories'));

        
        
    }
public function store(Request $request)
{
    $data = $request->validate([
        'brand_id' => 'required|integer',
        'name' => 'required|string|max:255',
        'short_description' => 'required|string',
        'description' => 'required|string',
        'thumbnail' => 'required|image',
        'price' => 'required|numeric',
        'sale_price' => 'nullable|numeric',
        'sale_price_start_at' => 'nullable|date',
        'sale_price_end_at' => 'nullable|date|after_or_equal:sale_price_start_at',
        'is_sale' => 'boolean',
        'is_featured' => 'boolean',
        'is_trending' => 'boolean',
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
    $data['is_featured'] = $request->has('is_featured');
    $data['is_trending'] = $request->has('is_trending');
    $data['is_active'] = $request->has('is_active');

    // Upload ảnh chính
    if ($request->hasFile('thumbnail')) {
        $data['thumbnail'] = $request->file('thumbnail')->store('uploads/products', 'public');
    }

    // Tạo sản phẩm
    $product = Product::create($data);

    // Tạo các biến thể
    if ($request->has('variants')) {
    foreach ($request->variants as $variantData) {
        $variant = new ProductVariant([
            'price' => $variantData['price'],
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

        // Gán giá trị thuộc tính cho biến thể (sửa lại ở đây)
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
         
        $product->load(['variants.attributeValues']);
        $colors = AttributeValue::whereHas('attribute', function($q) {
            $q->where('slug', 'color');
        })->get();

        $sizes = AttributeValue::whereHas('attribute', function($q) {
            $q->where('slug', 'size');
        })->get();
 $categories = Category::all();
        return view('admin.products.edit', compact('product', 'colors', 'sizes','categories'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'brand_id' => 'required|integer',
            'name' => 'required|string|max:255',
            'short_description' => 'required|string',
            'description' => 'required|string',
            'thumbnail' => 'nullable|image',
            'sku' => 'nullable|string|unique:products,sku,' . $product->id,
            'price' => 'required|numeric',
            'sale_price' => 'nullable|numeric',
            'sale_price_start_at' => 'nullable|date',
            'sale_price_end_at' => 'nullable|date|after_or_equal:sale_price_start_at',
            'is_sale' => 'boolean',
            'is_featured' => 'boolean',
            'is_trending' => 'boolean',
            'is_active' => 'boolean',
            'variants' => 'sometimes|array',
            'variants.*.id' => 'sometimes|exists:product_variants,id',
            'variants.*.color_id' => 'required_with:variants|exists:attribute_values,id',
            'variants.*.size_id' => 'required_with:variants|exists:attribute_values,id',
            'variants.*.price' => 'required_with:variants|numeric',
            'variants.*.stock' => 'required_with:variants|integer|min:0',
            'variants.*.sku' => 'nullable|string|unique:product_variants,sku,' . ($request->variants[0]['id'] ?? 'NULL'),
            'variants.*.thumbnail' => 'nullable|image',
        ]);

        // Xử lý checkbox
        $data['is_sale'] = $request->has('is_sale');
        $data['is_featured'] = $request->has('is_featured');
        $data['is_trending'] = $request->has('is_trending');
        $data['is_active'] = $request->has('is_active');

        // Upload ảnh chính nếu có
        if ($request->hasFile('thumbnail')) {
            // Xóa ảnh cũ nếu tồn tại
            if ($product->thumbnail) {
                Storage::disk('public')->delete($product->thumbnail);
            }
            $data['thumbnail'] = $request->file('thumbnail')->store('uploads/products', 'public');
        }

        // Cập nhật sản phẩm
        $product->update($data);

        // Xử lý biến thể
        if ($request->has('variants')) {
            $existingVariantIds = [];

            foreach ($request->variants as $variantData) {
                $variantData = array_filter($variantData);

                if (isset($variantData['id'])) {
                    // Cập nhật biến thể hiện có
                    $variant = ProductVariant::find($variantData['id']);
                    $variant->update([
                        'price' => $variantData['price'],
                        'stock' => $variantData['stock'],
                        'sku' => $variantData['sku'] ?? $variant->sku,
                    ]);

                    // Upload ảnh biến thể nếu có
                    if (isset($variantData['thumbnail']) && $variantData['thumbnail']->isValid()) {
                        // Xóa ảnh cũ nếu tồn tại
                        if ($variant->thumbnail) {
                            Storage::disk('public')->delete($variant->thumbnail);
                        }
                        $variant->thumbnail = $variantData['thumbnail']->store('uploads/variants', 'public');
                        $variant->save();
                    }

                    // Cập nhật thuộc tính biến thể
                    $variant->attributeValues()->sync([
                        $variantData['color_id'],
                        $variantData['size_id']
                    ]);

                    $existingVariantIds[] = $variantData['id'];
                } else {
                    // Tạo biến thể mới
                    $variant = new ProductVariant([
                        'sku' => $variantData['sku'] ?? $this->generateVariantSku($product, $variantData),
                        'price' => $variantData['price'],
                        'stock' => $variantData['stock'],
                    ]);

                    // Upload ảnh biến thể nếu có
                    if (isset($variantData['thumbnail']) && $variantData['thumbnail']->isValid()) {
                        $variant->thumbnail = $variantData['thumbnail']->store('uploads/variants', 'public');
                    } else {
                        $variant->thumbnail = $product->thumbnail;
                    }

                    // Lưu biến thể
                    $product->variants()->save($variant);

                    // Gán các thuộc tính biến thể
                    $variant->attributeValues()->attach([
                        $variantData['color_id'],
                        $variantData['size_id']
                    ]);

                    $existingVariantIds[] = $variant->id;
                }
            }

            // Xóa các biến thể không còn tồn tại
            $product->variants()->whereNotIn('id', $existingVariantIds)->delete();
        }

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    public function show(Product $product)
    {
        $product->load(['variants.attributeValues.attribute','galleries']);
        return view('admin.products.show', compact('product'));
    }

    public function destroy(Product $product)
    {
        // Xóa ảnh chính
        if ($product->thumbnail) {
            Storage::disk('public')->delete($product->thumbnail);
        }

        // Xóa các ảnh biến thể
        foreach ($product->variants as $variant) {
            if ($variant->thumbnail && $variant->thumbnail != $product->thumbnail) {
                Storage::disk('public')->delete($variant->thumbnail);
            }
        }

        $product->variants()->delete();
        $product->forceDelete();

        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
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
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Product;
use App\Models\Admin\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductVariantController extends Controller
{
    public function index(Product $product)
    {
        // Lấy tất cả biến thể, kể cả inactive
        $variants = $product->variantsWithAttributes()->get();
        return view('admin.products.variants.index', compact('product', 'variants'));
    }

    public function create(Product $product)
    {
        $colors = \App\Models\Admin\AttributeValue::whereHas('attribute', function ($q) {
            $q->where('slug', 'color');
        })->get();

        $sizes = \App\Models\Admin\AttributeValue::whereHas('attribute', function ($q) {
            $q->where('slug', 'size');
        })->get();

        return view('admin.products.variants.create', compact('product', 'colors', 'sizes'));
    }

    public function store(Request $request, Product $product)
    {
        // Nếu submit từ nhiều biến thể
        if ($request->has('variants')) {
            foreach ($request->variants as $variantData) {
                $validated = validator($variantData, [
                    'price' => 'required|numeric|min:0',
                    'stock' => 'required|integer|min:0',
                    'sku' => 'nullable|unique:product_variants,sku',
                ])->validate();

                $thumbnail = isset($variantData['thumbnail']) && $variantData['thumbnail'] instanceof \Illuminate\Http\UploadedFile
                    ? $variantData['thumbnail']->store('variants')
                    : null;

                $variant = $product->variants()->create([
                    'price' => $validated['price'],
                    'sku' => $variantData['sku'] ?? null,
                    'stock' => $validated['stock'],
                    'thumbnail' => $thumbnail,
                    'is_active' => 1, // Mặc định active khi tạo mới
                ]);

                $variant->attributeValues()->sync($variantData['attribute_value_id'] ?? []);
            }

            return redirect()->route('admin.products.variants.index', $product)
                ->with('success', 'Các biến thể đã được thêm thành công');
        }

        // Trường hợp thêm 1 biến thể duy nhất
        $request->validate([
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'sku' => 'required|unique:product_variants,sku',
            'attribute_values' => 'required|array|min:1',
            'thumbnail' => 'nullable|image',
        ]);

        $variant = $product->variants()->create([
            'price' => $request->price,
            'sku' => $request->sku,
            'stock' => $request->stock,
            'thumbnail' => $request->file('thumbnail') 
                ? $request->file('thumbnail')->store('variants') 
                : null,
            'is_active' => 1,
        ]);

        $variant->attributeValues()->sync($request->attribute_values);

        return redirect()->route('admin.products.variants.index', $product)
            ->with('success', 'Biến thể đã được thêm thành công');
    }

    public function update(Request $request, Product $product, ProductVariant $variant)
    {
        $request->validate([
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'sku' => 'required|unique:product_variants,sku,' . $variant->id,
            'attribute_values' => 'required|array|min:1',
            'thumbnail' => 'nullable|image',
        ]);

        $data = [
            'price' => $request->price,
            'sku' => $request->sku,
            'stock' => $request->stock,
        ];

        if ($request->hasFile('thumbnail')) {
            if ($variant->thumbnail) {
                Storage::delete($variant->thumbnail);
            }
            $data['thumbnail'] = $request->file('thumbnail')->store('variants');
        }

        $variant->update($data);
        $variant->attributeValues()->sync($request->attribute_values);

        return redirect()->route('admin.products.variants.index', $product)
            ->with('success', 'Biến thể đã được cập nhật');
    }

    public function destroy(Product $product, ProductVariant $variant)
{
    // Xóa ảnh thumbnail nếu có
    if ($variant->thumbnail) {
        Storage::delete($variant->thumbnail);
    }
    
    // Xóa các relationship trước (nếu cần)
    $variant->attributeValues()->detach();
    
    // Xóa biến thể
    $variant->delete();
    
    // Trả về JSON response cho AJAX request
    if (request()->ajax()) {
        return response()->json([
            'success' => true,
            'message' => 'Biến thể đã được xóa thành công.'
        ]);
    }
    
    return back()->with('success', 'Biến thể đã được xóa thành công.');
}

    public function restore(Product $product, $variantId)
    {
        // Đổi lại thành active
        $variant = ProductVariant::findOrFail($variantId);
        $variant->update(['is_active' => 1]);
        return back()->with('success', 'Biến thể đã được bật lại');
    }

    public function forceDelete(Product $product, $variantId)
    {
        // Xóa vĩnh viễn
        $variant = ProductVariant::findOrFail($variantId);
        
        if ($variant->thumbnail) {
            Storage::delete($variant->thumbnail);
        }
        
        $variant->delete();
        return back()->with('success', 'Biến thể đã bị xóa vĩnh viễn');
    }
}

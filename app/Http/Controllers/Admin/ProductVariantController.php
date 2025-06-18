<?php
// app/Http/Controllers/Admin/ProductVariantController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Product;
use App\Models\Admin\ProductVariant;
use Illuminate\Http\Request;

class ProductVariantController extends Controller
{
    public function index(Product $product)
    {
        $variants = $product->variantsWithAttributes();
        return view('admin.products.variants.index', compact('product', 'variants'));
    }

    public function create(Product $product)
    {
         $colors = \App\Models\Admin\AttributeValue::whereHas('attribute', function($q) {
        $q->where('slug', 'color');
    })->get();

    $sizes = \App\Models\Admin\AttributeValue::whereHas('attribute', function($q) {
        $q->where('slug', 'size');
    })->get();

    return view('admin.products.variants.create', compact('product', 'colors', 'sizes'));
        $attributes = $product->variantAttributes();
        return view('admin.products.variants.create', compact('product', 'attributes'));
    }

    public function store(Request $request, Product $product)
{
    $request->validate([
        'price' => 'required|numeric|min:0',
        'sku' => 'required|unique:product_variants,sku',
        'attribute_values' => 'required|array|min:1',
        'thumbnail' => 'nullable|image'
    ]);

    $variant = $product->variants()->create([
        'price' => $request->price,
        'sku' => $request->sku,
        'thumbnail' => $request->file('thumbnail') ? $request->file('thumbnail')->store('variants') : null
    ]);

    // Gán các giá trị thuộc tính cho biến thể
    $variant->attributeValues()->sync($request->attribute_values);

    return redirect()->route('admin.products.variants.index', $product)
        ->with('success', 'Biến thể đã được thêm thành công');
}

    public function edit(Product $product, ProductVariant $variant)
    {
        $attributes = $product->variantAttributes();
        $selectedValues = $variant->attributeValues->pluck('id')->toArray();
        
        return view('admin.products.variants.edit', compact('product', 'variant', 'attributes', 'selectedValues'));
    }

    public function update(Request $request, Product $product, ProductVariant $variant)
    {
        $request->validate([
            'price' => 'required|numeric|min:0',
            'sku' => 'required|unique:product_variants,sku,'.$variant->id,
 
            'attribute_values' => 'required|array|min:1',
            'thumbnail' => 'nullable|image'
        ]);

        $data = [
            'price' => $request->price,
            'sku' => $request->sku,

        ];

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('variants');
        }

        $variant->update($data);
        $variant->attributeValues()->sync($request->attribute_values);

        return redirect()->route('admin.products.variants.index', $product)
            ->with('success', 'Biến thể đã được cập nhật');
    }

    public function destroy(Product $product, ProductVariant $variant)
    {
        $variant->delete();
        return back()->with('success', 'Biến thể đã được xóa');
    }
}
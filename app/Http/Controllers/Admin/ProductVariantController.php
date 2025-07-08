<?php

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
                'quantity' => 'required|integer|min:0',
                'sku' => 'nullable|unique:product_variants,sku',
            ])->validate();

            $thumbnail = isset($variantData['thumbnail']) && $variantData['thumbnail'] instanceof \Illuminate\Http\UploadedFile
                ? $variantData['thumbnail']->store('variants')
                : null;

            $variant = $product->variants()->create([
                'price' => $validated['price'],
                'sku' => $variantData['sku'] ?? null,
                'quantity' => $validated['quantity'],
                'thumbnail' => $thumbnail,
            ]);

            $variant->attributeValues()->sync($variantData['attribute_value_id'] ?? []);
        }

        return redirect()->route('admin.products.variants.index', $product)
            ->with('success', 'Các biến thể đã được thêm thành công');
    }

    // Trường hợp thêm 1 biến thể duy nhất
    $request->validate([
        'price' => 'required|numeric|min:0',
        'quantity' => 'required|integer|min:0',
        'sku' => 'required|unique:product_variants,sku',
        'attribute_values' => 'required|array|min:1',
        'thumbnail' => 'nullable|image',
    ]);

    $variant = $product->variants()->create([
        'price' => $request->price,
        'sku' => $request->sku,
        'quantity' => $request->quantity,
        'thumbnail' => $request->file('thumbnail') 
            ? $request->file('thumbnail')->store('variants') 
            : null,
    ]);

    $variant->attributeValues()->sync($request->attribute_values);

    return redirect()->route('admin.products.variants.index', $product)
        ->with('success', 'Biến thể đã được thêm thành công');
}


    public function update(Request $request, Product $product, ProductVariant $variant)
    {
        $request->validate([
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'sku' => 'required|unique:product_variants,sku,' . $variant->id,
            'attribute_values' => 'required|array|min:1',
            'thumbnail' => 'nullable|image',
        ]);

        $data = [
            'price' => $request->price,
            'sku' => $request->sku,
            'quantity' => $request->quantity,
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

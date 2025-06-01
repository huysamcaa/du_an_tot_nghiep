<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('created_at', 'desc')->get();
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        return view('admin.products.create');
    }

   public function store(Request $request)
{
    $data = $request->validate([
        'brand_id' => 'required|integer',
        'name' => 'required|string|max:255',
        'slug' => 'required|unique:products,slug',
        'short_description' => 'required|string',
        'description' => 'required|string',
        'thumbnail' => 'required|image',  // bắt buộc có ảnh và là file ảnh
        'sku' => 'nullable|string|unique:products,sku',
        'price' => 'required|numeric',
        'sale_price' => 'nullable|numeric',
        'sale_price_start_at' => 'nullable|date',
        'sale_price_end_at' => 'nullable|date|after_or_equal:sale_price_start_at',
        'is_sale' => 'boolean',
        'is_featured' => 'boolean',
        'is_trending' => 'boolean',
        'is_active' => 'boolean',

    ]);

    // Xử lý checkbox vì nếu không check thì không gửi giá trị, nên ép kiểu true/false
    $data['is_sale'] = $request->has('is_sale');
    $data['is_featured'] = $request->has('is_featured');
    $data['is_trending'] = $request->has('is_trending');
    $data['is_active'] = $request->has('is_active');

    if ($request->hasFile('thumbnail')) {
        $data['thumbnail'] = $request->file('thumbnail')->store('uploads/products', 'public');
    }

    Product::create($data);

    return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
}


    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'brand_id' => 'required|integer', // Vẫn giữ brand_id, validate kiểu integer
            'name' => 'required|string|max:255',
            'slug' => 'required|unique:products,slug,' . $product->id,
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
        ]);

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('uploads/products', 'public');
        }

        $product->update($data);
        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->forceDelete();
        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }
}

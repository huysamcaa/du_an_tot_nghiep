<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;
class BrandController extends Controller
{
   public function index(Request $request)
{
    $query = Brand::withCount('products') // đếm số sản phẩm
        ->withTrashed() // lấy cả brand bị xóa mềm
        ->when($request->has('search'), function ($q) use ($request) {
            $search = $request->get('search');
            $q->where(function (Builder $query) use ($search) {
                $query->where('name', 'like', "%$search%")
                    ->orWhere('slug', 'like', "%$search%");
            });
        });

    $brands = $query->paginate($request->get('perPage', 10));

    return view('admin.brands.index', compact('brands'));
}
    public function create()
    {
        return view('admin.brands.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:brands,name',
            'slug' => 'nullable|unique:brands,slug',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $slug = $request->slug ?: Str::slug($request->name);
        if (Brand::where('slug', $slug)->exists()) {
    return back()->withErrors(['slug' => 'Slug này đã tồn tại.'])->withInput();
}




        $data = [
            'name' => $request->name,
            'slug' => $slug,
            'is_active' => $request->has('is_active') ? 1 : 0,
        ];

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('brands', 'public');
        }

        Brand::create($data);

        return redirect()->route('admin.brands.index')->with('success', 'Thêm thương hiệu thành công.');
    }

    public function edit(Brand $brand)
    {
        return view('admin.brands.edit', compact('brand'));
    }

    public function update(Request $request, Brand $brand)
{
    $request->validate([
        'name' => 'required|unique:brands,name,' . $brand->id,
        'slug' => 'nullable|unique:brands,slug,' . $brand->id,
        'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    ]);

    $slug = $request->slug ?: Str::slug($request->name);

    // Chỉ kiểm tra sự tồn tại của slug nếu slug mới không giống slug cũ
    if ($slug !== $brand->slug && Brand::where('slug', $slug)->exists()) {
        return back()->withErrors(['slug' => 'Slug này đã tồn tại.'])->withInput();
    }

    $data = [
        'name' => $request->name,
        'slug' => $slug,
        'is_active' => $request->has('is_active') ? 1 : 0,
    ];

    if ($request->hasFile('logo')) {
        if ($brand->logo) {
            Storage::disk('public')->delete($brand->logo);
        }
        $data['logo'] = $request->file('logo')->store('brands', 'public');
    }

    $brand->update($data);

    return redirect()->route('admin.brands.index')->with('success', 'Cập nhật thương hiệu thành công.');
}


    public function destroy(Brand $brand)
{
    if ($brand->products()->exists()) {
        return back()->with('error', 'Không thể xóa thương hiệu vì vẫn còn sản phẩm liên quan.');
    }

    // Xóa logo nếu có
    if ($brand->logo && Storage::disk('public')->exists($brand->logo)) {
        Storage::disk('public')->delete($brand->logo);
    }

    // Xóa thương hiệu (xóa mềm)
    $brand->delete();

    return back()->with('success', 'Đã xóa thương hiệu.');
}

    public function show(Brand $brand)
    {
        // Đếm số sản phẩm liên quan
        $brand->loadCount('products');

        return view('admin.brands.show', compact('brand'));
    }
    public function trash()
{
    $brands = Brand::onlyTrashed()->withCount('products')->paginate(10);
    return view('admin.brands.trash', compact('brands'));
}
public function restore($id)
{
    $brand = Brand::onlyTrashed()->findOrFail($id);
    $brand->restore();

    return redirect()->route('admin.brands.trash')->with('success', 'Khôi phục thương hiệu thành công.');
}

}

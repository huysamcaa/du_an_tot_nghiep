<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class BrandController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('perPage', 10);

        $brands = Brand::when($search, function ($query, $search) {
                $query->where('name', 'like', "%$search%")
                      ->orWhere('slug', 'like', "%$search%");
            })
            ->orderByDesc('created_at')
            ->paginate($perPage)
            ->appends($request->all());

        return view('admin.brands.index', compact('brands', 'search', 'perPage'));
    }

    public function create()
    {
        return view('admin.brands.create');
    }

  public function store(Request $request)
{
    // Tự động tạo slug nếu chưa nhập và merge vào request
    $request->merge([
        'slug' => $request->slug ?: Str::slug($request->name)
    ]);

    // Gọi hàm validate với logic và thông báo riêng
    $this->validateBrandForm($request);

    // Chuẩn bị dữ liệu để lưu
    $data = [
        'name' => $request->name,
        'slug' => $request->slug,
        'is_active' => (int) $request->input('is_active', 0),

    ];

    // Nếu có upload logo thì xử lý upload file
    if ($request->hasFile('logo')) {
        $data['logo'] = $request->file('logo')->store('brands', 'public');
    }

    // Tạo mới bản ghi
    Brand::create($data);

    // Redirect với thông báo
    return redirect()->route('admin.brands.index')->with('success', 'Thêm thương hiệu thành công');
}


    public function edit($id)
    {
        $brand = Brand::withTrashed()->findOrFail($id);
        return view('admin.brands.edit', compact('brand'));
    }

    public function update(Request $request, $id)
    {
        $brand = Brand::withTrashed()->findOrFail($id);
        $this->validateBrandForm($request, $id);

        $slug = $request->slug ?: Str::slug($request->name);

        $data = [
            'name' => $request->name,
            'slug' => $slug,
            'is_active' => (int) $request->input('is_active', 0),

        ];

        if ($request->hasFile('logo')) {
            if ($brand->logo && Storage::disk('public')->exists($brand->logo)) {
                Storage::disk('public')->delete($brand->logo);
            }
            $data['logo'] = $request->file('logo')->store('brands', 'public');
        }

        $brand->update($data);

        return redirect()->route('admin.brands.index')
        ->with('success','Cập nhật thương hiệu thành công');
    }

    public function destroy($id)
    {
        $brand = Brand::withTrashed()->findOrFail($id);

        if ($brand->products()->exists()) {
            return back()->with('error', 'Không thể xóa thương hiệu vì vẫn còn sản phẩm liên quan.');
        }

        if ($brand->logo && Storage::disk('public')->exists($brand->logo)) {
            Storage::disk('public')->delete($brand->logo);
        }

        $brand->delete();

        return back()->with('success', 'Xóa thương hiệu thành công.');
    }

    public function show($id)
    {
        $brand = Brand::withTrashed()->withCount('products')->findOrFail($id);
        return view('admin.brands.show', compact('brand'));
    }

    public function trash(Request $request)
    {
        $perPage = $request->input('perPage', 10);
        $search = $request->input('search');

        $brands = Brand::onlyTrashed()
            ->withCount('products')
            ->when($search, function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('slug', 'like', "%$search%");
            })
            ->paginate($perPage)
            ->appends($request->all());

        return view('admin.brands.trash', compact('brands'));
    }

    public function restore($id)
    {
        $brand = Brand::onlyTrashed()->findOrFail($id);
        $brand->restore();

        return redirect()->route('admin.brands.trash')->with('success', 'Khôi phục thương hiệu thành công');
    }

    /**
     * Validate dữ liệu tạo / cập nhật brand
     */
    protected function validateBrandForm(Request $request, $id = null)
{
    $rules = [
        'name' => [
            'required',
            'string',
            'max:100',
            Rule::unique('brands', 'name')->ignore($id)
        ],
        'slug' => [
            'required',
            'string',
            'max:100',
            Rule::unique('brands', 'slug')->ignore($id)
        ],
        'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        'is_active' => 'required|boolean',

    ];

    $messages = [
        'name.required' => 'Vui lòng nhập tên thương hiệu.',
        'name.max' => 'Tên thương hiệu không được vượt quá 100 ký tự.',
        'name.unique' => 'Tên thương hiệu đã tồn tại.',
        'slug.required' => 'Vui lòng nhập slug.',
        'slug.max' => 'Slug không được vượt quá 100 ký tự.',
        'slug.unique' => 'Slug này đã tồn tại.',
        'logo.image' => 'Logo phải là hình ảnh.',
        'logo.mimes' => 'Logo chỉ chấp nhận: jpg, jpeg, png, webp.',
        'logo.max' => 'Logo không được vượt quá 2MB.',
        'is_active.boolean' => 'Trạng thái hiển thị không hợp lệ.',
    ];

    Validator::make($request->all(), $rules, $messages)->validate();
}

}

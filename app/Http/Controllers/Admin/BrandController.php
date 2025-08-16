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
use Illuminate\Support\Facades\DB;
class BrandController extends Controller
{
public function index(Request $request)
{
    $perPage     = (int) $request->input('perPage', 10);
    $search      = trim((string) $request->input('search'));
    $isActive    = $request->input('is_active');       // '', '1', '0'
    $hasProducts = $request->input('has_products');    // '', 'yes', 'no'
    $startDate   = $request->input('start_date');      // YYYY-MM-DD
    $endDate     = $request->input('end_date');        // YYYY-MM-DD

    $query = Brand::query()->withCount('products');

    // Tìm kiếm
    if ($search !== '') {
        $query->where(function ($q) use ($search) {
            $q->where('name', 'LIKE', "%{$search}%")
              ->orWhere('slug', 'LIKE', "%{$search}%");
        });
    }

    // Trạng thái
    if ($isActive === '1') {
        $query->where('is_active', 1);
    } elseif ($isActive === '0') {
        $query->where('is_active', 0);
    }

    // Có sản phẩm / không có
    if ($hasProducts === 'yes') {
        $query->having('products_count', '>', 0);
    } elseif ($hasProducts === 'no') {
        $query->having('products_count', '=', 0);
    }

    // Khoảng ngày tạo
    if ($startDate) {
        $query->whereDate('created_at', '>=', $startDate);
    }
    if ($endDate) {
        $query->whereDate('created_at', '<=', $endDate);
    }

    $brands = $query
        ->orderByDesc('created_at')
        ->paginate($perPage)
        ->withQueryString();

    return view('admin.brands.index', compact('brands'));
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
        'is_active' => $request->boolean('is_active') ? 1 : 0,


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
            'is_active' => $request->boolean('is_active') ? 1 : 0,

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

        // if ($brand->logo && Storage::disk('public')->exists($brand->logo)) {
        //     Storage::disk('public')->delete($brand->logo);
        // }

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

    private function generateUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name, '-', 'vi'); // bỏ dấu tiếng Việt
        $slug = $base;
        $i = 2;

        while (
            Brand::withTrashed()
                ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
                ->where('slug', $slug)
                ->exists()
        ) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
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
        'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp',
        'is_active' => 'nullable|boolean',

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

        'is_active.boolean' => 'Trạng thái hiển thị không hợp lệ.',
    ];

    Validator::make($request->all(), $rules, $messages)->validate();
}
public function bulkDestroy(Request $request)
{
    $ids = array_filter((array) $request->input('ids', []), 'is_numeric');

    if (empty($ids)) {
        return back()->with('success', 'Không có mục nào được chọn.');
    }

    // Lấy danh sách brand kèm số sản phẩm
    $brands = Brand::withCount('products')
        ->whereIn('id', $ids)
        ->get();

    // Bị chặn nếu còn sản phẩm
    $blocked = $brands->where('products_count', '>', 0);
    // Được phép xóa nếu không còn sản phẩm
    $allowedIds = $brands->where('products_count', 0)->pluck('id')->all();

    if (!empty($allowedIds)) {
        DB::transaction(function () use ($allowedIds) {
            Brand::whereIn('id', $allowedIds)->delete(); // soft delete
        });
    }

    // Thông báo gộp
    $messages = [];
    if (!empty($allowedIds)) {
        $messages[] = 'Đã xóa  ' . count($allowedIds) . ' thương hiệu.';
    }
    if ($blocked->isNotEmpty()) {
        $names = $blocked->pluck('name')->implode(', ');
        $messages[] = 'Không thể xóa các thương hiệu còn sản phẩm: ' . $names . '.';
    }

    return back()->with('success', $messages ? implode(' ', $messages) : 'Không có thay đổi.');
}
public function bulkRestore(Request $request)
{
    $ids = array_filter((array) $request->input('ids', []), 'is_numeric');

    if (empty($ids)) {
        return back()->with('warning', 'Không có mục nào được chọn.');
    }

    // Chỉ restore các bản ghi đang ở trạng thái deleted
    $restored = Brand::onlyTrashed()->whereIn('id', $ids)->restore();

    return back()->with('success', "Đã khôi phục {$restored} thương hiệu.");
}

}

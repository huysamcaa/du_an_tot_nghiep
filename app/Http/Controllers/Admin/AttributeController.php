<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Attribute;
use Illuminate\Http\Request;

class AttributeController extends Controller
{
 public function index(Request $request)
{
    $perPage = $request->input('perPage', 10);
    $search = $request->input('search');

    $query = Attribute::query();

    if ($search) {
        $query->where('name', 'LIKE', "%{$search}%")
              ->orWhere('slug', 'LIKE', "%{$search}%");
    }

    $attributes = $query->orderBy('id', 'asc')
                        ->paginate($perPage)
                        ->withQueryString();

    return view('admin.attributes.index', compact('attributes'));
}

    public function create()
    {
        return view('admin.attributes.create');
    }

    public function store(Request $request)
{
   $request->validate([
    'name' => 'required|string|max:255',
    'slug' => 'required|string|max:255|unique:attributes,slug',
    'is_variant' => 'required|boolean',
    'is_active' => 'required|boolean',
    'values' => 'nullable|array',
    'values.*.name' => 'required|string|max:255',
    'values.*.hex' => 'required|string|max:7',
]);

    $attribute = Attribute::create($request->only(['name', 'slug', 'is_variant', 'is_active']));

    // Thêm giá trị thuộc tính nếu có
    if ($request->has('values')) {
    foreach ($request->values as $value) {
        $attribute->attributeValues()->create([
            'value' => trim($value['name']),
            'hex' => $value['hex'],
            'is_active' => 1,
        ]);
    }
}


    return redirect()->route('admin.attributes.index')->with('success', 'Thêm thuộc tính thành công!');
}

    public function edit(Attribute $attribute)
    {
        return view('admin.attributes.edit', compact('attribute'));
    }

   public function update(Request $request, Attribute $attribute)
{
    $attribute->update($request->only(['name', 'slug', 'is_variant', 'is_active']));

    if ($request->has('values')) {
        foreach ($request->values as $value) {
            if (!empty($value['id'])) {
                // Cập nhật giá trị cũ

                $attrValue = $attribute->attributeValues()->find($value['id']);
                if ($attrValue) {
                    $attrValue->update([
                        'value' => $value['name'],
                        'hex' => $value['hex'] ?? null,
                    ]);
                }
            } else {
                // Thêm mới
                $attribute->attributeValues()->create([
                    'value' => $value['name'],
                    'hex' => $value['hex'] ?? null,
                    'is_active' => 1,
                ]);

            }

        }

    }
    return redirect()->route('admin.attributes.index')->with('success', 'Cập nhật thuộc tính thành công!');
}

    public function destroy(Attribute $attribute)
    {
        $attribute->delete();
        return redirect()->route('admin.attributes.index')->with('success', 'Xóa thuộc tính thành công!');
    }
}

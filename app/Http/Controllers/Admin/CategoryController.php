<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Http\Requests\CategoryRequest;
use App\Models\Admin\Category;
use App\Models\Admin\Product; //

class CategoryController extends Controller
{
 public function index(Request $request)
{
    $perPage = $request->input('perPage', 10);
    $search = $request->input('search');

    $query = Category::with('parent')->orderBy('ordinal');

    if ($search) {
        $query->where('name', 'LIKE', "%{$search}%");
    }

    $categories = $query->paginate($perPage)->withQueryString();

    return view('admin.categories.index', compact('categories'));
}

public function show(Category $category)
{
    $products = $category->getAllProductsAttribute(); // Lấy cả direct và liên kết nhiều-nhiều
    return view('admin.categories.show', compact('category', 'products'));
}


    public function create()
    {
        $parentCategories = Category::whereNull('parent_id')->get();
        return view('admin.categories.create', compact('parentCategories'));
    }

    public function store(CategoryRequest $request)
    {
        Category::create([
            'parent_id' => $request->parent_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'icon' => $request->icon,
            'ordinal' => $request->ordinal,
            'is_active' => $request->is_active,
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Danh mục đã được tạo thành công.');
    }

    public function edit(Category $category)
    {
        $parentCategories = Category::whereNull('parent_id')
            ->where('id', '!=', $category->id)
            ->get();

        return view('admin.categories.edit', compact('category', 'parentCategories'));
    }

    public function update(CategoryRequest $request, Category $category)
    {
        $category->update([
            'parent_id' => $request->parent_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'icon' => $request->icon,
            'ordinal' => $request->ordinal,
            'is_active' => $request->is_active,
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Danh mục đã được cập nhật thành công.');
    }

    public function destroy(Category $category)
    {
        DB::beginTransaction();
        try {
            $hasProducts = Product::where('category_id', $category->id)->exists()
                || DB::table('category_product')->where('category_id', $category->id)->exists();

            if ($hasProducts) {
                throw new \Exception('Không thể xóa danh mục vì có sản phẩm.');
            }

            Category::where('parent_id', $category->id)->update(['parent_id' => null]);
            $category->delete();

            DB::commit();
            return redirect()->route('admin.categories.index')->with('success', 'Đã xóa mềm danh mục.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.categories.index')->with('error', $e->getMessage());
        }
    }

public function trashed(Request $request)
{

    $perPage = $request->input('per_page', 10);

    $query = Category::onlyTrashed()->with('parent')->orderByDesc('deleted_at');

    if ($request->filled('keyword')) {
        $query->where('name', 'like', '%' . $request->keyword . '%');
    }

    $categories = $query->paginate($perPage)->appends($request->all());

    return view('admin.categories.trashed', compact('categories', 'perPage'));
}

    public function restore($id)
    {
        $category = Category::onlyTrashed()->findOrFail($id);
        $category->restore();

        return redirect()->route('admin.categories.trashed')->with('success', 'Danh mục đã được khôi phục.');
    }

    public function forceDelete($id)
    {
        DB::beginTransaction();
        try {
            $category = Category::onlyTrashed()->findOrFail($id);

            Product::where('category_id', $category->id)->update(['category_id' => 0]);
            DB::table('category_product')->where('category_id', $category->id)->update(['category_id' => 0]);

            $category->forceDelete();

            DB::commit();
            return redirect()->route('admin.categories.trashed')->with('success', 'Danh mục đã xóa vĩnh viễn.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.categories.trashed')->with('error', $e->getMessage());
        }
    }
}

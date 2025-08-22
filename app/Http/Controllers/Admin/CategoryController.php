<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Http\Requests\CategoryRequest;
use App\Models\Admin\Category;
use App\Models\Admin\Product;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    /**
     * Hiển thị danh sách các danh mục.
     */
    public function index(Request $request)
    {
        $mode = $request->input('mode', 'parents');
        $query = Category::query();

        if ($mode === 'parents') {
            $query->whereNull('parent_id');
        } else {
            $query->whereNotNull('parent_id');

            if ($parentId = $request->input('parent_id')) {
                $query->where('parent_id', $parentId);
            }
        }

        if ($search = $request->input('search')) {
            $query->where('name', 'like', "%{$search}%");
        }
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->input('is_active'));
        }

        // Đã thay đổi thứ tự sắp xếp: ưu tiên created_at (mới nhất) trước, sau đó là ordinal.
        $categories = $query->orderBy('created_at', 'desc')->orderBy('ordinal')->paginate(10)->withQueryString();
        $parentCategoriesList = Category::whereNull('parent_id')->orderBy('name')->get();

        return view('admin.categories.index', compact('categories', 'parentCategoriesList'));
    }

    /**
     * Hiển thị chi tiết một danh mục.
     */
    public function show(Category $category)
    {
        $products = $category->getAllProductsAttribute();
        return view('admin.categories.show', compact('category', 'products'));
    }

    /**
     * Hiển thị form tạo danh mục mới.
     */
    public function create()
    {
        $parentCategories = Category::whereNull('parent_id')
                                   ->whereDoesntHave('directProducts')
                                   ->get();

        return view('admin.categories.create', compact('parentCategories'));
    }

    /**
     * Lưu danh mục mới vào cơ sở dữ liệu.
     */
    public function store(CategoryRequest $request)
    {
        try {
            if ($request->filled('parent_id')) {
                $parent = Category::find($request->parent_id);

                if (!$parent || $parent->hasDirectProducts()) {
                    return redirect()->back()->withInput()->with('error', 'Danh mục cha được chọn không hợp lệ hoặc đã có sản phẩm.');
                }
            }

            // Xử lý tải lên ảnh cho trường icon
            $imagePath = null;
            if ($request->hasFile('icon')) {
                $imagePath = $request->file('icon')->store('categories', 'public');
            }

            Category::create([
                'parent_id' => $request->parent_id,
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'icon' => $imagePath, // Cập nhật trường icon thành đường dẫn ảnh
                'ordinal' => $request->ordinal ?? 0,
                'is_active' => $request->is_active ?? 1,
            ]);

            return redirect()->route('admin.categories.index')->with('success', 'Danh mục đã được tạo thành công.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Có lỗi xảy ra khi tạo danh mục: ' . $e->getMessage());
        }
    }

    /**
     * Hiển thị form chỉnh sửa danh mục.
     */
    public function edit(Category $category)
    {
        $parentCategories = Category::whereNull('parent_id')
                                   ->whereDoesntHave('directProducts')
                                   ->get();

        $parentCategories = $parentCategories->filter(function ($parent) use ($category) {
            return $parent->id != $category->id;
        });

        $hasChildren = $category->hasChildren();
        $hasProducts = $category->hasDirectProducts();

        return view('admin.categories.edit', compact('category', 'parentCategories', 'hasChildren', 'hasProducts'));
    }

    /**
     * Cập nhật danh mục trong cơ sở dữ liệu.
     */
    public function update(CategoryRequest $request, Category $category)
    {
        try {
            $oldParentId = $category->parent_id;
            $newParentId = $request->input('parent_id');

            if ($newParentId && $category->id == $newParentId) {
                return redirect()->back()->withInput()->with('error', 'Không thể gán danh mục cha là chính nó.');
            }

            $hasChildren = $category->hasChildren();
            $hasProducts = $category->hasDirectProducts();

            // Logic mới để xử lý các trường hợp chuyển đổi
            if ($newParentId != $oldParentId) {
                // Kiểm tra nếu muốn chuyển thành danh mục cha
                if (is_null($newParentId)) {
                    // Nếu danh mục đang là con và có sản phẩm, có thể chuyển thành cha
                    // Điều kiện: danh mục không có con nào
                    if ($hasChildren) {
                         return redirect()->back()->withInput()->with('error', 'Không thể chuyển danh mục có danh mục con thành danh mục cha.');
                    }
                }

                // Kiểm tra nếu muốn gán vào một danh mục cha mới
                if (!is_null($newParentId)) {
                    if ($hasChildren) {
                        return redirect()->back()->withInput()->with('error', 'Không thể gán danh mục cha có danh mục con thành danh mục con.');
                    }

                    $newParent = Category::find($newParentId);
                    // Kiểm tra danh mục cha mới không hợp lệ hoặc đã có sản phẩm hoặc đã là con
                    if (!$newParent || $newParent->hasDirectProducts() || !is_null($newParent->parent_id)) {
                        return redirect()->back()->withInput()->with('error', 'Danh mục cha mới không hợp lệ hoặc đã có sản phẩm.');
                    }
                }
            }

            // Xử lý cập nhật ảnh
            $imagePath = $category->icon;
            if ($request->hasFile('icon')) {
                if ($category->icon && Storage::disk('public')->exists($category->icon)) {
                    Storage::disk('public')->delete($category->icon);
                }
                $imagePath = $request->file('icon')->store('categories', 'public');
            }

            // Cập nhật dữ liệu
            $category->update([
                'parent_id' => $newParentId,
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'icon' => $imagePath,
                'ordinal' => $request->ordinal,
                'is_active' => $request->is_active,
            ]);

            return redirect()->route('admin.categories.index')->with('success', 'Danh mục đã được cập nhật thành công.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Có lỗi xảy ra khi cập nhật danh mục: ' . $e->getMessage());
        }
    }

    /**
     * Xóa mềm một danh mục.
     */
    public function destroy(Category $category)
    {
        DB::beginTransaction();
        try {
            $hasProducts = $category->hasDirectProducts();
            $hasChildren = $category->hasChildren();

            if ($hasProducts) {
                throw new \Exception('Không thể xóa danh mục vì có sản phẩm liên quan.');
            }
            if ($hasChildren) {
                throw new \Exception('Không thể xóa danh mục vì có danh mục con.');
            }

            // Chỉ thực hiện xóa mềm
            $category->delete();

            DB::commit();
            return redirect()->route('admin.categories.index')->with('success', 'Đã xóa mềm danh mục.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.categories.index')->with('error', $e->getMessage());
        }
    }

    /**
     * Hiển thị danh sách các danh mục đã xóa mềm.
     */
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

    /**
     * Khôi phục danh mục đã xóa mềm.
     */
    public function restore($id)
    {
        try {
            $category = Category::onlyTrashed()->findOrFail($id);
            $category->restore();
            return redirect()->route('admin.categories.trashed')->with('success', 'Danh mục đã được khôi phục.');
        } catch (\Exception $e) {
            return redirect()->route('admin.categories.trashed')->with('error', 'Có lỗi xảy ra khi khôi phục danh mục: ' . $e->getMessage());
        }
    }

    /**
     * Xóa vĩnh viễn một danh mục.
     */
    public function forceDelete($id)
    {
        DB::beginTransaction();
        try {
            $category = Category::onlyTrashed()->findOrFail($id);
            $hasProducts = $category->hasDirectProducts();
            $hasChildren = $category->hasChildren();

            if ($hasProducts) {
                throw new \Exception('Không thể xóa vĩnh viễn danh mục này vì còn sản phẩm liên quan.');
            }
            if ($hasChildren) {
                throw new \Exception('Không thể xóa danh mục vì có danh mục con.');
            }

            // Xóa file ảnh trước khi xóa danh mục
            if ($category->icon && Storage::disk('public')->exists($category->icon)) {
                Storage::disk('public')->delete($category->icon);
            }

            $category->forceDelete();

            DB::commit();
            return redirect()->route('admin.categories.trashed')->with('success', 'Danh mục đã xóa vĩnh viễn.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.categories.trashed')->with('error', $e->getMessage());
        }
    }
}

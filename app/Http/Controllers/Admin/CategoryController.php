<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Requests\CategoryRequest;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('parent')
            ->orderBy('ordinal')
            ->get();
            
        return view('admin.categories.index', compact('categories'));
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

    public function show(Category $category)
    {
        return view('categories.show', compact('category'));
    }

    public function edit(Category $category)
    {
        $parentCategories = Category::whereNull('parent_id')
            ->where('id', '!=', $category->id)
            ->get();
            
        return view('admin.categories.edit', compact('category', 'parentCategories'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'parent_id' => 'nullable|exists:categories,id',
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'icon' => 'nullable|string|max:255',
            'ordinal' => 'required|integer',
            'is_active' => 'required|boolean',
        ]);

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
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Danh mục đã được xóa thành công.');
    }
}
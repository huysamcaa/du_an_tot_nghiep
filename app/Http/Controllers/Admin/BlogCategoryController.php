<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogCategoryController extends Controller
{
    public function index()
    {
        $categories = BlogCategory::latest()->paginate(10);
        return view('admin.blog_categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.blog_categories.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'is_active' => 'nullable|boolean'
        ]);

        $data['slug'] = Str::slug($data['name']);
        $data['is_active'] = $request->is_active ?? 1;

        BlogCategory::create($data);

        return redirect()->route('admin.blog_categories.index')->with('success', 'Tạo danh mục thành công');
    }

    public function edit(BlogCategory $blogCategory)
{
    return view('admin.blog_categories.edit', ['blog_category' => $blogCategory]);
}

    public function update(Request $request, BlogCategory $blogCategory)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'is_active' => 'nullable|boolean'
        ]);

        $data['slug'] = Str::slug($data['name']);
        $data['is_active'] = $request->is_active ?? 1;

        $blogCategory->update($data);

        return redirect()->route('admin.blog_categories.index')->with('success', 'Cập nhật danh mục thành công');
    }

    public function destroy(BlogCategory $blogCategory)
    {
        $blogCategory->delete();
        return redirect()->route('admin.blog_categories.index')->with('success', 'Xóa danh mục thành công');
    }
}

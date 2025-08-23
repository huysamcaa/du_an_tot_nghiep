<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Blog;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Models\BlogCategory;

class BlogController extends Controller
{
 public function index(Request $request)
{
    $perPage = $request->input('perPage', 10);
    $search = $request->input('search');

    $query = Blog::query();

    if ($search) {
        $query->where('title', 'LIKE', "%{$search}%");
    }

    $blogs = $query->latest()->paginate($perPage)->withQueryString();

    return view('admin.blogs.index', compact('blogs'));
}



    public function create()
    {
            $categories = BlogCategory::where('is_active', 1)->get();
    return view('admin.blogs.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|min:5',
            'content' => 'required',
            'image' => 'nullable|image|max:2048',
            'blog_category_id' => 'required|exists:blog_categories,id',
        ]);

        $image = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image')->store('blogs', 'public');
        }

        Blog::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'content' => $request->content,
            'image' => $image,
            'blog_category_id' => $request->blog_category_id,
        ]);

        return redirect()->route('admin.blogs.index')->with('success', 'Thêm bài viết thành công!');
    }

    public function edit($id)
    {
        $blog = Blog::findOrFail($id);
        $categories = BlogCategory::where('is_active', 1)->get();
        return view('admin.blogs.edit', compact('blog', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $blog = Blog::findOrFail($id);

        $request->validate([
            'title' => 'required|min:5',
            'content' => 'required',
            'image' => 'nullable|image|max:2048',
            'blog_category_id' => 'required|exists:blog_categories,id',
        ]);

        // Xóa ảnh nếu người dùng chọn "remove_image"
        if ($request->has('remove_image')) {
            if ($blog->image && Storage::disk('public')->exists($blog->image)) {
                Storage::disk('public')->delete($blog->image);
                $blog->image = null;
            }
        }

        // Nếu có ảnh mới thì xóa ảnh cũ và cập nhật ảnh mới
        if ($request->hasFile('image')) {
            if ($blog->image && Storage::disk('public')->exists($blog->image)) {
                Storage::disk('public')->delete($blog->image);
            }
            $blog->image = $request->file('image')->store('blogs', 'public');
        }

        $blog->update([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'content' => $request->content,
            'image' => $blog->image,
            'blog_category_id' => $request->blog_category_id,
        ]);

        return redirect()->route('admin.blogs.index')->with('success', 'Cập nhật thành công!');
    }

    public function destroy($id)
    {
        $blog = Blog::findOrFail($id);
        if ($blog->image) Storage::disk('public')->delete($blog->image);
        $blog->delete();

        return redirect()->route('admin.blogs.index')->with('success', 'Xóa bài viết thành công!');
    }
}

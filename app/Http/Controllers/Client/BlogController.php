<?php
namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Blog;
use App\Models\BlogCategory;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $blogQuery = Blog::with('category');

        if ($request->has('category') && $request->category != '') {
            $blogQuery->where('blog_category_id', $request->category);
        }

        $blogs = $blogQuery->latest()->paginate(6);
        $blogCategories = BlogCategory::where('is_active', 1)->get();

        return view('client.blogs.index', compact('blogs', 'blogCategories'));
    }

    public function show($slug)
    {
        $blog = Blog::where('slug', $slug)->with('category')->firstOrFail();
        $featuredBlogs = Blog::where('id', '!=', $blog->id)
                             ->latest()
                             ->take(3)
                             ->get();

        return view('client.blogs.show', compact('blog', 'featuredBlogs'));
    }
}

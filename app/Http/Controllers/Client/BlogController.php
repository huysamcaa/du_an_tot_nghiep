<?php
namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Blog;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::latest()->paginate(6);
        return view('client.blogs.index', compact('blogs'));
    }

    public function show($slug)
    {
        $blog = Blog::where('slug', $slug)->firstOrFail();
        $featuredBlogs = Blog::where('id', '!=', $blog->id)
                         ->latest()
                         ->take(3)
                         ->get();
        return view('client.blogs.show', compact('blog','featuredBlogs'));
    }
}

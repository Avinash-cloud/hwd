<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BlogController extends Controller
{
    /**
     * Display a listing of published spiritual blog articles.
     */
    public function index(Request $request): View
    {
        $query = Blog::published();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('excerpt', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $currentCategory = $request->input('category');
        if ($currentCategory) {
            $query->where('category', $currentCategory);
        }

        // Get single prominent featured article for top banner if not searching
        $featuredBlog = null;
        if (! $search && ! $currentCategory) {
            $featuredBlog = (clone $query)->where('is_featured', true)->latest('published_at')->first();
        }

        if ($featuredBlog) {
            $query->where('id', '!=', $featuredBlog->id);
        }

        $blogs = $query->latest('published_at')->paginate(9)->withQueryString();
        $categories = Blog::published()->distinct()->pluck('category')->filter()->values();

        return view('blogs.index', compact('blogs', 'featuredBlog', 'categories', 'currentCategory', 'search'));
    }

    /**
     * Display the specified spiritual article.
     */
    public function show(string $slug): View
    {
        $blog = Blog::published()->where('slug', $slug)->firstOrFail();

        // Increment view count safely
        $blog->increment('views_count');

        // Fetch related articles from same category
        $relatedBlogs = Blog::published()
            ->where('id', '!=', $blog->id)
            ->where('category', $blog->category)
            ->latest('published_at')
            ->take(3)
            ->get();

        // Fallback to latest if not enough in same category
        if ($relatedBlogs->count() < 3) {
            $moreBlogs = Blog::published()
                ->where('id', '!=', $blog->id)
                ->whereNotIn('id', $relatedBlogs->pluck('id'))
                ->latest('published_at')
                ->take(3 - $relatedBlogs->count())
                ->get();
            $relatedBlogs = $relatedBlogs->merge($moreBlogs);
        }

        // Recommended sacred offerings for footer banner
        $recommendedProducts = Product::where('is_active', true)
            ->where(function ($q) {
                $q->where('is_featured', true)->orWhere('is_gangajal', true);
            })
            ->take(4)
            ->get();

        return view('blogs.show', compact('blog', 'relatedBlogs', 'recommendedProducts'));
    }
}

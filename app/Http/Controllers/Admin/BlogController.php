<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BlogController extends Controller
{
    /**
     * Display a listing of blog articles.
     */
    public function index(Request $request): View
    {
        $query = Blog::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('excerpt', 'like', "%{$search}%")
                    ->orWhere('author_name', 'like', "%{$search}%");
            });
        }

        if ($category = $request->input('category')) {
            $query->where('category', $category);
        }

        if ($request->filled('status')) {
            $isPublished = $request->input('status') === 'published';
            $query->where('is_published', $isPublished);
        }

        $blogs = $query->latest('id')->paginate(12)->withQueryString();
        $categories = Blog::distinct()->pluck('category')->filter()->values();

        return view('admin.blogs.index', compact('blogs', 'categories'));
    }

    /**
     * Show the form for creating a new article.
     */
    public function create(): View
    {
        $existingCategories = Blog::distinct()->pluck('category')->filter()->values();

        return view('admin.blogs.create', compact('existingCategories'));
    }

    /**
     * Store a newly created article in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blogs,slug',
            'category' => 'required|string|max:100',
            'author_name' => 'required|string|max:100',
            'reading_time_minutes' => 'nullable|integer|min:1|max:60',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
            'image_url' => 'nullable|string|max:1000',
            'is_published' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'published_at' => 'nullable|date',
        ]);

        $validated['slug'] = ! empty($validated['slug'])
            ? Str::slug($validated['slug'])
            : Str::slug($validated['title']);

        // Ensure unique slug
        $baseSlug = $validated['slug'];
        $count = 1;
        while (Blog::where('slug', $validated['slug'])->exists()) {
            $validated['slug'] = "{$baseSlug}-{$count}";
            $count++;
        }

        if ($request->hasFile('image_file')) {
            $validated['image'] = $request->file('image_file')->store('blogs', 'public');
        } elseif (! empty($validated['image_url'])) {
            $validated['image'] = $validated['image_url'];
        }

        unset($validated['image_file'], $validated['image_url']);

        $validated['is_published'] = $request->boolean('is_published', true);
        $validated['is_featured'] = $request->boolean('is_featured', false);
        $validated['reading_time_minutes'] = (int) ($validated['reading_time_minutes'] ?? 5);

        if ($validated['is_published'] && empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        Blog::create($validated);

        return redirect()->route('admin.blogs.index')->with('success', 'Blog article published successfully.');
    }

    /**
     * Show the form for editing the specified article.
     */
    public function edit(Blog $blog): View
    {
        $existingCategories = Blog::distinct()->pluck('category')->filter()->values();

        return view('admin.blogs.edit', compact('blog', 'existingCategories'));
    }

    /**
     * Update the specified article in storage.
     */
    public function update(Request $request, Blog $blog): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => "nullable|string|max:255|unique:blogs,slug,{$blog->id}",
            'category' => 'required|string|max:100',
            'author_name' => 'required|string|max:100',
            'reading_time_minutes' => 'nullable|integer|min:1|max:60',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
            'image_url' => 'nullable|string|max:1000',
            'is_published' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'published_at' => 'nullable|date',
        ]);

        $validated['slug'] = ! empty($validated['slug'])
            ? Str::slug($validated['slug'])
            : Str::slug($validated['title']);

        // Check if slug changed and ensure unique
        if ($validated['slug'] !== $blog->slug) {
            $baseSlug = $validated['slug'];
            $count = 1;
            while (Blog::where('slug', $validated['slug'])->where('id', '!=', $blog->id)->exists()) {
                $validated['slug'] = "{$baseSlug}-{$count}";
                $count++;
            }
        }

        if ($request->hasFile('image_file')) {
            if ($blog->image && Storage::disk('public')->exists($blog->image)) {
                Storage::disk('public')->delete($blog->image);
            }
            $validated['image'] = $request->file('image_file')->store('blogs', 'public');
        } elseif (! empty($validated['image_url'])) {
            $validated['image'] = $validated['image_url'];
        }

        unset($validated['image_file'], $validated['image_url']);

        $validated['is_published'] = $request->boolean('is_published', true);
        $validated['is_featured'] = $request->boolean('is_featured', false);
        $validated['reading_time_minutes'] = (int) ($validated['reading_time_minutes'] ?? 5);

        if ($validated['is_published'] && empty($blog->published_at) && empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        $blog->update($validated);

        return redirect()->route('admin.blogs.index')->with('success', 'Blog article updated successfully.');
    }

    /**
     * Remove the specified article from storage.
     */
    public function destroy(Blog $blog): RedirectResponse
    {
        if ($blog->image && Storage::disk('public')->exists($blog->image)) {
            Storage::disk('public')->delete($blog->image);
        }

        $blog->delete();

        return redirect()->route('admin.blogs.index')->with('success', 'Blog article deleted successfully.');
    }

    /**
     * Toggle the published status of an article.
     */
    public function togglePublished(Blog $blog): RedirectResponse
    {
        $blog->update([
            'is_published' => ! $blog->is_published,
            'published_at' => (! $blog->is_published && ! $blog->published_at) ? now() : $blog->published_at,
        ]);

        $status = $blog->is_published ? 'published' : 'drafted';

        return back()->with('success', "Article '{$blog->title}' is now {$status}.");
    }

    /**
     * Toggle the featured status of an article.
     */
    public function toggleFeatured(Blog $blog): RedirectResponse
    {
        $blog->update(['is_featured' => ! $blog->is_featured]);

        $status = $blog->is_featured ? 'featured' : 'unfeatured';

        return back()->with('success', "Article '{$blog->title}' is now {$status}.");
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories with search and filters.
     */
    public function index(Request $request): View
    {
        $query = Category::with('parent')
            ->withCount(['products', 'children']);

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search): void {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('parent_id')) {
            $parentId = $request->input('parent_id');
            if ($parentId === 'root') {
                $query->root();
            } else {
                $query->where('parent_id', $parentId);
            }
        }

        if ($request->filled('is_featured')) {
            $query->where('is_featured', $request->boolean('is_featured'));
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        $categories = $query->orderBy('sort_order')
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        $rootCategories = Category::root()->get();

        return view('admin.categories.index', compact('categories', 'rootCategories'));
    }

    /**
     * Show the form for creating a new category.
     */
    public function create(): View
    {
        $rootCategories = Category::root()->get();
        $nextSortOrder = (Category::max('sort_order') ?? 0) + 1;

        return view('admin.categories.create', compact('rootCategories', 'nextSortOrder'));
    }

    /**
     * Store a newly created category in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:categories,slug',
            'parent_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string|max:1000',
            'icon' => 'nullable|string|max:50',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
            'image_url' => 'nullable|string|max:1000',
            'sort_order' => 'nullable|integer|min:0',
            'is_featured' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ]);

        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('categories', 'public');
            $validated['image'] = $path;
        } elseif (! empty($validated['image_url'])) {
            $validated['image'] = $validated['image_url'];
        }

        unset($validated['image_file'], $validated['image_url']);

        if (empty($validated['slug'])) {
            $baseSlug = Str::slug($validated['name']);
            $slug = $baseSlug;
            $counter = 1;
            while (Category::where('slug', $slug)->exists()) {
                $slug = "{$baseSlug}-{$counter}";
                $counter++;
            }
            $validated['slug'] = $slug;
        } else {
            $validated['slug'] = Str::slug($validated['slug']);
        }

        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['sort_order'] = (int) ($validated['sort_order'] ?? 0);

        Category::create($validated);

        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully.');
    }

    /**
     * Show the form for editing the specified category.
     */
    public function edit(Category $category): View
    {
        $rootCategories = Category::root()->where('id', '!=', $category->id)->get();

        return view('admin.categories.edit', compact('category', 'rootCategories'));
    }

    /**
     * Update the specified category in storage.
     */
    public function update(Request $request, Category $category): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:categories,slug,'.$category->id,
            'parent_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string|max:1000',
            'icon' => 'nullable|string|max:50',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
            'image_url' => 'nullable|string|max:1000',
            'sort_order' => 'nullable|integer|min:0',
            'is_featured' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ]);

        if ($request->hasFile('image_file')) {
            if ($category->image && Storage::disk('public')->exists($category->image)) {
                Storage::disk('public')->delete($category->image);
            }
            $path = $request->file('image_file')->store('categories', 'public');
            $validated['image'] = $path;
        } elseif (! empty($validated['image_url'])) {
            $validated['image'] = $validated['image_url'];
        }

        unset($validated['image_file'], $validated['image_url']);

        // Prevent category from being its own parent
        if (isset($validated['parent_id']) && $validated['parent_id'] == $category->id) {
            $validated['parent_id'] = null;
        }

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        } else {
            $validated['slug'] = Str::slug($validated['slug']);
        }

        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_active'] = $request->boolean('is_active');
        $validated['sort_order'] = (int) ($validated['sort_order'] ?? 0);

        $category->update($validated);

        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy(Category $category): RedirectResponse
    {
        // Safety check: protect products from accidental cascade deletion
        $productsCount = $category->products()->count();
        if ($productsCount > 0) {
            return back()->with('error', "Cannot delete category '{$category->name}' because it contains {$productsCount} products. Please reassign or delete these products first.");
        }

        // Reassign any child categories to root
        $category->children()->update(['parent_id' => null]);

        if ($category->image && Storage::disk('public')->exists($category->image)) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully.');
    }

    /**
     * Quick toggle active status for sale.
     */
    public function toggleActive(Category $category): RedirectResponse
    {
        $category->update(['is_active' => ! $category->is_active]);

        $status = $category->is_active ? 'activated' : 'deactivated';

        return back()->with('success', "Category '{$category->name}' {$status}.");
    }

    /**
     * Quick toggle featured status for homepage circular collections.
     */
    public function toggleFeatured(Category $category): RedirectResponse
    {
        $category->update(['is_featured' => ! $category->is_featured]);

        $status = $category->is_featured ? 'featured in homepage circular collections' : 'removed from homepage featured';

        return back()->with('success', "Category '{$category->name}' {$status}.");
    }
}

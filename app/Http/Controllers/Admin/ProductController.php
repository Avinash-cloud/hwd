<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Display listing of products with search and filtering.
     */
    public function index(Request $request): View
    {
        $query = Product::with(['category', 'batch']);

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search): void {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%")
                    ->orWhere('short_description', 'like', "%{$search}%");
            });
        }

        if ($categoryId = $request->get('category_id')) {
            $query->where('category_id', $categoryId);
        }

        if ($request->filled('is_gangajal')) {
            $query->where('is_gangajal', $request->boolean('is_gangajal'));
        }

        if ($request->filled('is_featured')) {
            $query->where('is_featured', $request->boolean('is_featured'));
        }

        if ($request->filled('is_bestseller')) {
            $query->where('is_bestseller', $request->boolean('is_bestseller'));
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        $products = $query->latest('id')->paginate(15)->withQueryString();
        $categories = Category::all();

        return view('admin.products.index', compact('products', 'categories'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create(): View
    {
        $categories = Category::all();
        $batches = Batch::where('status', 'ready')->get();

        return view('admin.products.create', compact('categories', 'batches'));
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'batch_id' => 'nullable|exists:batches,id',
            'name' => 'required|string|max:255',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'compare_price' => 'nullable|numeric|min:0',
            'member_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'sku' => 'nullable|string|max:100',
            'volume_ml' => 'nullable|integer',
            'badge' => 'nullable|string|max:100',
            'rating' => 'nullable|numeric|min:0|max:5',
            'reviews_count' => 'nullable|integer|min:0',
            'purity_details' => 'nullable|string',
            'benefits' => 'nullable|string',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
            'image_url' => 'nullable|string|max:1000',
            'is_gangajal' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'is_bestseller' => 'nullable|boolean',
        ]);

        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('products', 'public');
            $validated['image'] = $path;
        } elseif (! empty($validated['image_url'])) {
            $validated['image'] = $validated['image_url'];
        }

        unset($validated['image_file'], $validated['image_url']);

        $validated['slug'] = Str::slug($validated['name']).'-'.Str::lower(Str::random(5));
        $validated['is_gangajal'] = $request->boolean('is_gangajal');
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_bestseller'] = $request->boolean('is_bestseller');
        $validated['rating'] = $validated['rating'] ?? 4.80;
        $validated['reviews_count'] = $validated['reviews_count'] ?? 15;

        Product::create($validated);

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product): View
    {
        $categories = Category::all();
        $batches = Batch::all();

        return view('admin.products.edit', compact('product', 'categories', 'batches'));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'batch_id' => 'nullable|exists:batches,id',
            'name' => 'required|string|max:255',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'compare_price' => 'nullable|numeric|min:0',
            'member_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'sku' => 'nullable|string|max:100',
            'volume_ml' => 'nullable|integer',
            'badge' => 'nullable|string|max:100',
            'rating' => 'nullable|numeric|min:0|max:5',
            'reviews_count' => 'nullable|integer|min:0',
            'purity_details' => 'nullable|string',
            'benefits' => 'nullable|string',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
            'image_url' => 'nullable|string|max:1000',
            'is_gangajal' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'is_bestseller' => 'nullable|boolean',
        ]);

        if ($request->hasFile('image_file')) {
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $path = $request->file('image_file')->store('products', 'public');
            $validated['image'] = $path;
        } elseif (! empty($validated['image_url'])) {
            $validated['image'] = $validated['image_url'];
        }

        unset($validated['image_file'], $validated['image_url']);

        $validated['is_gangajal'] = $request->boolean('is_gangajal');
        $validated['is_active'] = $request->boolean('is_active');
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_bestseller'] = $request->boolean('is_bestseller');

        $product->update($validated);

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product): RedirectResponse
    {
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Product deleted.');
    }

    /**
     * Quick toggle active status for sale.
     */
    public function toggleActive(Product $product): RedirectResponse
    {
        $product->update(['is_active' => ! $product->is_active]);

        $status = $product->is_active ? 'activated' : 'deactivated';

        return back()->with('success', "Product '{$product->name}' {$status}.");
    }

    /**
     * Quick toggle featured status for homepage.
     */
    public function toggleFeatured(Product $product): RedirectResponse
    {
        $product->update(['is_featured' => ! $product->is_featured]);

        $status = $product->is_featured ? 'featured on homepage' : 'removed from featured';

        return back()->with('success', "Product '{$product->name}' {$status}.");
    }

    /**
     * Quick toggle bestseller status.
     */
    public function toggleBestseller(Product $product): RedirectResponse
    {
        $product->update(['is_bestseller' => ! $product->is_bestseller]);

        $status = $product->is_bestseller ? 'marked as bestseller' : 'unmarked as bestseller';

        return back()->with('success', "Product '{$product->name}' {$status}.");
    }
}

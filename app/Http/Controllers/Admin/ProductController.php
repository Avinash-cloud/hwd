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
        $query = Product::with(['category', 'batch', 'variants']);

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
            'hsn_code' => 'nullable|string|max:20',
            'gst_percentage' => 'nullable|numeric|min:0|max:100',
            'volume_ml' => 'nullable|integer',
            'badge' => 'nullable|string|max:100',
            'rating' => 'nullable|numeric|min:0|max:5',
            'reviews_count' => 'nullable|integer|min:0',
            'purity_details' => 'nullable|string',
            'benefits' => 'nullable|string',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
            'image_url' => 'nullable|string|max:1000',
            'gallery_files' => 'nullable|array',
            'gallery_files.*' => 'image|mimes:jpeg,png,jpg,webp,gif|max:5120',
            'gallery_urls' => 'nullable|string',
            'gallery_order' => 'nullable|string',
            'video_url' => 'nullable|string|max:1000',
            'video_file' => 'nullable|file|mimes:mp4,mov,webm,ogg|max:51200',
            'is_gangajal' => 'nullable|boolean',
            'is_member_free_eligible' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'is_bestseller' => 'nullable|boolean',
            'variants' => 'nullable|array',
            'variants.*.name' => 'required_with:variants|string|max:100',
            'variants.*.sku' => 'nullable|string|max:100',
            'variants.*.price' => 'required_with:variants|numeric|min:0',
            'variants.*.compare_price' => 'nullable|numeric|min:0',
            'variants.*.member_price' => 'nullable|numeric|min:0',
            'variants.*.stock' => 'nullable|integer|min:0',
            'variants.*.volume_ml' => 'nullable|integer',
            'variants.*.is_active' => 'nullable|boolean',
        ]);

        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('products', 'public');
            $validated['image'] = $path;
        } elseif (! empty($validated['image_url'])) {
            $validated['image'] = $validated['image_url'];
        }

        // Additional Gallery Images
        $gallery = [];
        if ($request->hasFile('gallery_files')) {
            foreach ($request->file('gallery_files') as $file) {
                $path = $file->store('products/gallery', 'public');
                $gallery[] = $path;
            }
        }
        if (! empty($validated['gallery_urls'])) {
            $urls = array_filter(array_map('trim', preg_split('/[\r\n,]+/', $validated['gallery_urls'])));
            foreach ($urls as $url) {
                if (! in_array($url, $gallery, true)) {
                    $gallery[] = $url;
                }
            }
        }
        if (! empty($gallery)) {
            $validated['gallery'] = array_values($gallery);
        }

        // Product Video
        if ($request->hasFile('video_file')) {
            $validated['video_url'] = $request->file('video_file')->store('products/videos', 'public');
        } elseif (! empty($validated['video_url'])) {
            $validated['video_url'] = trim($validated['video_url']);
        } else {
            $validated['video_url'] = null;
        }

        $variantsData = $validated['variants'] ?? [];

        unset(
            $validated['image_file'],
            $validated['image_url'],
            $validated['gallery_files'],
            $validated['gallery_urls'],
            $validated['gallery_order'],
            $validated['video_file'],
            $validated['variants']
        );

        $validated['slug'] = Str::slug($validated['name']).'-'.Str::lower(Str::random(5));
        $validated['hsn_code'] = $validated['hsn_code'] ?? '22019010';
        $validated['gst_percentage'] = $validated['gst_percentage'] ?? 18.00;
        $validated['is_gangajal'] = $request->boolean('is_gangajal');
        $validated['is_member_free_eligible'] = $request->boolean('is_member_free_eligible');
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_bestseller'] = $request->boolean('is_bestseller');
        $validated['rating'] = $validated['rating'] ?? 4.80;
        $validated['reviews_count'] = $validated['reviews_count'] ?? 15;

        $product = Product::create($validated);

        // Save Variants
        if (! empty($variantsData)) {
            foreach ($variantsData as $index => $vData) {
                if (empty($vData['name']) || ! isset($vData['price'])) {
                    continue;
                }
                $product->variants()->create([
                    'name' => trim($vData['name']),
                    'sku' => $vData['sku'] ?? ($product->sku ? "{$product->sku}-{$index}" : null),
                    'price' => (float) $vData['price'],
                    'compare_price' => ! empty($vData['compare_price']) ? (float) $vData['compare_price'] : null,
                    'member_price' => ! empty($vData['member_price']) ? (float) $vData['member_price'] : null,
                    'stock' => isset($vData['stock']) ? (int) $vData['stock'] : 0,
                    'volume_ml' => ! empty($vData['volume_ml']) ? (int) $vData['volume_ml'] : null,
                    'hsn_code' => $product->hsn_code,
                    'gst_percentage' => $product->gst_percentage,
                    'sort_order' => $index,
                    'is_active' => isset($vData['is_active']) ? (bool) $vData['is_active'] : true,
                ]);
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully with size variants.');
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product): View
    {
        $product->load('variants');
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
            'hsn_code' => 'nullable|string|max:20',
            'gst_percentage' => 'nullable|numeric|min:0|max:100',
            'volume_ml' => 'nullable|integer',
            'badge' => 'nullable|string|max:100',
            'rating' => 'nullable|numeric|min:0|max:5',
            'reviews_count' => 'nullable|integer|min:0',
            'purity_details' => 'nullable|string',
            'benefits' => 'nullable|string',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
            'image_url' => 'nullable|string|max:1000',
            'keep_gallery' => 'nullable|array',
            'gallery_files' => 'nullable|array',
            'gallery_files.*' => 'image|mimes:jpeg,png,jpg,webp,gif|max:5120',
            'gallery_urls' => 'nullable|string',
            'gallery_order' => 'nullable|string',
            'video_url' => 'nullable|string|max:1000',
            'video_file' => 'nullable|file|mimes:mp4,mov,webm,ogg|max:51200',
            'remove_video' => 'nullable|boolean',
            'is_gangajal' => 'nullable|boolean',
            'is_member_free_eligible' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'is_bestseller' => 'nullable|boolean',
            'variants' => 'nullable|array',
            'variants.*.id' => 'nullable|integer',
            'variants.*.name' => 'required_with:variants|string|max:100',
            'variants.*.sku' => 'nullable|string|max:100',
            'variants.*.price' => 'required_with:variants|numeric|min:0',
            'variants.*.compare_price' => 'nullable|numeric|min:0',
            'variants.*.member_price' => 'nullable|numeric|min:0',
            'variants.*.stock' => 'nullable|integer|min:0',
            'variants.*.volume_ml' => 'nullable|integer',
            'variants.*.is_active' => 'nullable|boolean',
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

        // Additional Gallery Images
        $gallery = is_array($product->gallery) ? $product->gallery : [];

        // Check which existing gallery items are kept
        if ($request->has('keep_gallery')) {
            $kept = (array) $request->input('keep_gallery', []);
            foreach ($gallery as $item) {
                if (! in_array($item, $kept, true) && Storage::disk('public')->exists($item)) {
                    Storage::disk('public')->delete($item);
                }
            }
            $gallery = array_values(array_intersect($gallery, $kept));
        } elseif ($request->boolean('clear_gallery')) {
            foreach ($gallery as $item) {
                if (Storage::disk('public')->exists($item)) {
                    Storage::disk('public')->delete($item);
                }
            }
            $gallery = [];
        }

        // Append new gallery file uploads
        if ($request->hasFile('gallery_files')) {
            foreach ($request->file('gallery_files') as $file) {
                $path = $file->store('products/gallery', 'public');
                $gallery[] = $path;
            }
        }

        // Append new gallery URLs
        if (! empty($validated['gallery_urls'])) {
            $urls = array_filter(array_map('trim', preg_split('/[\r\n,]+/', $validated['gallery_urls'])));
            foreach ($urls as $url) {
                if (! in_array($url, $gallery, true)) {
                    $gallery[] = $url;
                }
            }
        }

        // Support custom gallery ordering if provided
        if ($request->filled('gallery_order')) {
            $orderedList = json_decode($request->input('gallery_order'), true);
            if (is_array($orderedList) && ! empty($orderedList)) {
                $reordered = [];
                foreach ($orderedList as $item) {
                    if (in_array($item, $gallery, true)) {
                        $reordered[] = $item;
                    }
                }
                foreach ($gallery as $item) {
                    if (! in_array($item, $reordered, true)) {
                        $reordered[] = $item;
                    }
                }
                $gallery = $reordered;
            }
        }

        $validated['gallery'] = ! empty($gallery) ? array_values($gallery) : null;

        // Video Handling
        if ($request->boolean('remove_video')) {
            if ($product->video_url && Storage::disk('public')->exists($product->video_url)) {
                Storage::disk('public')->delete($product->video_url);
            }
            $validated['video_url'] = null;
        } elseif ($request->hasFile('video_file')) {
            if ($product->video_url && Storage::disk('public')->exists($product->video_url)) {
                Storage::disk('public')->delete($product->video_url);
            }
            $validated['video_url'] = $request->file('video_file')->store('products/videos', 'public');
        } elseif (! empty($validated['video_url'])) {
            $validated['video_url'] = trim($validated['video_url']);
        }

        $variantsData = $validated['variants'] ?? [];

        unset(
            $validated['image_file'],
            $validated['image_url'],
            $validated['keep_gallery'],
            $validated['gallery_files'],
            $validated['gallery_urls'],
            $validated['gallery_order'],
            $validated['video_file'],
            $validated['remove_video'],
            $validated['variants']
        );

        $validated['hsn_code'] = $validated['hsn_code'] ?? $product->hsn_code ?? '22019010';
        $validated['gst_percentage'] = $validated['gst_percentage'] ?? $product->gst_percentage ?? 18.00;
        $validated['is_gangajal'] = $request->boolean('is_gangajal');
        $validated['is_member_free_eligible'] = $request->boolean('is_member_free_eligible');
        $validated['is_active'] = $request->boolean('is_active');
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_bestseller'] = $request->boolean('is_bestseller');

        $product->update($validated);

        // Sync Variants
        $submittedVariantIds = [];
        if (! empty($variantsData)) {
            foreach ($variantsData as $index => $vData) {
                if (empty($vData['name']) || ! isset($vData['price'])) {
                    continue;
                }
                $variant = null;
                if (! empty($vData['id'])) {
                    $variant = $product->variants()->find($vData['id']);
                }
                $vAttributes = [
                    'name' => trim($vData['name']),
                    'sku' => $vData['sku'] ?? ($product->sku ? "{$product->sku}-{$index}" : null),
                    'price' => (float) $vData['price'],
                    'compare_price' => ! empty($vData['compare_price']) ? (float) $vData['compare_price'] : null,
                    'member_price' => ! empty($vData['member_price']) ? (float) $vData['member_price'] : null,
                    'stock' => isset($vData['stock']) ? (int) $vData['stock'] : 0,
                    'volume_ml' => ! empty($vData['volume_ml']) ? (int) $vData['volume_ml'] : null,
                    'hsn_code' => $product->hsn_code,
                    'gst_percentage' => $product->gst_percentage,
                    'sort_order' => $index,
                    'is_active' => isset($vData['is_active']) ? (bool) $vData['is_active'] : true,
                ];
                if ($variant) {
                    $variant->update($vAttributes);
                } else {
                    $variant = $product->variants()->create($vAttributes);
                }
                $submittedVariantIds[] = $variant->id;
            }
        }
        // Remove variants removed by admin
        $product->variants()->whereNotIn('id', $submittedVariantIds)->delete();

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

        if (is_array($product->gallery)) {
            foreach ($product->gallery as $item) {
                if (Storage::disk('public')->exists($item)) {
                    Storage::disk('public')->delete($item);
                }
            }
        }

        if ($product->video_url && Storage::disk('public')->exists($product->video_url)) {
            Storage::disk('public')->delete($product->video_url);
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }

    /**
     * Toggle active status.
     */
    public function toggleActive(Product $product): RedirectResponse
    {
        $product->update(['is_active' => ! $product->is_active]);

        return back()->with('success', 'Product active status updated.');
    }

    /**
     * Toggle featured status.
     */
    public function toggleFeatured(Product $product): RedirectResponse
    {
        $product->update(['is_featured' => ! $product->is_featured]);

        return back()->with('success', 'Product featured status updated.');
    }

    /**
     * Toggle bestseller status.
     */
    public function toggleBestseller(Product $product): RedirectResponse
    {
        $product->update(['is_bestseller' => ! $product->is_bestseller]);

        return back()->with('success', 'Product bestseller status updated.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class GalleryController extends Controller
{
    /**
     * Display a listing of sacred gallery items.
     */
    public function index(Request $request): View
    {
        $query = GalleryItem::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('caption', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%");
            });
        }

        if ($category = $request->input('category')) {
            $query->where('category', $category);
        }

        if ($mediaType = $request->input('media_type')) {
            $query->where('media_type', $mediaType);
        }

        if ($request->filled('status')) {
            $isActive = $request->input('status') === 'active';
            $query->where('is_active', $isActive);
        }

        $items = $query->orderBy('sort_order')->latest('id')->paginate(16)->withQueryString();
        $categories = GalleryItem::distinct()->pluck('category')->filter()->values();

        return view('admin.gallery.index', compact('items', 'categories'));
    }

    /**
     * Show the form for adding a new gallery item.
     */
    public function create(): View
    {
        $existingCategories = GalleryItem::distinct()->pluck('category')->filter()->values();
        $nextSortOrder = (GalleryItem::max('sort_order') ?? 0) + 1;

        return view('admin.gallery.create', compact('existingCategories', 'nextSortOrder'));
    }

    /**
     * Store a newly created gallery item in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'caption' => 'nullable|string|max:1000',
            'media_type' => 'required|in:image,video',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
            'image_url' => 'nullable|string|max:1000',
            'video_url' => 'nullable|string|max:1000',
            'category' => 'required|string|max:100',
            'location' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        if (! $request->hasFile('image_file') && empty($validated['image_url'])) {
            return back()->withInput()->withErrors(['image_file' => 'Please provide an image file or preview image URL.']);
        }

        if ($request->hasFile('image_file')) {
            $validated['image'] = $request->file('image_file')->store('gallery', 'public');
        } elseif (! empty($validated['image_url'])) {
            $validated['image'] = $validated['image_url'];
        }

        unset($validated['image_file'], $validated['image_url']);

        $validated['location'] = $validated['location'] ?: 'Sacred Ganga Ghat, Haridwar';
        $validated['sort_order'] = (int) ($validated['sort_order'] ?? 0);
        $validated['is_active'] = $request->boolean('is_active', true);

        GalleryItem::create($validated);

        return redirect()->route('admin.gallery.index')->with('success', 'Sacred gallery item added successfully.');
    }

    /**
     * Show the form for editing the specified gallery item.
     */
    public function edit(GalleryItem $gallery): View
    {
        $existingCategories = GalleryItem::distinct()->pluck('category')->filter()->values();

        return view('admin.gallery.edit', compact('gallery', 'existingCategories'));
    }

    /**
     * Update the specified gallery item in storage.
     */
    public function update(Request $request, GalleryItem $gallery): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'caption' => 'nullable|string|max:1000',
            'media_type' => 'required|in:image,video',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
            'image_url' => 'nullable|string|max:1000',
            'video_url' => 'nullable|string|max:1000',
            'category' => 'required|string|max:100',
            'location' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        if ($request->hasFile('image_file')) {
            if ($gallery->image && Storage::disk('public')->exists($gallery->image)) {
                Storage::disk('public')->delete($gallery->image);
            }
            $validated['image'] = $request->file('image_file')->store('gallery', 'public');
        } elseif (! empty($validated['image_url'])) {
            $validated['image'] = $validated['image_url'];
        }

        unset($validated['image_file'], $validated['image_url']);

        $validated['location'] = $validated['location'] ?: 'Sacred Ganga Ghat, Haridwar';
        $validated['sort_order'] = (int) ($validated['sort_order'] ?? 0);
        $validated['is_active'] = $request->boolean('is_active', true);

        $gallery->update($validated);

        return redirect()->route('admin.gallery.index')->with('success', 'Gallery item updated successfully.');
    }

    /**
     * Remove the specified gallery item from storage.
     */
    public function destroy(GalleryItem $gallery): RedirectResponse
    {
        if ($gallery->image && Storage::disk('public')->exists($gallery->image)) {
            Storage::disk('public')->delete($gallery->image);
        }

        $gallery->delete();

        return redirect()->route('admin.gallery.index')->with('success', 'Gallery item deleted successfully.');
    }

    /**
     * Toggle the active status of a gallery item.
     */
    public function toggleActive(GalleryItem $gallery): RedirectResponse
    {
        $gallery->update(['is_active' => ! $gallery->is_active]);

        $status = $gallery->is_active ? 'active' : 'hidden';

        return back()->with('success', "Gallery item '{$gallery->title}' is now {$status}.");
    }
}

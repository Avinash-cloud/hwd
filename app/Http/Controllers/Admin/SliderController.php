<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SliderController extends Controller
{
    /**
     * Display a listing of homepage hero slides.
     */
    public function index(): View
    {
        $sliders = Slider::orderBy('sort_order')->latest('id')->paginate(15);

        return view('admin.sliders.index', compact('sliders'));
    }

    /**
     * Show the form for creating a new slider.
     */
    public function create(): View
    {
        $nextSortOrder = (Slider::max('sort_order') ?? 0) + 1;

        return view('admin.sliders.create', compact('nextSortOrder'));
    }

    /**
     * Store a newly created slider in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'media_type' => 'nullable|in:image,video',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
            'image_url' => 'nullable|string|max:1000',
            'tablet_image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
            'tablet_image_url' => 'nullable|string|max:1000',
            'mobile_image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
            'mobile_image_url' => 'nullable|string|max:1000',
            'video_file' => 'nullable|file|mimes:mp4,mov,webm|max:51200',
            'video_url' => 'nullable|string|max:1000',
            'button_text' => 'nullable|string|max:100',
            'button_link' => 'nullable|string|max:255',
            'secondary_button_text' => 'nullable|string|max:100',
            'secondary_button_link' => 'nullable|string|max:255',
            'badge_color' => 'nullable|string|max:50',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $mediaType = $request->input('media_type', 'image');

        // Handle desktop image
        if ($request->hasFile('image_file')) {
            $validated['image'] = $request->file('image_file')->store('sliders', 'public');
        } else {
            $validated['image'] = $validated['image_url'] ?? null;
        }

        // Handle tablet image
        if ($request->hasFile('tablet_image_file')) {
            $validated['tablet_image'] = $request->file('tablet_image_file')->store('sliders', 'public');
        } elseif (! empty($validated['tablet_image_url'])) {
            $validated['tablet_image'] = $validated['tablet_image_url'];
        }

        // Handle mobile image
        if ($request->hasFile('mobile_image_file')) {
            $validated['mobile_image'] = $request->file('mobile_image_file')->store('sliders', 'public');
        } elseif (! empty($validated['mobile_image_url'])) {
            $validated['mobile_image'] = $validated['mobile_image_url'];
        }

        // Handle video
        if ($request->hasFile('video_file')) {
            $validated['video_file'] = $request->file('video_file')->store('sliders/videos', 'public');
        }

        unset(
            $validated['image_file'], $validated['image_url'],
            $validated['tablet_image_file'], $validated['tablet_image_url'],
            $validated['mobile_image_file'], $validated['mobile_image_url']
        );

        $validated['media_type'] = $mediaType;
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['sort_order'] = (int) ($validated['sort_order'] ?? 0);
        $validated['badge_color'] = ($validated['badge_color'] ?? null) ?: 'amber';

        Slider::create($validated);

        return redirect()->route('admin.sliders.index')->with('success', 'Homepage slide created successfully.');
    }

    /**
     * Show the form for editing the specified slider.
     */
    public function edit(Slider $slider): View
    {
        return view('admin.sliders.edit', compact('slider'));
    }

    /**
     * Update the specified slider in storage.
     */
    public function update(Request $request, Slider $slider): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'media_type' => 'nullable|in:image,video',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
            'image_url' => 'nullable|string|max:1000',
            'tablet_image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
            'tablet_image_url' => 'nullable|string|max:1000',
            'mobile_image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
            'mobile_image_url' => 'nullable|string|max:1000',
            'video_file' => 'nullable|file|mimes:mp4,mov,webm|max:51200',
            'video_url' => 'nullable|string|max:1000',
            'button_text' => 'nullable|string|max:100',
            'button_link' => 'nullable|string|max:255',
            'secondary_button_text' => 'nullable|string|max:100',
            'secondary_button_link' => 'nullable|string|max:255',
            'badge_color' => 'nullable|string|max:50',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        if ($request->hasFile('image_file')) {
            if ($slider->image && Storage::disk('public')->exists($slider->image)) {
                Storage::disk('public')->delete($slider->image);
            }
            $validated['image'] = $request->file('image_file')->store('sliders', 'public');
        } elseif (! empty($validated['image_url'])) {
            $validated['image'] = $validated['image_url'];
        }

        if ($request->hasFile('tablet_image_file')) {
            if ($slider->tablet_image && Storage::disk('public')->exists($slider->tablet_image)) {
                Storage::disk('public')->delete($slider->tablet_image);
            }
            $validated['tablet_image'] = $request->file('tablet_image_file')->store('sliders', 'public');
        } elseif (! empty($validated['tablet_image_url'])) {
            $validated['tablet_image'] = $validated['tablet_image_url'];
        }

        if ($request->hasFile('mobile_image_file')) {
            if ($slider->mobile_image && Storage::disk('public')->exists($slider->mobile_image)) {
                Storage::disk('public')->delete($slider->mobile_image);
            }
            $validated['mobile_image'] = $request->file('mobile_image_file')->store('sliders', 'public');
        } elseif (! empty($validated['mobile_image_url'])) {
            $validated['mobile_image'] = $validated['mobile_image_url'];
        }

        if ($request->hasFile('video_file')) {
            if ($slider->video_file && Storage::disk('public')->exists($slider->video_file)) {
                Storage::disk('public')->delete($slider->video_file);
            }
            $validated['video_file'] = $request->file('video_file')->store('sliders/videos', 'public');
        }

        unset(
            $validated['image_file'], $validated['image_url'],
            $validated['tablet_image_file'], $validated['tablet_image_url'],
            $validated['mobile_image_file'], $validated['mobile_image_url']
        );

        $validated['media_type'] = $request->input('media_type', $slider->media_type ?? 'image');
        $validated['is_active'] = $request->boolean('is_active');
        $validated['sort_order'] = (int) ($validated['sort_order'] ?? 0);
        $validated['badge_color'] = ($validated['badge_color'] ?? null) ?: 'amber';

        $slider->update($validated);

        return redirect()->route('admin.sliders.index')->with('success', 'Homepage slide updated successfully.');
    }

    /**
     * Remove the specified slider from storage.
     */
    public function destroy(Slider $slider): RedirectResponse
    {
        if ($slider->image && Storage::disk('public')->exists($slider->image)) {
            Storage::disk('public')->delete($slider->image);
        }

        $slider->delete();

        return redirect()->route('admin.sliders.index')->with('success', 'Homepage slide deleted.');
    }

    /**
     * Quick toggle active status.
     */
    public function toggleStatus(Slider $slider): RedirectResponse
    {
        $slider->update(['is_active' => ! $slider->is_active]);

        $statusText = $slider->is_active ? 'activated' : 'deactivated';

        return back()->with('success', "Slide #{$slider->id} {$statusText} successfully.");
    }
}

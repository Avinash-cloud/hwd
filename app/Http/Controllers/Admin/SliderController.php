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
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
            'image_url' => 'nullable|string|max:1000',
            'button_text' => 'nullable|string|max:100',
            'button_link' => 'nullable|string|max:255',
            'secondary_button_text' => 'nullable|string|max:100',
            'secondary_button_link' => 'nullable|string|max:255',
            'badge_color' => 'nullable|string|max:50',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        if (! $request->hasFile('image_file') && empty($validated['image_url'])) {
            return back()->withInput()->withErrors(['image_file' => 'Please provide an image file or an image URL for the slide.']);
        }

        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('sliders', 'public');
            $validated['image'] = $path;
        } else {
            $validated['image'] = $validated['image_url'];
        }

        unset($validated['image_file'], $validated['image_url']);

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
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
            'image_url' => 'nullable|string|max:1000',
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
            $path = $request->file('image_file')->store('sliders', 'public');
            $validated['image'] = $path;
        } elseif (! empty($validated['image_url'])) {
            $validated['image'] = $validated['image_url'];
        }

        unset($validated['image_file'], $validated['image_url']);

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

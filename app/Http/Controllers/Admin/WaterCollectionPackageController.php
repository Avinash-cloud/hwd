<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WaterCollectionPackage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WaterCollectionPackageController extends Controller
{
    /**
     * Display a listing of water collection packages / dynamic pricing tiers.
     */
    public function index(): View
    {
        if (WaterCollectionPackage::hasTable()) {
            WaterCollectionPackage::seedDefaultPackagesIfEmpty();
            $packages = WaterCollectionPackage::orderBy('sort_order')->orderBy('quantity_litres')->get();
        } else {
            $packages = collect();
        }

        return view('admin.water-packages.index', compact('packages'));
    }

    /**
     * Store a newly created water quantity & price tier.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'quantity_litres' => ['required', 'numeric', 'min:0.1', 'max:1000'],
            'unit' => ['required', 'string', 'max:50'],
            'container_type' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0', 'max:1000000'],
            'original_price' => ['nullable', 'numeric', 'min:0', 'max:1000000'],
            'description' => ['nullable', 'string', 'max:1000'],
            'badge' => ['nullable', 'string', 'max:100'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['sort_order'] = (int) ($validated['sort_order'] ?? 0);

        WaterCollectionPackage::create($validated);

        return redirect()->route('admin.water-collection-packages.index')
            ->with('success', 'Water collection package & dynamic price tier created successfully.');
    }

    /**
     * Update the specified water collection package & price tier.
     */
    public function update(Request $request, WaterCollectionPackage $package): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'quantity_litres' => ['required', 'numeric', 'min:0.1', 'max:1000'],
            'unit' => ['required', 'string', 'max:50'],
            'container_type' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0', 'max:1000000'],
            'original_price' => ['nullable', 'numeric', 'min:0', 'max:1000000'],
            'description' => ['nullable', 'string', 'max:1000'],
            'badge' => ['nullable', 'string', 'max:100'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['sort_order'] = (int) ($validated['sort_order'] ?? 0);

        $package->update($validated);

        return redirect()->route('admin.water-collection-packages.index')
            ->with('success', "Package '{$package->name}' updated successfully.");
    }

    /**
     * Toggle package active status.
     */
    public function toggleActive(WaterCollectionPackage $package): RedirectResponse
    {
        $package->update([
            'is_active' => ! $package->is_active,
        ]);

        $status = $package->is_active ? 'activated' : 'deactivated';

        return back()->with('success', "Package '{$package->name}' has been {$status}.");
    }

    /**
     * Remove the specified package.
     */
    public function destroy(WaterCollectionPackage $package): RedirectResponse
    {
        $name = $package->name;
        $package->delete();

        return redirect()->route('admin.water-collection-packages.index')
            ->with('success', "Package '{$name}' deleted successfully.");
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MembershipBenefitController extends Controller
{
    /**
     * Show membership monthly benefit catalog management.
     */
    public function index(): View
    {
        $products = Product::with('category')->orderBy('name')->get();
        $eligibleCount = $products->where('is_member_free_eligible', true)->count();

        return view('admin.memberships.benefits', compact('products', 'eligibleCount'));
    }

    /**
     * Update the list of products eligible for monthly member free redemption.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'eligible_product_ids' => 'nullable|array',
            'eligible_product_ids.*' => 'exists:products,id',
        ]);

        $eligibleIds = $validated['eligible_product_ids'] ?? [];

        // Mass update: mark selected products as eligible, others as not eligible
        Product::query()->update(['is_member_free_eligible' => false]);

        if (! empty($eligibleIds)) {
            Product::whereIn('id', $eligibleIds)->update(['is_member_free_eligible' => true]);
        }

        $count = count($eligibleIds);

        return back()->with('success', "Membership benefits updated successfully. {$count} product(s) are now eligible for the monthly member free offering.");
    }
}

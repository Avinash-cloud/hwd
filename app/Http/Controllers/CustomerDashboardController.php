<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Product;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerDashboardController extends Controller
{
    /**
     * Show customer portal / dashboard.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $membership = $user->activeMembership;
        $orders = $user->orders()->with(['batch', 'items.product'])->take(5)->get();
        $addresses = $user->addresses()->latest()->get();

        // Find primary Gangajal product for 1-click monthly order
        $monthlyGangajal = Product::where('is_gangajal', true)
            ->where('is_active', true)
            ->orderBy('volume_ml', 'asc')
            ->first();

        $membershipFee = SiteSetting::get('membership_fee', '500');
        $shippingRate = SiteSetting::get('shipping_charge', '149');

        return view('dashboard', compact(
            'user',
            'membership',
            'orders',
            'addresses',
            'monthlyGangajal',
            'membershipFee',
            'shippingRate'
        ));
    }

    /**
     * Save a new address in address book.
     */
    public function storeAddress(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'recipient_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address_line1' => 'required|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'landmark' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postal_code' => 'required|string|max:10',
            'is_default' => 'nullable|boolean',
        ]);

        if ($request->boolean('is_default') || $user->addresses()->count() === 0) {
            $user->addresses()->update(['is_default' => false]);
            $validated['is_default'] = true;
        }

        $user->addresses()->create($validated);

        return back()->with('success', 'New shipping address added to your address book.');
    }

    /**
     * Delete an address.
     */
    public function deleteAddress(int $id, Request $request): RedirectResponse
    {
        $user = $request->user();
        $address = $user->addresses()->findOrFail($id);
        $address->delete();

        return back()->with('success', 'Address removed successfully.');
    }
}

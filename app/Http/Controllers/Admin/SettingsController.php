<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function index(): View
    {
        $settings = [
            'membership_fee' => SiteSetting::get('membership_fee', '500'),
            'membership_validity_years' => SiteSetting::get('membership_validity_years', '5'),
            'shipping_charge' => SiteSetting::get('shipping_charge', '149'),
            'free_first_bottle_enabled' => SiteSetting::get('free_first_bottle_enabled', '1'),
            'support_phone' => SiteSetting::get('support_phone', '+91 98765 43210'),
            'support_email' => SiteSetting::get('support_email', 'bliss@haridwarbliss.com'),
            'ghat_location_default' => SiteSetting::get('ghat_location_default', 'Har Ki Pauri, Brahmakund, Haridwar, Uttarakhand'),
        ];

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'membership_fee' => 'required|numeric|min:1',
            'membership_validity_years' => 'required|integer|min:1|max:20',
            'shipping_charge' => 'required|numeric|min:0',
            'free_first_bottle_enabled' => 'nullable|boolean',
            'support_phone' => 'required|string|max:50',
            'support_email' => 'required|email|max:100',
            'ghat_location_default' => 'required|string|max:255',
        ]);

        SiteSetting::set('membership_fee', $validated['membership_fee']);
        SiteSetting::set('membership_validity_years', $validated['membership_validity_years']);
        SiteSetting::set('shipping_charge', $validated['shipping_charge']);
        SiteSetting::set('free_first_bottle_enabled', $request->boolean('free_first_bottle_enabled') ? '1' : '0');
        SiteSetting::set('support_phone', $validated['support_phone']);
        SiteSetting::set('support_email', $validated['support_email']);
        SiteSetting::set('ghat_location_default', $validated['ghat_location_default']);

        return back()->with('success', 'Dynamic pricing rules and platform settings updated successfully.');
    }
}

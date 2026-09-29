<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PageController extends Controller
{
    public function about(): View
    {
        $ghatLocation = SiteSetting::get('ghat_location_default', 'Har Ki Pauri, Brahmakund, Haridwar');

        return view('pages.about', compact('ghatLocation'));
    }

    public function faqs(): View
    {
        $membershipFee = SiteSetting::get('membership_fee', '500');
        $shippingRate = SiteSetting::get('shipping_charge', '149');

        return view('pages.faqs', compact('membershipFee', 'shippingRate'));
    }

    public function contact(): View
    {
        $supportPhone = SiteSetting::get('support_phone', '+91 98765 43210');
        $supportEmail = SiteSetting::get('support_email', 'bliss@haridwarbliss.com');

        return view('pages.contact', compact('supportPhone', 'supportEmail'));
    }

    public function submitContact(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        return back()->with('success', 'Thank you for contacting Haridwar Bliss. Our team will reach out to you shortly.');
    }

    public function terms(): View
    {
        return view('pages.terms');
    }

    public function privacy(): View
    {
        return view('pages.privacy');
    }
}

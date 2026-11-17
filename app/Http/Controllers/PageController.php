<?php

namespace App\Http\Controllers;

use App\Models\ContactInquiry;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PageController extends Controller
{
    public function about(): View
    {
        $ghatLocation = SiteSetting::get('ghat_location_default', 'Sacred Ganga Ghat, Haridwar');

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
        $supportPhone = SiteSetting::get('support_phone', '+91 7042482555');
        $supportEmail = SiteSetting::get('support_email', 'sdenterprisesghaziabad@gmail.com');

        return view('pages.contact', compact('supportPhone', 'supportEmail'));
    }

    public function onlinePuja(): View
    {
        $supportPhone = SiteSetting::get('support_phone', '+91 7042482555');
        $supportEmail = SiteSetting::get('support_email', 'sdenterprisesghaziabad@gmail.com');

        return view('pages.online-puja', compact('supportPhone', 'supportEmail'));
    }

    public function submitOnlinePujaBooking(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'package' => 'required|string|max:255',
            'devotee_name' => 'required|string|max:255',
            'gotra' => 'required|string|max:100',
            'puja_date' => 'required|date',
            'phone' => 'required|string|max:25',
            'email' => 'nullable|email|max:255',
            'occasion' => 'nullable|string|max:255',
            'shipping_address' => 'required|string|max:500',
            'special_instructions' => 'nullable|string|max:1000',
            'terms_agreed' => 'accepted',
        ]);

        $message = "Online Puja Booking Details:\n"
            ."Package: {$validated['package']}\n"
            ."Devotee Name: {$validated['devotee_name']}\n"
            ."Gotra: {$validated['gotra']}\n"
            ."Auspicious Date: {$validated['puja_date']}\n"
            .'Occasion: '.($validated['occasion'] ?? 'General Auspicious Blessing')."\n"
            ."Prasad Delivery Address: {$validated['shipping_address']}\n"
            .'Special Notes / Sankalp: '.($validated['special_instructions'] ?? 'None')."\n"
            ."Agreement: Devotee agreed to 100% Non-Refundable online puja terms.\n";

        ContactInquiry::create([
            'name' => $validated['devotee_name'],
            'email' => $validated['email'] ?? 'devotee@haridwarbliss.com',
            'phone' => $validated['phone'],
            'subject' => 'Online Puja Booking: '.$validated['package'],
            'message' => $message,
            'status' => 'unread',
        ]);

        return back()->with('booking_success', [
            'package' => $validated['package'],
            'devotee_name' => $validated['devotee_name'],
            'gotra' => $validated['gotra'],
            'puja_date' => $validated['puja_date'],
            'phone' => $validated['phone'],
        ]);
    }

    public function submitContact(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:25',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        ContactInquiry::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'status' => 'unread',
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

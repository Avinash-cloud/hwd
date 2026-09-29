<?php

namespace App\Http\Controllers;

use App\Models\Membership;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class MembershipController extends Controller
{
    /**
     * Show membership benefits and purchase page.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $membershipFee = (float) SiteSetting::get('membership_fee', 500);
        $validityYears = (int) SiteSetting::get('membership_validity_years', 5);
        $shippingRate = (float) SiteSetting::get('shipping_charge', 149);

        return view('membership.join', compact('user', 'membershipFee', 'validityYears', 'shippingRate'));
    }

    /**
     * Process membership payment and activate 5-year membership.
     */
    public function subscribe(Request $request): RedirectResponse
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login')->with('warning', 'Please log in or create an account to activate your membership.');
        }

        if ($user->hasActiveMembership()) {
            return redirect()->route('dashboard')->with('info', 'You already possess an active Haridwar Bliss Sacred Membership!');
        }

        $fee = (float) SiteSetting::get('membership_fee', 500);
        $years = (int) SiteSetting::get('membership_validity_years', 5);

        $paymentMethod = $request->input('payment_method', 'mock');
        $paymentId = $request->input('razorpay_payment_id', 'pay_HB_'.Str::random(12));

        // Create or renew membership
        Membership::create([
            'user_id' => $user->id,
            'status' => 'active',
            'fee_paid' => $fee,
            'validity_years' => $years,
            'starts_at' => now(),
            'expires_at' => now()->addYears($years),
            'payment_method' => $paymentMethod,
            'payment_id' => $paymentId,
            'terms_accepted' => true,
        ]);

        return redirect()->route('dashboard')->with(
            'success',
            'Har Har Gange! Your 5-Year Haridwar Bliss Sacred Membership has been successfully activated. You can now claim your monthly sacred Gangajal.'
        );
    }
}

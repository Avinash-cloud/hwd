<?php

namespace App\Http\Controllers;

use App\Models\ContactInquiry;
use App\Models\MembershipSubscription;
use App\Models\SiteSetting;
use App\Services\RazorpayService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PageController extends Controller
{
    public function __construct(protected RazorpayService $razorpayService) {}

    public function about(): View
    {
        $ghatLocation = SiteSetting::get('ghat_location_default', 'Sacred Ganga Ghat, Haridwar');

        return view('pages.about', compact('ghatLocation'));
    }

    public function faqs(): View
    {
        $membershipFee = SiteSetting::get('membership_fee', '500');
        $shippingRate = SiteSetting::get('shipping_charge', '149');
        $billing = MembershipSubscription::getMonthlyBillingDetails();
        $monthlyAutoDebit = $billing['total_monthly_charge'];
        $monthlyProductPrice = $billing['product_price'];
        $monthlyDeliveryCharge = $billing['delivery_charge'];

        return view('pages.faqs', compact('membershipFee', 'shippingRate', 'monthlyAutoDebit', 'monthlyProductPrice', 'monthlyDeliveryCharge'));
    }

    public function contact(Request $request): View
    {
        $supportPhone = SiteSetting::get('support_phone', '+91 7042482555');
        $supportEmail = SiteSetting::get('support_email', 'sales@haridwarbliss.com');

        return view('pages.contact', compact('supportPhone', 'supportEmail'));
    }

    public function onlinePuja(): View
    {
        $supportPhone = SiteSetting::get('support_phone', '+91 7042482555');
        $supportEmail = SiteSetting::get('support_email', 'sales@haridwarbliss.com');
        $razorpayKeyId = $this->razorpayService->getKeyId();

        return view('pages.online-puja', compact('supportPhone', 'supportEmail', 'razorpayKeyId'));
    }

    /**
     * Create a Razorpay order for online puja booking.
     */
    public function createRazorpayOrder(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'package' => ['required', 'string', 'max:255'],
            'devotee_name' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:25'],
            'email' => ['nullable', 'email', 'max:255'],
        ]);

        $packageName = $validated['package'];
        $amount = (str_contains($packageName, '11,000') || str_contains($packageName, '11000') || str_contains($packageName, 'पैकेज 2'))
            ? 11000.00
            : 5100.00;

        $receipt = 'puja_'.time().'_'.Str::random(6);

        $order = $this->razorpayService->createOrder($amount, $receipt, [
            'type' => 'online_puja_booking',
            'package' => $packageName,
            'devotee_name' => $validated['devotee_name'] ?? '',
            'phone' => $validated['phone'] ?? '',
        ]);

        return response()->json([
            'order_id' => $order['id'],
            'amount' => $order['amount'],
            'currency' => $order['currency'] ?? 'INR',
            'key' => $this->razorpayService->getKeyId(),
            'name' => 'Haridwar Bliss - Vedic Puja',
            'description' => $packageName,
            'prefill' => [
                'name' => $validated['devotee_name'] ?? '',
                'email' => $validated['email'] ?? '',
                'contact' => $validated['phone'] ?? '',
            ],
            'is_mock' => $order['is_mock'] ?? false,
        ]);
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
            'address_line' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'state' => ['required', 'string', 'max:100'],
            'pincode' => ['required', 'digits:6'],
            'special_instructions' => 'nullable|string|max:1000',
            'terms_agreed' => 'accepted',
            'payment_method' => 'nullable|string',
            'razorpay_payment_id' => 'nullable|string',
            'razorpay_order_id' => 'nullable|string',
            'razorpay_signature' => 'nullable|string',
        ]);

        $validated['shipping_address'] = "{$validated['address_line']}, {$validated['city']}, {$validated['state']} - {$validated['pincode']}";

        $packageName = $validated['package'];
        $amount = (str_contains($packageName, '11,000') || str_contains($packageName, '11000') || str_contains($packageName, 'पैकेज 2'))
            ? 11000.00
            : 5100.00;

        $paymentMethod = $validated['payment_method'] ?? 'razorpay';
        $razorpayPaymentId = $request->input('razorpay_payment_id');
        $razorpayOrderId = $request->input('razorpay_order_id');
        $razorpaySignature = $request->input('razorpay_signature');

        if ($paymentMethod === 'razorpay') {
            if ($razorpayOrderId && $razorpaySignature && $razorpayPaymentId) {
                $isValid = $this->razorpayService->verifyPaymentSignature($razorpayOrderId, $razorpayPaymentId, $razorpaySignature);
                if (! $isValid) {
                    return back()->withInput()->with('error', 'Razorpay payment signature verification failed. Please try again.');
                }
                $paymentStatus = 'paid';
            } elseif ($this->razorpayService->isDummy() && $razorpayPaymentId) {
                $paymentStatus = 'paid';
            } else {
                $paymentStatus = 'pending';
            }
        } else {
            $paymentStatus = 'unpaid';
        }

        $message = "Online Puja Booking Details:\n"
            ."Package: {$validated['package']}\n"
            ."Devotee Name: {$validated['devotee_name']}\n"
            ."Gotra: {$validated['gotra']}\n"
            ."Auspicious Date: {$validated['puja_date']}\n"
            .'Occasion: '.($validated['occasion'] ?? 'General Auspicious Blessing')."\n"
            ."Prasad Delivery Address: {$validated['shipping_address']}\n"
            .'Special Notes / Sankalp: '.($validated['special_instructions'] ?? 'None')."\n"
            ."Payment Method: {$paymentMethod}\n"
            ."Payment Status: {$paymentStatus}\n"
            .($razorpayPaymentId ? "Razorpay Payment ID: {$razorpayPaymentId}\n" : '')
            ."Agreement: Devotee agreed to Haridwar Ghat E-Puja Terms & Conditions (tiered cancellation & rescheduling policy).\n";

        ContactInquiry::create([
            'name' => $validated['devotee_name'],
            'email' => $validated['email'] ?? 'devotee@haridwarbliss.com',
            'phone' => $validated['phone'],
            'subject' => 'Online Puja Booking: '.$validated['package'],
            'amount' => $amount,
            'payment_method' => $paymentMethod,
            'payment_status' => $paymentStatus,
            'razorpay_payment_id' => $razorpayPaymentId,
            'razorpay_order_id' => $razorpayOrderId,
            'razorpay_signature' => $razorpaySignature,
            'message' => $message,
            'status' => 'unread',
        ]);

        return back()->with('booking_success', [
            'package' => $validated['package'],
            'devotee_name' => $validated['devotee_name'],
            'gotra' => $validated['gotra'],
            'puja_date' => $validated['puja_date'],
            'phone' => $validated['phone'],
            'amount' => $amount,
            'payment_status' => $paymentStatus,
            'payment_id' => $razorpayPaymentId,
        ]);
    }

    public function submitContact(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:25',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:3000',
        ]);

        ContactInquiry::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'status' => 'unread',
        ]);

        return back()->with('success', 'Jai Gange! Your message has been received. Our sewa team will respond within 24 hours.');
    }

    public function terms(): View
    {
        $membershipFee = (float) SiteSetting::get('membership_fee', 500);
        $validityYears = (int) SiteSetting::get('membership_validity_years', 5);
        $shippingRate = (float) SiteSetting::get('shipping_charge', 149);
        $supportEmail = SiteSetting::get('support_email', 'sales@haridwarbliss.com');
        $supportPhone = SiteSetting::get('support_phone', '+91 7042482555');

        $billing = MembershipSubscription::getMonthlyBillingDetails();
        $monthlyProduct = $billing['product'];
        $monthlyProductPrice = $billing['product_price'];
        $monthlyDeliveryCharge = $billing['delivery_charge'];
        $monthlyAutoDebit = $billing['total_monthly_charge'];
        $monthlyVolume = $monthlyProduct?->volume_ml ?? 500;

        return view('pages.terms', compact(
            'membershipFee',
            'validityYears',
            'shippingRate',
            'supportEmail',
            'supportPhone',
            'monthlyProduct',
            'monthlyProductPrice',
            'monthlyDeliveryCharge',
            'monthlyAutoDebit',
            'monthlyVolume'
        ));
    }

    public function privacy(): View
    {
        return view('pages.privacy');
    }

    public function whyChooseUs(): View
    {
        $membershipFee = (float) SiteSetting::get('membership_fee', 500);
        $validityYears = (int) SiteSetting::get('membership_validity_years', 5);
        $shippingRate = (float) SiteSetting::get('shipping_charge', 149);

        $billing = MembershipSubscription::getMonthlyBillingDetails();
        $monthlyProduct = $billing['product'];
        $monthlyProductPrice = $billing['product_price'];
        $monthlyDeliveryCharge = $billing['delivery_charge'];
        $monthlyAutoDebit = $billing['total_monthly_charge'];
        $monthlyVolume = $monthlyProduct?->volume_ml ?? 500;

        return view('pages.why-choose-us', compact(
            'membershipFee',
            'validityYears',
            'shippingRate',
            'monthlyProductPrice',
            'monthlyDeliveryCharge',
            'monthlyAutoDebit',
            'monthlyVolume'
        ));
    }

    public function cancellationPolicy(): View
    {
        $membershipFee = (float) SiteSetting::get('membership_fee', 500);
        $validityYears = (int) SiteSetting::get('membership_validity_years', 5);
        $supportEmail = SiteSetting::get('support_email', 'sales@haridwarbliss.com');
        $supportPhone = SiteSetting::get('support_phone', '+91 7042482555');

        $billing = MembershipSubscription::getMonthlyBillingDetails();
        $monthlyAutoDebit = $billing['total_monthly_charge'];

        return view('pages.cancellation-policy', compact(
            'membershipFee',
            'validityYears',
            'monthlyAutoDebit',
            'supportEmail',
            'supportPhone'
        ));
    }
}

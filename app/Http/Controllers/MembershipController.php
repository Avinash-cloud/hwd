<?php

namespace App\Http\Controllers;

use App\Models\Membership;
use App\Models\SiteSetting;
use App\Services\RazorpayService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class MembershipController extends Controller
{
    public function __construct(
        protected RazorpayService $razorpayService
    ) {}

    /**
     * Show membership benefits and purchase page.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $membershipFee = (float) SiteSetting::get('membership_fee', 500);
        $validityYears = (int) SiteSetting::get('membership_validity_years', 5);
        $shippingRate = (float) SiteSetting::get('shipping_charge', 149);
        $razorpayKeyId = $this->razorpayService->getKeyId();

        return view('membership.join', compact('user', 'membershipFee', 'validityYears', 'shippingRate', 'razorpayKeyId'));
    }

    /**
     * Create Razorpay order for membership checkout modal.
     */
    public function createRazorpayOrder(Request $request)
    {
        $user = $request->user();
        if (! $user) {
            return response()->json(['error' => 'Authentication required'], 401);
        }

        $fee = (float) SiteSetting::get('membership_fee', 500);
        $receipt = 'mem_rcpt_'.time().'_'.$user->id;

        $order = $this->razorpayService->createOrder($fee, $receipt, [
            'type' => 'membership_subscription',
            'user_id' => $user->id,
            'email' => $user->email,
        ]);

        return response()->json([
            'order_id' => $order['id'],
            'amount' => $order['amount'],
            'currency' => $order['currency'] ?? 'INR',
            'key' => $this->razorpayService->getKeyId(),
            'name' => 'Haridwar Bliss',
            'description' => '5-Year Sacred Devotee Membership',
            'prefill' => [
                'name' => $user->name,
                'email' => $user->email,
                'contact' => $user->phone ?? '',
            ],
            'is_mock' => $order['is_mock'] ?? false,
        ]);
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

        $request->validate([
            'terms_accepted' => 'required|accepted',
            'payment_method' => 'nullable|string',
        ], [
            'terms_accepted.accepted' => 'You must read and accept the Haridwar Bliss Membership Terms & Conditions to proceed with activation.',
        ]);

        $fee = (float) SiteSetting::get('membership_fee', 500);
        $years = (int) SiteSetting::get('membership_validity_years', 5);

        $paymentMethod = $request->input('payment_method', 'razorpay');
        $paymentId = $request->input('razorpay_payment_id', 'pay_HB_MEM_'.Str::random(10));
        $razorpayOrderId = $request->input('razorpay_order_id');
        $razorpaySignature = $request->input('razorpay_signature');

        // Verify Razorpay signature if provided
        if ($razorpayOrderId && $razorpaySignature) {
            $isValid = $this->razorpayService->verifyPaymentSignature($razorpayOrderId, $paymentId, $razorpaySignature);
            if (! $isValid) {
                return redirect()->back()->with('error', 'Payment verification failed. Please try again or contact support.');
            }
        }

        $invoiceNumber = 'HB-MEM-'.date('Ymd').'-'.strtoupper(Str::random(4));

        // Create membership with full audit trail
        $membership = Membership::create([
            'user_id' => $user->id,
            'status' => 'active',
            'fee_paid' => $fee,
            'validity_years' => $years,
            'starts_at' => now(),
            'expires_at' => now()->addYears($years),
            'payment_method' => $paymentMethod,
            'payment_id' => $paymentId,
            'invoice_number' => $invoiceNumber,
            'terms_accepted' => true,
            'terms_accepted_at' => now(),
            'terms_ip_address' => $request->ip(),
            'terms_user_agent' => $request->userAgent(),
            'terms_version' => '1.0',
        ]);

        return redirect()->route('dashboard')->with(
            'success',
            'Har Har Gange! Your 5-Year Haridwar Bliss Sacred Membership has been successfully activated. Invoice #'.$invoiceNumber.' has been generated.'
        );
    }

    /**
     * View and download membership tax invoice.
     */
    public function invoice(Request $request, Membership $membership): View
    {
        $user = $request->user();

        // Ensure user is authorized to view this invoice
        if ($user->id !== $membership->user_id && ! $user->is_admin) {
            abort(403, 'Unauthorized access to membership invoice.');
        }

        $taxBreakup = $membership->tax_breakup;

        return view('membership.invoice', compact('membership', 'taxBreakup'));
    }
}

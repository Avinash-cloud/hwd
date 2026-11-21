<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Batch;
use App\Models\Category;
use App\Models\Membership;
use App\Models\MembershipSubscription;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\SiteSetting;
use App\Models\SubscriptionLog;
use App\Services\GstService;
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
        $defaultAddress = $user ? ($user->addresses()->where('is_default', true)->first() ?? $user->addresses()->first()) : null;

        $billing = MembershipSubscription::getMonthlyBillingDetails();
        $monthlyProduct = $billing['product'];
        $monthlyProductPrice = $billing['product_price'];
        $monthlyDeliveryCharge = $billing['delivery_charge'];
        $monthlyAutoDebit = $billing['total_monthly_charge'];

        return view('membership.join', compact(
            'user',
            'membershipFee',
            'validityYears',
            'shippingRate',
            'razorpayKeyId',
            'defaultAddress',
            'monthlyProduct',
            'monthlyProductPrice',
            'monthlyDeliveryCharge',
            'monthlyAutoDebit'
        ));
    }

    /**
     * Create Razorpay subscription mandate & order for membership auto-debit checkout.
     */
    public function createRazorpayOrder(Request $request)
    {
        $user = $request->user();
        if (! $user) {
            return response()->json(['error' => 'Authentication required'], 401);
        }

        $fee = (float) SiteSetting::get('membership_fee', 500);
        $billing = MembershipSubscription::getMonthlyBillingDetails();
        $monthlyDebit = $billing['total_monthly_charge'];
        $productPrice = $billing['product_price'];
        $deliveryCharge = $billing['delivery_charge'];

        // 1. Create recurring monthly plan for auto-debit of product price + delivery charge
        $planId = $this->razorpayService->createPlan(
            name: 'Blu Bliss Monthly Sacred Gangajal Auto-Delivery',
            amountInRupees: $monthlyDebit,
            description: "Monthly recurring auto-order: Product (₹{$productPrice}) + Delivery (₹{$deliveryCharge})"
        );

        // 2. Create Razorpay recurring subscription (60 monthly cycles = 5 years) with upfront registration fee
        $sub = $this->razorpayService->createSubscription(
            planId: $planId,
            totalCount: 60,
            notes: [
                'type' => 'blu_bliss_membership_subscription',
                'user_id' => (string) $user->id,
                'email' => $user->email,
                'product_price' => (string) $productPrice,
                'delivery_charge' => (string) $deliveryCharge,
                'monthly_debit' => (string) $monthlyDebit,
            ],
            upfrontAddonAmount: $fee
        );

        // Fallback standard order
        $receipt = 'mem_rcpt_'.time().'_'.$user->id;
        $order = $this->razorpayService->createOrder($fee, $receipt, [
            'type' => 'membership_subscription',
            'user_id' => $user->id,
            'email' => $user->email,
        ]);

        return response()->json([
            'subscription_id' => $sub['id'],
            'order_id' => $order['id'],
            'amount' => $order['amount'],
            'monthly_amount' => $monthlyDebit,
            'product_price' => $productPrice,
            'delivery_charge' => $deliveryCharge,
            'currency' => 'INR',
            'key' => $this->razorpayService->getKeyId(),
            'name' => 'Haridwar Bliss',
            'description' => "Blu Bliss 5-Year Sacred Devotee Membership (Auto-Debit: ₹{$monthlyDebit}/mo)",
            'prefill' => [
                'name' => $user->name,
                'email' => $user->email,
                'contact' => $user->phone ?? '',
            ],
            'is_mock' => $sub['is_mock'] ?? false,
        ]);
    }

    /**
     * Process membership payment, register auto-debit mandate, and dispatch month 1 order.
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

        $validated = $request->validate([
            'terms_accepted' => 'required|accepted',
            'payment_method' => 'nullable|string',
            'shipping_address' => 'nullable|string|max:500',
            'shipping_city' => 'nullable|string|max:100',
            'shipping_state' => 'nullable|string|max:100',
            'shipping_pincode' => 'nullable|string|max:20',
            'shipping_phone' => 'nullable|string|max:25',
        ], [
            'terms_accepted.accepted' => 'You must read and accept the Blu Bliss Membership Terms & Conditions to proceed with activation.',
        ]);

        $defaultAddress = $user->addresses()->where('is_default', true)->first() ?? $user->addresses()->first();

        $shippingPhone = trim((string) ($request->input('shipping_phone') ?: ($defaultAddress?->phone ?? $user->phone ?? '9999999999')));
        $shippingAddressLine = trim((string) ($request->input('shipping_address') ?: ($defaultAddress?->address_line1 ?? 'Devotee Residential Address on file')));
        $shippingCity = trim((string) ($request->input('shipping_city') ?: ($defaultAddress?->city ?? 'Haridwar')));
        $shippingState = trim((string) ($request->input('shipping_state') ?: ($defaultAddress?->state ?? 'Uttarakhand')));
        $shippingPincode = trim((string) ($request->input('shipping_pincode') ?: ($defaultAddress?->postal_code ?? '249401')));

        $fee = (float) SiteSetting::get('membership_fee', 500);
        $years = (int) SiteSetting::get('membership_validity_years', 5);
        $shippingRate = (float) SiteSetting::get('shipping_charge', 149);

        $paymentMethod = $request->input('payment_method', 'razorpay_subscription');
        $paymentId = $request->input('razorpay_payment_id', 'pay_HB_MEM_'.Str::random(10));
        $subscriptionId = $request->input('razorpay_subscription_id');
        $razorpayOrderId = $request->input('razorpay_order_id');
        $razorpaySignature = $request->input('razorpay_signature');

        // Signature verification (support both subscription e-mandate and standard fallback)
        if ($subscriptionId && $paymentId && $razorpaySignature) {
            $isValid = $this->razorpayService->verifySubscriptionSignature($subscriptionId, $paymentId, $razorpaySignature);
            if (! $isValid) {
                return redirect()->back()->withInput()->with('error', 'Razorpay subscription verification failed. Please try again.');
            }
        } elseif ($razorpayOrderId && $razorpaySignature) {
            $isValid = $this->razorpayService->verifyPaymentSignature($razorpayOrderId, $paymentId, $razorpaySignature);
            if (! $isValid) {
                return redirect()->back()->withInput()->with('error', 'Payment verification failed. Please try again.');
            }
        }

        // Save or update shipping address on devotee profile
        $shippingAddressArray = [
            'recipient_name' => $user->name,
            'phone' => $shippingPhone,
            'email' => $user->email,
            'address_line1' => $shippingAddressLine,
            'city' => $shippingCity,
            'state' => $shippingState,
            'postal_code' => $shippingPincode,
            'country' => 'India',
        ];

        if (empty($user->phone)) {
            $user->update(['phone' => $shippingPhone]);
        }

        Address::updateOrCreate(
            ['user_id' => $user->id, 'is_default' => true],
            [
                'type' => 'shipping',
                'recipient_name' => $user->name,
                'phone' => $shippingPhone,
                'address_line1' => $shippingAddressLine,
                'city' => $shippingCity,
                'state' => $shippingState,
                'postal_code' => $shippingPincode,
                'country' => 'India',
            ]
        );

        $invoiceNumber = 'HB-MEM-'.date('Ymd').'-'.strtoupper(Str::random(4));

        // 1. Create 5-Year Membership
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

        // 2. Identify active product and monthly billing breakdown (Product Price + Delivery Charge)
        $billing = MembershipSubscription::getMonthlyBillingDetails();
        $monthlyGangajal = $billing['product'];
        $productPrice = $billing['product_price'];
        $deliveryCharge = $billing['delivery_charge'];
        $monthlyDebit = $billing['total_monthly_charge'];

        if (! $monthlyGangajal) {
            $cat = Category::firstOrCreate(['slug' => 'gangajal'], ['name' => 'Sacred Gangajal']);
            $monthlyGangajal = Product::create([
                'category_id' => $cat->id,
                'name' => 'Haridwar Sacred Gangajal 500ml',
                'slug' => 'haridwar-gangajal-500ml-'.Str::random(6),
                'price' => $productPrice ?: 50.00,
                'volume_ml' => 500,
                'is_gangajal' => true,
                'is_active' => true,
                'stock' => 999,
            ]);
            $productPrice = (float) $monthlyGangajal->price;
            $monthlyDebit = round($productPrice + $deliveryCharge, 2);
        }

        // 3. Create MembershipSubscription (Auto-Debit Profile)
        $subRecord = MembershipSubscription::create([
            'user_id' => $user->id,
            'membership_id' => $membership->id,
            'product_id' => $monthlyGangajal->id,
            'status' => 'active',
            'billing_interval_days' => 30,
            'billing_amount' => $monthlyDebit,
            'next_billing_date' => now()->addDays(30),
            'last_billed_at' => now(),
            'razorpay_subscription_id' => $subscriptionId ?? ('sub_'.Str::random(14)),
            'shipping_address' => $shippingAddressArray,
            'notes' => "Blu Bliss 5-Year Sacred Membership Auto-Debit: Product (₹{$productPrice}) + Delivery (₹{$deliveryCharge}) = ₹{$monthlyDebit}/mo",
        ]);

        // 4. Immediately create Month 1 initial dispatch Order
        $batch = Batch::where('status', 'ready')->latest('collection_date')->first();
        $orderNumber = 'HB-MEM-'.date('Ymd').'-'.strtoupper(Str::random(4));
        $qrToken = 'HB-QR-'.Str::uuid()->toString();

        // Month 1 entire order (bottle + shipping) is free as a membership welcome benefit.
        $productGstRate = (float) ($monthlyGangajal->gst_percentage ?? 18.00);

        $order = Order::create([
            'order_number' => $orderNumber,
            'user_id' => $user->id,
            'batch_id' => $batch?->id,
            'subtotal' => $productPrice,
            'total_taxable_amount' => 0.00,
            'total_gst_amount' => 0.00,
            'cgst_amount' => 0.00,
            'sgst_amount' => 0.00,
            'igst_amount' => 0.00,
            'discount_amount' => $monthlyDebit,
            'shipping_amount' => $deliveryCharge,
            'total_amount' => 0.00,
            'payment_method' => 'free',
            'payment_status' => 'paid',
            'payment_id' => $paymentId,
            'order_status' => 'confirmed',
            'inventory_deducted' => false,
            'qr_token' => $qrToken,
            'shipping_address' => $shippingAddressArray,
            'customer_notes' => "Month 1 complimentary Gangajal dispatch — entirely free as Blu Bliss 5-Year Membership welcome benefit (Product ₹{$productPrice} + Delivery ₹{$deliveryCharge} waived).",
        ]);

        if ($monthlyGangajal) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $monthlyGangajal->id,
                'product_name' => $monthlyGangajal->name,
                'hsn_code' => $monthlyGangajal->hsn_code ?? '22019010',
                'gst_percentage' => $productGstRate,
                'taxable_amount' => 0.00,
                'gst_amount' => 0.00,
                'unit_price' => $productPrice,
                'quantity' => 1,
                'total_price' => 0.00,
                'is_free_monthly_bottle' => true,
            ]);
        }

        // 5. Log subscription activation
        SubscriptionLog::create([
            'subscription_id' => $subRecord->id,
            'event' => 'subscription_activated',
            'amount' => $fee,
            'order_id' => $order->id,
            'payment_id' => $paymentId,
            'details' => [
                'order_number' => $orderNumber,
                'invoice_number' => $invoiceNumber,
                'note' => '5-Year sacred membership activated. First bottle dispatched free as welcome benefit. Auto-debit begins month 2.',
            ],
        ]);

        return redirect()->route('dashboard')->with(
            'success',
            'Har Har Gange! Your Blu Bliss 5-Year Sacred Membership has been activated with automated monthly deliveries. Month 1 Gangajal delivery (Order #'.$orderNumber.') has been confirmed!'
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

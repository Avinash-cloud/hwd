<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Batch;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\SiteSetting;
use App\Models\User;
use App\Services\CartService;
use App\Services\RazorpayService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function __construct(
        protected CartService $cartService,
        protected RazorpayService $razorpayService
    ) {}

    /**
     * Show checkout page with address selection, guest checkout fields, and pricing breakdown.
     */
    public function index(Request $request): View|RedirectResponse
    {
        $user = $request->user();
        $guestCheckoutEnabled = SiteSetting::get('guest_checkout_enabled', '0') === '1';

        if (! $user && ! $guestCheckoutEnabled) {
            return redirect()->route('login')->with(
                'warning',
                'Please sign in to continue to checkout. You may also join our 5-Year Membership.'
            );
        }

        if ($user && ! $user->hasActiveMembership() && ! $user->is_admin && ! $guestCheckoutEnabled) {
            return redirect()->route('membership.join')->with(
                'warning',
                'Only active members can place orders for sacred Gangajal. Please activate your 5-Year Membership to continue.'
            );
        }

        $cartDetails = $this->cartService->getDetails($user);

        if (empty($cartDetails['items'])) {
            return redirect()->route('cart.index')->with('info', 'Your cart is empty.');
        }

        $addresses = $user ? $user->addresses()->latest()->get() : collect();
        $defaultAddress = $user ? $user->defaultAddress : null;
        $razorpayKeyId = $this->razorpayService->getKeyId();

        return view('checkout.index', compact('cartDetails', 'user', 'addresses', 'defaultAddress', 'guestCheckoutEnabled', 'razorpayKeyId'));
    }

    /**
     * Create Razorpay order for cart checkout.
     */
    public function createRazorpayOrder(Request $request): JsonResponse
    {
        $user = $request->user();
        $cartDetails = $this->cartService->getDetails($user);

        if (empty($cartDetails['items'])) {
            return response()->json(['error' => 'Cart is empty'], 422);
        }

        $amount = (float) $cartDetails['total'];
        $receipt = 'rcpt_'.time().'_'.($user ? $user->id : 'guest');

        $order = $this->razorpayService->createOrder($amount, $receipt, [
            'type' => 'product_checkout',
            'user_id' => $user?->id ?? 'guest',
        ]);

        return response()->json([
            'order_id' => $order['id'],
            'amount' => $order['amount'],
            'currency' => $order['currency'] ?? 'INR',
            'key' => $this->razorpayService->getKeyId(),
            'name' => 'Haridwar Bliss',
            'description' => 'Sacred Brahmakund Order',
            'prefill' => [
                'name' => $user?->name ?? '',
                'email' => $user?->email ?? '',
                'contact' => $user?->phone ?? '',
            ],
            'is_mock' => $order['is_mock'] ?? false,
        ]);
    }

    /**
     * Place order and process checkout for both authenticated devotees and guest shoppers.
     */
    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();
        $guestCheckoutEnabled = SiteSetting::get('guest_checkout_enabled', '0') === '1';

        if (! $user && ! $guestCheckoutEnabled) {
            return redirect()->route('login')->with('warning', 'Please sign in to proceed with checkout.');
        }

        if ($user && ! $user->hasActiveMembership() && ! $user->is_admin && ! $guestCheckoutEnabled) {
            return redirect()->route('membership.join')->with('warning', 'Active membership required to checkout.');
        }

        $cartDetails = $this->cartService->getDetails($user);

        if (empty($cartDetails['items'])) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        // Prevent multiple free product orders in the same calendar month for members
        if ($cartDetails['free_bottle_applied'] && $user && $user->hasClaimedMonthlyFreeProduct()) {
            return redirect()->route('cart.index')->with(
                'warning',
                'You have already claimed your monthly complimentary devotee offering for this calendar month.'
            );
        }

        $useExisting = $user && $request->boolean('use_existing_address');
        $guestEmail = null;

        if ($useExisting && $request->filled('address_id')) {
            $addressModel = $user->addresses()->findOrFail($request->input('address_id'));
            $shippingAddress = [
                'recipient_name' => $addressModel->recipient_name,
                'phone' => $addressModel->phone,
                'email' => $user->email,
                'address_line1' => $addressModel->address_line1,
                'address_line2' => $addressModel->address_line2,
                'landmark' => $addressModel->landmark,
                'city' => $addressModel->city,
                'state' => $addressModel->state,
                'postal_code' => $addressModel->postal_code,
                'country' => $addressModel->country,
            ];
        } else {
            $validationRules = [
                'recipient_name' => 'required|string|max:255',
                'phone' => 'required|string|max:20',
                'address_line1' => 'required|string|max:255',
                'address_line2' => 'nullable|string|max:255',
                'landmark' => 'nullable|string|max:255',
                'city' => 'required|string|max:100',
                'state' => 'required|string|max:100',
                'postal_code' => 'required|string|max:10',
                'save_address' => 'nullable|boolean',
            ];

            if (! $user) {
                $validationRules['email'] = 'required|email|max:255';
                $validationRules['create_account'] = 'nullable|boolean';

                if ($request->boolean('create_account')) {
                    $validationRules['email'] = 'required|email|max:255|unique:users,email';
                    $validationRules['password'] = ['required', 'confirmed', Password::defaults()];
                }
            }

            $validated = $request->validate($validationRules);

            // Handle guest account creation if requested
            if (! $user && $request->boolean('create_account')) {
                $newUser = User::create([
                    'name' => $validated['recipient_name'],
                    'email' => $validated['email'],
                    'phone' => $validated['phone'],
                    'password' => Hash::make($request->input('password')),
                ]);

                Auth::login($newUser);
                $user = $newUser;

                Address::create([
                    'user_id' => $user->id,
                    'type' => 'shipping',
                    'recipient_name' => $validated['recipient_name'],
                    'phone' => $validated['phone'],
                    'address_line1' => $validated['address_line1'],
                    'address_line2' => $validated['address_line2'] ?? null,
                    'landmark' => $validated['landmark'] ?? null,
                    'city' => $validated['city'],
                    'state' => $validated['state'],
                    'postal_code' => $validated['postal_code'],
                    'country' => 'India',
                    'is_default' => true,
                ]);
            } elseif (! $user) {
                $guestEmail = strtolower(trim($validated['email']));
            } elseif ($user && $request->boolean('save_address')) {
                Address::create([
                    'user_id' => $user->id,
                    'type' => 'shipping',
                    'recipient_name' => $validated['recipient_name'],
                    'phone' => $validated['phone'],
                    'address_line1' => $validated['address_line1'],
                    'address_line2' => $validated['address_line2'] ?? null,
                    'landmark' => $validated['landmark'] ?? null,
                    'city' => $validated['city'],
                    'state' => $validated['state'],
                    'postal_code' => $validated['postal_code'],
                    'country' => 'India',
                    'is_default' => $user->addresses()->count() === 0,
                ]);
            }

            $shippingAddress = [
                'recipient_name' => $validated['recipient_name'],
                'phone' => $validated['phone'],
                'email' => $user ? $user->email : $guestEmail,
                'address_line1' => $validated['address_line1'],
                'address_line2' => $validated['address_line2'] ?? null,
                'landmark' => $validated['landmark'] ?? null,
                'city' => $validated['city'],
                'state' => $validated['state'],
                'postal_code' => $validated['postal_code'],
                'country' => 'India',
            ];
        }

        $paymentMethod = $request->input('payment_method', 'mock');
        $paymentStatus = in_array($paymentMethod, ['mock', 'razorpay']) ? 'paid' : 'pending';
        $paymentId = $request->input('razorpay_payment_id', 'pay_HB_ORD_'.Str::random(10));
        $razorpayOrderId = $request->input('razorpay_order_id');
        $razorpaySignature = $request->input('razorpay_signature');

        // Signature verification if provided
        if ($paymentMethod === 'razorpay' && $razorpayOrderId && $razorpaySignature) {
            $isValid = $this->razorpayService->verifyPaymentSignature($razorpayOrderId, $paymentId, $razorpaySignature);
            if (! $isValid) {
                return redirect()->back()->with('error', 'Razorpay payment signature verification failed. Please try again.');
            }
        }

        // Assign current active batch for Gangajal traceability
        $batch = Batch::where('status', 'ready')->latest('collection_date')->first();

        // Create Order
        $orderNumber = 'HB-ORD-'.date('Ymd').'-'.strtoupper(Str::random(4));
        $qrToken = 'HB-QR-'.Str::uuid()->toString();

        $order = Order::create([
            'order_number' => $orderNumber,
            'user_id' => $user?->id,
            'guest_email' => $guestEmail,
            'batch_id' => $batch?->id,
            'subtotal' => $cartDetails['subtotal'],
            'total_taxable_amount' => $cartDetails['total_taxable_amount'] ?? 0.00,
            'total_gst_amount' => $cartDetails['total_gst_amount'] ?? 0.00,
            'cgst_amount' => $cartDetails['cgst_amount'] ?? 0.00,
            'sgst_amount' => $cartDetails['sgst_amount'] ?? 0.00,
            'igst_amount' => $cartDetails['igst_amount'] ?? 0.00,
            'discount_amount' => $cartDetails['discount'],
            'coupon_code' => $cartDetails['coupon_code'],
            'coupon_discount' => $cartDetails['coupon_discount'],
            'shipping_amount' => $cartDetails['shipping'],
            'total_amount' => $cartDetails['total'],
            'payment_method' => $paymentMethod,
            'payment_status' => $paymentStatus,
            'payment_id' => $paymentId,
            'order_status' => 'new',
            'inventory_deducted' => false,
            'qr_token' => $qrToken,
            'shipping_address' => $shippingAddress,
            'customer_notes' => $request->input('customer_notes'),
        ]);

        // If user is authenticated, link any prior guest orders that matched this email
        if ($user) {
            Order::whereNull('user_id')
                ->where('guest_email', $user->email)
                ->update(['user_id' => $user->id, 'guest_email' => null]);
        }

        // Increment coupon usage count if coupon was used
        if ($cartDetails['coupon_code']) {
            Coupon::where('code', $cartDetails['coupon_code'])->increment('used_count');
        }

        // Create Order Items with variants and GST details
        foreach ($cartDetails['items'] as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product']->id,
                'variant_id' => $item['variant_id'] ?? null,
                'product_name' => $item['product']->name,
                'variant_name' => $item['variant_name'] ?? null,
                'hsn_code' => $item['hsn_code'] ?? '22019010',
                'gst_percentage' => $item['gst_percentage'] ?? 18.00,
                'taxable_amount' => $item['taxable_amount'] ?? 0.00,
                'gst_amount' => $item['gst_amount'] ?? 0.00,
                'unit_price' => $item['unit_price'],
                'quantity' => $item['quantity'],
                'total_price' => $item['line_total'],
                'is_free_monthly_bottle' => $item['has_free_bottle'],
            ]);
        }

        // Store session authorization for guest to view their order confirmation page
        session()->put('guest_order_access_'.$order->order_number, true);

        // Clear cart
        $this->cartService->clear();

        return redirect()->route('orders.show', $order->order_number)->with(
            'success',
            'Your sacred order has been confirmed! We are preparing your blessed shipment.'
        );
    }
}

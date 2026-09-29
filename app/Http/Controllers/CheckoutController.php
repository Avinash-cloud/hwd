<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Batch;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function __construct(
        protected CartService $cartService
    ) {}

    /**
     * Show checkout page with address selection and pricing breakdown.
     */
    public function index(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        if (! $user->hasActiveMembership() && ! $user->is_admin) {
            return redirect()->route('membership.join')->with(
                'warning',
                'Only active members can place orders for sacred Gangajal. Please activate your 5-Year Membership to continue.'
            );
        }

        $cartDetails = $this->cartService->getDetails($user);

        if (empty($cartDetails['items'])) {
            return redirect()->route('cart.index')->with('info', 'Your cart is empty.');
        }

        $addresses = $user->addresses()->latest()->get();
        $defaultAddress = $user->defaultAddress;

        return view('checkout.index', compact('cartDetails', 'user', 'addresses', 'defaultAddress'));
    }

    /**
     * Place order and process checkout.
     */
    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();

        if (! $user->hasActiveMembership() && ! $user->is_admin) {
            return redirect()->route('membership.join')->with('warning', 'Active membership required to checkout.');
        }

        $cartDetails = $this->cartService->getDetails($user);

        if (empty($cartDetails['items'])) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        // Validate address
        $useExisting = $request->boolean('use_existing_address');

        if ($useExisting && $request->filled('address_id')) {
            $addressModel = $user->addresses()->findOrFail($request->input('address_id'));
            $shippingAddress = [
                'recipient_name' => $addressModel->recipient_name,
                'phone' => $addressModel->phone,
                'address_line1' => $addressModel->address_line1,
                'address_line2' => $addressModel->address_line2,
                'landmark' => $addressModel->landmark,
                'city' => $addressModel->city,
                'state' => $addressModel->state,
                'postal_code' => $addressModel->postal_code,
                'country' => $addressModel->country,
            ];
        } else {
            $validatedAddress = $request->validate([
                'recipient_name' => 'required|string|max:255',
                'phone' => 'required|string|max:20',
                'address_line1' => 'required|string|max:255',
                'address_line2' => 'nullable|string|max:255',
                'landmark' => 'nullable|string|max:255',
                'city' => 'required|string|max:100',
                'state' => 'required|string|max:100',
                'postal_code' => 'required|string|max:10',
                'save_address' => 'nullable|boolean',
            ]);

            $shippingAddress = $validatedAddress;

            if ($request->boolean('save_address')) {
                Address::create([
                    'user_id' => $user->id,
                    'type' => 'shipping',
                    'recipient_name' => $validatedAddress['recipient_name'],
                    'phone' => $validatedAddress['phone'],
                    'address_line1' => $validatedAddress['address_line1'],
                    'address_line2' => $validatedAddress['address_line2'] ?? null,
                    'landmark' => $validatedAddress['landmark'] ?? null,
                    'city' => $validatedAddress['city'],
                    'state' => $validatedAddress['state'],
                    'postal_code' => $validatedAddress['postal_code'],
                    'country' => 'India',
                    'is_default' => $user->addresses()->count() === 0,
                ]);
            }
        }

        $paymentMethod = $request->input('payment_method', 'mock');
        $paymentStatus = in_array($paymentMethod, ['mock', 'razorpay']) ? 'paid' : 'pending';
        $paymentId = $request->input('razorpay_payment_id', 'pay_HB_ORD_'.Str::random(10));

        // Assign current active batch for Gangajal traceability
        $batch = Batch::where('status', 'ready')->latest('collection_date')->first();

        // Create Order
        $orderNumber = 'HB-ORD-'.date('Ymd').'-'.strtoupper(Str::random(4));
        $qrToken = 'HB-QR-'.Str::uuid()->toString();

        $order = Order::create([
            'order_number' => $orderNumber,
            'user_id' => $user->id,
            'batch_id' => $batch?->id,
            'subtotal' => $cartDetails['subtotal'],
            'discount_amount' => $cartDetails['discount'],
            'shipping_amount' => $cartDetails['shipping'],
            'total_amount' => $cartDetails['total'],
            'payment_method' => $paymentMethod,
            'payment_status' => $paymentStatus,
            'payment_id' => $paymentId,
            'order_status' => 'confirmed',
            'qr_token' => $qrToken,
            'shipping_address' => $shippingAddress,
            'customer_notes' => $request->input('customer_notes'),
        ]);

        // Create Order Items
        foreach ($cartDetails['items'] as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product']->id,
                'product_name' => $item['product']->name,
                'unit_price' => $item['unit_price'],
                'quantity' => $item['quantity'],
                'total_price' => $item['line_total'],
                'is_free_monthly_bottle' => $item['has_free_bottle'],
            ]);
        }

        // Clear cart
        $this->cartService->clear();

        return redirect()->route('orders.show', $order->order_number)->with(
            'success',
            'Your sacred order has been confirmed! We are preparing your blessed shipment.'
        );
    }
}

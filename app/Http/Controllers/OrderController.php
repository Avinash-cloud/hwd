<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Order;
use App\Models\User;
use App\Services\QrCodeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function __construct(
        protected QrCodeService $qrCodeService
    ) {}

    /**
     * Show customer order confirmation and details for both authenticated users and guests.
     */
    public function show(string $orderNumber, Request $request): View|RedirectResponse
    {
        $user = $request->user();

        $order = Order::with(['items.product', 'batch', 'user'])
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        $hasGuestSession = session('guest_order_access_'.$order->order_number, false);

        // Security authorization check
        if ($order->user_id) {
            if (! $user && ! $hasGuestSession) {
                return redirect()->route('login')->with('info', 'Please sign in to view your order details.');
            }

            if ($user && $user->id !== $order->user_id && ! $user->is_admin) {
                abort(403, 'Unauthorized to view this order.');
            }
        } else {
            // Guest order: allowed if session has recent access, or logged in user matches email or admin
            $allowed = $hasGuestSession
                || ($user && ($user->is_admin || strtolower($user->email) === strtolower((string) $order->guest_email)));

            if (! $allowed) {
                abort(403, 'Unauthorized to view this guest order.');
            }
        }

        $verificationUrl = url('/verify/'.$order->qr_token);
        $qrSvg = $this->qrCodeService->renderSvg($verificationUrl);

        return view('orders.show', compact('order', 'qrSvg', 'verificationUrl'));
    }

    /**
     * Allow a guest customer to create an account directly from their order confirmation page.
     */
    public function createAccountFromGuestOrder(string $orderNumber, Request $request): RedirectResponse
    {
        $order = Order::where('order_number', $orderNumber)->firstOrFail();

        if (! $order->isGuest()) {
            return back()->with('info', 'This order is already linked to a registered account.');
        }

        $email = $order->guest_email ?? ($order->shipping_address['email'] ?? null);

        if (! $email) {
            return back()->with('error', 'No customer email address associated with this order.');
        }

        if (User::where('email', $email)->exists()) {
            return redirect()->route('login')->with(
                'info',
                'An account with this email already exists. Please sign in to connect this order.'
            );
        }

        $request->validate([
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $name = $order->shipping_address['recipient_name'] ?? 'Devotee';
        $phone = $order->shipping_address['phone'] ?? null;

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'password' => Hash::make($request->input('password')),
        ]);

        // Save shipping address for user
        if (! empty($order->shipping_address)) {
            Address::create([
                'user_id' => $user->id,
                'type' => 'shipping',
                'recipient_name' => $order->shipping_address['recipient_name'] ?? $name,
                'phone' => $order->shipping_address['phone'] ?? $phone ?? '',
                'address_line1' => $order->shipping_address['address_line1'] ?? '',
                'address_line2' => $order->shipping_address['address_line2'] ?? null,
                'landmark' => $order->shipping_address['landmark'] ?? null,
                'city' => $order->shipping_address['city'] ?? '',
                'state' => $order->shipping_address['state'] ?? '',
                'postal_code' => $order->shipping_address['postal_code'] ?? '',
                'country' => $order->shipping_address['country'] ?? 'India',
                'is_default' => true,
            ]);
        }

        // Link all prior guest orders with this email to the new user
        Order::where('guest_email', $email)->update([
            'user_id' => $user->id,
            'guest_email' => null,
        ]);

        Auth::login($user);

        return redirect()->route('orders.show', $orderNumber)->with(
            'success',
            'Your devotee account has been created successfully! You can now track all your sacred orders from your dashboard.'
        );
    }

    /**
     * Display printable official invoice.
     */
    public function invoice(string $orderNumber, Request $request): View
    {
        $user = $request->user();

        $order = Order::with(['items.product', 'batch', 'user'])
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        $hasGuestSession = session('guest_order_access_'.$order->order_number, false);

        if ($order->user_id) {
            if ($user && $user->id !== $order->user_id && ! $user->is_admin) {
                abort(403, 'Unauthorized to view this invoice.');
            }

            if (! $user && ! $hasGuestSession) {
                abort(403, 'Unauthorized to view this invoice.');
            }
        } else {
            $allowed = $hasGuestSession
                || ($user && ($user->is_admin || strtolower($user->email) === strtolower((string) $order->guest_email)));

            if (! $allowed) {
                abort(403, 'Unauthorized to view this invoice.');
            }
        }

        $verificationUrl = url('/verify/'.$order->qr_token);
        $qrSvg = $this->qrCodeService->renderSvg($verificationUrl);

        return view('orders.invoice', compact('order', 'qrSvg', 'verificationUrl'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\SiteSetting;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(
        protected CartService $cartService
    ) {}

    /**
     * View shopping cart.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $cartDetails = $this->cartService->getDetails($user);
        $membershipFee = SiteSetting::get('membership_fee', '500');

        return view('cart.index', compact('cartDetails', 'user', 'membershipFee'));
    }

    /**
     * Get live cart details for slide-over cart drawer.
     */
    public function apiDetails(Request $request): JsonResponse
    {
        $user = $request->user();
        $cartDetails = $this->cartService->getDetails($user);

        return response()->json([
            'cart' => $cartDetails,
            'is_member' => (bool) ($user && ($user->hasActiveMembership() || $user->is_admin)),
            'guest_checkout_enabled' => SiteSetting::get('guest_checkout_enabled', '0') === '1',
        ]);
    }

    /**
     * Add product to cart with strict membership validation and optional size variant.
     */
    public function add(Request $request): RedirectResponse|JsonResponse
    {
        $user = $request->user();
        $guestCheckoutEnabled = SiteSetting::get('guest_checkout_enabled', '0') === '1';

        // Access Control (bypassable when guest checkout is enabled by admin)
        if (! $user && ! $guestCheckoutEnabled) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'auth_required' => true,
                    'redirect' => route('login'),
                    'message' => 'Please sign in to order sacred products.',
                ], 401);
            }

            return redirect()->route('login')->with(
                'warning',
                'Please sign in to order sacred products. If you are a new devotee, you may also join our 5-Year Membership.'
            );
        }

        if ($user && ! $user->hasActiveMembership() && ! $user->is_admin && ! $guestCheckoutEnabled) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'membership_required' => true,
                    'redirect' => route('membership.join'),
                    'message' => 'Ordering is reserved for 5-Year Haridwar Bliss members (₹500 for 5 years).',
                ], 403);
            }

            return redirect()->route('membership.join')->with(
                'warning',
                'Ordering sacred Gangajal and pooja essentials is reserved exclusively for Haridwar Bliss members. Please activate your 5-Year Membership for ₹500 to proceed.'
            );
        }

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'variant_id' => 'nullable|exists:product_variants,id',
            'quantity' => 'nullable|integer|min:1|max:50',
        ]);

        $productId = (int) $request->input('product_id');
        $variantId = $request->filled('variant_id') ? (int) $request->input('variant_id') : null;
        $quantity = (int) $request->input('quantity', 1);

        $product = Product::findOrFail($productId);

        if (! $product->is_active) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'This product is currently unavailable.'], 422);
            }

            return back()->with('error', 'This product is currently unavailable.');
        }

        $variantName = null;
        if ($variantId) {
            $variant = ProductVariant::where('product_id', $productId)->find($variantId);
            if (! $variant || ! $variant->is_active || $variant->stock <= 0) {
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json(['success' => false, 'message' => 'The selected size is currently out of stock.'], 422);
                }

                return back()->with('error', 'The selected size is currently out of stock.');
            }
            $variantName = $variant->name;
        } elseif (! $product->isInStock()) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'This sacred product is currently out of stock.'], 422);
            }

            return back()->with('error', 'This sacred product is currently out of stock.');
        }

        $this->cartService->add($productId, $quantity, $variantId);

        $productLabel = $variantName ? "{$product->name} ({$variantName})" : $product->name;

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "{$productLabel} added to your cart.",
                'cart' => $this->cartService->getDetails($user),
            ]);
        }

        return redirect()->route('cart.index')->with(
            'success',
            "{$productLabel} has been added to your sacred cart."
        );
    }

    /**
     * Update item quantity.
     */
    public function update(Request $request): RedirectResponse|JsonResponse
    {
        $request->validate([
            'product_id' => 'nullable',
            'item_key' => 'nullable|string',
            'quantity' => 'required|integer|min:0|max:50',
        ]);

        $key = $request->input('item_key') ?? $request->input('product_id');
        $this->cartService->update((string) $key, (int) $request->input('quantity'));

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'cart' => $this->cartService->getDetails($request->user()),
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Cart updated successfully.');
    }

    /**
     * Remove item from cart.
     */
    public function remove(Request $request): RedirectResponse|JsonResponse
    {
        $request->validate([
            'product_id' => 'nullable',
            'item_key' => 'nullable|string',
        ]);

        $key = $request->input('item_key') ?? $request->input('product_id');
        $this->cartService->remove((string) $key);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'cart' => $this->cartService->getDetails($request->user()),
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Item removed from sacred cart.');
    }

    /**
     * Apply promotional coupon code.
     */
    public function applyCoupon(Request $request): RedirectResponse|JsonResponse
    {
        $request->validate([
            'coupon_code' => 'required|string|max:50',
        ]);

        $result = $this->cartService->applyCoupon($request->input('coupon_code'), $request->user());

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => $result['success'],
                'message' => $result['message'],
                'cart' => $this->cartService->getDetails($request->user()),
            ], $result['success'] ? 200 : 422);
        }

        if (! $result['success']) {
            return back()->with('error', $result['message']);
        }

        return back()->with('success', $result['message']);
    }

    /**
     * Remove promotional coupon.
     */
    public function removeCoupon(Request $request): RedirectResponse|JsonResponse
    {
        $this->cartService->removeCoupon();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Coupon removed.',
                'cart' => $this->cartService->getDetails($request->user()),
            ]);
        }

        return back()->with('success', 'Coupon code removed.');
    }
}

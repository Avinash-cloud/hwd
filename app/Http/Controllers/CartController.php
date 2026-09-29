<?php

namespace App\Http\Controllers;

use App\Models\Product;
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
        ]);
    }

    /**
     * Add product to cart with strict membership validation.
     */
    public function add(Request $request): RedirectResponse|JsonResponse
    {
        $user = $request->user();

        // Access Control as per SOW Section 3
        if (! $user) {
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

        if (! $user->hasActiveMembership() && ! $user->is_admin) {
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
            'quantity' => 'nullable|integer|min:1|max:50',
        ]);

        $productId = (int) $request->input('product_id');
        $quantity = (int) $request->input('quantity', 1);

        $product = Product::findOrFail($productId);

        if (! $product->is_active) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'This product is currently not available.'], 422);
            }

            return back()->with('error', 'This sacred product is currently not available.');
        }

        $this->cartService->add($productId, $quantity);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "{$product->name} added to your cart.",
                'cart' => $this->cartService->getDetails($user),
            ]);
        }

        return redirect()->route('cart.index')->with(
            'success',
            "{$product->name} has been added to your sacred cart."
        );
    }

    /**
     * Update item quantity.
     */
    public function update(Request $request): RedirectResponse|JsonResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:0|max:50',
        ]);

        $this->cartService->update((int) $request->input('product_id'), (int) $request->input('quantity'));

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
            'product_id' => 'required|integer',
        ]);

        $this->cartService->remove((int) $request->input('product_id'));

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'cart' => $this->cartService->getDetails($request->user()),
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Item removed from sacred cart.');
    }
}

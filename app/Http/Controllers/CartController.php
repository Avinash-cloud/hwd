<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\SiteSetting;
use App\Services\CartService;
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
     * Add product to cart with strict membership validation.
     */
    public function add(Request $request): RedirectResponse
    {
        $user = $request->user();

        // Access Control as per SOW Section 3
        if (! $user) {
            return redirect()->route('login')->with(
                'warning',
                'Please sign in to order sacred products. If you are a new devotee, you may also join our 5-Year Membership.'
            );
        }

        if (! $user->hasActiveMembership() && ! $user->is_admin) {
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
            return back()->with('error', 'This sacred product is currently not available.');
        }

        $this->cartService->add($productId, $quantity);

        return redirect()->route('cart.index')->with(
            'success',
            "{$product->name} has been added to your sacred cart."
        );
    }

    /**
     * Update item quantity.
     */
    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:0|max:50',
        ]);

        $this->cartService->update((int) $request->input('product_id'), (int) $request->input('quantity'));

        return redirect()->route('cart.index')->with('success', 'Cart updated successfully.');
    }

    /**
     * Remove item from cart.
     */
    public function remove(Request $request): RedirectResponse
    {
        $request->validate([
            'product_id' => 'required|integer',
        ]);

        $this->cartService->remove((int) $request->input('product_id'));

        return redirect()->route('cart.index')->with('success', 'Item removed from sacred cart.');
    }
}

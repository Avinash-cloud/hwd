<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Store or update a customer review for a product.
     * Enforces strict verified purchase customer requirement.
     */
    public function store(Request $request, Product $product): RedirectResponse
    {
        $user = $request->user();

        if (! $user->hasPurchasedProduct($product)) {
            return back()->with('error', 'Only verified devotees who have ordered this sacred product may submit a review.');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:150',
            'comment' => 'required|string|min:5|max:2000',
        ]);

        // Find the user's latest verified order containing this product
        $order = Order::where('user_id', $user->id)
            ->where('order_status', '!=', 'cancelled')
            ->whereHas('items', fn ($q) => $q->where('product_id', $product->id))
            ->latest()
            ->first();

        Review::updateOrCreate(
            [
                'product_id' => $product->id,
                'user_id' => $user->id,
            ],
            [
                'order_id' => $order?->id,
                'rating' => $validated['rating'],
                'title' => $validated['title'],
                'comment' => $validated['comment'],
                'is_verified_purchase' => true,
                'status' => 'pending',
                'approved_at' => null,
            ]
        );

        return back()->with('success', 'Thank you, Devotee! Your sacred review has been submitted for temple verification and will appear once approved by our administrator.');
    }
}

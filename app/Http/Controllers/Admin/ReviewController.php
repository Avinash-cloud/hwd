<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReviewController extends Controller
{
    /**
     * Display a listing of reviews with status filters and search.
     */
    public function index(Request $request): View
    {
        $counts = [
            'pending' => Review::where('status', 'pending')->count(),
            'approved' => Review::where('status', 'approved')->count(),
            'rejected' => Review::where('status', 'rejected')->count(),
            'all' => Review::count(),
        ];

        // Default to 'pending' if there are pending reviews, else 'all'
        $defaultStatus = $counts['pending'] > 0 ? 'pending' : 'all';
        $currentStatus = $request->has('status') ? $request->get('status') : $defaultStatus;

        $query = Review::with(['product', 'user', 'order'])->latest();

        if ($currentStatus && $currentStatus !== 'all') {
            $query->where('status', $currentStatus);
        }

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search): void {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('comment', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search): void {
                        $uq->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    })
                    ->orWhereHas('product', function ($pq) use ($search): void {
                        $pq->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $reviews = $query->paginate(15)->withQueryString();

        return view('admin.reviews.index', compact('reviews', 'counts', 'currentStatus'));
    }

    /**
     * Approve a customer review.
     */
    public function approve(Review $review): RedirectResponse
    {
        $review->update([
            'status' => 'approved',
            'approved_at' => now(),
        ]);

        $review->product->recalculateRating();

        return back()->with('success', "Review #{$review->id} from {$review->user->name} has been approved and published to storefront.");
    }

    /**
     * Reject a customer review.
     */
    public function reject(Request $request, Review $review): RedirectResponse
    {
        $validated = $request->validate([
            'admin_notes' => 'nullable|string|max:500',
        ]);

        $review->update([
            'status' => 'rejected',
            'approved_at' => null,
            'admin_notes' => $validated['admin_notes'] ?? 'Rejected by temple moderation.',
        ]);

        $review->product->recalculateRating();

        return back()->with('success', "Review #{$review->id} has been rejected.");
    }

    /**
     * Perform bulk moderation actions on reviews.
     */
    public function bulkUpdate(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'review_ids' => 'required|array',
            'review_ids.*' => 'exists:reviews,id',
            'bulk_action' => 'required|in:approve,reject,delete',
        ]);

        $reviews = Review::with('product')->whereIn('id', $validated['review_ids'])->get();
        $affectedProducts = [];

        foreach ($reviews as $review) {
            $affectedProducts[$review->product_id] = $review->product;

            if ($validated['bulk_action'] === 'approve') {
                $review->update([
                    'status' => 'approved',
                    'approved_at' => now(),
                ]);
            } elseif ($validated['bulk_action'] === 'reject') {
                $review->update([
                    'status' => 'rejected',
                    'approved_at' => null,
                    'admin_notes' => 'Rejected in bulk moderation.',
                ]);
            } elseif ($validated['bulk_action'] === 'delete') {
                $review->delete();
            }
        }

        foreach ($affectedProducts as $product) {
            $product?->recalculateRating();
        }

        $count = count($validated['review_ids']);

        return back()->with('success', "Successfully processed {$count} review(s).");
    }

    /**
     * Delete a review.
     */
    public function destroy(Review $review): RedirectResponse
    {
        $product = $review->product;
        $review->delete();
        $product->recalculateRating();

        return back()->with('success', 'Review deleted successfully.');
    }
}

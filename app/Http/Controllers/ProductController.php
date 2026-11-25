<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\MembershipSubscription;
use App\Models\Product;
use App\Models\SiteSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Display product catalog.
     */
    public function index(Request $request): View
    {
        $relations = ['category', 'batch', 'activeVariants'];
        if (Product::hasCategoryProductTable()) {
            $relations[] = 'categories';
        }
        $query = Product::with($relations)->where('is_active', true);

        if ($categorySlug = $request->get('category')) {
            $matchedCategory = Category::with('children')->where('slug', $categorySlug)->first();
            if ($matchedCategory) {
                $categoryIds = $matchedCategory->children->pluck('id')->push($matchedCategory->id)->all();
                if (Product::hasCategoryProductTable()) {
                    $query->where(function ($q) use ($categoryIds): void {
                        $q->whereIn('category_id', $categoryIds)
                            ->orWhereHas('categories', function ($catQ) use ($categoryIds): void {
                                $catQ->whereIn('categories.id', $categoryIds);
                            });
                    });
                } else {
                    $query->whereIn('category_id', $categoryIds);
                }
            }
        }

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search): void {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('short_description', 'like', "%{$search}%")
                    ->orWhereHas('activeVariants', function ($varQ) use ($search): void {
                        $varQ->where('name', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->get('type') === 'gangajal') {
            $query->where('is_gangajal', true);
        }

        $allProducts = $query->get();
        $expanded = Product::expandVariantsForListing($allProducts);

        if ($request->filled('min_price')) {
            $minPrice = (float) $request->input('min_price');
            $expanded = $expanded->filter(fn ($p) => $p->display_price >= $minPrice);
        }

        if ($request->filled('max_price')) {
            $maxPrice = (float) $request->input('max_price');
            $expanded = $expanded->filter(fn ($p) => $p->display_price <= $maxPrice);
        }

        $sort = $request->get('sort', 'featured');
        $expanded = match ($sort) {
            'price_low' => $expanded->sortBy('display_price'),
            'price_high' => $expanded->sortByDesc('display_price'),
            'rating_high' => $expanded->sortByDesc('rating'),
            'newest' => $expanded->sortByDesc('id'),
            default => $expanded->sortByDesc('is_bestseller')->sortByDesc('is_featured'),
        };
        $expanded = $expanded->values();

        $page = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 12;
        $currentPageItems = $expanded->slice(($page - 1) * $perPage, $perPage)->values();

        $products = new LengthAwarePaginator(
            $currentPageItems,
            $expanded->count(),
            $perPage,
            $page,
            ['path' => LengthAwarePaginator::resolveCurrentPath(), 'query' => $request->query()]
        );

        $categories = Category::with('children')->root()->where('is_active', true)->orderBy('sort_order')->get();
        $membershipFee = SiteSetting::get('membership_fee', '500');
        $validityYears = (int) SiteSetting::get('membership_validity_years', 5);
        $shippingRate = (float) SiteSetting::get('shipping_charge', 149);
        $billing = MembershipSubscription::getMonthlyBillingDetails();
        $monthlyAutoDebit = $billing['total_monthly_charge'];
        $monthlyVolume = $billing['product']?->volume_ml ?? 500;

        return view('products.index', compact(
            'products',
            'categories',
            'membershipFee',
            'validityYears',
            'shippingRate',
            'monthlyAutoDebit',
            'monthlyVolume'
        ));
    }

    /**
     * Predictive search API endpoint for live header search.
     */
    public function apiSearch(Request $request): JsonResponse
    {
        $term = trim((string) $request->input('q', ''));
        if (strlen($term) < 2) {
            return response()->json([]);
        }

        $hasPivot = Product::hasCategoryProductTable();
        $relations = ['category', 'activeVariants'];
        if ($hasPivot) {
            $relations[] = 'categories';
        }

        $rawResults = Product::with($relations)
            ->where('is_active', true)
            ->where(function ($q) use ($term, $hasPivot): void {
                $q->where('name', 'like', "%{$term}%")
                    ->orWhere('short_description', 'like', "%{$term}%")
                    ->orWhereHas('category', function ($catQ) use ($term): void {
                        $catQ->where('name', 'like', "%{$term}%");
                    });

                if ($hasPivot) {
                    $q->orWhereHas('categories', function ($catQ) use ($term): void {
                        $catQ->where('name', 'like', "%{$term}%");
                    });
                }

                $q->orWhereHas('activeVariants', function ($varQ) use ($term): void {
                    $varQ->where('name', 'like', "%{$term}%");
                });
            })
            ->take(8)
            ->get();

        $expanded = Product::expandVariantsForListing($rawResults);

        $results = $expanded
            ->take(8)
            ->map(function (Product $p): array {
                return [
                    'id' => $p->id,
                    'variant_id' => $p->active_variant?->id,
                    'name' => $p->display_name,
                    'slug' => $p->slug,
                    'url' => $p->display_url,
                    'image' => $p->image_url,
                    'in_stock' => $p->isDisplayInStock(),
                    'price' => $p->isDisplayInStock() ? (float) $p->display_price : null,
                    'compare_price' => $p->isDisplayInStock() && $p->display_compare_price ? (float) $p->display_compare_price : null,
                    'discount' => $p->isDisplayInStock() ? $p->display_discount : null,
                    'rating' => (float) $p->rating,
                    'category' => $p->category?->name,
                    'badge' => $p->badge,
                ];
            })
            ->values();

        return response()->json($results);
    }

    /**
     * Display single product detail.
     */
    public function show(string $slug): View
    {
        $hasPivot = Product::hasCategoryProductTable();
        $relations = [
            'category',
            'batch',
            'approvedReviews.user',
            'variants' => fn ($q) => $q->where('is_active', true)->orderBy('sort_order'),
        ];
        if ($hasPivot) {
            $relations[] = 'categories';
        }

        $product = Product::with($relations)
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $catIds = $product->category_ids;
        $relatedProducts = Product::where('id', '!=', $product->id)
            ->where('is_active', true)
            ->where(function ($q) use ($catIds, $product, $hasPivot): void {
                if ($hasPivot && ! empty($catIds)) {
                    $q->whereIn('category_id', $catIds)
                        ->orWhereHas('categories', fn ($cq) => $cq->whereIn('categories.id', $catIds));
                } else {
                    $q->where('category_id', $product->category_id);
                }
            })
            ->take(4)
            ->get();

        $allProducts = Product::select('id', 'name')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $membershipFee = SiteSetting::get('membership_fee', '500');
        $validityYears = (int) SiteSetting::get('membership_validity_years', 5);
        $shippingRate = SiteSetting::get('shipping_charge', '149');
        $user = auth()->user();
        $isVerifiedPurchaser = $product->canUserReview($user);
        $userReview = $product->getUserReview($user);

        return view('products.show', compact('product', 'relatedProducts', 'allProducts', 'membershipFee', 'validityYears', 'shippingRate', 'isVerifiedPurchaser', 'userReview'));
    }
}

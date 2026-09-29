<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\SiteSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Display product catalog.
     */
    public function index(Request $request): View
    {
        $query = Product::with(['category', 'batch'])->where('is_active', true);

        if ($categorySlug = $request->get('category')) {
            $matchedCategory = Category::with('children')->where('slug', $categorySlug)->first();
            if ($matchedCategory) {
                $categoryIds = $matchedCategory->children->pluck('id')->push($matchedCategory->id)->all();
                $query->whereIn('category_id', $categoryIds);
            }
        }

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search): void {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('short_description', 'like', "%{$search}%");
            });
        }

        if ($request->get('type') === 'gangajal') {
            $query->where('is_gangajal', true);
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', (float) $request->input('min_price'));
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', (float) $request->input('max_price'));
        }

        $sort = $request->get('sort', 'featured');
        match ($sort) {
            'price_low' => $query->orderBy('price', 'asc'),
            'price_high' => $query->orderBy('price', 'desc'),
            'rating_high' => $query->orderByDesc('rating'),
            'newest' => $query->latest(),
            default => $query->orderByDesc('is_bestseller')->orderByDesc('is_featured')->latest(),
        };

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::with('children')->root()->where('is_active', true)->orderBy('sort_order')->get();
        $membershipFee = SiteSetting::get('membership_fee', '500');

        return view('products.index', compact('products', 'categories', 'membershipFee'));
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

        $results = Product::with('category')
            ->where('is_active', true)
            ->where(function ($q) use ($term): void {
                $q->where('name', 'like', "%{$term}%")
                    ->orWhere('short_description', 'like', "%{$term}%")
                    ->orWhereHas('category', function ($catQ) use ($term): void {
                        $catQ->where('name', 'like', "%{$term}%");
                    });
            })
            ->take(8)
            ->get()
            ->map(function (Product $p): array {
                return [
                    'id' => $p->id,
                    'name' => $p->name,
                    'slug' => $p->slug,
                    'url' => route('products.show', $p->slug),
                    'image' => $p->image,
                    'price' => (float) $p->price,
                    'compare_price' => $p->compare_price ? (float) $p->compare_price : null,
                    'discount' => $p->discount_percentage,
                    'rating' => (float) $p->rating,
                    'category' => $p->category?->name,
                    'badge' => $p->badge,
                ];
            });

        return response()->json($results);
    }

    /**
     * Display single product detail.
     */
    public function show(string $slug): View
    {
        $product = Product::with(['category', 'batch'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->take(4)
            ->get();

        $membershipFee = SiteSetting::get('membership_fee', '500');
        $shippingRate = SiteSetting::get('shipping_charge', '149');

        return view('products.show', compact('product', 'relatedProducts', 'membershipFee', 'shippingRate'));
    }
}

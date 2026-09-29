<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\SiteSetting;
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
            $query->whereHas('category', function ($q) use ($categorySlug): void {
                $q->where('slug', $categorySlug);
            });
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

        $sort = $request->get('sort', 'featured');
        match ($sort) {
            'price_low' => $query->orderBy('price', 'asc'),
            'price_high' => $query->orderBy('price', 'desc'),
            'newest' => $query->latest(),
            default => $query->orderBy('is_featured', 'desc')->latest(),
        };

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();
        $membershipFee = SiteSetting::get('membership_fee', '500');

        return view('products.index', compact('products', 'categories', 'membershipFee'));
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

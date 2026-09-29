<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Category;
use App\Models\Product;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Show Haridwar Bliss sacred home page.
     */
    public function index(Request $request): View
    {
        $bestSellers = Product::with(['category', 'batch'])
            ->where('is_active', true)
            ->where(function ($q) {
                $q->where('is_bestseller', true)
                    ->orWhere('is_featured', true);
            })
            ->orderByDesc('rating')
            ->take(8)
            ->get();

        $pujaEssentials = Product::with(['category', 'batch'])
            ->where('is_active', true)
            ->whereHas('category', function ($q) {
                $q->where('slug', 'puja-essentials')
                    ->orWhere('slug', 'diya-batti');
            })
            ->take(6)
            ->get();

        $hawanSamagri = Product::with(['category', 'batch'])
            ->where('is_active', true)
            ->whereHas('category', function ($q) {
                $q->where('slug', 'hawan-samagri')
                    ->orWhere('slug', 'hawan-cups')
                    ->orWhere('slug', 'sambrani-cup');
            })
            ->take(6)
            ->get();

        $spiritualItems = Product::with(['category', 'batch'])
            ->where('is_active', true)
            ->whereHas('category', function ($q) {
                $q->where('slug', 'spiritual-items')
                    ->orWhere('slug', 'brass-pooja-ware');
            })
            ->take(6)
            ->get();

        $featuredGangajal = Product::with(['category', 'batch'])
            ->where('is_gangajal', true)
            ->where('is_active', true)
            ->orderBy('price')
            ->get();

        $featuredProducts = Product::with(['category', 'batch'])
            ->where('is_gangajal', false)
            ->where('is_featured', true)
            ->where('is_active', true)
            ->take(6)
            ->get();

        $rootCategories = Category::with('children')
            ->root()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $circularCategories = Category::root()
            ->where('is_active', true)
            ->where('is_featured', true)
            ->orderBy('sort_order')
            ->take(7)
            ->get();

        $categories = $rootCategories;

        $latestBatch = Batch::where('status', 'ready')
            ->latest('collection_date')
            ->first();

        $membershipFee = SiteSetting::get('membership_fee', '500');
        $shippingRate = SiteSetting::get('shipping_charge', '149');

        return view('welcome', compact(
            'bestSellers',
            'pujaEssentials',
            'hawanSamagri',
            'spiritualItems',
            'featuredGangajal',
            'featuredProducts',
            'rootCategories',
            'circularCategories',
            'categories',
            'latestBatch',
            'membershipFee',
            'shippingRate'
        ));
    }
}

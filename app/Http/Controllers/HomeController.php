<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Category;
use App\Models\Product;
use App\Models\SiteSetting;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Show Haridwar Bliss sacred home page.
     */
    public function index(Request $request): View
    {
        // 1. Dynamic Hero Slides
        $sliders = Slider::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        if ($sliders->isEmpty()) {
            $sliders = Slider::orderBy('sort_order')->get();
        }

        // 2. Best Sellers & Featured Offerings
        $bestSellers = Product::with(['category', 'batch'])
            ->where('is_active', true)
            ->where(function ($q): void {
                $q->where('is_bestseller', true)
                    ->orWhere('is_featured', true);
            })
            ->orderByDesc('is_bestseller')
            ->orderByDesc('rating')
            ->take(8)
            ->get();

        if ($bestSellers->isEmpty()) {
            $bestSellers = Product::with(['category', 'batch'])
                ->where('is_active', true)
                ->latest('id')
                ->take(8)
                ->get();
        }

        // 3. Puja & Hawan Essentials
        $pujaEssentials = Product::with(['category', 'batch'])
            ->where('is_active', true)
            ->whereHas('category', function ($q): void {
                $q->where('slug', 'puja-essentials')
                    ->orWhere('slug', 'diya-batti')
                    ->orWhere('slug', 'daily-kits');
            })
            ->take(6)
            ->get();

        if ($pujaEssentials->isEmpty()) {
            $pujaEssentials = Product::with(['category', 'batch'])
                ->where('is_active', true)
                ->where('is_gangajal', false)
                ->take(6)
                ->get();
        }

        // 4. Hawan Samagri
        $hawanSamagri = Product::with(['category', 'batch'])
            ->where('is_active', true)
            ->whereHas('category', function ($q): void {
                $q->where('slug', 'hawan-samagri')
                    ->orWhere('slug', 'hawan-cups')
                    ->orWhere('slug', 'sambrani-cup');
            })
            ->take(6)
            ->get();

        // 5. Spiritual Items & Metal Ware
        $spiritualItems = Product::with(['category', 'batch'])
            ->where('is_active', true)
            ->whereHas('category', function ($q): void {
                $q->where('slug', 'spiritual-items')
                    ->orWhere('slug', 'brass-pooja-ware')
                    ->orWhere('slug', 'rudraksha');
            })
            ->take(6)
            ->get();

        if ($spiritualItems->isEmpty()) {
            $spiritualItems = Product::with(['category', 'batch'])
                ->where('is_active', true)
                ->where('is_gangajal', false)
                ->skip(6)
                ->take(6)
                ->get();
        }

        // 6. Sacred Gangajal Editions
        $featuredGangajal = Product::with(['category', 'batch'])
            ->where('is_gangajal', true)
            ->where('is_active', true)
            ->orderBy('price')
            ->get();

        // 7. Categories & Navigation
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

        // 8. Latest Batch Traceability & Settings
        $latestBatch = Batch::where('status', 'ready')
            ->latest('collection_date')
            ->first();

        $membershipFee = SiteSetting::get('membership_fee', '500');
        $shippingRate = SiteSetting::get('shipping_charge', '149');

        return view('welcome', compact(
            'sliders',
            'bestSellers',
            'pujaEssentials',
            'hawanSamagri',
            'spiritualItems',
            'featuredGangajal',
            'rootCategories',
            'circularCategories',
            'categories',
            'latestBatch',
            'membershipFee',
            'shippingRate'
        ));
    }
}

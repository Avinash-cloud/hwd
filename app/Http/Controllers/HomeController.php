<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Category;
use App\Models\MembershipSubscription;
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
        $bestSellersQuery = Product::with(['category', 'batch', 'activeVariants'])
            ->where('is_active', true)
            ->where(function ($q): void {
                $q->where('is_bestseller', true)
                    ->orWhere('is_featured', true);
            })
            ->orderByDesc('is_bestseller')
            ->orderByDesc('rating')
            ->take(8)
            ->get();

        if ($bestSellersQuery->isEmpty()) {
            $bestSellersQuery = Product::with(['category', 'batch', 'activeVariants'])
                ->where('is_active', true)
                ->latest('id')
                ->take(8)
                ->get();
        }
        $bestSellers = Product::expandVariantsForListing($bestSellersQuery)->take(12);

        $hasPivot = Product::hasCategoryProductTable();
        $relations = ['category', 'batch', 'activeVariants'];
        if ($hasPivot) {
            $relations[] = 'categories';
        }

        // 3. Puja & Hawan Essentials
        $pujaEssentialsQuery = Product::with($relations)
            ->where('is_active', true)
            ->where(function ($q) use ($hasPivot): void {
                $q->whereHas('category', function ($cq): void {
                    $cq->where('slug', 'puja-essentials')
                        ->orWhere('slug', 'diya-batti')
                        ->orWhere('slug', 'daily-kits');
                });
                if ($hasPivot) {
                    $q->orWhereHas('categories', function ($cq): void {
                        $cq->where('slug', 'puja-essentials')
                            ->orWhere('slug', 'diya-batti')
                            ->orWhere('slug', 'daily-kits');
                    });
                }
            })
            ->take(6)
            ->get();

        if ($pujaEssentialsQuery->isEmpty()) {
            $pujaEssentialsQuery = Product::with($relations)
                ->where('is_active', true)
                ->where('is_gangajal', false)
                ->take(6)
                ->get();
        }
        $pujaEssentials = Product::expandVariantsForListing($pujaEssentialsQuery)->take(8);

        // 4. Hawan Samagri
        $hawanSamagriQuery = Product::with($relations)
            ->where('is_active', true)
            ->where(function ($q) use ($hasPivot): void {
                $q->whereHas('category', function ($cq): void {
                    $cq->where('slug', 'hawan-samagri')
                        ->orWhere('slug', 'hawan-cups')
                        ->orWhere('slug', 'sambrani-cup');
                });
                if ($hasPivot) {
                    $q->orWhereHas('categories', function ($cq): void {
                        $cq->where('slug', 'hawan-samagri')
                            ->orWhere('slug', 'hawan-cups')
                            ->orWhere('slug', 'sambrani-cup');
                    });
                }
            })
            ->take(6)
            ->get();
        $hawanSamagri = Product::expandVariantsForListing($hawanSamagriQuery)->take(8);

        // 5. Spiritual Items & Metal Ware
        $spiritualItemsQuery = Product::with($relations)
            ->where('is_active', true)
            ->where(function ($q) use ($hasPivot): void {
                $q->whereHas('category', function ($cq): void {
                    $cq->where('slug', 'spiritual-items')
                        ->orWhere('slug', 'brass-pooja-ware')
                        ->orWhere('slug', 'rudraksha');
                });
                if ($hasPivot) {
                    $q->orWhereHas('categories', function ($cq): void {
                        $cq->where('slug', 'spiritual-items')
                            ->orWhere('slug', 'brass-pooja-ware')
                            ->orWhere('slug', 'rudraksha');
                    });
                }
            })
            ->take(6)
            ->get();

        if ($spiritualItemsQuery->isEmpty()) {
            $spiritualItemsQuery = Product::with($relations)
                ->where('is_active', true)
                ->where('is_gangajal', false)
                ->skip(6)
                ->take(6)
                ->get();
        }
        $spiritualItems = Product::expandVariantsForListing($spiritualItemsQuery)->take(8);

        // 6. Sacred Gangajal Editions
        $featuredGangajalQuery = Product::with(['category', 'batch', 'activeVariants'])
            ->where('is_gangajal', true)
            ->where('is_active', true)
            ->orderBy('price')
            ->get();
        $featuredGangajal = Product::expandVariantsForListing($featuredGangajalQuery);

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
        $validityYears = (int) SiteSetting::get('membership_validity_years', 5);
        $shippingRate = SiteSetting::get('shipping_charge', '149');
        $billing = MembershipSubscription::getMonthlyBillingDetails();
        $monthlyAutoDebit = $billing['total_monthly_charge'];
        $monthlyProductPrice = $billing['product_price'];
        $monthlyDeliveryCharge = $billing['delivery_charge'];
        $monthlyVolume = $billing['product']?->volume_ml ?? 500;

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
            'validityYears',
            'shippingRate',
            'monthlyAutoDebit',
            'monthlyProductPrice',
            'monthlyDeliveryCharge',
            'monthlyVolume'
        ));
    }
}

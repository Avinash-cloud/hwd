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

        $categories = Category::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $latestBatch = Batch::where('status', 'ready')
            ->latest('collection_date')
            ->first();

        $membershipFee = SiteSetting::get('membership_fee', '500');
        $shippingRate = SiteSetting::get('shipping_charge', '149');

        return view('welcome', compact(
            'featuredGangajal',
            'featuredProducts',
            'categories',
            'latestBatch',
            'membershipFee',
            'shippingRate'
        ));
    }
}

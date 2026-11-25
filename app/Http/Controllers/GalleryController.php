<?php

namespace App\Http\Controllers;

use App\Models\GalleryItem;
use App\Models\MembershipSubscription;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GalleryController extends Controller
{
    /**
     * Display a listing of sacred gallery photos and video darshan.
     */
    public function index(Request $request): View
    {
        $query = GalleryItem::active();

        $currentCategory = $request->input('category');
        if ($currentCategory) {
            $query->where('category', $currentCategory);
        }

        $currentType = $request->input('type');
        if ($currentType && in_array($currentType, ['image', 'video'], true)) {
            $query->where('media_type', $currentType);
        }

        $items = $query->orderBy('sort_order')->latest('id')->paginate(12)->withQueryString();
        $categories = GalleryItem::active()->distinct()->pluck('category')->filter()->values();
        $membershipFee = (float) SiteSetting::get('membership_fee', 500);
        $validityYears = (int) SiteSetting::get('membership_validity_years', 5);
        $billing = MembershipSubscription::getMonthlyBillingDetails();
        $monthlyAutoDebit = $billing['total_monthly_charge'];

        return view('gallery.index', compact(
            'items',
            'categories',
            'currentCategory',
            'currentType',
            'membershipFee',
            'validityYears',
            'monthlyAutoDebit'
        ));
    }
}

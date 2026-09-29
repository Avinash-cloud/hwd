<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\Membership;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Show admin dashboard with analytics and recent activities.
     */
    public function index(): View
    {
        $totalUsers = User::count();
        $activeMembers = Membership::where('status', 'active')
            ->where('expires_at', '>', now())
            ->count();
        $expiredMembers = Membership::where(function ($q): void {
            $q->where('status', 'expired')
                ->orWhere('expires_at', '<=', now());
        })->count();

        $totalOrders = Order::count();
        $totalRevenue = (float) Order::where('payment_status', 'paid')->sum('total_amount');
        $membershipRevenue = (float) Membership::sum('fee_paid');
        $grandRevenue = $totalRevenue + $membershipRevenue;

        $recentOrders = Order::with(['user', 'batch'])->latest()->take(8)->get();
        $recentBatches = Batch::withCount('orders')->latest()->take(4)->get();
        $lowStockProducts = Product::where('stock', '<=', 10)->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'activeMembers',
            'expiredMembers',
            'totalOrders',
            'totalRevenue',
            'membershipRevenue',
            'grandRevenue',
            'recentOrders',
            'recentBatches',
            'lowStockProducts'
        ));
    }
}

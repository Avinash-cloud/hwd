<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('layouts.navigation', function ($view): void {
            if (Schema::hasTable('categories')) {
                $navCategories = Category::with('children')
                    ->root()
                    ->where('is_active', true)
                    ->orderBy('sort_order')
                    ->get();
                $view->with('navCategories', $navCategories);
            }

            if (Schema::hasTable('site_settings')) {
                $view->with('navMembershipFee', (float) SiteSetting::get('membership_fee', 500));
                $view->with('navValidityYears', (int) SiteSetting::get('membership_validity_years', 5));
            }
        });
    }
}

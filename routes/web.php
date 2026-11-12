<?php

use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\BatchController as AdminBatchController;
use App\Http\Controllers\Admin\BlogController as AdminBlogController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\ContactInquiryController as AdminContactInquiryController;
use App\Http\Controllers\Admin\CouponController as AdminCouponController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\GalleryController as AdminGalleryController;
use App\Http\Controllers\Admin\MembershipBenefitController as AdminMembershipBenefitController;
use App\Http\Controllers\Admin\MembershipController as AdminMembershipController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\SettingsController as AdminSettingsController;
use App\Http\Controllers\Admin\SliderController as AdminSliderController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CustomerDashboardController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MembershipController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RazorpayWebhookController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\TraceabilityController;
use Illuminate\Support\Facades\Route;

// Public Pages
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');

// Spiritual Blogs & Articles
Route::get('/blogs', [BlogController::class, 'index'])->name('blogs.index');
Route::get('/blogs/{slug}', [BlogController::class, 'show'])->name('blogs.show');

// Sacred Haridwar Gallery
Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery.index');

Route::get('/about-us', [PageController::class, 'about'])->name('about');
Route::get('/faqs', [PageController::class, 'faqs'])->name('faqs');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [PageController::class, 'submitContact'])->name('contact.submit');
Route::get('/terms', [PageController::class, 'terms'])->name('terms');
Route::get('/privacy', [PageController::class, 'privacy'])->name('privacy');

// Public Traceability QR Verification
Route::get('/verify/{token}', [TraceabilityController::class, 'verify'])->name('verify.batch');

// Membership Subscription (Guest or Auth)
Route::get('/membership/join', [MembershipController::class, 'index'])->name('membership.join');
Route::post('/membership/create-razorpay-order', [MembershipController::class, 'createRazorpayOrder'])->middleware('auth')->name('membership.razorpay.order');
Route::post('/membership/subscribe', [MembershipController::class, 'subscribe'])->middleware('auth')->name('membership.subscribe');
Route::get('/membership/invoice/{membership}', [MembershipController::class, 'invoice'])->middleware('auth')->name('membership.invoice');

// Shopping Cart (Public view, but add requires auth/membership)
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::get('/cart/api/details', [CartController::class, 'apiDetails'])->name('cart.api.details');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/coupon/apply', [CartController::class, 'applyCoupon'])->name('cart.coupon.apply');
Route::post('/cart/coupon/remove', [CartController::class, 'removeCoupon'])->name('cart.coupon.remove');

// Predictive Search API
Route::get('/api/search', [ProductController::class, 'apiSearch'])->name('api.search');

// Checkout (Accessible by members, or guests when guest checkout is enabled)
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout/razorpay-order', [CheckoutController::class, 'createRazorpayOrder'])->name('checkout.razorpay.order');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::post('/webhooks/razorpay', [RazorpayWebhookController::class, 'handle'])->name('webhooks.razorpay');

// Order Confirmation & Invoices (Supports authenticated devotees and guest orders)
Route::get('/orders/{orderNumber}', [OrderController::class, 'show'])->name('orders.show');
Route::get('/orders/{orderNumber}/invoice', [OrderController::class, 'invoice'])->name('orders.invoice');
Route::post('/orders/{orderNumber}/create-account', [OrderController::class, 'createAccountFromGuestOrder'])->name('orders.createAccount');

// Customer Protected Routes
Route::middleware(['auth'])->group(function () {
    // Customer Portal / Dashboard
    Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');
    Route::post('/my-account/addresses', [CustomerDashboardController::class, 'storeAddress'])->name('addresses.store');
    Route::delete('/my-account/addresses/{address}', [CustomerDashboardController::class, 'deleteAddress'])->name('addresses.delete');

    // Customer Review Submission (verified purchasers only)
    Route::post('/products/{product}/reviews', [ReviewController::class, 'store'])->name('reviews.store');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Panel (requires auth + admin middleware)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::resource('sliders', AdminSliderController::class)->except(['show']);
    Route::post('/sliders/{slider}/toggle', [AdminSliderController::class, 'toggleStatus'])->name('sliders.toggle');

    Route::resource('categories', AdminCategoryController::class)->except(['show']);
    Route::post('/categories/{category}/toggle-active', [AdminCategoryController::class, 'toggleActive'])->name('categories.toggleActive');
    Route::post('/categories/{category}/toggle-featured', [AdminCategoryController::class, 'toggleFeatured'])->name('categories.toggleFeatured');

    Route::resource('products', AdminProductController::class)->except(['show']);
    Route::post('/products/{product}/toggle-active', [AdminProductController::class, 'toggleActive'])->name('products.toggleActive');
    Route::post('/products/{product}/toggle-featured', [AdminProductController::class, 'toggleFeatured'])->name('products.toggleFeatured');
    Route::post('/products/{product}/toggle-bestseller', [AdminProductController::class, 'toggleBestseller'])->name('products.toggleBestseller');

    // Blogs & Articles
    Route::resource('blogs', AdminBlogController::class)->except(['show']);
    Route::post('/blogs/{blog}/toggle-published', [AdminBlogController::class, 'togglePublished'])->name('blogs.togglePublished');
    Route::post('/blogs/{blog}/toggle-featured', [AdminBlogController::class, 'toggleFeatured'])->name('blogs.toggleFeatured');

    // Media Gallery
    Route::resource('gallery', AdminGalleryController::class)->except(['show']);
    Route::post('/gallery/{gallery}/toggle-active', [AdminGalleryController::class, 'toggleActive'])->name('gallery.toggleActive');

    Route::resource('batches', AdminBatchController::class)->except(['show', 'destroy']);

    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::post('/orders/bulk-update', [AdminOrderController::class, 'bulkUpdate'])->name('orders.bulkUpdate');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');
    Route::get('/orders/{order}/packing-slip', [AdminOrderController::class, 'packingSlip'])->name('orders.packingSlip');

    Route::get('/memberships', [AdminMembershipController::class, 'index'])->name('memberships.index');
    Route::post('/memberships/{membership}/toggle', [AdminMembershipController::class, 'toggleStatus'])->name('memberships.toggle');
    Route::get('/membership-benefits', [AdminMembershipBenefitController::class, 'index'])->name('membership-benefits.index');
    Route::post('/membership-benefits', [AdminMembershipBenefitController::class, 'update'])->name('membership-benefits.update');

    Route::resource('/coupons', AdminCouponController::class)->except(['show'])->names('coupons');
    Route::post('/coupons/{coupon}/toggle', [AdminCouponController::class, 'toggleStatus'])->name('coupons.toggle');

    Route::resource('/contact-inquiries', AdminContactInquiryController::class)
        ->only(['index', 'show', 'update', 'destroy'])
        ->parameters(['contact-inquiries' => 'inquiry'])
        ->names('contact-inquiries');

    // Customer Reviews Moderation
    Route::get('/reviews', [AdminReviewController::class, 'index'])->name('reviews.index');
    Route::post('/reviews/bulk-update', [AdminReviewController::class, 'bulkUpdate'])->name('reviews.bulkUpdate');
    Route::post('/reviews/{review}/approve', [AdminReviewController::class, 'approve'])->name('reviews.approve');
    Route::post('/reviews/{review}/reject', [AdminReviewController::class, 'reject'])->name('reviews.reject');
    Route::delete('/reviews/{review}', [AdminReviewController::class, 'destroy'])->name('reviews.destroy');

    Route::get('/settings', [AdminSettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings', [AdminSettingsController::class, 'update'])->name('settings.update');

    Route::get('/reports', [AdminReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export/{type}', [AdminReportController::class, 'export'])->name('reports.export');

    // Admin Staff & RBAC Management
    Route::middleware(['permission:manage_admins'])->group(function () {
        Route::resource('admins', AdminUserController::class)->except(['show']);
        Route::post('admins/{admin}/toggle-active', [AdminUserController::class, 'toggleActive'])->name('admins.toggle-active');
    });
});

require __DIR__.'/auth.php';

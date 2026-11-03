<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductReviewAndStockVisibilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_verified_purchase_customer_can_submit_review_for_moderation(): void
    {
        $customer = User::factory()->create();
        $category = Category::create(['name' => 'Sacred Offerings', 'slug' => 'sacred-offerings']);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Authentic Brahmakund Gangajal 500ml',
            'slug' => 'gangajal-500ml',
            'price' => 149.00,
            'stock' => 10,
            'is_active' => true,
        ]);

        // Create verified purchase order for customer
        $order = Order::create([
            'order_number' => 'HB-VERIFY-001',
            'user_id' => $customer->id,
            'subtotal' => 149.00,
            'total_amount' => 149.00,
            'order_status' => 'delivered',
            'qr_token' => 'QR-V-001',
            'shipping_address' => ['recipient_name' => 'Devotee', 'address_line1' => 'Ghat', 'city' => 'Haridwar', 'state' => 'Uttarakhand', 'postal_code' => '249401'],
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'unit_price' => 149.00,
            'quantity' => 1,
            'total_price' => 149.00,
        ]);

        // Customer submits review
        $res = $this->actingAs($customer)->post("/products/{$product->id}/reviews", [
            'rating' => 5,
            'title' => 'Purest holy water on earth',
            'comment' => 'Received the hermetically sealed bottle with QR code intact. Supreme purity and peace.',
        ]);

        $res->assertSessionHas('success');
        $this->assertDatabaseHas('reviews', [
            'product_id' => $product->id,
            'user_id' => $customer->id,
            'rating' => 5,
            'title' => 'Purest holy water on earth',
            'is_verified_purchase' => true,
            'status' => 'pending',
        ]);

        // Pending review is not visible to guest / public on product page
        auth()->logout();
        $publicRes = $this->get("/products/{$product->slug}");
        $publicRes->assertStatus(200);
        $publicRes->assertDontSee('Purest holy water on earth');
    }

    public function test_customer_without_verified_purchase_cannot_submit_review(): void
    {
        $customer = User::factory()->create();
        $category = Category::create(['name' => 'Sacred Offerings', 'slug' => 'sacred-offerings']);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Brass Pooja Diya',
            'slug' => 'brass-pooja-diya',
            'price' => 299.00,
            'stock' => 10,
            'is_active' => true,
        ]);

        // Customer has NOT purchased the product
        $res = $this->actingAs($customer)->post("/products/{$product->id}/reviews", [
            'rating' => 5,
            'title' => 'Looks great',
            'comment' => 'I would love to order this later.',
        ]);

        $res->assertSessionHas('error');
        $this->assertDatabaseCount('reviews', 0);
    }

    public function test_guest_cannot_submit_review(): void
    {
        $category = Category::create(['name' => 'Sacred Offerings', 'slug' => 'sacred-offerings']);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Ganga Holy Water',
            'slug' => 'ganga-water',
            'price' => 149.00,
            'stock' => 5,
            'is_active' => true,
        ]);

        $res = $this->post("/products/{$product->id}/reviews", [
            'rating' => 5,
            'comment' => 'Guest comment',
        ]);

        $res->assertRedirect('/login');
    }

    public function test_admin_can_approve_review_and_it_shows_on_admin_and_storefront(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $customer = User::factory()->create(['name' => 'Ramesh Devotee']);

        $category = Category::create(['name' => 'Incense', 'slug' => 'incense']);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Cow Dung Hawan Cups 12pc',
            'slug' => 'hawan-cups-12pc',
            'price' => 199.00,
            'stock' => 10,
            'is_active' => true,
        ]);

        $review = Review::create([
            'product_id' => $product->id,
            'user_id' => $customer->id,
            'rating' => 5,
            'title' => 'Devine Hawan Fragrance',
            'comment' => 'The guggal and samagri aroma filled my home with spiritual tranquility.',
            'is_verified_purchase' => true,
            'status' => 'pending',
        ]);

        // 1. Admin views pending reviews
        $adminIndex = $this->actingAs($admin)->get('/admin/reviews?status=pending');
        $adminIndex->assertStatus(200);
        $adminIndex->assertSee('Devine Hawan Fragrance');
        $adminIndex->assertSee('Ramesh Devotee');

        // 2. Admin approves review
        $approveRes = $this->actingAs($admin)->post("/admin/reviews/{$review->id}/approve");
        $approveRes->assertSessionHas('success');

        $this->assertEquals('approved', $review->fresh()->status);
        $this->assertNotNull($review->fresh()->approved_at);

        // 3. Review is visible on admin approved tab
        $adminApproved = $this->actingAs($admin)->get('/admin/reviews?status=approved');
        $adminApproved->assertStatus(200);
        $adminApproved->assertSee('Devine Hawan Fragrance');

        // 4. Approved review is visible on storefront product page
        $storefront = $this->get("/products/{$product->slug}");
        $storefront->assertStatus(200);
        $storefront->assertSee('Devine Hawan Fragrance');
        $storefront->assertSee('The guggal and samagri aroma filled my home with spiritual tranquility.');
        $storefront->assertSee('Verified Purchase');
    }

    public function test_admin_can_reject_review(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $customer = User::factory()->create();

        $category = Category::create(['name' => 'Incense', 'slug' => 'incense']);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Incense Sticks',
            'slug' => 'incense-sticks',
            'price' => 99.00,
            'stock' => 10,
            'is_active' => true,
        ]);

        $review = Review::create([
            'product_id' => $product->id,
            'user_id' => $customer->id,
            'rating' => 1,
            'title' => 'Spam comment',
            'comment' => 'Irrelevant promotional spam link text here.',
            'is_verified_purchase' => true,
            'status' => 'pending',
        ]);

        $rejectRes = $this->actingAs($admin)->post("/admin/reviews/{$review->id}/reject", [
            'admin_notes' => 'Spam content rejected.',
        ]);
        $rejectRes->assertSessionHas('success');

        $this->assertEquals('rejected', $review->fresh()->status);

        // Not visible on storefront
        $storefront = $this->get("/products/{$product->slug}");
        $storefront->assertDontSee('Spam comment');
    }

    public function test_in_stock_product_shows_in_stock_without_units_count(): void
    {
        $category = Category::create(['name' => 'Sacred Offerings', 'slug' => 'sacred-offerings']);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Brahmakund Gangajal 500ml',
            'slug' => 'gangajal-instock-check',
            'price' => 149.00,
            'stock' => 47, // Exact units
            'is_active' => true,
        ]);

        $res = $this->get("/products/{$product->slug}");
        $res->assertStatus(200);

        // Shows "In Stock"
        $res->assertSee('In Stock');

        // Does NOT show the exact numeric units count
        $res->assertDontSee('47 units ready to dispatch');
        $res->assertDontSee('units ready to dispatch');

        // Shows prices
        $res->assertSee('₹149');
        $res->assertSee('Add to Sacred Cart');
        $res->assertSee('Buy It Now');
    }

    public function test_out_of_stock_product_shows_no_price_and_disables_cart_actions(): void
    {
        $category = Category::create(['name' => 'Pooja Essentials', 'slug' => 'pooja-essentials']);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Copper Panchpatra with Achmani Spoon',
            'slug' => 'copper-panchpatra',
            'price' => 450.00,
            'stock' => 0, // OUT OF STOCK
            'is_active' => true,
        ]);

        // 1. On Product Detail Page
        $res = $this->get("/products/{$product->slug}");
        $res->assertStatus(200);

        // Does NOT show numerical price
        $res->assertDontSee('₹450');

        // Shows "Currently Out of Stock"
        $res->assertSee('Currently Out of Stock');

        // Shows "Out of Stock" button instead of Buy It Now
        $res->assertSee('Out of Stock');
        $res->assertDontSee('Buy It Now');

        // 2. On Product Card (Catalog listing)
        $catalogRes = $this->get('/products');
        $catalogRes->assertStatus(200);
        $catalogRes->assertSee('Copper Panchpatra with Achmani Spoon');
        $catalogRes->assertSee('Out of Stock');

        // 3. Cart Controller blocks adding out of stock item
        $user = User::factory()->create();
        $cartRes = $this->actingAs($user)->post('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);
        $this->assertTrue($cartRes->isRedirect() || $cartRes->getStatusCode() === 422);
    }
}

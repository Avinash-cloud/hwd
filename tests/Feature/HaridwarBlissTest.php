<?php

namespace Tests\Feature;

use App\Models\Batch;
use App\Models\Category;
use App\Models\Membership;
use App\Models\Order;
use App\Models\Product;
use App\Models\SiteSetting;
use App\Models\User;
use App\Services\CartService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HaridwarBlissTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        SiteSetting::set('membership_fee', '500');
        SiteSetting::set('membership_validity_years', '5');
        SiteSetting::set('shipping_charge', '149');
        SiteSetting::set('free_first_bottle_enabled', '1');
    }

    public function test_home_page_renders_with_sacred_offerings(): void
    {
        $category = Category::create([
            'name' => 'Sacred Gangajal',
            'slug' => 'sacred-gangajal',
        ]);

        $batch = Batch::create([
            'batch_number' => 'HB-GANG-TEST-01',
            'sourcing_ghat' => 'Har Ki Pauri, Haridwar',
            'collection_date' => now(),
            'packaging_date' => now(),
            'status' => 'ready',
        ]);

        $product = Product::create([
            'category_id' => $category->id,
            'batch_id' => $batch->id,
            'name' => 'Brahmakund Gangajal 500ml',
            'slug' => 'brahmakund-gangajal-500ml',
            'price' => 149.00,
            'member_price' => 0.00,
            'is_gangajal' => true,
            'volume_ml' => 500,
            'stock' => 50,
            'is_active' => true,
            'is_featured' => true,
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Haridwar Bliss');
        $response->assertSee('Brahmakund Gangajal 500ml');
    }

    public function test_non_member_cannot_add_to_cart(): void
    {
        $category = Category::create(['name' => 'Gangajal', 'slug' => 'gangajal']);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Holy Gangajal',
            'slug' => 'holy-gangajal',
            'price' => 149.00,
            'stock' => 10,
        ]);

        $user = User::factory()->create();

        // User is authenticated but not a member
        $response = $this->actingAs($user)->post('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $response->assertRedirect(route('membership.join'));
    }

    public function test_user_can_purchase_5_year_membership(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/membership/subscribe', [
            'payment_method' => 'mock',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertTrue($user->fresh()->hasActiveMembership());

        $membership = $user->fresh()->activeMembership;
        $this->assertEquals(500.00, $membership->fee_paid);
        $this->assertEquals(5, $membership->validity_years);
    }

    public function test_active_member_monthly_pricing_automation(): void
    {
        $user = User::factory()->create();
        Membership::create([
            'user_id' => $user->id,
            'status' => 'active',
            'fee_paid' => 500.00,
            'validity_years' => 5,
            'starts_at' => now(),
            'expires_at' => now()->addYears(5),
            'terms_accepted' => true,
        ]);

        $category = Category::create(['name' => 'Gangajal', 'slug' => 'gangajal']);
        $gangajal = Product::create([
            'category_id' => $category->id,
            'name' => 'Brahmakund Gangajal 500ml',
            'slug' => 'gangajal-500ml',
            'price' => 149.00,
            'member_price' => 0.00,
            'is_gangajal' => true,
            'volume_ml' => 500,
            'stock' => 50,
            'is_active' => true,
        ]);

        $cartService = app(CartService::class);

        // Case 1: 1 bottle of Gangajal -> price is ₹0, shipping is ₹149, total is ₹149
        $cartService->add($gangajal->id, 1);
        $details1 = $cartService->getDetails($user);

        $this->assertEquals(149.00, $details1['subtotal']);
        $this->assertEquals(149.00, $details1['discount']); // 1st bottle free
        $this->assertEquals(149.00, $details1['shipping']);
        $this->assertEquals(149.00, $details1['total']);
        $this->assertTrue($details1['free_bottle_applied']);

        // Case 2: 2 bottles of Gangajal -> 1 free (149 off), 1 charged (149), shipping (149) -> total 298
        $cartService->clear();
        $cartService->add($gangajal->id, 2);
        $details2 = $cartService->getDetails($user);

        $this->assertEquals(298.00, $details2['subtotal']);
        $this->assertEquals(149.00, $details2['discount']);
        $this->assertEquals(149.00, $details2['shipping']);
        $this->assertEquals(298.00, $details2['total']);
    }

    public function test_batch_traceability_public_verification(): void
    {
        $batch = Batch::create([
            'batch_number' => 'HB-GANG-VERIFY-99',
            'sourcing_ghat' => 'Har Ki Pauri, Brahmakund, Haridwar',
            'collection_date' => now()->subDays(2),
            'packaging_date' => now()->subDay(),
            'purity_notes' => 'Laboratory Tested pH 7.8',
            'status' => 'ready',
        ]);

        $user = User::factory()->create();
        $order = Order::create([
            'order_number' => 'HB-ORD-TEST-99',
            'user_id' => $user->id,
            'batch_id' => $batch->id,
            'subtotal' => 149.00,
            'shipping_amount' => 149.00,
            'total_amount' => 149.00,
            'qr_token' => 'HB-QR-TEST-TOKEN-99',
            'shipping_address' => [
                'recipient_name' => 'Devotee Test',
                'phone' => '9988776655',
                'address_line1' => 'Street 1',
                'city' => 'Haridwar',
                'state' => 'Uttarakhand',
                'postal_code' => '249401',
            ],
        ]);

        $response = $this->get('/verify/HB-QR-TEST-TOKEN-99');

        $response->assertStatus(200);
        $response->assertSee('HB-GANG-VERIFY-99');
        $response->assertSee('Har Ki Pauri, Brahmakund, Haridwar');
        $response->assertSee('pH 7.8');
    }

    public function test_admin_access_control(): void
    {
        $regularUser = User::factory()->create(['is_admin' => false]);
        $adminUser = User::factory()->create(['is_admin' => true]);

        // Regular user blocked
        $this->actingAs($regularUser)->get('/admin')->assertStatus(403);

        // Admin allowed
        $this->actingAs($adminUser)->get('/admin')->assertStatus(200);
    }

    public function test_predictive_search_api_returns_matching_products(): void
    {
        $category = Category::create(['name' => 'Puja Essentials', 'slug' => 'puja-essentials']);
        Product::create([
            'category_id' => $category->id,
            'name' => 'Premium Long Lasting Cotton Wicks',
            'slug' => 'premium-cotton-wicks',
            'price' => 159.00,
            'compare_price' => 259.00,
            'stock' => 50,
        ]);

        $response = $this->getJson('/api/search?q=Cotton');

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'name' => 'Premium Long Lasting Cotton Wicks',
            'price' => 159,
        ]);
    }

    public function test_cart_api_endpoints_work_with_ajax(): void
    {
        $user = User::factory()->create();
        Membership::create([
            'user_id' => $user->id,
            'status' => 'active',
            'fee_paid' => 500,
            'validity_years' => 5,
            'starts_at' => now(),
            'expires_at' => now()->addYears(5),
            'terms_accepted' => true,
        ]);

        $category = Category::create(['name' => 'Hawan Samagri', 'slug' => 'hawan-samagri']);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Cow Dung Hawan Cups',
            'slug' => 'cow-dung-hawan-cups',
            'price' => 249.00,
            'stock' => 30,
        ]);

        $this->actingAs($user);

        // Add to cart via JSON
        $addRes = $this->postJson('/cart/add', ['product_id' => $product->id, 'quantity' => 2]);
        $addRes->assertStatus(200);
        $addRes->assertJsonFragment(['success' => true]);

        // Get details via JSON
        $detailsRes = $this->getJson('/cart/api/details');
        $detailsRes->assertStatus(200);
        $detailsRes->assertJsonFragment(['is_member' => true]);
        $this->assertEquals(2, $detailsRes->json('cart.total_items_count'));
    }
}

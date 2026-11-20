<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Membership;
use App\Models\Product;
use App\Models\SiteSetting;
use App\Models\User;
use App\Services\CartService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MembershipBenefitAndQuotaTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        SiteSetting::set('membership_fee', '500');
        SiteSetting::set('shipping_charge', '149');
        SiteSetting::set('free_first_bottle_enabled', '1');
    }

    public function test_admin_can_manage_membership_benefit_catalog(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $category = Category::create(['name' => 'Puja Essentials', 'slug' => 'puja-essentials']);

        $product1 = Product::create([
            'category_id' => $category->id,
            'name' => 'Sacred Hawan Samagri',
            'slug' => 'hawan-samagri',
            'price' => 199.00,
            'is_member_free_eligible' => false,
            'stock' => 50,
        ]);

        $product2 = Product::create([
            'category_id' => $category->id,
            'name' => 'Dhoop Cones',
            'slug' => 'dhoop-cones',
            'price' => 99.00,
            'is_member_free_eligible' => false,
            'stock' => 50,
        ]);

        // Admin selects product 2 as eligible
        $res = $this->actingAs($admin)->post('/admin/membership-benefits', [
            'eligible_product_ids' => [$product2->id],
        ]);

        $res->assertSessionHas('success');
        $this->assertFalse($product1->fresh()->is_member_free_eligible);
        $this->assertTrue($product2->fresh()->is_member_free_eligible);
    }

    public function test_cart_does_not_discount_products_to_free_and_charges_catalog_prices(): void
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

        $category = Category::create(['name' => 'Sacred Offerings', 'slug' => 'sacred-offerings']);

        $productHigh = Product::create([
            'category_id' => $category->id,
            'name' => 'Large Gangajal Brass Can 1L',
            'slug' => 'large-gangajal-1l',
            'price' => 249.00,
            'is_member_free_eligible' => true,
            'stock' => 50,
        ]);

        $productLow = Product::create([
            'category_id' => $category->id,
            'name' => 'Haridwar Gangajal 500ml',
            'slug' => 'haridwar-gangajal-500ml',
            'price' => 149.00,
            'is_member_free_eligible' => true,
            'stock' => 50,
        ]);

        $cartService = app(CartService::class);
        $cartService->add($productHigh->id, 1);
        $cartService->add($productLow->id, 1);

        $details = $cartService->getDetails($user);

        // Nothing is free in cart: both products charged at their catalog price
        $this->assertEquals(398.00, $details['subtotal']);
        $this->assertEquals(0.00, $details['discount']); // No free product discount
        $this->assertEquals(149.00, $details['shipping']);
        $this->assertEquals(547.00, $details['total']); // 398 + 149 shipping = 547
        $this->assertFalse($details['free_bottle_applied']);
    }

    public function test_admin_can_configure_member_shipping_price_in_settings(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($admin)->post('/admin/settings', [
            'membership_fee' => '500',
            'membership_validity_years' => '5',
            'shipping_charge' => '149',
            'member_shipping_charge' => '49.00',
            'free_first_bottle_enabled' => '1',
            'guest_checkout_enabled' => '1',
            'support_phone' => '+91 70424 82555',
            'support_email' => 'bliss@haridwarbliss.com',
            'ghat_location_default' => 'Har Ki Pauri, Haridwar',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertEquals('49.00', SiteSetting::get('member_shipping_charge'));

        // Check settings view contains member shipping field
        $viewResponse = $this->actingAs($admin)->get('/admin/settings');
        $viewResponse->assertOk();
        $viewResponse->assertSee('member_shipping_charge');
        $viewResponse->assertSee('Member Shipping Rate per Order');
    }

    public function test_member_shipping_rate_applies_to_active_members_while_non_members_pay_standard(): void
    {
        SiteSetting::set('shipping_charge', '149');
        SiteSetting::set('member_shipping_charge', '0'); // Free delivery for members

        $member = User::factory()->create();
        Membership::create([
            'user_id' => $member->id,
            'status' => 'active',
            'fee_paid' => 500.00,
            'validity_years' => 5,
            'starts_at' => now(),
            'expires_at' => now()->addYears(5),
            'terms_accepted' => true,
        ]);

        $nonMember = User::factory()->create();

        $category = Category::create(['name' => 'Puja Oils', 'slug' => 'puja-oils']);
        $item = Product::create([
            'category_id' => $category->id,
            'name' => 'Sesame Puja Oil 1L',
            'slug' => 'sesame-puja-oil-1l',
            'price' => 300.00,
            'member_price' => 250.00,
            'is_gangajal' => false,
            'stock' => 25,
            'is_active' => true,
        ]);

        $cartService = app(CartService::class);
        $cartService->clear();
        $cartService->add($item->id, 1);

        // Member calculation
        $memberCart = $cartService->getDetails($member);
        $this->assertEquals(0.00, $memberCart['shipping']);
        $this->assertTrue($memberCart['is_member_shipping']);
        $this->assertEquals(250.00, $memberCart['total']); // 250 + 0 shipping

        // Non-member calculation
        $nonMemberCart = $cartService->getDetails($nonMember);
        $this->assertEquals(149.00, $nonMemberCart['shipping']);
        $this->assertFalse($nonMemberCart['is_member_shipping']);
        $this->assertEquals(449.00, $nonMemberCart['total']); // 300 + 149 shipping
    }
}

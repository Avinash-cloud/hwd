<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Membership;
use App\Models\Order;
use App\Models\OrderItem;
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

    public function test_lower_priced_eligible_product_receives_free_discount_in_cart(): void
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

        // Higher price eligible product: ₹249
        $productHigh = Product::create([
            'category_id' => $category->id,
            'name' => 'Large Gangajal Brass Can 1L',
            'slug' => 'large-gangajal-1l',
            'price' => 249.00,
            'is_member_free_eligible' => true,
            'stock' => 50,
        ]);

        // Lower price eligible product: ₹149
        $productLow = Product::create([
            'category_id' => $category->id,
            'name' => 'Brahmakund Gangajal 500ml',
            'slug' => 'brahmakund-gangajal-500ml',
            'price' => 149.00,
            'is_member_free_eligible' => true,
            'stock' => 50,
        ]);

        $cartService = app(CartService::class);
        $cartService->add($productHigh->id, 1);
        $cartService->add($productLow->id, 1);

        $details = $cartService->getDetails($user);

        // Lower priced item (₹149) should be free, higher priced (₹249) charged
        $this->assertEquals(398.00, $details['subtotal']);
        $this->assertEquals(149.00, $details['discount']); // ₹149 discounted
        $this->assertEquals(149.00, $details['shipping']);
        $this->assertEquals(398.00, $details['total']); // 249 + 149 shipping = 398
        $this->assertTrue($details['free_bottle_applied']);
    }

    public function test_member_can_only_order_one_free_product_per_calendar_month(): void
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
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Brahmakund Gangajal 500ml',
            'slug' => 'gangajal-500ml',
            'price' => 149.00,
            'is_gangajal' => true,
            'is_member_free_eligible' => true,
            'stock' => 50,
        ]);

        // First order in current calendar month claiming free bottle
        $order1 = Order::create([
            'order_number' => 'HB-ORD-MONTH-01',
            'user_id' => $user->id,
            'subtotal' => 149.00,
            'discount_amount' => 149.00,
            'shipping_amount' => 149.00,
            'total_amount' => 149.00,
            'payment_method' => 'mock',
            'payment_status' => 'paid',
            'order_status' => 'confirmed',
            'qr_token' => 'HB-QR-M1',
            'shipping_address' => ['recipient_name' => 'Devotee', 'address_line1' => 'Ghat', 'city' => 'Haridwar', 'state' => 'Uttarakhand', 'postal_code' => '249401'],
            'created_at' => now(),
        ]);

        OrderItem::create([
            'order_id' => $order1->id,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'unit_price' => 149.00,
            'quantity' => 1,
            'total_price' => 0.00,
            'is_free_monthly_bottle' => true,
        ]);

        // User now has claimed their monthly quota for current month
        $this->assertTrue($user->fresh()->hasClaimedMonthlyFreeProduct());

        // Now user adds product to cart again in the SAME month
        $cartService = app(CartService::class);
        $cartService->clear();
        $cartService->add($product->id, 1);

        $details = $cartService->getDetails($user->fresh());

        // Discount should be 0, full price charged
        $this->assertEquals(149.00, $details['subtotal']);
        $this->assertEquals(0.00, $details['discount']);
        $this->assertFalse($details['free_bottle_applied']);
        $this->assertTrue($details['monthly_quota_used']);
        $this->assertEquals(298.00, $details['total']); // 149 product + 149 shipping = 298

        // However, in the NEXT calendar month, user has NOT claimed yet
        $nextMonth = now()->addMonth();
        $this->assertFalse($user->fresh()->hasClaimedMonthlyFreeProduct($nextMonth));
    }
}

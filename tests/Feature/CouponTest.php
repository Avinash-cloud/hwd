<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Coupon;
use App\Models\Membership;
use App\Models\Product;
use App\Models\SiteSetting;
use App\Models\User;
use App\Services\CartService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CouponTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        SiteSetting::set('shipping_charge', '149');
    }

    public function test_admin_can_create_and_manage_coupons(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $res = $this->actingAs($admin)->post('/admin/coupons', [
            'code' => 'ganga50',
            'name' => 'Flat 50 Off',
            'discount_type' => 'fixed',
            'discount_value' => 50.00,
            'min_spend' => 200.00,
            'is_active' => true,
        ]);

        $res->assertRedirect(route('admin.coupons.index'));
        $this->assertDatabaseHas('coupons', [
            'code' => 'GANGA50',
            'discount_type' => 'fixed',
            'discount_value' => 50.00,
            'is_active' => true,
        ]);
    }

    public function test_devotee_can_apply_valid_coupon_in_cart(): void
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

        $category = Category::create(['name' => 'Puja Items', 'slug' => 'puja-items']);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Brass Pooja Thali Set',
            'slug' => 'brass-thali-set',
            'price' => 500.00,
            'stock' => 10,
        ]);

        Coupon::create([
            'code' => 'DIWALI20',
            'name' => '20 Percent Off',
            'discount_type' => 'percentage',
            'discount_value' => 20.00, // 20% of 500 = 100
            'min_spend' => 300.00,
            'is_active' => true,
        ]);

        $cartService = app(CartService::class);
        $cartService->add($product->id, 1);

        $result = $cartService->applyCoupon('DIWALI20', $user);
        $this->assertTrue($result['success']);

        $details = $cartService->getDetails($user);
        $this->assertEquals('DIWALI20', $details['coupon_code']);
        $this->assertEquals(100.00, $details['coupon_discount']);
        $this->assertEquals(500.00, $details['subtotal']);
        $this->assertEquals(549.00, $details['total']); // 500 - 100 + 149 shipping = 549
    }
}

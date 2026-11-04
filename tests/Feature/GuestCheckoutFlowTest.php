<?php

namespace Tests\Feature;

use App\Models\Batch;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class GuestCheckoutFlowTest extends TestCase
{
    use RefreshDatabase;

    protected Category $category;

    protected Product $product;

    protected Batch $batch;

    protected function setUp(): void
    {
        parent::setUp();

        SiteSetting::set('membership_fee', '500');
        SiteSetting::set('membership_validity_years', '5');
        SiteSetting::set('shipping_charge', '149');
        SiteSetting::set('free_first_bottle_enabled', '1');
        SiteSetting::set('support_phone', '+91 98765 43210');
        SiteSetting::set('support_email', 'bliss@haridwarbliss.com');
        SiteSetting::set('ghat_location_default', 'Har Ki Pauri, Haridwar');

        $this->category = Category::create([
            'name' => 'Sacred Essentials',
            'slug' => 'sacred-essentials',
        ]);

        $this->batch = Batch::create([
            'batch_number' => 'HB-BATCH-GUEST-01',
            'sourcing_ghat' => 'Har Ki Pauri, Haridwar',
            'collection_date' => now()->subDay(),
            'packaging_date' => now(),
            'status' => 'ready',
        ]);

        $this->product = Product::create([
            'category_id' => $this->category->id,
            'batch_id' => $this->batch->id,
            'name' => 'Pure Brahmakund Gangajal 500ml',
            'slug' => 'pure-brahmakund-gangajal-500ml',
            'price' => 149.00,
            'compare_price' => 199.00,
            'stock' => 100,
            'is_active' => true,
        ]);
    }

    public function test_admin_can_toggle_guest_checkout_setting(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        // Toggle Guest Checkout ON
        $response = $this->actingAs($admin)->post('/admin/settings', [
            'membership_fee' => '500',
            'membership_validity_years' => '5',
            'shipping_charge' => '149',
            'free_first_bottle_enabled' => '1',
            'guest_checkout_enabled' => '1',
            'support_phone' => '+91 98765 43210',
            'support_email' => 'bliss@haridwarbliss.com',
            'ghat_location_default' => 'Har Ki Pauri, Haridwar',
        ]);

        $response->assertRedirect();
        $this->assertEquals('1', SiteSetting::get('guest_checkout_enabled'));

        // Toggle Guest Checkout OFF
        $response = $this->actingAs($admin)->post('/admin/settings', [
            'membership_fee' => '500',
            'membership_validity_years' => '5',
            'shipping_charge' => '149',
            'free_first_bottle_enabled' => '1',
            // guest_checkout_enabled omitted (unchecked checkbox)
            'support_phone' => '+91 98765 43210',
            'support_email' => 'bliss@haridwarbliss.com',
            'ghat_location_default' => 'Har Ki Pauri, Haridwar',
        ]);

        $response->assertRedirect();
        $this->assertEquals('0', SiteSetting::get('guest_checkout_enabled'));
    }

    public function test_when_guest_checkout_is_disabled_unauthenticated_user_cannot_add_to_cart_or_checkout(): void
    {
        SiteSetting::set('guest_checkout_enabled', '0');

        // Unauthenticated cart add redirects to login
        $res = $this->post('/cart/add', [
            'product_id' => $this->product->id,
            'quantity' => 1,
        ]);
        $res->assertRedirect(route('login'));

        // Ajax request gets 401 JSON
        $ajaxRes = $this->postJson('/cart/add', [
            'product_id' => $this->product->id,
            'quantity' => 1,
        ]);
        $ajaxRes->assertStatus(401);

        // Checkout page access redirects to login
        $checkoutRes = $this->get('/checkout');
        $checkoutRes->assertRedirect(route('login'));
    }

    public function test_when_guest_checkout_is_enabled_guest_can_add_to_cart_and_view_checkout(): void
    {
        SiteSetting::set('guest_checkout_enabled', '1');

        $res = $this->post('/cart/add', [
            'product_id' => $this->product->id,
            'quantity' => 2,
        ]);
        $res->assertRedirect(route('cart.index'));
        $this->assertEquals(2, session('cart')[$this->product->id]['quantity']);

        $checkoutRes = $this->get('/checkout');
        $checkoutRes->assertStatus(200);
        $checkoutRes->assertSee('Guest Checkout Active');
        $checkoutRes->assertSee('Email Address');
        $checkoutRes->assertSee('Create an account for faster checkout');
    }

    public function test_guest_can_complete_order_without_creating_account(): void
    {
        SiteSetting::set('guest_checkout_enabled', '1');

        // Add to cart
        $this->post('/cart/add', [
            'product_id' => $this->product->id,
            'quantity' => 1,
        ]);

        // Place order as guest
        $response = $this->post('/checkout', [
            'recipient_name' => 'Meera Sharma',
            'phone' => '+91 9811223344',
            'email' => 'meera.guest@example.com',
            'address_line1' => 'Plot 12, Vrindavan Enclave',
            'city' => 'Mathura',
            'state' => 'Uttar Pradesh',
            'postal_code' => '281001',
            'payment_method' => 'cod',
            'create_account' => '0',
        ]);

        $order = Order::latest()->first();
        $this->assertNotNull($order);
        $this->assertNull($order->user_id);
        $this->assertEquals('meera.guest@example.com', $order->guest_email);
        $this->assertEquals('Meera Sharma', $order->customer_name);
        $this->assertEquals('Mathura', $order->shipping_address['city']);
        $this->assertEquals(1, $order->items()->count());

        $response->assertRedirect(route('orders.show', $order->order_number));

        // Guest can view their order confirmation page via recent session
        $showRes = $this->get(route('orders.show', $order->order_number));
        $showRes->assertStatus(200);
        $showRes->assertSee('Order #'.$order->order_number);
        $showRes->assertSee('Create an Account to Track Your Sacred Order');
        $showRes->assertSee('meera.guest@example.com');
    }

    public function test_guest_can_complete_order_and_create_account_during_checkout(): void
    {
        SiteSetting::set('guest_checkout_enabled', '1');

        $this->post('/cart/add', [
            'product_id' => $this->product->id,
            'quantity' => 1,
        ]);

        $response = $this->post('/checkout', [
            'recipient_name' => 'Vikram Aditya',
            'phone' => '+91 9877112233',
            'email' => 'vikram.aditya@example.com',
            'address_line1' => 'Villa 5, Ganga Vihar',
            'city' => 'Rishikesh',
            'state' => 'Uttarakhand',
            'postal_code' => '249201',
            'payment_method' => 'razorpay',
            'create_account' => '1',
            'password' => 'SacredPass123!',
            'password_confirmation' => 'SacredPass123!',
        ]);

        $user = User::where('email', 'vikram.aditya@example.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals('Vikram Aditya', $user->name);

        $order = Order::latest()->first();
        $this->assertNotNull($order);
        $this->assertEquals($user->id, $order->user_id);
        $this->assertNull($order->guest_email);
        $this->assertEquals('vikram.aditya@example.com', $order->customer_email);

        $response->assertRedirect(route('orders.show', $order->order_number));
        $this->assertTrue(Auth::check());
        $this->assertEquals($user->id, Auth::id());
    }

    public function test_guest_can_create_account_after_order_from_order_confirmation_page(): void
    {
        SiteSetting::set('guest_checkout_enabled', '1');

        $this->post('/cart/add', [
            'product_id' => $this->product->id,
            'quantity' => 1,
        ]);

        // Place guest order without creating account
        $this->post('/checkout', [
            'recipient_name' => 'Kavita Joshi',
            'phone' => '+91 9766554433',
            'email' => 'kavita.postorder@example.com',
            'address_line1' => 'Lane 4, Anand Dham',
            'city' => 'Dehradun',
            'state' => 'Uttarakhand',
            'postal_code' => '248001',
            'payment_method' => 'cod',
        ]);

        $order = Order::latest()->first();
        $this->assertNull($order->user_id);

        // Guest creates account from confirmation page
        $createRes = $this->post(route('orders.createAccount', $order->order_number), [
            'password' => 'SafeHaridwar2026!',
            'password_confirmation' => 'SafeHaridwar2026!',
        ]);

        $createRes->assertRedirect(route('orders.show', $order->order_number));

        $user = User::where('email', 'kavita.postorder@example.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals('Kavita Joshi', $user->name);

        // Order is now linked to the newly created user
        $this->assertEquals($user->id, $order->fresh()->user_id);
        $this->assertTrue(Auth::check());
        $this->assertEquals($user->id, Auth::id());
    }

    public function test_registering_in_future_automatically_links_prior_guest_orders(): void
    {
        SiteSetting::set('guest_checkout_enabled', '1');

        $this->post('/cart/add', [
            'product_id' => $this->product->id,
            'quantity' => 1,
        ]);

        // Place order as guest
        $this->post('/checkout', [
            'recipient_name' => 'Anil Verma',
            'phone' => '+91 9123456789',
            'email' => 'anil.future@example.com',
            'address_line1' => 'Flat 102, Shivalik Tower',
            'city' => 'Haridwar',
            'state' => 'Uttarakhand',
            'postal_code' => '249401',
            'payment_method' => 'mock',
        ]);

        $order = Order::latest()->first();
        $this->assertNull($order->user_id);
        $this->assertEquals('anil.future@example.com', $order->guest_email);

        // Logout session to simulate returning visitor weeks later
        session()->flush();

        // Customer registers via standard /register page
        $regResponse = $this->post('/register', [
            'name' => 'Anil Verma',
            'email' => 'anil.future@example.com',
            'password' => 'VedicFuture2026!',
            'password_confirmation' => 'VedicFuture2026!',
        ]);

        $regResponse->assertRedirect(route('dashboard'));

        $user = User::where('email', 'anil.future@example.com')->first();
        $this->assertNotNull($user);

        // Order is now linked to Anil's account automatically
        $this->assertEquals($user->id, $order->fresh()->user_id);
        $this->assertTrue($user->orders()->where('id', $order->id)->exists());
    }
}

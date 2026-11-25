<?php

namespace Tests\Feature;

use App\Mail\AdminSubscriptionAlertMail;
use App\Models\Batch;
use App\Models\Category;
use App\Models\Membership;
use App\Models\MembershipSubscription;
use App\Models\Order;
use App\Models\Product;
use App\Models\SiteSetting;
use App\Models\User;
use App\Services\SubscriptionOrderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class MembershipSubscriptionRecurringTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        SiteSetting::set('membership_fee', '797');
        SiteSetting::set('shipping_charge', '149');
        SiteSetting::set('membership_validity_years', '5');

        Batch::create([
            'batch_number' => 'BATCH-TEST-001',
            'sourcing_ghat' => 'Haridwar Sacred Ganga Ghat',
            'collection_date' => now()->toDateString(),
            'packaging_date' => now()->toDateString(),
            'status' => 'ready',
        ]);

        $category = Category::create([
            'name' => 'Gangajal',
            'slug' => 'gangajal',
        ]);

        Product::create([
            'category_id' => $category->id,
            'name' => 'Sacred Haridwar Gangajal 500ml',
            'slug' => 'sacred-gangajal-500ml',
            'price' => 50.00,
            'volume_ml' => 500,
            'is_gangajal' => true,
            'is_active' => true,
            'stock' => 100,
        ]);
    }

    public function test_devotee_can_view_join_page(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('membership.join'));

        $response->assertOk();
        $response->assertSee('Blu Bliss 5-Year Sacred Devotee Membership');
        $response->assertSee('797');
        $response->assertSee('199');
        $response->assertSee('Doorstep Delivery Address');
        $response->assertSee('renews automatically');
        $response->assertDontSee('payable by the 5th');
        $response->assertDontSee('₹85 + GST');
    }

    public function test_devotee_can_create_razorpay_subscription_order(): void
    {
        $user = User::factory()->create(['phone' => '9876543210']);

        $response = $this->actingAs($user)->postJson(route('membership.razorpay.order'));

        $response->assertOk();
        $response->assertJsonStructure([
            'subscription_id',
            'order_id',
            'key',
            'name',
            'description',
            'prefill',
        ]);
    }

    public function test_devotee_can_subscribe_with_doorstep_address_creating_membership_and_first_month_order(): void
    {
        $user = User::factory()->create(['phone' => '9876543210']);

        $response = $this->actingAs($user)->post(route('membership.subscribe'), [
            'terms_accepted' => '1',
            'payment_method' => 'mock',
            'shipping_phone' => '9876543210',
            'shipping_address' => 'House 108, Sacred Vihar, Kankhal',
            'shipping_city' => 'Haridwar',
            'shipping_state' => 'Uttarakhand',
            'shipping_pincode' => '249408',
            'razorpay_payment_id' => 'pay_test_mem_001',
            'razorpay_subscription_id' => 'sub_test_mem_001',
        ]);

        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('success');

        // Assert 5-Year Membership created
        $this->assertDatabaseHas('memberships', [
            'user_id' => $user->id,
            'status' => 'active',
            'fee_paid' => 797.00,
            'validity_years' => 5,
        ]);

        // Assert MembershipSubscription auto-debit profile created
        $this->assertDatabaseHas('membership_subscriptions', [
            'user_id' => $user->id,
            'status' => 'active',
            'billing_amount' => 199.00,
            'razorpay_subscription_id' => 'sub_test_mem_001',
        ]);

        // Assert Month 1 initial dispatch order created (free welcome benefit)
        $order = Order::where('user_id', $user->id)->latest()->first();
        $this->assertNotNull($order);
        $this->assertEquals(0.00, (float) $order->total_amount);
        $this->assertEquals(50.00, (float) $order->subtotal);
        $this->assertEquals(149.00, (float) $order->shipping_amount);
        $this->assertEquals(now()->format('Y-m'), $order->billing_period);
        $this->assertEquals('House 108, Sacred Vihar, Kankhal', $order->shipping_address['address_line1']);
        $this->assertEquals('Haridwar', $order->shipping_address['city']);

        // Assert OrderItem has is_free_monthly_bottle = true (free welcome benefit)
        $item = $order->items->first();
        $this->assertNotNull($item);
        $this->assertTrue((bool) $item->is_free_monthly_bottle);
        $this->assertEquals(50.00, (float) $item->unit_price);

        // Assert subscription log recorded
        $this->assertDatabaseHas('subscription_logs', [
            'event' => 'subscription_activated',
            'payment_id' => 'pay_test_mem_001',
        ]);
    }

    public function test_razorpay_webhook_creates_monthly_order_on_subscription_charged(): void
    {
        $user = User::factory()->create();

        $membership = Membership::create([
            'user_id' => $user->id,
            'status' => 'active',
            'fee_paid' => 797.00,
            'validity_years' => 5,
            'starts_at' => now(),
            'expires_at' => now()->addYears(5),
            'terms_accepted' => true,
        ]);

        $sub = MembershipSubscription::create([
            'user_id' => $user->id,
            'membership_id' => $membership->id,
            'product_id' => Product::first()->id,
            'status' => 'active',
            'billing_interval_days' => 30,
            'billing_amount' => 199.00,
            'next_billing_date' => now()->addDays(30),
            'last_billed_at' => now(),
            'razorpay_subscription_id' => 'sub_webhook_monthly_123',
            'shipping_address' => [
                'recipient_name' => $user->name,
                'phone' => '9876543210',
                'address_line1' => 'Flat 402, Ganga Residency',
                'city' => 'Haridwar',
                'state' => 'Uttarakhand',
                'postal_code' => '249401',
                'country' => 'India',
            ],
        ]);

        // Mock Webhook Payload for subscription.charged
        $payload = [
            'event' => 'subscription.charged',
            'payload' => [
                'subscription' => [
                    'entity' => [
                        'id' => 'sub_webhook_monthly_123',
                        'status' => 'active',
                    ],
                ],
                'payment' => [
                    'entity' => [
                        'id' => 'pay_recur_debit_999',
                        'amount' => 19900,
                        'subscription_id' => 'sub_webhook_monthly_123',
                        'status' => 'captured',
                    ],
                ],
            ],
        ];

        $rawPayload = (string) json_encode($payload);
        $secret = (string) config('services.razorpay.webhook_secret', 'dummy_webhook_secret');
        $signature = hash_hmac('sha256', $rawPayload, $secret);

        $response = $this->call('POST', route('webhooks.razorpay'), [], [], [], [
            'HTTP_X_RAZORPAY_SIGNATURE' => $signature,
            'CONTENT_TYPE' => 'application/json',
        ], $rawPayload);
        $response->assertOk();

        // Verify order created automatically
        $order = Order::where('payment_id', 'pay_recur_debit_999')->first();
        $this->assertNotNull($order);
        $this->assertEquals($user->id, $order->user_id);
        $this->assertEquals(199.00, (float) $order->total_amount);
        $this->assertEquals('confirmed', $order->order_status);
        $this->assertEquals('Flat 402, Ganga Residency', $order->shipping_address['address_line1']);

        $item = $order->items->first();
        $this->assertNotNull($item);
        $this->assertFalse((bool) $item->is_free_monthly_bottle);

        // Verify next billing date moved forward
        $sub->refresh();
        $this->assertTrue($sub->next_billing_date->isFuture());

        // Verify log recorded
        $this->assertDatabaseHas('subscription_logs', [
            'subscription_id' => $sub->id,
            'event' => 'payment_success',
            'payment_id' => 'pay_recur_debit_999',
        ]);
    }

    public function test_razorpay_webhook_auto_renews_5_year_membership_when_expiring(): void
    {
        $user = User::factory()->create();

        // Expiring membership (expires in 10 days)
        $initialExpiry = now()->addDays(10);
        $membership = Membership::create([
            'user_id' => $user->id,
            'status' => 'active',
            'fee_paid' => 797.00,
            'validity_years' => 5,
            'starts_at' => now()->subYears(5),
            'expires_at' => $initialExpiry,
            'terms_accepted' => true,
        ]);

        $sub = MembershipSubscription::create([
            'user_id' => $user->id,
            'membership_id' => $membership->id,
            'product_id' => Product::first()->id,
            'status' => 'active',
            'billing_interval_days' => 30,
            'billing_amount' => 199.00,
            'next_billing_date' => now(),
            'last_billed_at' => now()->subDays(30),
            'razorpay_subscription_id' => 'sub_renewal_check_456',
            'shipping_address' => [
                'recipient_name' => $user->name,
                'phone' => '9876543210',
                'address_line1' => 'Ganga Kripa Kutir',
                'city' => 'Haridwar',
                'state' => 'Uttarakhand',
                'postal_code' => '249401',
            ],
        ]);

        $payload = [
            'event' => 'subscription.charged',
            'payload' => [
                'subscription' => [
                    'entity' => [
                        'id' => 'sub_renewal_check_456',
                    ],
                ],
                'payment' => [
                    'entity' => [
                        'id' => 'pay_recur_debit_renewal_111',
                        'amount' => 19900,
                        'subscription_id' => 'sub_renewal_check_456',
                    ],
                ],
            ],
        ];

        $rawPayload = (string) json_encode($payload);
        $secret = (string) config('services.razorpay.webhook_secret', 'dummy_webhook_secret');
        $signature = hash_hmac('sha256', $rawPayload, $secret);

        $response = $this->call('POST', route('webhooks.razorpay'), [], [], [], [
            'HTTP_X_RAZORPAY_SIGNATURE' => $signature,
            'CONTENT_TYPE' => 'application/json',
        ], $rawPayload);
        $response->assertOk();

        // Assert 5 years added to membership
        $membership->refresh();
        $this->assertEquals($initialExpiry->copy()->addYears(5)->toDateString(), $membership->expires_at->toDateString());

        // Assert renewal logged
        $this->assertDatabaseHas('subscription_logs', [
            'subscription_id' => $sub->id,
            'event' => 'membership_auto_renewed',
        ]);
    }

    public function test_dashboard_displays_active_subscription_status_and_next_delivery(): void
    {
        $user = User::factory()->create();

        $membership = Membership::create([
            'user_id' => $user->id,
            'status' => 'active',
            'fee_paid' => 797.00,
            'validity_years' => 5,
            'starts_at' => now(),
            'expires_at' => now()->addYears(5),
            'terms_accepted' => true,
        ]);

        MembershipSubscription::create([
            'user_id' => $user->id,
            'membership_id' => $membership->id,
            'product_id' => Product::first()->id,
            'status' => 'active',
            'billing_interval_days' => 30,
            'billing_amount' => 199.00,
            'next_billing_date' => now()->addDays(20),
            'last_billed_at' => now(),
            'razorpay_subscription_id' => 'sub_dash_789',
            'shipping_address' => [
                'recipient_name' => $user->name,
                'phone' => '9876543210',
                'address_line1' => 'Ganga Kripa Kutir',
                'city' => 'Haridwar',
                'state' => 'Uttarakhand',
                'postal_code' => '249401',
            ],
        ]);

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertOk();
        $response->assertSee('Auto-Debit Active');
        $response->assertSee('Auto-Renewing');
        $response->assertSee('199');
    }

    public function test_artisan_command_can_force_process_recurring_renewal_and_order(): void
    {
        $user = User::factory()->create();

        $membership = Membership::create([
            'user_id' => $user->id,
            'status' => 'active',
            'fee_paid' => 797.00,
            'validity_years' => 5,
            'starts_at' => now(),
            'expires_at' => now()->addYears(5),
            'terms_accepted' => true,
        ]);

        $sub = MembershipSubscription::create([
            'user_id' => $user->id,
            'membership_id' => $membership->id,
            'product_id' => Product::first()->id,
            'status' => 'active',
            'billing_interval_days' => 30,
            'billing_amount' => 199.00,
            'next_billing_date' => now()->addDays(25),
            'last_billed_at' => now(),
            'razorpay_subscription_id' => 'sub_cmd_test_123',
            'shipping_address' => [
                'recipient_name' => $user->name,
                'phone' => '9876543210',
                'address_line1' => 'Ganga Kripa Kutir',
                'city' => 'Haridwar',
                'state' => 'Uttarakhand',
                'postal_code' => '249401',
            ],
        ]);

        $this->artisan('subscriptions:process-renewals', ['--force' => true, '--user' => $user->id])
            ->assertSuccessful();

        // An automated order was created
        $order = Order::where('user_id', $user->id)->latest()->first();
        $this->assertNotNull($order);
        $this->assertEquals(199.00, (float) $order->total_amount);
        $this->assertFalse((bool) $order->items->first()->is_free_monthly_bottle);
    }

    public function test_deduplication_prevents_duplicate_orders_for_same_billing_cycle(): void
    {
        $user = User::factory()->create();

        $membership = Membership::create([
            'user_id' => $user->id,
            'status' => 'active',
            'fee_paid' => 797.00,
            'validity_years' => 5,
            'starts_at' => now(),
            'expires_at' => now()->addYears(5),
            'terms_accepted' => true,
        ]);

        $sub = MembershipSubscription::create([
            'user_id' => $user->id,
            'membership_id' => $membership->id,
            'product_id' => Product::first()->id,
            'status' => 'active',
            'billing_interval_days' => 30,
            'billing_amount' => 199.00,
            'next_billing_date' => now(),
            'last_billed_at' => now()->subDays(30),
            'razorpay_subscription_id' => 'sub_dedup_test_999',
            'shipping_address' => [
                'recipient_name' => $user->name,
                'phone' => '9876543210',
                'address_line1' => 'Ganga Kripa Kutir',
                'city' => 'Haridwar',
                'state' => 'Uttarakhand',
                'postal_code' => '249401',
            ],
        ]);

        $orderService = app(SubscriptionOrderService::class);
        $billingPeriod = now()->format('Y-m');

        // First creation succeeds
        $order1 = $orderService->createOrderForBillingCycle(
            subscription: $sub,
            billingPeriod: $billingPeriod,
            amountPaid: 199.00,
            paymentId: 'pay_first_call_111',
            paymentMethod: 'razorpay_subscription'
        );
        $this->assertNotNull($order1);

        // Second creation for same period returns null (dedup guard)
        $order2 = $orderService->createOrderForBillingCycle(
            subscription: $sub,
            billingPeriod: $billingPeriod,
            amountPaid: 199.00,
            paymentId: 'pay_second_call_222',
            paymentMethod: 'razorpay_subscription'
        );
        $this->assertNull($order2);

        // Exactly one order exists in DB for this subscription
        $this->assertEquals(1, Order::where('membership_subscription_id', $sub->id)->count());
    }

    public function test_payment_failure_alerts_admin_via_email(): void
    {
        Mail::fake();

        $user = User::factory()->create();

        $membership = Membership::create([
            'user_id' => $user->id,
            'status' => 'active',
            'fee_paid' => 797.00,
            'validity_years' => 5,
            'starts_at' => now(),
            'expires_at' => now()->addYears(5),
            'terms_accepted' => true,
        ]);

        $sub = MembershipSubscription::create([
            'user_id' => $user->id,
            'membership_id' => $membership->id,
            'product_id' => Product::first()->id,
            'status' => 'active',
            'billing_interval_days' => 30,
            'billing_amount' => 199.00,
            'next_billing_date' => now()->addDays(30),
            'razorpay_subscription_id' => 'sub_fail_test_888',
        ]);

        $payload = [
            'event' => 'payment.failed',
            'payload' => [
                'payment' => [
                    'entity' => [
                        'id' => 'pay_failed_123',
                        'subscription_id' => 'sub_fail_test_888',
                        'amount' => 19900,
                        'error_code' => 'BAD_REQUEST_ERROR',
                        'error_description' => 'Card balance insufficient',
                    ],
                ],
            ],
        ];

        $rawPayload = (string) json_encode($payload);
        $secret = (string) config('services.razorpay.webhook_secret', 'dummy_webhook_secret');
        $signature = hash_hmac('sha256', $rawPayload, $secret);

        $response = $this->call('POST', route('webhooks.razorpay'), [], [], [], [
            'HTTP_X_RAZORPAY_SIGNATURE' => $signature,
            'CONTENT_TYPE' => 'application/json',
        ], $rawPayload);

        $response->assertOk();

        // Verify failure count incremented
        $sub->refresh();
        $this->assertEquals(1, $sub->payment_failures_count);

        // Verify admin was alerted via email
        Mail::assertSent(AdminSubscriptionAlertMail::class);
    }
}

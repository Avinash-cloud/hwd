<?php

namespace Tests\Feature;

use App\Mail\OfflineMembershipWelcomeMail;
use App\Models\Category;
use App\Models\Membership;
use App\Models\MembershipSubscription;
use App\Models\Order;
use App\Models\Product;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class OfflineMembershipOnboardingTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['is_admin' => true]);

        SiteSetting::set('membership_fee', '500');
        SiteSetting::set('shipping_charge', '99');
        SiteSetting::set('membership_validity_years', '5');

        $category = Category::create([
            'name' => 'Sacred Gangajal',
            'slug' => 'gangajal',
        ]);

        $this->product = Product::create([
            'category_id' => $category->id,
            'name' => 'Haridwar Sacred Gangajal 500ml',
            'slug' => 'haridwar-gangajal-500ml',
            'price' => 149.00,
            'volume_ml' => 500,
            'is_gangajal' => true,
            'is_active' => true,
            'stock' => 200,
        ]);
    }

    public function test_admin_can_enroll_single_offline_member_with_backdated_tenure_and_welcome_email(): void
    {
        Mail::fake();

        $startsAt = now()->subMonths(4)->format('Y-m-d');

        $response = $this->actingAs($this->admin)->post(route('admin.memberships.offline.store'), [
            'name' => 'Rameshwar Tiwari',
            'email' => 'rameshwar@example.com',
            'phone' => '9876543210',
            'address_line1' => 'House 12, Shivalik Nagar',
            'city' => 'Haridwar',
            'state' => 'Uttarakhand',
            'postal_code' => '249403',
            'starts_at' => $startsAt,
            'fee_paid' => '699',
            'validity_years' => '5',
            'payment_method' => 'cash',
            'payment_id' => 'RCPT-OFF-901',
            'invoice_number' => 'HB-OFF-2026-001',
            'prepaid_months' => '0',
            'send_welcome_email' => '1',
        ]);

        $response->assertRedirect(route('admin.memberships.index'));
        $response->assertSessionHas('success');

        $user = User::where('email', 'rameshwar@example.com')->first();
        $this->assertNotNull($user);
        $this->assertTrue($user->hasActiveMembership());

        $this->assertDatabaseHas('memberships', [
            'user_id' => $user->id,
            'status' => 'active',
            'fee_paid' => 699.00,
            'payment_method' => 'cash',
            'payment_id' => 'RCPT-OFF-901',
            'invoice_number' => 'HB-OFF-2026-001',
        ]);

        $this->assertDatabaseHas('addresses', [
            'user_id' => $user->id,
            'postal_code' => '249403',
            'is_default' => true,
        ]);

        Mail::assertSent(OfflineMembershipWelcomeMail::class, function (OfflineMembershipWelcomeMail $mail) use ($user): bool {
            return $mail->hasTo($user->email) && $mail->setPasswordUrl !== null;
        });
    }

    public function test_non_admin_cannot_access_offline_member_onboarding(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $this->actingAs($user)
            ->get(route('admin.memberships.offline.create'))
            ->assertForbidden();
    }

    public function test_admin_can_download_template_and_import_csv_with_duplicate_skipping(): void
    {
        Mail::fake();

        $existingUser = User::factory()->create(['email' => 'already@example.com']);
        Membership::create([
            'user_id' => $existingUser->id,
            'status' => 'active',
            'fee_paid' => 500,
            'validity_years' => 5,
            'starts_at' => now()->subMonth(),
            'expires_at' => now()->addYears(5),
        ]);

        $this->actingAs($this->admin)
            ->get(route('admin.memberships.offline.template'))
            ->assertOk();

        $csvContent = implode("\n", [
            'name,email,phone,address_line1,address_line2,city,state,postal_code,starts_at,fee_paid,validity_years,payment_method,payment_id,invoice_number,prepaid_months',
            'Already Member,already@example.com,9999990001,Addr 1,,Haridwar,Uttarakhand,249401,'.now()->subMonth()->format('Y-m-d').',500,5,cash,RCPT-1,,0',
            'Prepaid Devotee,prepaid@example.com,9999990002,Plot 88 Kankhal,,Haridwar,Uttarakhand,249408,'.now()->subDay()->format('Y-m-d').',699,5,upi,UTR-888,HB-CSV-002,6',
        ]);

        $file = UploadedFile::fake()->createWithContent('offline_members.csv', $csvContent);

        $response = $this->actingAs($this->admin)->post(route('admin.memberships.offline.import'), [
            'csv_file' => $file,
            'send_welcome_email' => '1',
        ]);

        $response->assertRedirect(route('admin.memberships.index'));
        $response->assertSessionHas('success');

        $prepaidUser = User::where('email', 'prepaid@example.com')->first();
        $this->assertNotNull($prepaidUser);
        $this->assertDatabaseHas('membership_subscriptions', [
            'user_id' => $prepaidUser->id,
            'billing_mode' => 'offline_prepaid',
            'status' => 'active',
        ]);
    }

    public function test_admin_dispatch_and_reconciliation_handle_offline_prepaid_without_razorpay_api(): void
    {
        Mail::fake();

        $user = User::factory()->create(['email' => 'prepaid.recon@example.com']);
        $membership = Membership::create([
            'user_id' => $user->id,
            'status' => 'active',
            'fee_paid' => 500,
            'validity_years' => 5,
            'starts_at' => now()->subDays(10),
            'expires_at' => now()->addYears(5),
            'payment_method' => 'cash',
        ]);

        $sub = MembershipSubscription::create([
            'user_id' => $user->id,
            'membership_id' => $membership->id,
            'product_id' => $this->product->id,
            'status' => 'active',
            'billing_mode' => 'offline_prepaid',
            'billing_interval_days' => 30,
            'billing_amount' => 248.00,
            'next_billing_date' => now()->subDay(),
            'prepaid_until' => now()->addMonths(2),
            'razorpay_subscription_id' => null,
        ]);

        $this->artisan('subscriptions:process-renewals', ['--user' => $user->id])
            ->assertExitCode(0);

        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'membership_subscription_id' => $sub->id,
            'payment_method' => 'offline_prepaid',
            'billing_period' => now()->format('Y-m'),
        ]);

        // Second dispatch attempt in the same billing period via admin button is safely deduplicated
        $dispatchResponse = $this->actingAs($this->admin)
            ->post(route('admin.memberships.dispatchMonthly', $membership->id));

        $dispatchResponse->assertRedirect();
        $this->assertSame(1, Order::where('membership_subscription_id', $sub->id)->count());
    }

    public function test_offline_member_can_activate_monthly_auto_debit_mandate_on_dashboard_with_zero_joining_fee(): void
    {
        Mail::fake();

        $user = User::factory()->create(['email' => 'mandate.member@example.com', 'phone' => '9876543210']);
        $membership = Membership::create([
            'user_id' => $user->id,
            'status' => 'active',
            'fee_paid' => 500,
            'validity_years' => 5,
            'starts_at' => now()->subDays(15),
            'expires_at' => now()->addYears(5),
            'payment_method' => 'cash',
            'payment_id' => 'RCPT-CASH-77',
        ]);

        // Dashboard shows zero-joining-fee mandate setup CTA
        $dashboard = $this->actingAs($user)->get(route('dashboard'));
        $dashboard->assertOk();
        $dashboard->assertSee('Activate Monthly Auto-Delivery (₹0 Joining Fee)');

        // Mandate Razorpay order endpoint succeeds
        $orderRes = $this->actingAs($user)->postJson(route('membership.mandate.razorpay.order'));
        $orderRes->assertOk();
        $orderRes->assertJsonStructure(['subscription_id', 'monthly_amount', 'key', 'prefill']);

        // Activating mandate attaches Razorpay subscription without duplicating Membership row
        $activateRes = $this->actingAs($user)->post(route('membership.mandate.activate'), [
            'terms_accepted' => '1',
            'payment_method' => 'mock',
            'razorpay_subscription_id' => 'sub_mandate_999',
            'razorpay_payment_id' => 'pay_mandate_999',
            'razorpay_signature' => 'sig_mock',
            'shipping_phone' => '9876543210',
            'shipping_address' => '45 Ganga Vihar',
            'shipping_city' => 'Haridwar',
            'shipping_state' => 'Uttarakhand',
            'shipping_pincode' => '249401',
        ]);

        $activateRes->assertRedirect(route('dashboard'));
        $activateRes->assertSessionHas('success');

        // Still only 1 Membership row (did not double-create or re-charge commitment fee)
        $this->assertSame(1, Membership::where('user_id', $user->id)->count());
        $this->assertDatabaseHas('membership_subscriptions', [
            'user_id' => $user->id,
            'membership_id' => $membership->id,
            'billing_mode' => 'razorpay',
            'razorpay_subscription_id' => 'sub_mandate_999',
            'status' => 'active',
        ]);
        $this->assertDatabaseHas('subscription_logs', [
            'event' => 'mandate_activated_for_existing_member',
            'amount' => 0.00,
        ]);
    }
}

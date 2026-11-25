<?php

namespace Tests\Feature;

use App\Models\SiteSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentDeliveryPolicyTest extends TestCase
{
    use RefreshDatabase;

    public function test_payment_and_delivery_policy_page_displays_razorpay_and_nimbuspost_details_with_preserved_contacts(): void
    {
        SiteSetting::set('membership_fee', '699');
        SiteSetting::set('shipping_charge', '149');
        SiteSetting::set('member_shipping_charge', '0');
        SiteSetting::set('membership_validity_years', '5');

        $response = $this->get(route('payment-delivery-policy'));

        $response->assertOk();
        $response->assertSee('Payment & Delivery Policy');
        $response->assertSee('M/s Haridwar Bliss Food & Beverages Pvt. Ltd. (Blu Bliss)');
        $response->assertSee('Razorpay');
        $response->assertSee('HMAC-SHA256');
        $response->assertSee('NimbusPost');
        $response->assertSee('AWB Tracking Number');
        $response->assertSee('₹699');
        $response->assertSee('₹149');
        $response->assertSee('24 to 48 hours');
        $response->assertSee('3 to 5 working days');
        $response->assertSee('15 days prior');
        $response->assertSee('Haridwar District Court, Uttarakhand');
        $response->assertSee(route('cancellation-policy'), false);

        // Golden Rule: preserve official website contact details and exclude mismatched/stale strings
        $response->assertSee('sales@haridwarbliss.com');
        $response->assertSee('+91 7042482555');
        $response->assertDontSee('SDENTERPRISESGHAZIABAD@GMAIL.COM');
        $response->assertDontSee('Shiprocket');
    }

    public function test_footer_links_to_payment_and_delivery_policy(): void
    {
        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee(route('payment-delivery-policy'), false);
        $response->assertSee('Payment & Delivery Policy');
    }
}

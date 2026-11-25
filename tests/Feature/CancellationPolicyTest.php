<?php

namespace Tests\Feature;

use App\Models\SiteSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CancellationPolicyTest extends TestCase
{
    use RefreshDatabase;

    public function test_cancellation_policy_page_displays_membership_and_epuja_policies_with_preserved_contacts(): void
    {
        SiteSetting::set('membership_fee', '699');
        SiteSetting::set('shipping_charge', '149');
        SiteSetting::set('membership_validity_years', '5');

        $response = $this->get(route('cancellation-policy'));

        $response->assertOk();
        $response->assertSee('Service & E-Puja Cancellation Policy');
        $response->assertSee('M/s Haridwar Bliss Food & Beverages Pvt. Ltd. (Blu Bliss)');
        $response->assertSee('₹699');
        $response->assertSee('₹149/month');
        $response->assertSee('Automatic Renewal After 60 Months');
        $response->assertSee('renews automatically');
        $response->assertDontSee('₹85 + GST');
        $response->assertDontSee('payable by the 5th');
        $response->assertSee('Haridwar District Court, Uttarakhand');

        // Tiered E-Puja cancellation clauses
        $response->assertSee('a refund after deducting applicable payment gateway and processing charges');
        $response->assertSee('50% refund');
        $response->assertSee('5 to 7 working days');
        $response->assertSee('6 hours');
        $response->assertSee('3 to 5 working days');

        // Golden Rule: existing site contacts preserved, mismatched source contacts excluded
        $response->assertSee('sales@haridwarbliss.com');
        $response->assertSee('+91 7042482555');
        $response->assertDontSee('SDENTERPRISESGHAZIABAD@GMAIL.COM');
        $response->assertDontSee('Ghaziabad District Court');
    }
}

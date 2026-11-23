<?php

namespace Tests\Feature;

use App\Models\SiteSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TermsAndConditionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_terms_and_conditions_page_loads_successfully_with_updated_terms(): void
    {
        $response = $this->get('/terms');

        $response->assertStatus(200);
        $response->assertSee('Terms & Conditions');
        $response->assertSee('HARIDWAR BLISS FOOD &amp; BEVERAGES PVT. LTD.', false);
        $response->assertSee('₹797');
        $response->assertDontSee('699');
        $response->assertSee('sales@haridwarbliss.com');
        $response->assertDontSee('SDENTERPRISESGHAZIABAD@GMAIL.COM');
        $response->assertSee('Five-Year Ganga Jal Seva Commitment Plan');
        $response->assertSee('₹149');
        $response->assertSee('Ghaziabad District Court');
    }

    public function test_terms_page_uses_database_pricing(): void
    {
        SiteSetting::set('membership_fee', '1250');
        SiteSetting::set('shipping_charge', '199');
        SiteSetting::set('membership_validity_years', '3');
        SiteSetting::set('membership_continuation_charge', '95');

        $response = $this->get('/terms');

        $response->assertStatus(200);
        $response->assertSee('₹1,250');
        $response->assertSee('₹95');
        $response->assertSee('3-year');
    }
}

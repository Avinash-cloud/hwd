<?php

namespace Tests\Feature;

use Tests\TestCase;

class TermsAndConditionsTest extends TestCase
{
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
}

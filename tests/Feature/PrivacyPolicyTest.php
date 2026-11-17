<?php

namespace Tests\Feature;

use Tests\TestCase;

class PrivacyPolicyTest extends TestCase
{
    public function test_privacy_policy_page_loads_successfully_with_updated_policy(): void
    {
        $response = $this->get('/privacy');

        $response->assertStatus(200);
        $response->assertSee('Privacy Policy');
        $response->assertSee('HARIDWAR BLISS FOOD &amp; BEVERAGES PVT. LTD.', false);
        $response->assertSee('sales@haridwarbliss.com');
        $response->assertDontSee('SDENTERPRISESGHAZIABAD@GMAIL.COM');
        $response->assertSee('Personal Information We Collect');
        $response->assertSee('How We Use Your Personal Information');
        $response->assertSee('Your Privacy Rights');
        $response->assertSee('Grievance Redressal and Privacy Requests');
    }
}

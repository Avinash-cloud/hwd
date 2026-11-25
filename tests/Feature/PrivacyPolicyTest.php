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
        $response->assertSee('17 November 2026');
        $response->assertSee('sales@haridwarbliss.com');
        $response->assertSee('+91 7042482555');
        $response->assertDontSee('SDENTERPRISESGHAZIABAD@GMAIL.COM');
        $response->assertSee('Personal Information We Collect');
        $response->assertSee('How We Use Your Personal Information');
        $response->assertSee('Your Privacy Rights');
        $response->assertSee('Right to Grievance Redressal');
        $response->assertSee('Right to Nominate');
        $response->assertSee('Grievance Redressal and Privacy Requests');
    }
}

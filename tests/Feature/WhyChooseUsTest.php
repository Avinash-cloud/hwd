<?php

namespace Tests\Feature;

use App\Models\SiteSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WhyChooseUsTest extends TestCase
{
    use RefreshDatabase;

    public function test_why_choose_us_page_displays_five_polished_pillars_and_dynamic_pricing(): void
    {
        SiteSetting::set('membership_fee', '699');
        SiteSetting::set('shipping_charge', '149');
        SiteSetting::set('membership_validity_years', '5');

        $response = $this->get(route('why-choose-us'));

        $response->assertOk();
        $response->assertSee('Why Choose Blu Bliss');
        $response->assertSee('Purity Guaranteed');
        $response->assertSee('Auto-Delivery Subscription');
        $response->assertSee('Virtue + Cleanliness Double Benefit');
        $response->assertSee('Zero Logistics Hassle Across India');
        $response->assertSee('Family Well-Being');
        $response->assertSee('500 ml PET bottles');
        $response->assertSee('₹699');
        $response->assertSee('₹149/month');
        $response->assertSee('Auto-Renewed');
        $response->assertDontSee('₹85 + GST');
        $response->assertDontSee('payable by the 5th');
        $response->assertSee('about ₹5 per day');
        $response->assertSee('Haridwar at Your Doorstep');

        // Held claims and garbled draft phrases must not be published
        $response->assertDontSee('90% of');
        $response->assertDontSee('Lab Test Report');
        $response->assertDontSee('0.5kG');
        $response->assertDontSee('0.5 kg');
        $response->assertDontSee('NGO');
        $response->assertDontSee('Wear and Tear');
        $response->assertDontSee('Glass bottle');
        $response->assertDontSee('PET BOOTEL');
        $response->assertDontSee('Logistic Tension 0');
        $response->assertDontSee('149 + Sanskar');
    }

    public function test_home_page_includes_why_choose_blu_bliss_section(): void
    {
        SiteSetting::set('membership_fee', '500');
        SiteSetting::set('shipping_charge', '149');

        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee('Why Choose Blu Bliss');
        $response->assertSee('Purity Guaranteed');
        $response->assertSee('Haridwar at Your Doorstep');
        $response->assertSee('about ₹5 per day');
        $response->assertDontSee('Wear and Tear');
        $response->assertDontSee('PET BOOTEL');
        $response->assertDontSee('0.5kG');
    }
}

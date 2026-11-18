<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AboutUsTest extends TestCase
{
    use RefreshDatabase;

    public function test_about_us_page_loads_successfully_with_updated_corporate_and_brand_content(): void
    {
        $response = $this->get('/about-us');

        $response->assertStatus(200);

        // Corporate & Brand Entity Assertions
        $response->assertSee('Haridwar Bliss Food &amp; Beverages Private Limited', false);
        $response->assertSee('Blu Bliss');
        $response->assertSee('G-46, Shivalik Nagar, BHEL, Ranipur More, Haridwar');
        $response->assertSee('+91 70424 82555');

        // English Version Assertions
        $response->assertSee('From the Holy Ghats of Haridwar to Your Sacred Altar');
        $response->assertSee('Collected at Brahma Muhurta');
        $response->assertSee('Hygienic &amp; Tamper-Proof Packaging', false);
        $response->assertSee('Live Ghat Pujas &amp; Vedic Essentials', false);
        $response->assertSee('Conscious Social &amp; Environmental Pledge', false);

        // Hindi Version Assertions
        $response->assertSee('ब्लू ब्लिस (Blu Bliss) के बारे में');
        $response->assertSee('हरिद्वार के पावन घाटों से सीधे आपके घर के मंदिर तक');
        $response->assertSee('ब्रह्म मुहूर्त में संकलन');
        $response->assertSee('स्वच्छ एवं सुरक्षित पैकेजिंग');
        $response->assertSee('लाइव घाट पूजा एवं वैदिक सामग्री');
        $response->assertSee('पर्यावरण एवं गंगा स्वच्छता संकल्प');
    }
}

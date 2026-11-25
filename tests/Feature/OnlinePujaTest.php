<?php

namespace Tests\Feature;

use App\Models\ContactInquiry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OnlinePujaTest extends TestCase
{
    use RefreshDatabase;

    public function test_online_puja_page_loads_successfully_with_package_details(): void
    {
        $response = $this->get('/online-puja');

        $response->assertStatus(200);
        // Hindi text & pricing assertions
        $response->assertSee('हरिद्वार पावन घाट ऑनलाइन पूजा एवं वैदिक अनुष्ठान');
        $response->assertSee('मंगल उत्सव एवं आयुष्मान संकल्प पूजा');
        $response->assertSee('5,100');
        $response->assertSee('महा-तिथि एवं पर्व विशेष दिव्य अनुष्ठान');
        $response->assertSee('11,000');
        $response->assertSee('15 से 20 मिनट की व्यक्तिगत लाइव स्ट्रीमिंग');
        $response->assertSee('Blu Bliss 500ml');
        $response->assertSee('100% Non-Refundable');
        $response->assertSee('100% गैर-वापसी योग्य');

        // English text & language switcher assertions
        $response->assertSee('Sacred Haridwar Ghats Online Puja &amp; Vedic Rituals', false);
        $response->assertSee('Package 1: "Mangal Utsav &amp; Ayushman Sankalp Puja"', false);
        $response->assertSee('Package 2: "Maha-Tithi &amp; Divine Festival Special Ritual"', false);
        $response->assertSee('Language');
        $response->assertSee('English');
        $response->assertSee('हिन्दी');

        // Modal popup structure assertions
        $response->assertSee('openBookingModal', false);
        $response->assertSee('isBookingModalOpen', false);
        $response->assertSee('closeBookingModal', false);
        $response->assertSee('openTermsModal', false);
        $response->assertSee('isTermsModalOpen', false);
        $response->assertSee('Vedic Sankalp &amp; Online Puja Booking', false);
        $response->assertSee('50% refund');
        $response->assertSee('5 to 7 working days');
        $response->assertSee('6 hours');
        $response->assertSee('3 to 5 working days');
        $response->assertSee('Haridwar District Court, Uttarakhand');
    }

    public function test_navigation_menu_links_to_online_puja_page(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee(route('online-puja'), false);
    }

    public function test_header_and_footer_link_to_about_us_page(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee(route('about'), false);
        $response->assertSee('About Us');
    }

    public function test_devotee_can_submit_online_puja_booking_form(): void
    {
        $bookingData = [
            'package' => 'पैकेज 1: मंगल उत्सव एवं आयुष्मान संकल्प पूजा (₹5,100/-)',
            'devotee_name' => 'आदित्य एवं नेहा शर्मा',
            'gotra' => 'कश्यप',
            'puja_date' => now()->addDays(3)->format('Y-m-d'),
            'occasion' => 'विवाह की 10वीं वर्षगांठ',
            'phone' => '+91 7042482555',
            'email' => 'devotee@example.com',
            'address_line' => '102, गंगा एन्क्लेव, निकट हर की पौड़ी',
            'city' => 'Haridwar',
            'state' => 'Uttarakhand',
            'pincode' => '249401',
            'special_instructions' => 'सुख-शांति एवं उत्तम स्वास्थ्य की विशेष प्रार्थना',
            'terms_agreed' => '1',
        ];

        $response = $this->post(route('online-puja.book'), $bookingData);

        $response->assertRedirect();
        $response->assertSessionHas('booking_success');

        $this->assertDatabaseHas('contact_inquiries', [
            'name' => 'आदित्य एवं नेहा शर्मा',
            'phone' => '+91 7042482555',
            'email' => 'devotee@example.com',
            'subject' => 'Online Puja Booking: पैकेज 1: मंगल उत्सव एवं आयुष्मान संकल्प पूजा (₹5,100/-)',
        ]);

        $inquiry = ContactInquiry::latest('id')->first();
        $this->assertStringContainsString('कश्यप', $inquiry->message);
        $this->assertStringContainsString('Haridwar Ghat E-Puja Terms & Conditions (tiered cancellation & rescheduling policy)', $inquiry->message);
    }

    public function test_booking_submission_fails_if_mandatory_terms_not_agreed(): void
    {
        $bookingData = [
            'package' => 'पैकेज 1: मंगल उत्सव एवं आयुष्मान संकल्प पूजा (₹5,100/-)',
            'devotee_name' => 'आदित्य शर्मा',
            'gotra' => 'कश्यप',
            'puja_date' => now()->addDays(3)->format('Y-m-d'),
            'phone' => '+91 7042482555',
            'shipping_address' => 'हरिद्वार',
            // terms_agreed missing
        ];

        $response = $this->post(route('online-puja.book'), $bookingData);

        $response->assertSessionHasErrors(['terms_agreed']);
        $this->assertDatabaseCount('contact_inquiries', 0);
    }
}

<?php

namespace Tests\Feature;

use App\Models\ContactInquiry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactInquiryTest extends TestCase
{
    use RefreshDatabase;

    public function test_devotee_can_submit_contact_inquiry_and_it_is_persisted(): void
    {
        $response = $this->post('/contact', [
            'name' => 'Aarav Sharma',
            'email' => 'aarav@example.com',
            'phone' => '+91 98765 43210',
            'subject' => 'Holy Ganga Jal Delivery Question',
            'message' => 'Can you please confirm if this Gangajal is sourced directly from Brahmakund Haridwar?',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('contact_inquiries', [
            'name' => 'Aarav Sharma',
            'email' => 'aarav@example.com',
            'phone' => '+91 98765 43210',
            'subject' => 'Holy Ganga Jal Delivery Question',
            'status' => 'unread',
        ]);
    }

    public function test_admin_can_view_and_manage_contact_inquiries(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $inquiry = ContactInquiry::create([
            'name' => 'Pooja Verma',
            'email' => 'pooja@example.com',
            'subject' => 'Subscription Assistance',
            'message' => 'How can I update my delivery address for monthly Gangajal?',
            'status' => 'unread',
        ]);

        // Admin lists inquiries
        $listRes = $this->actingAs($admin)->get('/admin/contact-inquiries');
        $listRes->assertStatus(200);
        $listRes->assertSee('Pooja Verma');
        $listRes->assertSee('Subscription Assistance');

        // Viewing single inquiry marks it as read
        $showRes = $this->actingAs($admin)->get("/admin/contact-inquiries/{$inquiry->id}");
        $showRes->assertStatus(200);
        $this->assertEquals('read', $inquiry->fresh()->status);

        // Update inquiry with admin notes and mark as replied
        $updateRes = $this->actingAs($admin)->put("/admin/contact-inquiries/{$inquiry->id}", [
            'status' => 'replied',
            'admin_notes' => 'Contacted via phone and assisted with address update.',
        ]);
        $updateRes->assertSessionHas('success');
        $this->assertEquals('replied', $inquiry->fresh()->status);
        $this->assertNotNull($inquiry->fresh()->replied_at);
    }
}

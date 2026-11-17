<?php

namespace Tests\Feature;

use App\Models\BulkOrderInquiry;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BulkOrderInquiryTest extends TestCase
{
    use RefreshDatabase;

    private function createProduct(array $overrides = []): Product
    {
        $category = Category::create([
            'name' => 'Sacred Water',
            'slug' => 'sacred-water',
            'is_active' => true,
        ]);

        return Product::create(array_merge([
            'category_id' => $category->id,
            'name' => 'Original Gangajal 1 Litre Brass Pack',
            'slug' => 'original-gangajal-1-litre-brass-pack',
            'sku' => 'GJ-BRASS-1L',
            'price' => 399.00,
            'stock' => 50,
            'is_active' => true,
            'short_description' => 'Pure Haridwar Gangajal',
            'description' => 'Detailed product description',
        ], $overrides));
    }

    public function test_product_detail_page_displays_bulk_inquiry_button_and_modal(): void
    {
        $product = $this->createProduct();

        $response = $this->get("/products/{$product->slug}");

        $response->assertStatus(200);
        $response->assertSee('Bulk Product Inquiry');
        $response->assertSee('open-bulk-inquiry');
        $response->assertSee('Wholesale');
        $response->assertSee('Delivery Address');
        $response->assertSee('City');
        $response->assertSee('State');
        $response->assertSee('Pincode');
    }

    public function test_api_can_submit_bulk_product_inquiry_and_persists_to_database(): void
    {
        $product = $this->createProduct();

        $response = $this->postJson('/api/bulk-inquiry', [
            'name' => 'Pandit Rajesh Sharma',
            'email' => 'rajesh.sharma@temple.org',
            'phone' => '+91 98765 43210',
            'product_id' => $product->id,
            'quantity' => 200,
            'address' => 'Shri Ganga Mandir Ashram, Kankhal Ghat',
            'city' => 'Haridwar',
            'state' => 'Uttarakhand',
            'pincode' => '249408',
            'message' => 'Need 200 consecrated brass bottles for annual temple maha-abhishekam.',
        ]);

        $response->assertStatus(201);
        $response->assertJson([
            'success' => true,
        ]);

        $this->assertDatabaseHas('bulk_order_inquiries', [
            'product_id' => $product->id,
            'product_name' => $product->name,
            'name' => 'Pandit Rajesh Sharma',
            'email' => 'rajesh.sharma@temple.org',
            'phone' => '+91 98765 43210',
            'quantity' => 200,
            'address' => 'Shri Ganga Mandir Ashram, Kankhal Ghat',
            'city' => 'Haridwar',
            'state' => 'Uttarakhand',
            'pincode' => '249408',
            'status' => 'pending',
        ]);
    }

    public function test_bulk_inquiry_validates_required_fields(): void
    {
        $response = $this->postJson('/api/bulk-inquiry', [
            'name' => '',
            'phone' => '',
            'product_id' => 99999,
            'quantity' => 0,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name', 'phone', 'product_id', 'quantity']);
    }

    public function test_guest_cannot_access_admin_bulk_inquiries(): void
    {
        $response = $this->get('/admin/bulk-inquiries');
        $response->assertRedirect('/login');
    }

    public function test_admin_can_view_and_manage_bulk_inquiries(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $product = $this->createProduct();

        $inquiry = BulkOrderInquiry::create([
            'product_id' => $product->id,
            'product_name' => $product->name,
            'name' => 'Manoj Gupta',
            'email' => 'manoj@ashram.in',
            'phone' => '9876500000',
            'quantity' => 500,
            'address' => 'Plot 45, Temple Road, Kankhal',
            'city' => 'Haridwar',
            'state' => 'Uttarakhand',
            'pincode' => '249401',
            'message' => 'Bulk supply for Haridwar pilgrimage camp.',
            'status' => 'pending',
        ]);

        // 1. Admin index lists inquiry
        $listRes = $this->actingAs($admin)->get('/admin/bulk-inquiries');
        $listRes->assertStatus(200);
        $listRes->assertSee('Manoj Gupta');
        $listRes->assertSee('500 units');
        $listRes->assertSee($product->name);
        $listRes->assertSee('Haridwar');

        // 2. Admin can filter by status
        $filterRes = $this->actingAs($admin)->get('/admin/bulk-inquiries?status=pending');
        $filterRes->assertStatus(200);
        $filterRes->assertSee('Manoj Gupta');

        // 3. Admin show details
        $showRes = $this->actingAs($admin)->get("/admin/bulk-inquiries/{$inquiry->id}");
        $showRes->assertStatus(200);
        $showRes->assertSee('Bulk supply for Haridwar pilgrimage camp.');
        $showRes->assertSee('9876500000');
        $showRes->assertSee('Plot 45, Temple Road, Kankhal');
        $showRes->assertSee('Haridwar');
        $showRes->assertSee('Uttarakhand');
        $showRes->assertSee('249401');

        // 4. Admin updates status and adds internal notes
        $updateRes = $this->actingAs($admin)->put("/admin/bulk-inquiries/{$inquiry->id}", [
            'status' => 'quoted',
            'admin_notes' => 'Quoted wholesale price at Rs 280/unit including transport.',
        ]);
        $updateRes->assertSessionHas('success');
        $this->assertEquals('quoted', $inquiry->fresh()->status);
        $this->assertEquals('Quoted wholesale price at Rs 280/unit including transport.', $inquiry->fresh()->admin_notes);

        // 5. Admin can delete inquiry
        $deleteRes = $this->actingAs($admin)->delete("/admin/bulk-inquiries/{$inquiry->id}");
        $deleteRes->assertRedirect(route('admin.bulk-inquiries.index'));
        $this->assertDatabaseMissing('bulk_order_inquiries', ['id' => $inquiry->id]);
    }
}

<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GstReportBreakdownTest extends TestCase
{
    use RefreshDatabase;

    public function test_gst_report_displays_product_and_delivery_breakdown_and_exports_csv(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $customer = User::factory()->create();

        Order::create([
            'order_number' => 'HB-GST-001',
            'user_id' => $customer->id,
            'subtotal' => 500.00,
            'discount_amount' => 50.00, // Product net = 450
            'shipping_amount' => 149.00, // Delivery = 149
            'total_amount' => 599.00,
            'payment_method' => 'mock',
            'payment_status' => 'paid',
            'order_status' => 'delivered',
            'qr_token' => 'QR-GST-1',
            'shipping_address' => [
                'recipient_name' => 'Devotee Test',
                'address_line1' => 'Ghat Road',
                'city' => 'Haridwar',
                'state' => 'Uttarakhand',
                'postal_code' => '249401',
            ],
        ]);

        // 1. Check reports web page
        $res = $this->actingAs($admin)->get('/admin/reports');
        $res->assertStatus(200);
        $res->assertSee('Product Collections');
        $res->assertSee('Delivery Fee Collections');
        $res->assertSee('₹450.00'); // product net
        $res->assertSee('₹149.00'); // delivery net

        // 2. Check GST CSV export
        $exportRes = $this->actingAs($admin)->get('/admin/reports/export/gst');
        $exportRes->assertStatus(200);
        $exportRes->assertHeader('content-type', 'text/csv; charset=UTF-8');

        $content = $exportRes->streamedContent();
        $this->assertStringContainsString('Product Gross (INR)', $content);
        $this->assertStringContainsString('Delivery Fee Gross (INR)', $content);
        $this->assertStringContainsString('Total Amount Received (INR)', $content);
        $this->assertStringContainsString('HB-GST-001', $content);
    }
}

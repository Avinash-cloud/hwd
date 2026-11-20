<?php

namespace Tests\Feature;

use App\Mail\OrderCancelledMail;
use App\Mail\OrderDeliveredMail;
use App\Mail\OrderDispatchedMail;
use App\Mail\OrderOutForDeliveryMail;
use App\Models\Batch;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Services\NimbusPostService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class NimbusPostAndEmailIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected User $admin;

    protected Product $product;

    protected Batch $batch;

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'services.nimbuspost.mock_mode' => true,
            'services.nimbuspost.auto_create_shipment' => true,
            'services.nimbuspost.webhook_secret' => 'test_webhook_secret',
            'mail.default' => 'array',
        ]);

        $this->user = User::factory()->create([
            'name' => 'Sacred Devotee',
            'email' => 'devotee@haridwarbliss.test',
        ]);

        $this->admin = User::factory()->create([
            'name' => 'Store Admin',
            'email' => 'admin@haridwarbliss.test',
            'is_admin' => true,
        ]);

        $this->batch = Batch::create([
            'batch_number' => 'HB-TST-001',
            'sourcing_ghat' => 'Har Ki Pauri',
            'collection_date' => now()->subDays(5),
            'packaging_date' => now()->subDays(2),
            'status' => 'ready',
        ]);

        $category = Category::create([
            'name' => 'Sacred Offerings',
            'slug' => 'sacred-offerings-test',
        ]);

        $this->product = Product::create([
            'category_id' => $category->id,
            'batch_id' => $this->batch->id,
            'name' => 'Original Gangajal 500ml',
            'slug' => 'original-gangajal-500ml-test',
            'price' => 299.00,
            'stock' => 100,
            'is_active' => true,
            'is_gangajal' => true,
        ]);
    }

    protected function createTestOrder(array $attributes = []): Order
    {
        $order = Order::create(array_merge([
            'order_number' => 'HB-ORD-TEST-'.uniqid(),
            'user_id' => $this->user->id,
            'batch_id' => $this->batch->id,
            'subtotal' => 299.00,
            'total_taxable_amount' => 253.39,
            'total_gst_amount' => 45.61,
            'cgst_amount' => 22.80,
            'sgst_amount' => 22.80,
            'igst_amount' => 45.61,
            'discount_amount' => 0.00,
            'shipping_amount' => 50.00,
            'total_amount' => 349.00,
            'payment_method' => 'razorpay',
            'payment_status' => 'paid',
            'payment_id' => 'pay_test_12345',
            'order_status' => 'new',
            'inventory_deducted' => false,
            'qr_token' => 'HB-QR-TEST-'.uniqid(),
            'shipping_address' => [
                'recipient_name' => 'Sacred Devotee',
                'phone' => '9876543210',
                'email' => 'devotee@haridwarbliss.test',
                'address_line1' => 'Ganga Vihar Phase 1',
                'city' => 'New Delhi',
                'state' => 'Delhi',
                'postal_code' => '110001',
                'country' => 'India',
            ],
        ], $attributes));

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $this->product->id,
            'product_name' => $this->product->name,
            'unit_price' => 299.00,
            'quantity' => 1,
            'total_price' => 299.00,
            'hsn_code' => '22019010',
            'gst_percentage' => 18.00,
            'taxable_amount' => 253.39,
            'gst_amount' => 45.61,
        ]);

        return $order;
    }

    public function test_service_creates_nimbuspost_shipment_in_mock_mode(): void
    {
        $order = $this->createTestOrder();
        $service = app(NimbusPostService::class);

        $result = $service->createShipment($order);

        $this->assertTrue($result['success']);
        $this->assertNotEmpty($result['awb_number']);
        $this->assertNotEmpty($result['shipment_id']);

        $order->refresh();
        $this->assertEquals($result['awb_number'], $order->awb_number);
        $this->assertEquals($result['shipment_id'], $order->nimbuspost_shipment_id);
        $this->assertTrue($order->hasNimbusPostShipment());
    }

    public function test_admin_can_book_nimbuspost_shipment(): void
    {
        $order = $this->createTestOrder();

        $response = $this->actingAs($this->admin)->post(route('admin.orders.nimbusShip', $order->id));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $order->refresh();
        $this->assertNotNull($order->awb_number);
        $this->assertEquals('Delhivery Surface', $order->courier_name);
        $this->assertEquals('confirmed', $order->order_status);
    }

    public function test_nimbuspost_webhook_dispatched_event_updates_status_and_sends_email(): void
    {
        Mail::fake();

        $order = $this->createTestOrder([
            'awb_number' => 'NP123456789',
            'order_status' => 'confirmed',
        ]);

        $payload = [
            'event' => 'shipment_status_update',
            'data' => [
                'awb' => 'NP123456789',
                'order_id' => $order->order_number,
                'current_status' => 'In Transit',
                'status_code' => 'IN_TRANSIT',
                'courier_name' => 'Delhivery Surface',
                'location' => 'Ambala Hub',
                'remarks' => 'Parcel arrived at destination transit hub',
                'timestamp' => now()->toIso8601String(),
            ],
        ];

        $response = $this->postJson(route('webhooks.nimbuspost'), $payload, [
            'X-NimbusPost-Signature' => 'test_webhook_secret',
        ]);

        $response->assertOk();
        $response->assertJson(['status' => true]);

        $order->refresh();
        $this->assertEquals('dispatched', $order->order_status);
        $this->assertNotNull($order->dispatched_at);
        $this->assertCount(1, $order->tracking_history);

        Mail::assertSent(OrderDispatchedMail::class, function ($mail) use ($order) {
            return $mail->hasTo($order->customer_email) && $mail->order->id === $order->id;
        });
    }

    public function test_nimbuspost_webhook_out_for_delivery_sends_email(): void
    {
        Mail::fake();

        $order = $this->createTestOrder([
            'awb_number' => 'NP998877665',
            'order_status' => 'dispatched',
        ]);

        $payload = [
            'data' => [
                'awb' => 'NP998877665',
                'current_status' => 'Out for Delivery',
                'status_code' => 'OFD',
                'location' => 'Delhi South Hub',
                'remarks' => 'Package out for delivery with courier executive',
            ],
        ];

        $response = $this->postJson(route('webhooks.nimbuspost'), $payload, [
            'X-NimbusPost-Signature' => 'test_webhook_secret',
        ]);

        $response->assertOk();

        Mail::assertSent(OrderOutForDeliveryMail::class, function ($mail) use ($order) {
            return $mail->hasTo($order->customer_email);
        });
    }

    public function test_nimbuspost_webhook_delivered_event_updates_status_and_sends_email(): void
    {
        Mail::fake();

        $order = $this->createTestOrder([
            'awb_number' => 'NP554433221',
            'order_status' => 'dispatched',
        ]);

        $payload = [
            'data' => [
                'awb' => 'NP554433221',
                'current_status' => 'Delivered',
                'status_code' => 'DEL',
                'location' => 'Customer Sanctuary',
                'remarks' => 'Sacred parcel successfully delivered',
            ],
        ];

        $response = $this->postJson(route('webhooks.nimbuspost'), $payload, [
            'X-NimbusPost-Signature' => 'test_webhook_secret',
        ]);

        $response->assertOk();

        $order->refresh();
        $this->assertEquals('delivered', $order->order_status);
        $this->assertNotNull($order->delivered_at);

        Mail::assertSent(OrderDeliveredMail::class, function ($mail) use ($order) {
            return $mail->hasTo($order->customer_email);
        });
    }

    public function test_devotee_can_cancel_order_before_dispatch_which_cancels_nimbuspost_and_sends_email(): void
    {
        Mail::fake();

        $order = $this->createTestOrder([
            'order_status' => 'confirmed',
            'awb_number' => 'NP778899001',
            'inventory_deducted' => true,
        ]);

        $this->product->update(['stock' => 99]);

        $response = $this->actingAs($this->user)->post(route('orders.cancel', $order->order_number), [
            'reason' => 'Ordered duplicate bottle by mistake',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $order->refresh();
        $this->assertEquals('cancelled', $order->order_status);
        $this->assertEquals('cancelled', $order->nimbuspost_status);
        $this->assertNotNull($order->cancelled_at);
        $this->assertEquals('Ordered duplicate bottle by mistake', $order->cancellation_reason);

        // Inventory should be restored
        $this->product->refresh();
        $this->assertEquals(100, $this->product->stock);

        Mail::assertSent(OrderCancelledMail::class, function ($mail) use ($order) {
            return $mail->hasTo($order->customer_email);
        });
    }

    public function test_admin_status_update_to_cancelled_cancels_nimbuspost_and_sends_email(): void
    {
        Mail::fake();

        $order = $this->createTestOrder([
            'order_status' => 'confirmed',
            'awb_number' => 'NP112233445',
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.orders.status', $order->id), [
            'order_status' => 'cancelled',
            'admin_notes' => 'Customer requested cancellation over devotee phone support',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $order->refresh();
        $this->assertEquals('cancelled', $order->order_status);
        $this->assertEquals('cancelled', $order->nimbuspost_status);

        Mail::assertSent(OrderCancelledMail::class, function ($mail) use ($order) {
            return $mail->hasTo($order->customer_email);
        });
    }

    public function test_admin_refresh_tracking_and_label_redirect(): void
    {
        $order = $this->createTestOrder([
            'awb_number' => 'NP99001122',
            'label_url' => 'https://example.com/mock-label.pdf',
        ]);

        $trackResponse = $this->actingAs($this->admin)->get(route('admin.orders.nimbusTrack', $order->id));
        $trackResponse->assertRedirect();
        $trackResponse->assertSessionHas('success');

        $labelResponse = $this->actingAs($this->admin)->get(route('admin.orders.downloadLabel', $order->id));
        $labelResponse->assertRedirect('https://example.com/mock-label.pdf');
    }
}

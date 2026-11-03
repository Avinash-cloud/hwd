<?php

namespace Tests\Feature;

use App\Models\Batch;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminOrderManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_orders_defaults_to_new_orders(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $customer = User::factory()->create();

        $newOrder = Order::create([
            'order_number' => 'HB-NEW-001',
            'user_id' => $customer->id,
            'subtotal' => 149.00,
            'total_amount' => 149.00,
            'order_status' => 'new',
            'qr_token' => 'QR-NEW-1',
            'shipping_address' => ['recipient_name' => 'John', 'address_line1' => 'Street', 'city' => 'Delhi', 'state' => 'Delhi', 'postal_code' => '110001'],
        ]);

        $deliveredOrder = Order::create([
            'order_number' => 'HB-DEL-002',
            'user_id' => $customer->id,
            'subtotal' => 149.00,
            'total_amount' => 149.00,
            'order_status' => 'delivered',
            'qr_token' => 'QR-DEL-2',
            'shipping_address' => ['recipient_name' => 'Jane', 'address_line1' => 'Street', 'city' => 'Delhi', 'state' => 'Delhi', 'postal_code' => '110001'],
        ]);

        // Accessing /admin/orders without parameters defaults to 'new' orders
        $response = $this->actingAs($admin)->get('/admin/orders');
        $response->assertStatus(200);
        $response->assertSee('HB-NEW-001');
        $response->assertDontSee('HB-DEL-002');

        // Accessing 'all' status shows both
        $allRes = $this->actingAs($admin)->get('/admin/orders?status=all');
        $allRes->assertStatus(200);
        $allRes->assertSee('HB-NEW-001');
        $allRes->assertSee('HB-DEL-002');
    }

    public function test_admin_can_perform_bulk_batch_assignment_and_status_update(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $customer = User::factory()->create();

        $batch = Batch::create([
            'batch_number' => 'HB-BATCH-BULK-01',
            'sourcing_ghat' => 'Har Ki Pauri',
            'collection_date' => now(),
            'packaging_date' => now(),
            'status' => 'ready',
        ]);

        $order1 = Order::create([
            'order_number' => 'HB-BULK-01',
            'user_id' => $customer->id,
            'subtotal' => 149.00,
            'total_amount' => 149.00,
            'order_status' => 'new',
            'qr_token' => 'QR-B1',
            'shipping_address' => ['recipient_name' => 'Devotee 1', 'address_line1' => 'Line 1', 'city' => 'Haridwar', 'state' => 'Uttarakhand', 'postal_code' => '249401'],
        ]);

        $order2 = Order::create([
            'order_number' => 'HB-BULK-02',
            'user_id' => $customer->id,
            'subtotal' => 149.00,
            'total_amount' => 149.00,
            'order_status' => 'new',
            'qr_token' => 'QR-B2',
            'shipping_address' => ['recipient_name' => 'Devotee 2', 'address_line1' => 'Line 2', 'city' => 'Haridwar', 'state' => 'Uttarakhand', 'postal_code' => '249401'],
        ]);

        // Bulk assign batch
        $resBatch = $this->actingAs($admin)->post('/admin/orders/bulk-update', [
            'order_ids' => [$order1->id, $order2->id],
            'bulk_action' => 'assign_batch',
            'target_batch_id' => $batch->id,
        ]);
        $resBatch->assertSessionHas('success');
        $this->assertEquals($batch->id, $order1->fresh()->batch_id);
        $this->assertEquals($batch->id, $order2->fresh()->batch_id);

        // Bulk update status to dispatched
        $resStatus = $this->actingAs($admin)->post('/admin/orders/bulk-update', [
            'order_ids' => [$order1->id, $order2->id],
            'bulk_action' => 'update_status',
            'target_status' => 'dispatched',
        ]);
        $resStatus->assertSessionHas('success');
        $this->assertEquals('dispatched', $order1->fresh()->order_status);
        $this->assertEquals('dispatched', $order2->fresh()->order_status);
        $this->assertNotNull($order1->fresh()->dispatched_at);
    }

    public function test_admin_single_status_update_auto_deducts_and_reverses_inventory(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $customer = User::factory()->create();

        $category = Category::create(['name' => 'Sacred', 'slug' => 'sacred']);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Consecrated Rudraksha Mala',
            'slug' => 'rudraksha-mala',
            'price' => 350.00,
            'stock' => 50,
        ]);

        $order = Order::create([
            'order_number' => 'HB-STAT-001',
            'user_id' => $customer->id,
            'subtotal' => 350.00,
            'total_amount' => 499.00,
            'order_status' => 'new',
            'inventory_deducted' => false,
            'qr_token' => 'QR-S1',
            'shipping_address' => ['recipient_name' => 'Devotee', 'address_line1' => 'Ghat', 'city' => 'Haridwar', 'state' => 'Uttarakhand', 'postal_code' => '249401'],
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'unit_price' => 350.00,
            'quantity' => 1,
            'total_price' => 350.00,
        ]);

        // 1. Admin confirms order -> stock drops to 49
        $resConfirm = $this->actingAs($admin)->post("/admin/orders/{$order->id}/status", [
            'order_status' => 'confirmed',
        ]);
        $resConfirm->assertSessionHas('success');
        $this->assertEquals(49, $product->fresh()->stock);
        $this->assertTrue($order->fresh()->inventory_deducted);

        // 2. Admin cancels order -> stock restores to 50
        $resCancel = $this->actingAs($admin)->post("/admin/orders/{$order->id}/status", [
            'order_status' => 'cancelled',
        ]);
        $resCancel->assertSessionHas('success');
        $this->assertEquals(50, $product->fresh()->stock);
        $this->assertFalse($order->fresh()->inventory_deducted);
    }

    public function test_admin_order_show_view_contains_financial_and_address_details(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $customer = User::factory()->create(['email' => 'devotee@example.com']);

        $category = Category::create(['name' => 'Offerings', 'slug' => 'offerings']);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Brahmakund Gangajal 500ml',
            'slug' => 'gangajal-show-500ml',
            'price' => 149.00,
            'stock' => 20,
        ]);

        $order = Order::create([
            'order_number' => 'HB-SHOW-999',
            'user_id' => $customer->id,
            'subtotal' => 149.00,
            'discount_amount' => 149.00,
            'coupon_code' => 'FESTIVE10',
            'coupon_discount' => 10.00,
            'shipping_amount' => 149.00,
            'total_amount' => 139.00,
            'order_status' => 'new',
            'qr_token' => 'QR-SHOW-99',
            'payment_method' => 'razorpay',
            'payment_status' => 'paid',
            'payment_id' => 'pay_TEST_12345',
            'shipping_address' => [
                'recipient_name' => 'Aarohi Sen',
                'phone' => '+91 9988776655',
                'address_line1' => 'Flat 402, Ganga Heights',
                'address_line2' => 'Near Shanti Kunj',
                'landmark' => 'Near River Gate',
                'city' => 'Haridwar',
                'state' => 'Uttarakhand',
                'postal_code' => '249401',
                'country' => 'India',
            ],
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'unit_price' => 149.00,
            'quantity' => 1,
            'total_price' => 0.00,
            'is_free_monthly_bottle' => true,
        ]);

        $res = $this->actingAs($admin)->get("/admin/orders/{$order->id}");
        $res->assertStatus(200);
        $res->assertSee('HB-SHOW-999');
        $res->assertSee('Aarohi Sen');
        $res->assertSee('Flat 402, Ganga Heights');
        $res->assertSee('Near River Gate');
        $res->assertSee('249401');
        $res->assertSee('pay_TEST_12345');
        $res->assertSee('FESTIVE10');
        $res->assertSee('Monthly Member Free Quota');
    }

    public function test_admin_orders_tabs_math_matches_total_count_across_all_statuses(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $customer = User::factory()->create();

        $statuses = ['new', 'confirmed', 'batched', 'dispatched', 'delivered', 'cancelled'];
        foreach ($statuses as $idx => $status) {
            Order::create([
                'order_number' => "HB-MATH-{$idx}",
                'user_id' => $customer->id,
                'subtotal' => 149.00,
                'total_amount' => 149.00,
                'order_status' => $status,
                'qr_token' => "QR-MATH-{$idx}",
                'shipping_address' => ['recipient_name' => "User {$idx}", 'address_line1' => 'Ghat', 'city' => 'Haridwar', 'state' => 'Uttarakhand', 'postal_code' => '249401'],
            ]);
        }

        $res = $this->actingAs($admin)->get('/admin/orders?status=all');
        $res->assertStatus(200);

        $counts = $res->viewData('counts');
        $sumOfStatusTabs = $counts['new'] + $counts['confirmed'] + $counts['batched'] + $counts['dispatched'] + $counts['delivered'] + $counts['cancelled'];
        $this->assertEquals($counts['all'], $sumOfStatusTabs);
        $this->assertEquals(6, $counts['all']);

        // Check each tab renders its count
        $res->assertSee('Batched');
        $res->assertSee('Cancelled');
        $res->assertSee('Confirmed');
        $res->assertSee('Dispatched');
        $res->assertSee('Delivered');
        $res->assertSee('All Orders (6)');
    }
}

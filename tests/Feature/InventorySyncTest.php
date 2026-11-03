<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Services\InventoryService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InventorySyncTest extends TestCase
{
    use RefreshDatabase;

    public function test_inventory_sync_is_automated_and_reversible(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $customer = User::factory()->create();
        $category = Category::create(['name' => 'General', 'slug' => 'general']);

        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Sacred Copper Lota',
            'slug' => 'sacred-copper-lota',
            'price' => 299.00,
            'stock' => 20, // initial stock
        ]);

        $order = Order::create([
            'order_number' => 'HB-INV-001',
            'user_id' => $customer->id,
            'subtotal' => 598.00,
            'total_amount' => 598.00,
            'order_status' => 'new',
            'inventory_deducted' => false,
            'qr_token' => 'QR-INV-1',
            'shipping_address' => ['recipient_name' => 'Test', 'address_line1' => 'Ghat', 'city' => 'Haridwar', 'state' => 'Uttarakhand', 'postal_code' => '249401'],
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'unit_price' => 299.00,
            'quantity' => 2,
            'total_price' => 598.00,
        ]);

        $inventoryService = app(InventoryService::class);

        // Step 1: Admin confirms order -> stock should decrement by 2 (from 20 to 18)
        $inventoryService->syncStatus($order->fresh(), 'confirmed');
        $this->assertEquals(18, $product->fresh()->stock);
        $this->assertTrue($order->fresh()->inventory_deducted);

        // Step 2: Admin moves to batched -> stock should remain 18 (no double deduction)
        $inventoryService->syncStatus($order->fresh(), 'batched');
        $this->assertEquals(18, $product->fresh()->stock);

        // Step 3: Order is cancelled -> stock should auto-increase by 2 (back to 20)
        $inventoryService->syncStatus($order->fresh(), 'cancelled');
        $this->assertEquals(20, $product->fresh()->stock);
        $this->assertFalse($order->fresh()->inventory_deducted);

        // Step 4: Order is un-cancelled back to confirmed -> stock should auto-decrement by 2 (back to 18)
        $inventoryService->syncStatus($order->fresh(), 'confirmed');
        $this->assertEquals(18, $product->fresh()->stock);
        $this->assertTrue($order->fresh()->inventory_deducted);
    }
}

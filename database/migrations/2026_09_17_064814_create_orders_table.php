<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('batch_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('subtotal', 10, 2);
            $table->decimal('discount_amount', 10, 2)->default(0.00);
            $table->decimal('shipping_amount', 10, 2)->default(149.00);
            $table->decimal('total_amount', 10, 2);
            $table->string('payment_method')->default('mock'); // razorpay, cod, mock
            $table->string('payment_status')->default('pending'); // pending, paid, failed, refunded
            $table->string('payment_id')->nullable();
            $table->string('order_status')->default('placed'); // placed, confirmed, batched, dispatched, in_transit, delivered, cancelled
            $table->string('tracking_number')->nullable();
            $table->string('courier_name')->nullable();
            $table->string('qr_token')->unique();
            $table->json('shipping_address');
            $table->json('billing_address')->nullable();
            $table->text('customer_notes')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamp('dispatched_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};

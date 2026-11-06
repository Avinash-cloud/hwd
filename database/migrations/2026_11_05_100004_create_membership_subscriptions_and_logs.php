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
        Schema::create('membership_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('membership_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('variant_id')->nullable()->constrained('product_variants')->nullOnDelete();
            $table->string('status')->default('active'); // active, paused, past_due, cancelled
            $table->integer('billing_interval_days')->default(30);
            $table->decimal('billing_amount', 10, 2)->default(149.00); // Standard delivery or product charge
            $table->timestamp('next_billing_date');
            $table->timestamp('last_billed_at')->nullable();
            $table->string('razorpay_subscription_id')->nullable()->index();
            $table->string('razorpay_plan_id')->nullable();
            $table->string('razorpay_customer_id')->nullable();
            $table->integer('payment_failures_count')->default(0);
            $table->json('shipping_address')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('subscription_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscription_id')->constrained('membership_subscriptions')->cascadeOnDelete();
            $table->string('event'); // payment_success, payment_failed, order_created, cancelled, retried
            $table->decimal('amount', 10, 2)->default(0.00);
            $table->foreignId('order_id')->nullable()->constrained('orders')->nullOnDelete();
            $table->string('payment_id')->nullable();
            $table->json('details')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_logs');
        Schema::dropIfExists('membership_subscriptions');
    }
};

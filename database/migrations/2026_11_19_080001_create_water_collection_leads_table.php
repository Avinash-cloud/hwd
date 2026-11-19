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
        if (! Schema::hasTable('water_collection_leads')) {
            Schema::create('water_collection_leads', function (Blueprint $table) {
                $table->id();
                $table->string('lead_number')->unique();
                $table->string('name');
                $table->string('mobile', 25);
                $table->string('email')->nullable();
                $table->text('delivery_address');
                $table->string('delivery_city', 100)->nullable();
                $table->string('delivery_state', 100)->nullable();
                $table->string('delivery_pincode', 20)->nullable();
                $table->string('hotel_or_dharamshala');
                $table->string('pickup_room_or_landmark')->nullable();
                $table->foreignId('package_id')->nullable()->constrained('water_collection_packages')->nullOnDelete();
                $table->string('package_name');
                $table->string('water_quantity');
                $table->unsignedInteger('quantity_count')->default(1);
                $table->decimal('unit_price', 10, 2);
                $table->decimal('total_price', 10, 2);
                $table->date('pickup_date')->nullable();
                $table->text('notes')->nullable();
                $table->string('status')->default('pending'); // pending, contacted, scheduled, collected, dispatched, delivered, cancelled
                $table->text('admin_notes')->nullable();
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('water_collection_leads');
    }
};

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
        if (! Schema::hasTable('water_collection_packages')) {
            Schema::create('water_collection_packages', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->decimal('quantity_litres', 8, 2)->default(1.00);
                $table->string('unit')->default('Litre');
                $table->string('container_type')->default('Food-Grade Sacred Bottle');
                $table->decimal('price', 10, 2);
                $table->decimal('original_price', 10, 2)->nullable();
                $table->text('description')->nullable();
                $table->string('badge')->nullable();
                $table->integer('sort_order')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('water_collection_packages');
    }
};

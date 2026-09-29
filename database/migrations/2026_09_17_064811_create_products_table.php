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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('batch_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('member_price', 10, 2)->nullable();
            $table->boolean('is_gangajal')->default(false);
            $table->integer('volume_ml')->nullable();
            $table->integer('stock')->default(100);
            $table->string('sku')->nullable();
            $table->string('image')->nullable();
            $table->json('gallery')->nullable();
            $table->string('video_url')->nullable();
            $table->text('purity_details')->nullable();
            $table->json('specifications')->nullable();
            $table->text('benefits')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

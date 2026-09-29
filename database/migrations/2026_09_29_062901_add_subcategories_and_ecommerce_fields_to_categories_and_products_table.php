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
        Schema::table('categories', function (Blueprint $table) {
            $table->foreignId('parent_id')->nullable()->after('id')->constrained('categories')->nullOnDelete();
            $table->string('icon')->nullable()->after('image');
            $table->boolean('is_featured')->default(false)->after('is_active');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->decimal('compare_price', 10, 2)->nullable()->after('price');
            $table->decimal('rating', 3, 2)->default(4.80)->after('member_price');
            $table->unsignedInteger('reviews_count')->default(15)->after('rating');
            $table->string('badge')->nullable()->after('reviews_count');
            $table->boolean('is_bestseller')->default(false)->after('is_featured');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['compare_price', 'rating', 'reviews_count', 'badge', 'is_bestseller']);
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn(['parent_id', 'icon', 'is_featured']);
        });
    }
};

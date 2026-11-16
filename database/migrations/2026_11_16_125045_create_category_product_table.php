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
        Schema::create('category_product', function (Blueprint $table) {
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->primary(['product_id', 'category_id']);
        });

        // Migrate existing product-category associations to pivot table
        $existing = DB::table('products')->whereNotNull('category_id')->select('id', 'category_id')->get();
        $inserts = [];
        foreach ($existing as $p) {
            $inserts[] = [
                'product_id' => $p->id,
                'category_id' => $p->category_id,
            ];
        }
        if (! empty($inserts)) {
            DB::table('category_product')->insertOrIgnore($inserts);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_product');
    }
};

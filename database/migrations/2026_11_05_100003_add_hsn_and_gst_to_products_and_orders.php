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
        Schema::table('products', function (Blueprint $table) {
            $table->string('hsn_code', 20)->nullable()->after('sku');
            $table->decimal('gst_percentage', 5, 2)->default(18.00)->after('price');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->foreignId('variant_id')->nullable()->after('product_id')->constrained('product_variants')->nullOnDelete();
            $table->string('variant_name')->nullable()->after('product_name');
            $table->string('hsn_code', 20)->nullable()->after('variant_name');
            $table->decimal('gst_percentage', 5, 2)->default(18.00)->after('hsn_code');
            $table->decimal('taxable_amount', 10, 2)->default(0.00)->after('unit_price');
            $table->decimal('gst_amount', 10, 2)->default(0.00)->after('taxable_amount');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('total_taxable_amount', 10, 2)->default(0.00)->after('subtotal');
            $table->decimal('total_gst_amount', 10, 2)->default(0.00)->after('total_taxable_amount');
            $table->decimal('cgst_amount', 10, 2)->default(0.00)->after('total_gst_amount');
            $table->decimal('sgst_amount', 10, 2)->default(0.00)->after('cgst_amount');
            $table->decimal('igst_amount', 10, 2)->default(0.00)->after('sgst_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'total_taxable_amount',
                'total_gst_amount',
                'cgst_amount',
                'sgst_amount',
                'igst_amount',
            ]);
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropForeign(['variant_id']);
            $table->dropColumn([
                'variant_id',
                'variant_name',
                'hsn_code',
                'gst_percentage',
                'taxable_amount',
                'gst_amount',
            ]);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['hsn_code', 'gst_percentage']);
        });
    }
};

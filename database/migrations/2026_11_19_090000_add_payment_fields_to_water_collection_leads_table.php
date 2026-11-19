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
        Schema::table('water_collection_leads', function (Blueprint $table) {
            if (! Schema::hasColumn('water_collection_leads', 'payment_method')) {
                $table->string('payment_method')->default('razorpay')->after('total_price');
            }
            if (! Schema::hasColumn('water_collection_leads', 'payment_status')) {
                $table->string('payment_status')->default('pending')->after('payment_method'); // pending, paid, failed, cod
            }
            if (! Schema::hasColumn('water_collection_leads', 'razorpay_payment_id')) {
                $table->string('razorpay_payment_id')->nullable()->after('payment_status');
            }
            if (! Schema::hasColumn('water_collection_leads', 'razorpay_order_id')) {
                $table->string('razorpay_order_id')->nullable()->after('razorpay_payment_id');
            }
            if (! Schema::hasColumn('water_collection_leads', 'razorpay_signature')) {
                $table->string('razorpay_signature')->nullable()->after('razorpay_order_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('water_collection_leads', function (Blueprint $table) {
            $columns = array_filter(['payment_method', 'payment_status', 'razorpay_payment_id', 'razorpay_order_id', 'razorpay_signature'], fn ($col) => Schema::hasColumn('water_collection_leads', $col));
            if (! empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};

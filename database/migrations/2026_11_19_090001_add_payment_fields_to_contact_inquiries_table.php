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
        Schema::table('contact_inquiries', function (Blueprint $table) {
            if (! Schema::hasColumn('contact_inquiries', 'amount')) {
                $table->decimal('amount', 10, 2)->nullable()->after('subject');
            }
            if (! Schema::hasColumn('contact_inquiries', 'payment_method')) {
                $table->string('payment_method')->nullable()->after('amount');
            }
            if (! Schema::hasColumn('contact_inquiries', 'payment_status')) {
                $table->string('payment_status')->default('unpaid')->after('payment_method');
            }
            if (! Schema::hasColumn('contact_inquiries', 'razorpay_payment_id')) {
                $table->string('razorpay_payment_id')->nullable()->after('payment_status');
            }
            if (! Schema::hasColumn('contact_inquiries', 'razorpay_order_id')) {
                $table->string('razorpay_order_id')->nullable()->after('razorpay_payment_id');
            }
            if (! Schema::hasColumn('contact_inquiries', 'razorpay_signature')) {
                $table->string('razorpay_signature')->nullable()->after('razorpay_order_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contact_inquiries', function (Blueprint $table) {
            $columns = array_filter(['amount', 'payment_method', 'payment_status', 'razorpay_payment_id', 'razorpay_order_id', 'razorpay_signature'], fn ($col) => Schema::hasColumn('contact_inquiries', $col));
            if (! empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};

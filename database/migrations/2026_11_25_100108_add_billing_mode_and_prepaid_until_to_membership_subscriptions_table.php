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
        Schema::table('membership_subscriptions', function (Blueprint $table): void {
            $table->string('billing_mode')->default('razorpay')->after('status');
            $table->timestamp('prepaid_until')->nullable()->after('last_billed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('membership_subscriptions', function (Blueprint $table): void {
            $table->dropColumn(['billing_mode', 'prepaid_until']);
        });
    }
};

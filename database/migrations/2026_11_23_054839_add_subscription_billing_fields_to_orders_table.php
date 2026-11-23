<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('membership_subscription_id')->nullable()->after('batch_id');
            $table->string('billing_period', 7)->nullable()->after('membership_subscription_id');

            $table->unique(
                ['membership_subscription_id', 'billing_period'],
                'orders_sub_billing_unique'
            );

            $table->foreign('membership_subscription_id')
                ->references('id')
                ->on('membership_subscriptions')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropUnique('orders_sub_billing_unique');
            $table->dropForeign(['membership_subscription_id']);
            $table->dropColumn(['membership_subscription_id', 'billing_period']);
        });
    }
};

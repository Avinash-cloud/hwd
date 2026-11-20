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
        Schema::table('orders', function (Blueprint $table): void {
            $table->string('nimbuspost_order_id')->nullable()->after('courier_name');
            $table->string('nimbuspost_shipment_id')->nullable()->after('nimbuspost_order_id');
            $table->string('awb_number')->nullable()->index()->after('nimbuspost_shipment_id');
            $table->string('courier_id')->nullable()->after('awb_number');
            $table->text('label_url')->nullable()->after('courier_id');
            $table->text('manifest_url')->nullable()->after('label_url');
            $table->string('nimbuspost_status')->nullable()->after('manifest_url');
            $table->json('tracking_history')->nullable()->after('nimbuspost_status');
            $table->timestamp('cancelled_at')->nullable()->after('delivered_at');
            $table->text('cancellation_reason')->nullable()->after('cancelled_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table): void {
            $table->dropColumn([
                'nimbuspost_order_id',
                'nimbuspost_shipment_id',
                'awb_number',
                'courier_id',
                'label_url',
                'manifest_url',
                'nimbuspost_status',
                'tracking_history',
                'cancelled_at',
                'cancellation_reason',
            ]);
        });
    }
};

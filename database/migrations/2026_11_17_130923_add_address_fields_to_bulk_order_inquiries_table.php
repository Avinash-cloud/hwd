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
        Schema::table('bulk_order_inquiries', function (Blueprint $table) {
            if (! Schema::hasColumn('bulk_order_inquiries', 'address')) {
                $table->text('address')->nullable()->after('quantity');
            }
            if (! Schema::hasColumn('bulk_order_inquiries', 'city')) {
                $table->string('city', 100)->nullable()->after('address');
            }
            if (! Schema::hasColumn('bulk_order_inquiries', 'state')) {
                $table->string('state', 100)->nullable()->after('city');
            }
            if (! Schema::hasColumn('bulk_order_inquiries', 'pincode')) {
                $table->string('pincode', 20)->nullable()->after('state');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bulk_order_inquiries', function (Blueprint $table) {
            $columns = array_filter(['address', 'city', 'state', 'pincode'], fn ($col) => Schema::hasColumn('bulk_order_inquiries', $col));
            if (! empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};

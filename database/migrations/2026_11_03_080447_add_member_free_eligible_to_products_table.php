<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('is_member_free_eligible')->default(false)->after('is_gangajal');
        });

        // Set default eligibility: any existing Ganga Jal products are free monthly eligible by default
        DB::table('products')->where('is_gangajal', true)->update(['is_member_free_eligible' => true]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('is_member_free_eligible');
        });
    }
};

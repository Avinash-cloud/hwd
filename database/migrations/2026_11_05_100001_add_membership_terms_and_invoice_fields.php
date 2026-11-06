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
        Schema::table('memberships', function (Blueprint $table) {
            $table->string('invoice_number')->nullable()->unique()->after('payment_id');
            $table->timestamp('terms_accepted_at')->nullable()->after('terms_accepted');
            $table->string('terms_ip_address', 45)->nullable()->after('terms_accepted_at');
            $table->text('terms_user_agent')->nullable()->after('terms_ip_address');
            $table->string('terms_version', 20)->default('1.0')->after('terms_user_agent');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('memberships', function (Blueprint $table) {
            $table->dropColumn([
                'invoice_number',
                'terms_accepted_at',
                'terms_ip_address',
                'terms_user_agent',
                'terms_version',
            ]);
        });
    }
};

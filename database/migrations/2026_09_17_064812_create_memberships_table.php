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
        Schema::create('memberships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('status')->default('active'); // active, expired, cancelled, pending
            $table->decimal('fee_paid', 10, 2)->default(500.00);
            $table->integer('validity_years')->default(5);
            $table->timestamp('starts_at');
            $table->timestamp('expires_at');
            $table->string('payment_method')->nullable();
            $table->string('payment_id')->nullable();
            $table->boolean('terms_accepted')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('memberships');
    }
};

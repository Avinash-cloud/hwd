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
        Schema::create('batches', function (Blueprint $table) {
            $table->id();
            $table->string('batch_number')->unique();
            $table->string('sourcing_ghat')->default('Har Ki Pauri, Brahmakund, Haridwar');
            $table->date('collection_date');
            $table->date('packaging_date');
            $table->text('description')->nullable();
            $table->text('purity_notes')->nullable();
            $table->string('lab_certificate_path')->nullable();
            $table->string('video_url')->nullable();
            $table->string('image')->nullable();
            $table->string('status')->default('ready');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batches');
    }
};

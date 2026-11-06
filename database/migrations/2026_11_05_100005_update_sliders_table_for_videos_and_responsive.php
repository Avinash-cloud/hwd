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
        Schema::table('sliders', function (Blueprint $table) {
            $table->string('image')->nullable()->change();
            $table->string('media_type', 20)->default('image')->after('description'); // 'image' or 'video'
            $table->string('video_url')->nullable()->after('media_type');
            $table->string('video_file')->nullable()->after('video_url');
            $table->string('tablet_image')->nullable()->after('image');
            $table->string('mobile_image')->nullable()->after('tablet_image');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sliders', function (Blueprint $table) {
            $table->dropColumn([
                'media_type',
                'video_url',
                'video_file',
                'tablet_image',
                'mobile_image',
            ]);
        });
    }
};

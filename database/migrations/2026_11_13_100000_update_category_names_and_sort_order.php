<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('categories')) {
            DB::table('categories')->where('slug', 'sacred-gangajal')->update(['sort_order' => 1]);
            DB::table('categories')->where('slug', 'puja-kits')->update([
                'name' => 'Festival & Pooja Kit',
                'sort_order' => 2,
            ]);
            DB::table('categories')->where('slug', 'puja-essentials')->update(['sort_order' => 3]);
            DB::table('categories')->where('slug', 'hawan-samagri')->update(['sort_order' => 4]);
            DB::table('categories')->where('slug', 'dhoop-incense')->update(['sort_order' => 5]);
            DB::table('categories')->where('slug', 'spiritual-items')->update(['sort_order' => 6]);
            DB::table('categories')->where('slug', 'brass-pooja-ware')->update(['sort_order' => 7]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('categories')) {
            DB::table('categories')->where('slug', 'puja-kits')->update([
                'name' => 'Festive & Puja Kits',
                'sort_order' => 6,
            ]);
            DB::table('categories')->where('slug', 'puja-essentials')->update(['sort_order' => 1]);
            DB::table('categories')->where('slug', 'hawan-samagri')->update(['sort_order' => 2]);
            DB::table('categories')->where('slug', 'sacred-gangajal')->update(['sort_order' => 3]);
            DB::table('categories')->where('slug', 'dhoop-incense')->update(['sort_order' => 4]);
            DB::table('categories')->where('slug', 'spiritual-items')->update(['sort_order' => 5]);
            DB::table('categories')->where('slug', 'brass-pooja-ware')->update(['sort_order' => 7]);
        }
    }
};

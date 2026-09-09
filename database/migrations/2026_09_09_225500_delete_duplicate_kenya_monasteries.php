<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Removes any accidental corrupt duplicate rows (e.g. from Kenya/Equator) and ensures exactly 260 monasteries exist.
     */
    public function up(): void
    {
        // 1. Delete any duplicate rows with ID > 260
        DB::table('monasteries')->where('id', '>', 260)->delete();

        // 2. Delete any row with latitude 0 or outside Serbia's latitude (< 40)
        DB::table('monasteries')->where('latitude', '<', 40)->orWhere('lat', '<', 40)->delete();
        DB::table('monasteries')->where('latitude', 0)->orWhere('lat', 0)->delete();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No down action needed
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('monasteries', function (Blueprint $table) {

            // Dodaj samo ono što nedostaje u tvojoj bazi.
            // Ako ti neke od ovih kolona već postoje, obriši taj red.

            if (!Schema::hasColumn('monasteries', 'image')) {
                $table->string('image')->nullable()->after('lng');
            }
            if (!Schema::hasColumn('monasteries', 'excerpt')) {
                $table->text('excerpt')->nullable()->after('image');
            }
            if (!Schema::hasColumn('monasteries', 'source_url')) {
                $table->string('source_url')->nullable()->after('excerpt');
            }

            // Ako nemaš lat/lng, dodaj i to (sigurnosno)
            if (!Schema::hasColumn('monasteries', 'lat')) {
                $table->decimal('lat', 10, 7)->nullable()->after('address');
            }
            if (!Schema::hasColumn('monasteries', 'lng')) {
                $table->decimal('lng', 10, 7)->nullable()->after('lat');
            }
        });
    }

    public function down(): void
    {
        Schema::table('monasteries', function (Blueprint $table) {
            // SQLite ume da pravi probleme sa dropColumn u nekim slučajevima,
            // ali za lokalno ti je OK.
            $drops = [];
            foreach (['image','excerpt','source_url','lat','lng'] as $col) {
                if (Schema::hasColumn('monasteries', $col)) $drops[] = $col;
            }
            if ($drops) $table->dropColumn($drops);
        });
    }
};
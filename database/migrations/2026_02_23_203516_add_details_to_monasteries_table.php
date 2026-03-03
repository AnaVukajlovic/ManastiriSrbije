<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('monasteries', function (Blueprint $table) {

            // NOVO: FK (za filtriranje)
            if (!Schema::hasColumn('monasteries', 'region_id')) {
                $table->foreignId('region_id')->nullable()->after('eparchy_id')
                    ->constrained('regions')->nullOnDelete();
            }

            if (!Schema::hasColumn('monasteries', 'city_id')) {
                $table->foreignId('city_id')->nullable()->after('region_id')
                    ->constrained('cities')->nullOnDelete();
            }

            // NOVO: tekst sekcije
            if (!Schema::hasColumn('monasteries', 'excerpt')) {
                $table->text('excerpt')->nullable()->after('description');
            }

            if (!Schema::hasColumn('monasteries', 'architecture')) {
                $table->longText('architecture')->nullable()->after('history');
            }

            if (!Schema::hasColumn('monasteries', 'art')) {
                $table->longText('art')->nullable()->after('architecture');
            }

            if (!Schema::hasColumn('monasteries', 'spiritual_life')) {
                $table->longText('spiritual_life')->nullable()->after('art');
            }

            if (!Schema::hasColumn('monasteries', 'visiting')) {
                $table->longText('visiting')->nullable()->after('spiritual_life');
            }

            if (!Schema::hasColumn('monasteries', 'sources')) {
                $table->longText('sources')->nullable()->after('source');
            }
        });
    }

    public function down(): void
    {
        Schema::table('monasteries', function (Blueprint $table) {

            // drop FK constraints (SQLite nekad ignoriše, ali je ok)
            if (Schema::hasColumn('monasteries', 'city_id')) {
                // u SQLite dropForeign može da bude problem ako nema constraint name
                // zato samo dropColumn
                $table->dropColumn('city_id');
            }

            if (Schema::hasColumn('monasteries', 'region_id')) {
                $table->dropColumn('region_id');
            }

            foreach (['excerpt','architecture','art','spiritual_life','visiting','sources'] as $col) {
                if (Schema::hasColumn('monasteries', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
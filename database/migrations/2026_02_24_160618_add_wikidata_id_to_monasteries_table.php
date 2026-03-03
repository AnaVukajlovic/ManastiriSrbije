<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('monasteries', function (Blueprint $table) {
            if (!Schema::hasColumn('monasteries', 'wikidata_id')) {
                $table->string('wikidata_id', 32)->nullable()->index()->after('slug');
            }
        });
    }

    public function down(): void
    {
        Schema::table('monasteries', function (Blueprint $table) {
            if (Schema::hasColumn('monasteries', 'wikidata_id')) {
                $table->dropColumn('wikidata_id');
            }
        });
    }
};
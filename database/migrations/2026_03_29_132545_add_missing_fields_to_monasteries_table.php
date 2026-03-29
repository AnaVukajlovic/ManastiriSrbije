<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('monasteries', function (Blueprint $table) {
            if (!Schema::hasColumn('monasteries', 'description_short')) {
                $table->text('description_short')->nullable()->after('city');
            }

            if (!Schema::hasColumn('monasteries', 'architecture')) {
                $table->text('architecture')->nullable()->after('history');
            }

            if (!Schema::hasColumn('monasteries', 'art')) {
                $table->text('art')->nullable()->after('architecture');
            }

            if (!Schema::hasColumn('monasteries', 'spiritual_life')) {
                $table->text('spiritual_life')->nullable()->after('art');
            }

            if (!Schema::hasColumn('monasteries', 'visiting')) {
                $table->text('visiting')->nullable()->after('spiritual_life');
            }

            if (!Schema::hasColumn('monasteries', 'sources')) {
                $table->text('sources')->nullable()->after('visiting');
            }

            if (!Schema::hasColumn('monasteries', 'ktitor')) {
                $table->string('ktitor')->nullable()->after('source');
            }

            if (!Schema::hasColumn('monasteries', 'godina_izgradnje')) {
                $table->string('godina_izgradnje')->nullable()->after('ktitor');
            }

            if (!Schema::hasColumn('monasteries', 'napomena_podaci')) {
                $table->text('napomena_podaci')->nullable()->after('godina_izgradnje');
            }

            if (!Schema::hasColumn('monasteries', 'status')) {
                $table->string('status')->nullable()->after('napomena_podaci');
            }

            if (!Schema::hasColumn('monasteries', 'coord_source')) {
                $table->string('coord_source')->nullable()->after('status');
            }

            if (!Schema::hasColumn('monasteries', 'coord_url')) {
                $table->text('coord_url')->nullable()->after('coord_source');
            }

            if (!Schema::hasColumn('monasteries', 'coord_status')) {
                $table->string('coord_status')->nullable()->after('coord_url');
            }
        });
    }

    public function down(): void
    {
        Schema::table('monasteries', function (Blueprint $table) {
            $cols = [
                'description_short',
                'architecture',
                'art',
                'spiritual_life',
                'visiting',
                'sources',
                'ktitor',
                'godina_izgradnje',
                'napomena_podaci',
                'status',
                'coord_source',
                'coord_url',
                'coord_status',
            ];

            foreach ($cols as $col) {
                if (Schema::hasColumn('monasteries', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
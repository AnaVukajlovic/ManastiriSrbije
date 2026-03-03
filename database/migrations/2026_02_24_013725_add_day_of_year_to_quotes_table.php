<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('quotes', function (Blueprint $table) {
            if (!Schema::hasColumn('quotes', 'day_of_year')) {
                $table->unsignedSmallInteger('day_of_year')->nullable()->after('id'); // 1..366
                $table->unique('day_of_year');
            }
        });
    }

    public function down(): void
    {
        Schema::table('quotes', function (Blueprint $table) {
            if (Schema::hasColumn('quotes', 'day_of_year')) {
                $table->dropUnique(['day_of_year']);
                $table->dropColumn('day_of_year');
            }
        });
    }
};
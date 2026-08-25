<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ktitors', function (Blueprint $table) {
            if (!Schema::hasColumn('ktitors', 'saint_name')) {
                $table->string('saint_name')->nullable()->after('is_saint');
            }
            if (!Schema::hasColumn('ktitors', 'feast_day')) {
                $table->string('feast_day')->nullable()->after('saint_name');
            }
        });
    }

    public function down(): void
    {
        Schema::table('ktitors', function (Blueprint $table) {
            $table->dropColumn(['saint_name', 'feast_day']);
        });
    }
};

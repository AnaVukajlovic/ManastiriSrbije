<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('calendar_days', function (Blueprint $table) {
            if (!Schema::hasColumn('calendar_days', 'day_name')) {
                $table->string('day_name')->nullable()->after('day_of_year');
            }
        });
    }

    public function down(): void
    {
        Schema::table('calendar_days', function (Blueprint $table) {
            if (Schema::hasColumn('calendar_days', 'day_name')) {
                $table->dropColumn('day_name');
            }
        });
    }
};
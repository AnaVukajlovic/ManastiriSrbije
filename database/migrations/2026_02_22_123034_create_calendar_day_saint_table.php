<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('calendar_day_saint', function (Blueprint $table) {
            $table->id();
            $table->foreignId('calendar_day_id')->constrained('calendar_days')->cascadeOnDelete();
            $table->foreignId('saint_id')->constrained('saints')->cascadeOnDelete();
            $table->unsignedSmallInteger('priority')->default(1); // 1 = glavni, 2 = sporedni...
            $table->timestamps();

            $table->unique(['calendar_day_id', 'saint_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('calendar_day_saint');
    }
};
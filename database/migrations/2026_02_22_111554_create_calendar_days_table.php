<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('calendar_days', function (Blueprint $table) {
            $table->id();

            // Gregorian date (najbrže i najstabilnije)
            $table->date('date')->unique();

            $table->string('feast_name')->nullable();   // praznik
            $table->string('saint_name')->nullable();   // svetac (za početnu dovoljno kao string)
            $table->string('fasting_type')->nullable(); // "Nema posta", "Post na vodi", "Post na ulju", ...
            $table->text('note')->nullable();           // napomena (narodna verovanja / zabrane / običaji)
            $table->boolean('is_red_letter')->default(false); // crveno slovo

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('calendar_days');
    }
};
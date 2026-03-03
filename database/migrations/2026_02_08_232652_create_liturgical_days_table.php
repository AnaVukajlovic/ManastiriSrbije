<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('liturgical_days', function (Blueprint $table) {
            $table->id();
            $table->date('date')->unique();
            $table->string('feast')->nullable();     // praznik
            $table->string('saint')->nullable();     // svetac
            $table->string('fasting')->nullable();   // post: Nema posta / Voda / Ulje / Riba / Strogi
            $table->text('note')->nullable();        // napomena
            $table->string('source')->nullable();    // izvor
            $table->timestamps();
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('liturgical_days');
    }
};

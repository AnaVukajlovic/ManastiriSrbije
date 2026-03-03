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
        Schema::create('favorites', function (Blueprint $table) {
            $table->id();

            // korisnik koji je označio omiljeni manastir
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            // manastir koji je omiljen
            $table->foreignId('monastery_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->timestamps();

            // jedan user ne može isti manastir više puta
            $table->unique(['user_id', 'monastery_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('favorites');
    }
};
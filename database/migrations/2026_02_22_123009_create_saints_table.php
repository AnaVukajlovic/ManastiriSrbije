<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('saints', function (Blueprint $table) {
            $table->id();
            $table->string('name');                 // npr. "Sveti Simeon Mirotočivi"
            $table->string('short_name')->nullable(); // npr. "Simeon"
            $table->string('slug')->unique();       // za buduće stranice
            $table->text('bio')->nullable();         // kratak opis
            $table->string('icon')->nullable();      // emoji ili putanja do ikonice
            $table->string('wikipedia_url')->nullable();
            $table->string('source')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saints');
    }
};
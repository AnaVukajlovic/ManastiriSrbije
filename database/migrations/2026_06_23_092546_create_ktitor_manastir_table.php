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
Schema::create('ktitor_manastir', function (Blueprint $table) {
    $table->id();
    $table->foreignId('ktitor_id')->constrained()->onDelete('cascade');
    $table->foreignId('monastery_id')->constrained('monasteries')->onDelete('cascade');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ktitor_manastir');
    }
};

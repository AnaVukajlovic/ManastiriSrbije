<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ktitor_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ktitor_id')->constrained('ktitors')->cascadeOnDelete();
            $table->string('path');                 // npr: images/kritors/car - dusan.jpg
            $table->string('caption')->nullable();
            $table->string('source')->nullable();
            $table->string('credit')->nullable();
            $table->unsignedSmallInteger('sort')->default(1);
            $table->timestamps();

            $table->unique(['ktitor_id', 'path']);  // da ne pravi duplikate
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ktitor_images');
    }
};
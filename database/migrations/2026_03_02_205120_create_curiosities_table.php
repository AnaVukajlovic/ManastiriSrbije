<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('curiosities', function (Blueprint $table) {
            $table->id();

            // osnovno
            $table->string('title');
            $table->string('slug')->unique();

            // organizacija
            $table->string('category')->nullable(); 
            $table->unsignedSmallInteger('reading_minutes')->nullable();

            // sadržaj
            $table->string('image')->nullable(); // npr: images/curiosities/freske.jpg
            $table->text('excerpt')->nullable();
            $table->longText('content');

            // status
            $table->boolean('is_published')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('curiosities');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('virtual_tours', function (Blueprint $table) {
            $table->id();
            $table->foreignId('monastery_id')->constrained('monasteries')->cascadeOnDelete();
            $table->string('title');
            $table->string('provider')->default('google'); // google | custom
            $table->text('embed_url'); // iframe src
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['monastery_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('virtual_tours');
    }
};

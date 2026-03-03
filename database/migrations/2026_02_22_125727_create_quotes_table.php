<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('quotes', function (Blueprint $table) {
            $table->id();
            $table->text('text');
            $table->string('author')->nullable();
            $table->string('source')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('weight')->default(1);
            $table->timestamps();
                $table->unsignedSmallInteger('day_of_year')->nullable()->after('id'); // 1..366
    $table->unique('day_of_year');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quotes');
    }
};
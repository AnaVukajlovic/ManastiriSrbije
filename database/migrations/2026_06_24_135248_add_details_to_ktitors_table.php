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
    Schema::table('ktitors', function (Blueprint $table) {
        $table->string('title')->nullable();
        $table->string('dynasty')->nullable();
        $table->boolean('is_saint')->default(false);
        $table->string('burial_place')->nullable();
    });
}

    /**
     * Reverse the migrations.
     */
public function down(): void
{
    Schema::table('ktitors', function (Blueprint $table) {
        $table->dropColumn(['title', 'dynasty', 'is_saint', 'burial_place']);
    });
}
};

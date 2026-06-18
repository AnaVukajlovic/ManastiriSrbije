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
    Schema::table('monasteries', function (Blueprint $table) {
        //$table->string('ktitor')->nullable()->after('description');
        //$table->string('godina_izgradnje')->nullable()->after('ktitor');
    });
}

public function down(): void
{
    Schema::table('monasteries', function (Blueprint $table) {
        $table->dropColumn(['ktitor', 'godina_izgradnje']);
    });
}
};

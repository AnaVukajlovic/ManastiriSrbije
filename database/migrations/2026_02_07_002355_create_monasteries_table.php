<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('monasteries', function (Blueprint $table) {
            $table->id();

            // Osnovno
            $table->string('name');
            $table->string('slug')->unique();

            $table->string('region')->nullable()->index();
            $table->string('city')->nullable()->index();

            $table->text('description')->nullable();

            // Lokacija
            $table->decimal('latitude', 10, 7)->nullable();   // npr. 43.1234567
            $table->decimal('longitude', 10, 7)->nullable();  // npr. 20.1234567

            // Dodatni detalji (koristiš ih u bazi)
            $table->string('type')->nullable();
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->string('opening_hours')->nullable();

            // Izvori
            $table->string('wikidata_item')->nullable();
            $table->string('wikipedia_url')->nullable();
            $table->text('history')->nullable();

            // Slika (hero/thumbnail)
            $table->string('image_url')->nullable();

            $table->string('source')->nullable();

            // Wikidata/QID + SPC heuristike (iz liste kolona)
            $table->string('wikidata_qid')->nullable()->index();
            $table->string('religion_qid')->nullable();
            $table->string('denomination_qid')->nullable();

            $table->boolean('is_spc')->nullable()->default(null);
            $table->boolean('is_spc_guess')->nullable()->default(null);

            // Moderacija
            $table->string('review_status')->nullable()->default('pending')->index();
            $table->boolean('is_approved')->default(false)->index();

            // Eparhija
            $table->foreignId('eparchy_id')
                ->nullable()
                ->constrained('eparchies')
                ->nullOnDelete()
                ->index();

            $table->timestamps();

            // Korisni kompozitni indeks za listanje
            $table->index(['region', 'city']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('monasteries');
    }
};
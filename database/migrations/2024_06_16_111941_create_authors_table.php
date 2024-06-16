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
        Schema::create('authors', function (Blueprint $table) {
            $table->id();

            $table->string('openlibrary_author_id', 15)->unique()->nullable(false);
            $table->string('name', 250)->nullable(false);
            $table->text('bio');
            $table->date('birth_date')->nullable(true);
            $table->date('death_date')->nullable(true);

            $table->timestamps();
        });
        Schema::create('author_photos', function (Blueprint $table) {
            $table->foreignId('author_id')->constrained('authors');
            $table->string('photo_id', 15);
        });
        Schema::create('author_alternative_names', function (Blueprint $table) {
            $table->foreignId('author_id')->constrained('authors');
            $table->string('name', 250);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('authors');
        Schema::dropIfExists('author_photos');
        Schema::dropIfExists('author_alternative_names');
    }
};

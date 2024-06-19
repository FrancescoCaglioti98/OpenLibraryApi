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
            $table->foreignId('author_id')->constrained('authors')->onDelete("cascade");
            $table->string('photo_id', 15);
        });
        Schema::create('author_alternative_names', function (Blueprint $table) {
            $table->foreignId('author_id')->constrained('authors')->onDelete("cascade");
            $table->string('name', 250);
        });
        Schema::create('author_useful_links', function (Blueprint $table) {
            $table->foreignId('author_id')->constrained('authors')->onDelete("cascade");
            $table->string('title', 50)->nullable(false);
            $table->string('link', 100)->nullable(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('author_useful_links');
        Schema::dropIfExists('author_alternative_names');
        Schema::dropIfExists('author_photos');
        Schema::dropIfExists('authors');
    }
};

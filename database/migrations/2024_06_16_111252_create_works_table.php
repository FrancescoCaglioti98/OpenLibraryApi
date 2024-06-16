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
        Schema::create('works', function (Blueprint $table) {
            $table->id();

            $table->string("openlibrary_work_id",15)->unique()->nullable( false );
            $table->string("title", 250)->nullable( false );
            $table->text("description");
            $table->date( "first_publish" )->nullable( true );

            $table->timestamps();
        });
        Schema::create('work_links', function (Blueprint $table) {
            $table->foreignId( "work_id" )->constrained( "works" );
            $table->string( "title", 250 );
            $table->string( "link", 250 );
        });
        Schema::create('work_covers', function (Blueprint $table) {
            $table->foreignId( "work_id" )->constrained( "works" );
            $table->string( "cover", 25 );
        });
        Schema::create( "work_subject_peoples", function( Blueprint $table ) {
            $table->foreignId( "work_id" )->constrained( "works" );
            $table->string( "people", 50 );
        });
        Schema::create( "work_subjects", function( Blueprint $table ) {
            $table->foreignId( "work_id" )->constrained( "works" );
            $table->text( "subject" )->nullable( false );
        });
        Schema::create( "work_subject_times", function( Blueprint $table ) {
            $table->foreignId( "work_id" )->constrained( "works" );
            $table->string( "time", 100 )->nullable( false );
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_subject_times');
        Schema::dropIfExists('work_subjects');
        Schema::dropIfExists('work_subject_peoples');
        Schema::dropIfExists('work_covers');
        Schema::dropIfExists('work_links');
        Schema::dropIfExists('works');
    }

};

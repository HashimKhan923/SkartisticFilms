<?php
// database/migrations/2024_01_01_000002_create_movies_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('genre')->nullable();
            $table->year('year')->nullable();
            $table->text('description')->nullable();
            $table->string('poster')->nullable();         // uploaded image path
            $table->string('banner')->nullable();         // wide banner image
            $table->string('video_type')->default('youtube'); // 'youtube' or 'upload'
            $table->string('video_youtube')->nullable();   // YouTube URL
            $table->string('video_file')->nullable();      // uploaded video path
            $table->decimal('rating', 3, 1)->nullable();
            $table->integer('duration')->nullable();       // minutes
            $table->boolean('is_featured')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movies');
    }
};
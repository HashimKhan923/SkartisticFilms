<?php
// database/migrations/2024_01_01_000003_create_characters_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('characters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('movie_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('actor_name')->nullable();
            $table->string('role')->nullable();            // e.g. Lead, Villain, Supporting
            $table->text('bio')->nullable();
            $table->string('photo')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('characters');
    }
};
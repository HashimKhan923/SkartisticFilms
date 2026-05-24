<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('software', function (Blueprint $table) {
            $table->dropColumn('icon');
            $table->string('image')->nullable()->after('name');
            $table->unsignedSmallInteger('width')->default(48)->after('image');
        });
    }

    public function down(): void
    {
        Schema::table('software', function (Blueprint $table) {
            $table->dropColumn(['image', 'width']);
            $table->string('icon')->nullable()->after('name');
        });
    }
};

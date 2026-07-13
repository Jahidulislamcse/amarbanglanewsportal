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
        Schema::table('top_reporters', function (Blueprint $table) {
            $table->dropUnique(['year', 'month', 'position']);
            $table->integer('year')->nullable()->change();
            $table->integer('month')->nullable()->change();
            $table->string('week')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('top_reporters', function (Blueprint $table) {
            $table->dropColumn('week');
            $table->integer('year')->nullable(false)->change();
            $table->integer('month')->nullable(false)->change();
            $table->unique(['year', 'month', 'position']);
        });
    }
};

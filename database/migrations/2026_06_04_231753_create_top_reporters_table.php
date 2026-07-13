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

        Schema::create('top_reporters', function (Blueprint $table) {
            $table->id();
        
            $table->unsignedBigInteger('user_id');
            $table->integer('position');
            $table->bigInteger('total_views')->default(0);
        
            $table->integer('year');
            $table->integer('month');
        
            $table->timestamps();
        
            $table->index('user_id');
            $table->unique(['year', 'month', 'position']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('top_reporters');
    }
};

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
        Schema::create('worldcup_matches', function (Blueprint $table) {
            $table->id();
        
            $table->string('group_name');
        
            $table->string('home_team');
            $table->string('away_team');
        
            $table->integer('home_score')->default(0);
            $table->integer('away_score')->default(0);
        
            $table->dateTime('match_date')->nullable();
        
            $table->enum('status', [
                'scheduled',
                'finished'
            ])->default('scheduled');
        
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('world_cup_matches');
    }
};

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
        Schema::create('worldcup_standings', function (Blueprint $table) {
            $table->id();
        
            $table->string('group_name');
            $table->integer('rank');
        
            $table->integer('team_id')->unique();
            $table->string('team_name');
            $table->string('team_logo');
        
            $table->integer('played');
            $table->integer('win');
            $table->integer('draw');
            $table->integer('lose');
        
            $table->integer('goals_for');
            $table->integer('goals_against');
        
            $table->integer('goal_diff');
            $table->integer('points');
        
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('worldcup_standings');
    }
};

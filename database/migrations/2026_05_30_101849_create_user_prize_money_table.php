<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_prize_money', function (Blueprint $table) {
            $table->id();
        
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('post_quiz_winner_id')->nullable();
        
            $table->decimal('amount', 10, 2);
        
            $table->enum('type', [
                'quiz_prize',
                'prize_reversal',
            ])->default('quiz_prize');
        
            $table->string('remarks')->nullable();
        
            $table->timestamps();
        
            $table->index('user_id');
            $table->index('post_quiz_winner_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_prize_money');
    }
};
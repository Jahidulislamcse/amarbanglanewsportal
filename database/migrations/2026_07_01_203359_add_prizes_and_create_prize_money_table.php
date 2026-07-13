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
        // 1. Add columns to fees table
        Schema::table('fees', function (Blueprint $table) {
            $table->decimal('reporter_1st_prize', 10, 2)->default(0.00);
            $table->decimal('reporter_2nd_prize', 10, 2)->default(0.00);
            $table->decimal('reporter_3rd_prize', 10, 2)->default(0.00);
            $table->decimal('quiz_1st_prize', 10, 2)->default(0.00);
            $table->decimal('quiz_2nd_prize', 10, 2)->default(0.00);
            $table->decimal('quiz_3rd_prize', 10, 2)->default(0.00);
        });

        // 2. Create reporter_prize_money table
        Schema::create('reporter_prize_money', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->integer('position');
            $table->decimal('amount', 10, 2);
            $table->string('week');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reporter_prize_money');

        Schema::table('fees', function (Blueprint $table) {
            $table->dropColumn([
                'reporter_1st_prize',
                'reporter_2nd_prize',
                'reporter_3rd_prize',
                'quiz_1st_prize',
                'quiz_2nd_prize',
                'quiz_3rd_prize'
            ]);
        });
    }
};

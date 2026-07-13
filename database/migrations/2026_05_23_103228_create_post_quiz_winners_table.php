<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostQuizWinnersTable extends Migration
{
    public function up()
    {
        Schema::create('post_quiz_winners', function (Blueprint $table) {
            $table->id();

            $table->integer('post_quiz_id')->nullable();

            $table->integer('answer_id')->nullable();

            $table->tinyInteger('position');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('post_quiz_winners');
    }
}
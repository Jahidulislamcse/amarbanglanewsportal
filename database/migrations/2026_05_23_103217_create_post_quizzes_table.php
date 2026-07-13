<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostQuizzesTable extends Migration
{
    public function up()
    {
        Schema::create('post_quizzes', function (Blueprint $table) {

            $table->id();

            $table->integer('post_id')->nullable();

            $table->text('question');

            $table->string('option_1');
            $table->string('option_2');
            $table->string('option_3');
            $table->string('option_4');

            $table->tinyInteger('correct_answer');

            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('post_quizzes');
    }
}
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostQuizAnswersTable extends Migration
{
    public function up()
    {
        Schema::create('post_quiz_answers', function (Blueprint $table) {
            $table->id();

            $table->integer('post_quiz_id')->nullable();
            $table->integer('post_id')->nullable();

            $table->integer('user_id')->nullable();

            $table->string('name')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();

            // selected option: 1,2,3,4
            $table->tinyInteger('selected_answer');

            $table->boolean('is_correct')->default(false);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('post_quiz_answers');
    }
}
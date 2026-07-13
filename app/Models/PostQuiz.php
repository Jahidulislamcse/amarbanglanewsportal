<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostQuiz extends Model
{
    protected $fillable = [
        'post_id',
        'question',
        'option_1',
        'option_2',
        'option_3',
        'option_4',
        'correct_answer',
        'is_active',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id');
    }
    
    public function answers()
    {
        return $this->hasMany(PostQuizAnswer::class, 'post_quiz_id');
    }
    
    public function winners()
    {
        return $this->hasMany(PostQuizWinner::class, 'post_quiz_id');
    }
}
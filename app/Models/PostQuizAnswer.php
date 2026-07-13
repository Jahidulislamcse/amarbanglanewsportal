<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostQuizAnswer extends Model
{
    protected $fillable = [
        'post_quiz_id',
        'post_id',
        'user_id',
        'name',
        'phone',
        'email',
        'selected_answer',
        'is_correct',
    ];

    public function quiz()
    {
        return $this->belongsTo(PostQuiz::class, 'post_quiz_id');
    }
    
    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
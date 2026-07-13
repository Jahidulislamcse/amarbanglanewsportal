<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostQuizWinner extends Model
{
    protected $fillable = [
        'post_quiz_id',
        'answer_id',
        'position',
        'draw_type',
        'period_start',
        'period_end',
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
    ];

    public function quiz()
    {
        return $this->belongsTo(PostQuiz::class, 'post_quiz_id');
    }
    
    public function answer()
    {
        return $this->belongsTo(PostQuizAnswer::class, 'answer_id');
    }
}

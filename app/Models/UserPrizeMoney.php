<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPrizeMoney extends Model
{
    protected $table = 'user_prize_money';

    protected $fillable = [
        'user_id',
        'post_quiz_winner_id',
        'amount',
        'type',
        'remarks',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function winner()
    {
        return $this->belongsTo(PostQuizWinner::class, 'post_quiz_winner_id');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReporterPrizeMoney extends Model
{
    protected $table = 'reporter_prize_money';

    protected $fillable = [
        'user_id',
        'position',
        'amount',
        'week'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

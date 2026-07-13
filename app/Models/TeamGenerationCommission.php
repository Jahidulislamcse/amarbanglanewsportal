<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeamGenerationCommission extends Model
{
    protected $fillable = [
        'receiver_user_id',
        'source_user_id',
        'upgrade_request_id',
        'generation',
        'package',
        'upgrade_amount',
        'rate',
        'commission',
    ];

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_user_id');
    }

    public function source()
    {
        return $this->belongsTo(User::class, 'source_user_id');
    }
}
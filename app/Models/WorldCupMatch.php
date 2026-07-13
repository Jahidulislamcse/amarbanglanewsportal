<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorldCupMatch extends Model
{
    protected $table = 'worldcup_matches';

    protected $fillable = [
        'group_name',
        'home_team',
        'away_team',
        'home_score',
        'away_score',
        'match_date',
        'status',
    ];
}
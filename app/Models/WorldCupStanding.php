<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorldCupStanding extends Model
{
    protected $table = 'worldcup_standings';

    protected $fillable = [
        'group_name',
        'rank',
        'team_id',
        'team_name',
        'team_logo',
        'played',
        'win',
        'draw',
        'lose',
        'goals_for',
        'goals_against',
        'goal_diff',
        'points',
    ];
}

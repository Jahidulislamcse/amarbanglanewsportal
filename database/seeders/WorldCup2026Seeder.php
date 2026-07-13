<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WorldCupStanding;

class WorldCup2026Seeder extends Seeder
{
    public function run(): void
    {
        WorldCupStanding::truncate();

        $teams = [

            // Group A
            ['group'=>'A','rank'=>1,'team'=>'Mexico','played'=>1,'win'=>1,'draw'=>0,'lose'=>0,'gf'=>2,'ga'=>0,'gd'=>2,'points'=>3],
            ['group'=>'A','rank'=>2,'team'=>'South Korea','played'=>1,'win'=>1,'draw'=>0,'lose'=>0,'gf'=>1,'ga'=>0,'gd'=>1,'points'=>3],
            ['group'=>'A','rank'=>3,'team'=>'Czech Republic','played'=>1,'win'=>0,'draw'=>0,'lose'=>1,'gf'=>0,'ga'=>1,'gd'=>-1,'points'=>0],
            ['group'=>'A','rank'=>4,'team'=>'South Africa','played'=>1,'win'=>0,'draw'=>0,'lose'=>1,'gf'=>0,'ga'=>2,'gd'=>-2,'points'=>0],

            // Group B (all zero)
            ['group'=>'B','rank'=>1,'team'=>'Bosnia','played'=>0,'win'=>0,'draw'=>0,'lose'=>0,'gf'=>0,'ga'=>0,'gd'=>0,'points'=>0],
            ['group'=>'B','rank'=>2,'team'=>'Canada','played'=>0,'win'=>0,'draw'=>0,'lose'=>0,'gf'=>0,'ga'=>0,'gd'=>0,'points'=>0],
            ['group'=>'B','rank'=>3,'team'=>'Qatar','played'=>0,'win'=>0,'draw'=>0,'lose'=>0,'gf'=>0,'ga'=>0,'gd'=>0,'points'=>0],
            ['group'=>'B','rank'=>4,'team'=>'Switzerland','played'=>0,'win'=>0,'draw'=>0,'lose'=>0,'gf'=>0,'ga'=>0,'gd'=>0,'points'=>0],

            // Group C
            ['group'=>'C','rank'=>1,'team'=>'Brazil','played'=>0,'win'=>0,'draw'=>0,'lose'=>0,'gf'=>0,'ga'=>0,'gd'=>0,'points'=>0],
            ['group'=>'C','rank'=>2,'team'=>'Haiti','played'=>0,'win'=>0,'draw'=>0,'lose'=>0,'gf'=>0,'ga'=>0,'gd'=>0,'points'=>0],
            ['group'=>'C','rank'=>3,'team'=>'Morocco','played'=>0,'win'=>0,'draw'=>0,'lose'=>0,'gf'=>0,'ga'=>0,'gd'=>0,'points'=>0],
            ['group'=>'C','rank'=>4,'team'=>'Scotland','played'=>0,'win'=>0,'draw'=>0,'lose'=>0,'gf'=>0,'ga'=>0,'gd'=>0,'points'=>0],

            // Group D
            ['group'=>'D','rank'=>1,'team'=>'Australia','played'=>0,'win'=>0,'draw'=>0,'lose'=>0,'gf'=>0,'ga'=>0,'gd'=>0,'points'=>0],
            ['group'=>'D','rank'=>2,'team'=>'Paraguay','played'=>0,'win'=>0,'draw'=>0,'lose'=>0,'gf'=>0,'ga'=>0,'gd'=>0,'points'=>0],
            ['group'=>'D','rank'=>3,'team'=>'Turkey','played'=>0,'win'=>0,'draw'=>0,'lose'=>0,'gf'=>0,'ga'=>0,'gd'=>0,'points'=>0],
            ['group'=>'D','rank'=>4,'team'=>'USA','played'=>0,'win'=>0,'draw'=>0,'lose'=>0,'gf'=>0,'ga'=>0,'gd'=>0,'points'=>0],

        ];

        foreach ($teams as $t) {
            WorldCupStanding::create([
                'group_name' => 'Group '.$t['group'],
                'rank' => $t['rank'],
                'team_name' => $t['team'],
                'team_logo' => '',
                'played' => $t['played'],
                'win' => $t['win'],
                'draw' => $t['draw'],
                'lose' => $t['lose'],
                'goals_for' => $t['gf'],
                'goals_against' => $t['ga'],
                'goal_diff' => $t['gd'],
                'points' => $t['points'],
            ]);
        }
    }
}
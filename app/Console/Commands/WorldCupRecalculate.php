<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\WorldCupMatch;
use App\Models\WorldCupStanding;

class WorldCupRecalculate extends Command
{
    protected $signature = 'worldcup:recalculate';

    protected $description = 'Recalculate World Cup standings';

    public function handle()
    {
        WorldCupStanding::query()->update([
            'played' => 0,
            'win' => 0,
            'draw' => 0,
            'lose' => 0,
            'goals_for' => 0,
            'goals_against' => 0,
            'goal_diff' => 0,
            'points' => 0,
        ]);

        $matches = WorldCupMatch::where('status','finished')->get();

        foreach ($matches as $match) {

            $home = WorldCupStanding::where(
                'team_name',
                $match->home_team
            )->first();

            $away = WorldCupStanding::where(
                'team_name',
                $match->away_team
            )->first();

            if (!$home || !$away) {
                continue;
            }

            $home->played++;
            $away->played++;

            $home->goals_for += $match->home_score;
            $home->goals_against += $match->away_score;

            $away->goals_for += $match->away_score;
            $away->goals_against += $match->home_score;

            if ($match->home_score > $match->away_score) {

                $home->win++;
                $home->points += 3;

                $away->lose++;

            } elseif ($match->home_score < $match->away_score) {

                $away->win++;
                $away->points += 3;

                $home->lose++;

            } else {

                $home->draw++;
                $away->draw++;

                $home->points++;
                $away->points++;
            }

            $home->goal_diff =
                $home->goals_for - $home->goals_against;

            $away->goal_diff =
                $away->goals_for - $away->goals_against;

            $home->save();
            $away->save();
        }

        $groups = WorldCupStanding::select('group_name')
            ->distinct()
            ->pluck('group_name');

        foreach ($groups as $group) {

            $rank = 1;

            $teams = WorldCupStanding::where(
                'group_name',
                $group
            )
            ->orderByDesc('points')
            ->orderByDesc('goal_diff')
            ->orderByDesc('goals_for')
            ->get();

            foreach ($teams as $team) {

                $team->rank = $rank++;
                $team->save();
            }
        }

        $this->info('Standings updated');
    }
}
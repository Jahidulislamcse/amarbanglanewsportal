<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\WorldCupStanding;

class SyncWorldCupStandings extends Command
{
    protected $signature = 'worldcup:sync';

    protected $description = 'Sync World Cup standings from API';

    public function handle()
    {
        $response = Http::timeout(10)->withHeaders([
            'x-apisports-key' => env('API_FOOTBALL_KEY'),
        ])->get('https://v3.football.api-sports.io/standings', [
            'league' => 1,
            'season' => 2026,
        ]);

        if (!$response->ok()) {
            $this->error('API request failed');
            return;
        }

        $data = $response->json();
        dd($data);

        $groups = data_get($data, 'response.0.league.standings');

        if (!$groups) {
            $this->error('No standings data found');
            return;
        }

        foreach ($groups as $groupTeams) {
            foreach ($groupTeams as $team) {

                WorldCupStanding::updateOrCreate(
                    [
                        'team_id' => $team['team']['id'],
                    ],
                    [
                        'group_name' => $team['group'] ?? null,
                        'rank' => $team['rank'] ?? 0,
                        'team_name' => $team['team']['name'] ?? '',
                        'team_logo' => $team['team']['logo'] ?? '',

                        'played' => $team['all']['played'] ?? 0,
                        'win' => $team['all']['win'] ?? 0,
                        'draw' => $team['all']['draw'] ?? 0,
                        'lose' => $team['all']['lose'] ?? 0,

                        'goals_for' => $team['all']['goals']['for'] ?? 0,
                        'goals_against' => $team['all']['goals']['against'] ?? 0,

                        'goal_diff' => $team['goalsDiff'] ?? 0,
                        'points' => $team['points'] ?? 0,
                    ]
                );
            }
        }

        $this->info('World Cup standings synced successfully');
    }
}
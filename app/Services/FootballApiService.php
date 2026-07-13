<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class FootballApiService
{
    protected string $baseUrl = 'https://v3.football.api-sports.io';

    protected function headers(): array
    {
        return [
            'x-apisports-key' => env('API_FOOTBALL_KEY'),
        ];
    }

    public function worldCupStandings(int $season = 2026): array
    {
        return Http::withHeaders($this->headers())
            ->get($this->baseUrl . '/standings', [
                'league' => 1,
                'season' => $season,
            ])
            ->json();
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WorldCupStanding;
use App\Models\WorldCupMatch;
use App\Services\FootballApiService;

class WorldCupController extends Controller
{
    public function pointsTable()
    {
        $groups = WorldCupStanding::orderBy('group_name')
            ->orderBy('rank')
            ->get()
            ->groupBy('group_name');
            
        $matches = WorldCupMatch::orderBy('match_date')
        ->orderBy('match_date')
        ->get();
    
        return view('frontend.worldcup.points', compact('groups', 'matches'));
    }
}
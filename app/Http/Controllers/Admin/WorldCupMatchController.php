<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WorldCupMatch;

class WorldCupMatchController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'group_name' => 'required',
            'home_team' => 'required',
            'away_team' => 'required',
            'match_date' => 'required',
            'status' => 'required',
        ]);

        WorldCupMatch::create([
            'group_name' => $request->group_name,
            'home_team' => $request->home_team,
            'away_team' => $request->away_team,
            'home_score' => $request->home_score ?? 0,
            'away_score' => $request->away_score ?? 0,
            'match_date' => $request->match_date,
            'status' => $request->status,
        ]);

        return back()->with('success', 'Match added successfully');
    }

    public function update(Request $request, $id)
    {
        $match = WorldCupMatch::findOrFail($id);

        $match->update([
            'group_name' => $request->group_name,
            'home_team' => $request->home_team,
            'away_team' => $request->away_team,
            'home_score' => $request->home_score ?? 0,
            'away_score' => $request->away_score ?? 0,
            'match_date' => $request->match_date,
            'status' => $request->status,
        ]);

        return back()->with('success', 'Match updated successfully');
    }

    public function destroy($id)
    {
        WorldCupMatch::findOrFail($id)->delete();

        return back()->with('success', 'Match deleted successfully');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WorldCupStanding;
use App\Models\WorldCupMatch;

class WorldCupStandingController extends Controller
{
    public function index()
    {
        $standings = WorldCupStanding::orderBy('group_name')
            ->orderBy('rank')
            ->get();
        $matches = WorldCupMatch::orderBy('group_name')
            ->orderBy('match_date')
            ->get();

        return view('admin.worldcup.matches', compact('standings','matches'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'group_name' => 'required',
            'team_name' => 'required',
        ]);

        WorldCupStanding::create([
            'group_name' => $request->group_name,
            'team_name' => $request->team_name,
            'rank' => $request->rank ?? 0,
            'played' => $request->played ?? 0,
            'win' => $request->win ?? 0,
            'draw' => $request->draw ?? 0,
            'lose' => $request->lose ?? 0,
            'goals_for' => $request->goals_for ?? 0,
            'goals_against' => $request->goals_against ?? 0,
            'goal_diff' => $request->goal_diff ?? 0,
            'points' => $request->points ?? 0,
        ]);

        return back()->with('success', 'Standing added successfully');
    }

    public function update(Request $request, $id)
    {
        $standing = WorldCupStanding::findOrFail($id);

        $standing->update([
            'group_name' => $request->group_name,
            'team_name' => $request->team_name,
            'rank' => $request->rank ?? 0,
            'played' => $request->played ?? 0,
            'win' => $request->win ?? 0,
            'draw' => $request->draw ?? 0,
            'lose' => $request->lose ?? 0,
            'goals_for' => $request->goals_for ?? 0,
            'goals_against' => $request->goals_against ?? 0,
            'goal_diff' => $request->goal_diff ?? 0,
            'points' => $request->points ?? 0,
        ]);

        return back()->with('success', 'Standing updated successfully');
    }

    public function destroy($id)
    {
        WorldCupStanding::findOrFail($id)->delete();

        return back()->with('success', 'Standing deleted successfully');
    }
}
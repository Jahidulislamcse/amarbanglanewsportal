<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PollAnswer;
use App\Models\PollQuestion;
use App\Models\PollResult;
use DB;

class PollVoteController extends Controller
{
    public function pollvote(Request $request)
    {
        $request->validate([
            'poll_question_id' => 'required|integer',
            'poll_answer_id' => 'required|integer'
        ]);
    
        $ip = $request->ip();
    
        $exists = PollResult::where('poll_question_id', $request->poll_question_id)
            ->where('ip_address', $ip)
            ->exists();
    
        if ($exists) {
            return response()->json(['message' => 'already voted'], 403);
        }
    
        PollResult::create([
            'poll_question_id' => $request->poll_question_id,
            'poll_answer_id' => $request->poll_answer_id,
            'ip_address' => $ip
        ]);
    
        return $this->getStats($request->poll_question_id);
    }

    /**
     * Get stats
     */
    public function getStats($id)
    {
        $question = PollQuestion::with('answers')->findOrFail($id);
    
        $totalVotes = PollResult::where('poll_question_id', $id)->count();
    
        $stats = [];
    
        foreach ($question->answers as $answer) {
    
            $count = PollResult::where('poll_question_id', $id)
                ->where('poll_answer_id', $answer->id)
                ->count();
    
            $percent = $totalVotes > 0
                ? round(($count / $totalVotes) * 100, 1)
                : 0;
    
            $stats[] = [
                'answer_id' => $answer->id,
                'count' => $count,
                'percent' => $percent
            ];
        }
    
        return response()->json([
            'total' => $totalVotes,
            'stats' => $stats
        ]);
    }

}

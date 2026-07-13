<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PostQuiz;
use App\Models\PostQuizAnswer;

class PostQuizController extends Controller
{
    public function submit(Request $request)
    {
         $request->validate([
            'quiz_id' => 'required|exists:post_quizzes,id',
            'selected_answer' => 'required|integer|between:1,4',
        ]);

        if (!auth()->check()) {
        
            return response()->json([
                'status' => false,
                'message' => 'Please login first.'
            ], 401);
        }

        $quiz = PostQuiz::findOrFail($request->quiz_id);

        $userId = auth('web')->id();

        $exists = PostQuizAnswer::where('post_quiz_id', $quiz->id)
            ->where('user_id', $userId)
            ->exists();

        if ($exists) {

            return response()->json([
                'status' => false,
                'message' => 'Already answered.'
            ]);
        }

        $isCorrect = ((int)$quiz->correct_answer === (int)$request->selected_answer);

        PostQuizAnswer::create([
            'post_quiz_id' => $quiz->id,
            'post_id' => $quiz->post_id,
            'user_id' => $userId,
            'name' => auth('web')->user()->name ?? null,
            'phone' => auth('web')->user()->phone ?? null,
            'email' => auth('web')->user()->email ?? null,
            'selected_answer' => $request->selected_answer,
            'is_correct' => $isCorrect,
        ]);
        
        if ($isCorrect) {
            $user = auth('web')->user();
        
            $user->increment('balance', 1);
            $user->increment('daily_quiz_money', 1);
        }

        return response()->json([
            'status' => true,
            'correct' => $isCorrect,
            'message' => $isCorrect
                ? '✅ সঠিক উত্তর! ১ টাকা ব্যালেন্স যোগ হয়েছে।'
                : '❌ ভুল উত্তর।'
        ]);
    }
}
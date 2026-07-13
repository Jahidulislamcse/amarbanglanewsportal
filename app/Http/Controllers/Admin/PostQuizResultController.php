<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PostQuiz;
use App\Models\PostQuizAnswer;
use App\Models\PostQuizWinner;
use App\Models\User;
use App\Models\UserPrizeMoney;
use App\Models\Fee;
use Illuminate\Support\Facades\DB;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PostQuizResultController extends Controller
{
    private const WEEKLY_DRAW_TYPE = 'weekly';

    private array $quizPrizeAmounts = [];

    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    private function quizPrizeAmounts(): array
    {
        if ($this->quizPrizeAmounts !== []) {
            return $this->quizPrizeAmounts;
        }

        $fees = Fee::first();

        $this->quizPrizeAmounts = [
            1 => (float)($fees->quiz_1st_prize ?? 0),
            2 => (float)($fees->quiz_2nd_prize ?? 0),
            3 => (float)($fees->quiz_3rd_prize ?? 0),
        ];

        return $this->quizPrizeAmounts;
    }

    private function prizeAmount(int $position): float
    {
        return $this->quizPrizeAmounts()[$position] ?? 0;
    }

    private function currentWeeklyPeriod(string $timezone = 'Asia/Dhaka'): array
    {
        $end = now($timezone)->endOfDay();
        $start = now($timezone)->subDays(6)->startOfDay();

        return [$start, $end];
    }

    private function weeklyParticipantRanking(Carbon $start, Carbon $end, bool $onlyEligible = false)
    {
        $query = PostQuizAnswer::select(
                'user_id',
                DB::raw('MIN(id) as answer_id'),
                DB::raw('MAX(name) as name'),
                DB::raw('MAX(phone) as phone'),
                DB::raw('MAX(email) as email'),
                DB::raw('COUNT(*) as participation_count'),
                DB::raw('SUM(CASE WHEN is_correct = 1 THEN 1 ELSE 0 END) as correct_count'),
                DB::raw('SUM(CASE WHEN is_correct = 0 THEN 1 ELSE 0 END) as wrong_count'),
                DB::raw('MIN(created_at) as first_participated_at'),
                DB::raw('MAX(created_at) as last_participated_at')
            )
            ->whereBetween('created_at', [$start, $end])
            ->whereNotNull('user_id')
            ->groupBy('user_id');

        if ($onlyEligible) {
            $query->havingRaw('SUM(CASE WHEN is_correct = 1 THEN 1 ELSE 0 END) > 0');
        }

        return $query
            ->orderByDesc('participation_count')
            ->orderByDesc('correct_count')
            ->orderBy('first_participated_at')
            ->get();
    }

    private function representativeWeeklyAnswer(int $userId, Carbon $start, Carbon $end): ?PostQuizAnswer
    {
        $baseQuery = PostQuizAnswer::where('user_id', $userId)
            ->whereBetween('created_at', [$start, $end]);

        $correctAnswer = (clone $baseQuery)
            ->where('is_correct', 1)
            ->orderBy('created_at')
            ->first();

        return $correctAnswer ?: $baseQuery->orderBy('created_at')->first();
    }

    private function weeklyWinners(Carbon $start, Carbon $end)
    {
        return PostQuizWinner::with(['answer.user'])
            ->where('draw_type', self::WEEKLY_DRAW_TYPE)
            ->whereDate('period_start', $start->toDateString())
            ->whereDate('period_end', $end->toDateString())
            ->orderBy('position')
            ->get();
    }

    private function creditWinnerPrize(PostQuizAnswer $answer, PostQuizWinner $winner, float $amount, string $remarks): void
    {
        if ($amount <= 0 || !$answer->user_id) {
            return;
        }

        User::where('id', $answer->user_id)->increment('balance', $amount);

        UserPrizeMoney::create([
            'user_id' => $answer->user_id,
            'post_quiz_winner_id' => $winner->id,
            'amount' => $amount,
            'type' => 'quiz_prize',
            'remarks' => $remarks,
        ]);
    }

    private function reverseWinnerPrize(PostQuizWinner $winner, string $remarks): void
    {
        $amount = $this->prizeAmount((int)$winner->position);

        if (
            $amount <= 0 ||
            !$winner->answer ||
            !$winner->answer->user_id
        ) {
            return;
        }

        User::where('id', $winner->answer->user_id)
            ->decrement('balance', $amount);

        UserPrizeMoney::create([
            'user_id' => $winner->answer->user_id,
            'post_quiz_winner_id' => $winner->id,
            'amount' => -$amount,
            'type' => 'prize_reversal',
            'remarks' => $remarks,
        ]);
    }
    
    public function index()
    {
        $timezone = 'Asia/Dhaka';
        [$weeklyStart, $weeklyEnd] = $this->currentWeeklyPeriod($timezone);
        $weeklyParticipants = $this->weeklyParticipantRanking($weeklyStart, $weeklyEnd);
        $weeklyWinners = $this->weeklyWinners($weeklyStart, $weeklyEnd);
        $quizPrizeAmounts = $this->quizPrizeAmounts();
    
        $quizzes = PostQuiz::with('post')
            ->withCount([
                'answers as total_participants',
                'answers as right_count' => function ($query) {
                    $query->where('is_correct', 1);
                },
                'answers as wrong_count' => function ($query) {
                    $query->where('is_correct', 0);
                },
                'winners as winners_count',
            ])
            ->join('posts', 'post_quizzes.post_id', '=', 'posts.id')
            ->select('post_quizzes.*')
            ->orderByRaw('COALESCE(posts.schedule_post_date, posts.created_at) DESC')
            ->paginate(20);
    
        $quizDates = $quizzes->getCollection()
            ->map(function ($quiz) use ($timezone) {
                $date = $quiz->post->schedule_post_date ?? $quiz->post->created_at ?? $quiz->created_at;
                return \Carbon\Carbon::parse($date, $timezone)->toDateString();
            })
            ->unique()
            ->values();
    
        $dayCandidates = [];
        $dayWinners = [];
    
        foreach ($quizDates as $date) {
            $quizIds = PostQuiz::join('posts', 'post_quizzes.post_id', '=', 'posts.id')
                ->whereDate(DB::raw('COALESCE(posts.schedule_post_date, posts.created_at)'), $date)
                ->pluck('post_quizzes.id');
    
            $dayCandidates[$date] = PostQuizAnswer::select(
                    'user_id',
                    DB::raw('MIN(id) as answer_id'),
                    DB::raw('MAX(name) as name'),
                    DB::raw('MAX(phone) as phone'),
                    DB::raw('MAX(email) as email'),
                    DB::raw('COUNT(*) as correct_count'),
                    DB::raw('MIN(created_at) as first_correct_at')
                )
                ->whereIn('post_quiz_id', $quizIds)
                ->where('is_correct', 1)
                ->whereNotNull('user_id')
                ->groupBy('user_id')
                ->orderByDesc('correct_count')
                ->orderBy('first_correct_at')
                ->get();
    
            $dayWinners[$date] = PostQuizWinner::with(['answer.user'])
                ->whereIn('post_quiz_id', $quizIds)
                ->where(function ($query) {
                    $query->whereNull('draw_type')
                        ->orWhere('draw_type', '!=', self::WEEKLY_DRAW_TYPE);
                })
                ->orderBy('position')
                ->get();
        }
    
        return view('admin.post_quiz.index', compact(
            'quizzes',
            'dayCandidates',
            'dayWinners',
            'weeklyStart',
            'weeklyEnd',
            'weeklyParticipants',
            'weeklyWinners',
            'quizPrizeAmounts'
        ));
    }

    public function show($quizId)
    {
        $timezone = 'Asia/Dhaka';
        [$weeklyStart, $weeklyEnd] = $this->currentWeeklyPeriod($timezone);
        $weeklyParticipants = $this->weeklyParticipantRanking($weeklyStart, $weeklyEnd);
        $weeklyWinners = $this->weeklyWinners($weeklyStart, $weeklyEnd);
        $quizPrizeAmounts = $this->quizPrizeAmounts();

        $quiz = PostQuiz::with(['post', 'winners.answer'])->findOrFail($quizId);

        $answers = PostQuizAnswer::where('post_quiz_id', $quiz->id)
            ->with('user')
            ->latest()
            ->paginate(50);

        $totalParticipants = PostQuizAnswer::where('post_quiz_id', $quiz->id)->count();
        $rightCount = PostQuizAnswer::where('post_quiz_id', $quiz->id)->where('is_correct', 1)->count();
        $wrongCount = PostQuizAnswer::where('post_quiz_id', $quiz->id)->where('is_correct', 0)->count();

        $winners = PostQuizWinner::where('post_quiz_id', $quiz->id)
            ->with('answer')
            ->where(function ($query) {
                $query->whereNull('draw_type')
                    ->orWhere('draw_type', '!=', self::WEEKLY_DRAW_TYPE);
            })
            ->orderBy('position')
            ->get();

        return view('admin.post_quiz.result', compact(
            'quiz',
            'answers',
            'totalParticipants',
            'rightCount',
            'wrongCount',
            'winners',
            'weeklyStart',
            'weeklyEnd',
            'weeklyParticipants',
            'weeklyWinners',
            'quizPrizeAmounts'
        ));
    }

    public function drawWinners($quizId)
    {
        $quiz = PostQuiz::findOrFail($quizId);
    
        $existingWinnerCount = PostQuizWinner::where('post_quiz_id', $quiz->id)
            ->where(function ($query) {
                $query->whereNull('draw_type')
                    ->orWhere('draw_type', '!=', self::WEEKLY_DRAW_TYPE);
            })
            ->count();
    
        if ($existingWinnerCount > 0) {
            Toastr::warning('Winners already selected for this quiz.');
            return back();
        }
    
        $correctParticipantsCount = PostQuizAnswer::where('post_quiz_id', $quiz->id)
            ->where('is_correct', 1)
            ->count();
    
        if ($correctParticipantsCount == 0) {
            Toastr::error('No correct participants found. Winners cannot be selected.');
            return back();
        }
    
        if ($correctParticipantsCount < 3) {
            Toastr::error('At least 3 correct participants are required to select winners.');
            return back();
        }
    
        DB::transaction(function () use ($quiz) {
            $winnerAnswers = PostQuizAnswer::where('post_quiz_id', $quiz->id)
                ->where('is_correct', 1)
                ->inRandomOrder()
                ->limit(3)
                ->get();
    
            foreach ($winnerAnswers as $index => $answer) {
                PostQuizWinner::create([
                    'post_quiz_id' => $quiz->id,
                    'answer_id' => $answer->id,
                    'position' => $index + 1,
                    'draw_type' => 'quiz',
                ]);
            }
        });
    
        Toastr::success('Winners selected successfully.');
        return back();
    }
    
    public function drawDayWinnersByDate($date)
    {
        $timezone = 'Asia/Dhaka';
    
        $drawDate = \Carbon\Carbon::parse($date, $timezone)->toDateString();
    
        if ($drawDate >= now($timezone)->toDateString()) {
            Toastr::error('Only previous day quiz winners can be selected.');
            return back();
        }
    
        $quizIds = PostQuiz::join('posts', 'post_quizzes.post_id', '=', 'posts.id')
            ->whereDate(
                DB::raw('COALESCE(posts.schedule_post_date, posts.created_at)'),
                $drawDate
            )
            ->pluck('post_quizzes.id');
    
        if ($quizIds->count() == 0) {
            Toastr::error('No quizzes found for this date.');
            return back();
        }
    
        $existingWinnerCount = PostQuizWinner::whereIn('post_quiz_id', $quizIds)
            ->where(function ($query) {
                $query->whereNull('draw_type')
                    ->orWhere('draw_type', '!=', self::WEEKLY_DRAW_TYPE);
            })
            ->count();
    
        if ($existingWinnerCount > 0) {
            Toastr::warning('Winners already selected for this date.');
            return back();
        }
    
        $winnerRows = PostQuizAnswer::select(
                'user_id',
                DB::raw('COUNT(*) as correct_count'),
                DB::raw('MIN(created_at) as first_correct_at')
            )
            ->whereIn('post_quiz_id', $quizIds)
            ->where('is_correct', 1)
            ->whereNotNull('user_id')
            ->groupBy('user_id')
            ->orderByDesc('correct_count')
            ->orderBy('first_correct_at')
            ->limit(3)
            ->get();
    
        if ($winnerRows->count() == 0) {
            Toastr::error('No correct participants found for this date.');
            return back();
        }
    
        DB::transaction(function () use ($winnerRows, $quizIds, $drawDate) {
    
            foreach ($winnerRows as $index => $winnerRow) {
    
                $answer = PostQuizAnswer::whereIn('post_quiz_id', $quizIds)
                    ->where('user_id', $winnerRow->user_id)
                    ->where('is_correct', 1)
                    ->orderBy('created_at')
                    ->first();
    
                if (!$answer) {
                    continue;
                }
    
                $position = $index + 1;
    
                $amount = $this->prizeAmount($position);
    
                $winner = PostQuizWinner::create([
                    'post_quiz_id' => $answer->post_quiz_id,
                    'answer_id' => $answer->id,
                    'position' => $position,
                    'draw_type' => 'daily',
                    'period_start' => $drawDate,
                    'period_end' => $drawDate,
                ]);
    
                if ($amount > 0) {
    
                    User::where('id', $winnerRow->user_id)
                        ->increment('balance', $amount);
    
                    UserPrizeMoney::create([
                        'user_id' => $winnerRow->user_id,
                        'post_quiz_winner_id' => $winner->id,
                        'amount' => $amount,
                        'type' => 'quiz_prize',
                        'remarks' => "Day winner position {$position}",
                    ]);
                }
            }
        });
    
        Toastr::success('Day wise winners selected successfully.');
    
        return back();
    }
    
    public function chooseManualWinner(Request $request, $answerId)
    {
        $request->validate([
            'position' => 'required|integer|in:1,2,3',
        ]);
    
        $answer = PostQuizAnswer::with('quiz.post')->findOrFail($answerId);
    
        if (!$answer->is_correct) {
            Toastr::error('Only correct answers can be selected as winners.');
            return back();
        }
    
        if (!$answer->user_id) {
            Toastr::error('Selected participant has no user account linked.');
            return back();
        }
    
        if (!$answer->quiz || !$answer->quiz->post) {
            Toastr::error('Quiz post not found.');
            return back();
        }
    
        $timezone = 'Asia/Dhaka';
    
        $postDateValue = $answer->quiz->post->schedule_post_date
            ?: $answer->quiz->post->created_at;
    
        $winnerDate = \Carbon\Carbon::parse($postDateValue, $timezone)
            ->toDateString();
    
        $quizIds = PostQuiz::join('posts', 'post_quizzes.post_id', '=', 'posts.id')
            ->whereDate(
                DB::raw('COALESCE(posts.schedule_post_date, posts.created_at)'),
                $winnerDate
            )
            ->pluck('post_quizzes.id');
    
        try {
    
            DB::transaction(function () use ($request, $answer, $quizIds, $winnerDate) {
    
                $amount = $this->prizeAmount((int)$request->position);
    
                // Remove existing winner in same position
                $oldWinners = PostQuizWinner::with('answer')
                    ->whereIn('post_quiz_id', $quizIds)
                    ->where(function ($query) {
                        $query->whereNull('draw_type')
                            ->orWhere('draw_type', '!=', self::WEEKLY_DRAW_TYPE);
                    })
                    ->where('position', $request->position)
                    ->get();
    
                foreach ($oldWinners as $oldWinner) {
    
                    $oldAmount = $this->prizeAmount((int)$oldWinner->position);
    
                    if (
                        $oldAmount > 0 &&
                        $oldWinner->answer &&
                        $oldWinner->answer->user_id
                    ) {
    
                        User::where('id', $oldWinner->answer->user_id)
                            ->decrement('balance', $oldAmount);
    
                        UserPrizeMoney::create([
                            'user_id' => $oldWinner->answer->user_id,
                            'post_quiz_winner_id' => $oldWinner->id,
                            'amount' => -$oldAmount,
                            'type' => 'prize_reversal',
                            'remarks' => 'Winner replaced or removed',
                        ]);
                    }
    
                    $oldWinner->delete();
                }
    
                // Remove previous wins of same user on same day
                $sameUserAnswerIds = PostQuizAnswer::whereIn('post_quiz_id', $quizIds)
                    ->where('user_id', $answer->user_id)
                    ->pluck('id');
    
                $userOldWinners = PostQuizWinner::with('answer')
                    ->whereIn('answer_id', $sameUserAnswerIds)
                    ->where(function ($query) {
                        $query->whereNull('draw_type')
                            ->orWhere('draw_type', '!=', self::WEEKLY_DRAW_TYPE);
                    })
                    ->get();
    
                foreach ($userOldWinners as $uw) {
    
                    $oldAmount = $this->prizeAmount((int)$uw->position);
    
                    if (
                        $oldAmount > 0 &&
                        $uw->answer &&
                        $uw->answer->user_id
                    ) {
    
                        User::where('id', $uw->answer->user_id)
                            ->decrement('balance', $oldAmount);
    
                        UserPrizeMoney::create([
                            'user_id' => $uw->answer->user_id,
                            'post_quiz_winner_id' => $uw->id,
                            'amount' => -$oldAmount,
                            'type' => 'prize_reversal',
                            'remarks' => 'Winner replaced or removed',
                        ]);
                    }
    
                    $uw->delete();
                }
    
                // Create winner
                $winner = PostQuizWinner::create([
                    'post_quiz_id' => $answer->post_quiz_id,
                    'answer_id' => $answer->id,
                    'position' => $request->position,
                    'draw_type' => 'daily',
                    'period_start' => $winnerDate,
                    'period_end' => $winnerDate,
                ]);
    
                $user = User::find($answer->user_id);
    
                if (!$user) {
                    throw new \Exception(
                        'User not found. User ID: ' . $answer->user_id
                    );
                }
    
                if ($amount > 0) {
    
                    $user->increment('balance', $amount);
    
                    UserPrizeMoney::create([
                        'user_id' => $user->id,
                        'post_quiz_winner_id' => $winner->id,
                        'amount' => $amount,
                        'type' => 'quiz_prize',
                        'remarks' => 'Manual winner selection',
                    ]);
                }
    
                \Log::info('Manual Winner Selected', [
                    'winner_id' => $winner->id,
                    'user_id' => $user->id,
                    'amount' => $amount,
                ]);
            });
    
        } catch (\Exception $e) {
    
            \Log::error('Manual Winner Error', [
                'message' => $e->getMessage(),
            ]);
    
            Toastr::error($e->getMessage());
    
            return back();
        }
    
        Toastr::success('Winner selected manually and prize money added.');
        return back();
    }

    public function drawWeeklyWinners()
    {
        $timezone = 'Asia/Dhaka';
        [$weeklyStart, $weeklyEnd] = $this->currentWeeklyPeriod($timezone);

        if ($this->weeklyWinners($weeklyStart, $weeklyEnd)->count() > 0) {
            Toastr::warning('Weekly winners already selected for this 7 day period.');
            return back();
        }

        $winnerRows = $this->weeklyParticipantRanking($weeklyStart, $weeklyEnd, true)
            ->take(3)
            ->values();

        if ($winnerRows->count() < 3) {
            Toastr::error('At least 3 participants with right answers are required for weekly winners.');
            return back();
        }

        DB::transaction(function () use ($winnerRows, $weeklyStart, $weeklyEnd) {
            foreach ($winnerRows as $index => $winnerRow) {
                $answer = $this->representativeWeeklyAnswer((int)$winnerRow->user_id, $weeklyStart, $weeklyEnd);

                if (!$answer || !$answer->user_id) {
                    continue;
                }

                $position = $index + 1;
                $amount = $this->prizeAmount($position);

                $winner = PostQuizWinner::create([
                    'post_quiz_id' => $answer->post_quiz_id,
                    'answer_id' => $answer->id,
                    'position' => $position,
                    'draw_type' => self::WEEKLY_DRAW_TYPE,
                    'period_start' => $weeklyStart->toDateString(),
                    'period_end' => $weeklyEnd->toDateString(),
                ]);

                $this->creditWinnerPrize(
                    $answer,
                    $winner,
                    $amount,
                    "Weekly quiz winner position {$position}"
                );
            }
        });

        Toastr::success('Weekly quiz winners selected successfully.');
        return back();
    }

    public function chooseWeeklyManualWinner(Request $request, $userId)
    {
        $request->validate([
            'position' => 'required|integer|in:1,2,3',
        ]);

        $timezone = 'Asia/Dhaka';
        [$weeklyStart, $weeklyEnd] = $this->currentWeeklyPeriod($timezone);
        $userId = (int)$userId;

        $participant = $this->weeklyParticipantRanking($weeklyStart, $weeklyEnd, true)
            ->firstWhere('user_id', $userId);

        if (!$participant) {
            Toastr::error('This participant has no right answer in the last 7 days.');
            return back();
        }

        $answer = $this->representativeWeeklyAnswer($userId, $weeklyStart, $weeklyEnd);

        if (!$answer || !$answer->is_correct) {
            Toastr::error('A correct answer could not be found for this participant.');
            return back();
        }

        try {
            DB::transaction(function () use ($request, $userId, $answer, $weeklyStart, $weeklyEnd) {
                $position = (int)$request->position;

                $oldWinners = PostQuizWinner::with('answer')
                    ->where('draw_type', self::WEEKLY_DRAW_TYPE)
                    ->whereDate('period_start', $weeklyStart->toDateString())
                    ->whereDate('period_end', $weeklyEnd->toDateString())
                    ->where(function ($query) use ($position, $userId) {
                        $query->where('position', $position)
                            ->orWhereHas('answer', function ($answerQuery) use ($userId) {
                                $answerQuery->where('user_id', $userId);
                            });
                    })
                    ->get();

                foreach ($oldWinners as $oldWinner) {
                    $this->reverseWinnerPrize($oldWinner, 'Weekly winner replaced or removed');
                    $oldWinner->delete();
                }

                $winner = PostQuizWinner::create([
                    'post_quiz_id' => $answer->post_quiz_id,
                    'answer_id' => $answer->id,
                    'position' => $position,
                    'draw_type' => self::WEEKLY_DRAW_TYPE,
                    'period_start' => $weeklyStart->toDateString(),
                    'period_end' => $weeklyEnd->toDateString(),
                ]);

                $this->creditWinnerPrize(
                    $answer,
                    $winner,
                    $this->prizeAmount($position),
                    'Manual weekly winner selection'
                );
            });
        } catch (\Exception $e) {
            \Log::error('Manual Weekly Winner Error', [
                'message' => $e->getMessage(),
            ]);

            Toastr::error($e->getMessage());
            return back();
        }

        Toastr::success('Weekly winner selected manually and prize money added.');
        return back();
    }
        
    public function removeWinner($winnerId)
    {
        $winner = PostQuizWinner::with('answer')->findOrFail($winnerId);
    
        DB::transaction(function () use ($winner) {
    
            $this->reverseWinnerPrize($winner, 'Winner removed');
    
            $winner->delete();
        });
    
        Toastr::success('Winner removed successfully.');
        return back();
    }
    
    public function destroy($quizId)
    {
        $quiz = PostQuiz::findOrFail($quizId);
    
        DB::transaction(function () use ($quiz) {
            PostQuizWinner::where('post_quiz_id', $quiz->id)->delete();
            PostQuizAnswer::where('post_quiz_id', $quiz->id)->delete();
    
            $quiz->delete();
        });
    
        Toastr::success('Quiz deleted successfully.');
        return back();
    }
}

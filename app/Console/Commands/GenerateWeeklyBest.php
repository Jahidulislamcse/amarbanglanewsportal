<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\TopReporter;
use Carbon\Carbon;

class GenerateWeeklyBest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'weekly-best:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically calculate and save the top 3 weekly reporters based on post views of the last 7 days';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $oneWeekAgo = Carbon::now()->subDays(7)->startOfDay()->toDateTimeString();

        // 1. Fetch top 3 reporters of the last 7 days based on views (fallback to all-time views if 0)
        $topReporters = User::select(
                'users.id',
                'users.views as total_views'
            )
            ->selectRaw('COALESCE(SUM(posts.view_count), 0) as views_last_week')
            ->leftJoin('posts', function($join) use ($oneWeekAgo) {
                $join->on('posts.user_id', '=', 'users.id')
                     ->where('posts.created_at', '>=', $oneWeekAgo);
            })
            ->where('users.is_reader', 0)
            ->groupBy('users.id', 'users.views')
            ->orderByDesc('views_last_week')
            ->orderByDesc('users.views')
            ->limit(3)
            ->get();

        $startOfWeek = Carbon::now()->subDays(7)->format('Y-m-d');
        $endOfWeek = Carbon::now()->format('Y-m-d');
        $weekRange = "{$startOfWeek} - {$endOfWeek}";

        \Illuminate\Support\Facades\DB::transaction(function() use ($topReporters, $weekRange) {
            // 2. Revert previous prize money balances for this week range if any exist
            $existingPrizes = \App\Models\ReporterPrizeMoney::where('week', $weekRange)->get();
            foreach ($existingPrizes as $existingPrize) {
                $user = User::find($existingPrize->user_id);
                if ($user) {
                    $user->decrement('balance', $existingPrize->amount);
                }
            }

            // 3. Clear previous weekly records for this week range
            \App\Models\ReporterPrizeMoney::where('week', $weekRange)->delete();
            TopReporter::where('week', $weekRange)->delete();

            // 4. Get prize settings
            $fee = \App\Models\Fee::first();
            $prizes = [
                1 => $fee ? (float)$fee->reporter_1st_prize : 0.00,
                2 => $fee ? (float)$fee->reporter_2nd_prize : 0.00,
                3 => $fee ? (float)$fee->reporter_3rd_prize : 0.00,
            ];

            // 5. Save the new weekly winners and award prize money
            foreach ($topReporters as $index => $reporter) {
                $position = $index + 1;
                $prizeAmount = $prizes[$position] ?? 0.00;

                TopReporter::create([
                    'user_id' => $reporter->id,
                    'position' => $position,
                    'total_views' => $reporter->views_last_week,
                    'week' => $weekRange,
                    'year' => Carbon::now()->year,
                    'month' => Carbon::now()->month
                ]);

                if ($prizeAmount > 0) {
                    \App\Models\ReporterPrizeMoney::create([
                        'user_id' => $reporter->id,
                        'position' => $position,
                        'amount' => $prizeAmount,
                        'week' => $weekRange
                    ]);

                    $user = User::find($reporter->id);
                    if ($user) {
                        $user->increment('balance', $prizeAmount);
                    }
                }

                $this->info("Saved Winner {$position}: User ID {$reporter->id} with {$reporter->views_last_week} weekly views. Prize: {$prizeAmount}");
            }
        });

        $this->info("Successfully generated top 3 weekly best reporters for {$weekRange}.");
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class CacheTopViews extends Command
{
    protected $signature = 'cache:topviews';
    protected $description = 'Cache top viewed posts for the day';

    public function handle()
    {
        $gs = Cache::remember('general_settings', 604800, function () {
            return \App\Models\GeneralSettings::first();
        });

        $limit = $gs->popular_news_limit ?? 5;

        $top_views = DB::table('views')
            ->join('posts', 'views.post_id', '=', 'posts.id')
            ->whereDate('views.created_at', now())
            ->groupBy('views.post_id')
            ->select(DB::raw('COUNT(*) as top_views, post_id'))
            ->orderByDesc('top_views')
            ->take($limit)
            ->get();

        Cache::put('top_views', $top_views, 604800); // cache for 1 week
        $this->info('Top views cached successfully!');
    }
}
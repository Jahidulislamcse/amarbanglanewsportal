<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\GeneralSettings;
use App\Models\Font;
use App\Models\Language;
use App\Models\SocialLink;
use App\Models\Post;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        Post::saved(function () {
            cache()->forget('home_index_data_lang_1');
            cache()->forget('details_recents_1');
            cache()->forget('details_trendings_1');
        });
        Post::deleted(function () {
            cache()->forget('home_index_data_lang_1');
            cache()->forget('details_recents_1');
            cache()->forget('details_trendings_1');
        });

        View::composer('partial.front2.header', function ($view) {

            $trendings = Post::with('category:id,slug,title')
                ->where('is_trending', 1)
                ->where('is_pending', 0)
                ->where('schedule_post_date', '<=', now())
                ->where('status', true)
                ->latest()
                ->take(10)
                ->get();
    
            $view->with('trendings', $trendings);
        });
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        // Cache and share static data globally
        $gs = GeneralSettings::first();
        
        $seo = DB::table('seotools')->first();

        $social_links = cache()->remember('social_links', now()->addDays(3), function () {
            return SocialLink::orderByDesc('id')->get();
        });

        $languages = cache()->rememberForever('languages', function () {
            return Language::orderBy('id', 'desc')->get();
        });

        $default_font = cache()->rememberForever('default_font', function () {
            return Font::where('is_default', 1)->first();
        });

        // Share static data with all views
        View::share(compact('gs', 'seo', 'social_links', 'languages', 'default_font'));

        // Dynamic data per request / per user
        view()->composer('*', function ($view) use ($gs) {

            // Default language (from session or fallback)
            if (Session::has('language')) {
            
                $default_language = Language::find(Session::get('language'));
            
            } else {
            
                $default_language = cache()->rememberForever('default_language', function () {
                    return Language::where('is_default', 1)->first();
                });
            
            }
            
            app()->setLocale($default_language->name);

            // Categories for menu (per language)
            $categories = cache()->remember('categories_'.$default_language->id, 604800, function () use ($default_language) {
                return Category::with('child') // eager load children if needed
                    ->where('language_id', $default_language->id)
                    ->where('parent_id', null)
                    ->where('show_on_menu', 1)
                    ->orderBy('category_order', 'asc')
                    ->get();
            });

            // Top viewed posts (today) - consider precomputing in scheduled job
            $limit = $gs->popular_news_limit ?? 5;
            $top_views = Post::with('category')
            ->where('language_id', $default_language->id)
            ->where('is_pending', 0)
            ->where('status', true)
            ->where('schedule_post_date', '<=', now())
            ->where('created_at', '>=', now()->startOfDay())
            ->where('created_at', '<=', now())
            ->orderByDesc('view_count')
            ->take($limit)
            ->get();

            // Tags
            $tags = $gs->tags ? explode(',', $gs->tags) : [];

            $view->with(compact('categories', 'top_views', 'tags', 'default_language'));
        });
    }
}
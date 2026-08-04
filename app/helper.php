<?php 
use Illuminate\Support\Str;
use Stichoza\GoogleTranslate\GoogleTranslate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

if (!function_exists('rashghifalllists')) {

    function rashghifalllists($language, $sel = null)
    {
        $data = Cache::remember(
            'rashghifalllists_' . $language,
            now()->addDays(7),
            function () use ($language) {

                if ($language == 2) {

                    return [
                        1 => "Aries [21 Mar-20 Apr]",
                        2 => "Taurus [21 Apr-20 May]",
                        3 => "Gemini [21 May-20 June]",
                        4 => "Cancer [21 June-20 July]",
                        5 => "Leo [21 July-20 Aug]",
                        6 => "Virgo [22 Aug-22 Sep]",
                        7 => "Libra [23 Sep-22 Oct]",
                        8 => "Scorpio [23 Oct-21 Nov]",
                        9 => "Sagittarius [22 Nov-20 Dec]",
                        10 => "Capricorn [21 Dec-19 Jan]",
                        11 => "Aquarius [20 Jan-18 Feb]",
                        12 => "Pisces [19 Feb-20 Mar]",
                    ];

                }

                return [
                    1 => "মেষ [২১ মর্-২০ এপ্রি]",
                    2 => "বৃষ [২১ এপ্রি-২০ মে]",
                    3 => "মিথুন [২১ মে-২০ জুন]",
                    4 => "কর্কট [২১ জুন-২০ জুলা]",
                    5 => "সিংহ [২১ জুলা-২০ আগ]",
                    6 => "কন্যা [২২ আগ-২২ সেপ্টে]",
                    7 => "তুলা [২৩ সেপ্টে-২২ অক্টো]",
                    8 => "বৃশ্চিক [২৩ অক্টো-২১ নভে]",
                    9 => "ধনু [২২ নভে-২০ ডিসে]",
                    10 => "মকর [২১ ডিসে-১৯ জানু]",
                    11 => "কুম্ভ [২০ জানু-১৮ ফেব্রু]",
                    12 => "মীন [১৯ ফেব্রু-২০ মার্চ]",
                ];
            }
        );

        return $sel !== null
            ? ($data[$sel] ?? '')
            : $data;
    }
}

if (!function_exists('getReporterAreaName')) {
    function getReporterAreaName($languageId, $reportTypeId, $user)
    {
        if (!$user || !is_object($user)) {
            return '';
        }

        $column = $languageId == 1 ? 'bn_name' : 'name';

        if (!empty($user->report_type)) {

            $types = json_decode($user->report_type, true);

            if (is_array($types) && isset($types[0])) {

                $typeId = (int)$types[0];

                if (in_array($typeId, [29, 31, 37]) && !empty($user->division_id)) {
                    return DB::table('divisions')
                        ->where('id', $user->division_id)
                        ->value($column);
                }

                elseif (in_array($typeId, [30, 36]) && !empty($user->district_id)) {
                    return DB::table('districts')
                        ->where('id', $user->district_id)
                        ->value($column);
                }

                elseif (in_array($typeId, [32, 35]) && !empty($user->thana_id)) {
                    return DB::table('upazilas')
                        ->where('id', $user->thana_id)
                        ->value($column);
                }

                elseif ($typeId == 34 && !empty($user->union_id)) {
                    return DB::table('unions')
                        ->where('id', $user->union_id)
                        ->value($column);
                }
            }
        }

        return '';
    }
}

if (!function_exists('educationlists') ) {
    function educationlists($language,$sel=null) {
		
		if($language==2){
			$education_exams = [
			'1'        =>  'SSC / Dakhil / Equivalent',
			'2'        => 'HSC / Alim / Equivalent',
			'3'   =>  "Bachelor's / Honours / Fazil / Equivalent",
			'4'    => "Master's / Postgraduate / Kamil",
			'5'      => 'MPhil',
			'6'        =>  'PhD / Doctorate',
			'7'     => 'Others'
		];
		}else{
			$education_exams = [
				'1'        =>   'এসএসসি / দাখিল / সমমান',
				'2'        => 'এইচএসসি / আলিম / সমমান',
				'3'   =>  'স্নাতক / অনার্স / ফাজিল / সমমান',
				'4'    => 'স্নাতকোত্তর / মাস্টার্স/ কামিল / সমমান',
				'5'      => 'এমফিল',
				'6'        =>  'পিএইচডি / ডক্টরেট',
				'7'     => 'অন্যান্য'
			];
		}
		
		if(isset($education_exams[$sel])){
			return $education_exams[$sel];
		}else{
			return $education_exams;
		}
	}
}



if (!function_exists('payment_type') ) {
    function payment_type() {
		$p_type_array=array("1"=>"Cash","2"=>"Bank","3"=>"Bkash","4"=>"Nagad","5"=>"Rocket","6"=>"Upay","7"=>"Mcash","8"=>"Sure Cash","9"=>"Wallet");
		return $p_type_array;
	}
}

if (!function_exists('convertToBangla')) {
    function convertToBangla($datetime = null)
    {
        // Use current date if no datetime provided
        $datetime = new DateTime($datetime ?? date('Y-m-d H:i:s'));

        $en_digits = ['0','1','2','3','4','5','6','7','8','9'];
        $bn_digits = ['০','১','২','৩','৪','৫','৬','৭','৮','৯'];

        $en_months = [
            'January','February','March','April','May','June',
            'July','August','September','October','November','December'
        ];
        $bn_months = [
            'জানুয়ারি','ফেব্রুয়ারি','মার্চ','এপ্রিল','মে','জুন',
            'জুলাই','আগস্ট','সেপ্টেম্বর','অক্টোবর','নভেম্বর','ডিসেম্বর'
        ];

        $en_days = ['Saturday','Sunday','Monday','Tuesday','Wednesday','Thursday','Friday'];
        $bn_days = ['শনিবার','রবিবার','সোমবার','মঙ্গলবার','বুধবার','বৃহস্পতিবার','শুক্রবার'];

        // Format
        $formatted = $datetime->format('l, d F Y, h:i A');

        // Replace
        $formatted = str_replace($en_days, $bn_days, $formatted);
        $formatted = str_replace($en_months, $bn_months, $formatted);
        $formatted = str_replace($en_digits, $bn_digits, $formatted);
        $formatted = str_replace(['AM','PM'], ['এ এম','পি এম'], $formatted);

        return $formatted;
    }
}

if (!function_exists('isMobile') ) {
    function isMobile() {
		return preg_match(
			'/(android|iphone|ipod|ipad|blackberry|opera mini|iemobile|mobile)/i',
			$_SERVER['HTTP_USER_AGENT']
		);
	}
}
if(!function_exists('blood_groups')){
    function blood_groups($lid,$sel=null){
		
		if($lid==2){
			$blood_groups= [
				1 => "A+",
				2 => "A-",
				3 => "B+",
				4 => "B-",
				5 => "O+",
				6 => "O-",
				7 => "AB+",
				8 => "AB-"
			];
		}else{
			$blood_groups = [
				1 => "এ প্লাস",
				2 => "এ মাইনাস",
				3 => "বি প্লাস",
				4 => "বি মাইনাস",
				5 => "ও প্লাস",
				6 => "ও মাইনাস",
				7 => "এবি প্লাস",
				8 => "এবি মাইনাস"
			];
		}
		if($sel){
			 return isset($blood_groups[$sel])?$blood_groups[$sel]:'';
		}else{
			 return $blood_groups;
		}
       
    }
}





if (!function_exists('slug_create') ) {
    function slug_create($val) {
	    $tr = new GoogleTranslate('en');
		$englishTitle = $tr->translate($val);
		$slug = Str::slug($englishTitle);
		
        return $slug;
    }
}

if(!function_exists('advertisement')){
    function advertisement(){
        $index_bottom = App\Models\Advertisement::inRandomOrder()
                                        ->where('add_placement','1')
                                        ->where('status',1)
                                        ->first(); 
        return $index_bottom;
    }
}

if(!function_exists('sidebar_banner')){
    function sidebar_banner(){
        $sidebar_banner = App\Models\Advertisement::inRandomOrder()
                                        ->where('add_placement','sidebar_bottom')
                                        ->where('addSize','size_468')
                                        ->where('status',1)
                                        ->first(); 
        return $sidebar_banner;
    }
}

if(!function_exists('sponsor_banner')){
    function sponsor_banner(){
        $sponsor_banners     = App\Models\Advertisement::inRandomOrder()
                                        ->where('add_placement','sponsor')
                                        ->where('addSize','size_468')
                                        ->where('status',1)
                                        ->take(2)
                                        ->get();
        return $sponsor_banners;
    }
}

if(!function_exists('header_ads')){

    function header_ads(){
        $header_ads   = App\Models\Advertisement::inRandomOrder()
                                ->where('add_placement','1')
                                ->where('status',1)
                                ->inRandomOrder()
                                ->limit(1)
                                ->first(); 
        return $header_ads;
    }
}

if (! function_exists('convertUtf8')) {
    function convertUtf8( $value ) {
        return mb_detect_encoding($value, mb_detect_order(), true) === 'UTF-8' ? $value : mb_convert_encoding($value, 'UTF-8');
    }
}

if(!function_exists('d_logo')){
    function d_logo($lid){
        $header_footer_logo = App\Models\Language::find($lid)->logos;
        return $header_footer_logo;
    }
}

if(!function_exists('report_type')){
    function report_type($lid){
		
		if($lid==1){
			$title="title_bn";
		}else{
			$title="title_en";
		}
        $reportcategory = App\Models\ReportCategory::pluck("$title AS title",'id');
        return $reportcategory;
    }
}

if(!function_exists('video_category')){
    function video_category($lid){
        $reportcategory = App\Models\VideoCategory::where('language_id',$lid)->pluck('title','id');
        return $reportcategory;
    }
}

if(!function_exists('is_trendings')){
    function is_trendings($lid)
    {
        return cache()->remember("is_trendings_{$lid}", 600, function() use ($lid) {
            return App\Models\Post::where('is_trending', 1)
                ->where('is_pending', 0)
                ->where('schedule_post_date', '<=', now())
                ->whereIn('post_type', ['article', 'audio'])
                ->where('language_id', $lid)
                ->where('status', true)
                ->orderByDesc('id')
                ->take(10)
                ->pluck('id')
                ->toArray();
        });
    }
}

if (!function_exists('is_recents')) {
    function is_recents($lid)
    {
        $limit = App\Models\GeneralSettings::first()->recent_news_limit ?? 5;

        return App\Models\Post::orderByDesc('id')
            ->where('is_pending', 0)
            ->where('schedule_post_date', '<=', now())
            ->whereIn('post_type', ['article', 'audio'])
            ->where('is_slider', '<>', 3)
            ->where('language_id', $lid)
            ->where('status', true)
            ->take($limit)
            ->get(['id','title','slug','image_big','category_id']);
    }
}


if(!function_exists('is_ads')){
    function is_ads($array){
        sort($array);
        $key = 'ads_' . implode('_', $array);
        return cache()->remember($key, 1800, function() use ($array) {
             return App\Models\Advertisement::where('status',1)
								->whereIn('add_placement',$array)
                                ->get(); 
        });
    }
}

if (!function_exists('is_division')) {
    function is_division($lid, $value = null)
    {
        $column = $lid == 1 ? 'bn_name' : 'name';

        $query = App\Models\Division::query();

        if ($value) {
            $query->where(function ($q) use ($column, $value) {
                $q->where($column, $value)
                  ->orWhere('name', $value)
                  ->orWhere('bn_name', $value);
            });

            return $query->first();
        }

        return $query->select('id', "$column as name")->get();
    }
}


if(!function_exists('is_area')){
    function is_area($yes,$id){
		
	  $name="name";
	  
		if($yes == 1 && $id){
			 $data =App\Models\Division::where("id", $id)->select("name")->first();
			 $name=$data->name ?? ''; 
		} elseif($yes == 2 && $id){
			 $data =App\Models\District::where("id", $id)->select("name")->first();
			  $name=$data->name ?? ''; 
		} elseif($yes == 3 && $id){
			 $data =App\Models\Thana::where("id", $id)->select("name")->first();
			  $name=$data->name ?? ''; 
		} elseif($yes == 4 && $id){
			 $data =App\Models\Unions::where("id", $id)->select("name")->first();
			  $name=$data->name ?? ''; 
		}else{
			 $name="";
		}

	  return $name;
    }
}
// if(!function_exists('is_division')){
//     function is_division($lid, $division_id = null){
//         $name = $lid == 1 ? 'bn_name' : 'name';
//         if($division_id){
//             return App\Models\Division::where('id', $division_id)
//                 ->select('id', "$name AS name")
//                 ->first();
//         } else {
//             return App\Models\Division::select('id', "$name AS name")->get();
//         }
//     }
// }
if(!function_exists('is_district')){
    function is_district($lid, $division_id, $single=false){
        $name = $lid == 1 ? 'bn_name' : 'name';
        if($single){
            return App\Models\District::where('id', $division_id)
                ->select('id', "$name AS name")
                ->first();
        } else {
            return App\Models\District::where('division_id', $division_id)
                ->select('id', "$name AS name")
                ->get();
        }
    }
}

if(!function_exists('is_thana')){
    function is_thana($lid,$district_id,$yes=0){
	  if($lid==1){
		  $name="bn_name";
	  }else{
		  $name="name";
	  }
	  if($yes){
		  $thanas =App\Models\Thana::where("$name", $district_id)->select("id","$name AS name")->first(); 
	  }else{
		   $thanas =App\Models\Thana::where('district_id', $district_id)->select("id","$name AS name")->get(); 
	  }
        
		return $thanas;
    }
}

if(!function_exists('is_union')){
    function is_union($lid,$upazilla_id,$yes=0){
	  if($lid==1){
		  $name="bn_name";
	  }else{
		  $name="name";
	  }
	  if($yes){
		  $unions =App\Models\Unions::where("$name", $upazilla_id)->select("id","$name AS name")->first(); 
	  }else{
		   $unions =App\Models\Unions::where('upazilla_id', $upazilla_id)->select("id","$name AS name")->get(); 
	  }
        
		return $unions;
    }
}

if(!function_exists('is_status')){
    function is_status(){
	    $status = [0=>'Pending',1=>'Approved', 2=>'Rejected'];
		return $status;
    }
}
if (!function_exists('is_reporter_area')) {

    function is_reporter_area($language_id = 1, $yes = null)
    {
        static $lists = [

            1 => [
                1 => 'বিভাগ',
                2 => 'জেলা',
                3 => 'উপজেলা',
                4 => 'পৌরসভা',
                5 => 'ইউনিয়ন'
            ],

            2 => [
                1 => 'Division',
                2 => 'District',
                3 => 'Upazila',
                4 => 'Pourosobha',
                5 => 'Union'
            ]
        ];

        return $lists[$language_id][$yes] ?? '';
    }
}

if(!function_exists('reporter_area')){
    function reporter_area($yes=null,$language_id=null){
        if($language_id==1){
            if($yes==1){
                $status = [1=>'বিভাগ',2=>'জেলা', 3=>'উপজেলা',4=>'পৌরসভা',5=>'ইউনিয়ন'];
            } elseif($yes==2){
                $status = [2=>'জেলা', 3=>'উপজেলা',4=>'পৌরসভা',5=>'ইউনিয়ন'];
            } elseif($yes==3){
                $status = [3=>'উপজেলা',4=>'পৌরসভা',5=>'ইউনিয়ন'];
            } elseif($yes==4){
                $status = [4=>'পৌরসভা',5=>'ইউনিয়ন'];
            } elseif($yes==5){
                $status = [5=>'ইউনিয়ন'];
            } else {
                $status = [1=>'বিভাগ',2=>'জেলা', 3=>'উপজেলা',4=>'পৌরসভা',5=>'ইউনিয়ন'];
            }
        } else {
            if($yes==1){
                $status = [1=>'Division',2=>'District',3=>'Upazila',4=>'Pourosobha',5=>'Union'];
            } elseif($yes==2){
                $status = [2=>'District',3=>'Upazila',4=>'Pourosobha',5=>'Union'];
            } elseif($yes==3){
                $status = [3=>'Upazila',4=>'Pourosobha',5=>'Union'];
            } elseif($yes==4){
                $status = [4=>'Pourosobha',5=>'Union'];
            } elseif($yes==5){
                $status = [5=>'Union'];
            } else {
                $status = [1=>'Division',2=>'District',3=>'Upazila',4=>'Pourosobha',5=>'Union'];
            }
        }
        return $status;
    }
}



if(!function_exists('pr')){
   function pr($data,$is_die=null) {
		echo "<pre>";
		print_r($data);
		if($is_die){
			die;
		}
	}
}

if(!function_exists('enToBn')){
   function enToBn($value,$is_numeric=null) {
		$en = ['0','1','2','3','4','5','6','7','8','9'];
		$bn = ['০','১','২','৩','৪','৫','৬','৭','৮','৯'];
		if($is_numeric){
			$data = str_replace($en, $bn, $value);
		}else{
			$weekDays = [
				'Saturday' => 'শনিবার',
				'Sunday' => 'রবিবার',
				'Monday' => 'সোমবার',
				'Tuesday' => 'মঙ্গলবার',
				'Wednesday' => 'বুধবার',
				'Thursday' => 'বৃহস্পতিবার',
				'Friday' => 'শুক্রবার'
			];
			$months = [
				1 => 'জানুয়ারী',
				2 => 'ফেব্রুয়ারী',
				3 => 'মার্চ',
				4 => 'এপ্রিল',
				5 => 'মে',
				6 => 'জুন',
				7 => 'জুলাই',
				8 => 'আগস্ট',
				9 => 'সেপ্টেম্বর',
				10 => 'অক্টোবর',
				11 => 'নভেম্বর',
				12 => 'ডিসেম্বর'
			];
			
			$timestamp = strtotime($value);
			$dayName = date('l', $timestamp);
			$day = date('d', $timestamp);
			$month = date('n', $timestamp);
			$year = date('Y', $timestamp);
			
			$hour_minute = date('h:i', $timestamp);
			$am_pm = date('A', $timestamp);
			
			$am_pms = [
				'AM' => 'এ এম',
				'PM' => 'পি.এম'
			];

			$data = $weekDays[$dayName] . ', ' . str_replace($en, $bn, $day) . ' ' . $months[$month] . ', ' . str_replace($en, $bn, $year). ' ' . str_replace($en, $bn, $hour_minute).' '. $am_pms[$am_pm];
		}
		return $data;
	}
}


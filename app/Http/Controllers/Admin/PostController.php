<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Category;
use App\Models\Gallery;
use App\Models\User;
use App\Models\Language;
use App\Models\View;
use Carbon\Carbon;
use Datatables;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function datatables(Request $request)
    {
        $input = $request->all();
        $admin = Auth::guard('admin')->user();   
        $role  = $admin->role;                   
    
        $query = Post::with(['category','language','admin','user'])
            ->where('status', 'true')
            ->where('schedule_post_date', '<=', now());
    
        if ($role->code === 'admin') {
            $query->where('admin_id', $admin->id);
        }
    
        if (!empty($input['lang'])) {
            $query->where('language_id', $input['lang']);
        }
    
        if (!empty($input['category'])) {
            $query->where('category_id', $input['category']);
        }
    
        $query->orderBy('id','desc');
    
        return Datatables::of($query)
            ->addColumn('checkbox', fn($data) => '<input type="checkbox" class="form-check-input m-0 p-0 postCheck" value="'.$data->id.'">')
            ->editColumn('category_id', fn($data) => $data->category ? '<span class="badge badge-primary">'.$data->category->title.'</span>' : '')
            ->editColumn('language_id', fn($data) => $data->language ? '<span class="badge badge-info">'.$data->language->language.'</span>' : '')
            ->addColumn('view_count', fn($data) => $data->view_count ?? 0)
            ->addColumn('unique_count', fn($data) => $data->unique_count ?? 0)
            ->addColumn('current_count', fn($data) => $data->current_count ?? 0)
            ->editColumn('post_type', fn($data) => $data->post_type ? '<span class="badge badge-secondary">'.$data->post_type.'</span>' : '')
            ->editColumn('image_big', function($data){
                $img = $data->post_type == 'rss' 
                    ? ($data->rss_image ?? url('assets/images/nopic.png')) 
                    : ($data->image_big ? url('assets/images/post/'.$data->image_big) : url('assets/images/nopic.png'));
            
                return '<img src="'.$img.'" alt="Image" style="height:50px;">'; 
            })

            ->editColumn('created_at', fn($data) => $data->created_at ? $data->created_at->format('d M Y, h:i A') : '')
            ->editColumn('admin_id', fn($data) => $data->admin ? $data->admin->name : ($data->user ? $data->user->name : 'Deleted'))
            ->addColumn('is_approve', function(Post $data){
                return $data->is_pending != 0 
                    ? '<span class="badge badge-warning text-white">pending</span>' 
                    : '<span class="badge badge-success">approve</span>';
            })
            ->addColumn('postcard', function(Post $data) {
                if ($data->is_pending != 0) {
                    return '<small class="text-muted">will appear when news approved</small>';
                }
                $dateStr = $data->schedule_post_date ?? $data->created_at->toDateTimeString();
                $formattedDate = enToBn(date('d M Y', strtotime($dateStr)));
                $imageUrl = $data->post_type == 'rss' ? ($data->rss_image ? $data->rss_image : asset('assets/images/nopic.png')) : ($data->image_big ? asset('assets/images/post/'.$data->image_big) : asset('assets/images/nopic.png'));

                return '<a href="javascript:void(0)" class="download-postcard-btn btn btn-info btn-sm" style="background:#145a32; border-color:#145a32;" data-title="'.e($data->title).'" data-image="'.$imageUrl.'" data-date="'.$formattedDate.'"> <i class="fas fa-image"></i> Download</a>';
            })
            ->addColumn('copylink', function(Post $data) {
                if ($data->is_pending != 0) {
                    return '<small class="text-muted">will appear when news approved</small>';
                }
                $categorySlug = !empty($data->category->slug) ? $data->category->slug : 'uncategorized';
                $detailsUrl = route('frontend.postBySubcategory.details', [$categorySlug, $data->slug]);
                return '<a href="javascript:void(0)" class="copy-post-link-btn btn btn-success btn-sm" data-url="'.$detailsUrl.'"> <i class="fas fa-copy"></i> Copy Link</a>';
            })
            ->addColumn('action', function(Post $data){
                $slider = '';
                $is_trending = $data->is_trending == 0 
                    ? '<a href="'.route('post.trendingChange',$data->id).'"><i class="fa fa-plus"></i> Add Into Breaking</a>' 
                    : '<a href="'.route('post.trendingChange',$data->id).'"><i class="fa fa-minus"></i> Remove Breaking</a>';
    
                $is_approve = '';
                if (Auth::guard('admin')->user()->sectionCheck('news_approve') || Auth::user()->id == 1) {
                    $is_approve = $data->is_pending == 0 
                        ? '<a href="'.route('post.pendingChange',$data->id).'"><i class="fa fa-file"></i> Make Post Pending</a>' 
                        : '<a href="'.route('post.pendingChange',$data->id).'"><i class="fa fa-file"></i> Make Post Approve</a>';
                }
    
                $is_slider_lefts = $data->is_feature == 0 
                    ? '<a href="'.route('post.feature',$data->id).'"><i class="fa fa-plus"></i> Add into Feature</a>' 
                    : '<a href="'.route('post.feature',$data->id).'"><i class="fa fa-minus"></i> Remove Feature</a>';
    
                $edit = $data->post_type == 'rss' ? '' : '<a href="'.route('post.edit',$data->id).'"> <i class="fas fa-edit"></i> Edit</a>';
                $details = '<a href="'.route('frontend.postBySubcategory.details',[$data->category->slug ?? '','' .$data->slug]).'" target="_blank"> <i class="fa fa-info-circle"></i> View on Frontend</a>';
    
                return '<div class="godropdown"><button class="go-dropdown-toggle"> Actions<i class="fas fa-chevron-down"></i></button><div class="action-list">'.$details.$edit.$slider.$is_trending.$is_slider_lefts.$is_approve.'<a href="javascript:;" data-href="'.route('post.delete',$data->id).'" data-toggle="modal" data-target="#confirm-delete" class="delete"><i class="fas fa-trash-alt"></i> Delete</a></div></div>';
            })
            ->addColumn('approved_at', function(Post $data) {
                if (empty($data->approved_at)) {
                    return '<span class="text-muted">N/A</span>';
                }
                try {
                    return \Carbon\Carbon::parse($data->approved_at)->format('d M Y, h:i A');
                } catch (\Exception $e) {
                    return '<span class="text-muted">N/A</span>';
                }
            })
            ->rawColumns(['checkbox','image_big','category_id','language_id','post_type','admin_id','is_approve','approved_at','action', 'postcard', 'copylink'])
            ->make(true);
    }
	
	
	public function postledgerdatatables(Request $request){
        $input = $request->all();
        $user = Auth::guard('admin')->user()->role;
        $default_language = Language::where('is_default',1)->first();
		$datas=[];
		if($user->name == 'admin'){
			if(isset($input['lang']) || isset($input['category'])){
				if(isset($input['lang'])){

									$datas=Post::selectRaw('posts.*, SUM(views.view_count) as total_view,posts.view_count as total_unique_view,SUM(posts.view_count*0.01) as total_income')
									 ->join('views', 'posts.id', '=', 'views.post_id')
									 ->where('posts.language_id', $input['lang'])
									 ->groupBy('views.post_id')
									 ->orderBy('views.post_id', 'desc')
									 ->get();
										
				if(isset($input['category'])){

								$datas=Post::selectRaw('posts.*, SUM(views.view_count) as total_view,posts.view_count as total_unique_view,SUM(posts.view_count*0.01) as total_income')
									 ->join('views', 'posts.id', '=', 'views.post_id')
									 ->where('posts.category', $input['category'])
									 ->groupBy('views.post_id')
									 ->orderBy('views.post_id', 'desc')
									 ->get();

				}
			}else{

								$datas=Post::selectRaw('posts.*, SUM(views.view_count) as total_view,posts.view_count as total_unique_view,SUM(posts.view_count*0.01) as total_income')
									 ->join('views', 'posts.id', '=', 'views.post_id')
									 ->where('posts.category', $input['category'])
									 ->groupBy('views.post_id')
									 ->orderBy('views.post_id', 'desc')
									 ->get();
			}
	  }
		  }
        return Datatables::of($datas)

                            ->editColumn('category_id',function(Post $data){
                                $category_id = $data->category_id ? '<span class="badge badge-primary">'.$data->category->title.'</span>' : '';
                                return $category_id;
                            })
                            ->editColumn('language_id',function(Post $data){
                                $language_id = $data->language_id ? '<span class="badge badge-info">'.$data->language->language.'</span>' : '';
                                return $language_id;
                            })
                            ->editColumn('post_type',function(Post $data){
                                $post_type = $data->post_type ? '<span class="badge badge-secondary">'.$data->post_type.'</span>': '';
                                return $post_type;
                            })
                            ->editColumn('image_big',function(Post $data){
                                if($data->post_type == 'rss'){
                                    $rss_image = $data->rss_image ?  $data->rss_image:url('assets/images/nopic.png');
                                    return '<img src="'.$rss_image.'" alt="Image">';
                                }else{
                                    $image_big = $data->image_big ? url('assets/images/post/'.$data->image_big):url('assets/images/nopic.png');
                                    return '<img src="'.$image_big.'" alt="Image">';
                                }
                            })
                            ->editColumn('created_at',function(Post $data){
                                $created_at = $data->created_at;
                                return $created_at->toFormattedDateString();
                            })
                            ->rawColumns(['image_big','category_id','language_id','post_type','created_at',])
                            ->toJson(); //--- Returning Json Data To Client Side
    
	
	}
    public function index(Request $request){
		
		
        return view('admin.post.index');
    }
    
    public function approvedDatatables(Request $request)
    {
        $admin = Auth::guard('admin')->user();
    
        $query = Post::with(['category','language','admin','user'])
            ->where('status', 'true')
            ->where('is_pending', 0) 
            ->where('approve_by', $admin->id)
            ->orderBy('id','desc');
    
        return Datatables::of($query)
        
            ->addColumn('checkbox', fn($data) =>
                '<input type="checkbox" class="form-check-input m-0 p-0 postCheck" value="'.$data->id.'">'
            )
        
            ->addColumn('image_big', function(Post $data){
                if($data->post_type == 'rss'){
                    $rss_image = $data->rss_image ?  $data->rss_image : url('assets/images/nopic.png');
                    return '<img src="'.$rss_image.'" alt="Image" style="height:50px;">';
                } else {
                    $image_big = $data->image_big ? url('assets/images/post/'.$data->image_big) : url('assets/images/nopic.png');
                    return '<img src="'.$image_big.'" alt="Image" style="height:50px;">';
                }
            })
        
            ->editColumn('category_id', fn($data) =>
                $data->category ? '<span class="badge badge-primary">'.$data->category->title.'</span>' : ''
            )
        
            ->editColumn('language_id', fn($data) =>
                $data->language ? '<span class="badge badge-info">'.$data->language->language.'</span>' : ''
            )
        
            ->editColumn('admin_id', fn($data) =>
                $data->admin ? $data->admin->name : ($data->user ? $data->user->name : 'Deleted')
            )
        
            ->addColumn('is_approve', fn($data) =>
                '<span class="badge badge-success">Approved</span>'
            )
            ->addColumn('postcard', function(Post $data) {
                if ($data->is_pending != 0) {
                    return '<small class="text-muted">will appear when news approved</small>';
                }
                $dateStr = $data->schedule_post_date ?? $data->created_at->toDateTimeString();
                $formattedDate = enToBn(date('d M Y', strtotime($dateStr)));
                $imageUrl = $data->post_type == 'rss' ? ($data->rss_image ? $data->rss_image : asset('assets/images/nopic.png')) : ($data->image_big ? asset('assets/images/post/'.$data->image_big) : asset('assets/images/nopic.png'));

                return '<a href="javascript:void(0)" class="download-postcard-btn btn btn-info btn-sm" style="background:#145a32; border-color:#145a32;" data-title="'.e($data->title).'" data-image="'.$imageUrl.'" data-date="'.$formattedDate.'"> <i class="fas fa-image"></i> Download</a>';
            })
            ->addColumn('copylink', function(Post $data) {
                if ($data->is_pending != 0) {
                    return '<small class="text-muted">will appear when news approved</small>';
                }
                $categorySlug = !empty($data->category->slug) ? $data->category->slug : 'uncategorized';
                $detailsUrl = route('frontend.postBySubcategory.details', [$categorySlug, $data->slug]);
                return '<a href="javascript:void(0)" class="copy-post-link-btn btn btn-success btn-sm" data-url="'.$detailsUrl.'"> <i class="fas fa-copy"></i> Copy Link</a>';
            })
        
            ->addColumn('action', function(Post $data){
                return '
                <div class="dropdown">
                  <button class="btn btn-secondary dropdown-toggle" type="button" id="actionMenu'.$data->id.'" data-bs-toggle="dropdown" aria-expanded="false">
                    Actions
                  </button>
                  <ul class="dropdown-menu" aria-labelledby="actionMenu'.$data->id.'">
                    <li><a class="dropdown-item" href="'.route('post.edit',$data->id).'">Edit</a></li>
                    <li><a class="dropdown-item" href="'.route('frontend.postBySubcategory.details',[$data->category->slug ?? '','' .$data->slug]).'" target="_blank">View</a></li>
                    <li><a class="dropdown-item" data-href="'.route('post.delete',$data->id).'" data-toggle="modal" data-target="#confirm-delete" style="color: #fff !important;">Delete</a></li>
                  </ul>
                </div>';
            })
            ->addColumn('approved_at', function(Post $data) {
                if (empty($data->approved_at)) {
                    return '<span class="text-muted">N/A</span>';
                }
                try {
                    return \Carbon\Carbon::parse($data->approved_at)->format('d M Y, h:i A');
                } catch (\Exception $e) {
                    return '<span class="text-muted">N/A</span>';
                }
            })
            ->rawColumns(['checkbox','image_big','category_id','language_id','is_approve','approved_at','action', 'postcard', 'copylink'])
            ->make(true);
    }



    
    public function approved()
    {
        return view('admin.post.approved');
    }

    public function rejected()
    {
        $languages = Language::all();
        return view('admin.post.rejected', compact('languages'));
    }

    public function rejectedDatatables(Request $request)
    {
        $input = $request->all();
        $admin = Auth::guard('admin')->user();
        $role  = $admin->role;
    
        $query = Post::with(['category','language','admin','user','rejectedBy'])
            ->where('status', 'true')
            ->where('is_pending', 2);
    
        if ($role && $role->code === 'admin') {
            $query->where('admin_id', $admin->id);
        }
    
        if (!empty($input['lang'])) {
            $query->where('language_id', $input['lang']);
        }
    
        if (!empty($input['category'])) {
            $query->where('category_id', $input['category']);
        }
    
        $query->orderBy('id','desc');
    
        return Datatables::of($query)
            ->addColumn('checkbox', fn($data) =>
                '<input type="checkbox" class="form-check-input m-0 p-0 postCheck" value="'.$data->id.'">'
            )
            ->addColumn('image_big', function(Post $data){
                if($data->post_type == 'rss'){
                    $rss_image = $data->rss_image ?  $data->rss_image : url('assets/images/nopic.png');
                    return '<img src="'.$rss_image.'" alt="Image" style="height:50px;">';
                } else {
                    $image_big = $data->image_big ? url('assets/images/post/'.$data->image_big) : url('assets/images/nopic.png');
                    return '<img src="'.$image_big.'" alt="Image" style="height:50px;">';
                }
            })
            ->editColumn('category_id', fn($data) =>
                $data->category ? '<span class="badge badge-primary">'.$data->category->title.'</span>' : ''
            )
            ->editColumn('language_id', fn($data) =>
                $data->language ? '<span class="badge badge-info">'.$data->language->language.'</span>' : ''
            )
            ->editColumn('admin_id', fn($data) =>
                $data->admin ? $data->admin->name : ($data->user ? $data->user->name : 'Deleted')
            )
            ->addColumn('is_approve', fn($data) =>
                '<span class="badge badge-danger">Rejected</span>'
            )
            ->addColumn('rejected_by', function(Post $data) {
                return $data->rejectedBy ? $data->rejectedBy->name : '<span class="text-muted">N/A</span>';
            })
            ->addColumn('reject_reason', fn($data) =>
                e($data->reject_reason)
            )
            ->addColumn('action', function(Post $data){
                return '
                <div class="godropdown">
                    <button class="go-dropdown-toggle"> Actions<i class="fas fa-chevron-down"></i></button>
                    <div class="action-list">
                        <a href="'.route('post.edit',$data->id).'"> <i class="fas fa-edit"></i> Edit</a>
                        <a href="'.route('frontend.postBySubcategory.details',[$data->category->slug ?? '','' .$data->slug]).'" target="_blank"> <i class="fa fa-info-circle"></i> View</a>
                        <a href="javascript:;" data-href="'.route('post.delete',$data->id).'" data-toggle="modal" data-target="#confirm-delete" class="delete"><i class="fas fa-trash-alt"></i> Delete</a>
                    </div>
                </div>';
            })
            ->rawColumns(['checkbox','image_big','category_id','language_id','is_approve','rejected_by','reject_reason','action'])
            ->make(true);
    }
	
	public function postledger(Request $request){
        return view('admin.post.postledger');
    }


    public function categoryFilter($id){
        $datas = Language::find($id)->categories()->where('parent_id',NULL)->get();
		
        $output = '<option data-href="'.route('post.datatables').'?category=&lang='.$id.'">All</option>';
        foreach($datas as $data){
            $output .= '<option data-href="'.route('post.datatables').'?category='.$data->id.'">'.$data->title.'</option>';
        }
        return $output;
    }
	  public function categoryFilterledger($id){
        $datas = Language::find($id)->categories()->where('parent_id',NULL)->get();
		
        $output = '<option data-href="'.route('post.postledgerdatatables').'?category=&lang='.$id.'">All</option>';
        foreach($datas as $data){
            $output .= '<option data-href="'.route('post.postledgerdatatables').'?category='.$data->id.'">'.$data->title.'</option>';
        }
        return $output;
    }
    public function edit($id){

        $data = Post::find($id);
        $data->load('quiz');
        $categories = Category::where('parent_id','=',NULL)->get();
        $languages = Language::orderBy('id','desc')->get();
        
        $reporterName = $data->reporter_id && $data->admin 
        ? $data->admin->name 
        : ($data->user_id && $data->user ? $data->user->name : 'Deleted');

        $phone_a = $data->reporter_id && $data->admin ? $data->admin->phone : '';
        $phone_u = $data->user_id && $data->user ? $data->user->phone : '';
        $reporterPhone = $phone_a ?: $phone_u;

        $reportType = json_decode($data->user->report_type ?? '[]', true);
        $reportTypeId = $reportType[0] ?? null;
        $reporterArea = $reportTypeId 
            ? getReporterAreaName($data->language_id, $reportTypeId, $data->user) 
            : '';

        $reportCategory = $reportTypeId ? \App\Models\ReportCategory::find($reportTypeId) : null;
        $reportTypeTitle = $reportCategory ? ($data->language_id == 1 ? $reportCategory->title_bn : $reportCategory->title_en) : '';

        if ($data->reporter_id) {
            $reporterQuery = \App\Models\Post::where('admin_id', $data->reporter_id);
        } elseif ($data->user_id) {
            $reporterQuery = \App\Models\Post::where('user_id', $data->user_id);
        } else {
            $reporterQuery = null;
        }

        if ($reporterQuery) {
            $reporterPostCount = (clone $reporterQuery)->count();
            $reporterViews = (clone $reporterQuery)->sum('view_count');
            
            $approvedCount = (clone $reporterQuery)->where('is_pending', 0)->count();
            $pendingCount = (clone $reporterQuery)->where('is_pending', 1)->count();
            $rejectedCount = (clone $reporterQuery)->where('is_pending', 2)->count();
            
            $todayStart = \Carbon\Carbon::today();
            $threeDaysAgo = \Carbon\Carbon::now()->subDays(3);
            $sevenDaysAgo = \Carbon\Carbon::now()->subDays(7);
            
            $todayCount = (clone $reporterQuery)->where('created_at', '>=', $todayStart)->count();
            $threeDaysCount = (clone $reporterQuery)->where('created_at', '>=', $threeDaysAgo)->count();
            $sevenDaysCount = (clone $reporterQuery)->where('created_at', '>=', $sevenDaysAgo)->count();
        } else {
            $reporterPostCount = 0;
            $reporterViews = 0;
            $approvedCount = 0;
            $pendingCount = 0;
            $rejectedCount = 0;
            $todayCount = 0;
            $threeDaysCount = 0;
            $sevenDaysCount = 0;
        }

        if($data->post_type == 'article'){
            return view('admin.article.edit',compact('categories','languages','data','reporterPhone','reporterArea','reporterName','reportTypeTitle','reporterPostCount','reporterViews','approvedCount','pendingCount','rejectedCount','todayCount','threeDaysCount','sevenDaysCount'));
        }
        elseif($data->post_type == 'video')
        {
            return view('admin.video.edit',compact('categories','languages','data','reporterPhone','reporterArea','reporterName','reportTypeTitle','reporterPostCount','reporterViews','approvedCount','pendingCount','rejectedCount','todayCount','threeDaysCount','sevenDaysCount'));
        }
        elseif($data->post_type == 'Sorted List')
        {
            return view('admin.shortlist.edit',compact('categories','languages','data','reporterPhone','reporterArea','reporterName','reportTypeTitle','reporterPostCount','reporterViews','approvedCount','pendingCount','rejectedCount','todayCount','threeDaysCount','sevenDaysCount'));
        }
        elseif($data->post_type == 'Trivia Quiz'){
            return view('admin.quiz.edit',compact('categories','languages','data','reporterPhone','reporterArea','reporterName','reportTypeTitle','reporterPostCount','reporterViews','approvedCount','pendingCount','rejectedCount','todayCount','threeDaysCount','sevenDaysCount'));
        }
        elseif($data->post_type == 'Personality Quiz'){
            return view('admin.pquiz.edit',compact('categories','languages','data','reporterPhone','reporterArea','reporterName','reportTypeTitle','reporterPostCount','reporterViews','approvedCount','pendingCount','rejectedCount','todayCount','threeDaysCount','sevenDaysCount'));
        }
        else{
            return view('admin.audio.edit',compact('categories','languages','data' ,'reporterPhone','reporterArea','reporterName','reportTypeTitle','reporterPostCount','reporterViews','approvedCount','pendingCount','rejectedCount','todayCount','threeDaysCount','sevenDaysCount'));
        }
    }

    public function view($id){
        $data = Post::find($id);
        if($data->post_type == 'article'){
            return view('admin.article.view',compact('data'));
        }
    }

    public function sliderChange($id){
        $data = Post::find($id);

        if($data->is_slider == 1){
           $data->is_slider = 0;
        }else{
            $data->is_slider = 1;
        }
        $data->update();
        return back()->with('success','Slider Status Updated successfully!');
    }

    public function sliderBulk(Request $request){
        $datas =  explode(',',$request->ids);
        foreach($datas as $data){
            $post = Post::findOrFail($data);
            if($post->is_slider == 0){
               $post->update(['is_slider'=> 1]);
            }
        }
        return back()->with('success','Data Updated successfully!');
    }

    public function removesliderBulk(Request $request){
        $datas =  explode(',',$request->ids);
        foreach($datas as $data){
            $post = Post::findOrFail($data);
            if($post->is_slider == 1){
               $post->update(['is_slider'=> 0]);
            }
        }
        return back()->with('success','Data Updated successfully!');
    }

    public function breakingBulk(Request $request){
        $datas =  explode(',',$request->ids);
        foreach($datas as $data){
            $post = Post::findOrFail($data);
            if($post->is_trending == 0){
               $post->update(['is_trending'=> 1]);
            }
        }
        return back()->with('success','Data Updated successfully!');
    }

    public function removebreakingBulk(Request $request){
        $datas =  explode(',',$request->ids);
        foreach($datas as $data){
            $post = Post::findOrFail($data);
            if($post->is_trending == 1){
               $post->update(['is_trending'=> 0]);
            }
        }
        return back()->with('success','Data Updated successfully!');
    }
    public function featureBulk(Request $request){
        $datas =  explode(',',$request->ids);
        foreach($datas as $data){
            $post = Post::findOrFail($data);
            if($post->is_feature == 0){
               $post->update(['is_feature'=> 1]);
            }
        }
        return back()->with('success','Data Updated successfully!');
    }

    public function removefeatureBulk(Request $request){
        $datas =  explode(',',$request->ids);
        foreach($datas as $data){
            $post = Post::findOrFail($data);
            if($post->is_feature == 1){
               $post->update(['is_feature'=> 0]);
            }
        }
        return back()->with('success','Data Updated successfully!');
    }

    public function rightBulk(Request $request){
        $datas =  explode(',',$request->ids);
        foreach($datas as $data){
            $post = Post::findOrFail($data);
            if($post->slider_right == 0){
               $post->update(['slider_right'=> 1]);
            }
        }
        return back()->with('success','Data Updated successfully!');
    }

    public function removerightBulk(Request $request){
        $datas =  explode(',',$request->ids);
        foreach($datas as $data){
            $post = Post::findOrFail($data);
            if($post->slider_right == 1){
               $post->update(['slider_right'=> 0]);
            }
        }
        return back()->with('success','Data Updated successfully!');
    }

    public function trendingChange($id){
        $data = Post::find($id);

        if($data->is_trending == 1){
           $data->is_trending = 0;
        }else{
            $data->is_trending = 1;
        }
        $data->update();
        return back()->with('success','Breaking News Status Updated successfully!');
    }

    public function featureChange($id){
        $data = Post::find($id);

        if($data->is_feature == 1){
           $data->is_feature = 0;
        }else{
            $data->is_feature = 1;
        }
        $data->update();
        return back()->with('success','Data Updated successfully!');
    }

    public function sliderright($id){
        $data = Post::find($id);

        if($data->slider_right == 1){
           $data->slider_right = 0;
        }else{
            $data->slider_right = 1;
        }
        $data->update();
        return back()->with('success','Data Updated successfully!');
    }

    public function pendingChange($id){
        $data = Post::find($id);

        if($data->is_pending == 1){
            $data->is_pending = 0;
            $data->approve_by   = auth()->guard('admin')->id();
            $data->approved_at = now();
            $data->reject_reason = null;
            $data->rejected_by = null;
        }else{
            $data->is_pending = 1;
            $data->approve_by = null;
            $data->approved_at = null;
            $data->reject_reason = null;
            $data->rejected_by = null;
        }
        $data->update();
        return back()->with('success','Pending Status Updated successfully!');
    }

    public function delete($id){
        $data = Post::find($id);
        foreach($data->views as $view){
           $view->delete();
        }
        if($data->post_type == 'audio'){
            @unlink('assets/audios/'.$data->audio);
        }
        if($data->post_type == 'video'){
            @unlink('assets/videos/'.$data->video);
        }
        if($data->post_type == 'Trivia Quiz'){
            if($data->tquizs->count()>0){
                foreach ($data->tquizs as $quiz) {
                   if($quiz->answers->count()>0){
                       foreach($quiz->answers as $answer){
                        @unlink('assets/images/quizanswer/'.$answer->answer_photo);
                        $answer->delete();
                       }
                   }
                   @unlink('assets/images/quiz/'.$quiz->question_photo);
                   $quiz->delete();
                }
            }
            if($data->tresults->count()>0){
                foreach($data->tresults as $tresult){
                    @unlink('assets/images/quizresult/'.$tresult->result_photo);
                    $tresult->delete();
                }
            }
        }

        if($data->post_type == 'Personality Quiz'){
            if($data->pquizs->count()>0){
                foreach ($data->pquizs as $quiz) {
                   if($quiz->answers->count()>0){
                       foreach($quiz->answers as $answer){
                        @unlink('assets/images/panswer/'.$answer->answer_photo);
                        $answer->delete();
                       }
                   }
                   @unlink('assets/images/pquiz/'.$quiz->question_photo);
                   $quiz->delete();
                }
            }

            if($data->presults->count()>0){
                foreach($data->presults as $presult){
                    @unlink('assets/images/presult/'.$presult->result_photo);
                    $presult->delete();
                }
            }
        }

        if($data->post_type == 'Sorted List'){
            if($data->sorts->count()>0){
                foreach($data->sorts as $sort){
                    @unlink('assets/images/sort/'.$sort->item_photo);
                    $sort->delete();
                }
            }
        }

        @unlink('assets/images/post/'.$data->image_big);
        $data->delete();
        $msg = 'Data Successfully Deleted';
        return response()->json($msg);
    }

    public function bulkdelete(Request $request){
        $datas =  explode(',',$request->ids);
        foreach($datas as $data){
            $views = Post::find($data)->views;
            foreach($views as $view){
                $view->delete();
             }
            $post = Post::findOrFail($data);
            if($post->post_type == 'audio'){
                @unlink('assets/audios/'.$data->audio);
            }
            if($post->post_type == 'video'){
                @unlink('assets/videos/'.$data->video);
            }
            if($post->post_type == 'Trivia Quiz'){
                if($post->tquizs->count()>0){
                    foreach ($post->tquizs as $quiz) {
                       if($quiz->answers->count()>0){
                           foreach($quiz->answers as $answer){
                            @unlink('assets/images/quizanswer/'.$answer->answer_photo);
                            $answer->delete();
                           }
                       }
                       @unlink('assets/images/quiz/'.$quiz->question_photo);
                       $quiz->delete();
                    }
                }
                if($post->tresults->count()>0){
                    foreach($post->tresults as $tresult){
                        @unlink('assets/images/quizresult/'.$tresult->result_photo);
                        $tresult->delete();
                    }
                }
            }

            if($post->post_type == 'Personality Quiz'){
                if($post->pquizs->count()>0){
                    foreach ($post->pquizs as $quiz) {
                       if($quiz->answers->count()>0){
                           foreach($quiz->answers as $answer){
                            @unlink('assets/images/panswer/'.$answer->answer_photo);
                            $answer->delete();
                           }
                       }
                       @unlink('assets/images/pquiz/'.$quiz->question_photo);
                       $quiz->delete();
                    }
                }

                if($post->presults->count()>0){
                    foreach($post->presults as $presult){
                        @unlink('assets/images/presult/'.$presult->result_photo);
                        $presult->delete();
                    }
                }
            }

            if($post->post_type == 'Sorted List'){
                if($post->sorts->count()>0){
                    foreach($post->sorts as $sort){
                        @unlink('assets/images/sort/'.$sort->item_photo);
                        $sort->delete();
                    }
                }
            }

            @unlink('assets/images/post/'.$post->image_big);
            $post->delete();
        }
        return back()->with('success','Data Deleted successfully!');
    }
}

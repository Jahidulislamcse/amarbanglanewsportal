<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Language;
use App\Models\Post;
use Datatables;
use Illuminate\Http\Request;


class BreakingController extends Controller
{
   public function __construct()
    {
        $this->middleware('auth');
    }
	
	public function datatables(Request $request){
        $input = $request->all();

        if(isset($input['lang']) || isset($input['category'])){
            if(isset($input['lang'])){
                $datas = Post::whereUserId(auth()->id())
                                ->where('status','=','true')
                                ->where('language_id',$input['lang'])
                                ->where('schedule_post_date', '<=', date('Y-m-d H:i:s'))
								->where('is_trending','=',1)
                                ->orderBy('id','desc');
            }
            if(isset($input['category'])){
                $datas = Post::whereUserId(auth()->id())
                                ->where('status','=','true')
                                ->where('category_id',$input['category'])
                                ->where('schedule_post_date', '<=', date('Y-m-d H:i:s'))
								->where('is_trending','=',1)
                                ->orderBy('id','desc');
            }
        }else{
            $datas = Post::whereUserId(auth()->id())
                            ->where('status','=','true')
                            ->where('schedule_post_date', '<=', date('Y-m-d H:i:s'))
							->where('is_trending','=',1)
                            ->orderBy('id','desc');
        }

        return Datatables::of($datas)
                            ->addColumn('action', function(Post $data) {
                                if($data->post_type=='rss'){
                                    $edit = '';
                                }else{
                                    $edit = '<a href="'.route('user.post.edit',$data->id).'"> <i class="fas fa-edit"></i> Edit</a>';
                                }
								
                                $details = '<a href="'.route('frontend.postBySubcategory.details',[$data->category->slug,$data->slug]).'" target="_blank"> <i class="fa fa-info-circle" aria-hidden="true"></i> View on Frontend</a>';
								
							   $delete = '<a href="javascript:;" data-href="'.route('user.post.delete',$data->id).'" data-toggle="modal" data-target="#confirm-delete" class="delete"><i class="fas fa-trash-alt"></i> Delete</a>';
								
								if($data->is_pending==0){
									$edit=$delete="";
								}
								
                                return '<div class="godropdown"><button class="go-dropdown-toggle"> Actions<i class="fas fa-chevron-down"></i></button><div class="action-list">'.$details.''.$edit.$delete.'</div></div>';
                            })
                            ->editColumn('category_id',function(Post $data){
                                $category_id = $data->category_id ? '<span class="badge badge-primary">'.$data->category->title.'</span>' : '';
                                return $category_id;
                            })
                            ->editColumn('language_id',function(Post $data){
                                $language_id = $data->language_id ? '<span class="badge badge-info">'.$data->language->language.'</span>' : '';
                                return $language_id;
                            })
                            ->addColumn('is_approve',function(Post $data){
                                $is_approve = $data->is_pending == 0 ? '<span class="badge badge-success">approve</span>':'<span class="badge badge-warning">pending</span>';
                                return $is_approve;
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
                            ->rawColumns(['image_big','category_id','language_id','action','is_approve','post_type','created_at'])
                            ->toJson(); //--- Returning Json Data To Client Side

    }
    
    public function index(){
        return view('user.breaking.index');
    }
    
    public function categoryFilter($id){
        $datas = Language::find($id)->categories()->where('parent_id',NULL)->get();
        $output = '<option data-href="'.route('breaking.datatables').'?category=&lang='.$id.'">All</option>';
        foreach($datas as $data){
            $output .= '<option data-href="'.route('breaking.datatables').'?category='.$data->id.'">'.$data->title.'</option>';
        }
        return $output;
    }
}

<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Datatables;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function datatables(){
        $datas = Post::whereUserId(auth()->id())
                            ->where('status','=','true')
                            ->where('schedule_post','=',1)
                            ->where('is_pending','=',0)
                            ->orderBy('id','desc')
                            ->get();

        return Datatables::of($datas)
                            ->addColumn('action', function(Post $data) {
                                $details = '<a href="'.route('frontend.postBySubcategory.details',[$data->category->slug,$data->slug]).'"> <i class="fa fa-info-circle" aria-hidden="true"></i> View on Frontend</a>';
                                return '<div class="godropdown"><button class="go-dropdown-toggle"> Actions<i class="fas fa-chevron-down"></i></button><div class="action-list">'.$details.'<a href="'.route('post.edit',$data->id).'"> <i class="fas fa-edit"></i> Edit</a><a href="javascript:;" data-href="'.route('post.delete',$data->id).'" data-toggle="modal" data-target="#confirm-delete" class="delete"><i class="fas fa-trash-alt"></i> Delete</a></div></div>';
                            })
                            ->editColumn('category_id',function(Post $data){
                                $category_id = $data->category_id ? $data->category->title : '';
                                return $category_id;
                            })
                            ->editColumn('image_big',function(Post $data){
                                $image_big = $data->image_big ? url('assets/images/post/'.$data->image_big):url('assets/images/noimage.png');
                                return '<img src="'.$image_big.'" alt="Image">';
                            })
                            ->editColumn('language_id',function(Post $data){
                                $language_id = $data->language_id ? '<span class="badge badge-info">'.$data->language->language.'</span>' : '';
                                return $language_id;
                            })
                            ->editColumn('post_type',function(Post $data){
                                $post_type = $data->post_type ? '<span class="badge badge-secondary">'.$data->post_type.'</span>': '';
                                return $post_type;
                            })
                            ->rawColumns(['image_big','category_id','language_id','post_type','checkbox','action'])
                            ->toJson(); //--- Returning Json Data To Client Side
    }
    public function index(){
        return view('user.schedule.index');
    }

}

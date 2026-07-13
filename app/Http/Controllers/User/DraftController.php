<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Datatables;
use Illuminate\Http\Request;

class DraftController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function datatables(){
        $datas = Post::whereUserId(auth()->id())
                                    ->where('status','=','draft')
                                    ->orderBy('id','desc')
                                    ->get();

        return Datatables::of($datas)
                            ->addColumn('action', function(Post $data) {
                                $details = '<a href="'.route('frontend.postBySubcategory.details',[$data->category->slug,$data->slug]).'"> <i class="fa fa-info-circle" aria-hidden="true"></i> View on Frontend</a>';
                                return '<div class="godropdown"><button class="go-dropdown-toggle"> Actions<i class="fas fa-chevron-down"></i></button><div class="action-list">'.$details.'<a href="'.route('user.post.edit',$data->id).'"> <i class="fas fa-edit"></i> Edit</a><a href="javascript:;" data-href="'.route('user.post.delete',$data->id).'" data-toggle="modal" data-target="#confirm-delete" class="delete"><i class="fas fa-trash-alt"></i> Delete</a></div></div>';
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
                            ->rawColumns(['image_big','category_id','language_id','post_type','description','action'])
                            ->toJson(); //--- Returning Json Data To Client Side
    }
    public function index(){
        return view('user.draft.index');
    }

    public function draftArticle(){
        $datas = Post::where('post_type','article')
                    ->where('status','draft')
                    ->get();

        foreach($datas as $data){
            if($data->schedule_post_date == Carbon::now()){
               $post = Post::find($data->id);
               $post->schedule_post = 0;
               $post->schedule_post_date = null;
            }else{
                return 'false';
            }
        }
    }

    public function draftAudio(){
        $datas = Post::where('post_type','audio')
                        ->where('status','draft')
                        ->get();
        foreach($datas as $data){
            if($data->schedule_post_date == Carbon::now()){
               $post = Post::find($data->id);
               $post->schedule_post = 0;
               $post->schedule_post_date = null;
            }else{
                return 'false';
            }
        }
    }

    public function draftVideo(){
        $datas = Post::where('post_type','video')
                        ->where('status','draft')
                        ->get();
        foreach($datas as $data){
            if($data->schedule_post_date == Carbon::now()){
               $post = Post::find($data->id);
               $post->schedule_post = 0;
               $post->schedule_post_date = null;
            }else{
                return 'false';
            }
        }
    }
}

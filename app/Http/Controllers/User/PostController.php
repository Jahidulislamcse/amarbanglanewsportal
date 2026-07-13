<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Language;
use App\Models\Post;
use Datatables;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function datatables(Request $request){
        $input = $request->all();


		$q = Post::whereUserId(auth()->id());
		
						if(isset($input['lang'])){
								 $q->where('language_id',$input['lang']);
							}
							 if(isset($input['category'])){
								$q->where('category_id',$input['category']);
							}
						 if(isset($input['type'])){
							$q->where('post_type',$input['type']);
						}else{
							
							$q->where('status','=','true');

							$q->where('schedule_post_date', '<=', date('Y-m-d H:i:s'));
						}
						 $q ->orderBy('id','desc');

        

        return Datatables::of($q)
                            ->addColumn('action', function(Post $data) {
                                if($data->post_type=='rss'){
                                    $edit = '';
                                }else{
                                    $edit = '<a href="'.route('user.post.edit',$data->id).'"> <i class="fas fa-edit"></i> Edit</a>';
                                }
                                $details = '<a href="'.route('frontend.postBySubcategory.details',[$data->category->slug,$data->slug]).'" target="_blank"> <i class="fa fa-info-circle" aria-hidden="true"></i> See News</a>';
								
								 $delete = '<a href="javascript:;" data-href="'.route('user.post.delete',$data->id).'" data-toggle="modal" data-target="#confirm-delete" class="delete"><i class="fas fa-trash-alt"></i> Delete</a>';
								
								if($data->is_pending==0){
									$edit=$delete="";
								}
								
                                return '<div class="godropdown"><button class="go-dropdown-toggle"> Actions<i class="fas fa-chevron-down"></i></button><div class="action-list">'.$details.''.$edit.$delete.'</div></div>';
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
                                $detailsUrl = route('frontend.postBySubcategory.details', [$data->category->slug, $data->slug]);
                                return '<a href="javascript:void(0)" class="copy-post-link-btn btn btn-success btn-sm" data-url="'.$detailsUrl.'"> <i class="fas fa-copy"></i> Copy Link</a>';
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
                                if ($data->is_pending == 2) {
                                    $is_approve = '<span class="badge badge-danger">rejected</span>';
                                    if ($data->reject_reason) {
                                        $is_approve .= '<br><small class="text-danger" title="'.e($data->reject_reason).'">Reason: '.e($data->reject_reason).'</small>';
                                    }
                                } else {
                                    $is_approve = $data->is_pending == 0 ? '<span class="badge badge-success">approve</span>':'<span class="badge badge-warning">pending</span>';
                                }
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
                            ->rawColumns(['image_big','category_id','language_id','action','is_approve','post_type','created_at', 'postcard', 'copylink'])
                            ->toJson(); //--- Returning Json Data To Client Side

    }

    public function index(Request $request){

        $type=$request->type;
        return view('user.post.index',compact('type'));
    }

    public function categoryFilter($id){
        $datas = Language::find($id)->categories()->where('parent_id',NULL)->get();
        $output = '<option data-href="'.route('user.post.datatables').'?category=&lang='.$id.'">All</option>';
        foreach($datas as $data){
            $output .= '<option data-href="'.route('user.post.datatables').'?category='.$data->id.'">'.$data->title.'</option>';
        }
        return $output;
    }

    public function edit($id){
        $data = Post::find($id);
        $categories = Category::where('parent_id','=',NULL)->get();
        $languages = Language::orderBy('id','desc')->get();

        if($data->post_type == 'article'){
            return view('user.article.edit',compact('categories','languages','data'));
        }
        elseif($data->post_type == 'video')
        {
            return view('user.video.edit',compact('categories','languages','data'));
        }
        elseif($data->post_type == 'Sorted List')
        {
            return view('user.shortlist.edit',compact('categories','languages','data'));
        }
        elseif($data->post_type == 'Trivia Quiz'){
            return view('user.quiz.edit',compact('categories','languages','data'));
        }
        elseif($data->post_type == 'Personality Quiz'){
            return view('user.pquiz.edit',compact('categories','languages','data'));
        }
        else{
            return view('user.audio.edit',compact('categories','languages','data'));
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
        }else{
            $data->is_pending = 1;
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

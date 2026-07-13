<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Language;
use App\Models\VideoCategory;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Brian2694\Toastr\Facades\Toastr;
use Image;
use Illuminate\Support\Str;

class VideoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create(){
        $datas = Category::where('parent_id','=',NULL)->get();
        $languages = Language::orderBy('id','desc')->get();
        return view('user.video.create',compact('datas','languages'));
    }

    public function language($id){
		
        $datas = VideoCategory::where('language_id',$id)->get();
        $output = '<option value="">Please Select a Category *</option>';
        foreach($datas as $data){
            $output .= '<option value="'.$data->id.'">'.$data->title.'</option>';
        }
        return $output;
    }

    public function subcategory($id){

        $datas = Category::find($id)->child;
        $output = '<option value="">Please Select a SubCategory (if any)</option>';
        foreach($datas as $data){
            $output .= '<option value="'.$data->id.'">'.$data->title.'</option>';
        }
        return $output;
    }

    public function subcategoryUpdate($id,$y){
        $datas = Category::find($id)->child;
        $post = Post::find($y);
        $output = '<option value="">Please Select a SubCategory (if any)</option>';
        foreach($datas as $data){
            if($data->id == $post->subcategories_id){
                $msg = 'selected';
            }else{
                $msg = '';
            }
            $output .= '<option value="'.$data->id.'" '.$msg.'>'.$data->title.'</option>';
        }
        return $output;
    }


    public function slugCreate(Request $request){
        $data = 1;
        $val =  $request->title;
        $output = slug_create($val);
        return $output;
    }

    public function slugCheck(Request $request){
        $val =  $request->slug;
        $output = Str::slug($val);
        $result = Post::where('slug','=',$output)->exists();
        if(!$result){
            return $output;
        }else{
            $output2 = $output.'-'.Str::random(3).rand(0,999).time();
            return $output2;
        }
    }

    public function store(Request $request){
        $rules = [
            'title' => 'required',
            'slug' => 'required|unique:posts',
            'image_big' => 'image|mimes:jpeg,png,jpg,gif,svg',
            'description' => 'required',
            'category_id' => 'required',
        ];

        $validator = Validator::make($request->all(),$rules);
        if($validator->fails()){
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }

        $data  = new Post();
        $input = $request->all();
        if($file = $request->file('image_big')){
            $img = Image::make($file->getRealPath())->resize(780,438);
            $thumbnail = time().Str::random(8).'.jpg';
            $img->save(base_path().'/../assets/images/post/'.$thumbnail);
            $input['image_big'] = $thumbnail;
        }
        if($video = $request->file('video')){
            $videoName = time().$video->getClientOriginalName();
            $video->move('assets/videos/',$videoName);
            $input['video'] = $videoName;
        }


        $input['user_id']   = auth()->id();
        $input['is_pending'] = 1;

        if($request->embed_video){
            $input['embed_video']   = $request->embed_video;
            @unlink('assets/videos/'.$data->video);
            $input['video'] = '';
        }
		$input['created_at'] = date('Y-m-d H:i:s');
        if($request->draft == 1){
            $input['status'] = 'draft';
        }else{
            $input['status'] = 'true';
        }

        if($date = $request->schedule_post_date){
            $input['schedule_post_date'] = $date;
        }else{
			 $input['schedule_post_date'] = date('Y-m-d H:i:s');;
		}
		
        $input['post_type'] = 'video';
		$user = auth()->user();
		$input['division_id'] = $user->division_id;
		
        $data->fill($input)->save();

        $msg = 'Video Added Successfully';
        return response()->json($msg);
    }

    public function languageOnUpdate( $x, $y){
        $datas = Language::find($x)->categories()->where('parent_id','=',NULL)->get();
        $post = Post::find($y);
        $output = '<option value="">Please Select a Category *</option>';
        foreach($datas as $data){
            if($data->id == $post->category_id){
                $msg = 'selected';
            }else{
                $msg = '';
            }
            $output .= '<option value="'.$data->id.'" '.$msg.'>'.$data->title.'</option>';
        }
        return $output;
    }

    public function update(Request $request,$id){
        $rules = [
            'title' => 'required',
            'slug' => 'required|unique:posts,slug,'.$id,
            'image_big' => 'image|mimes:jpeg,png,jpg,gif,svg',
            'description' => 'required',
            'category_id' => 'required',
        ];
        $validator = Validator::make($request->all(),$rules);
        if($validator->fails()){
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }

        $data  = Post::findOrFail($id);
        $input = $request->all();

        if($file = $request->file('image_big')){
            $img = Image::make($file->getRealPath())->resize(780,438);
            $thumbnail = time().Str::random(8).'.jpg';
            $img->save(base_path().'/../assets/images/post/'.$thumbnail);
            @unlink('assets/images/post/'.$data->image_big);
            $input['image_big'] = $thumbnail;
        }

        if($request->hasFile('video')){
            $video = $request->file('video');
            $videoName = time().$video->getClientOriginalName();
            $video->move('assets/videos/',$videoName);
            @unlink('assets/videos/'.$data->video);
            $input['video'] = $videoName;
            $input['embed_video'] = '';
        }

        if($request->draft == 1){
            $input['status'] = 'draft';
        }else{
            $input['status'] = 'true';
        }

        if($date = $request->schedule_post_date){
            $input['schedule_post_date'] = $date;
        }

        if($request->embed_video){
            $input['embed_video']   = $request->embed_video;
            @unlink('assets/videos/'.$data->video);
            $input['video'] = '';
        }

        $input['post_type'] = 'video';
        $data->update($input);

        $msg = 'Video Updated Successfully';
        return response()->json($msg);
    }
}

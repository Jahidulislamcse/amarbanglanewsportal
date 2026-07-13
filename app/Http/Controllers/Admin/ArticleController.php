<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Gallery;
use App\Models\Language;
use App\Models\Post;
use App\Models\NewsCover;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Brian2694\Toastr\Facades\Toastr;
use Image;
use Purifier;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    //Create article page
    public function create(){
        $datas = Category::where('parent_id','=',NULL)->get();
        $languages = Language::orderBy('id','desc')->get();
        $covers = NewsCover::orderBy('id','desc')
                        ->paginate(50);
        return view('admin.article.create',compact('datas','languages', 'covers'));
    }
    
    public function searchCoverImages(Request $request)
    {
        $search = $request->search;
    
        $covers = NewsCover::where('title', 'LIKE', "%{$search}%")
            ->orderBy('id', 'desc')
            ->limit(30)
            ->get();
    
        return response()->json($covers);
    }

    //fetch categories under the language
    public function language($id){
        $datas = Language::find($id)->categories()->where('parent_id','=',NULL)->get();
        $output = '<option value="">Please Select a Category *</option>';
        foreach($datas as $data){
            $output .= '<option value="'.$data->id.'">'.$data->title.'</option>';
        }
        return $output;
    }

    //fetch subcategories under category
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
        $output = slug_create($val); //slug_create() from helper.php
        return $output;
    }

    public function store(Request $request)
    {
        // dd($request->all());
    
        $rules = [
            'language_id' => 'required',
            'title' => 'required',
            'image_title' => 'nullable|max:255',
            'slug' => 'required|unique:posts',
            'image_big' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
            'cover_image_id' => 'nullable|exists:news_covers,id',
            'image_note' => 'nullable|max:100',
            'description' => 'required',
            'category_id' => 'required',
            'death_count' => 'nullable|integer|min:0',
            'gallery.*' => 'image|mimes:jpeg,png,jpg,gif,svg',

            'quiz_question' => 'nullable|max:255',
            'option_1' => 'nullable|max:255',
            'option_2' => 'nullable|max:255',
            'option_3' => 'nullable|max:255',
            'option_4' => 'nullable|max:255',
            'correct_answer' => 'nullable|in:1,2,3,4',
        ];
    
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->getMessageBag()->toArray()
            ]);
        }
    
        $data = new Post();
    
        $input = $request->all();

        if ($file = $request->file('image_big')) {
    
            $img = Image::make($file->getRealPath())->resize(780, 438);
    
            $thumbnail = time() . Str::random(8) . '.jpg';
    
            $img->save(base_path() . '/../assets/images/post/' . $thumbnail);
    
            $input['image_big'] = $thumbnail;
    
            $newsCover = NewsCover::create([
                'title'       => $input['image_title'],
                'cover_image' => $thumbnail,
                'user_id'     => auth()->id(),
                'admin_id'    => null,
            ]);
    
            $input['cover_image_id'] = $newsCover->id;
        }

        if (!$request->hasFile('image_big') && $request->cover_image_id) {
    
            $cover = NewsCover::find($request->cover_image_id);
    
            if ($cover) {
                $input['image_big'] = $cover->cover_image;
            }
        }
    
        $input['admin_id'] = auth()->guard('admin')->id();
    
        $input['is_pending'] = 0;
    
        if ($request->draft == 1) {
    
            $input['status'] = 'draft';
    
        } else {
    
            $input['approve_by'] = auth()->guard('admin')->id();
            $input['approved_at'] = now();
    
            $input['status'] = 'true';
        }
    
        $input['created_at'] = date('Y-m-d H:i:s');
    
        $hasSchedulePermission = auth()->guard('admin')->user()->IsSuper() || (auth()->guard('admin')->user()->role && (strtolower(auth()->guard('admin')->user()->role->name) === 'admin' || auth()->guard('admin')->user()->sectionCheck('schedule_post')));
        if ($hasSchedulePermission && $request->schedule_post == 1) {
            if ($date = $request->schedule_post_date) {
                $input['schedule_post_date'] = $date;
            } else {
                $input['schedule_post_date'] = date('Y-m-d H:i:s');
            }
        } else {
            $input['schedule_post'] = 0;
            $input['schedule_post_date'] = date('Y-m-d H:i:s');
        }
    
        $input['post_type'] = 'article';

        $data->fill($input)->save();
    
        $lastid = $data->id;

        if (
            !empty($request->quiz_question) &&
            !empty($request->option_1) &&
            !empty($request->option_2) &&
            !empty($request->option_3) &&
            !empty($request->option_4) &&
            !empty($request->correct_answer)
        ) {
    
            \App\Models\PostQuiz::create([
    
                'post_id' => $lastid,
    
                'question' => $request->quiz_question,
    
                'option_1' => $request->option_1,
    
                'option_2' => $request->option_2,
    
                'option_3' => $request->option_3,
    
                'option_4' => $request->option_4,
    
                'correct_answer' => $request->correct_answer,
    
                'is_active' => 1,
            ]);
        }

        if ($files = $request->file('gallery')) {
    
            foreach ($files as $key => $file) {
    
                $gallery = new Gallery;
    
                $img = Image::make($file->getRealPath())->resize(780, 438);
    
                $thumbnail = time() . Str::random(8) . '.jpg';
    
                $img->save(base_path() . '/../assets/images/galleries/' . $thumbnail);
    
                $gallery['photo'] = $thumbnail;
    
                $gallery['post_id'] = $lastid;
    
                $gallery->save();
            }
        }
    
        $msg = 'Article Added Successfully';
        
        $categorySlug = ($data->category && !empty($data->category->slug)) ? $data->category->slug : 'uncategorized';
        $detailsUrl = route('frontend.postBySubcategory.details', [$categorySlug, $data->slug]);
        
        $dateStr = $data->schedule_post_date ?? $data->created_at->toDateTimeString();
        $formattedDate = enToBn(date('d M Y', strtotime($dateStr)));
        $imageUrl = $data->post_type == 'rss' ? ($data->rss_image ? $data->rss_image : asset('assets/images/nopic.png')) : ($data->image_big ? asset('assets/images/post/'.$data->image_big) : asset('assets/images/nopic.png'));

        $msg = 'Article Added Successfully.<br><br>';
        $msg .= '<div class="post-success-actions" style="margin-top: 10px; display: flex; flex-direction: column; gap: 12px; background: #f8fafc; padding: 15px; border-radius: 8px; border: 1px solid #cbd5e1; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">';
        
        // Copy Link Button
        $msg .= '  <div style="display: flex; align-items: center; justify-content: space-between; gap: 10px;">';
        $msg .= '    <span style="font-weight: 600; color: #334155; font-size: 14px;">Post Link:</span>';
        $msg .= '    <div style="display: flex; gap: 5px; flex-grow: 1; max-width: 450px;">';
        $msg .= '      <input type="text" readonly value="'.$detailsUrl.'" style="flex-grow: 1; padding: 4px 8px; border: 1px solid #cbd5e1; border-radius: 4px; font-size: 13px; background: #fff;" onclick="this.select();">';
        $msg .= '      <a href="javascript:void(0)" class="copy-post-link-btn btn btn-success btn-sm" data-url="'.$detailsUrl.'" style="white-space: nowrap; padding: 4px 10px; font-size: 12px;"> <i class="fas fa-copy"></i> Copy Link</a>';
        $msg .= '    </div>';
        $msg .= '  </div>';
        
        // Download Postcard Button
        $msg .= '  <div style="display: flex; align-items: center; justify-content: space-between; gap: 10px; border-top: 1px solid #e2e8f0; padding-top: 12px;">';
        $msg .= '    <span style="font-weight: 600; color: #334155; font-size: 14px;">News Postcard:</span>';
        $msg .= '    <a href="javascript:void(0)" class="download-postcard-btn btn btn-info btn-sm" style="background:#145a32; border-color:#145a32; padding: 4px 10px; font-size: 12px;" data-title="'.e($data->title).'" data-image="'.$imageUrl.'" data-date="'.$formattedDate.'"> <i class="fas fa-image"></i> Download Postcard</a>';
        $msg .= '  </div>';
        
        $msg .= '</div>';
    
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
            'language_id' => 'required',
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
        $admin = Auth::guard('admin')->user()->role_id; //take admin role id
        $data  = Post::find($id);

        $input = $request->all();

        if($file = $request->file('image_big')){
            $img = Image::make($file->getRealPath())->resize(780,438);
            $thumbnail = time().Str::random(8).'.jpg';
            $img->save(base_path().'/../assets/images/post/'.$thumbnail);
            @unlink('assets/images/post/'.$data->image_big);
            $input['image_big'] = $thumbnail;
        }

        if($request->is_pending == 1){
            $input['is_pending'] = 0;
            $input['approve_by'] = auth()->guard('admin')->id();
            $input['approved_at'] = now();
            $input['reject_reason'] = null;
            $input['rejected_by'] = null;
        } elseif ($request->is_pending == 2) {
            $input['is_pending'] = 2;
            $input['reject_reason'] = $request->reject_reason;
            $input['rejected_by'] = auth()->guard('admin')->id();
            $input['approve_by'] = null;
            $input['approved_at'] = null;
        } else {
            $input['is_pending'] = 1;
            $input['reject_reason'] = null;
            $input['approve_by'] = null;
            $input['approved_at'] = null;
            $input['rejected_by'] = null;
        }
        
        if($request->draft == 1){
            $input['status'] = 'draft';
        }else{
            $input['status'] = 'true';
        }

        $hasSchedulePermission = auth()->guard('admin')->user()->IsSuper() || (auth()->guard('admin')->user()->role && (strtolower(auth()->guard('admin')->user()->role->name) === 'admin' || auth()->guard('admin')->user()->sectionCheck('schedule_post')));
        if ($hasSchedulePermission && $request->schedule_post == 1) {
            if ($date = $request->schedule_post_date) {
                $input['schedule_post_date'] = $date;
            }
        } else {
            $input['schedule_post'] = 0;
        }
        $input['post_type'] = 'article';
        $data->update($input);
        
        $quiz = \App\Models\PostQuiz::where('post_id', $id)->first();

        $hasQuiz =
            $request->filled('quiz_question') &&
            $request->filled('option_1') &&
            $request->filled('option_2') &&
            $request->filled('option_3') &&
            $request->filled('option_4') &&
            $request->filled('correct_answer');
    
        if ($hasQuiz) {
    
            $quizData = [
                'question' => $request->quiz_question,
                'option_1' => $request->option_1,
                'option_2' => $request->option_2,
                'option_3' => $request->option_3,
                'option_4' => $request->option_4,
                'correct_answer' => $request->correct_answer,
                'is_active' => 1,
            ];
    
            if ($quiz) {
                $quiz->update($quizData);
            } else {
                \App\Models\PostQuiz::create([
                    'post_id' => $id,
                    'question' => $request->quiz_question,
                    'option_1' => $request->option_1,
                    'option_2' => $request->option_2,
                    'option_3' => $request->option_3,
                    'option_4' => $request->option_4,
                    'correct_answer' => $request->correct_answer,
                    'is_active' => 1,
                ]);
            }
    
        } else {
    
            if ($quiz) {
                $quiz->delete();
            }
        }
    
        $msg = 'Data Updated successfully';
        return response()->json($msg);
    }

}

<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Gallery;
use App\Models\Language;
use App\Models\NewsCover;
use App\Models\Post;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Image;
use Purifier;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function create(){
        $data['datas'] = Category::where('parent_id','=',NULL)->get();
        $data['languages'] = Language::orderBy('id','desc')->get();
        $user = auth()->user();

        // Check user's previously purchased products
        $purchasedProductIds = \App\Models\OrderItem::whereHas('order.payment', function ($q) {
            $q->where('status', 'paid');
        })
        ->whereHas('order', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })
        ->pluck('product_id')
        ->unique()
        ->toArray();

        $package1Products = Product::where('package', 'package1')->where('is_active', true)->get();
        
        // Auto-heal package1_purchased flag if they already bought all package1 items
        $package1ProductIds = $package1Products->pluck('id')->toArray();
        if (!empty($package1ProductIds)) {
            $missingIds = array_diff($package1ProductIds, $purchasedProductIds);
            if (empty($missingIds) && $user->package1_purchased == 0) {
                $user->update(['package1_purchased' => 1]);
            }
        }

        $postCount = Post::where('user_id', $user->id)->where('is_pending', 0)->count();

        $data['blockUser'] = ($postCount >= 1 && !$user->package1_purchased);
        $data['package1Products'] = $package1Products;
        $data['purchasedProductIds'] = $purchasedProductIds;

        return view('user.article.create', $data);
    }

    public function language($id){
        $datas = Language::find($id)->categories()->where('parent_id','=',NULL)->get();
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
            if($post && $data->id == $post->subcategories_id){
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


    public function store(Request $request){

        // Server-side package gate guard using flag
        $user      = auth()->user();
        $postCount = Post::where('user_id', $user->id)->where('is_pending', 0)->count();
        if ($postCount >= 10 && !$user->package1_purchased) {
            return response()->json(['errors' => ['package' => ['আপনার সাংবাদিকতার পরিচয়কে আরও পেশাদার করুন। অফিসিয়াল আইডি কার্ড, ভিজিটিং কার্ডসহ প্রয়োজনীয় সাংবাদিকতা সামগ্রী এবং আরও সংবাদ প্রকাশের সুবিধা পেতে নিচের প্যাকেজটি অর্ডার করুন।']]]);
        }

        $rules = [
            'language_id' => 'required',
            'title' => 'required',
            'slug' => 'required|unique:posts',
            'image_big' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
            'image_note' => 'nullable | max:100',
            'description' => 'required',
            'category_id' => 'required',
            'gallery.*' => 'image|mimes:jpeg,png,jpg,gif,svg',
        ];
        $validator = Validator::make($request->all(),$rules);
        if($validator->fails()){
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }

        $data  = new Post();
        $input = $request->all();

        if ($file = $request->file('image_big')) {
            $img = Image::make($file->getRealPath())->resize(780,438);
            $thumbnail = time().Str::random(8).'.jpg';
            $img->save(base_path().'/../assets/images/post/'.$thumbnail);
        
            $input['image_big'] = $thumbnail;
        }
    

        $input['user_id']   = auth()->id();
        $input['is_pending'] = 1;

        if($request->draft == 1){
            $input['status'] = 'draft';
        }else{
            $input['status'] = 'true';
        }


        if($date = $request->schedule_post_date){
            $input['schedule_post_date'] = $date;
        }else{
			$input['schedule_post_date'] = date('Y-m-d H:i:s');
		}
		
		$input['created_at'] = date('Y-m-d H:i:s');
        $input['post_type'] = 'article';
		$user = auth()->user();
		
		$input['division_id'] = $user->division_id;
		$input['district_id'] = $user->district_id;
		$input['thana_id'] = $user->thana_id;
		$input['union_id'] = $user->union_id;
		

        $data->fill($input)->save();

        $lastid = $data->id;


        if ($files = $request->file('gallery')){
            foreach ($files as  $key => $file){
                    $gallery = new Gallery;
                    $img = Image::make($file->getRealPath())->resize(780,438);
                    $thumbnail = time().Str::random(8).'.jpg';
                    $img->save(base_path().'/../assets/images/galleries/'.$thumbnail);
                    $gallery['photo'] = $thumbnail;
                    $gallery['post_id'] = $lastid;
                    $gallery->save();
            }
        }
        $category_slug = '';
        if ($request->filled('subcategories_id')) {
            $subcat = Category::find($request->subcategories_id);
            if ($subcat) {
                $category_slug = $subcat->slug;
            }
        }
        
        if (empty($category_slug) && $request->filled('category_id')) {
            $cat = Category::find($request->category_id);
            if ($cat) {
                $category_slug = $cat->slug;
            }
        }
        
        if (empty($category_slug)) {
            $category_slug = 'news';
        }
        
        $post_slug = $data->slug;
        $news_link = route('frontend.postBySubcategory.details', [$category_slug, $post_slug]);

        $msg = 'Article Added Successfully. <br><strong>Note:</strong> Admin অনুমোদনের পর <strong>News >> All News</strong> পেজ থেকে সংবাদটির লিংক কপি করতে এবং নিউজ কার্ড ডাউনলোড করতে পারবেন। <br><br><strong style="color:#d32f2f;font-size:16px;">📢 অনুগ্রহ করে সংবাদটির লিংক ও নিউজ কার্ডটি আপনার Facebook অ্যাকাউন্ট বা পেজে শেয়ার করুন, যাতে এটি আপনার বন্ধু, অনুসারী ও পরিচিতদের মধ্যে আরও বেশি পৌঁছায় এবং অধিক ভিউ অর্জন করে।</strong>';        
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

        $data  = Post::find($id);
        $input = $request->all();

        if($file = $request->file('image_big')){
            $img = Image::make($file->getRealPath())->resize(780,438);
            $thumbnail = time().Str::random(8).'.jpg';
            $img->save(base_path().'/../assets/images/post/'.$thumbnail);
            @unlink('assets/images/post/'.$data->image_big);
            $input['image_big'] = $thumbnail;
        }


        if($request->draft == 1){
            $input['status'] = 'draft';
        }else{
            $input['status'] = 'true';
        }

        if($date = $request->schedule_post_date){
            $input['schedule_post_date'] = $date;
        }
        $input['post_type'] = 'article';
        $data->update($input);

        $msg = 'Data Updated successfully';
        return response()->json($msg);
    }
}

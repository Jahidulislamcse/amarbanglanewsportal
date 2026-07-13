<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ImageAlbum;
use App\Models\Language;
use Datatables;
use App\Models\ImageCategory;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class ImageAlbumController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    
    public function datatables(){
       $datas = ImageAlbum::orderBy('id','desc')->get();
       return Datatables::of($datas)
                            ->addColumn('action', function(ImageAlbum $data) {
                                return '<div class="action-list"><a data-href="'.route('image.album.edit',$data->id) .'" class="edit" data-toggle="modal" data-target="#modal1"> <i class="fas fa-edit"></i>Edit</a><a href="javascript:;" data-href="'.route('image.album.delete',$data->id).'" data-toggle="modal" data-target="#confirm-delete" class="delete"><i class="fas fa-trash-alt"></i></a></div>';
                            })
                            ->editColumn('photo',function(ImageAlbum $data){
                                $photo = $data->photo ? url('assets/images/image-album/'.$data->photo) : url('assets/images/noimage.png');
                                return '<img src="'.$photo.'" alt="">';
                            })
                            ->editColumn('language_id',function(ImageAlbum $data){
                                return $language = $data->language_id ? $data->language->language : '';
                            })
							->editColumn('image_category_id',function(ImageAlbum $data){
                                return $category = $data->image_category_id ? $data->category->name : '';
                            })
                            ->rawColumns(['action','photo','language_id'])
                            ->toJson();
    }
    public function index(){
        return view('admin.image-album.index');
    }
	
	  public function language($id){
        $datas = ImageCategory::where('language_id',$id)->get();
        $output = '<option value="">Category *</option>';
        foreach($datas as $data){
            $output .= '<option value="'.$data->id.'">'.$data->name.'</option>';
        }
		
        return $output;
    }
	  public function languageOnUpdate( $x, $y){
        $datas = ImageCategory::where('language_id',$x)->get();
        $output = '<option value="">Category *</option>';
        foreach($datas as $data){
            if($data->id == $y){
                $msg = 'selected';
            }else{
                $msg = '';
            }
            $output .= '<option value="'.$data->id.'" '.$msg.'>'.$data->name.'</option>';
        }
        return $output;
    }
    public function create(){
		
		$category = ImageCategory::pluck('name','id');
        $datas = Language::orderBy('id','desc')->get();
        return view('admin.image-album.create',compact('datas','category'));
    }
    public function store(Request $request){
        $rules = [
            'language_id' => 'required',
			'album_name' => 'required',
			'album_description' => 'required',
			'image_category_id' => 'required',
			'slug' => 'required',
            'photo'        => 'mimes:jpeg,jpg,png,svg',
            'album_name' => 'required|unique:image_albums'
        ];

        $validator = Validator::make($request->all(),$rules);
        if($validator->fails()){
            return response()->json(array('errors'=>$validator->getMessageBag()->toArray()));
        }

        $data  = new ImageAlbum();
        $input = $request->all();
        if($request->hasFile('photo')){
            $file = $request->file('photo');
            $name = time().$file->getClientOriginalName();
            $file->move('assets/images/image-album/',$name);
            $input['photo'] = $name;
        }
		
		$input['user_id'] = Auth::user()->id;;
        $data->fill($input)->save();
        $msg = 'Data Added Successfully';
        return response()->json($msg);
    }
    public function edit($id){
        $data = ImageAlbum::find($id);
		$category = ImageCategory::pluck('name','id');
        $languages = Language::orderBy('id','desc')->get();
        return view('admin.image-album.edit',compact('data','languages','category'));
    }
    public function update(Request $request,$id){
        $rules = [
            'language_id' => 'required',
			'album_name' => 'required',
			'album_description' => 'required',
			'image_category_id' => 'required',
			'slug' => 'required',
            'photo'        => 'mimes:jpeg,jpg,png,svg',
            'album_name' => 'required|unique:image_albums,album_name,'.$id
        ];

        $validator = Validator::make($request->all(),$rules);
        if($validator->fails()){
            return response()->json(array('errors'=>$validator->getMessageBag()->toArray()));
        }
        $data = ImageAlbum::find($id);
        $input = $request->all();
        if($request->hasFile('photo')){
            $file = $request->file('photo');
            $name = time().$file->getClientOriginalName();
            $file->move('assets/images/image-album/',$name);
            @unlink('assets/images/image-album/'.$data->photo);
            $input['photo'] = $name;
        }
		$input['user_id'] = Auth::user()->id;
        $data->update($input);
        $msg = 'Data Updated Successfully';
        return response()->json($msg);
    }
    public function delete($id){
        $data = ImageAlbum::find($id);
        @unlink('assets/images/image-album/'.$data->photo);
        $data->delete();
        $msg = 'Data Deleted Successfully';
        return response()->json($msg);
    }
}

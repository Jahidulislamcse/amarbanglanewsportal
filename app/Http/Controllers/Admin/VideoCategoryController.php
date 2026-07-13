<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\VideoCategory;
use App\Models\Language;
use App\Models\Post;
use Datatables;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Brian2694\Toastr\Facades\Toastr;

class VideoCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function categoriesDatatables(){
        $datas = VideoCategory::orderBy('id','desc')
                            ->get();

        //--- Integrating This Collection Into Datatables
        return Datatables::of($datas)
                            ->addColumn('action', function(VideoCategory $data) {
                                return '<div class="action-list"><a data-href="'.route('videocategories.categoriesEdit',$data->id) .'" class="edit" data-toggle="modal" data-target="#modal1"> <i class="fas fa-edit"></i>Edit</a><a href="javascript:;" data-href="'.route('videocategories.categoriesDelete',$data->id).'" data-toggle="modal" data-target="#confirm-delete" class="delete"><i class="fas fa-trash-alt"></i></a></div>';
                            })

                            ->editColumn('status',function(VideoCategory $data){
                                $status = $data->status == 1 ? '<span class="btn btn-success btn-sm" style="border-radius: 15px;"> active</span>' : '<span class="btn btn-danger btn-sm" style="border-radius: 15px;"> Inactive</span>';
                                return $status;
                            })
                            ->editColumn('language_id',function(VideoCategory $data){
                                return $language_id = $data->language_id ? $data->language->language : '';
                            })

                            ->rawColumns(['action','status','language_id'])
                            ->toJson(); //--- Returning Json Data To Client Side
    }
    public function videocategories(){
        return view('admin.videocategory.index');
    }
    public function categoriesCreate(){
        $cat_id = VideoCategory::all()->map(function ($item, $key) {
            return $item->id;
        });
        $datas = Language::orderBy('id','desc')->get();
        return view('admin.videocategory.create',compact('datas','cat_id'));
    }

    public function categoriesStore(Request $request){

        //validation area
        $rules = [
            'language_id'=>'required',
            'title' => 'required',
            'status' => 'required',
        ];

        $validator = Validator::make($request->all(),$rules);
        if($validator->fails()){
            return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
        }
        //validation area end

       
            $data  = new VideoCategory();
            $input = $request->all(); //taking all fields value

            $data->fill($input)->save(); //Save data to the videocategories table
            $msg = 'New Data Added Successfully.';
            return response()->json($msg);
        

    }
    public function categoriesEdit($id){

        $data      = VideoCategory::findOrFail($id); //find a record by id
        $languages = Language::all();
        return view('admin.videocategory.edit',compact('data','languages'));
    }

    public function categoriesUpdate(Request $request,$id){

        //validation area
        $rules = [
            'language_id'=>'required',
            'title' => 'required',
            'status' => 'required',
        ];

        $validator = Validator::make($request->all(),$rules);
        if($validator->fails()){
            return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
        }
        //validation area end

        $data  = VideoCategory::findOrFail($id); //find a record by id
        $input = $request->all();  //taking all fields value
        $data->update($input);     //Update data
        $msg = 'Data Updated Successfully';
        return response()->json($msg);
    }
    public function categoriesDelete($id){
        $data  = VideoCategory::findOrFail($id);
     
        $data->delete();
        $msg = 'Data Successfully Deleted';
        return response()->json($msg);
    }
}

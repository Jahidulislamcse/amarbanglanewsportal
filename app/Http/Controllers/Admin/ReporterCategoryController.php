<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ReportCategory;
use App\Models\Language;
use App\Models\Post;
use Datatables;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Brian2694\Toastr\Facades\Toastr;

class ReporterCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function categoriesDatatables(){
        $datas = Reportcategory::orderBy('id','desc')
                            ->get();

        //--- Integrating This Collection Into Datatables
        return Datatables::of($datas)
                            ->addColumn('action', function(Reportcategory $data) {
                                return '<div class="action-list"><a data-href="'.route('reportcategories.categoriesEdit',$data->id) .'" class="edit" data-toggle="modal" data-target="#modal1"> <i class="fas fa-edit"></i>Edit</a><a href="javascript:;" data-href="'.route('reportcategories.categoriesDelete',$data->id).'" data-toggle="modal" data-target="#confirm-delete" class="delete"><i class="fas fa-trash-alt"></i></a></div>';
                            })

                            ->editColumn('status',function(Reportcategory $data){
                                $status = $data->status == 1 ? '<span class="btn btn-success btn-sm" style="border-radius: 15px;"> active</span>' : '<span class="btn btn-danger btn-sm" style="border-radius: 15px;"> Inactive</span>';
                                return $status;
                            })


                            ->rawColumns(['action','status'])
                            ->toJson(); //--- Returning Json Data To Client Side
    }
    public function reportcategories(){
        return view('admin.reportcategory.index');
    }
    public function categoriesCreate(){
        $cat_id = Reportcategory::all()->map(function ($item, $key) {
            return $item->id;
        });
        $datas = Language::orderBy('id','desc')->get();
        return view('admin.reportcategory.create',compact('datas','cat_id'));
    }

    public function categoriesStore(Request $request){

        //validation area
        $rules = [

            'title_bn' => 'required',
			'title_en' => 'required',
            'status' => 'required',
        ];

        $validator = Validator::make($request->all(),$rules);
        if($validator->fails()){
            return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
        }
        //validation area end

       
            $data  = new Reportcategory();
            $input = $request->all(); //taking all fields value

            $data->fill($input)->save(); //Save data to the reportcategories table
            $msg = 'New Data Added Successfully.';
            return response()->json($msg);
        

    }
    public function categoriesEdit($id){

        $data      = Reportcategory::findOrFail($id); //find a record by id
        $languages = Language::all();
        return view('admin.reportcategory.edit',compact('data','languages'));
    }

    public function categoriesUpdate(Request $request,$id){

        //validation area
        $rules = [
           'title_bn' => 'required',
		   'title_en' => 'required',
        ];

        $validator = Validator::make($request->all(),$rules);
        if($validator->fails()){
            return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
        }
        //validation area end

        $data  = Reportcategory::findOrFail($id); //find a record by id
        $input = $request->all();  //taking all fields value
        $data->update($input);     //Update data
        $msg = 'Data Updated Successfully';
        return response()->json($msg);
    }
    public function categoriesDelete($id){
        $data  = Reportcategory::findOrFail($id);
     
        $data->delete();
        $msg = 'Data Successfully Deleted';
        return response()->json($msg);
    }
}

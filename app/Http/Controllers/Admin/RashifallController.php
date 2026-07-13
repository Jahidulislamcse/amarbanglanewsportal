<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\Rashifall;
use Datatables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Brian2694\Toastr\Facades\Toastr;

use Carbon\Carbon;

class RashifallController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
     public function datatables_old(){
        $datas = Rashifall::orderBy('id','desc')->get();
        return Datatables::of($datas)
                            ->addColumn('action', function(Rashifall $data) {
                                return '<div class="action-list"><a href="'.route('admin.rashifall.edit',$data->id) .'" class="edit" > <i class="fas fa-edit"></i>Edit</a><a href="javascript:;" data-href="'.route('admin.rashifall.delete',$data->id).'" data-toggle="modal" data-target="#confirm-delete" class="delete"><i class="fas fa-trash-alt"></i></a></div>';
                            })
                            ->editColumn('language_id',function(Rashifall $data){
                                return $language = $data->language_id ? $data->language->language : '';
                            })
							 ->editColumn('type',function(Rashifall $data){
								if($data->type>52){
									
									if($data->type==53){
										$type="Year";
									}elseif($data->type==54){
										$type="Month";
									}elseif($data->type==55){
										$type="Day";
									}
									
								}else{
									$type="Week";
								}
								return $type;
                            })
							 ->editColumn('description_1',function(Rashifall $data){
								
								return strip_tags($data->description_1);
                            })
							 ->editColumn('description_2',function(Rashifall $data){
								
								return strip_tags($data->description_2);
                            })
							 ->editColumn('description_3',function(Rashifall $data){
								
								return strip_tags($data->description_3);
                            })
							 ->editColumn('description_4',function(Rashifall $data){
								
								return strip_tags($data->description_4);
                            })
							 ->editColumn('description_5',function(Rashifall $data){
								
								return strip_tags($data->description_5);
                            })
							 ->editColumn('description_6',function(Rashifall $data){
								
								return strip_tags($data->description_6);
                            })
							 ->editColumn('description_7',function(Rashifall $data){
								
								return strip_tags($data->description_7);
                            })
							 ->editColumn('description_8',function(Rashifall $data){
								
								return strip_tags($data->description_8);
                            })
							 ->editColumn('description_9',function(Rashifall $data){
								
								return strip_tags($data->description_9);
                            })
							 ->editColumn('description_10',function(Rashifall $data){
								
								return strip_tags($data->description_10);
                            })
							 ->editColumn('description_11',function(Rashifall $data){
								
								return strip_tags($data->description_11);
                            })
							 ->editColumn('description_12',function(Rashifall $data){
								
								return strip_tags($data->description_12);
                            })
                            ->rawColumns(['action','type','description_1','description_2','description_3','description_4','description_5','description_6','description_7','description_8','description_9','description_10','description_11','description_12','language_id'])
                            ->toJson();
    }
    public function datatables(){
        $datas = Rashifall::orderBy('id','desc')->get();
        return Datatables::of($datas)
                            ->addColumn('action', function(Rashifall $data) {
                                return '<div class="action-list"><a href="'.route('admin.rashifall.edit',$data->id) .'" class="edit" > <i class="fas fa-edit"></i>Edit</a><a href="javascript:;" data-href="'.route('admin.rashifall.delete',$data->id).'" data-toggle="modal" data-target="#confirm-delete" class="delete"><i class="fas fa-trash-alt"></i></a></div>';
                            })
                            ->editColumn('language_id',function(Rashifall $data){
                                return $language = $data->language_id ? $data->language->language : '';
                            })
							->editColumn('type',function(Rashifall $data){
								if($data->type>52){
									if($data->type==53){
										return "Yearly";
									}elseif($data->type==54){
										return "Monthly";
									}elseif($data->type==55){
										return "Daily";
									}
								}else{
									return "Weekly (Week " . $data->type . ")";
								}
								return "Week";
                            })
                            ->editColumn('date', function(Rashifall $data) {
                                if (empty($data->date)) {
                                    return '';
                                }
                                try {
                                    if ($data->type == 55) { // Daily
                                        return \Carbon\Carbon::parse($data->date)->format('d M Y');
                                    } elseif ($data->type == 54) { // Monthly
                                        return \Carbon\Carbon::parse($data->date . '-01')->format('M Y');
                                    }
                                } catch (\Exception $e) {
                                    // fallback
                                }
                                return $data->date;
                            })
                            ->addColumn('preview', function(Rashifall $data) {
                                return mb_strimwidth(strip_tags($data->description_1), 0, 80, '...');
                            })
                            ->rawColumns(['action','type','language_id','preview','date'])
                            ->toJson();
    }
    public function index(){
        return view('admin.rashifall.index');
    }
    public function create(){
        $languages = Language::orderBy('id','desc')->get();
        return view('admin.rashifall.create',compact('languages'));
    }
    //slug create 
    public function slugCreate(Request $request){
        $data = 1;
        $val =  $request->title;
        $output = slug_create($val); //slug_create() from helper.php
        return $output;
    }

    public function store(Request $request){
        $rules = [
            'language_id' => 'required',
            'year' => 'required',
        ];
        $validator = Validator::make($request->all(),$rules);
        if($validator->fails()){
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
		
	

        $data = new Rashifall();
        $input = $request->all();
		$date="";
		if($input['year']){
			$date=$input['year'];
			$input['type']=53;
		}
		if($input['month']){
			$date=$input['year'].'-'.$input['month'];
			$input['type']=54;
		}
		if($input['day']){
			$date=$input['year'].'-'.$input['month'].'-'.$input['day'];
			$input['type']=55;
		}
			
		if($input['week']){
			$date = $input['year'];
			$input['type']=$input['week'];
		}
		
		unset($input['year']);
		unset($input['month']);
		unset($input['day']);
		unset($input['week']);
		unset($input['year']);
		

		$input['date']=$date;
		$input['user_id']=Auth::guard('admin')->user()->id;
		
		$descriptions=$input['description'];
		unset($input['description']);
		foreach($descriptions as $kl=>$description){
			$input['description_'.$kl]=$description;
		}
		

        $data->fill($input)->save();
        $msg = 'Data Added Successfully';
        return response()->json($msg);
    }
    public function edit($id){
        $data = Rashifall::find($id);
        $languages = Language::orderBy('id','desc')->get();
        return view('admin.rashifall.edit',compact('data','languages'));
    }
    public function update(Request $request,$id){
        $rules = [
            'language_id' => 'required',
            'year' => 'required',
        ];
        $validator = Validator::make($request->all(),$rules);
        if($validator->fails()){
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        
        $data = Rashifall::find($id);
        $input = $request->all();
		$date="";
		if($input['year']){
			$date=$input['year'];
			$input['type']=53;
		}
		if($input['month']){
			$date=$input['year'].'-'.$input['month'];
			$input['type']=54;
		}
		if($input['day']){
			$date=$input['year'].'-'.$input['month'].'-'.$input['day'];
			$input['type']=55;
		}
			
		if($input['week']){
			$date = $input['year'];
			$input['type']=$input['week'];
		}
		
		unset($input['year']);
		unset($input['month']);
		unset($input['day']);
		unset($input['week']);
		unset($input['year']);

		$input['date']=$date;
		$input['user_id']=Auth::guard('admin')->user()->id;
		$descriptions=$input['description'];
		unset($input['description']);
		foreach($descriptions as $kl=>$description){
			$input['description_'.$kl]=$description;
		}
	
		
        $data->update($input);
        $msg = 'Data Updated Successfully';
        return response()->json($msg);
    }
    public function delete($id){
        $data = Rashifall::find($id)->delete();
        $msg = 'Data Deleted Successfully';
        return response()->json($msg);
    }
}

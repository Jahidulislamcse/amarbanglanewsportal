<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Thana;
use App\Models\District;
use Datatables;
use Illuminate\Support\Facades\Validator;

class ThanaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function datatables()
    {
        $datas = Thana::orderBy('id', 'desc')->get();

        return Datatables::of($datas)
            ->addColumn('action', function (Thana $data) {
                return '<div class="action-list">' .
                    '<a href="javascript:;" class="edit-location-btn" data-toggle="modal" data-target="#modal1" ' .
                    'data-id="' . $data->id . '" ' .
                    'data-district_id="' . $data->district_id . '" ' .
                    'data-name="' . htmlspecialchars($data->name, ENT_QUOTES, 'UTF-8') . '" ' .
                    'data-bn_name="' . htmlspecialchars($data->bn_name, ENT_QUOTES, 'UTF-8') . '" ' .
                    'data-url="' . htmlspecialchars($data->url, ENT_QUOTES, 'UTF-8') . '">' .
                    ' <i class="fas fa-edit"></i>Edit</a>' .
                    '<a href="javascript:;" data-href="' . route('admin.thanas.delete', $data->id) . '" data-toggle="modal" data-target="#confirm-delete" class="delete"><i class="fas fa-trash-alt"></i></a>' .
                    '</div>';
            })
            ->editColumn('district_id', function (Thana $data) {
                $district = District::find($data->district_id);
                if ($district) {
                    return $district->name . ($district->is_city_corporation ? ' (City Corp)' : ' (District)');
                }
                return 'N/A';
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    public function index()
    {
        $districts = District::orderBy('name')->get();
        return view('admin.thana.index', compact('districts'));
    }

    public function store(Request $request)
    {
        $rules = [
            'district_id' => 'required',
            'name' => 'required|max:255',
            'bn_name' => 'required|max:255',
            'url' => 'nullable|max:255',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
        }

        $data = new Thana();
        $input = $request->all();
        $data->fill($input)->save();

        $msg = 'Thana/Upazila added successfully.';
        return response()->json($msg);
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'district_id' => 'required',
            'name' => 'required|max:255',
            'bn_name' => 'required|max:255',
            'url' => 'nullable|max:255',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
        }

        $data = Thana::findOrFail($id);
        $input = $request->all();
        $data->update($input);

        $msg = 'Thana/Upazila updated successfully.';
        return response()->json($msg);
    }

    public function delete($id)
    {
        $data = Thana::findOrFail($id);
        $data->delete();

        $msg = 'Thana/Upazila deleted successfully.';
        return response()->json($msg);
    }
}

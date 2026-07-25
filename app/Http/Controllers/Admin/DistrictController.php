<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Division;
use Datatables;
use Illuminate\Support\Facades\Validator;

class DistrictController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function datatables()
    {
        $datas = District::orderBy('id', 'desc')->get();

        return Datatables::of($datas)
            ->addColumn('action', function (District $data) {
                return '<div class="action-list">' .
                    '<a href="javascript:;" class="edit-location-btn" data-toggle="modal" data-target="#modal1" ' .
                    'data-id="' . $data->id . '" ' .
                    'data-division_id="' . $data->division_id . '" ' .
                    'data-name="' . htmlspecialchars($data->name, ENT_QUOTES, 'UTF-8') . '" ' .
                    'data-bn_name="' . htmlspecialchars($data->bn_name, ENT_QUOTES, 'UTF-8') . '" ' .
                    'data-url="' . htmlspecialchars($data->url, ENT_QUOTES, 'UTF-8') . '" ' .
                    'data-is_city_corporation="' . $data->is_city_corporation . '">' .
                    ' <i class="fas fa-edit"></i>Edit</a>' .
                    '<a href="javascript:;" data-href="' . route('admin.districts.delete', $data->id) . '" data-toggle="modal" data-target="#confirm-delete" class="delete"><i class="fas fa-trash-alt"></i></a>' .
                    '</div>';
            })
            ->editColumn('division_id', function (District $data) {
                $division = Division::find($data->division_id);
                return $division ? $division->name : 'N/A';
            })
            ->editColumn('is_city_corporation', function (District $data) {
                return $data->is_city_corporation == 1 
                    ? '<span class="badge badge-success">City Corporation</span>' 
                    : '<span class="badge badge-secondary">District</span>';
            })
            ->rawColumns(['action', 'is_city_corporation'])
            ->toJson();
    }

    public function index()
    {
        $divisions = Division::orderBy('name')->get();
        return view('admin.district.index', compact('divisions'));
    }

    public function store(Request $request)
    {
        $rules = [
            'division_id' => 'required',
            'name' => 'required|max:255',
            'bn_name' => 'required|max:255',
            'url' => 'nullable|max:255',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
        }

        $data = new District();
        $input = $request->all();
        $input['is_city_corporation'] = $request->has('is_city_corporation') ? 1 : 0;
        $data->fill($input)->save();

        $msg = 'District/City Corporation added successfully.';
        return response()->json($msg);
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'division_id' => 'required',
            'name' => 'required|max:255',
            'bn_name' => 'required|max:255',
            'url' => 'nullable|max:255',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
        }

        $data = District::findOrFail($id);
        $input = $request->all();
        $input['is_city_corporation'] = $request->has('is_city_corporation') ? 1 : 0;
        $data->update($input);

        $msg = 'District/City Corporation updated successfully.';
        return response()->json($msg);
    }

    public function delete($id)
    {
        $data = District::findOrFail($id);
        $data->delete();

        $msg = 'District/City Corporation deleted successfully.';
        return response()->json($msg);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Unions;
use App\Models\Thana;
use Datatables;
use Illuminate\Support\Facades\Validator;

class WardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function datatables()
    {
        $datas = Unions::orderBy('id', 'desc')->get();

        return Datatables::of($datas)
            ->addColumn('action', function (Unions $data) {
                return '<div class="action-list">' .
                    '<a href="javascript:;" class="edit-location-btn" data-toggle="modal" data-target="#modal1" ' .
                    'data-id="' . $data->id . '" ' .
                    'data-upazilla_id="' . $data->upazilla_id . '" ' .
                    'data-name="' . htmlspecialchars($data->name, ENT_QUOTES, 'UTF-8') . '" ' .
                    'data-bn_name="' . htmlspecialchars($data->bn_name, ENT_QUOTES, 'UTF-8') . '" ' .
                    'data-url="' . htmlspecialchars($data->url, ENT_QUOTES, 'UTF-8') . '">' .
                    ' <i class="fas fa-edit"></i>Edit</a>' .
                    '<a href="javascript:;" data-href="' . route('admin.wards.delete', $data->id) . '" data-toggle="modal" data-target="#confirm-delete" class="delete"><i class="fas fa-trash-alt"></i></a>' .
                    '</div>';
            })
            ->editColumn('upazilla_id', function (Unions $data) {
                $thana = Thana::find($data->upazilla_id);
                if ($thana) {
                    $district = $thana->district_id ? \App\Models\District::find($thana->district_id) : null;
                    $parentInfo = $district 
                        ? ' (' . $district->name . ($district->is_city_corporation ? ' City Corp' : ' Dist') . ')'
                        : '';
                    return $thana->name . $parentInfo;
                }
                return 'N/A';
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    public function index()
    {
        $thanas = Thana::orderBy('name')->get();
        return view('admin.ward.index', compact('thanas'));
    }

    public function store(Request $request)
    {
        $rules = [
            'upazilla_id' => 'required',
            'name' => 'required|max:255',
            'bn_name' => 'required|max:255',
            'url' => 'nullable|max:255',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
        }

        $data = new Unions();
        $input = $request->all();
        $data->fill($input)->save();

        $msg = 'Ward/Union added successfully.';
        return response()->json($msg);
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'upazilla_id' => 'required',
            'name' => 'required|max:255',
            'bn_name' => 'required|max:255',
            'url' => 'nullable|max:255',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
        }

        $data = Unions::findOrFail($id);
        $input = $request->all();
        $data->update($input);

        $msg = 'Ward/Union updated successfully.';
        return response()->json($msg);
    }

    public function delete($id)
    {
        $data = Unions::findOrFail($id);
        $data->delete();

        $msg = 'Ward/Union deleted successfully.';
        return response()->json($msg);
    }
}

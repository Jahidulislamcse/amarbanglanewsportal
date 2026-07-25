<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Thana;
use App\Models\Unions;
use App\Models\Division;
use Datatables;
use Illuminate\Support\Facades\Validator;

class LocationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $divisions = Division::orderBy('name')->get();
        // Only show/manage districts that are City Corporations
        $districts = District::where('is_city_corporation', 1)->orderBy('name')->get();
        // Only show thanas that belong to City Corporations
        $thanas = Thana::whereIn('district_id', $districts->pluck('id'))->orderBy('name')->get();
        
        return view('admin.locations.index', compact('divisions', 'districts', 'thanas'));
    }

    /* =========================================================================
     * CITY CORPORATIONS (Managed in districts table)
     * ========================================================================= */

    public function districtsDatatables()
    {
        // Only show districts where is_city_corporation is 1
        $datas = District::where('is_city_corporation', 1)->orderBy('id', 'desc')->get();

        return Datatables::of($datas)
            ->addColumn('action', function (District $data) {
                return '<div class="action-list">' .
                    '<a href="javascript:;" class="edit-district-btn" ' .
                    'data-id="' . $data->id . '" ' .
                    'data-division_id="' . $data->division_id . '" ' .
                    'data-name="' . htmlspecialchars($data->name, ENT_QUOTES, 'UTF-8') . '" ' .
                    'data-bn_name="' . htmlspecialchars($data->bn_name, ENT_QUOTES, 'UTF-8') . '" ' .
                    'data-url="' . htmlspecialchars($data->url, ENT_QUOTES, 'UTF-8') . '">' .
                    ' <i class="fas fa-edit"></i>Edit</a>' .
                    '<a href="javascript:;" data-href="' . route('admin.districts.delete', $data->id) . '" data-toggle="modal" data-target="#confirm-delete" class="delete"><i class="fas fa-trash-alt"></i></a>' .
                    '</div>';
            })
            ->editColumn('division_id', function (District $data) {
                $division = Division::find($data->division_id);
                return $division ? $division->name : 'N/A';
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    public function districtsStore(Request $request)
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

        try {
            $data = new District();
            $input = $request->all();
            $input['is_city_corporation'] = 1; // Always force to true
            $data->fill($input)->save();

            $msg = 'City Corporation added successfully.';
            return response()->json($msg);
        } catch (\Exception $e) {
            return response()->json(['errors' => [$e->getMessage()]]);
        }
    }

    public function districtsUpdate(Request $request, $id)
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

        try {
            $data = District::findOrFail($id);
            $input = $request->all();
            $input['is_city_corporation'] = 1; // Always force to true
            $data->update($input);

            $msg = 'City Corporation updated successfully.';
            return response()->json($msg);
        } catch (\Exception $e) {
            return response()->json(['errors' => [$e->getMessage()]]);
        }
    }

    public function districtsDelete($id)
    {
        try {
            $data = District::findOrFail($id);
            $data->delete();

            $msg = 'City Corporation deleted successfully.';
            return response()->json($msg);
        } catch (\Exception $e) {
            return response()->json(['errors' => [$e->getMessage()]]);
        }
    }

    /* =========================================================================
     * THANAS / UPAZILAS (Under City Corporations)
     * ========================================================================= */

    public function thanasDatatables()
    {
        // Only show thanas that belong to City Corporations
        $cityCorpIds = District::where('is_city_corporation', 1)->pluck('id');
        $datas = Thana::whereIn('district_id', $cityCorpIds)->orderBy('id', 'desc')->get();

        return Datatables::of($datas)
            ->addColumn('action', function (Thana $data) {
                return '<div class="action-list">' .
                    '<a href="javascript:;" class="edit-thana-btn" ' .
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
                return $district ? $district->name : 'N/A';
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    public function thanasStore(Request $request)
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

        try {
            $data = new Thana();
            $input = $request->all();
            $data->fill($input)->save();

            $msg = 'Thana added successfully.';
            return response()->json($msg);
        } catch (\Exception $e) {
            return response()->json(['errors' => [$e->getMessage()]]);
        }
    }

    public function thanasUpdate(Request $request, $id)
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

        try {
            $data = Thana::findOrFail($id);
            $input = $request->all();
            $data->update($input);

            $msg = 'Thana updated successfully.';
            return response()->json($msg);
        } catch (\Exception $e) {
            return response()->json(['errors' => [$e->getMessage()]]);
        }
    }

    public function thanasDelete($id)
    {
        try {
            $data = Thana::findOrFail($id);
            $data->delete();

            $msg = 'Thana deleted successfully.';
            return response()->json($msg);
        } catch (\Exception $e) {
            return response()->json(['errors' => [$e->getMessage()]]);
        }
    }

    /* =========================================================================
     * WARDS / UNIONS (Under City Corporation Thanas)
     * ========================================================================= */

    public function wardsDatatables()
    {
        // Only show wards that belong to City Corporation Thanas
        $cityCorpIds = District::where('is_city_corporation', 1)->pluck('id');
        $thanaIds = Thana::whereIn('district_id', $cityCorpIds)->pluck('id');
        $datas = Unions::whereIn('upazilla_id', $thanaIds)->orderBy('id', 'desc')->get();

        return Datatables::of($datas)
            ->addColumn('action', function (Unions $data) {
                return '<div class="action-list">' .
                    '<a href="javascript:;" class="edit-ward-btn" ' .
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
                    $district = District::find($thana->district_id);
                    $parentInfo = $district ? ' (' . $district->name . ')' : '';
                    return $thana->name . $parentInfo;
                }
                return 'N/A';
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    public function wardsStore(Request $request)
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

        try {
            $data = new Unions();
            $input = $request->all();
            $data->fill($input)->save();

            $msg = 'Ward added successfully.';
            return response()->json($msg);
        } catch (\Exception $e) {
            return response()->json(['errors' => [$e->getMessage()]]);
        }
    }

    public function wardsUpdate(Request $request, $id)
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

        try {
            $data = Unions::findOrFail($id);
            $input = $request->all();
            $data->update($input);

            $msg = 'Ward updated successfully.';
            return response()->json($msg);
        } catch (\Exception $e) {
            return response()->json(['errors' => [$e->getMessage()]]);
        }
    }

    public function wardsDelete($id)
    {
        try {
            $data = Unions::findOrFail($id);
            $data->delete();

            $msg = 'Ward deleted successfully.';
            return response()->json($msg);
        } catch (\Exception $e) {
            return response()->json(['errors' => [$e->getMessage()]]);
        }
    }
}

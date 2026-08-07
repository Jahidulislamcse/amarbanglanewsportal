<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Notice;
use Datatables;
use Illuminate\Support\Facades\Validator;

class NoticeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function datatables()
    {
        $datas = Notice::orderBy('id', 'desc')->get();
        return Datatables::of($datas)
            ->addColumn('action', function (Notice $data) {
                return '<div class="action-list"><a href="javascript:;" data-href="' . route('admin.notice.edit', $data->id) . '" class="edit" data-toggle="modal" data-target="#modal1"> <i class="fas fa-edit"></i>Edit</a><a href="javascript:;" data-href="' . route('admin.notice.delete', $data->id) . '" data-toggle="modal" data-target="#confirm-delete" class="delete"><i class="fas fa-trash-alt"></i></a></div>';
            })
            ->editColumn('status', function (Notice $data) {
                return $data->status == 1 ? '<span class="btn btn-sm btn-success">Active</span>' : '<span class="btn btn-sm btn-danger">Inactive</span>';
            })
            ->editColumn('image', function (Notice $data) {
                $url = $data->image ? asset('assets/images/notices/' . $data->image) : asset('assets/images/noimage.png');
                return '<img src="' . $url . '" alt="Image" style="max-height: 40px;">';
            })
            ->rawColumns(['action', 'status', 'image'])
            ->toJson();
    }

    public function index()
    {
        return view('admin.notice.index');
    }

    public function create()
    {
        return view('admin.notice.create');
    }

    public function store(Request $request)
    {
        $rules = [
            'title' => 'required|max:255',
            'text' => 'required',
            'image' => 'nullable|mimes:jpeg,jpg,png,svg,webp|max:2048',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }

        $data = new Notice();
        $input = $request->all();

        if ($file = $request->file('image')) {
            $name = time() . '_' . $file->getClientOriginalName();
            $file->move('assets/images/notices', $name);
            $input['image'] = $name;
        }

        $data->fill($input)->save();
        $msg = 'Notice Added Successfully';
        return response()->json($msg);
    }

    public function edit($id)
    {
        $data = Notice::find($id);
        return view('admin.notice.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'title' => 'required|max:255',
            'text' => 'required',
            'image' => 'nullable|mimes:jpeg,jpg,png,svg,webp|max:2048',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }

        $data = Notice::find($id);
        $input = $request->all();

        if ($file = $request->file('image')) {
            $name = time() . '_' . $file->getClientOriginalName();
            $file->move('assets/images/notices', $name);
            if ($data->image && file_exists('assets/images/notices/' . $data->image)) {
                @unlink('assets/images/notices/' . $data->image);
            }
            $input['image'] = $name;
        }

        $data->update($input);
        $msg = 'Notice Updated Successfully';
        return response()->json($msg);
    }

    public function delete($id)
    {
        $data = Notice::find($id);
        if ($data->image && file_exists('assets/images/notices/' . $data->image)) {
            @unlink('assets/images/notices/' . $data->image);
        }
        $data->delete();
        $msg = 'Notice Deleted Successfully';
        return response()->json($msg);
    }
}

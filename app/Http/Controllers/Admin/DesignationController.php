<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Designation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DesignationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $designations = Designation::orderBy('name')->paginate(20);
        return view('admin.salary.designations.index', compact('designations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
        ]);

        $data = $request->all();
        $data['code'] = Str::slug($request->name);
        $data['name_bn'] = $request->name;
        $data['section'] = json_encode([]);

        Designation::create($data);

        return redirect()->route('admin.designations.index')
            ->with('success', 'Designation (Role) created successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $id,
        ]);

        $designation = Designation::findOrFail($id);
        
        $data = $request->all();
        $data['code'] = Str::slug($request->name);
        $data['name_bn'] = $request->name;

        $designation->update($data);

        return redirect()->route('admin.designations.index')
            ->with('success', 'Designation (Role) updated successfully.');
    }

    public function destroy($id)
    {
        $designation = Designation::findOrFail($id);
        if ($designation->employees()->count() > 0) {
            return redirect()->route('admin.designations.index')
                ->with('unsuccess', 'Cannot delete designation (role) as it is currently assigned to one or more employees.');
        }
        $designation->delete();

        return redirect()->route('admin.designations.index')
            ->with('success', 'Designation deleted successfully.');
    }
}

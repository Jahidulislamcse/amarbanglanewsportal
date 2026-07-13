<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Designation;
use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $employees = Employee::with('designation')->orderBy('name')->paginate(20);
        return view('admin.salary.employees.index', compact('employees'));
    }

    public function create()
    {
        $designations = Designation::orderBy('name')->get();
        return view('admin.salary.employees.create', compact('designations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:100|unique:admins,email',
            'phone' => 'nullable|string|max:100',
            'designation_id' => 'required|exists:roles,id',
            'salary' => 'required|numeric|min:0',
            'account_details' => 'nullable|string',
            'password' => 'required|string|min:6',
        ]);

        $data = $request->all();
        $data['role_id'] = $request->designation_id;
        $data['password'] = bcrypt($request->password);
        $data['display_password'] = $request->password;
        $data['verify'] = 1;
        $data['status'] = 1;

        Employee::create($data);

        return redirect()->route('admin.employees.index')
            ->with('success', 'Employee (Admin) added successfully.');
    }

    public function edit($id)
    {
        $employee = Employee::findOrFail($id);
        $designations = Designation::orderBy('name')->get();
        return view('admin.salary.employees.edit', compact('employee', 'designations'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:100|unique:admins,email,' . $id,
            'phone' => 'nullable|string|max:100',
            'designation_id' => 'required|exists:roles,id',
            'salary' => 'required|numeric|min:0',
            'account_details' => 'nullable|string',
            'password' => 'nullable|string|min:6',
        ]);

        $employee = Employee::findOrFail($id);
        $data = $request->all();
        $data['role_id'] = $request->designation_id;
        
        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
            $data['display_password'] = $request->password;
        } else {
            unset($data['password']);
        }

        $employee->update($data);

        return redirect()->route('admin.employees.index')
            ->with('success', 'Employee (Admin) updated successfully.');
    }

    public function destroy($id)
    {
        if ($id == 1) {
            return redirect()->route('admin.employees.index')
                ->with('unsuccess', 'Cannot delete primary super admin account.');
        }
        $employee = Employee::findOrFail($id);
        $employee->delete();

        return redirect()->route('admin.employees.index')
            ->with('success', 'Employee deleted successfully.');
    }
}

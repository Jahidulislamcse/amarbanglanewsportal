<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdvanceSalary;
use App\Models\Employee;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class AdvanceSalaryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $advances = AdvanceSalary::with('employee')->orderByDesc('payment_date')->paginate(20);
        return view('admin.salary.advance.index', compact('advances'));
    }

    public function create()
    {
        $employees = Employee::orderBy('name')->get();
        return view('admin.salary.advance.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:admins,id',
            'year' => 'required|integer|min:2020|max:2100',
            'month' => 'required|integer|min:1|max:12',
            'amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $data = $request->all();
        $data['status'] = 'approved';
        AdvanceSalary::create($data);

        return redirect()->route('admin.advance-salaries.index')
            ->with('success', 'Advance payment recorded successfully.');
    }

    public function destroy($id)
    {
        $advance = AdvanceSalary::findOrFail($id);
        $advance->delete();

        return redirect()->route('admin.advance-salaries.index')
            ->with('success', 'Advance payment record deleted.');
    }

    public function downloadReceipt($id)
    {
        $advance = AdvanceSalary::with('employee.designation')->findOrFail($id);
        $gs = \App\Models\GeneralSettings::find(1);
        $pdf = Pdf::loadView('admin.salary.receipts.advance', compact('advance', 'gs'));
        return $pdf->download('advance_receipt_' . $advance->id . '.pdf');
    }

    public function approve($id)
    {
        $advance = AdvanceSalary::findOrFail($id);
        $advance->update(['status' => 'approved', 'payment_date' => date('Y-m-d')]);

        return redirect()->route('admin.advance-salaries.index')
            ->with('success', 'Advance request approved successfully.');
    }

    public function reject($id)
    {
        $advance = AdvanceSalary::findOrFail($id);
        $advance->update(['status' => 'rejected']);

        return redirect()->route('admin.advance-salaries.index')
            ->with('success', 'Advance request rejected.');
    }

    public function myAdvanceIndex()
    {
        $employeeId = auth('admin')->id();
        $advances = AdvanceSalary::where('employee_id', $employeeId)->orderByDesc('year')->orderByDesc('month')->paginate(20);
        return view('admin.salary.my_salaries.advance_index', compact('advances'));
    }

    public function myAdvanceCreate()
    {
        return view('admin.salary.my_salaries.advance_create');
    }

    public function myAdvanceStore(Request $request)
    {
        $request->validate([
            'year' => 'required|integer|min:2020|max:2100',
            'month' => 'required|integer|min:1|max:12',
            'amount' => 'required|numeric|min:0.01',
            'notes' => 'nullable|string',
        ]);

        $employeeId = auth('admin')->id();

        AdvanceSalary::create([
            'employee_id' => $employeeId,
            'year' => $request->year,
            'month' => $request->month,
            'amount' => $request->amount,
            'payment_date' => date('Y-m-d'),
            'notes' => $request->notes,
            'status' => 'pending',
        ]);

        return redirect()->route('admin.my-advance-salaries.index')
            ->with('success', 'Advance salary request submitted successfully.');
    }
}

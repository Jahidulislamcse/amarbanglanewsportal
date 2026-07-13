<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdvanceSalary;
use App\Models\Employee;
use App\Models\Salary;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class SalaryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index(Request $request)
    {
        $year = $request->input('year', date('Y'));
        $month = $request->input('month', date('n')); // 1 to 12

        $employees = Employee::with(['designation', 'salaries' => function ($q) use ($year, $month) {
            $q->where('year', $year)->where('month', $month);
        }, 'advanceSalaries' => function ($q) use ($year, $month) {
            $q->where('year', $year)->where('month', $month)->where('status', 'approved');
        }])->orderBy('name')->get();

        return view('admin.salary.salaries.index', compact('employees', 'year', 'month'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:admins,id',
            'year' => 'required|integer',
            'month' => 'required|integer|min:1|max:12',
        ]);

        $employee = Employee::findOrFail($request->employee_id);
        $year = $request->year;
        $month = $request->month;

        $existing = Salary::where('employee_id', $employee->id)
            ->where('year', $year)
            ->where('month', $month)
            ->first();

        if ($existing) {
            return redirect()->back()->with('unsuccess', 'Salary already paid for this month.');
        }

        $advancePaid = AdvanceSalary::where('employee_id', $employee->id)
            ->where('year', $year)
            ->where('month', $month)
            ->where('status', 'approved')
            ->sum('amount');

        $basicSalary = $employee->salary;
        $netSalary = max(0, $basicSalary - $advancePaid);

        Salary::create([
            'employee_id' => $employee->id,
            'year' => $year,
            'month' => $month,
            'basic_salary' => $basicSalary,
            'advance_paid' => $advancePaid,
            'salary_paid' => $netSalary,
            'status' => 'paid',
            'payment_date' => date('Y-m-d'),
        ]);

        return redirect()->back()->with('success', 'Salary payment recorded successfully.');
    }

    public function downloadReceipt($id)
    {
        $salary = Salary::with('employee.designation')->findOrFail($id);
        $gs = \App\Models\GeneralSettings::find(1);
        $pdf = Pdf::loadView('admin.salary.receipts.salary', compact('salary', 'gs'));
        return $pdf->download('salary_slip_' . $salary->id . '.pdf');
    }

    public function mySalariesIndex(Request $request)
    {
        $employeeId = auth('admin')->id();
        $salaries = Salary::where('employee_id', $employeeId)->orderByDesc('year')->orderByDesc('month')->paginate(20);
        return view('admin.salary.my_salaries.index', compact('salaries'));
    }
}

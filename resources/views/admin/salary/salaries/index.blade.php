@extends('layouts.admin')

@section('content')
<div class="content-area">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="heading">{{ __('Pay Salary') }}</h4>
                <ul class="links">
                    <li>
                        <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                    </li>
                    <li><a href="javascript:;">{{ __('Staff Salary') }}</a></li>
                    <li>
                        <a href="{{ route('admin.salaries.index') }}">{{ __('Pay Salary') }}</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    
    <div class="product-area">
        <div class="row">
            <div class="col-lg-12">
                <div class="mr-table allproduct p-4">
                    @include('includes.admin.form-success')
                    @include('includes.admin.form-error')
                    @include('includes.admin.flash-message')
                    
                    {{-- Filter Month & Year --}}
                    <form action="{{ route('admin.salaries.index') }}" method="GET" class="bg-light p-3 rounded mb-4 shadow-sm border">
                        <div class="row align-items-end">
                            <div class="col-md-4">
                                <label for="year" class="font-weight-bold">Select Year</label>
                                <select class="form-control" name="year" id="year">
                                    @for($y = date('Y') - 2; $y <= date('Y') + 2; $y++)
                                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>
                                            {{ $y }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="month" class="font-weight-bold">Select Month</label>
                                <select class="form-control" name="month" id="month">
                                    @for($m = 1; $m <= 12; $m++)
                                        <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                                            {{ date('F', mktime(0, 0, 0, $m, 10)) }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-4 mt-2">
                                <button type="submit" class="btn btn-primary w-100"><i class="fas fa-filter mr-2"></i>Filter Payroll</button>
                            </div>
                        </div>
                    </form>

                    <h5 class="mb-3 text-secondary p-2 bg-light rounded border">
                        Payroll Cycle: <strong>{{ date('F', mktime(0, 0, 0, $month, 10)) }}, {{ $year }}</strong>
                    </h5>

                    <div class="table-responsive">
                        <table class="table table-hover" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>{{ __('Employee') }}</th>
                                    <th>{{ __('Designation') }}</th>
                                    <th>{{ __('Basic Salary') }}</th>
                                    <th>{{ __('Advance Deduction') }}</th>
                                    <th>{{ __('Net Payable') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($employees as $employee)
                                    @php
                                        $advanceDeduction = $employee->advanceSalaries->sum('amount');
                                        $netPayable = max(0, $employee->salary - $advanceDeduction);
                                        $salaryRecord = $employee->salaries->first();
                                    @endphp
                                    <tr>
                                        <td>
                                            <strong>{{ $employee->name }}</strong>
                                        </td>
                                        <td>{{ $employee->designation->name ?? '-' }}</td>
                                        <td>৳{{ number_format($employee->salary, 2) }}</td>
                                        <td>
                                            @if($advanceDeduction > 0)
                                                <span class="text-danger font-weight-bold">-৳{{ number_format($advanceDeduction, 2) }}</span>
                                            @else
                                                <span class="text-muted">৳0.00</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="text-success font-weight-bold" style="font-size:14px;">৳{{ number_format($netPayable, 2) }}</span>
                                        </td>
                                        <td>
                                            @if($salaryRecord && $salaryRecord->status == 'paid')
                                                <span class="badge badge-success px-3 py-2">Paid</span>
                                                <br>
                                                <small class="text-muted">On: {{ \Carbon\Carbon::parse($salaryRecord->payment_date)->format('d M Y') }}</small>
                                            @else
                                                <span class="badge badge-secondary px-3 py-2">Unpaid</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($salaryRecord && $salaryRecord->status == 'paid')
                                                <a href="{{ route('admin.salaries.receipt', $salaryRecord->id) }}" class="btn btn-info">
                                                    <i class="fas fa-download mr-1"></i> Payslip
                                                </a>
                                                {{-- <span class="badge badge-secondary px-3 py-2">Paid</span> --}}
                                            @else
                                                <form action="{{ route('admin.salaries.store') }}" method="POST" onsubmit="return confirm('Record salary payment of ৳{{ number_format($netPayable, 2) }} for {{ $employee->name }}?');">
                                                    @csrf
                                                    <input type="hidden" name="employee_id" value="{{ $employee->id }}">
                                                    <input type="hidden" name="year" value="{{ $year }}">
                                                    <input type="hidden" name="month" value="{{ $month }}">
                                                    
                                                    <button type="submit" class="btn btn-success">
                                                        <i class="fas fa-money-bill-wave mr-1"></i> Pay Now
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">No employees available for payment. Add employees first.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

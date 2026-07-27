@extends('layouts.admin')

@section('content')
<div class="content-area">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="heading">{{ __('My Salary History') }}</h4>
                <ul class="links">
                    <li>
                        <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                    </li>
                    <li><a href="javascript:;">{{ __('My Salary') }}</a></li>
                    <li>
                        <a href="{{ route('admin.my-salaries.index') }}">{{ __('Salary History') }}</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    
    <div class="product-area">
        <div class="row">
            <div class="col-lg-12">
                <div class="mr-table allproduct">
                    @include('includes.admin.form-success')
                    @include('includes.admin.form-error')
                    @include('includes.admin.flash-message')

                    <div class="table-responsive">
                        <table class="table table-hover" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>{{ __('Year/Month') }}</th>
                                    <th>{{ __('Basic Salary') }}</th>
                                    <th>{{ __('Advance Deducted') }}</th>
                                    <th>{{ __('Net Salary Paid') }}</th>
                                    <th>{{ __('Payment Date') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Receipt') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($salaries as $salary)
                                    <tr>
                                        <td>{{ $salary->year }} / {{ date('F', mktime(0, 0, 0, $salary->month, 10)) }}</td>
                                        <td>৳{{ number_format($salary->basic_salary, 2) }}</td>
                                        <td>৳{{ number_format($salary->advance_paid, 2) }}</td>
                                        <td>৳{{ number_format($salary->salary_paid, 2) }}</td>
                                        <td>{{ \Carbon\Carbon::parse($salary->payment_date)->format('d M Y') }}</td>
                                        <td>
                                            <span class="badge badge-success px-3 py-2 text-white" style="background-color: #28a745; font-size: 11px; font-weight: 600; border-radius: 20px;">
                                                {{ ucfirst($salary->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.salaries.receipt', $salary->id) }}" class="btn btn-info btn-sm">
                                                <i class="fas fa-download"></i> Download Slip
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">No salary payment records found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        
                        <div class="p-3">
                            {{ $salaries->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

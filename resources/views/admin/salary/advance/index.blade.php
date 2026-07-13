@extends('layouts.admin')

@section('content')
<div class="content-area">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="heading">{{ __('Advance Payments') }}</h4>
                <ul class="links">
                    <li>
                        <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                    </li>
                    <li><a href="javascript:;">{{ __('Staff Salary') }}</a></li>
                    <li>
                        <a href="{{ route('admin.advance-salaries.index') }}">{{ __('Advance Payments') }}</a>
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
                    
                    <div class="row mb-3 p-3">
                        <div class="col-sm-12 text-right">
                            <a class="btn btn-primary" href="{{ route('admin.advance-salaries.create') }}">
                                <i class="fas fa-plus"></i> {{ __('Record Advance Payment') }}
                            </a>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>{{ __('Employee') }}</th>
                                    <th>{{ __('Year/Month') }}</th>
                                    <th>{{ __('Amount') }}</th>
                                    <th>{{ __('Payment Date') }}</th>
                                    <th>{{ __('Notes') }}</th>
                                    <th>{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($advances as $advance)
                                    <tr>
                                        <td>{{ $advance->employee->name ?? '-' }}</td>
                                        <td>{{ $advance->year }} / {{ date('F', mktime(0, 0, 0, $advance->month, 10)) }}</td>
                                        <td>৳{{ number_format($advance->amount, 2) }}</td>
                                        <td>{{ \Carbon\Carbon::parse($advance->payment_date)->format('d M Y') }}</td>
                                        <td><span class="text-muted">{{ $advance->notes ?? '-' }}</span></td>
                                        <td>
                                            <div class="action-list">
                                                <a href="{{ route('admin.advance-salaries.receipt', $advance->id) }}" class="btn btn-info">
                                                    <i class="fas fa-download"></i> Receipt
                                                </a>
                                                <a href="{{ route('admin.advance-salaries.delete', $advance->id) }}" 
                                                    onclick="return confirm('Are you sure you want to delete this advance payment record?');" 
                                                    class="btn btn-danger">
                                                    <i class="fas fa-trash-alt"></i> Delete
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">No advance payments recorded.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        
                        <div class="p-3">
                            {{ $advances->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

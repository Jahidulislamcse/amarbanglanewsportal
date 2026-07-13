@extends('layouts.admin')

@section('content')
<div class="content-area">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="heading">{{ __('My Advance Salary History') }}</h4>
                <ul class="links">
                    <li>
                        <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                    </li>
                    <li><a href="javascript:;">{{ __('My Salary') }}</a></li>
                    <li>
                        <a href="{{ route('admin.my-advance-salaries.index') }}">{{ __('Advance Salary History') }}</a>
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
                            <a class="btn btn-primary" href="{{ route('admin.my-advance-salaries.create') }}">
                                <i class="fas fa-plus"></i> {{ __('Request Advance Salary') }}
                            </a>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>{{ __('Year/Month') }}</th>
                                    <th>{{ __('Amount') }}</th>
                                    <th>{{ __('Payment Date') }}</th>
                                    <th>{{ __('Notes') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Receipt') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($advances as $advance)
                                    <tr>
                                        <td>{{ $advance->year }} / {{ date('F', mktime(0, 0, 0, $advance->month, 10)) }}</td>
                                        <td>৳{{ number_format($advance->amount, 2) }}</td>
                                        <td>{{ $advance->payment_date ? \Carbon\Carbon::parse($advance->payment_date)->format('d M Y') : 'Pending Approval' }}</td>
                                        <td><span class="text-muted">{{ $advance->notes ?? '-' }}</span></td>
                                        <td>
                                            @if($advance->status === 'approved')
                                                <span class="badge badge-success px-3 py-2 text-white" style="background-color: #28a745; font-size: 11px; font-weight: 600; border-radius: 20px;">Approved</span>
                                            @elseif($advance->status === 'pending')
                                                <span class="badge badge-warning px-3 py-2 text-dark" style="background-color: #ffc107; font-size: 11px; font-weight: 600; border-radius: 20px;">Pending</span>
                                            @elseif($advance->status === 'rejected')
                                                <span class="badge badge-danger px-3 py-2 text-white" style="background-color: #dc3545; font-size: 11px; font-weight: 600; border-radius: 20px;">Rejected</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($advance->status === 'approved')
                                                <a href="{{ route('admin.advance-salaries.receipt', $advance->id) }}" class="btn btn-info btn-sm">
                                                    <i class="fas fa-download"></i> Receipt
                                                </a>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">No advance salary history found.</td>
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

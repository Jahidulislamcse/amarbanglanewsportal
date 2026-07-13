@extends('layouts.admin')

@section('content')
<div class="content-area">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="heading">{{ __('Employees') }}</h4>
                <ul class="links">
                    <li>
                        <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                    </li>
                    <li><a href="javascript:;">{{ __('Staff Salary') }}</a></li>
                    <li>
                        <a href="{{ route('admin.employees.index') }}">{{ __('Employees') }}</a>
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
                            <a class="btn btn-primary" href="{{ route('admin.employees.create') }}">
                                <i class="fas fa-plus"></i> {{ __('Add New Employee') }}
                            </a>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Email / Phone') }}</th>
                                    <th>{{ __('Designation (Role)') }}</th>
                                    <th>{{ __('Base Salary') }}</th>
                                    <th>{{ __('Account Details') }}</th>
                                    <th>{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($employees as $employee)
                                    <tr>
                                        <td>
                                            <strong>{{ $employee->name }}</strong>
                                        </td>
                                        <td>
                                            {{ $employee->email }}
                                            @if($employee->phone)
                                                <br><small class="text-muted"><i class="fas fa-phone-alt"></i> {{ $employee->phone }}</small>
                                            @endif
                                        </td>
                                        <td>{{ $employee->designation->name ?? '-' }}</td>
                                        <td>৳{{ number_format($employee->salary, 2) }}</td>
                                        <td>
                                            <span class="text-muted" style="white-space: pre-line;">{{ $employee->account_details ?? 'Not Set' }}</span>
                                        </td>
                                        <td>
                                            <div class="action-list">
                                                <a href="{{ route('admin.employees.edit', $employee->id) }}" class="btn btn-warning">
                                                    <i class="fas fa-edit"></i> Edit
                                                    @if($employee->id == 1)
                                                        (Super Admin)
                                                    @endif
                                                </a>
                                                @if($employee->id != 1)
                                                    <a href="{{ route('admin.employees.delete', $employee->id) }}" 
                                                        onclick="return confirm('Are you sure you want to delete this administrator employee account?');" 
                                                        class="btn btn-danger">
                                                        <i class="fas fa-trash-alt"></i> Delete
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">No employees found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        
                        <div class="p-3">
                            {{ $employees->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

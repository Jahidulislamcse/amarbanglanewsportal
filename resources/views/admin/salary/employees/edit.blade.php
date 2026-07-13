@extends('layouts.admin')

@section('content')
<div class="content-area">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="heading">{{ __('Edit Employee') }}</h4>
                <ul class="links">
                    <li>
                        <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                    </li>
                    <li><a href="javascript:;">{{ __('Staff Salary') }}</a></li>
                    <li>
                        <a href="{{ route('admin.employees.index') }}">{{ __('Employees') }}</a>
                    </li>
                    <li><a href="javascript:;">{{ __('Edit Employee') }}</a></li>
                </ul>
            </div>
        </div>
    </div>
    
    <div class="add-product-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="product-description">
                    <div class="body-area">
                        @include('includes.admin.form-error')
                        
                        <form action="{{ route('admin.employees.update', $employee->id) }}" method="POST">
                            @csrf
                            
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="left-area">
                                        <h4 class="heading">{{ __('Name') }} *</h4>
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <input type="text" class="input-field" name="name" required placeholder="Employee Full Name" value="{{ old('name', $employee->name) }}">
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-lg-4">
                                    <div class="left-area">
                                        <h4 class="heading">{{ __('Email Address') }} *</h4>
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <input type="email" class="input-field" name="email" required placeholder="Unique Login Email" value="{{ old('email', $employee->email) }}">
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-lg-4">
                                    <div class="left-area">
                                        <h4 class="heading">{{ __('Phone Number') }}</h4>
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <input type="text" class="input-field" name="phone" placeholder="e.g. +88017xxxxxxxx" value="{{ old('phone', $employee->phone) }}">
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-lg-4">
                                    <div class="left-area">
                                        <h4 class="heading">{{ __('Password') }}</h4>
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <input type="password" class="input-field" name="password" placeholder="Leave blank to keep current password" minlength="6">
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-lg-4">
                                    <div class="left-area">
                                        <h4 class="heading">{{ __('Designation (Role)') }} *</h4>
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <select class="input-field" name="designation_id" required>
                                        <option value="">Select Designation/Role</option>
                                        @foreach($designations as $designation)
                                            <option value="{{ $designation->id }}" {{ old('designation_id', $employee->role_id) == $designation->id ? 'selected' : '' }}>
                                                {{ $designation->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-lg-4">
                                    <div class="left-area">
                                        <h4 class="heading">{{ __('Base Salary') }} (৳) *</h4>
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <input type="number" step="0.01" class="input-field" name="salary" required placeholder="e.g. 25000" value="{{ old('salary', $employee->salary) }}">
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-lg-4">
                                    <div class="left-area">
                                        <h4 class="heading">{{ __('Account & Wallet Details') }}</h4>
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <textarea class="input-field" name="account_details" rows="4" placeholder="Enter bank details, routing number, or Mobile Financial Services (bKash/Nagad) details">{{ old('account_details', $employee->account_details) }}</textarea>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-lg-4"></div>
                                <div class="col-lg-7">
                                    <button class="addProductSubmit-btn" type="submit">{{ __('Update Employee') }}</button>
                                    <a class="btn btn-secondary rounded-pill ml-3 px-4 py-2" href="{{ route('admin.employees.index') }}">{{ __('Cancel') }}</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

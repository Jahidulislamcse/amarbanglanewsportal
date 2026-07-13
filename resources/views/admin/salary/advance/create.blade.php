@extends('layouts.admin')

@section('content')
<div class="content-area">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="heading">{{ __('Record Advance Payment') }}</h4>
                <ul class="links">
                    <li>
                        <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                    </li>
                    <li><a href="javascript:;">{{ __('Staff Salary') }}</a></li>
                    <li>
                        <a href="{{ route('admin.advance-salaries.index') }}">{{ __('Advance Payments') }}</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.advance-salaries.create') }}">{{ __('Record Advance') }}</a>
                    </li>
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
                        
                        <form action="{{ route('admin.advance-salaries.store') }}" method="POST">
                            @csrf
                            
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="left-area">
                                        <h4 class="heading">{{ __('Employee') }} *</h4>
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <select class="input-field" name="employee_id" required id="employee_id">
                                        <option value="">Select Employee</option>
                                        @foreach($employees as $employee)
                                            <option value="{{ $employee->id }}" data-salary="{{ $employee->salary }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                                {{ $employee->name }} (Base: ৳{{ number_format($employee->salary, 2) }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-lg-4">
                                    <div class="left-area">
                                        <h4 class="heading">{{ __('Target Year') }} *</h4>
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <select class="input-field" name="year" required>
                                        @for($y = date('Y') - 1; $y <= date('Y') + 2; $y++)
                                            <option value="{{ $y }}" {{ old('year', date('Y')) == $y ? 'selected' : '' }}>
                                                {{ $y }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-lg-4">
                                    <div class="left-area">
                                        <h4 class="heading">{{ __('Target Month') }} *</h4>
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <select class="input-field" name="month" required>
                                        @for($m = 1; $m <= 12; $m++)
                                            <option value="{{ $m }}" {{ old('month', date('n')) == $m ? 'selected' : '' }}>
                                                {{ date('F', mktime(0, 0, 0, $m, 10)) }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-lg-4">
                                    <div class="left-area">
                                        <h4 class="heading">{{ __('Advance Amount') }} (৳) *</h4>
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <input type="number" step="0.01" class="input-field" name="amount" id="amount" required placeholder="e.g. 5000" value="{{ old('amount') }}">
                                    <small class="text-danger d-none" id="salary-warning">Warning: Advance amount exceeds the base salary!</small>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-lg-4">
                                    <div class="left-area">
                                        <h4 class="heading">{{ __('Payment Date') }} *</h4>
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <input type="date" class="input-field" name="payment_date" required value="{{ old('payment_date', date('Y-m-d')) }}">
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-lg-4">
                                    <div class="left-area">
                                        <h4 class="heading">{{ __('Notes') }}</h4>
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <textarea class="input-field" name="notes" rows="3" placeholder="Additional details or reason for advance">{{ old('notes') }}</textarea>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-lg-4"></div>
                                <div class="col-lg-7">
                                    <button class="addProductSubmit-btn" type="submit">{{ __('Record Advance') }}</button>
                                    <a class="btn btn-secondary rounded-pill ml-3 px-4 py-2" href="{{ route('admin.advance-salaries.index') }}">{{ __('Cancel') }}</a>
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

@section('scripts')
<script>
    $(document).ready(function() {
        function checkSalaryLimit() {
            var selectedOption = $('#employee_id option:selected');
            var baseSalary = parseFloat(selectedOption.data('salary')) || 0;
            var advanceAmount = parseFloat($('#amount').val()) || 0;
            
            if (baseSalary > 0 && advanceAmount > baseSalary) {
                $('#salary-warning').removeClass('d-none');
            } else {
                $('#salary-warning').addClass('d-none');
            }
        }
        
        $('#employee_id, #amount').on('change input', checkSalaryLimit);
    });
</script>
@endsection

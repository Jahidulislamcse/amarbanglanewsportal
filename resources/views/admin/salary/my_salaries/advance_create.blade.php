@extends('layouts.admin')

@section('content')
<div class="content-area">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="heading">{{ __('Request Advance Salary') }}</h4>
                <ul class="links">
                    <li>
                        <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                    </li>
                    <li><a href="javascript:;">{{ __('My Salary') }}</a></li>
                    <li>
                        <a href="{{ route('admin.my-advance-salaries.index') }}">{{ __('Advance Salary History') }}</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.my-advance-salaries.create') }}">{{ __('Request Advance') }}</a>
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
                        
                        <form action="{{ route('admin.my-advance-salaries.store') }}" method="POST">
                            @csrf
                            
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="left-area">
                                        <h4 class="heading">{{ __('Employee') }}</h4>
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <input type="text" class="input-field" value="{{ auth('admin')->user()->name }} (Base Salary: ৳{{ number_format(auth('admin')->user()->salary, 2) }}, Max Month Advance: ৳{{ number_format(auth('admin')->user()->salary * 0.30, 2) }})" readonly disabled>
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
                                        @for($y = date('Y'); $y <= date('Y') + 1; $y++)
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
                                        <h4 class="heading">{{ __('Requested Amount') }} (৳) *</h4>
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <input type="number" step="0.01" class="input-field" name="amount" id="amount" required placeholder="e.g. 5000" value="{{ old('amount') }}">
                                    <small class="text-danger d-none" id="salary-warning">Warning: Requested advance amount exceeds the 30% limit of your base salary (৳{{ number_format(auth('admin')->user()->salary * 0.30, 2) }})!</small>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-lg-4">
                                    <div class="left-area">
                                        <h4 class="heading">{{ __('Reason / Notes') }}</h4>
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <textarea class="input-field" name="notes" rows="3" placeholder="Reason for advance request">{{ old('notes') }}</textarea>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-lg-4"></div>
                                <div class="col-lg-7">
                                    <button class="addProductSubmit-btn" type="submit">{{ __('Submit Request') }}</button>
                                    <a class="btn btn-secondary rounded-pill ml-3 px-4 py-2" href="{{ route('admin.my-advance-salaries.index') }}">{{ __('Cancel') }}</a>
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
        var baseSalary = parseFloat("{{ auth('admin')->user()->salary }}") || 0;
        var limit = baseSalary * 0.30;
        
        function checkSalaryLimit() {
            var requestAmount = parseFloat($('#amount').val()) || 0;
            
            if (limit > 0 && requestAmount > limit) {
                $('#salary-warning').removeClass('d-none');
            } else {
                $('#salary-warning').addClass('d-none');
            }
        }
        
        $('#amount').on('change input', checkSalaryLimit);
    });
</script>
@endsection

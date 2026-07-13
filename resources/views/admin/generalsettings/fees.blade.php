@extends('layouts.admin')

@section('content')
<style>
    .number-input {
        position: relative;
        width: 120px;
        display: inline-block;
    }
    
    .number-input input {
        width: 100%;
        height: 35px;
        padding-right: 28px;
        text-align: center;
        border: 1px solid #ced4da;
        border-radius: 6px;
        font-size: 16px;
        box-sizing: border-box;
    }
    
    .number-input .arrow {
        position: absolute;
        right: 0;
        width: 28px;
        height: 70%;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        user-select: none;
    }
    
    .number-input .up {
        top: 0;
    }
    
    .number-input .down {
        bottom: 0;
    }
</style>

<div class="content-area">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="heading">{{ __('Dynamic Fees/Commissions') }}</h4>
                <ul class="links">
                    <li>
                        <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a>
                    </li>
                    <li>
                        <a href="javascript:;">{{ __('General Settings') }}</a>
                    </li>
                    <li>
                        <a href="">{{ __('Dynamic Fees/Commissions') }}</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="add-product-content">
        @include('includes.admin.form-both')
        <div class="row">
            <div class="col-lg-12">
                <div class="product-description">
                    <div class="body-area">
                        <div class="gocover" style="background: url({{ asset('assets/images/'.$gs->admin_loader) }}) no-repeat scroll center center rgba(45, 45, 45, 0.5);"></div>
                        <form class="uplogo-form" id="geniusform"
                          action="{{ route('admin.generalsettings.fees.update') }}"
                          method="POST" enctype="multipart/form-data">
                        @csrf
                    
                        {{-- Reporter Monthly Fee --}}
                        <div class="row justify-content-center">
                            <div class="col-lg-3">
                                <div class="left-area">
                                    <h4 class="heading">{{ __('Reporter Monthly Fee') }} *</h4>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <input type="number" step="0.01"
                                    name="rep_monthly_fee"
                                    value="{{ old('rep_monthly_fee', $data->rep_monthly_fee ?? 0) }}"
                                    class="input-field" required>
                            </div>
                        </div>
                        
                        <div class="row justify-content-center">
                            <div class="col-lg-3">
                                <div class="left-area">
                                    <h4 class="heading">Withdraw Minimum *</h4>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <input type="number" step="0.01"
                                    name="withdraw_min"
                                    value="{{ old('withdraw_min', $data->withdraw_min ?? 0) }}"
                                    class="input-field" required>
                            </div>
                        </div>
                    
                        {{-- Reader View Rate --}}
                        <div class="row justify-content-center">
                            <div class="col-lg-3">
                                <div class="left-area">
                                    <h4 class="heading">{{ __('Earning on each view for readers') }} *</h4>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <input type="number" step="0.01"
                                    name="reader_view_rate"
                                    value="{{ old('reader_view_rate', $data->reader_view_rate ?? 0) }}"
                                    class="input-field" required>
                            </div>
                        </div>
                    
                        {{-- Reporter View Rate --}}
                        <div class="row justify-content-center">
                            <div class="col-lg-3">
                                <div class="left-area">
                                    <h4 class="heading">{{ __('Earning on each view for Reporter') }} *</h4>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <input type="number" step="0.01"
                                    name="reporter_view_rate"
                                    value="{{ old('reporter_view_rate', $data->reporter_view_rate ?? 0) }}"
                                    class="input-field" required>
                            </div>
                        </div>
                    
                        {{-- Referral Commission --}}
                        <div class="row justify-content-center">
                            <div class="col-lg-3">
                                <div class="left-area">
                                    <h4 class="heading">{{ __('One Time Referral Commission (%)') }} *</h4>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <input type="number" step="0.01"
                                    name="referral_commission"
                                    value="{{ old('referral_commission', $data->referral_commission ?? 0) }}"
                                    class="input-field" required>
                            </div>
                        </div>
                        
                         <div class="row justify-content-center">
                            <div class="col-lg-3">
                                <div class="left-area">
                                    <h4 class="heading">{{ __('Common Refferel Commission (Tk)') }} *</h4>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <input type="number" step="0.01"
                                    name="common_reffer_commission"
                                    value="{{ old('common_reffer_commission', $data->common_reffer_commission ?? 0) }}"
                                    class="input-field" required>
                            </div>
                        </div>
                    
                        {{-- Referral View Commission --}}
                        <div class="row justify-content-center">
                            <div class="col-lg-3">
                                <div class="left-area">
                                    <h4 class="heading">{{ __('Referral Commission on each view') }} *</h4>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <input type="number" step="0.01"
                                    name="referral_view_commission"
                                    value="{{ old('referral_view_commission', $data->referral_view_commission ?? 0) }}"
                                    class="input-field" required>
                            </div>
                        </div>
                    
                        {{-- Free Reader Rate --}}
                        <div class="row justify-content-center">
                            <div class="col-lg-3">
                                <div class="left-area">
                                    <h4 class="heading">{{ __('Free Reader Bonus on View') }} *</h4>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <input type="number" step="0.01"
                                    name="free_reader_rate"
                                    value="{{ old('free_reader_rate', $data->free_reader_rate ?? 0) }}"
                                    class="input-field" required>
                            </div>
                        </div>
                    
                        {{-- Executive Reader Rate --}}
                        <div class="row justify-content-center">
                            <div class="col-lg-3">
                                <div class="left-area">
                                    <h4 class="heading">{{ __('Executive Reader Bonus on View') }}</h4>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <input type="number" step="0.01"
                                    name="executive_reader_rate"
                                    value="{{ old('executive_reader_rate', $data->executive_reader_rate ?? 0) }}"
                                    class="input-field">
                            </div>
                        </div>
                    
                        {{-- VIP Reader Rate --}}
                        <div class="row justify-content-center">
                            <div class="col-lg-3">
                                <div class="left-area">
                                    <h4 class="heading">{{ __('VIP Reader Bonus on View') }}</h4>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <input type="number" step="0.01"
                                    name="vip_reader_rate"
                                    value="{{ old('vip_reader_rate', $data->vip_reader_rate ?? 0) }}"
                                    class="input-field">
                            </div>
                        </div>
                    
                        {{-- Executive Package Price --}}
                        <div class="row justify-content-center">
                            <div class="col-lg-3">
                                <div class="left-area">
                                    <h4 class="heading">{{ __('Executive Package Price') }}</h4>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <input type="number" step="0.01"
                                    name="executive_package_price"
                                    value="{{ old('executive_package_price', $data->executive_package_price ?? 0) }}"
                                    class="input-field">
                            </div>
                        </div>
                    
                        {{-- VIP Package Price --}}
                        <div class="row justify-content-center">
                            <div class="col-lg-3">
                                <div class="left-area">
                                    <h4 class="heading">{{ __('VIP Package Price') }}</h4>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <input type="number" step="0.01"
                                    name="vip_package_price"
                                    value="{{ old('vip_package_price', $data->vip_package_price ?? 0) }}"
                                    class="input-field">
                            </div>
                        </div>

                        <hr>
                        <h4 class="text-center mb-4" style="color: #2a5298; font-weight: 700;">🏆 {{ __('Weekly Reporter & Quiz Prizes') }}</h4>

                        {{-- Reporter 1st Prize --}}
                        <div class="row justify-content-center">
                            <div class="col-lg-3">
                                <div class="left-area">
                                    <h4 class="heading">{{ __('Reporter 1st Winner Prize (৳)') }} *</h4>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <input type="number" step="0.01"
                                    name="reporter_1st_prize"
                                    value="{{ old('reporter_1st_prize', $data->reporter_1st_prize ?? 0) }}"
                                    class="input-field" required>
                            </div>
                        </div>

                        {{-- Reporter 2nd Prize --}}
                        <div class="row justify-content-center">
                            <div class="col-lg-3">
                                <div class="left-area">
                                    <h4 class="heading">{{ __('Reporter 2nd Winner Prize (৳)') }} *</h4>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <input type="number" step="0.01"
                                    name="reporter_2nd_prize"
                                    value="{{ old('reporter_2nd_prize', $data->reporter_2nd_prize ?? 0) }}"
                                    class="input-field" required>
                            </div>
                        </div>

                        {{-- Reporter 3rd Prize --}}
                        <div class="row justify-content-center">
                            <div class="col-lg-3">
                                <div class="left-area">
                                    <h4 class="heading">{{ __('Reporter 3rd Winner Prize (৳)') }} *</h4>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <input type="number" step="0.01"
                                    name="reporter_3rd_prize"
                                    value="{{ old('reporter_3rd_prize', $data->reporter_3rd_prize ?? 0) }}"
                                    class="input-field" required>
                            </div>
                        </div>

                        {{-- Quiz 1st Prize --}}
                        <div class="row justify-content-center">
                            <div class="col-lg-3">
                                <div class="left-area">
                                    <h4 class="heading">{{ __('Quiz 1st Winner Prize (৳)') }} *</h4>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <input type="number" step="0.01"
                                    name="quiz_1st_prize"
                                    value="{{ old('quiz_1st_prize', $data->quiz_1st_prize ?? 0) }}"
                                    class="input-field" required>
                            </div>
                        </div>

                        {{-- Quiz 2nd Prize --}}
                        <div class="row justify-content-center">
                            <div class="col-lg-3">
                                <div class="left-area">
                                    <h4 class="heading">{{ __('Quiz 2nd Winner Prize (৳)') }} *</h4>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <input type="number" step="0.01"
                                    name="quiz_2nd_prize"
                                    value="{{ old('quiz_2nd_prize', $data->quiz_2nd_prize ?? 0) }}"
                                    class="input-field" required>
                            </div>
                        </div>

                        {{-- Quiz 3rd Prize --}}
                        <div class="row justify-content-center">
                            <div class="col-lg-3">
                                <div class="left-area">
                                    <h4 class="heading">{{ __('Quiz 3rd Winner Prize (৳)') }} *</h4>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <input type="number" step="0.01"
                                    name="quiz_3rd_prize"
                                    value="{{ old('quiz_3rd_prize', $data->quiz_3rd_prize ?? 0) }}"
                                    class="input-field" required>
                            </div>
                        </div>
                    
                        {{-- Submit --}}
                        <div class="row justify-content-center">
                            <div class="col-lg-3"></div>
                            <div class="col-lg-6">
                                <button class="addProductSubmit-btn" type="submit">
                                    {{ __('Save Settings') }}
                                </button>
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
<script src="{{ asset('assets/admin/js/notify.js') }}"></script>
<script src="{{ asset('assets/admin/js/distawk.js') }}"></script>

<script>
    function incrementValue(button) {
        const input = button.parentElement.querySelector('input');
        let value = parseFloat(input.value) || 0;
        value += 0.01;
        input.value = value.toFixed(2);
    }

    function decrementValue(button) {
        const input = button.parentElement.querySelector('input');
        let value = parseFloat(input.value) || 0;
        value -= 0.01;
        if (value < 0) value = 0;
        input.value = value.toFixed(2);
    }

    document.addEventListener('DOMContentLoaded', function() {
        const inputs = document.querySelectorAll('input[type="number"]');
        inputs.forEach(input => {
            input.addEventListener('change', function() {
                if (this.value < 0) {
                    this.value = 0;
                }
            });
        });
    });
</script>
@endsection
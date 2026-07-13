@extends('layouts.load')
@section('content')

@php
    $user = auth()->user();
    $balance = $user->balance;

    $fee = \App\Models\Fee::first();

    $vatPercent = 15;

    $minWithdraw = 500;

    $availableBalance = $balance > 1 ? $balance - 1 : 0;

    $canWithdraw = $availableBalance >= $minWithdraw;

    $maxWithdraw = $availableBalance;

    $disabled = $canWithdraw ? '' : 'disabled';
@endphp

<div class="add-product-content p-0 shadow-none">
    @include('includes.admin.form-both')

    <div class="row">
        <div class="col-lg-12">
            <div class="product-description">
                <div class="body-area shadow-none">

                    <div class="gocover"
                        style="background: url({{ asset('assets/images/'.$gs->admin_loader) }}) no-repeat center rgba(45,45,45,0.5);">
                    </div>

                    <form id="geniusformdata"
                          action="{{ route('user.profile.paymentstore') }}"
                          method="POST">
                        @csrf

                        {{-- Amount --}}
                        <div class="row mt-3">
                            <div class="col-lg-12">
                                <h4 class="heading">Amount * (Available Balance: {{ $availableBalance }})</h4>

                                <input type="number"
                                   class="input-field"
                                   id="amountInput"
                                   name="user_amount"
                                   value="{{ $canWithdraw ? $maxWithdraw : 0 }}"
                                   min="{{ $minWithdraw }}"
                                   max="{{ $maxWithdraw }}"
                                   onkeyup="validateAmount()"
                                   onchange="validateAmount()"
                                   {{ $disabled }}
                                   required>
                                   
                                @if(!$canWithdraw)
                                    <p style="color:red;">
                                        Minimum withdraw amount is {{ $minWithdraw }}.
                                        Your available balance is {{ $availableBalance }}.
                                    </p>
                                @else
                                    <p id="amountMsg" style="color:red; display:none;">
                                        Enter amount between 500 and {{ $maxWithdraw }}.
                                    </p>
                                @endif
                            </div>
                        </div>

                        {{-- Amount after VAT --}}
                        <div class="row mt-3">
                            <div class="col-lg-12">
                                <h4 class="heading">Amount After 15% VAT (Actual Withdraw Amount)</h4>
                                
                                <input type="number"
                                       class="input-field"
                                       id="afterVatInput"
                                       value="{{ $canWithdraw ? number_format($maxWithdraw * 0.85, 2, '.', '') : '' }}"
                                       disabled>
                                
                                <input type="hidden"
                                       id="requestAmountHidden"
                                       name="request_amount"
                                       value="{{ $canWithdraw ? number_format($maxWithdraw * 0.85, 2, '.', '') : '' }}">
                                       
                                <p id="vatCalculationText" style="color:green; font-weight: bold; margin-top: 5px;">
                                    {{ $canWithdraw ? 'You will receive: ' . number_format($maxWithdraw * 0.85, 2, '.', '') . ' (after 15% VAT deduction)' : '' }}
                                </p>
                            </div>
                        </div>

                        
                        {{-- Payment Type --}}
                        <div class="row">
                            <div class="col-lg-12">
                                <h4 class="heading">Payment Type *</h4>

                                <select class="form-control"
                                        name="payment_type"
                                        required
                                        {{ $disabled }}>
                                    <option value="">Select Payment Type</option>
                                    @foreach(payment_type() as $key => $type)
                                        @if(in_array($key, [3, 4, 5]))
                                            <option value="{{ $key }}">{{ $type }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>


                        {{-- Account Details --}}
                        <div class="row mt-3">
                            <div class="col-lg-12">
                                <h4 class="heading">Payment Number *</h4>

                                <input type="text"
                                       class="input-field"
                                       name="account_details"
                                       placeholder="017XXXXXXXX"
                                       required
                                       {{ $disabled }}>
                            </div>
                        </div>

                        {{-- Submit --}}
                        <div class="row mt-4">
                            <div class="col-lg-12">
                                <button class="addProductSubmit-btn"
                                        id="submitBtn"
                                        type="submit"
                                        {{ $disabled }}>
                                    Create Withdraw Request
                                </button>
                            </div>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
function validateAmount() {
    let amount = parseFloat(document.getElementById('amountInput').value);
    let min = 500;
    let max = {{ $maxWithdraw }};

    if (isNaN(amount) || amount < min || amount > max) {
        document.getElementById('submitBtn').disabled = true;
        document.getElementById('amountMsg').style.display = 'block';
    } else {
        document.getElementById('submitBtn').disabled = false;
        document.getElementById('amountMsg').style.display = 'none';
    }

    if (!isNaN(amount) && amount > 0) {
        let vat = amount * 0.15;
        let finalAmount = amount - vat;
        let finalStr = finalAmount.toFixed(2);
        document.getElementById('afterVatInput').value = finalStr;
        document.getElementById('requestAmountHidden').value = finalStr;
        document.getElementById('vatCalculationText').innerHTML = "You will receive: " + finalStr + " (after 15% VAT deduction)";
    } else {
        document.getElementById('afterVatInput').value = '';
        document.getElementById('requestAmountHidden').value = '';
        document.getElementById('vatCalculationText').innerHTML = "";
    }
}

// Call validateAmount immediately since this file is loaded dynamically via AJAX
validateAmount();
</script>

@endsection
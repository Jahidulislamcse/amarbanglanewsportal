@extends('layouts.load')
@section('content')

<?php
//print_r( $data);

?>
    <div class="add-product-content p-0 shadow-none">
        @include('includes.admin.form-both')
        <div class="row">
            <div class="col-lg-12">
                <div class="product-description">
                    <div class="body-area  shadow-none">
                    <div class="gocover" style="background: url({{asset('assets/images/'.$gs->admin_loader)}}) no-repeat scroll center center rgba(45, 45, 45, 0.5);"></div>
                        <form id="geniusformdata"
                              action="{{ route('admin.administator.paymentupdate',$data->id) }}"
                              method="POST">
                            @csrf
                        
                            {{-- VIEW ONLY DETAILS --}}
                            <div class="row">
                                <div class="col-lg-12">
                                    <h4 class="heading">Payment Type</h4>
                                    <input type="text" class="input-field"
                                           value="{{ payment_type()[$data->payment_type] ?? '' }}"
                                           readonly>
                                </div>
                            </div>
                        
                            <div class="row">
                                <div class="col-lg-12">
                                    <h4 class="heading">Requested Amount</h4>
                                    <input type="text" class="input-field"
                                           value="{{ $data->request_amount }}"
                                           readonly>
                                </div>
                            </div>
                        
                            <div class="row">
                                <div class="col-lg-12">
                                    <h4 class="heading">Account Details</h4>
                                    <input type="text" class="input-field"
                                           value="{{ $data->account_details }}"
                                           readonly>
                                </div>
                            </div>
                        
                            <div class="row">
                                <div class="col-lg-12">
                                    <h4 class="heading">Request Date</h4>
                                    <input type="text" class="input-field"
                                           value="{{ $data->request_date }}"
                                           readonly>
                                </div>
                            </div>
                        
                        
                            {{-- ACTION --}}
                            <div class="row mt-4">
                                <div class="col-lg-12">
                                    <h4 class="heading">Action</h4>
                                    <select name="status" id="status" class="form-control" required>
                                        <option value="">Select</option>
                                        <option value="1">Approve</option>
                                        <option value="2">Reject</option>
                                    </select>
                                </div>
                            </div>
                        
                        
                            {{-- HIDDEN AUTO VALUES --}}
                            <input type="hidden" name="payment_type" value="{{ $data->payment_type }}">
                            <input type="hidden" name="request_amount" value="{{ $data->request_amount }}">
                            <input type="hidden" name="approve_amount" id="approve_amount" value="{{ $data->request_amount }}">
                            <input type="hidden" name="account_details" value="{{ $data->account_details }}">
                            <input type="hidden" name="verify_date" id="verify_date">
                            <input type="hidden" name="verifier_id" value="{{ auth()->id() }}">
                        
                        
                            <div class="row mt-4">
                                <div class="col-lg-12">
                                    <button class="addProductSubmit-btn" type="submit">
                                        Submit Action
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
document.getElementById('status').addEventListener('change', function () {
    let now = new Date().toISOString().slice(0,19).replace('T',' ');
    document.getElementById('verify_date').value = now;

    if (this.value == '2') {
        document.getElementById('approve_amount').value = 0;
    } else {
        document.getElementById('approve_amount').value = {{ $data->request_amount }};
    }
});
</script>
@endsection

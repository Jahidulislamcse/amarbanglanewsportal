@extends('layouts.load')
@section('content')


    <div class="add-product-content p-0 shadow-none">
        @include('includes.admin.form-both')
        <div class="row">
            <div class="col-lg-12">
                <div class="product-description">
                    <div class="body-area  shadow-none">
                    <div class="gocover" style="background: url({{asset('assets/images/'.$gs->admin_loader)}}) no-repeat scroll center center rgba(45, 45, 45, 0.5);"></div>
                    <form id="geniusformdata" action="{{ route('admin.administator.receivestore') }}" method="POST" enctype="multipart/form-data">
                        {{csrf_field()}}
							
							
							<div class="row">
                            <div class="col-lg-12">
                                <div class="left-area">
                                        <h4 class="heading">{{ __('Reporter') }} *</h4>
                                </div>
                            </div>
                            <div class="col-lg-12">
							  <select class="form-control" id='user_id' required name="user_id">
								 <?php
								 
									
									  echo '<option value="">Select Reporter</option>'; 
									  foreach($users as $key=>$user)
									   {
										 echo "<option  value='".$user->id."'>".$user->name."</option>";
									   }
									
									?>
							</select>
                            </div>
                        </div>
						
						<div class="row">
							<div class="col-lg-12">
								<div class="left-area">
										<h4 class="heading">{{ __('Amount') }} *</h4>
								</div>
							</div>
							<div class="col-lg-12">
								<input type="text" class="input-field" name="amount" placeholder="{{ __('Amount') }}" required="" value="500">
							
							</div>
						</div>
						
						<div class="row">
                            <div class="col-lg-12">
                                <div class="left-area">
                                        <h4 class="heading">{{ __('Payment Type') }} *</h4>
                                </div>
                            </div>
                            <div class="col-lg-12">
							  <select class="form-control" id='payment_type' required name="payment_type">
								 <?php
								 
									
									  echo '<option value="">Select Payment Type</option>'; 
									  foreach(payment_type() as $key=>$res1)
									   {
										 echo "<option  value='".$key."'>".$res1."</option>";
									   }
									
									?>
							</select>
                            </div>
                        </div>
						
						<div class="row">
                            <div class="col-lg-12">
                                <div class="left-area">
                                        <h4 class="heading">{{ __('Sender Number') }}</h4>
                                </div>
                            </div>
                            <div class="col-lg-12">
							<input type="text" class="input-field" name="send_number" placeholder="{{ __('Sender Number') }}">
                            </div>
                        </div>
						
						<div class="row">
                            <div class="col-lg-12">
                                <div class="left-area">
                                        <h4 class="heading">{{ __('Payment Date') }} *</h4>
                                </div>
                            </div>
                            <div class="col-lg-12">
							<input type="date" class="input-field" name="pdate" placeholder="{{ __('Payment Date') }}" required="">
                            </div>
                        </div>

						<div class="row">
                            <div class="col-lg-12">
                                <div class="left-area">
									<h4 class="heading">{{ __('TXN ID') }}</h4>
                                </div>
                            </div>
                            <div class="col-lg-12">
							<input type="text" class="input-field" name="txn_id" placeholder="{{ __('TXN ID') }}">
                            </div>
                        </div>


  

                        <div class="row">
                            <div class="col-lg-12">
                            <button class="addProductSubmit-btn" type="submit">{{ __("Create") }}</button>
                            </div>
                        </div>

                    </form>


                    </div>
                </div>
            </div>
        </div>
    </div>
<script>

@endsection

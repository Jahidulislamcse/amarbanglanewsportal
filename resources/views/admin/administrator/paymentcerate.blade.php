@extends('layouts.load')
@section('content')


    <div class="add-product-content p-0 shadow-none">
        @include('includes.admin.form-both')
        <div class="row">
            <div class="col-lg-12">
                <div class="product-description">
                    <div class="body-area  shadow-none">
                    <div class="gocover" style="background: url({{asset('assets/images/'.$gs->admin_loader)}}) no-repeat scroll center center rgba(45, 45, 45, 0.5);"></div>
                    <form id="geniusformdata" action="{{ route('admin.administator.paymentstore') }}" method="POST" enctype="multipart/form-data">
                        {{csrf_field()}}

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
	
							<?php
		
					   $disabled="";
						if($balance>=1000){
						   $min_amount=1000;
						}else{
							$min_amount=$balance;
							$disabled="disabled";
						}
						?>
									
						<div class="row">
							<div class="col-lg-12">
								<div class="left-area">
										<h4 class="heading">{{ __('Amount') }} *</h4>
								</div>
							</div>
							<div class="col-lg-12">
								<input type="text" class="input-field" name="request_amount" {{$disabled}} onkeyup="getBlance(this.value)" placeholder="{{ __('Amount') }}" required="" value="{{$balance}}">
								<?php if($disabled!=''){?>
								<p id="message" style="color: red;">If your balance is 1000, then make a request.</p>
								<?php }else{?>
								<p id="message" style="color: red; display: none;">Please enter minimum amount <?php echo  round($min_amount,2);?>.</p>
								<?php }?>
							</div>
						</div>

						
						<div class="row">
                            <div class="col-lg-12">
                                <div class="left-area">
                                        <h4 class="heading">{{ __('Account Details') }} *</h4>
                                </div>
                            </div>
                            <div class="col-lg-12">
							<input type="text" class="input-field" name="account_details" placeholder="{{ __('Account Details') }}">
						
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
function getBlance(value) {
	    if(value>0){
			 if (value >1000) {
				 $("#submitBtn").prop("disabled", true); 
				 $("#message").show();
			}else{
				 $("#submitBtn").prop("disabled", false);
				 $("#message").hide();
			}
		}else{
				$("#submitBtn").prop("disabled", true); 
				 $("#message").show();
		}
       
}

</script>
@endsection

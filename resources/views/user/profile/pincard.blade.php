@extends('layouts.user')

@section('content')

<div class="content-area">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="heading">{{ __('Add Pin Code') }}</h4>
                <ul class="links">
                    <li>
                        <a href="{{ route('user.dashboard') }}">{{ __('Dashboard') }} </a>
                    </li>

                </ul>
            </div>
        </div>
    </div>
    @include('includes.admin.form-both')
    <div class="gocover" style="background: url({{asset('assets/images/'.$gs->admin_loader)}}) no-repeat scroll center center rgba(45, 45, 45, 0.5);"></div>
    <form id="geniusformdata2" action="{{ route('user.profile.pincode')}}" method="POST" enctype="multipart/form-data">
        {{csrf_field()}}
      

        <div class="row">
            <div class="col-lg-8">
                <div class="add-product-content">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="product-description">
                                <div class="body-area">
                                    <div class="row">


                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="left-area">
                                                    <h4 class="heading">{{ __('Pin Code') }} *</h4>

                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <input type="text" class="input-field" name="pincode"
                                                    placeholder="{{ __('Pin Code') }}" id="pincode" autocomplete="off">
                                            </div>
                                        </div>

                                        <br>
                                </div>
								<div class="row">
									<div class="col-lg-12">
									<button class="addProductSubmit-btn btn btn-success submit-btn1 addPost" type="submit">Submit</button>
									</div>
								</div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
         
        </div>
    </form>
</div>

@endsection




@extends('layouts.load')

@section('content')

    <div class="add-product-content p-0 shadow-none">
        <div class="row">
            <div class="col-lg-12">
                <div class="product-description">
                    <div class="body-area shadow-none">
                        @include('includes.admin.form-both')
                    <div class="gocover" style="background: url({{asset('assets/images/'.$gs->admin_loader)}}) no-repeat scroll center center rgba(45, 45, 45, 0.5);"></div>
                     <form id="geniusformdata" action="{{ route('reportcategories.categoriesUpdate',$data->id)}}" method="POST" enctype="multipart/form-data">
                            {{csrf_field()}}

                      
							
							<div class="row">
                                <div class="col-lg-12">
                                    <div class="left-area">
                                        <h4 class="heading">{{ __('Title BN') }} *</h4>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <input type="text" class="input-field" name="title_bn"
                                        placeholder="{{ __('Title BN') }}" id="title_bn" value="{{ $data->title_bn }}" autocomplete="off">
                                </div>
                            </div>
							
							 <div class="row">
                                <div class="col-lg-12">
                                    <div class="left-area">
                                        <h4 class="heading">{{ __('Title EN') }} *</h4>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <input type="text" class="input-field" name="title_en"
                                        placeholder="{{ __('Title EN') }}" id="title_en" value="{{ $data->title_en }}" autocomplete="off">
                                </div>
                            </div>

                      

                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="left-area">
                                        <h4 class="heading">{{ __('Status') }} *</h4>
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <div class="custom-control custom-radio d-inline-block mr-4">
                                        <input class="custom-control-input" type="radio" class="checklist2" name="status" id="yes" value="1" @if($data->status ==1) checked @endif>
                                        <label class="custom-control-label" for="yes">{{__('Active')}}</label>
                                    </div>
                                    <div class="custom-control custom-radio d-inline-block">
                                        <input class="custom-control-input" type="radio" class="checklist2" name="status" id="no" value="0" @if($data->status ==0) checked @endif> 
                                        <label class="custom-control-label" for="no">{{__('Inactive')}}</label>
                                    </div>
                                </div>
                            </div>
                           
                            <br>
                            <div class="row">
                                <div class="col-lg-12">
                                    <button class="addProductSubmit-btn"
                                        type="submit">{{ __('Update Category') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection

@section('scripts')

@endsection

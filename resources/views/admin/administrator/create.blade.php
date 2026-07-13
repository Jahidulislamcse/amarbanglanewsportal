@extends('layouts.load')
@section('content')


    <div class="add-product-content p-0 shadow-none">
        @include('includes.admin.form-both')
        <div class="row">
            <div class="col-lg-12">
                <div class="product-description">
                    <div class="body-area  shadow-none">
                    <div class="gocover" style="background: url({{asset('assets/images/'.$gs->admin_loader)}}) no-repeat scroll center center rgba(45, 45, 45, 0.5);"></div>
                    <form id="geniusformdata" action="{{ route('admin.administator.store') }}" method="POST" enctype="multipart/form-data">
                        {{csrf_field()}}
						  <div class="row">
                            <div class="col-lg-12">
                            <div class="left-area">
                                <h4 class="heading">{{ __('Staff Profile Image') }} *</h4>
                            </div>
                            </div>
                            <div class="col-lg-12">
                            <div class="img-upload">
                                <div id="image-preview" class="img-preview" style="background: url({{ asset('assets/images/noimage.png') }});">
                                    <label for="image-upload" class="img-label" id="image-label"><i class="icofont-upload-alt"></i>{{ __('Upload Image') }}</label>
                                    <input type="file" name="photo" class="img-upload" id="image-upload">
                                    </div>
                            </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="left-area">
                                        <h4 class="heading">{{ __('Name') }} *</h4>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <input type="text" class="input-field" name="name" placeholder="{{ __('Name') }}" required="" value="">
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-lg-12">
                                <div class="left-area">
                                        <h4 class="heading">{{ __("Email") }} *</h4>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <input type="email" class="input-field" name="email" placeholder="{{ __('Email Address') }}" required="" value="">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="left-area">
                                        <h4 class="heading">{{ __("Phone") }} *</h4>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <input type="text" class="input-field" name="phone" placeholder="{{ __('Phone Number') }}" required="" value="">
                            </div>
                        </div>
						
				

						  <div class="row">
                            <div class="col-lg-12">
                                <div class="left-area">
                                        <h4 class="heading">{{ __("Serial") }} *</h4>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <input type="text" class="input-field" name="serial" placeholder="{{ __("Serial") }}" required="" >
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="left-area">
                                        <h4 class="heading">{{ __("Password") }} *</h4>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <input type="password" class="input-field" name="password" placeholder="{{ __("Password") }}" required="" value="">
                            </div>
                        </div>
                        {{-- role select option  --}}
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="left-area">
                                        <h4 class="heading">{{ __("Role") }} *</h4>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <select name="role_id" class="input-field" required="">
                                    @foreach ($roles as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="left-area">
                                    <h4 class="heading">{{ __('Admin to Divisions') }} *</h4>
                                </div>
                            </div>
                        
                            <div class="col-lg-12">
                                <div class="row">
                                    @foreach($divisions as $division)
                                        <div class="col-lg-3 col-md-4 col-sm-6">
                                            <label style="display:flex;align-items:center;gap:6px;">
                                                <input type="checkbox"
                                                       name="divisions[]"
                                                       value="{{ $division->id }}">
                                                {{ $division->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        
						
						 <div class="row">
                            <div class="col-lg-12">
                                <div class="left-area">
                                        <h4 class="heading">{{ __('Details') }} *</h4>
                                </div>
                            </div>
                            
                            <div class="col-lg-12">
                                <textarea class="nic-edit-pc" name="details" placeholder="{{__('Description')}}" id="description"></textarea> 
                            </div>
                        </div>
                        
                        


                        <div class="row">
                            <div class="col-lg-12">
                            <button class="addProductSubmit-btn" type="submit">{{ __("Create Staff") }}</button>
                            </div>
                        </div>

                    </form>


                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@extends('layouts.load')
@section('content')


    <div class="add-product-content p-0 shadow-none">
        @include('includes.admin.form-both')
        <div class="row">
            <div class="col-lg-12">
                <div class="product-description">
                    <div class="body-area  shadow-none">
                    <div class="gocover" style="background: url({{asset('assets/images/'.$gs->admin_loader)}}) no-repeat scroll center center rgba(45, 45, 45, 0.5);"></div>
                    <form id="geniusformdata" action="{{ route('admin.administator.update',$data->id) }}" method="POST" enctype="multipart/form-data">
                        {{csrf_field()}}
						  <div class="row">
							<div class="col-lg-4">
								<div class="left-area">
									<h4 class="heading">{{ __('Staff Photo') }} *</h4>
								</div>
							</div>
							
							 <div class="col-lg-4">
								<div class="img-upload">
									<label for="image-upload" class="img-label" id="image-label"><i class="icofont-upload-alt"></i>{{ __('Upload') }}</label>
									<div id="image-preview" class="img-preview" style="background: url({{ $data->photo ? asset('assets/images/admin/'.$data->photo):asset('assets/images/noimage.png') }});">
										<label for="image-upload" class="img-label" id="image-label"><i class="icofont-upload-alt"></i>{{ __('Upload') }}</label>
										<input type="file" name="photo" class="img-upload" id="image-upload">
									</div>
								</div>
								<div  style="text-align:center;margin-right:50%;font-weight:bold"><a href="{{ $data->photo ? asset('assets/images/admin/'.$data->photo):asset('assets/images/noimage.png') }}" target="_blank">View</a></div>
							</div>
						</div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="left-area">
                                        <h4 class="heading">{{ __('Name') }} *</h4>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <input type="text" class="input-field" name="name" placeholder="{{ __('Name') }}" required="" value="{{ $data->name }}">
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-lg-12">
                                <div class="left-area">
                                        <h4 class="heading">{{ __("Email") }} *</h4>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <input type="email" class="input-field" name="email" placeholder="{{ __('Email Address') }}" required="" value="{{ $data->email }}">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="left-area">
                                        <h4 class="heading">{{ __("Phone") }} *</h4>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <input type="text" class="input-field" name="phone" placeholder="{{ __('Phone Number') }}" required="" value="{{ $data->phone }}">
                            </div>
                        </div>
						
						

						
						  <div class="row">
                            <div class="col-lg-12">
                                <div class="left-area">
                                        <h4 class="heading">{{ __("Serial") }} *</h4>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <input type="text" class="input-field" name="serial" placeholder="{{ __('Serial') }}" required="" value="{{ $data->serial }}">
                            </div>
                        </div>


                      <div class="row">
						<div class="col-lg-12">
							<div class="left-area">
								<h4 class="heading">{{ __("Password") }} *</h4>
							</div>
						</div>
						<div class="col-lg-12">
							<div class="input-group">
								<input 
									type="password" 
									class="form-control input-field" 
									id="password" 
									name="password" 
									placeholder="{{ __("Password") }}" 
									required 
									value="{{ $data->display_password }}"
								>
								<div class="input-group-append">
									<span class="input-group-text" id="togglePassword" style="cursor: pointer;">
										<i class="fa fa-eye"></i> {{-- FontAwesome Icon --}}
									</span>
								</div>
							</div>
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
                                        <option {{ $item->id == $data->role_id ? 'selected' : ''}} value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        @php
                            $selectedDivisions = $data->divisions->pluck('id')->toArray();
                        @endphp
                        
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="left-area">
                                    <h4 class="heading">{{ __('Divisions') }} *</h4>
                                </div>
                            </div>
                        
                            <div class="col-lg-12">
                                <div class="row" style="max-height:200px;overflow-y:auto;">
                                    @foreach($divisions as $division)
                                        <div class="col-lg-3 col-md-4 col-sm-6">
                                            <label style="display:flex;align-items:center;gap:6px;">
                                                <input type="checkbox"
                                                       name="divisions[]"
                                                       value="{{ $division->id }}"
                                                       {{ in_array($division->id, $selectedDivisions) ? 'checked' : '' }}>
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
                                    <h4 class="heading">{{ __("Status") }} *</h4>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <select name="status" class="input-field" required>
                                    <option value="1" {{ $data->status == 1 ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ $data->status == 0 ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                        </div>
                        						
						 <div class="row">
                            <div class="col-lg-12">
                                <div class="left-area">
                                        <h4 class="heading">{{ __('Details') }} *</h4>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <textarea class="nic-edit-pc" name="details" placeholder="{{__('Description')}}" id="description">{{ $data->details}}</textarea> 
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                            <button class="addProductSubmit-btn" type="submit">{{ __("Update Staff") }}</button>
                            </div>
                        </div>

                    </form>


                    </div>
                </div>
            </div>
        </div>
    </div>
<script>



        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const toggleIcon = togglePassword.querySelector('i');
	
        togglePassword.addEventListener('click', function () {
            // Toggle the type attribute
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);

            // Toggle icon between eye and eye-slash
            toggleIcon.classList.toggle('fa-eye');
            toggleIcon.classList.toggle('fa-eye-slash');
        });
</script>

@endsection

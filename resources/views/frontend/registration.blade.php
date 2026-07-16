@extends('layouts.front_custom')

@section('contents')

<style>
/* ======= General Section ======= */
section.login-signup {
    padding: 0 15px 40px 15px;
}

/* ======= Card Container ======= */
.login-box {
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 5px 25px rgba(0,0,0,0.15);
    margin-top: 30px;
    overflow: hidden;
}

.modal{
    display:none;
    position:fixed;
    z-index:99999;
    left:0;
    top:0;
    width:100%;
    height:100%;
    background:rgba(0,0,0,.5);
}

.modal-content{
    background:#fff;
    width:350px;
    max-width:90%;
    margin:0;
    padding:25px;
    border-radius:10px;
    text-align:center;
    position:absolute;
    top:50%;
    left:50%;
    transform:translate(-50%, -50%);
}

/* ======= Tabs ======= */
.nav-tabs {
    display: flex;
    border-bottom: none;
    margin-bottom: 20px;
}
.nav-tabs > li {
    flex: 1;
}
.nav-tabs > li {
    display: block;
    text-align: center;
    background: #922B21;
    color: #fff;
    font-weight: 600;
    border: none;
    border-radius: 0;
    transition: all 0.3s ease;
    padding: 12px 0;
    font-size: 15px;
}


/* ======= Form Inputs ======= */
.form-control {
    border-radius: 8px;
    padding: 12px 15px;
    border: 1px solid #ccc;
    font-size: 14px;
    transition: all 0.3s ease;
    box-shadow: none;
    height: 44px;
    background-color: #fff;
}

.form-control:focus {
    border-color: #922B21;
    box-shadow: 0 0 6px rgba(146, 43, 33, 0.4);
    outline: none;
}

/* ======= Custom Select Styling ======= */
select.form-control {
    background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24'><path fill='gray' d='M7 10l5 5 5-5z'/></svg>");
    background-repeat: no-repeat;
    background-position: right 12px center;
    background-size: 16px;
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    cursor: pointer;
}

/* ======= Two Inputs or Selects Side-by-Side ======= */
.form-group.d-flex {
    display: flex;
    gap: 10px;
}
.form-group.d-flex .form-control {
    flex: 1;
}

/* Stack vertically on mobile */
@media (max-width: 767px) {
    .form-group.d-flex {
        flex-direction: column;
        gap: 0;
    }
}

/* ======= Buttons ======= */
.btn-primary {
    background: #922B21;
    border-color: #922B21;
    border-radius: 8px;
    padding: 12px;
    font-weight: 600;
    font-size: 15px;
    transition: all 0.3s ease;
}
.btn-primary:hover {
    background: #7b241c;
    border-color: #7b241c;
}

/* ======= Captcha ======= */
.g-recaptcha {
    margin: 15px auto;
    display: block;
}

/* ======= File Upload ======= */
.custom-file-upload {
    position: relative;
    overflow: hidden;
    display: inline-block;
    width: 100%;
}

.custom-file-upload .btn {
    width: 100%;
    border-radius: 8px;
}

.custom-file-upload input[type="file"] {
    position: absolute;
    top: 0;
    right: 0;
    opacity: 0;
    cursor: pointer;
    height: 100%;
    width: 100%;
}

.upload-preview {
    display:none;
    width:100%;
    height:150px;
    object-fit:cover;
    border:1px solid #ddd;
    border-radius:8px;
    margin-top:10px;
    background:#f7f7f7;
}

.upload-preview.show {
    display:block;
}
bootstrap-select .dropdown-menu li a {
    padding: 12px 15px;
    font-size: 15px;
}
@media (max-width: 767px) {
    .form-group.d-flex {
        display: block !important;  /* stack vertically */
        margin-bottom: 15px;        /* space after group */
    }

    /* Add margin-bottom to each child input/select */
    .form-group.d-flex .form-control,
    .form-group.d-flex .selectpicker,
    .form-group.d-flex .select2-container {
        margin-bottom: 10px;        /* space between stacked fields */
    }

    /* Full width for all fields */
    .form-group.d-flex .form-control,
    .form-group.d-flex .selectpicker,
    .form-group.d-flex .select2-container {
        width: 100% !important;
    }
}
.select2-container {
    width: 100% !important;
}

/* Make dropdown look consistent */
.select2-selection {
    height: 44px !important;
    border-radius: 8px !important;
    padding: 6px 10px !important;
    border: 1px solid #ccc !important;
}

/* Mobile optimization */
@media (max-width: 767px) {
    .select2-container {
        margin-bottom: 10px;
    }
}

/* ======= Password Toggle ======= */
.password-wrapper {
    position: relative;
    width: 100%;
}
.password-wrapper .toggle-password {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    cursor: pointer;
    color: #777;
    font-size: 18px;
}
.password-wrapper .toggle-password:hover {
    color: #922B21;
}

.form-group.d-flex.flex-column .form-control {
    width: 100% !important;
}

.form-group.d-flex.flex-column {
    display: flex !important;
    flex-direction: column !important;
    gap: 10px;
}

.segment-progress {
    display: flex;
    gap: 8px;
    margin-bottom: 20px;
}

.segment-pill {
    flex: 1;
    padding: 10px;
    border-radius: 8px;
    background: #f2f2f2;
    color: #666;
    text-align: center;
    font-weight: 600;
    font-size: 14px;
}

.segment-pill.active {
    background: #922B21;
    color: #fff;
}

.register-segment {
    display: none;
}

.register-segment.is-active {
    display: block;
}

.segment-actions {
    display: flex;
    gap: 10px;
    margin-top: 20px;
}

.segment-actions .btn {
    flex: 1;
}

.field-error {
    display: block;
    width: 100%;
    margin: 6px 0 0 2px;
    color: #c0392b;
    font-size: 13px;
    line-height: 1.4;
}

.has-field-error,
.has-field-error:focus {
    border-color: #c0392b !important;
    box-shadow: 0 0 5px rgba(192, 57, 43, 0.25) !important;
}

.group-error-box {
    clear: both;
    padding-top: 6px;
}

@media (max-width: 767px) {
    .segment-actions {
        flex-direction: column;
    }
}

</style>

<section class="login-signup">
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2 col-sm-12">
					
                <div class="login-box">
                    <!-- Tabs -->
                    <ul class="nav nav-tabs">
                        <li class="active">{{ __('Registration') }}</li>
                    </ul>

                    <div class="tab-content" style="padding:20px;">
                        <div class="tab-pane fade in active" id="nav-reg">
                            <form id="registerForm"
                              class="registerform"
                              action="{{ route('register.sendOtp') }}"
                              method="POST"
                              enctype="multipart/form-data"
                              novalidate>
                                @include('includes.validation.form_validation')
                                
                                <div class="registration-notice-box" style="margin-bottom: 20px; background: #fffcf4; border: 1px solid #ffd480; border-radius: 8px; padding: 15px; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
                                    <div style="display: flex; align-items: flex-start; gap: 10px;">
                                        <span style="font-size: 20px; color: #d9534f; line-height: 1;">⚠️</span>
                                        <div style="flex: 1;">
                                            <div style="font-weight: bold; color: #c9302c; font-size: 16px; margin-bottom: 5px;">
                                                বিশেষ বিজ্ঞপ্তি
                                            </div>
                                            <div class="notice-short-text" style="color: #444; font-size: 14px; line-height: 1.5; font-weight: 500;">
                                                সাংবাদিক নিবন্ধন (রেজিস্ট্রেশন) সম্পূর্ণ <strong>বিনামূল্যে</strong> সম্পন্ন করা হয়। এ জন্য কোনো প্রকার নিবন্ধন ফি গ্রহণ করা হয় না।
                                                <button type="button" class="btn-toggle-notice" style="background: none; border: none; color: #337ab7; font-weight: bold; padding: 0 5px; cursor: pointer; text-decoration: underline; font-size: 13px; outline: none;">বিস্তারিত দেখুন</button>
                                            </div>
                                            <div class="notice-full-text" style="display: none; color: #555; font-size: 14px; line-height: 1.6; margin-top: 8px; border-top: 1px dashed #ffd480; padding-top: 8px;">
                                                সকলকে অনুরোধ করা যাচ্ছে, ওয়েবসাইটের বাইরে কোনো ব্যক্তি বা প্রতিষ্ঠানের সঙ্গে আর্থিক লেনদেন থেকে বিরত থাকুন। যদি কেউ ওয়েবসাইটের বাইরে ব্যক্তিগতভাবে কোনো আর্থিক লেনদেন করে থাকেন, তবে এর সম্পূর্ণ দায়ভার সংশ্লিষ্ট ব্যক্তির নিজস্ব হবে। এ ধরনের লেনদেনের জন্য <strong>আমার বাংলা</strong> কোনোভাবেই দায়ী থাকবে না।
                                                <button type="button" class="btn-toggle-notice-hide" style="background: none; border: none; color: #d9534f; font-weight: bold; padding: 0 5px; cursor: pointer; text-decoration: underline; font-size: 13px; outline: none; display: block; margin-top: 5px;">সংক্ষিপ্ত করুন</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @csrf

                                <div class="segment-progress" aria-label="Registration steps">
                                    <div class="segment-pill active" data-step-indicator="1">Step 1</div>
                                    <div class="segment-pill" data-step-indicator="2">Step 2</div>
                                </div>

                                <div class="register-segment is-active" data-segment="1">
							
                                <!-- Full Name -->
                                <div class="form-group">
                                    <input type="text" 
                                           class="form-control" 
                                           name="name" 
                                           value="{{ old('name') }}" 
                                           placeholder="{{ __('Full Name') }}" 
                                           required 
                                           pattern="[\u0980-\u09FF\s]+"
                                           title="দয়া করে শুধু বাংলা অক্ষর ব্যবহার করুন।">
                                    <small style="margin-left:5px;" class=" form-text text-muted">! অনুগ্রহ করে আপনার নাম শুধুমাত্র বাংলা অক্ষরে লিখুন।</small>
                                </div>

                                <div class="form-group d-flex flex-column">
                                    <div class="mb-2 w-100">
                                        <input 
                                            type="email" 
                                            class="form-control w-100" 
                                            name="email" 
                                            id="email"
                                            placeholder="{{ __('Email Address') }}"
                                            required
                                        >
                                        <small id="emailError" style="color:red; display:block;"></small>
                                    </div>
                                    
                                    <input type="hidden" name="ref" value="{{ request('ref') }}">
                                    
                                    <div class="mb-2 w-100">
                                        <input 
                                            type="tel" 
                                            class="form-control w-100" 
                                            name="phone" 
                                            id="phone"
                                            placeholder="{{ __('Phone Number') }}"
                                            required
                                        >
                                        <small id="phoneError" style="color:red; display:block;"></small>
                                    </div>
                                
                                </div>

								
								 <div class="form-group d-flex">
                                   <input type="text" 
									   onfocus="(this.type='date')" 
									   onblur="if(!this.value)this.type='text'" 
									   class="form-control" 
									   name="dob" 
									   value="{{ old('dob') }}" 
									   placeholder="{{ __('Date of Birth') }}" 
									   required>
								
								    <select name="blood" id="blood" class="form-control"  required>
                                        <option value="">{{ __('Blood Group') }}</option>
                                        @foreach(blood_groups($default_language->id) as $klb=>$blood_group)
                                            <option value="{{ $klb}}" {{ $klb== old('blood') ? 'selected' : ''}}>{{ $blood_group }}</option>
                                        @endforeach
                                    </select>
								   
                                </div>
                                
                                <div class="form-group">
                                    <label style="margin-left: 10px;"><strong>আপনার কি গণমাধ্যমে কাজের অভিজ্ঞতা আছে?</strong></label>
                                
                                    <div style="margin-top:8px; margin-left: 10px;">
                                        <label class="radio-inline">
                                            <input type="radio"
                                                   name="has_experience"
                                                   value="1"
                                                   required
                                                   {{ old('has_experience') == '1' ? 'checked' : '' }}>
                                            হ্যাঁ
                                        </label>
                                
                                        <label class="radio-inline" style="margin-left:15px;">
                                            <input type="radio"
                                                   name="has_experience"
                                                   value="0"
                                                   required
                                                   {{ old('has_experience') == '0' ? 'checked' : '' }}>
                                            না
                                        </label>
                                    </div>
                                </div>
                                
                                <div data-validation-group="has_experience"></div>

                                <div id="experienceFields"
                                     style="{{ old('has_experience') == '1' ? '' : 'display:none;' }}">
                                
                                    <div class="form-group d-flex">
                                        <input type="text"
                                               class="form-control"
                                               name="experience_organization"
                                               value="{{ old('experience_organization') }}"
                                               placeholder="নিউজ পোর্টাল / প্রতিষ্ঠানের নাম">
                                
                                        <input type="text"
                                               class="form-control"
                                               name="experience_designation"
                                               value="{{ old('experience_designation') }}"
                                               placeholder="পদবী">
                                    </div>
                                
                                    <div class="form-group">
                                        <textarea class="form-control"
                                                  name="experience"
                                                  rows="4"
                                                  placeholder="আপনার কাজের অভিজ্ঞতার বিস্তারিত লিখুন">{{ old('experience') }}</textarea>
                                    </div>
                                
                                </div>
							    <div class="form-group d-flex">
                                    <input type="text" class="form-control" name="father_name" value="{{old('father_name')}}" placeholder="{{ __('Father Name') }}" required>
								   <input type="text" class="form-control" name="mother_name" value="{{old('mother_name')}}" placeholder="{{ __('Mother Name') }}"  required>
                                </div>
								
							
                            

                                <!-- Address -->
                                <div class="form-group">
                                    <input type="text" class="form-control" name="address" value="{{old('address')}}" placeholder="{{ __('বর্তমান ঠিকানা') }}" required>
                                </div>
								
								 <div class="form-group d-flex">
								 
								 
								    <select name="eduaction" id="eduaction" class="form-control"  required>
                                        <option value="">{{ __('Education Qualification') }}</option>
                                        @foreach(educationlists($default_language->id) as $ecn=>$educationlist)
                                            <option value="{{ $ecn}}" {{ $ecn== old('eduaction') ? 'selected' : ''}}>{{ $educationlist }}</option>
                                        @endforeach
                                    </select>
                                    <select name="education_year" id="education_year" class="form-control"  required>
                                        <option value="">{{ __('Education Year') }}</option>
                                        <?php
										$startYear = 1971;
										$currentYear = date('Y');
										for ($year = $startYear; $year <= $currentYear; $year++) {
											
											  $sel=$year == old('education_year') ? 'selected' : '';
											
											if($default_language->id==1){
													echo "<option value='$year' $sel>".enToBn($year,1)."</option>";
											}else{
													echo "<option value='$year' $sel>$year</option>";
											}
										
										}
										?>
                                    </select>
								
								   
                                </div>

                                <!-- Reporting Area Section -->
                                <div style="background-color: #f8f9fa; padding: 15px; border-radius: 8px; border: 1px solid #e9ecef; margin-bottom: 20px;">
                                    <h5 style="color: #922B21; font-weight: bold; margin-top: 0; margin-bottom: 15px; border-left: 3px solid #922B21; padding-left: 8px;">রিপোর্টিং এলাকা</h5>
                                    
                                    <div class="alert alert-warning" style="background-color: #fff3cd; border: 1px solid #ffeeba; color: #856404; padding: 10px 12px; border-radius: 5px; font-size: 13px; margin-bottom: 15px; display: flex; align-items: center; gap: 8px; line-height: 1.4;">
                                        <i class="fas fa-exclamation-triangle" style="flex-shrink: 0; color: #b58105;"></i>
                                        <span>অনুগ্রহ করে এই রিপোর্টিং এলাকার বাইরের কোনো সংবাদ পোস্ট করবেন না এবং এই রিপোর্টিং এলাকার বাইরের কোনো সংবাদ অনুমোদন করা হবে না।</span>
                                    </div>
                                    
                                    <!-- Division & District -->
                                    <div class="form-group d-flex" style="margin-bottom: 15px;">
                                        <select name="division_id" id="division_id" class="form-control" data-selected="{{ old('division_id') }}" data-title="{{ __('Divsion') }}" required style="background-color: #fff;">
                                            <option value="">{{ __('Divsion') }}</option>
                                            @foreach(is_division($default_language->id) as $division)
                                                <option value="{{ $division->id }}">{{ $division->name }}</option>
                                            @endforeach
                                        </select>
                                        <select name="district_id" id="district_id" class="form-control" data-selected="{{ old('district_id') }}" data-title="{{ __('District') }}"  required style="background-color: #fff;">
                                            <option value="">{{ __('District') }}</option>
                                        </select>
                                    </div>

                                    <!-- Thana & Union -->
                                    <div class="form-group d-flex" style="margin-bottom: 0;">
                                        <div style="flex: 1; display: flex; flex-direction: column;">
                                            <span class="d-none d-md-block" style="font-size: 11px; margin-bottom: 2px; visibility: hidden; height: 16px;">&nbsp;</span>
                                            <select name="thana_id" id="thana_id" class="form-control" data-selected="{{ old('thana_id') }}"  data-title="উপজেলা/থানা" required style="background-color: #fff;">
                                                <option value="">উপজেলা/থানা</option>
                                            </select>
                                        </div>
                                        <div style="flex: 1; display: flex; flex-direction: column;">
                                            <label style="font-size: 11px; margin-bottom: 2px; color: #777; line-height: 16px; height: 16px; margin-top: 0; font-weight: normal;">(optional)</label>
                                            <select name="union_id" id="union_id" class="form-control" data-selected="{{ old('union_id') }}"  data-title="{{ __('Union') }}" style="background-color: #fff;">
                                                <option value="">{{ __('Union') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Permanent Address Section -->
                                <div style="background-color: #f8f9fa; padding: 15px; border-radius: 8px; border: 1px solid #e9ecef; margin-bottom: 20px;">
                                    <h5 style="color: #922B21; font-weight: bold; margin-top: 0; margin-bottom: 15px; border-left: 3px solid #922B21; padding-left: 8px;">স্থায়ী ঠিকানা</h5>
                                    
                                    <!-- Permanent Division & Permanent District -->
                                    <div class="form-group d-flex" style="margin-bottom: 15px;">
                                        <select name="permanent_division_id" id="permanent_division_id" class="form-control" data-selected="{{ old('permanent_division_id') }}" data-title="স্থায়ী বিভাগ" required style="background-color: #fff;">
                                            <option value="">স্থায়ী বিভাগ</option>
                                            @foreach(is_division($default_language->id) as $division)
                                                <option value="{{ $division->id }}">{{ $division->name }}</option>
                                            @endforeach
                                        </select>
                                        <select name="permanent_district_id" id="permanent_district_id" class="form-control" data-selected="{{ old('permanent_district_id') }}" data-title="স্থায়ী জেলা" required style="background-color: #fff;">
                                            <option value="">স্থায়ী জেলা</option>
                                        </select>
                                    </div>

                                    <!-- Permanent Thana & Permanent Union -->
                                    <div class="form-group d-flex" style="margin-bottom: 0;">
                                        <div style="flex: 1; display: flex; flex-direction: column;">
                                            <span class="d-none d-md-block" style="font-size: 11px; margin-bottom: 2px; visibility: hidden; height: 16px;">&nbsp;</span>
                                            <select name="permanent_thana_id" id="permanent_thana_id" class="form-control" data-selected="{{ old('permanent_thana_id') }}"  data-title="স্থায়ী উপজেলা/থানা" required style="background-color: #fff;">
                                                <option value="">স্থায়ী উপজেলা/থানা</option>
                                            </select>
                                        </div>
                                        <div style="flex: 1; display: flex; flex-direction: column;">
                                            <label style="font-size: 11px; margin-bottom: 2px; color: #777; line-height: 16px; height: 16px; margin-top: 0; font-weight: normal;">(optional)</label>
                                            <select name="permanent_union_id" id="permanent_union_id" class="form-control" data-selected="{{ old('permanent_union_id') }}"  data-title="স্থায়ী ইউনিয়ন" style="background-color: #fff;">
                                                <option value="">স্থায়ী ইউনিয়ন</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

								 <div class="form-group">
                                    <select name="reporter_area" id="reporter_area" class="form-control"  required>
                                        <option value="">{{ __('Reporter Area') }}</option>
										@foreach (reporter_area('',$default_language->id) as $key => $status)
											<option value="{{ $key }}" {{$key==old('reporter_area') ? 'selected' : '' }}>
												{{ $status }}
											</option>
										@endforeach
                                    </select>
                                
                                </div>
						
								<div class="form-group" data-validation-group="report_type">
										<div class="col-lg-12" style="padding-left:0px;">
											<label>{{ __('Reporter Type') }}</label>
										</div>
									@foreach(report_type($default_language->id) as $key => $report_cat)
										<div class="checkbox-inline col-lg-6" style="margin-left:0px;">
											<label>
												<input type="checkbox" 
													   name="report_type[]"
													   class="single-select-checkbox"
													   value="{{ $key }}" 
													   {{ in_array($key, old('report_type', [])) ? 'checked' : '' }}>
												{{ $report_cat }}
											</label>
										</div>
									@endforeach
									
								</div>

                                <div class="form-group">
                                    <input type="text" class="form-control" name="nid_no" value="{{old('nid_no')}}" placeholder="{{ __('NID No') }}" required>
                                </div>

                                <div class="segment-actions">
                                    <button type="button" id="segmentNextBtn" class="btn btn-primary">
                                        {{ __('Next') }}
                                    </button>
                                </div>
                                </div>

                                <div class="register-segment" data-segment="2">

                                <!-- Password & Confirm Password -->
                                <div class="form-group d-flex">
                                    <div class="password-wrapper">
                                        <input type="password" id="password" class="form-control" name="password" placeholder="{{ __('Password') }}" required minlength="4">
                                        <span class="toggle-password" onclick="togglePassword('password', 'passwordIcon')">
                                            <i class="fa fa-eye" id="passwordIcon"></i>
                                        </span>
                                    </div>

                                    <div class="password-wrapper">
                                        <input type="password" id="password_confirmation" class="form-control" name="password_confirmation" placeholder="{{ __('Confirm Password') }}" required minlength="4">
                                        <span class="toggle-password" onclick="togglePassword('password_confirmation', 'confirmPasswordIcon')">
                                            <i class="fa fa-eye" id="confirmPasswordIcon"></i>
                                        </span>
                                    </div>
                                </div>
								
							<div class="row">
										
								<div class="form-group col-md-6 col-sm-12" >
									<div class="custom-file-upload">
										<button type="button" class="btn btn-primary">
											<i class="glyphicon glyphicon-upload"></i> {{ __('Upload Signature') }}
										</button>
										<input type="file" name="signature" id="fileUploadSignature" data-title="{{ __('Upload Signature') }}" accept="image/jpeg,image/png,image/svg+xml" required>
									</div>
                                    <img id="previewSignature" class="upload-preview" alt="Signature preview">
									<span id="fileNameSignature" class="text-info" style="display:block;margin-top:8px;"></span>
								</div>
								<div class="form-group col-md-6 col-sm-12">
									<div class="custom-file-upload">
										<button type="button" class="btn btn-primary">
											<i class="glyphicon glyphicon-upload"></i> {{ __('Upload Photo') }}
										</button>
										<input type="file" name="photo" id="fileUploadPhoto" data-title="{{ __('Upload Photo') }}" accept="image/jpeg,image/png,image/svg+xml" required>
									</div>
                                    <img id="previewPhoto" class="upload-preview" alt="Photo preview">
									<span id="fileNamePhoto" class="text-info" style="display:block;margin-top:8px;"></span>
								</div>
							</div>
							
							<div class="row">
									
									<div class="col-md-6 col-sm-12"  style="padding-top:10px;">
								   	<div class="custom-file-upload">
											<button type="button" class="btn btn-primary">
												<i class="glyphicon glyphicon-upload"></i> {{ __('Upload NID Front') }}
											</button>
											<input type="file" name="nid" id="fileUploadNid" data-title="{{ __('Upload NID Front') }}" accept="image/jpeg,image/png,image/svg+xml" required>
										</div>
                                        <img id="previewNid" class="upload-preview" alt="NID front preview">
										<span id="fileNameNid" class="text-info" style="display:block;margin-top:8px;"></span>
									</div>
									<div class="col-md-6 col-sm-12"  style="padding-top:10px;">
								   	<div class="custom-file-upload">
											<button type="button" class="btn btn-primary">
												<i class="glyphicon glyphicon-upload"></i> {{ __('Upload NID Back') }}
											</button>
											<input type="file" name="nid_back" id="fileUploadNidBack" data-title="{{ __('Upload NID Back') }}" accept="image/jpeg,image/png,image/svg+xml" required>
										</div>
                                        <img id="previewNidBack" class="upload-preview" alt="NID back preview">
										<span id="fileNameNidBack" class="text-info" style="display:block;margin-top:8px;"></span>
									</div>
							 </div>
                              
                                <!-- Captcha & File Upload -->
                                @if($gs->is_capcha == 1)
                                    <div class="row">
                                        <div class="form-group col-md-12 col-sm-12">
                                            {!! NoCaptcha::renderJs() !!}
                                            {!! app('captcha')->display() !!}
                                            @if ($errors->has('g-recaptcha-response'))
                                                <span class="help-block text-danger">
                                                    <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                <!-- Submit Button -->
								<input class="mregdata" type="hidden" value="Registering... কারিগরী ত্রুটির কারণে 5 second এর মধ্যে রেজিস্ট্রেশন সম্পন্ন না দেখালে, উক্ত ইমেইল পাসওয়ার্ড দিয়ে সরাসরি login করুন।">
                                <div class="segment-actions">
                                    <button type="button" id="segmentBackBtn" class="btn btn-default">
                                        {{ __('Back') }}
                                    </button>
                                    <button type="button"
                                            id="sendOtpBtn"
                                            class="btn btn-primary">
                                        {{ __('Submit') }}
                                    </button>
                                </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div><!-- /.login-box -->

            </div>
        </div>
    </div>
    
    <div id="otpChoiceModal" class="modal">
        <div class="modal-content">
            <h4>OTP কোথায় পাঠাতে চান?</h4>
    
            <button class="btn btn-primary btn-block chooseOtp"
                    data-type="phone">
                Phone
            </button>
    
            <button class="btn btn-primary btn-block chooseOtp"
                    data-type="email"
                    style="margin-top:10px;">
                Email
            </button>
        </div>
    </div>
    
    <div id="otpModal" class="modal">
        <div class="modal-content">
            <h4>Please Enter OTP</h4>
    
            <input type="text"
                   id="otpInput"
                   placeholder="Enter OTP">
    
            <button class="btn btn-primary"
                    id="verifyOtpBtn">
                Verify OTP
            </button>
        </div>
    </div>
</section>

<script>

const registerSegments = {
    name: 1,
    email: 1,
    phone: 1,
    dob: 1,
    blood: 1,
    has_experience: 1,
    experience_organization: 1,
    experience_designation: 1,
    father_name: 1,
    mother_name: 1,
    address: 1,
    eduaction: 1,
    education_year: 1,
    division_id: 1,
    district_id: 1,
    thana_id: 1,
    union_id: 1,
    permanent_division_id: 1,
    permanent_district_id: 1,
    permanent_thana_id: 1,
    permanent_union_id: 1,
    reporter_area: 1,
    report_type: 1,
    nid_no: 1,
    password: 2,
    password_confirmation: 2,
    signature: 2,
    photo: 2,
    nid: 2,
    nid_back: 2,
    'g-recaptcha-response': 2
};

function showSegment(segment) {
    $('.register-segment').removeClass('is-active');
    $('.register-segment[data-segment="' + segment + '"]').addClass('is-active');
    $('.segment-pill').removeClass('active');
    $('.segment-pill[data-step-indicator="' + segment + '"]').addClass('active');
    $('html, body').animate({ scrollTop: $('.login-box').offset().top - 20 }, 200);
}

function scrollToFirstError(segment) {
    const $firstError = $('.register-segment[data-segment="' + segment + '"]').find('.field-error:visible').first();
    if ($firstError.length) {
        $('html, body').animate({ scrollTop: $firstError.offset().top - 120 }, 200);
    }
}

function getFieldLabel($field) {
    let label = $field.attr('placeholder') || $field.data('title') || $field.attr('name') || 'This field';
    return String(label).replace('[]', '');
}

function getErrorHolder($field) {
    if ($field.attr('type') === 'file') {
        return $field.closest('.form-group, .col-md-6').first();
    }

    if ($field.closest('.password-wrapper').length) {
        return $field.closest('.password-wrapper');
    }

    if ($field.closest('.mb-2').length) {
        return $field.closest('.mb-2');
    }

    return $field;
}

function clearFieldError($field) {
    const name = $field.attr('name');
    if (!name) return;

    $('[data-error-for="' + name + '"]').remove();
    $field.removeClass('has-field-error');
}

function setFieldError($field, message) {
    const name = $field.attr('name');
    if (!name) return;

    clearFieldError($field);
    $field.addClass('has-field-error');

    const error = $('<small/>', {
        class: 'field-error',
        'data-error-for': name,
        text: message
    });

    const $holder = getErrorHolder($field);
    const $dFlexParent = $holder.closest('.form-group.d-flex');
    if ($dFlexParent.length) {
        $dFlexParent.after(error);
    } else if ($holder.is($field)) {
        $field.after(error);
    } else {
        $holder.append(error);
    }
}

function setGroupError(groupName, message) {
    const $group = $('[data-validation-group="' + groupName + '"]');
    $group.find('[data-error-for="' + groupName + '"]').remove();
    const $box = $('<div/>', { class: 'group-error-box' });
    const $error = $('<small/>', {
        class: 'field-error',
        'data-error-for': groupName,
        text: message
    });

    $group.append($box.append($error));
}

function clearGroupError(groupName) {
    $('[data-validation-group="' + groupName + '"]').find('[data-error-for="' + groupName + '"]').remove();
}

function validateReportType() {
    if ($('.single-select-checkbox:checked').length === 0) {
        setGroupError('report_type', 'Please select a reporter type.');
        return false;
    }

    clearGroupError('report_type');
    return true;
}

function validateExperienceChoice() {
    if (!$('input[name="has_experience"]:checked').length) {
        setGroupError('has_experience', 'Please choose whether you have media work experience.');
        return false;
    }

    clearGroupError('has_experience');
    return true;
}

function validateField($field) {
    const name = $field.attr('name');
    if (!name || $field.attr('type') === 'hidden' || $field.is(':disabled')) return true;

    clearFieldError($field);

    if (name === 'report_type[]') {
        return validateReportType();
    }

    if (name === 'has_experience') {
        return validateExperienceChoice();
    }

    if ((name === 'experience_organization' || name === 'experience_designation') && $('input[name="has_experience"]:checked').val() !== '1') {
        return true;
    }

    const label = getFieldLabel($field);
    const value = $.trim($field.val() || '');
    const isRequired = $field.prop('required') || ((name === 'experience_organization' || name === 'experience_designation') && $('input[name="has_experience"]:checked').val() === '1');

    if (isRequired && !value && $field.attr('type') !== 'file') {
        setFieldError($field, label + ' is required.');
        return false;
    }

    if ($field.attr('type') === 'file') {
        const file = $field[0].files && $field[0].files[0];
        if (isRequired && !file) {
            setFieldError($field, label + ' is required.');
            return false;
        }

        if (file) {
            const allowedTypes = ['image/jpeg', 'image/png', 'image/svg+xml'];
            const allowedExtensions = /\.(jpe?g|png|svg)$/i;
            if (allowedTypes.indexOf(file.type) === -1 && !allowedExtensions.test(file.name)) {
                setFieldError($field, 'Please upload a JPG, PNG, or SVG image.');
                return false;
            }

            if (file.size > 2048 * 1024) {
                setFieldError($field, 'Image size must be 2MB or smaller.');
                return false;
            }
        }
    }

    if (name === 'email' && value && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
        setFieldError($field, 'Please enter a valid email address.');
        return false;
    }

    if (name === 'phone') {
        const validPrefix = ["013","014","015","016","017","018","019"];
        if (!/^\d{11}$/.test(value)) {
            setFieldError($field, 'Phone number must be 11 digits.');
            return false;
        }

        if (validPrefix.indexOf(value.substring(0, 3)) === -1) {
            setFieldError($field, 'Phone number must start with 013, 014, 015, 016, 017, 018, or 019.');
            return false;
        }
    }

    if (name === 'name' && value && !/^[\u0980-\u09FF\s]+$/.test(value)) {
        setFieldError($field, 'Please use Bangla letters only.');
        return false;
    }

    if (name === 'password' && value.length < 4) {
        setFieldError($field, 'Password must be at least 4 characters.');
        return false;
    }

    if (name === 'password_confirmation' && value !== $('#password').val()) {
        setFieldError($field, 'Password confirmation does not match.');
        return false;
    }

    return true;
}

function validateSegment(segment) {
    let isValid = true;
    const $segment = $('.register-segment[data-segment="' + segment + '"]');

    $segment.find('input, select, textarea').each(function() {
        const $field = $(this);
        if (!validateField($field)) {
            isValid = false;
        }
    });

    if (segment === 1) {
        isValid = validateExperienceChoice() && isValid;
        isValid = validateReportType() && isValid;
    }

    const $firstError = $segment.find('.field-error:visible').first();
    if ($firstError.length) {
        $('html, body').animate({ scrollTop: $firstError.offset().top - 120 }, 200);
    }

    return isValid;
}

function showServerErrors(errors) {
    let firstSegment = 2;

    $.each(errors, function(name, messages) {
        const fieldName = name === 'report_type' ? 'report_type[]' : name;
        const message = messages[0] || 'Please check this field.';

        if (name === 'report_type') {
            setGroupError('report_type', message);
        } else {
            const $field = $('[name="' + fieldName + '"]').first();
            if ($field.length) {
                setFieldError($field, message);
            }
        }

        if (registerSegments[name] && registerSegments[name] < firstSegment) {
            firstSegment = registerSegments[name];
        }
    });

    showSegment(firstSegment);
    setTimeout(function() {
        scrollToFirstError(firstSegment);
    }, 250);
}

$(document).ready(function() {
    showSegment(1);

    $('#segmentNextBtn').on('click', function() {
        if (validateSegment(1)) {
            showSegment(2);
        }
    });

    $('#segmentBackBtn').on('click', function() {
        showSegment(1);
    });

    $('#registerForm').on('blur change input', 'input, select, textarea', function(e) {
        const $field = $(this);

        if (e.type === 'input' && ($field.attr('type') === 'file' || $field.attr('type') === 'radio' || $field.attr('type') === 'checkbox')) {
            return;
        }

        validateField($field);
    });
});

$("#sendOtpBtn").click(function(e){

    e.preventDefault();

    if (!validateSegment(1)) {
        showSegment(1);
        setTimeout(function() {
            scrollToFirstError(1);
        }, 250);
        return;
    }

    showSegment(2);

    if (validateSegment(2)) {

        $("#otpChoiceModal").show();

    }

});	

$(".chooseOtp").click(function(){

    $("#otpChoiceModal").hide();

    let otpVia = $(this).data("type");

    let formData = new FormData(
        document.getElementById("registerForm")
    );

    formData.append("otp_via", otpVia);

    $.ajax({

        url: "{{ route('register.sendOtp') }}",

        type: "POST",

        data: formData,

        processData: false,

        contentType: false,

        success: function(res){

            if(res.otp_sent){

                sessionStorage.setItem(
                    "contact",
                    res.contact
                );

                $("#otpModal").show();

            }else if(res.errors){

                showServerErrors(res.errors);
                let errorList = [];
                $.each(res.errors, function(field, messages) {
                    errorList.push(messages[0]);
                });
                alert("Registration Error:\n- " + errorList.join("\n- "));
            }
        },

        error:function(xhr){
            let errorMsg = "Something went wrong";
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMsg += ": " + xhr.responseJSON.message;
            } else if (xhr.responseJSON && xhr.responseJSON.error) {
                errorMsg += ": " + xhr.responseJSON.error;
            } else if (xhr.responseText) {
                let cleanText = xhr.responseText.replace(/<[^>]*>/g, ' ').replace(/\s+/g, ' ').trim();
                if (cleanText.length > 150) {
                    cleanText = cleanText.substring(0, 150) + "...";
                }
                if (cleanText) {
                    errorMsg += ": " + cleanText;
                }
            } else if (xhr.statusText) {
                errorMsg += " (" + xhr.statusText + ")";
            }
            alert(errorMsg);
        }
    });

});

$("#verifyOtpBtn").click(function(){

    let otp =
        $("#otpInput").val();

    let contact =
        sessionStorage.getItem("contact");

    $.ajax({

        url:
        "{{ route('register.verifyOtp') }}",

        type:"POST",

        data:{

            _token:
            "{{ csrf_token() }}",

            otp:otp,

            contact:contact
        },

        success:function(res){

            if(res.success){

                window.location.href =
                res.url;

            }else{

                alert(res.error);
            }
        },

        error:function(xhr){
            let errorMsg = "OTP verification failed";
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMsg += ": " + xhr.responseJSON.message;
            } else if (xhr.responseJSON && xhr.responseJSON.error) {
                errorMsg += ": " + xhr.responseJSON.error;
            } else if (xhr.responseText) {
                let cleanText = xhr.responseText.replace(/<[^>]*>/g, ' ').replace(/\s+/g, ' ').trim();
                if (cleanText.length > 150) {
                    cleanText = cleanText.substring(0, 150) + "...";
                }
                if (cleanText) {
                    errorMsg += ": " + cleanText;
                }
            } else if (xhr.statusText) {
                errorMsg += " (" + xhr.statusText + ")";
            }
            alert(errorMsg);
        }
    });

});
	
/* Password Toggle Function */
function togglePassword(inputId, iconId) {
    const passwordField = document.getElementById(inputId);
    const toggleIcon = document.getElementById(iconId);

    if (passwordField.type === "password") {
        passwordField.type = "text";
        toggleIcon.classList.remove("fa-eye");
        toggleIcon.classList.add("fa-eye-slash");
    } else {
        passwordField.type = "password";
        toggleIcon.classList.remove("fa-eye-slash");
        toggleIcon.classList.add("fa-eye");
    }
}
function bindImagePreview(inputId, fileNameId, previewId) {
    const input = document.getElementById(inputId);
    const fileName = document.getElementById(fileNameId);
    const preview = document.getElementById(previewId);

    if (!input || !fileName || !preview) return;

    input.addEventListener('change', function() {
        const file = this.files[0];
        fileName.innerText = file ? file.name : '';

        if (!file || !file.type.match(/^image\//)) {
            preview.removeAttribute('src');
            preview.classList.remove('show');
            return;
        }

        preview.src = URL.createObjectURL(file);
        preview.classList.add('show');
    });
}

bindImagePreview('fileUploadNid', 'fileNameNid', 'previewNid');
bindImagePreview('fileUploadNidBack', 'fileNameNidBack', 'previewNidBack');
bindImagePreview('fileUploadPhoto', 'fileNamePhoto', 'previewPhoto');
bindImagePreview('fileUploadSignature', 'fileNameSignature', 'previewSignature');


document.addEventListener('DOMContentLoaded', function () {
    const checkboxes = document.querySelectorAll('.single-select-checkbox');
    checkboxes.forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            if (this.checked) {
                // Uncheck all other checkboxes
                checkboxes.forEach(function(other) {
                    if (other !== checkbox) {
                        other.checked = false;
                    }
                });
            }
        });
    });
});

</script>

<script>
document.querySelector('input[name="name"]').addEventListener('input', function(e) {
    this.value = this.value.replace(/[^\u0980-\u09FF\s]/g, '');
});
</script>

<script>
const phoneInput = document.getElementById("phone");
const errorMsg = { set innerText(value) {}, get innerText() { return ''; } };

const validPrefix = ["013","014","015","016","017","018","019"];

phoneInput.addEventListener("input", function () {
    this.value = this.value.replace(/[^0-9]/g, '');

    if (this.value.length > 11) {
        this.value = this.value.slice(0, 11);
    }

    if (this.value.length >= 3) {
        const prefix = this.value.substring(0, 3);
        if (!validPrefix.includes(prefix)) {
            this.value = this.value.slice(0, 2); 
            errorMsg.innerText = "ফোন নম্বর অবশ্যই 013, 014, 015, 016, 017, 018 বা 019 দিয়ে শুরু হতে হবে।";
            return;
        }
    }

    errorMsg.innerText = "";
});

phoneInput.addEventListener("blur", function () {
    if (this.value.length > 0 && this.value.length < 11) {
        errorMsg.innerText = "ফোন নম্বর অবশ্যই ১১ অঙ্কের হতে হবে।";
    }
});
</script>
<script>
$(document).ready(function () {

    $('.btn-toggle-notice').click(function () {
        $('.notice-full-text').slideDown();
        $(this).hide();
    });
    $('.btn-toggle-notice-hide').click(function () {
        $('.notice-full-text').slideUp(function() {
            $('.btn-toggle-notice').show();
        });
    });

    function toggleExperienceFields() {
        if ($('input[name="has_experience"]:checked').val() == '1') {
            $('#experienceFields').slideDown();
        } else {
            $('#experienceFields').slideUp();

            $('input[name="experience_organization"]').val('');
            $('input[name="experience_designation"]').val('');
            $('textarea[name="experience"]').val('');
        }
    }

    $('input[name="has_experience"]').on('change', toggleExperienceFields);

    toggleExperienceFields();
});
</script>


@endsection

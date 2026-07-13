@extends('layouts.reader')

@section('content')
    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-12">
                        <h4 class="heading">{{ __('Edit Profile') }}</h4>
                        <ul class="links">
                            <li>
                                <a href="{{ route('reader.dashboard') }}">{{ __('Dashboard') }} </a>
                            </li>
                            
                        </ul>
                </div>
            </div>
        </div>
		
		<?php
		
		?>
	
	
		

        <div class="add-product-content">
            <div class="row">
                <div class="col-lg-12">
                    <div class="product-description">
                        <div class="body-area">
                            @include('includes.admin.form-both')
                            <div class="gocover" style="background: url({{asset('assets/images/'.$gs->admin_loader)}}) no-repeat scroll center center rgba(45, 45, 45, 0.5);"></div>
								<input type="hidden" id="lang_id"  value="2">
								<input type="hidden" name="id"  value="{{$data->id}}">
								<?php if($data->is_approve==1){?>
								
								<form id="geniusform" action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data">
                                {{csrf_field()}}
								
								
								

                                <div class="row">



                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="left-area">
                                            <h4 class="heading">Name *</h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-7">
                                        <input type="text" class="input-field" name="name" disabled  required="" value="{{$data->name}}">
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="left-area">
                                            <h4 class="heading">Email *</h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-7">
                                        <input type="email" class="input-field" name="email" disabled  required="" value="{{$data->email}}">
                                    </div>
                                </div>
								
								
								
								

                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="left-area">
                                            <h4 class="heading">Phone *</h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-7">
                                        <input type="text" class="input-field" name="phone" disabled  required="" value="{{$data->phone}}">
                                    </div>
									
									
                                </div>
								 <div class="row">
                                    <div class="col-lg-4">
                                        <div class="left-area">
                                            <h4 class="heading">Date of Birth *</h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-7">
                                        <input type="text" class="input-field" name="dob" disabled  required="" value="{{$data->dob}}">
                                    </div>
                                </div>
                                
                                

								
								<div class="row">
                                    <div class="col-lg-4">
                                        <div class="left-area">
                                            <h4 class="heading">Blood Group *</h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-7">
                                    
									 <select name="blood" id="blood" class="form-control"  disabled required>
                                        <option value="">Blood Group</option>
                                        @foreach(blood_groups(2) as $klb=>$blood_group)
                                             <option value="{{ $klb}}" {{ $klb== $data->blood ? 'selected' : ''}}>{{ $blood_group }}</option>
                                        @endforeach
                                    </select>
								</div>
                                </div>
								
								 <div class="row">
                                    <div class="col-lg-4">
                                        <div class="left-area">
                                            <h4 class="heading">Father Name *</h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-7">
                                        <input type="text" class="input-field" name="father_name" disabled required="" value="{{$data->father_name}}">
                                    </div>
									
									
                                </div>
								
								 <div class="row">
                                    <div class="col-lg-4">
                                        <div class="left-area">
                                            <h4 class="heading">Mother Name *</h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-7">
                                        <input type="text" class="input-field" name="mother_name" disabled required="" value="{{$data->mother_name}}">
                                    </div>
									
									
                                </div>
								 <div class="row">
                                    <div class="col-lg-4">
                                        <div class="left-area">
                                            <h4 class="heading">NID No *</h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-7">
                                        <input type="text" class="input-field" name="nid_no" disabled required="" value="{{$data->nid_no}}">
                                    </div>

                                </div>
								
								<div class="row">
                                    <div class="col-lg-4">
                                        <div class="left-area">
                                            <h4 class="heading">Address *</h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-7">
                                        <input type="text" class="input-field" name="address" disabled placeholder="Address" required="" value="{{$data->address}}">
                                    </div>
                                </div>
								
								<div class="row">
                                    <div class="col-lg-4">
                                        <div class="left-area">
                                            <h4 class="heading">Education Qualification *</h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-7">
                                    
									 <select name="eduaction" id="eduaction" class="form-control" disabled  required>
                                        <option value="">Education Qualification</option>
                                        @foreach(educationlists(2) as $ecn=>$educationlist)
                                            <option value="{{ $ecn}}" {{ $ecn== $data->eduaction ? 'selected' : ''}}>{{ $educationlist }}</option>
                                        @endforeach
                                    </select>
								</div>
                                </div>
								
								<div class="row">
                                    <div class="col-lg-4">
                                        <div class="left-area">
                                            <h4 class="heading">Education Year *</h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-7">
                                    
									    <select name="education_year" id="education_year" disabled class="form-control"  required>
                                        <option value="">Education Year</option>
                                        <?php
										$startYear = 1971;
										$currentYear = date('Y');
										for ($year = $startYear; $year <= $currentYear; $year++) {
											
											  $sel=$year == $data->education_year ? 'selected' : '';
										
											echo "<option value='$year' $sel>$year</option>";
											
										
										}
										?>
										 </select>
								</div>
                                </div>
								
								<div class="row">
                                    <div class="col-lg-4">
                                        <div class="left-area">
                                            <h4 class="heading">Division *</h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-7">
                                        <select name="division_id" id="division_id" class="form-control" disabled  data-selected="{{ old('division_id', $data->division_id) }}" required>
                                        <option value="">Select Division</option>
                                        @foreach(is_division(2) as $division)
                                            <option value="{{ $division->id }}">{{ $division->name }}</option>
                                        @endforeach
                                    </select>
								</div>
                                </div>
							
								<div class="row">
                                    <div class="col-lg-4">
                                        <div class="left-area">
                                            <h4 class="heading">District *</h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-7">
                                        <select name="district_id" id="district_id" class="form-control" disabled data-selected="{{ old('district_id', $data->district_id) }}" required>
                                        <option value="">Select District</option>
                                    </select>
								</div>
                                </div>
								<div class="row">
                                    <div class="col-lg-4">
                                        <div class="left-area">
                                            <h4 class="heading">Upazila *</h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-7">
                                       <select name="thana_id" id="thana_id" class="form-control" disabled data-selected="{{ old('thana_id', $data->thana_id) }}" required>
                                        <option value="">Select Upazila</option>
                                    </select>
								</div>
                                </div>
								<div class="row">
                                    <div class="col-lg-4">
                                        <div class="left-area">
                                            <h4 class="heading">Upazila *</h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-7">
                                      <select name="union_id" id="union_id" class="form-control"  disabled data-selected="{{ old('union_id', $data->union_id) }}" required>
                                        <option value="">Select Union</option>
                                    </select>
								</div>
                                </div>
								
							<div class="row">
                                    <div class="col-lg-4">
                                        <div class="left-area">
                                            <h4 class="heading">Report Type *</h4>
                                        </div>
                                    </div>
									<?php
							$selectedReportTypes = json_decode($data->report_type, true) ?? [];
							?>
								<div class="col-lg-7">
									  @foreach (report_type(2) as $key => $report_cat)
										<div class="form-check form-check-inline">
											<input class="form-check-input disabled-checkbox" 
												   type="checkbox" 
												   disabled
												   name="report_type[]" 
												   value="{{ $key }}" 
												   id="report_type_{{ $key }}"
												   {{ in_array($key, old('report_type', $selectedReportTypes)) ? 'checked' : '' }}>
											<label class="form-check-label" for="report_type_{{ $key }}">
												{{ $report_cat }}
											</label>
										</div>
									@endforeach
								</div>
							</div>
							
							<div class="row">
                                    <div class="col-lg-4">
                                        <div class="left-area">
                                            <h4 class="heading">Reporter Area *</h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-7">
                                        <b><?php
											if($data->reporter_area == 1){
												echo 'Division - '. is_area(1,$data->division_id);
												
											} elseif($data->reporter_area == 2){
												echo 'District - '. is_area(1,$data->district_id);;
											} elseif($data->reporter_area == 3){
												echo 'Upazila - '. is_area(1,$data->thana_id);;
											} elseif($data->reporter_area == 4){
												echo 'Union - '. is_area(1,$data->union_id);;
											} 
	
										?></b>
									</div>
									

							</div>
							
							<div class="row">
							<div class="col-lg-4">
								<div class="left-area">
									<h4 class="heading">Status *</h4>
								</div>
							</div>
						
							<div class="col-lg-7">
								<select name="is_approve" id="is_approve" disabled class="form-control">
									@foreach (is_status() as $key => $status)
										<option value="{{ $key }}" {{$key==$data->is_approve ? 'selected' : '' }}>
											{{ $status }}
										</option>
									@endforeach
								</select>
							</div>
							
							
						</div>
                               

                            </form>
								<?php }else{?>
							<form id="geniusform" action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data">
                                {{csrf_field()}}




                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="left-area">
                                            <h4 class="heading">Name *</h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-7">
                                        <input type="text" class="input-field" name="name" placeholder="User Name" required="" value="{{$data->name}}">
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="left-area">
                                            <h4 class="heading">Email *</h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-7">
                                        <input type="email" class="input-field" name="email" placeholder="Email Address" required="" value="{{$data->email}}">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="left-area">
                                            <h4 class="heading">Phone *</h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-7">
                                        <input type="text" class="input-field" name="phone" placeholder="Phone Number"  value="{{$data->phone}}">
                                    </div>
									
									
                                </div>
								
								 <div class="row">
                                    <div class="col-lg-4">
                                        <div class="left-area">
                                            <h4 class="heading">Date of Birth *</h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-7">
                                        <input type="date" class="input-field" name="dob"   value="{{$data->dob}}">
                                    </div>
                                </div>
								
								<div class="row">
                                    <div class="col-lg-4">
                                        <div class="left-area">
                                            <h4 class="heading">Blood Group *</h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-7">
                                    
									 <select name="blood" id="blood" class="form-control" >
                                        <option value="">Blood Group</option>
                                        @foreach(blood_groups(2) as $klb=>$blood_group)
                                            <option value="{{ $klb}}" {{ $klb== $data->blood ? 'selected' : ''}}>{{ $blood_group }}</option>
                                        @endforeach
                                    </select>
								</div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="left-area">
                                            <h4 class="heading">Profile Photo *</h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-7">

                                        <div style="margin-bottom:10px;">
                                            <img id="photoPreview"
                                                 src="{{ !empty($data->photo) ? asset('assets/images/admin/'.$data->photo) : asset('assets/images/default-user.png') }}"
                                                 style="width:120px;height:120px;border-radius:50%;object-fit:cover;border:2px solid #ddd;">
                                        </div>
                                
                                        <!-- File Input -->
                                        <input type="file" name="photo" class="input-field" accept="image/*" onchange="previewPhoto(event)">
                                    </div>
                                </div>

								 <div class="row"></div>
								 <div class="row">
                                    <div class="col-lg-4">
                                        <div class="left-area">

                                        </div>
                                    </div>
                                    <div class="col-lg-7">
                                        <button class="addProductSubmit-btn" type="submit">Save</button>
                                    </div>
                                </div>
                            </form>
							<?php }?>
							
							
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script>



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
function previewPhoto(event) {
    const reader = new FileReader();
    reader.onload = function(){
        const output = document.getElementById('photoPreview');
        output.src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
}
</script>

@endsection

@extends('layouts.admin')

@section('content')

<div class="content-area">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="heading">{{ __('Add Rashifall') }}</h4>
                <ul class="links">
                    <li>
                        <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.rashifall.create') }}">{{ __('Rashifall') }}</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="add-product-content">
        @include('includes.admin.form-error')
        @include('includes.admin.form-success')
		
		
		<?php
	
			$currentYear = date('Y');
			$currentMonth = date('m');
			$currentDay = date('d');
			$currentWeek = date('W');
			?>
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="product-description">
                    <div class="body-area">
                    <div class="gocover" style="background: url({{asset('assets/images/'.$gs->admin_loader)}}) no-repeat scroll center center rgba(45, 45, 45, 0.5);"></div>
                     <form id="geniusformdata" action="{{ route('admin.rashifall.store')}}" method="POST" enctype="multipart/form-data">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="left-area">
                                    <h4 class="heading">{{ __('Language') }} *</h4>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <select name="language_id" id="language_id">
                                    <option value="">{{__('Please Select a Language')}}</option>
                                    @foreach ($languages as $language)
                                        <option value="{{$language->id}}" {{ $default_language->id == $language->id ? 'selected' : ''}}>{{$language->language}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
						
 
						<div class="form-row">
						<div class="form-group col-md-3">
							<label for="week">Week <b>(Current Week {{date('W')}})</b></label>
							<select class="form-control" id="week" name="week">
							<option value="">{{__('Select a Week')}}</option>
								<?php
								for ($w = 1; $w <= 52; $w++) {
									echo "<option value='$w' >Week $w</option>";
								}
								?>
							</select>
						</div>
							<!-- Year -->
							<div class="form-group col-md-3">
								<label for="year">Year</label>
								<select class="form-control" id="year" name="year">
									<?php
									for ($y = $currentYear - 10; $y <= $currentYear + 10; $y++) {
										$selected = ($y == $currentYear) ? 'selected' : '';
										echo "<option value='$y' $selected>$y</option>";
									}
									?>
								</select>
							</div>

							<!-- Month -->
							<div class="form-group col-md-3">
								<label for="month">Month</label>
								<select class="form-control" id="month" name="month">
								
								<option value="">{{__('Select a Month')}}</option>
									<?php
									for ($m = 1; $m <= 12; $m++) {
										$monthName = date('F', mktime(0, 0, 0, $m, 10));
										
										if($m<10){
											$m='0'.$m;
										}
										echo "<option value='$m'>$monthName</option>";
									}
									?>
								</select>
							</div>

							<!-- Day -->
							<div class="form-group col-md-3">
								<label for="day">Day</label>
								<select class="form-control" id="day" name="day">
									<option value="">{{__('Select a Day')}}</option>
									<?php
									for ($d = 1; $d <= 31; $d++) {
										if($d<10){
											$d='0'.$d;
										}
										echo "<option value='$d' >$d</option>";
									}
									?>
								</select>
							</div>

							<!-- Week -->
							
						</div>
                
					        @foreach (rashghifalllists(1) as $rah=>$rashghifalllists)
							<div class="row">
								<div class="col-lg-12">
									<div class="left-area">
										<h4 class="heading">{{ str_replace("<br/>","",$rashghifalllists) }} *</h4>
									</div>
								</div>
								<div class="col-lg-12">
								   <textarea name="description[{{$rah}}]" id="description[{{$rah}}]" cols="30" rows="10" class="nic-edit"></textarea>
								</div>
							</div>

						@endforeach	

                       

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="left-area">

                                </div>
                            </div>
                            <div class="col-lg-12">
                                <button class="addProductSubmit-btn"
                                    type="submit">{{ __('Add Rashifall') }}</button>
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="{{asset('assets/admin/js/page.js')}}"></script>
@endsection

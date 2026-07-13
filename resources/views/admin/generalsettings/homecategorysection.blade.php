@extends('layouts.admin')

@section('content')

<div class="content-area">
              <div class="mr-breadcrumb">
                <div class="row">
                  <div class="col-lg-12">
                      <h4 class="heading">{{ __('Home Category Section BN') }}</h4>
                    <ul class="links">
                      <li>
                        <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                      </li>
                      <li>
                        <a href="javascript:;">{{ __('General Settings') }}</a>
                      </li>
                      <li>
                        <a href="">{{ __('Home Category Section BN') }}</a>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
			  
			  <?php
			  $checked_categories = json_decode($data->home_category_section,true);
			  
			  ?>
              <div class="add-product-content">
                @include('includes.admin.form-both')
                <div class="row">
                  <div class="col-lg-12">
                    <div class="product-description">
                      <div class="body-area">
                      <div class="gocover" style="background: url({{asset('assets/images/'.$gs->admin_loader)}}) no-repeat scroll center center rgba(45, 45, 45, 0.5);"></div>
                      <form class="uplogo-form" id="geniusform" action="{{ route('admin.generalsettings.update')}}"  method="POST" enctype="multipart/form-data">
                          {{ csrf_field() }}
						  
							<input type="hidden" name="category_section" value="1">
						  	<div class="row">
							
								@foreach($sections as $section)
								<div class="menu-items col-lg-2">
									<div><b>{{ $section['title'] }}</b></div><br/>
									@foreach ($categorlists as $category)
										@php
											$children = $category->child->where('language_id', 1);
	
											$selected = $checked_categories[$section['id']] ?? [];
										@endphp

										<!-- Parent -->
										<div class="menu-item parent">
											<label>
												<input type="checkbox" 
													   name="categories[{{$section['id']}}][{{$category->slug}}]" 
													   value="{{ $category->id }}"
													   {{ in_array($category->id, $selected) ? 'checked' : '' }}>
												{{ $category->title }}
											</label>
										</div>

										<!-- Children -->
										@foreach ($children as $child)
											<div class="menu-item child" style="margin-left:20px;">
												<label>
													<input type="checkbox" 
														   name="categories[{{$section['id']}}][{{$child->slug}}]" 
														   value="{{ $child->id }}"
														   {{ in_array($child->id, $selected) ? 'checked' : '' }}>
													{{ $child->title }}
												</label>
											</div>
										@endforeach

									@endforeach
								</div>
							@endforeach
							
                        </div>

                   


                        <div class="row justify-content-center">
                          <div class="col-lg-3">
                            <div class="left-area">

                            </div>
                          </div>
                          <div class="col-lg-6">
                            <button class="addProductSubmit-btn" type="submit">{{ __('Save') }}</button>
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
<script src="{{asset('assets/admin/js/notify.js')}}"></script>
<script src="{{asset('assets/admin/js/distawk.js')}}"></script>

@endsection

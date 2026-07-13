<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
    	<meta name="author" content="GeniusOcean">
    	<meta name="csrf-token" content="{{ csrf_token() }}">
    	
		<!-- Title -->
		<title>{{__('Amar Bangla')}}</title>
		<style>
            /* Profile dropdown menu positioning and style */
            .login-profile-area {
                position: relative;
            }
            .profile-dropdown {
                display: none;
                position: absolute;
                background: #ffffff;
                right: 0;
                top: 50px;
                width: 200px;
                box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05);
                border-radius: 8px;
                padding: 8px 0;
                z-index: 1000;
                border: 1px solid #e2e8f0;
                margin: 0;
                list-style: none;
            }
            .profile-dropdown li {
                display: block !important;
                width: 100%;
            }
            .profile-dropdown li a {
                display: flex !important;
                align-items: center;
                gap: 10px;
                width: 100%;
                padding: 10px 16px !important;
                font-size: 14px !important;
                color: #4a5568 !important;
                transition: all 0.2s ease;
                text-decoration: none !important;
                background: transparent !important;
                border: none !important;
                margin: 0 !important;
            }
            .profile-dropdown li a:hover {
                background-color: #f7fafc !important;
                color: #0f766e !important;
            }
            .profile-dropdown li a i {
                font-size: 16px;
                color: #a0aec0;
            }
            
            /* Quick Links Header styling */
            .header-quick-links {
                display: flex;
                align-items: center;
                gap: 12px;
                margin-left: auto;
                margin-right: 20px;
                overflow-x: auto;
                white-space: nowrap;
                scrollbar-width: none; /* Firefox */
                -ms-overflow-style: none; /* IE 10+ */
                flex: 1;
                min-width: 0;
                justify-content: flex-end;
                padding: 4px 0;
            }
            .header-quick-links::-webkit-scrollbar {
                display: none; /* Safari and Chrome */
            }
            .header-quick-links .quick-link {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                color: #4a5568 !important;
                font-weight: 500;
                padding: 6px 12px;
                border-radius: 20px;
                transition: all 0.3s ease;
                font-size: 13px;
                border: 1px solid #e2e8f0;
                background: #f7fafc;
                text-decoration: none !important;
                flex-shrink: 0;
            }
            .header-quick-links .quick-link:hover {
                background: #edf2f7;
                color: #0f766e !important;
                border-color: #cbd5e0;
            }
            .header-quick-links .quick-link.active {
                background: linear-gradient(135deg, #14b8a6 0%, #0f766e 100%);
                color: #ffffff !important;
                border-color: #0f766e;
                box-shadow: 0 4px 6px -1px rgba(20, 184, 166, 0.4);
            }
            .header-quick-links .quick-link i {
                font-size: 14px;
            }

            /* Responsive tweaks */
            @media (max-width: 1024px) {
                .header-quick-links {
                    justify-content: flex-start;
                }
                .header-quick-links .quick-link span {
                    display: inline !important; /* Ensure item name/text is shown on mobile/tablet */
                }
            }
            @media (max-width: 768px) {
                .header-quick-links {
                    margin-right: 15px;
                    margin-left: 15px;
                    gap: 8px;
                }
                /* Place the 3 dash (menu toggle button) on left on mobile */
                .page .header .menu-toggle-button .nav-link {
                    left: 15px !important;
                }
                .admin-logo {
                    margin-left: 55px !important;
                }
            }
            @media (max-width: 480px) {
                .admin-logo img {
                    max-width: 80px;
                }
                .header-quick-links {
                    gap: 6px;
                    margin-left: 10px;
                    margin-right: 10px;
                }
                .header-quick-links .quick-link {
                    padding: 5px 10px;
                    font-size: 11px;
                }
                .header-quick-links .quick-link i {
                    font-size: 12px;
                }
                .admin-logo {
                    margin-left: 45px !important;
                }
            }
            #productImagesCarousel {
              position: relative;
            }
            
            #productImagesCarousel .carousel-control-prev,
            #productImagesCarousel .carousel-control-next {
              width: 40px;
              height: 60px;
              top: 50%;
              transform: translateY(-50%);
              background: transparent;
              border: none;
              opacity: 1;
            }
            
            #productImagesCarousel .carousel-control-prev {
              left: 0;
            }
            
            #productImagesCarousel .carousel-control-next {
              right: 0;
            }
            
            #productImagesCarousel .carousel-control-prev svg,
            #productImagesCarousel .carousel-control-next svg {
              stroke: #000; 
            }

            
            #productsModal .modal-content {
              height: 80vh; 
            }
            
            #productsModal .modal-body {
              height: calc(80vh - 70px); 
              overflow-y: auto;          
            }

            .modal-custom-width {
              max-width: 650px;  
            }

            #productsModal .modal-body {
              max-height: 70vh;
              overflow-y: auto;
            }


		</style>
		<!-- favicon -->
		<link rel="shortcut icon" href="{{asset('assets/images/'.$gs->favicon)}}" type="image/x-icon">
		<!-- Bootstrap -->
		<link href="{{asset('assets/admin/css/bootstrap.min.css')}}" rel="stylesheet" />
		<!-- Fontawesome -->
		<link rel="stylesheet" href="{{asset('assets/admin/css/fontawesome.css')}}">
		<!-- icofont -->
		<link rel="stylesheet" href="{{asset('assets/admin/css/icofont.min.css')}}">
		<link rel="stylesheet" href="{{asset('assets/admin/css/jquery-ui.css')}}">
		<!-- Sidemenu Css -->
		<link href="{{asset('assets/admin/plugins/fullside-menu/css/dark-side-style.css')}}" rel="stylesheet" />
		<link href="{{asset('assets/admin/plugins/fullside-menu/waves.min.css')}}" rel="stylesheet" />

		<link href="{{asset('assets/admin/css/plugin.css')}}" rel="stylesheet" />

		<link href="{{asset('assets/admin/css/jquery.tagit.css')}}" rel="stylesheet" />
    	<link rel="stylesheet" href="{{ asset('assets/admin/css/bootstrap-coloroicker.css') }}">
		<link rel="stylesheet" href="{{ asset('assets/admin/css/datetimepicker.css') }}">
		<link rel="stylesheet" href="{{ asset('assets/admin/css/bootstrap-tagsinput.css') }}">

		<link href="{{asset('assets/admin/css/tagify.css')}}" rel="stylesheet"/>
		
		<!-- Google Fonts for Bengali Postcards -->
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Noto+Serif+Bengali:wght@400;700;900&family=Noto+Sans+Bengali:wght@400;700;900&display=swap" rel="stylesheet">
		<link rel="stylesheet" href="{{ asset('assets/front2/css/font.css') }}">

		<!-- Main Css -->

		<!-- stylesheet -->
		@if(DB::table('admin_languages')->where('is_default','=',1)->first()->rtl == 1)

		<link href="{{asset('assets/admin/css/rtl/style.css')}}" rel="stylesheet"/>
		<link href="{{asset('assets/admin/css/rtl/custom.css')}}" rel="stylesheet"/>
		<link href="{{asset('assets/admin/css/rtl/responsive.css')}}" rel="stylesheet" />
		<link href="{{asset('assets/admin/css/common.css')}}" rel="stylesheet" />

		@else

		<link href="{{asset('assets/admin/css/style.css')}}" rel="stylesheet"/>
		<link href="{{asset('assets/admin/css/custom.css')}}" rel="stylesheet"/>
		<link href="{{asset('assets/admin/css/responsive.css')}}" rel="stylesheet" />
		<link href="{{asset('assets/admin/css/common.css')}}" rel="stylesheet" />
		
		@endif

		@yield('styles')

	</head>
	<body>
		<div class="page">
			<div class="page-main">
				<!-- Header Menu Area Start -->
				<div class="header">
					<div class="container-fluid">
						<div class="d-flex justify-content-between align-self-center">
							<a class="admin-logo" href="{{ route('user.dashboard') }}">
								<img src="{{$gs->logo ? asset('assets/images/logo/'.$gs->logo) : asset('assets/front/images/logo.png')}}" alt="">
							</a>
							<div class="menu-toggle-button">
								<a class="nav-link" href="javascript:;" id="sidebarCollapse">
									<div class="my-toggl-icon">
											<span class="bar1"></span>
											<span class="bar2"></span>
											<span class="bar3"></span>
									</div>
								</a>
							</div>

							<!-- Header Quick Links -->
							<div class="header-quick-links">
								<a href="{{ route('user.dashboard') }}" class="quick-link {{ request()->routeIs('user.dashboard') ? 'active' : '' }}" title="{{ __('Dashboard') }}">
									<i class="fa fa-home"></i> 
								</a>
								@if(auth()->user()->is_approve == 1)
								<a href="{{ route('user.article.create') }}" class="quick-link {{ request()->routeIs('user.article.create') ? 'active' : '' }}" title="{{ __('Add News') }}">
									<i class="fa fa-plus-circle"></i> <span>{{ __('Add News') }}</span>
								</a>
								<a href="{{ route('user.post.index') }}" class="quick-link {{ request()->routeIs('user.post.index') ? 'active' : '' }}" title="{{ __('All News') }}">
									<i class="fa fa-newspaper"></i> <span>{{ __('News') }}</span>
								</a>
								@endif
								<a href="{{ route('frontend.index') }}" class="quick-link" target="_blank" title="{{ __('Visit Site') }}">
									<i class="fa fa-globe"></i> <span>{{ __('Visit Site') }}</span>
								</a>
							</div>

							<!-- Unified Profile Dropdown -->
							<div class="right-eliment login-profile-area">
								<ul class="list" style="margin: 0; padding: 0; list-style: none;">
									<li class="login-profile-area" style="display:flex; align-items:center;">
										<div class="user-img" id="profileMenuBtn" style="cursor:pointer; display:flex; align-items:center; gap:8px;">
											@php $data = auth()->user(); @endphp
											<img src="{{ $data->photo ? asset('assets/images/admin/'.$data->photo) : asset('assets/images/noimage.png') }}"
												 alt="" style="width:40px;height:40px;border-radius:50%;">
											<span class="user-name d-none d-md-inline" style="font-weight: 500; color: #4a5568;">{{ $data->name }}</span>
											<i class="fas fa-chevron-down d-none d-md-inline" style="font-size: 10px; color: #718096;"></i>
										</div>
							
										<ul class="profile-dropdown" id="profileDropdown">
											<li><a href="{{route('frontend.index')}}" target="_blank"><i class="far fa-newspaper"></i> {{__('View Site')}}</a></li>
											<li><a href="{{route('user.profile')}}"><i class="fas fa-user"></i> {{ __('Edit Profile') }}</a></li>
											<li><a href="{{route('user.password')}}"><i class="fas fa-cog"></i> {{ __('Change Password') }}</a></li>
											@if(auth()->user()->is_approve == 1)
											<li><a href="{{route('user.idcard')}}" target="_blank"><i class="fas fa-credit-card"></i> {{ __('ID Card') }}</a></li>
											@endif
											<li><a href="{{route('user.applicationform')}}" target="_blank"><i class="fas fa-user"></i> {{ __('Application Form') }}</a></li>
											<li><a href="{{ route('user.logout') }}"><i class="fas fa-power-off"></i> Logout</a></li>
										</ul>
									</li>
								</ul>
							</div>

						</div>
					</div>
				</div>
				<!-- Header Menu Area End -->
				<div class="wrapper">
					<!-- Side Menu Area Start -->
					<nav id="sidebar" class="nav-sidebar">
						<ul class="list-unstyled components" id="accordion">
							<li>
								<a href="{{ route('user.dashboard') }}" class="wave-effect"><i class="fa fa-home mr-2"></i>{{ __('Dashboard') }}</a>
							</li>
							
							@if(auth()->user()->is_approve == 1)
                                <li>
                                    <a href="{{ route('user.article.create') }}" >
                                        <i class="fa fa-file"></i>{{ __('Add News') }}
                                    </a>
                                </li>
    
    							<li>
    								<a href="#menu4" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
    									<i class="fa fa-bars"></i>{{ __('News') }}
    								</a>
    								<ul class="collapse list-unstyled" id="menu4" data-parent="#accordion">
    									<li>
    										<a href="{{ route('user.post.index') }}"><span><i class="fas fa-angle-double-right"></i>{{ __('All News') }}</span></a>
    									</li>
    									
    									<li>
    										<a href="{{ route('user.feature.index') }}"><span><i class="fas fa-angle-double-right"></i>{{ __('Featured News') }}</span></a>
    									</li>
    									<li>
    										<a href="{{ route('user.breaking.index') }}"><span><i class="fas fa-angle-double-right"></i>{{ __('Breaking News') }}</span></a>
    									</li>
    									<li>
    										<a href="{{ route('user.pending.index') }}"><span><i class="fas fa-angle-double-right"></i>{{ __('Pending News') }}</span></a>
    									</li>
    
    								</ul>
    							</li>
    
    
                                <li>
                                    <a href="{{ route('user.schedule.index') }}"><span><i class="fa fa-calendar" aria-hidden="true"></i>{{ __('Schedule News') }}</span></a>
                                </li>
    
                                <li>
                                    <a href="{{ route('user.draft.index') }}"><span><i class="fab fa-firstdraft"></i>{{ __('Drafts') }}</span></a>
                                </li>
    						
    							 <!--<li>-->
            <!--                        <a href="{{ route('user.reporter') }}"><span><i class="fab fa-firstdraft"></i>Reporter</span></a>-->
            <!--                    </li>-->
    							<li>
                                    <a href="{{ route('user.profile.paymentrequest') }}" class=" wave-effect"><i class="fa fa-database"></i>{{ __('Payment Request') }}</a>
                                </li>
    							
    							<li>
                                    <a href="{{ route('user.profile.receiverequest') }}" class=" wave-effect"><i class="fa fa-database"></i>{{ __('Send Payment') }}</a>
                                </li>
                                
                                <li>
                                    <a href="{{ route('user.cache.clear') }}" class=" wave-effect"><i class="fa fa-database"></i>{{ __('Clear Cache') }}</a>
                                </li>
                            @endif
						</ul>

					</nav>
					<!-- Main Content Area Start -->
					@yield('content')
					<!-- Main Content Area End -->
					</div>
				</div>
			</div>

			<script>
				var mainurl = "{{url('/')}}/";
				var gs = '{{$gs}}'
			</script>
		<!-- Dashboard Core -->
		<script src="{{asset('assets/admin/js/vendors/jquery-1.12.4.min.js')}}"></script>
        <script src="{{asset('assets/admin/js/vendors/vue.js')}}"></script>
		<script src="{{asset('assets/admin/js/vendors/bootstrap.min.js')}}"></script>
		<script src="{{asset('assets/admin/js/jqueryui.min.js')}}"></script>
		<!-- Fullside-menu Js-->
		<script src="{{asset('assets/admin/plugins/fullside-menu/jquery.slimscroll.min.js')}}"></script>
		<script src="{{asset('assets/admin/plugins/fullside-menu/waves.min.js')}}"></script>

		<script src="{{asset('assets/admin/js/plugin.js')}}"></script>
		<script src="{{asset('assets/admin/js/Chart.min.js')}}"></script>
		<script src="{{asset('assets/admin/js/tag-it.js')}}"></script>
		<script src="{{asset('assets/admin/js/nicEdit.js')}}"></script>
        <script src="{{asset('assets/admin/js/bootstrap-colorpicker.min.js') }}"></script>
        <script src="{{asset('assets/admin/js/notify.js') }}"></script>

        <script src="{{asset('assets/admin/js/jquery.canvasjs.min.js')}}"></script>

		<script src="{{asset('assets/admin/js/load.js')}}"></script>
		<!-- Custom Js-->
		<script src="{{asset('assets/admin/js/custom.js')}}"></script>
		<script src="{{asset('assets/front/js/login.js')}}"></script>
		<!-- AJAX Js-->
		<script src="{{asset('assets/admin/js/myscript.js')}}"></script>
		<!--bootstrap-taginput-->
		<script src="{{asset('assets/admin/js/bootstrap-tagsinput.js')}}"></script>
		<script src="{{asset('assets/admin/js/jquery.validate.min.js')}}"></script>
		<script src="{{asset('assets/admin/js/additional-methods.min.js')}}"></script>

		{{-- <script src="http://cdn.bootcss.com/toastr.js/latest/js/toastr.min.js"></script>
		{!! Toastr::message() !!} --}}

		<script src="{{asset('assets/admin/js/datetimepicker.js')}}"></script>

		<script src="{{asset('assets/admin/js/newspaper.js')}}"></script>
		<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
		<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

		<script>
		$(document).ready(function() {
			$('#report_type').select2({
				placeholder: "Select Report Type",
				allowClear: true,
				width: '100%'
			});
		});

		</script>
		<script>
            $('#profileMenuBtn').on('click', function () {
                $('#profileDropdown').toggle();
            });
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.login-profile-area').length) {
                    $('#profileDropdown').hide();
                }
            });
        </script>

		@yield('scripts')


	</body>

</html>

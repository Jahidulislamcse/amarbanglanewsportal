<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
    	<meta name="author" content="GeniusOcean">
    	<meta name="csrf-token" content="{{ csrf_token() }}">
		<!-- Title -->
		<title>{{__('আমার বাংলা 24')}}</title>
		<!-- favicon -->
		<link rel="icon" href="{{ asset('assets/images/'.$gs->favicon) }}" type="image/png">
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
		<link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
          crossorigin="anonymous" referrerpolicy="no-referrer" />
				
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
							<a class="admin-logo" href="{{ route('admin.dashboard') }}">
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

							<div class="right-eliment">
								<ul class="list">
								
							
									<li class="login-profile-area">
										<a class="dropdown-toggle-1 text-dark" href="javascript:;">
											<i class="far fa-newspaper fa-lg"></i>
											<span class="badge badge-danger badge-pill" id="news-count">0</span>
										</a>
										<div class="dropdown-menu">
											<div class="dropdownmenu-wrapper">
												<h6 class="dropdown-header">Pending News</h6>
												<div id="news-list" class="list-group small"></div>
												<div class="dropdown-divider"></div>
												<a href="{{ route('pending.index') }}" class="dropdown-item text-center">View all news</a>
												</div>
										</div>
										
									</li>
									
									<li class="login-profile-area">
										<a class="dropdown-toggle-1 text-dark" href="javascript:;">
											<i class="fas fa-user fa-lg"></i>
											<span class="badge badge-warning badge-pill" id="app-count">0</span>
										</a>
										<div class="dropdown-menu">
											<div class="dropdownmenu-wrapper">
											<h6 class="dropdown-header">Pending Reporter</h6>
											<div id="app-list" class="list-group small"></div>
											<div class="dropdown-divider"></div>
											<a href="{{ route('admin.staff.index') }}?pending_status=1" class="dropdown-item text-center">View all reporter</a>
											</div>
										</div>
										
									</li>
									

                                    @if(Auth::guard('admin')->user()->IsSuper() || (Auth::guard('admin')->user()->role && strtolower(Auth::guard('admin')->user()->role->name) === 'admin'))
                                    <li class="login-profile-area dropdown">
                                    
                                        <a class="dropdown-toggle-1 text-dark"
                                           data-toggle="dropdown"
                                           href="javascript:;">
                                    
                                            <i class="fas fa-wallet fa-lg"></i>

                                            <span class="badge badge-danger badge-pill" id="payment-count">
                                                0
                                            </span>
                                        </a>
                                    
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <div class="dropdownmenu-wrapper">
                                    
                                                <h6 class="dropdown-header">
                                                    Pending Payment Requests
                                                </h6>
                                    
                                                <div class="list-group small" id="payment-list"></div>
                                    
                                                <div class="dropdown-divider"></div>
                                    
                                                <a href="{{ route('admin.administator.paymentrequest') }}"
                                                   class="dropdown-item text-center">
                                                    View all payments
                                                </a>
                                            </div>
                                        </div>
                                    </li>

                                    <li class="login-profile-area dropdown">
                                        <a class="dropdown-toggle-1 text-dark" data-toggle="dropdown" href="javascript:;">
                                            <i class="fas fa-level-up-alt fa-lg"></i>
                                            <span class="badge badge-success badge-pill" id="package-payment-count">0</span>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <div class="dropdownmenu-wrapper">
                                                <h6 class="dropdown-header">Package Upgrade Payments</h6>
                                                <div class="list-group small" id="package-payment-list"></div>
                                                <div class="dropdown-divider"></div>
                                                <a href="{{ route('admin.administator.packageUpgradePayments', ['seen' => 0]) }}" class="dropdown-item text-center">View payments</a>
                                            </div>
                                        </div>
                                    </li>

                                    <li class="login-profile-area dropdown">
                                        <a class="dropdown-toggle-1 text-dark" data-toggle="dropdown" href="javascript:;">
                                            <i class="fas fa-calendar-check fa-lg"></i>
                                            <span class="badge badge-info badge-pill" id="monthly-payment-count">0</span>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <div class="dropdownmenu-wrapper">
                                                <h6 class="dropdown-header">Reporter Monthly Payments</h6>
                                                <div class="list-group small" id="monthly-payment-list"></div>
                                                <div class="dropdown-divider"></div>
                                                <a href="{{ route('admin.administator.monthlypayments', ['seen' => 0]) }}" class="dropdown-item text-center">View payments</a>
                                            </div>
                                        </div>
                                    </li>

                                    <li class="login-profile-area dropdown">
                                        <a class="dropdown-toggle-1 text-dark" data-toggle="dropdown" href="javascript:;">
                                            <i class="fas fa-shopping-cart fa-lg"></i>
                                            <span class="badge badge-danger badge-pill" id="product-payment-count">0</span>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <div class="dropdownmenu-wrapper">
                                                <h6 class="dropdown-header">Product Payments</h6>
                                                <div class="list-group small" id="product-payment-list"></div>
                                                <div class="dropdown-divider"></div>
                                                <a href="{{ route('admin.administator.productPayments', ['seen' => 0]) }}" class="dropdown-item text-center">View payments</a>
                                            </div>
                                        </div>
                                    <li class="login-profile-area dropdown mr-3">
                                        <a class="dropdown-toggle-1 text-dark" data-toggle="dropdown" href="javascript:;">
                                            <i class="fas fa-book fa-lg"></i>
                                            <span class="badge badge-warning badge-pill" id="book-payment-count">0</span>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <div class="dropdownmenu-wrapper">
                                                <h6 class="dropdown-header">Book Purchases</h6>
                                                <div class="list-group small" id="book-payment-list"></div>
                                                <div class="dropdown-divider"></div>
                                                <a href="{{ route('admin.administator.bookPurchasePayments', ['seen' => 0]) }}" class="dropdown-item text-center">View payments</a>
                                            </div>
                                        </div>
                                    </li>
                                    @endif

                                    @if(Auth::guard('admin')->user()->IsSuper() || (Auth::guard('admin')->user()->role && (strtolower(Auth::guard('admin')->user()->role->name) === 'admin' || Auth::guard('admin')->user()->sectionCheck('product_orders'))))
                                    <li class="login-profile-area dropdown mr-3">
                                        <a class="dropdown-toggle-1 text-dark" data-toggle="dropdown" href="javascript:;">
                                            <i class="fas fa-shopping-bag fa-lg"></i>
                                            <span class="badge badge-danger badge-pill" id="order-pending-count">0</span>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <div class="dropdownmenu-wrapper">
                                                <h6 class="dropdown-header">Pending Orders</h6>
                                                <div class="list-group small" id="order-pending-list"></div>
                                                <div class="dropdown-divider"></div>
                                                <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="dropdown-item text-center">View all orders</a>
                                            </div>
                                        </div>
                                    </li>
                                    @endif
                                    
									<li class="bell-area">
										<a id="notf_order" class="dropdown-toggle-1" href="{{route('frontend.index')}}" target="_blank">
											<i class="far fa-newspaper"></i>
											<span data-href="" id="order-notf-count"></span>{{__('View Site')}}
										</a>
										<div class="dropdown-menu">
											<div class="dropdownmenu-wrapper" data-href="" id="order-notf-show">
										</div>
										</div>
									</li>
									
									<li class="login-profile-area">
										<a class="dropdown-toggle-1" href="javascript:;">
											<div class="user-img">
												@php
													$data = Auth::guard('admin')->user();
												@endphp
												<img src="{{ $data->photo ? asset('assets/images/admin/'.$data->photo) : asset('assets/images/noimage.png') }}" alt="">
											</div>
										</a>
										<div class="dropdown-menu">
											<div class="dropdownmenu-wrapper">
												<ul>
													<h5>{{ __('Welcome!') }}</h5>
													<li>
														<a href="{{route('admin.profile')}}"><i class="fas fa-user"></i> Edit Profile</a>
													</li>
													<li>
														<a href="{{route('admin.password')}}"><i class="fas fa-cog"></i> Change Password</a>
													</li>
													<li>
														<a href="{{ route('admin.logout') }}"><i class="fas fa-power-off"></i> Logout</a>
													</li>
												</ul>
											</div>
										</div>
										
									</li>
								</ul>
							</div>
						</div>
					</div>
				</div>

				<div class="wrapper">
		
					<nav id="sidebar" class="nav-sidebar">
						<ul class="list-unstyled components" id="accordion">
							<li>
								<a href="{{ route('admin.dashboard') }}" class="wave-effect"><i class="fa fa-home mr-2"></i>{{ __('Dashboard') }}</a>
							</li>
							@if(Auth::guard('admin')->user()->IsSuper())
								@include('partial.admin-role.super')
							@else
								@include('partial.admin-role.normal')
							@endif
						</ul>
						@if(Auth::guard('admin')->user()->IsSuper())
							<p class="version-name"> {{__('Version')}}: {{ $gs->version }}</p>
						@endif
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
		<!-- AJAX Js-->
		<script src="{{asset('assets/admin/js/myscript.js')}}"></script>
		<!--bootstrap-taginput-->
		<script src="{{asset('assets/admin/js/bootstrap-tagsinput.js')}}"></script>
		<script src="{{asset('assets/admin/js/jquery.validate.min.js')}}"></script>
		<script src="{{asset('assets/admin/js/additional-methods.min.js')}}"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

		
		{{-- <script src="http://cdn.bootcss.com/toastr.js/latest/js/toastr.min.js"></script>
		{!! Toastr::message() !!} --}}
		
		<script src="{{asset('assets/admin/js/datetimepicker.js')}}"></script>
	
		<script src="{{asset('assets/admin/js/newspaper.js')}}"></script>
		
		<script>
	document.addEventListener("DOMContentLoaded", function() {
		function loadNotifications() {
			$.ajax({
				url: "{{ route('admin.administator.notifications.fetch') }}", // create this route
				type: "GET",
				success: function(response) {

					$('#news-count').text(response.news_count);
					$('#news-list').html('');
					response.news.forEach(function(news) {
						$('#news-list').append(
							`<a href="${news.url}" target="_blank" class="list-group-item list-group-item-action">
								${news.title}
								<small class="text-muted d-block">${news.time}</small>
							</a>`
						);
					});
					$('#app-count').text(response.applicant_count);
					$('#app-list').html('');
					response.applicants.forEach(function(app) {
						$('#app-list').append(
							`<a href="${app.url}" target="_blank" class="list-group-item list-group-item-action">
								${app.name}
								<small class="text-muted d-block">${app.time}</small>
							</a>`
						);
					});
					$('#upgrade-count').text(response.upgrade_count);
                    $('#upgrade-list').html('');
                    
                    response.upgrades.forEach(function(upgrade) {
                    
                        $('#upgrade-list').append(`
                            <a href="${upgrade.url}"
                               class="list-group-item list-group-item-action">
                    
                                <strong>${upgrade.name}</strong>
                    
                                requested
                    
                                <span class="badge badge-success">
                                    ${upgrade.package}
                                </span>
                    
                                <br>
                    
                                <small>
                                    Amount: ৳${upgrade.amount}
                                </small>
                    
                                <small class="text-muted d-block">
                                    ${upgrade.time}
                                </small>
                    
                            </a>
                        `);
                    });
                    
                    
                    $('#payment-count').text(response.payment_count || 0);
                    $('#payment-list').html('');
                    (response.payments || []).forEach(function(payment) {
                        $('#payment-list').append(`
                            <a href="${payment.url}" class="list-group-item list-group-item-action">
                                <strong>${payment.name}</strong> submitted a payment request
                                <br>
                                <small>Amount: ৳${payment.amount}</small>
                                <small class="text-muted d-block">${payment.time}</small>
                            </a>
                        `);
                    });

                    $('#package-payment-count').text(response.package_payment_count || 0);
                    $('#package-payment-list').html('');
                    (response.package_payments || []).forEach(function(payment) {
                        $('#package-payment-list').append(`
                            <a href="${payment.url}" class="list-group-item list-group-item-action">
                                <strong>${payment.name}</strong> upgrade to <strong>${payment.package}</strong>
                                <br>
                                <small>Amount: ৳${payment.amount}</small>
                                <small class="text-muted d-block">${payment.time}</small>
                            </a>
                        `);
                    });

                    $('#monthly-payment-count').text(response.monthly_payment_count || 0);
                    $('#monthly-payment-list').html('');
                    (response.monthly_payments || []).forEach(function(payment) {
                        $('#monthly-payment-list').append(`
                            <a href="${payment.url}" class="list-group-item list-group-item-action">
                                <strong>${payment.name}</strong> monthly fee
                                <br>
                                <small>Amount: ৳${payment.amount}</small>
                                <small class="text-muted d-block">${payment.time}</small>
                            </a>
                        `);
                    });

                    $('#product-payment-count').text(response.product_payment_count || 0);
                    $('#product-payment-list').html('');
                    (response.product_payments || []).forEach(function(payment) {
                        $('#product-payment-list').append(`
                            <a href="${payment.url}" class="list-group-item list-group-item-action">
                                <strong>${payment.name}</strong> product payment
                                <br>
                                <small>${payment.items}</small>
                                <small class="d-block">Amount: ৳${payment.amount}</small>
                                <small class="text-muted d-block">${payment.time}</small>
                            </a>
                        `);
                    });

                    $('#book-payment-count').text(response.book_payment_count || 0);
                    $('#book-payment-list').html('');
                    (response.book_payments || []).forEach(function(payment) {
                        $('#book-payment-list').append(`
                            <a href="${payment.url}" class="list-group-item list-group-item-action">
                                <strong>${payment.name}</strong> bought ${payment.book}
                                <br>
                                <small>Amount: ৳${payment.amount}</small>
                                <small class="text-muted d-block">${payment.time}</small>
                            </a>
                        `);
                    });

                    $('#order-pending-count').text(response.pending_order_count || 0);
                    $('#order-pending-list').html('');
                    (response.pending_orders || []).forEach(function(order) {
                        $('#order-pending-list').append(`
                            <a href="${order.url}" class="list-group-item list-group-item-action">
                                <strong>${order.name}</strong> placed an order
                                <br>
                                <small>Amount: ৳${order.amount}</small>
                                <small class="text-muted d-block">${order.time}</small>
                            </a>
                        `);
                    });
				}
			});
		}

		loadNotifications();
		setInterval(loadNotifications, 10000); // auto refresh every 10 seconds
	});
	</script>
		@yield('scripts')


	</body>

</html>

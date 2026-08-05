@extends('layouts.admin')

@section('content')
					<input type="hidden" id="headerdata" value="{{ __('User') }}">
					<div class="content-area">
						<div class="mr-breadcrumb">
							<div class="row">
								<div class="col-lg-12">
										<h4 class="heading">News Wise {{$user_informations->name}} Income</h4>
										<ul class="links">
											<li>
												<a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
											</li>
											<li>
												<a href="{{ route('admin.staff.index') }}">{{ __('Manage User') }}</a>
											</li>
										</ul>
								</div>
							</div>
						</div>
						<div class="product-area">
						
							{{-- Stats Cards --}}
							<div class="row mb-4" style="margin-top: 20px;">
								<div class="col-md-4 col-lg">
									<div class="card bg-primary text-white text-center p-3" style="border-radius: 10px; border: none; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
										<h6 style="color: #fff; font-size: 14px; font-weight: 500;">Total Views</h6>
										<h3 style="color: #fff; font-weight: 700; margin-top: 5px;">{{ number_format($user_informations->views ?? 0) }}</h3>
									</div>
								</div>
								<div class="col-md-4 col-lg">
									<div class="card bg-success text-white text-center p-3" style="border-radius: 10px; border: none; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
										<h6 style="color: #fff; font-size: 14px; font-weight: 500;">Total View Income</h6>
										<h3 style="color: #fff; font-weight: 700; margin-top: 5px;">৳{{ number_format($view_income, 2) }}</h3>
									</div>
								</div>
								<div class="col-md-4 col-lg">
									<div class="card bg-info text-white text-center p-3" style="border-radius: 10px; border: none; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
										<h6 style="color: #fff; font-size: 14px; font-weight: 500;">Referral Earning</h6>
										<h3 style="color: #fff; font-weight: 700; margin-top: 5px;">৳{{ number_format($user_informations->referral_earning ?? 0, 2) }}</h3>
									</div>
								</div>
								<div class="col-md-4 col-lg">
									<div class="card bg-danger text-white text-center p-3" style="border-radius: 10px; border: none; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
										<h6 style="color: #fff; font-size: 14px; font-weight: 500;">Top Reporter Prize</h6>
										<h3 style="color: #fff; font-weight: 700; margin-top: 5px;">৳{{ number_format($top_reporter_prize_total, 2) }}</h3>
									</div>
								</div>
								<div class="col-md-4 col-lg">
									<div class="card bg-secondary text-white text-center p-3" style="border-radius: 10px; border: none; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
										<h6 style="color: #fff; font-size: 14px; font-weight: 500;">Approved Withdraw</h6>
										<h3 style="color: #fff; font-weight: 700; margin-top: 5px;">৳{{ number_format($approved_withdraw_total, 2) }}</h3>
									</div>
								</div>
								<div class="col-md-4 col-lg">
									<div class="card bg-warning text-white text-center p-3" style="border-radius: 10px; border: none; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
										<h6 style="color: #fff; font-size: 14px; font-weight: 500;">Current Balance</h6>
										<h3 style="color: #fff; font-weight: 700; margin-top: 5px;">৳{{ number_format($user_informations->balance ?? 0, 2) }}</h3>
									</div>
								</div>
							</div>

							<div class="row">
								{{-- Profile Card --}}
								<div class="col-md-4 mb-4">
									<div class="card shadow-sm h-100" style="border-radius: 10px; border: 1px solid #e3e6f0;">
										<div class="card-header bg-light py-3">
											<h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-user mr-2"></i>User Profile</h6>
										</div>
										<div class="card-body">
											<table class="table table-borderless m-0">
												<tbody>
													<tr>
														<th style="padding: 8px 0; font-weight: 600; width: 80px;">Name</th>
														<td style="padding: 8px 0;">{{ $user_informations->name }}</td>
													</tr>
													<tr>
														<th style="padding: 8px 0; font-weight: 600;">Email</th>
														<td style="padding: 8px 0;">{{ $user_informations->email }}</td>
													</tr>
													<tr>
														<th style="padding: 8px 0; font-weight: 600;">Phone</th>
														<td style="padding: 8px 0;">{{ $user_informations->phone }}</td>
													</tr>
													<tr>
														<th style="padding: 8px 0; font-weight: 600;">Package 1</th>
														<td style="padding: 8px 0;">
															@if($user_informations->package1_purchased)
																<span class="badge badge-success text-white">Purchased</span>
															@else
																<span class="badge badge-secondary text-white">Not Purchased</span>
															@endif
														</td>
													</tr>
												</tbody>
											</table>
										</div>
									</div>
								</div>

								{{-- Referred Journalist Team --}}
								<div class="col-md-4 mb-4">
									<div class="card shadow-sm h-100" style="border-radius: 10px; border: 1px solid #e3e6f0;">
										<div class="card-header bg-light py-3">
											<h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-users mr-2"></i>Referred Team (1st Gen)</h6>
										</div>
										<div class="card-body" style="max-height: 300px; overflow-y: auto;">
											@php
												$firstGenUsers = $genUsers[1] ?? [];
											@endphp
											@if(count($firstGenUsers) > 0)
												<ul class="list-unstyled mb-0 mt-3">
													@foreach($firstGenUsers as $u)
														<li class="media mb-3 align-items-center">
															<img src="{{ $u->photo ? asset('assets/images/admin/' . $u->photo) : asset('assets/images/default_user.png') }}" 
																 class="mr-3 rounded-circle" 
																 style="width: 32px; height: 32px; object-fit: cover; border: 1px solid #ddd;" 
																 alt="Photo">
															<div class="media-body">
																<h6 class="mt-0 mb-0 font-weight-bold" style="font-size: 13.5px;">{{ $u->name }}</h6>
																@if(!empty($u->phone))
																	<small class="text-muted" style="font-size: 11px;">{{ $u->phone }}</small>
																@endif
																@if($u->district_name || $u->thana_name)
																	<br>
																	<small class="text-muted" style="font-size: 10px;"><i class="fas fa-map-marker-alt text-danger mr-1"></i>{{ implode(', ', array_filter([$u->thana_name, $u->district_name])) }}</small>
																@endif
															</div>
														</li>
													@endforeach
												</ul>
											@else
												<p class="text-muted text-center my-4">No team members referred</p>
											@endif
										</div>
									</div>
								</div>

								{{-- Team Purchases & Commission --}}
								<div class="col-md-4 mb-4">
									<div class="card shadow-sm h-100" style="border-radius: 10px; border: 1px solid #e3e6f0;">
										<div class="card-header bg-light py-3">
											<h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-shopping-cart mr-2"></i>Team Purchases & Commission</h6>
										</div>
										<div class="card-body" style="max-height: 300px; overflow-y: auto;">
											@if(count($team_purchases) > 0)
												<div class="list-group list-group-flush">
													@foreach($team_purchases as $tp)
														<div class="list-group-item px-0 py-2 border-bottom">
															<div class="d-flex align-items-center mb-1">
																<img src="{{ $tp->user->photo ? asset('assets/images/admin/' . $tp->user->photo) : asset('assets/images/default_user.png') }}"
																	 alt="Photo" class="mr-2 rounded-circle"
																	 style="width: 28px; height: 28px; object-fit: cover;">
																<span class="font-weight-bold text-dark" style="font-size: 13px;">{{ $tp->user->name ?? 'Deleted member' }}</span>
															</div>
															<div class="pl-4">
																<small class="d-block text-muted">
																	<strong>Product:</strong>
																	@if ($tp->order && $tp->order->items)
																		{{ implode(', ', $tp->order->items->map(function ($item) {
																			return ($item->product->name ?? 'Product') . ' (x' . $item->quantity . ')';
																		})->toArray()) }}
																	@else
																		Product (Order #{{ $tp->order_id }})
																	@endif
																</small>
																<small class="d-flex justify-content-between text-success font-weight-bold mt-1" style="font-size: 11.5px;">
																	<span>Price: ৳{{ number_format($tp->order_amount, 2) }}</span>
																	<span>Comm. (10%): +৳{{ number_format($tp->commission_amount, 2) }}</span>
																</small>
															</div>
														</div>
													@endforeach
												</div>
											@else
												<p class="text-muted text-center my-4">No team purchases recorded</p>
											@endif
										</div>
									</div>
								</div>
							</div>

							<style>
								[data-toggle="collapse"] {
									transition: background-color 0.15s ease-in-out;
								}
								[data-toggle="collapse"]:hover {
									background-color: #f8f9fc !important;
								}
								[data-toggle="collapse"] .fa-chevron-down {
									transition: transform 0.2s ease-in-out;
								}
								[data-toggle="collapse"].collapsed .fa-chevron-down {
									transform: rotate(-90deg);
								}
							</style>

							<div class="row">
								{{-- Top Reporter Achievements Card --}}
								<div class="col-md-12 mb-4">
									<div class="card shadow-sm h-100" style="border-radius: 10px; border: 1px solid #e3e6f0;">
										<div class="card-header bg-light py-3 d-flex justify-content-between align-items-center collapsed" style="cursor: pointer;" data-toggle="collapse" data-target="#topReporterCollapse" aria-expanded="false" aria-controls="topReporterCollapse">
											<h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-trophy mr-2"></i>Top Reporter Records / Achievements</h6>
											<i class="fas fa-chevron-down text-primary"></i>
										</div>
										<div id="topReporterCollapse" class="collapse">
											<div class="card-body">
												@if(count($top_reporter_records) > 0)
													<div class="table-responsive">
														<table class="table table-hover mb-0">
															<thead>
																<tr>
																	<th>Type</th>
																	<th>Period</th>
																	<th>Rank / Position</th>
																	<th>Total Views</th>
																	<th>Prize Amount</th>
																</tr>
															</thead>
															<tbody>
																@foreach($top_reporter_records as $record)
																	<tr>
																		<td>
																			<span class="badge badge-info text-white">Weekly</span>
																		</td>
																		<td>
																			{{ $record->week }}
																		</td>
																		<td>
																			<span class="font-weight-bold text-dark">
																				@if($record->position == 1)
																					1st Place 🥇
																				@elseif($record->position == 2)
																					2nd Place 🥈
																				@elseif($record->position == 3)
																					3rd Place 🥉
																				@else
																					{{ $record->position }}th Place
																				@endif
																			</span>
																		</td>
																		<td>{{ number_format($record->total_views) }}</td>
																		<td>
																			@if(isset($prize_money_by_week[$record->week]))
																				৳{{ number_format($prize_money_by_week[$record->week], 2) }}
																			@else
																				-
																			@endif
																		</td>
																	</tr>
																@endforeach
															</tbody>
														</table>
													</div>
												@else
													<p class="text-muted text-center my-4">No Top Reporter records found for this user.</p>
												@endif
											</div>
										</div>
									</div>
								</div>
							</div>

							<div class="row">
								{{-- Withdraw Records Card --}}
								<div class="col-md-12 mb-4">
									<div class="card shadow-sm h-100" style="border-radius: 10px; border: 1px solid #e3e6f0;">
										<div class="card-header bg-light py-3 d-flex justify-content-between align-items-center collapsed" style="cursor: pointer;" data-toggle="collapse" data-target="#withdrawCollapse" aria-expanded="false" aria-controls="withdrawCollapse">
											<h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-hand-holding-usd mr-2"></i>Withdrawal Requests / Records</h6>
											<i class="fas fa-chevron-down text-primary"></i>
										</div>
										<div id="withdrawCollapse" class="collapse">
											<div class="card-body">
												@if(count($withdraw_records) > 0)
													<div class="table-responsive">
														<table class="table table-hover mb-0">
															<thead>
																<tr>
																	<th>Request Date</th>
																	<th>Method</th>
																	<th>Requested Amount</th>
																	<th>Approved Amount</th>
																	<th>Account Details</th>
																	<th>Status</th>
																	<th>Verify Date / Verifier</th>
																</tr>
															</thead>
															<tbody>
																@foreach($withdraw_records as $withdraw)
																	<tr>
																		<td>{{ $withdraw->request_date ? date('d M Y', strtotime($withdraw->request_date)) : ($withdraw->created_at ? $withdraw->created_at->format('d M Y') : '') }}</td>
																		<td><span class="badge badge-secondary text-uppercase">{{ $withdraw->payment_type }}</span></td>
																		<td>৳{{ number_format($withdraw->request_amount, 2) }}</td>
																		<td>৳{{ number_format($withdraw->approve_amount, 2) }}</td>
																		<td style="max-width: 250px; word-break: break-all;">
																			<small>{{ $withdraw->account_details }}</small>
																		</td>
																		<td>
																			@if($withdraw->status == 0)
																				<span class="badge badge-warning text-white">Pending</span>
																			@elseif($withdraw->status == 1)
																				<span class="badge badge-success text-white">Approved</span>
																			@else
																				<span class="badge badge-danger text-white">Rejected</span>
																			@endif
																		</td>
																		<td>
																			@if($withdraw->status == 1 || $withdraw->status == 2)
																				<small>
																					{{ $withdraw->verify_date ? date('d M Y', strtotime($withdraw->verify_date)) : '' }}
																					@if($withdraw->verifier)
																						<br>By: {{ $withdraw->verifier->name }}
																					@endif
																				</small>
																			@else
																				-
																			@endif
																		</td>
																	</tr>
																@endforeach
															</tbody>
														</table>
													</div>
												@else
													<p class="text-muted text-center my-4">No withdrawal requests found for this user.</p>
												@endif
											</div>
										</div>
									</div>
								</div>
							</div>

							<div class="row">
								{{-- Monthly Fee Payments Card --}}
								<div class="col-md-12 mb-4">
									<div class="card shadow-sm h-100" style="border-radius: 10px; border: 1px solid #e3e6f0;">
										<div class="card-header bg-light py-3 d-flex justify-content-between align-items-center collapsed" style="cursor: pointer;" data-toggle="collapse" data-target="#monthlyFeeCollapse" aria-expanded="false" aria-controls="monthlyFeeCollapse">
											<h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-receipt mr-2"></i>Monthly Fee Payments (Paid Only)</h6>
											<i class="fas fa-chevron-down text-primary"></i>
										</div>
										<div id="monthlyFeeCollapse" class="collapse">
											<div class="card-body">
												@if(count($monthly_fee_payments) > 0)
													<div class="table-responsive">
														<table class="table table-hover mb-0">
															<thead>
																<tr>
																	<th>Payment Date</th>
																	<th>Method</th>
																	<th>Transaction ID</th>
																	<th>EPS Txn ID</th>
																	<th>Amount</th>
																</tr>
															</thead>
															<tbody>
																@foreach($monthly_fee_payments as $payment)
																	<tr>
																		<td>{{ $payment->paid_at ? $payment->paid_at->format('d M Y, h:i A') : ($payment->created_at ? $payment->created_at->format('d M Y, h:i A') : '') }}</td>
																		<td><span class="badge badge-secondary text-uppercase">{{ $payment->payment_type ?? 'N/A' }}</span></td>
																		<td>{{ $payment->transaction_id }}</td>
																		<td>{{ $payment->eps_transaction_id ?? '-' }}</td>
																		<td>৳{{ number_format($payment->amount, 2) }}</td>
																	</tr>
																@endforeach
															</tbody>
														</table>
													</div>
												@else
													<p class="text-muted text-center my-4">No paid monthly fee payments found for this user.</p>
												@endif
											</div>
										</div>
									</div>
								</div>
							</div>

							<div class="row">
								{{-- Order List Card --}}
								<div class="col-md-12 mb-4">
									<div class="card shadow-sm h-100" style="border-radius: 10px; border: 1px solid #e3e6f0;">
										<div class="card-header bg-light py-3 d-flex justify-content-between align-items-center collapsed" style="cursor: pointer;" data-toggle="collapse" data-target="#orderCollapse" aria-expanded="false" aria-controls="orderCollapse">
											<h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-shopping-bag mr-2"></i>Order List / Purchase History</h6>
											<i class="fas fa-chevron-down text-primary"></i>
										</div>
										<div id="orderCollapse" class="collapse">
											<div class="card-body">
												@if(count($order_records) > 0)
													<div class="table-responsive">
														<table class="table table-hover mb-0">
															<thead>
																<tr>
																	<th>Order ID / Txn ID</th>
																	<th>Date</th>
																	<th>Products / Qty</th>
																	<th>Total Amount</th>
																	<th>Status</th>
																</tr>
															</thead>
															<tbody>
																@foreach($order_records as $order)
																	<tr>
																		<td>
																			<strong>#{{ $order->id }}</strong>
																			@if($order->transaction_id)
																				<br><small class="text-muted">Txn: {{ $order->transaction_id }}</small>
																			@endif
																		</td>
																		<td>{{ $order->created_at ? $order->created_at->format('d M Y, h:i A') : '' }}</td>
																		<td>
																			@if ($order->items && count($order->items) > 0)
																				<ul class="list-unstyled mb-0">
																					@foreach($order->items as $item)
																						<li><small>{{ $item->product->name ?? 'Product' }} (x{{ $item->quantity }}) - ৳{{ number_format($item->price, 2) }}</small></li>
																					@endforeach
																				</ul>
																			@else
																				<small class="text-muted">No items</small>
																			@endif
																		</td>
																		<td>৳{{ number_format($order->total_amount, 2) }}</td>
																		<td>
																			<span class="badge badge-secondary text-uppercase">{{ $order->status ?? 'pending' }}</span>
																		</td>
																	</tr>
																@endforeach
															</tbody>
														</table>
													</div>
												@else
													<p class="text-muted text-center my-4">No orders found for this user.</p>
												@endif
											</div>
										</div>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-lg-12">
									<div class="mr-table allproduct">
    									@include('includes.admin.form-success')
										<div class="table-responsiv">
												<table id="geniustable" class="table table-hover dt-responsive" cellspacing="0" width="100%">
													<thead>
														<tr>
									                        <th>{{ __('Date') }}</th>
									                        <th>{{ __('Title') }}</th>
									                        <th>{{ __('News') }}</th>
															<th>{{ __('Total Views') }}</th>
															<th>{{ __('Total Income') }}</th>
														</tr>
													</thead>
												</table>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

{{-- ADD / EDIT MODAL --}}

				<div class="modal fade-scale" id="modal1" tabindex="-1" role="dialog" aria-labelledby="modal1" aria-hidden="true">

					<div class="modal-dialog modal-dialog-centered" role="document">
						<div class="modal-content">
							<div class="submit-loader">
									<img  src="{{asset('assets/images/'.$gs->admin_loader)}}" alt="">
							</div>
							<div class="modal-header">
								<h5 class="modal-title"></h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">

							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
							</div>
						</div>
					</div>

				</div>

{{-- ADD / EDIT MODAL ENDS --}}


{{-- DELETE MODAL --}}

<div class="modal fade-scale" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="modal1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

	<div class="modal-header d-block text-center">
		<h4 class="modal-title d-inline-block">{{ __('Confirm Delete') }}</h4>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
	</div>

      <!-- Modal body -->
      <div class="modal-body">
            <p class="text-center">{{ __('You are about to delete this User.') }}</p>
            <p class="text-center">{{ __('Do you want to proceed?') }}</p>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer justify-content-center">
            <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Cancel') }}</button>
            <a class="btn btn-danger btn-ok">{{ __('Delete') }}</a>
      </div>

    </div>
  </div>
</div>

{{-- DELETE MODAL ENDS --}}

@endsection



@section('scripts')

{{-- DATA TABLE --}}


    <script type="text/javascript">

		 var table = $('#geniustable').DataTable({
				ordering: false,
				processing: true,
				serverSide: true,
				ajax: {
					url: '{{ route('admin.staff.userPostdatatables') }}',
					data: function (d) {
						d.user_id = {{$user_informations->id}};

					}
				},
				columns: [
					{ data: 'created_at', name: 'created_at' },
					{ data: 'title', name: 'title' },
					{ data: 'description', name: 'description' },
					{ data: 'view_count', name: 'view_count' },
					{ data: 'total_commission', name: 'total_commission' }
				],
				language: {
					processing: '<img src="{{ asset('assets/images/'.$gs->admin_loader) }}">'
				}
			});

			$(document).on('click', '.toggle-news', function(e) {
				e.preventDefault();
				var container = $(this).closest('.news-text-container');
				var shortSpan = container.find('.news-short');
				var fullSpan = container.find('.news-full');
				
				if (fullSpan.is(':visible')) {
					fullSpan.hide();
					shortSpan.show();
					$(this).text('See full news');
				} else {
					shortSpan.hide();
					fullSpan.show();
					$(this).text('See less');
				}
			});

    </script>

{{-- DATA TABLE --}}

@endsection

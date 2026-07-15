@extends('layouts.admin')
@section('content')

<style>
   label {
      font-size: 14px;
      color: #555;
      margin-right: 8px;
    }

    input[type="month"] {
      padding: 6px 8px;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 14px;
      outline: none;
      transition: all 0.3s ease;
    }

    input[type="month"]:focus {
      border-color: #3b82f6;
      box-shadow: 0 0 4px rgba(59, 130, 246, 0.5);
    }
</style>
					<input type="hidden" id="headerdata" value="{{ __('PAYMENT REQUEST') }}">
					<div class="content-area">
						<div class="mr-breadcrumb">
							<div class="row">
								<div class="col-lg-12">
										<h4 class="heading">Payment Request</h4>
										<ul class="links">
											<li>
												<a href="{{ route('user.dashboard') }}">{{ __('Dashboard') }} </a>
											</li>

										</ul>
								</div>
							</div>
						</div>
						<div class="product-area">
					
						 <div class="row">
							<div class="col-sm-2 m-3">
								<label ><b>Reporter</b></label>
								 <select name="user_id" id="user_id" class="form-control"  required>
									<option value="">Reporter</option>
									@foreach ($users as $key => $user)
										<option value="{{ $user->id }}"  }}>
											{{ $user->name }}
										</option>
									@endforeach
								</select>
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
														
									                        <th>{{ __('Reporter Name') }}</th>
									                        <th>{{ __('Email') }}</th>
									                        <th>{{ __('User Contact') }}</th>
															<th>{{ __('Request Date') }}</th>
															<th>{{ __('Request Amount') }}</th>
															<th>{{ __('Approve Amount') }}</th>
															<th>{{ __('Approve Date') }}</th>
															<th>{{ __('Status') }}</th>
															<th>{{ __('Approve By') }}</th>
															<th>{{ __('Action') }}</th>
									                     
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

<div class="modal fade-scale" id="readerDetailsModal" tabindex="-1" role="dialog" aria-labelledby="readerDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.15);">
            <div class="modal-header bg-primary text-white" style="border-top-left-radius: 12px; border-top-right-radius: 12px;">
                <h5 class="modal-title font-weight-bold" id="readerDetailsModalLabel"><i class="fas fa-user-circle mr-2"></i> Reader Details</h5>
                <button type="button" class="close text-white" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close" style="opacity: 1; background: transparent; border: none;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                <div class="text-center mb-4">
                    <h4 class="font-weight-bold text-dark mb-1" id="detail_name">User Name</h4>
                    <span class="badge badge-info px-3 py-1 font-weight-bold" style="font-size: 13px;" id="detail_status">Active</span>
                </div>
                
                <table class="table table-bordered mb-0" style="font-size: 15px;">
                    <tbody>
                        <tr>
                            <th class="bg-light w-50 font-weight-bold text-muted">Created At (নিবন্ধন তারিখ)</th>
                            <td id="detail_created" class="font-weight-bold text-dark">N/A</td>
                        </tr>
                        <tr>
                            <th class="bg-light font-weight-bold text-muted">Referral Earning (রেফারেল আয়)</th>
                            <td id="detail_referral" class="font-weight-bold text-success">৳0.00</td>
                        </tr>
                        <tr>
                            <th class="bg-light font-weight-bold text-muted">View Income (ভীউজ আয়)</th>
                            <td id="detail_views_income" class="font-weight-bold text-success">৳0.00</td>
                        </tr>
                        <tr>
                            <th class="bg-light font-weight-bold text-muted">Daily Quiz Money (কুইজ আয়)</th>
                            <td id="detail_quiz_money" class="font-weight-bold text-success">৳0.00</td>
                        </tr>
                        <tr>
                            <th class="bg-light font-weight-bold text-muted">Quiz Winner Money (কুইজ বিজয়ী আয়)</th>
                            <td id="detail_quiz_winner_money" class="font-weight-bold text-success">৳0.00</td>
                        </tr>
                        <tr>
                            <th class="bg-light font-weight-bold text-muted">Total Withdraw (মোট উত্তোলন)</th>
                            <td id="detail_withdraw" class="font-weight-bold text-danger">৳0.00</td>
                        </tr>
                        <tr>
                            <th class="bg-light font-weight-bold text-muted">Current Balance (চলতি ব্যালেন্স)</th>
                            <td id="detail_balance" class="font-weight-bold text-primary">৳0.00</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer bg-light" style="border-bottom-left-radius: 12px; border-bottom-right-radius: 12px;">
                <button type="button" class="btn btn-secondary px-4 font-weight-bold" data-dismiss="modal" data-bs-dismiss="modal" style="border-radius: 6px;">Close</button>
            </div>
        </div>
    </div>
</div>
 
@endsection



@section('scripts')

{{-- DATA TABLE --}}


    <script type="text/javascript">

		var table = $('#geniustable').DataTable({
			   ordering: false,
               processing: true,
               serverSide: true,
				ajax: {
					url: '{{ route('admin.administator.paymentdatatables') }}',
					data: function (data) {
						var user_id=$('select[name=user_id]').val();
						data.user_id= user_id;

					}
				},
               columns: [
                         { data: 'name', name: 'name' },
                         { data: 'email', name: 'email' },
                         { data: 'phone', name: 'phone' },
 						 { data: 'request_date', name: 'request_date' },
 						 { data: 'request_amount', name: 'request_amount' },
 						 { data: 'approve_amount', name: 'approve_amount' },
						 { data: 'verify_date', name: 'verify_date' },
						 { data: 'status', name: 'status' },
						 { data: 'admin_id', name: 'admin_id' },
						 { data: 'action', name: 'action' },

                      ],
               language : {
                	processing: '<img src="{{asset('assets/images/'.$gs->admin_loader)}}">'
                }
            });
			
			 $('#user_id').change(function(){
					table.draw();
			});
		$(function() {
        $(".btn-area").append('<div class="col-sm-4 text-right">'+
        	'<a class="add-btn" data-href="{{route('admin.administator.paymentcerate')}}" id="add-data" data-toggle="modal" data-target="#modal1">'+
          '<i class="fas fa-plus"></i> {{ __('Add Payment Request') }}'+
          '</a>'+
          '</div>');
      });

	$(document).on('click', '.view-details', function () {
		var name = $(this).data('name');
		var created = $(this).data('created');
		var referral = $(this).data('referral');
		var viewsIncome = $(this).data('views-income');
		var quizMoney = $(this).data('quiz-money');
		var quizWinnerMoney = $(this).data('quiz-winner-money');
		var withdraw = $(this).data('withdraw');
		var balance = $(this).data('balance');
		var isBan = $(this).data('ban');

		$('#detail_name').text(name);
		$('#detail_created').text(created);
		$('#detail_referral').text('৳' + referral);
		$('#detail_views_income').text('৳' + viewsIncome);
		$('#detail_quiz_money').text('৳' + quizMoney);
		$('#detail_quiz_winner_money').text('৳' + quizWinnerMoney);
		$('#detail_withdraw').text('৳' + withdraw);
		$('#detail_balance').text('৳' + balance);
		
		if (isBan == 1) {
			$('#detail_status').text('Disabled').removeClass('badge-success').addClass('badge-danger');
		} else {
			$('#detail_status').text('Active').removeClass('badge-danger').addClass('badge-success');
		}

		$('#readerDetailsModal').modal('show');
	});
    

    </script>
	
@endsection

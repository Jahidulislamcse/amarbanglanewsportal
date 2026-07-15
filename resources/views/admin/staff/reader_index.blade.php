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
    .table-scroll {
        max-height: 300px;
        overflow: auto;
    }
    
    .table-scroll thead th {
        position: sticky;
        top: 0;
        z-index: 2;
        background: #f8f9fa;
    }
</style>

<input type="hidden" id="headerdata" value="{{ __('Readers') }}">
<div class="content-area">
	<div class="mr-breadcrumb">
		<div class="row">
			<div class="col-lg-12">
					<h4 class="heading">{{ __('Readers') }}</h4>
					<ul class="links">
						<li>
							<a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
						</li>
					
					</ul>
			</div>
		</div>
	</div>

    <div class="card mb-4 shadow-sm border-0">
    
        {{-- Header --}}
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-arrow-up"></i> Upgrade Requests
            </h5>
        </div>
    
        {{-- Body --}}
        <div class="card-body">
    
            <div class="mb-3 mt-3">
                <div class="d-inline-block me-2">
                    <button class="btn btn-warning px-3" type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#pendingRequests">
    
                        Pending ({{ $pendingRequests->count() }})
                    </button>
                </div>
    
                <div class="d-inline-block me-2">
                    <button class="btn btn-success px-3" type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#approvedRequests">
    
                        Approved ({{ $approvedRequests->count() }})
                    </button>
                </div>
    
                <div class="d-inline-block">
                    <button class="btn btn-danger px-3" type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#rejectedRequests">
    
                        Rejected ({{ $rejectedRequests->count() }})
                    </button>
                </div>
            </div>
    
            <div class="accordion" id="upgradeAccordion">
    
                {{-- Pending --}}
                <div id="pendingRequests"
                     class="collapse show"
                     data-bs-parent="#upgradeAccordion">
    
                    <div class="table-responsive table-scroll mb-3">
                        <table class="table table-bordered table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>User</th>
                                    <th>Package</th>
                                    <th>Amount</th>
                                    <th>Phone</th>
                                    <th>Operator</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
    
                            <tbody>
                                @forelse($pendingRequests as $req)
                                    <tr>
                                        <td>{{ $req->user->name ?? 'Deleted User' }}</td>
                                        <td>{{ ucfirst($req->package) }}</td>
                                        <td>৳{{ $req->amount }}</td>
                                        <td>{{ $req->phone_number }}</td>
                                        <td>{{ $req->operator }}</td>
                                        <td>
                                            <span class="badge bg-warning">
                                                Pending
                                            </span>
                                        </td>
                                        <td>
                                            <form action="{{ route('admin.upgrade.approve', $req->id) }}" method="POST" style="display:inline-block">
                                                @csrf
                                                <button class="btn btn-success btn-sm">Approve</button>
                                            </form>
    
                                            <form action="{{ route('admin.upgrade.reject', $req->id) }}" method="POST" style="display:inline-block">
                                                @csrf
                                                <button class="btn btn-danger btn-sm">Reject</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center mb-3">
                                            No pending requests found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
    
                </div>
    
                @php
                    $approvedTotalAmount = $approvedRequests->sum('amount');
                @endphp
                
                <div id="approvedRequests"
                     class="collapse"
                     data-bs-parent="#upgradeAccordion">
                
                    <div class="mb-3">
                        <h5 class="mb-0">
                            Total Approved Amount:
                            <span class="badge bg-success">
                                ৳{{ number_format($approvedTotalAmount, 2) }}
                            </span>
                        </h5>
                    </div>
    
                     <div class="table-responsive table-scroll mb-3">
                        <table class="table table-bordered table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>User</th>
                                    <th>Package</th>
                                    <th>Amount</th>
                                    <th>Phone</th>
                                    <th>Operator</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
    
                            <tbody>
                                @forelse($approvedRequests as $req)
                                    <tr>
                                        <td>{{ $req->user->name ?? 'Deleted User' }}</td>
                                        <td>{{ ucfirst($req->package) }}</td>
                                        <td>৳{{ $req->amount }}</td>
                                        <td>{{ $req->phone_number }}</td>
                                        <td>{{ $req->operator }}</td>
                                        <td>
                                            <span class="badge bg-success">
                                                Approved
                                            </span>
                                        </td>
                                        <td>
                                            <form action="{{ route('admin.upgrade.delete', $req->id) }}" method="POST" style="display:inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-dark btn-sm" onclick="return confirm('Delete this request?')">
                                                    Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">
                                            No approved requests found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
    
                </div>
    
                {{-- Rejected --}}
                <div id="rejectedRequests"
                     class="collapse"
                     data-bs-parent="#upgradeAccordion">
    
                     <div class="table-responsive table-scroll mb-3">
                        <table class="table table-bordered table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>User</th>
                                    <th>Package</th>
                                    <th>Amount</th>
                                    <th>Phone</th>
                                    <th>Operator</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
    
                            <tbody>
                                @forelse($rejectedRequests as $req)
                                    <tr>
                                        <td>{{ $req->user->name ?? 'Deleted User' }}</td>
                                        <td>{{ ucfirst($req->package) }}</td>
                                        <td>৳{{ $req->amount }}</td>
                                        <td>{{ $req->phone_number }}</td>
                                        <td>{{ $req->operator }}</td>
                                        <td>
                                            <span class="badge bg-danger">
                                                Rejected
                                            </span>
                                        </td>
                                        <td>
                                            <form action="{{ route('admin.upgrade.delete', $req->id) }}" method="POST" style="display:inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-dark btn-sm" onclick="return confirm('Delete this request?')">
                                                    Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">
                                            No rejected requests found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
    
                </div>
    
            </div>
    
        </div>
    </div>
	<div class="product-area">
	
        <div class="row mb-4 p-3 bg-white rounded shadow-sm">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="status_filter" class="font-weight-bold"><i class="fas fa-filter text-primary mr-1"></i> Status Filter:</label>
                    <select id="status_filter" name="status_filter" class="form-control" style="height: 40px; border-radius: 6px;">
                        <option value="all">Show All Members</option>
                        <option value="active">Active Members Only</option>
                        <option value="disabled">Disabled Members Only</option>
                    </select>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="form-group">
                    <label for="sort_filter" class="font-weight-bold"><i class="fas fa-sort text-primary mr-1"></i> Sort By:</label>
                    <select id="sort_filter" name="sort_filter" class="form-control" style="height: 40px; border-radius: 6px;">
                        <option value="default">Default (Highest Commission First)</option>
                        <option value="views_desc">Highest Views First</option>
                        <option value="balance_desc">Highest Balance First</option>
                        <option value="banned_first">Disabled Members First</option>
                    </select>
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
									    <th>{{ __('Photo') }}</th>
				                        <th>{{ __('Name') }}</th>
										
				                        <th>{{ __('Email') }}</th>
				                        <th>{{ __('Phone') }}</th>
				                        <th>{{ __('Reader Type') }}</th>
										<th>{{ __('Views') }}</th>
										<th>{{ __('Balance') }}</th>
										<th>{{ __('Status') }}</th>
										
				                        <th>{{ __('Options') }}</th>
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

<!-- Buttons JS -->
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

<!-- Excel Export (JSZip) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

<!-- PDF Export (pdfmake) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script type="text/javascript">
	



	var table = $('#geniustable').DataTable({
		ordering: true,
		processing: true,
		serverSide: true,
		order: [[6, 'desc']], // default sort by Balance desc (now column index 6 due to reader_type)
		
        pageLength: 30,
        lengthMenu: [30, 50, 100, 150, 200],
        dom: 'Blfrtip',
        
		ajax: {
			url: '{{ route('admin.readers.datatables') }}',
			data: function (data) {
				data.user_id = $('select[name=user_id]').val();
				data.reporter_area = $('select[name=reporter_area]').val();
				data.report_type = $('select[name=report_type]').val();
				data.pending_status = '{{$pending_status}}';
				data.status_filter = $('#status_filter').val();
				data.sort_filter = $('#sort_filter').val();
			}
		},
		columns: [
		    { data: 'photo', name: 'photo', orderable: false, searchable: false },
			{ data: 'name', name: 'name' },
			
			{ data: 'email', name: 'email' },
			{ data: 'phone', name: 'phone' },
			{ data: 'reader_type', name: 'reader_type' },
			{ data: 'total_views', name: 'total_views' },
			{ data: 'total_commission', name: 'total_commission' },
			{ data: 'is_ban', name: 'is_ban' },
			
			{ data: 'action', searchable: false, orderable: false }
		],
		language: {
			processing: '<img src="{{ asset('assets/images/'.$gs->admin_loader) }}">'
		},

		// Enable export buttons
// 		dom: 'Bfrtip',
		buttons: [
			{
				extend: 'excelHtml5',
				title: 'Reporter List',
				exportOptions: {
					columns: ':visible:not(:last-child)' // Exclude the action column
				}
			},
			{
				extend: 'pdfHtml5',
				title: 'Reporter List',
				orientation: 'landscape', // optional: 'portrait' or 'landscape'
				pageSize: 'A4',
				exportOptions: {
					columns: ':visible:not(:last-child)'
				},
				customize: function (doc) {
					doc.styles.tableHeader.alignment = 'left'; 
					doc.defaultStyle.alignment = 'left';
					
					doc.content[1].table.widths = "*";
				}
			},
			{
				extend: 'csvHtml5',
				title: 'Reporter List',
				exportOptions: {
					columns: ':visible:not(:last-child)'
				}
			},
			{
				extend: 'print',
				title: 'Reporter List',
				exportOptions: {
					columns: ':visible:not(:last-child)'
				}
			}
		]
	});

			
	 $('#report_type,#reporter_area,#status_filter,#sort_filter').change(function(){
			table.draw();
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

  	$(function() {
        $(".btn-area").append('<div class="col-sm-4 text-right">'+
        	'<a class="add-btn" data-href="{{route('admin.staff.create')}}" id="add-data" data-toggle="modal" data-target="#modal1">'+
          '<i class="fas fa-plus"></i> {{ __('Add New Reporter') }}'+
          '</a>'+
          '</div>');
      });

    </script>

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

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

    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .content-area {
        padding: 20px;
    }

    .product-area {
        padding: 18px;
    }

    .reporter-filter-row {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        align-items: flex-end;
        padding: 0;
    }

    .reporter-filter-row .filter-item {
        flex: 1 1 120px;
        min-width: 110px;
        margin: 0 0 10px !important;
    }

    .reporter-filter-row .filter-item-wide {
        flex-basis: 150px;
    }

    .reporter-filter-row .filter-action {
        flex: 0 0 132px;
    }

    .reporter-filter-row label {
        display: block;
        margin-bottom: 4px;
        white-space: nowrap;
    }

    .reporter-filter-row .form-control,
    .reporter-filter-row .btn {
        height: 38px;
        padding-left: 8px;
        padding-right: 8px;
        font-size: 13px;
    }

    .rejected-reporters-section {
        display: none;
    }

    .btn-gradient-primary {
        background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
        color: #fff !important;
        border: none;
        transition: all 0.3s ease;
    }
    .btn-gradient-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4) !important;
        opacity: 0.95;
    }
    .btn-gradient-warning {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: #fff !important;
        border: none;
        transition: all 0.3s ease;
    }
    .btn-gradient-warning:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.4) !important;
        opacity: 0.95;
    }
    .gap-2 {
        gap: 0.5rem !important;
    }
    .gap-3 {
        gap: 1rem !important;
    }

    @media (min-width: 1200px) {
        .reporter-filter-row {
            flex-wrap: nowrap;
        }
    }

    @media (max-width: 767px) {
        .content-area {
            padding: 12px;
        }

        .product-area {
            padding: 12px;
        }
    }

    #geniustable, #rejectedtable, #nopurchasetable, #noposttable {
        font-size: 12px !important;
    }
    #geniustable th, #geniustable td,
    #rejectedtable th, #rejectedtable td,
    #nopurchasetable th, #nopurchasetable td,
    #noposttable th, #noposttable td {
        padding: 5px 3px !important;
        vertical-align: middle !important;
        white-space: nowrap !important;
    }
    .action-list {
        display: flex;
        gap: 3px;
        flex-wrap: nowrap;
    }
    .action-list a, .action-list button {
        padding: 2px 4px !important;
        font-size: 10px !important;
        white-space: nowrap !important;
    }
    .email-cell {
        max-width: 100px !important;
        overflow: hidden !important;
        text-overflow: ellipsis !important;
        white-space: nowrap !important;
        cursor: pointer !important;
    }
    .email-cell.expanded-email {
        max-width: none !important;
        overflow: visible !important;
        white-space: normal !important;
        word-break: break-all !important;
    }
</style>

<input type="hidden" id="headerdata" value="{{ __('Reporter') }}">

<div class="content-area">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-8">
                <h4 class="heading">{{ __('Reporter') }}</h4>
                <ul class="links">
                    <li>
                        <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.staff.index') }}">{{ __('Manage Reporter') }}</a>
                    </li>
                </ul>
            </div>
            <div class="col-lg-4 text-right">
                <a class="add-btn btn btn-primary rounded-pill font-weight-bold" data-href="{{ route('admin.staff.create') }}" id="add-data" data-toggle="modal" data-target="#modal1" style="cursor: pointer; display: inline-block; padding: 10px 25px;">
                    <i class="fas fa-plus"></i> {{ __('Create User') }}
                </a>
            </div>
        </div>
    </div>

    <div class="product-area">
    <div class="row reporter-filter-row">
        <div class="filter-item">
            <label><b>Area</b></label>
            <select name="reporter_area" id="reporter_area" class="form-control">
                <option value="">All Area</option>
                @foreach (reporter_area(Auth::user()->reporter_area) as $key => $status)
                    <option value="{{ $key }}">{{ $status }}</option>
                @endforeach
            </select>
        </div>

        <div class="filter-item">
            <label><b>Type</b></label>
            <select name="report_type" id="report_type" class="form-control">
                <option value="">All Type</option>
                @foreach (report_type(2) as $key => $report_cat)
                    <option value="{{ $key }}">{{ $report_cat }}</option>
                @endforeach
            </select>
        </div>

        <!--<div class="filter-item">-->
        <!--    <label><b>Month</b></label>-->
        <!--   <input -->
        <!--    id="month" -->
        <!--    type="month" -->
        <!--    class="form-control" -->
        <!--    value="{{ $startOfLastMonth->format('Y-m') }}" -->
        <!--    max="{{ \Carbon\Carbon::now()->format('Y-m') }}">-->
        <!--</div>-->
        
        <div class="filter-item">
            <label><b>Division</b></label>
            <select name="division_id" id="filter_division_id" class="form-control">
                <option value="">All Division</option>
                @foreach($divisions as $division)
                    <option value="{{ $division->id }}">{{ $division->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="filter-item">
            <label><b>District</b></label>
            <select name="district_id" id="filter_district_id" class="form-control">
                <option value="">All District</option>
            </select>
        </div>
        <div class="filter-item">
            <label><b>Thana</b></label>
            <select name="thana_id" id="filter_thana_id" class="form-control">
                <option value="">All Thana</option>
            </select>
        </div>
        <div class="filter-item">
            <label><b>Joined</b></label>
            <select name="date_filter" id="date_filter" class="form-control">
                <option value="">All Dates</option>
                <option value="last_3_days">Last 3 Days</option>
                <option value="last_7_days">Last 7 Days</option>
                <option value="last_month">Last Month</option>
            </select>
        </div>
        <div class="filter-item">
            <label><b>Status</b></label>
            <select name="user_status" id="user_status" class="form-control">
                <option value="">All Status</option>
                <option value="pending">Pending</option>
                <option value="approved">Approved</option>
                <option value="rejected">Rejected</option>
            </select>
        </div>
        <!--<div class="filter-item filter-item-wide">-->
        <!--    <label><b>Sort</b></label>-->
        <!--    <select name="sort_by" id="sort_by" class="form-control">-->
        <!--        <option value="latest">Latest First</option>-->
        <!--        <option value="location">Location</option>-->
        <!--    </select>-->
        <!--</div>-->

    </div>
     <div class="row reporter-filter-row">
     <div class="d-flex flex-wrap gap-3 align-items-center mt-3 mb-4 p-3 bg-light rounded shadow-sm border col-12">
         <div class="mr-auto d-flex align-items-center">
             <h5 class="m-0 text-secondary"><i class="fas fa-tasks mr-2 text-primary"></i> Reporter Actions</h5>
         </div>
         <div class="d-flex flex-wrap gap-2">
             {{-- <button type="button" id="generate-top-reporters" class="btn btn-gradient-primary rounded-pill px-4 shadow-sm">
                 <i class="fas fa-medal mr-2"></i> Best Reporter of Month
             </button> --}}
             <button type="button" id="choose-weekly-best" class="btn btn-gradient-warning rounded-pill px-4 shadow-sm text-white">
                 <i class="fas fa-trophy mr-2"></i> Weekly Best Reporter
             </button>
              <button type="button" id="toggle-rejected-reporters" class="btn btn-outline-danger rounded-pill px-4">
                  <i class="fas fa-user-slash mr-2"></i> Show Rejected
              </button>
              <button type="button" id="toggle-no-purchase-reporters" class="btn btn-outline-secondary rounded-pill px-4">
                  <i class="fas fa-shopping-cart mr-2"></i> No purchased Reporters
              </button>
              <button type="button" id="toggle-no-post-reporters" class="btn btn-outline-info rounded-pill px-4">
                  <i class="fas fa-newspaper mr-2"></i> No Post/Pending/Rejected Post Only
              </button>
         </div>
     </div>
         </div>

    <div class="row p-4">
        <div class="col-lg-12">
            <h4>Top 3 Reporters (<span id="reporter-date-range">{{ $startOfLastMonth->format('d M Y') }} - {{ $endOfLastMonth->format('d M Y') }}</span>)</h4>
            <div class="table-responsive">
                <table class="table table-bordered" id="top-reporters-table">
                    <thead>
                        <thead>
                            <tr>
                                <th>Position</th>
                                <th>Photo</th>
                                <th>Name</th>
                                <th>Phone</th>
                                <!--<th>Next Payment Date</th>-->
                                <th>Reporter Type</th>
                                <th>Reporter Area</th>
                                <th>Total Views</th>
                            </tr>
                        </thead>

                    </thead>
                    <tbody>
                        @foreach($topReporters as $key => $reporter)
                            <tr>
                                <td>
                                    @if($key == 0) 1st 
                                    @elseif($key == 1) 2nd 
                                    @elseif($key == 2) 3rd 
                                    @endif
                                </td>
                                <td>
                                    <img 
                                        src="{{ $reporter->photo ? asset('assets/images/admin/' . $reporter->photo) : asset('assets/images/default_user.png') }}" 
                                        alt="{{ $reporter->name }}" 
                                        width="50" 
                                        height="50" 
                                        style="border-radius: 50%; object-fit: cover;">
                                </td>
                                <td>{{ $reporter->name }}</td>
                                <td>{{ $reporter->phone }}</td>
                                <td>{{ $reporter->report_type_title }}</td>
                                 <td>{{ $reporter->reporter_area_title }}</td>

                                <td>{{ $reporter->total_views }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    
                    </table>
                </div>
            </div>
        </div>
    </div>


        <div class="row" id="reporters-section">
            <div class="col-lg-12">
                <div class="mr-table allproduct">
                    @include('includes.admin.form-success')
                    <h4 class="mb-3">Reporter List</h4>
                    <div class="table-responsive">
                        <table id="geniustable" class="table table-hover dt-responsive nowrap" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>{{ __('Photo') }}</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Desination') }}</th>
                                    <th>{{ __('Email') }}</th>
                                    <th>{{ __('Phone') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>Division</th>
                                    <th>{{ __('District') }}</th>
                                    <th>{{ __('Next Payment') }}</th>
                                    <th>{{ __('7D Posts') }}</th>
                                    <th>{{ __('Pending News') }}</th>
                                    <th>{{ __('Rejected News') }}</th>
                                    <th>{{ __('Views') }}</th>
                                    <th>{{ __('Balance') }}</th>
                                    <th>{{ __('Orders') }}</th>
                                    <th>{{ __('Joining') }}</th>
                                    <th>{{ __('Options') }}</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row rejected-reporters-section" id="rejected-reporters-section">
            <div class="col-lg-12">
                <div class="mr-table allproduct">
                    <h4 class="mb-3 text-danger">Rejected Reporter List</h4>
                    <div class="table-responsive">
                        <table id="rejectedtable" class="table table-hover dt-responsive nowrap" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>{{ __('Photo') }}</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Desination') }}</th>
                                    <th>{{ __('Email') }}</th>
                                    <th>{{ __('Phone') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>Division</th>
                                    <th>District</th>
                                    <th>{{ __('Next Payment') }}</th>
                                    <th>{{ __('7D Posts') }}</th>
                                    <th>{{ __('Pending News') }}</th>
                                    <th>{{ __('Rejected News') }}</th>
                                    <th>{{ __('Views') }}</th>
                                    <th>{{ __('Balance') }}</th>
                                    <th>{{ __('Orders') }}</th>
                                    <th>{{ __('Joining') }}</th>
                                    <th>{{ __('Options') }}</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row no-purchase-reporters-section" id="no-purchase-reporters-section" style="display: none;">
            <div class="col-lg-12">
                <div class="mr-table allproduct">
                    <h4 class="mb-3 text-secondary">No purchased Reporters List</h4>
                    <div class="table-responsive">
                        <table id="nopurchasetable" class="table table-hover dt-responsive nowrap" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>{{ __('Photo') }}</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Desination') }}</th>
                                    <th>{{ __('Email') }}</th>
                                    <th>{{ __('Phone') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>Division</th>
                                    <th>District</th>
                                    <th>{{ __('Next Payment') }}</th>
                                    <th>{{ __('7D Posts') }}</th>
                                    <th>{{ __('Pending News') }}</th>
                                    <th>{{ __('Rejected News') }}</th>
                                    <th>{{ __('Views') }}</th>
                                    <th>{{ __('Balance') }}</th>
                                    <th>{{ __('Orders') }}</th>
                                    <th>{{ __('Joining') }}</th>
                                    <th>{{ __('Options') }}</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row no-post-reporters-section" id="no-post-reporters-section" style="display: none;">
            <div class="col-lg-12">
                <div class="mr-table allproduct">
                    <h4 class="mb-3 text-info">No Post / Pending Post Only Reporters List</h4>
                    <div class="table-responsive">
                        <table id="noposttable" class="table table-hover dt-responsive nowrap" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>{{ __('Photo') }}</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Desination') }}</th>
                                    <th>{{ __('Email') }}</th>
                                    <th>{{ __('Phone') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>Division</th>
                                    <th>District</th>
                                    <th>{{ __('Next Payment') }}</th>
                                    <th>{{ __('7D Posts') }}</th>
                                    <th>{{ __('Pending News') }}</th>
                                    <th>{{ __('Rejected News') }}</th>
                                    <th>{{ __('Views') }}</th>
                                    <th>{{ __('Balance') }}</th>
                                    <th>{{ __('Orders') }}</th>
                                    <th>{{ __('Joining') }}</th>
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
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="submit-loader">
                <img src="{{ asset('assets/images/' . $gs->admin_loader) }}" alt="">
            </div>
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body"></div>
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

            <div class="modal-body">
                <p class="text-center">{{ __('You are about to delete this User.') }}</p>
                <p class="text-center">{{ __('Do you want to proceed?') }}</p>
            </div>

            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Cancel') }}</button>
                <a class="btn btn-danger btn-ok">{{ __('Delete') }}</a>
            </div>
        </div>
    </div>
</div>
{{-- DELETE MODAL ENDS --}}

{{-- WEEKLY BEST REPORTER MODAL --}}
<div class="modal fade-scale" id="weeklyBestModal" tabindex="-1" role="dialog" aria-labelledby="weeklyBestModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="weeklyBestModalLabel"><i class="fas fa-trophy text-warning mr-2"></i> Top 3 Weekly Winners (Based on last week's post views)</h5>
                <button type="button" class="close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="weekly-best-candidates-table">
                        <thead>
                            <tr>
                                <th>Position</th>
                                <th>Photo</th>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Reporter Type</th>
                                <th>Reporter Area</th>
                                <th>Views (Last 7 Days)</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="8" class="text-center">Loading candidates...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

{{-- USER ORDERS MODAL --}}
<div class="modal fade-scale" id="userOrdersModal" tabindex="-1" role="dialog" aria-labelledby="userOrdersModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userOrdersModalLabel">
                    <i class="fas fa-shopping-basket text-success mr-2"></i> 
                    User Ordered Products: <span id="ordersModalUserName" class="font-weight-bold"></span>
                </h5>
                <button type="button" class="close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-left" style="position: relative; min-height: 200px;">
                <div id="ordersModalLoader" style="display: none; position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255,255,255,0.7); z-index: 10; display: flex; align-items: center; justify-content: center;">
                    <div class="spinner-border text-success" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>

                <div id="ordersModalContent">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="user-orders-table" style="width:100%; text-align: left;">
                            <thead class="bg-light text-left">
                                <tr>
                                    <th style="text-align: left;">{{ __('Order ID / Txn ID') }}</th>
                                    <th style="text-align: left;">{{ __('Products Ordered') }}</th>
                                    <th style="text-align: left;">{{ __('Delivery Status') }}</th>
                                    <th class="text-right" style="text-align: right;">{{ __('Total Price') }}</th>
                                    <th style="text-align: left;">{{ __('Date') }}</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
{{-- USER ORDERS MODAL ENDS --}}
@endsection

@section('scripts')
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

<script type="text/javascript">
    var allDistricts = @json($districts);
    var allThanas = @json($thanas);
    var rejectedTable = null;
    var noPostTable = null;

    function reporterFilters() {
        return {
            user_id: $('select[name=user_id]').val(),
            reporter_area: $('#reporter_area').val(),
            report_type: $('#report_type').val(),
            division_id: $('#filter_division_id').val(),
            district_id: $('#filter_district_id').val(),
            thana_id: $('#filter_thana_id').val(),
            date_filter: $('#date_filter').val(),
            user_status: $('#user_status').val(),
            sort_by: $('#sort_by').val(),
            pending_status: '{{ $pending_status }}'
        };
    }

    function reporterColumns() {
        return [
            { data: 'photo', name: 'photo', orderable: false, searchable: false },
            { data: 'name', name: 'name' },
            { data: 'report_type', name: 'report_type' },
            { data: 'email', name: 'email', className: 'email-cell' },
            { data: 'phone', name: 'phone' },
            {
                data: 'is_approve',
                name: 'is_approve',
                render: function(data) {
                    if (data == 1) {
                        return '<span class="badge badge-success">Approved</span>';
                    }
                    if (data == 2) {
                        return '<span class="badge badge-danger">Rejected</span>';
                    }
                    return '<span class="badge badge-warning">Pending</span>';
                }
            },
            { data: 'division_name', name: 'division_name', defaultContent: '' },
            { data: 'district_name', name: 'district_name', defaultContent: '' },
            { data: 'next_payment_date', name: 'next_payment_date' },
            { data: 'last_7_days_posts_count', name: 'last_7_days_posts_count', searchable: false },
            { data: 'pending_posts_count', name: 'pending_posts_count', searchable: false, defaultContent: '0' },
            { data: 'rejected_posts_count', name: 'rejected_posts_count', searchable: false, defaultContent: '0' },
            { data: 'total_views', name: 'total_views' },
            { data: 'total_commission', name: 'total_commission' },
            { data: 'orders', name: 'orders', searchable: false, orderable: false },
            { data: 'created_at', name: 'created_at' },
            { data: 'action', searchable: false, orderable: false }
        ];
    }

    function reporterButtons(title) {
        return [
            {
                extend: 'excelHtml5',
                title: title,
                exportOptions: { columns: ':visible:not(:last-child)' }
            },
            {
                extend: 'pdfHtml5',
                title: title,
                orientation: 'landscape',
                pageSize: 'A4',
                exportOptions: { columns: ':visible:not(:last-child)' },
                customize: function(doc) {
                    doc.styles.tableHeader.alignment = 'left';
                    doc.defaultStyle.alignment = 'left';
                    doc.content[1].table.widths = "*";
                }
            },
            {
                extend: 'csvHtml5',
                title: title,
                exportOptions: { columns: ':visible:not(:last-child)' }
            },
            {
                extend: 'print',
                title: title,
                exportOptions: { columns: ':visible:not(:last-child)' }
            }
        ];
    }

    function buildReporterTable(selector, statusFilter, title) {
        return $(selector).DataTable({
            ordering: false,
            processing: true,
            serverSide: true,
            pageLength: 30,
            lengthMenu: [30, 50, 100, 150, 200],
            dom: 'Blfrtip',
            ajax: {
                url: '{{ route('admin.staff.datatables') }}',
                data: function(data) {
                    $.extend(data, reporterFilters(), { status_filter: statusFilter });
                }
            },
            columns: reporterColumns(),
            language: {
                processing: '<img src="{{ asset('assets/images/' . $gs->admin_loader) }}">'
            },
            buttons: reporterButtons(title)
        });
    }

    function populateDistricts() {
        var divisionId = $('#filter_division_id').val();
        var districtOptions = '<option value="">All District</option>';
        allDistricts.forEach(function(district) {
            if (!divisionId || district.division_id == divisionId) {
                districtOptions += '<option value="' + district.id + '">' + district.name + '</option>';
            }
        });
        $('#filter_district_id').html(districtOptions);
        populateThanas();
    }

    function populateThanas() {
        var districtId = $('#filter_district_id').val();
        var thanaOptions = '<option value="">All Thana</option>';
        allThanas.forEach(function(thana) {
            if (!districtId || thana.district_id == districtId) {
                thanaOptions += '<option value="' + thana.id + '">' + thana.name + '</option>';
            }
        });
        $('#filter_thana_id').html(thanaOptions);
    }

    var table = buildReporterTable('#geniustable', 'active', 'Reporter List');

    var noPurchaseTable = null;

    function showReporterPanel(showType) {
        if (showType === true) showType = 'rejected';
        if (showType === false) showType = 'active';

        $('#reporters-section').toggle(showType === 'active');
        $('#rejected-reporters-section').toggle(showType === 'rejected');
        $('#no-purchase-reporters-section').toggle(showType === 'no_purchase');
        $('#no-post-reporters-section').toggle(showType === 'no_posts');

        $('#toggle-rejected-reporters').text(showType === 'rejected' ? 'Hide Rejected' : 'Show Rejected');
        $('#toggle-no-purchase-reporters').text(showType === 'no_purchase' ? 'Hide No Purchase' : 'No purchased Reporters');
        $('#toggle-no-post-reporters').text(showType === 'no_posts' ? 'Hide No Post' : 'No Post/Pending/Rejected Post Only');

        if (showType === 'rejected') {
            $('#toggle-rejected-reporters').addClass('active btn-danger').removeClass('btn-outline-danger');
            $('#toggle-no-purchase-reporters').removeClass('active btn-secondary').addClass('btn-outline-secondary');
            $('#toggle-no-post-reporters').removeClass('active btn-info').addClass('btn-outline-info');
        } else if (showType === 'no_purchase') {
            $('#toggle-no-purchase-reporters').addClass('active btn-secondary').removeClass('btn-outline-secondary');
            $('#toggle-rejected-reporters').removeClass('active btn-danger').addClass('btn-outline-danger');
            $('#toggle-no-post-reporters').removeClass('active btn-info').addClass('btn-outline-info');
        } else if (showType === 'no_posts') {
            $('#toggle-no-post-reporters').addClass('active btn-info').removeClass('btn-outline-info');
            $('#toggle-rejected-reporters').removeClass('active btn-danger').addClass('btn-outline-danger');
            $('#toggle-no-purchase-reporters').removeClass('active btn-secondary').addClass('btn-outline-secondary');
        } else {
            $('#toggle-rejected-reporters').removeClass('active btn-danger').addClass('btn-outline-danger');
            $('#toggle-no-purchase-reporters').removeClass('active btn-secondary').addClass('btn-outline-secondary');
            $('#toggle-no-post-reporters').removeClass('active btn-info').addClass('btn-outline-info');
        }

        if (showType === 'rejected') {
            if (!rejectedTable) {
                rejectedTable = buildReporterTable('#rejectedtable', 'rejected', 'Rejected Reporter List');
            } else {
                rejectedTable.draw();
            }
        } else if (showType === 'no_purchase') {
            if (!noPurchaseTable) {
                noPurchaseTable = buildReporterTable('#nopurchasetable', 'no_purchase', 'No purchased Reporters List');
            } else {
                noPurchaseTable.draw();
            }
        } else if (showType === 'no_posts') {
            if (!noPostTable) {
                noPostTable = buildReporterTable('#noposttable', 'no_posts', 'No Post/Pending/Rejected Post Only ');
            } else {
                noPostTable.draw();
            }
        } else {
            table.draw();
        }
    }

    function redrawAllTables() {
        table.draw();
        if (rejectedTable) {
            rejectedTable.draw();
        }
        if (noPurchaseTable) {
            noPurchaseTable.draw();
        }
        if (noPostTable) {
            noPostTable.draw();
        }
    }

    $('#report_type, #reporter_area, #date_filter, #sort_by').change(redrawAllTables);

    $('#user_status').change(function() {
        showReporterPanel($(this).val() === 'rejected' ? 'rejected' : 'active');
    });

    $('#filter_division_id').change(function() {
        populateDistricts();
        redrawAllTables();
    });

    $('#filter_district_id').change(function() {
        populateThanas();
        redrawAllTables();
    });

    $('#filter_thana_id').change(redrawAllTables);

    $('#toggle-rejected-reporters').click(function() {
        var willShow = !$('#rejected-reporters-section').is(':visible');
        if (willShow) {
            $('#user_status').val('rejected');
        } else {
            $('#user_status').val('');
        }
        showReporterPanel(willShow ? 'rejected' : 'active');
    });

    $('#toggle-no-purchase-reporters').click(function() {
        var willShow = !$('#no-purchase-reporters-section').is(':visible');
        showReporterPanel(willShow ? 'no_purchase' : 'active');
    });

    $('#toggle-no-post-reporters').click(function() {
        var willShow = !$('#no-post-reporters-section').is(':visible');
        showReporterPanel(willShow ? 'no_posts' : 'active');
    });

    populateDistricts();

    $(function() {
        $(".btn-area").append(
            '<div class="col-sm-4 text-right">' +
                '<a class="add-btn" data-href="{{ route('admin.staff.create') }}" id="add-data" data-toggle="modal" data-target="#modal1">' +
                    '<i class="fas fa-plus"></i> {{ __('Add New Reporter') }}' +
                '</a>' +
            '</div>'
        );
    });
</script>

<script>
$(document).ready(function() {

   function loadTopReporters(month) {
    let [year, m] = month.split('-');

        $.ajax({
            url: "{{ route('admin.staff.top_reporters') }}",
            type: 'GET',
            data: { year: year, month: m },
            success: function(res) {

                $('#top-reporters-table tbody').html(res.tbody);
                $('#reporter-date-range').text(res.date_range);
            }
        });
    }
    
    $('#month').change(function() {
        let month = $(this).val();
        if(month) loadTopReporters(month);
    });


});
</script>

<script>
    $(document).on('click', '.email-cell', function() {
        $(this).toggleClass('expanded-email');
    });

    $(document).on('click', '.update-next-payment', function() {
        let reporterId = $(this).data('id');
        let button = $(this);

        if(!confirm("আপনি কি নিশ্চিত যে এই রিপোর্টারের পরবর্তী পেমেন্টের তারিখ আপডেট করতে চান?")) {
            return; 
        }
    
        $.ajax({
            url: "{{ route('admin.staff.update_next_payment') }}",
            method: 'POST',
            data: {
                id: reporterId,
                _token: "{{ csrf_token() }}"
            },
            beforeSend: function() {
                button.prop('disabled', true).text('Updating...');
            },
            success: function(res) {
                if(res.success){
                    button.text('Updated ✅');
                    setTimeout(() => button.text('Update Next Payment').prop('disabled', false), 2000);
                    table.ajax.reload(null, false); 
                }
            },
            error: function(err) {
                console.log(err);
                button.text('Error ❌').prop('disabled', false);
            }
        });
    });
</script>
<script>
$('#generate-top-reporters').click(function() {

    let month = $('#month').val();

    if(!month){
        alert('Please select a month');
        return;
    }

    if(!confirm('Generate Top 3 Reporters for this month?')){
        return;
    }

    $.ajax({
        url: "{{ route('admin.staff.generate_top_reporters') }}",
        type: "POST",
        data: {
            month: month,
            _token: "{{ csrf_token() }}"
        },
        success: function(res){

            if(res.success){
                alert(res.message);
            }else{
                alert('Failed');
            }
        },
        error: function(){
            alert('Something went wrong');
        }
    });

});
</script>
<script>
$(document).ready(function() {
    function loadWeeklyBestCandidates() {
        $('#weekly-best-candidates-table tbody').html('<tr><td colspan="8" class="text-center">Loading winners...</td></tr>');
        $.ajax({
            url: "{{ route('admin.staff.weekly_best_candidates') }}",
            type: 'GET',
            success: function(res) {
                if (res.tbody && res.tbody !== '') {
                    $('#weekly-best-candidates-table tbody').html(res.tbody);
                    $('#weeklyBestModalLabel').html('<i class="fas fa-trophy text-warning mr-2"></i> Top 3 Weekly Winners (' + res.week_range + ')');
                    if (typeof table !== 'undefined') {
                        table.ajax.reload(null, false);
                    }
                } else {
                    $('#weekly-best-candidates-table tbody').html('<tr><td colspan="8" class="text-center text-muted">No winners found.</td></tr>');
                    $('#weeklyBestModalLabel').html('<i class="fas fa-trophy text-warning mr-2"></i> Top 3 Weekly Winners');
                }
            },
            error: function() {
                $('#weekly-best-candidates-table tbody').html('<tr><td colspan="8" class="text-center text-danger">Error loading weekly winners.</td></tr>');
            }
        });
    }

    $('#choose-weekly-best').click(function() {
        loadWeeklyBestCandidates();
        $('#weeklyBestModal').modal('show');
    });
});
</script>

<script>
$(document).ready(function() {
    $(document).on('click', '.view-orders', function() {
        var userId = $(this).data('id');
        var userName = $(this).data('name');
        
        $('#ordersModalUserName').text(userName);
        $('#user-orders-table tbody').html('');
        $('#ordersModalLoader').show();
        $('#userOrdersModal').modal('show');
        
        $.ajax({
            url: "{{ url('/admin/user') }}/" + userId + "/orders",
            type: 'GET',
            success: function(orders) {
                $('#ordersModalLoader').hide();
                var html = '';
                if (!orders || orders.length === 0) {
                    html = '<tr><td colspan="5" class="text-center text-muted">No orders found for this user.</td></tr>';
                } else {
                    orders.forEach(function(order) {
                        var itemsHtml = '<ul class="pl-3 mb-0 text-left">';
                        if (order.items && order.items.length > 0) {
                            order.items.forEach(function(item) {
                                var productName = item.product ? item.product.name : 'Unknown Product';
                                itemsHtml += '<li>' + productName + ' <strong class="text-muted">x' + item.quantity + '</strong> (<span class="text-success">৳' + parseFloat(item.price).toFixed(2) + '</span>)</li>';
                            });
                        } else {
                            itemsHtml += '<li>No items in this order</li>';
                        }
                        itemsHtml += '</ul>';

                        var statusBadge = '';
                        if (order.status === 'completed') {
                            statusBadge = '<span class="badge badge-success">Completed</span>';
                        } else if (order.status === 'pending') {
                            statusBadge = '<span class="badge badge-warning">Pending</span>';
                        } else {
                            statusBadge = '<span class="badge badge-secondary">' + order.status + '</span>';
                        }

                        var date = new Date(order.created_at);
                        var formattedDate = date.toLocaleDateString('en-US', { day: 'numeric', month: 'short', year: 'numeric' }) + ' ' + date.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });

                        html += '<tr>' +
                            '<td>' + (order.transaction_id || order.id) + '</td>' +
                            '<td>' + itemsHtml + '</td>' +
                            '<td>' + statusBadge + '</td>' +
                            '<td class="text-right font-weight-bold text-success">৳' + parseFloat(order.total_amount).toFixed(2) + '</td>' +
                            '<td>' + formattedDate + '</td>' +
                            '</tr>';
                    });
                }
                $('#user-orders-table tbody').html(html);
            },
            error: function() {
                $('#ordersModalLoader').hide();
                $('#user-orders-table tbody').html('<tr><td colspan="5" class="text-center text-danger">Error loading orders.</td></tr>');
            }
        });
    });
});
</script>
@endsection

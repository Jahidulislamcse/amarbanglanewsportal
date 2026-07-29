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

    #geniustable, #rejectedtable, #nopurchasetable, #noposttable, #nopurchasewithpoststable {
        font-size: 12px !important;
    }
    #geniustable th, #geniustable td,
    #rejectedtable th, #rejectedtable td,
    #nopurchasetable th, #nopurchasetable td,
    #noposttable th, #noposttable td,
    #nopurchasewithpoststable th, #nopurchasewithpoststable td {
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
                <button type="button" id="toggle-no-purchase-with-posts" class="btn btn-outline-warning rounded-pill px-4">
                    <i class="fas fa-shopping-basket mr-2"></i> Reporters with Post without Purchase
                </button>
                <button type="button" id="toggle-expired-reporters" class="btn btn-outline-danger rounded-pill px-4">
                    <i class="fas fa-hourglass-half mr-2"></i> Expired Payments
                </button>
          </div>
      </div>
          </div>

     <div class="row reporter-filter-row" id="expired-subfilters-row" style="display: none;">
         <div class="d-flex flex-wrap gap-3 align-items-center mb-4 p-3 bg-light rounded shadow-sm border col-12">
             <div class="mr-auto d-flex align-items-center">
                 <h6 class="m-0 text-secondary"><i class="fas fa-filter mr-2 text-danger"></i> Expired Period:</h6>
             </div>
             <div class="d-flex flex-wrap gap-2">
                 <button type="button" id="sub-expired-recent" class="btn btn-sm btn-outline-success rounded-pill px-3">
                     <i class="fas fa-hourglass-start mr-1"></i> Recently Expired (Within 10 Days)
                 </button>
                 <button type="button" id="sub-expired-1month" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                     <i class="fas fa-calendar-alt mr-1"></i> 1month (11-30 Days)
                 </button>
                 <button type="button" id="sub-expired-more" class="btn btn-sm btn-outline-warning rounded-pill px-3">
                     <i class="fas fa-calendar-times mr-1"></i> more (31-90 Days)
                 </button>
                 <button type="button" id="sub-expired-inactive" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                     <i class="fas fa-user-times mr-1"></i> inactive (More than 3 Months)
                 </button>
             </div>
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
                                    <th>{{ __('Total Posts') }}</th>
                                    <th>{{ __('7D Posts') }}</th>
                                    <th>{{ __('Pending') }}</th>
                                    <th>{{ __('Rejected') }}</th>
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
                                    <th>{{ __('Total Posts') }}</th>
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
                                    <th>{{ __('Total Posts') }}</th>
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
                                    <th>{{ __('Total Posts') }}</th>
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

        <div class="row no-purchase-with-posts-section" id="no-purchase-with-posts-section" style="display: none;">
            <div class="col-lg-12">
                <div class="mr-table allproduct">
                    <h4 class="mb-3 text-warning">Reporters Having Posts with No Purchase List</h4>
                    <div class="table-responsive">
                        <table id="nopurchasewithpoststable" class="table table-hover dt-responsive nowrap" cellspacing="0" width="100%">
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
                                    <th>{{ __('Total Posts') }}</th>
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

        <div class="row expired-recent-section" id="expired-recent-section" style="display: none;">
            <div class="col-lg-12">
                <div class="mr-table allproduct">
                    <h4 class="mb-3 text-success">Recently Expired List (Within 10 Days)</h4>
                    <div class="table-responsive">
                        <table id="expiredrecenttable" class="table table-hover dt-responsive nowrap" cellspacing="0" width="100%">
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
                                    <th>{{ __('Total Posts') }}</th>
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

        <div class="row expired-1month-section" id="expired-1month-section" style="display: none;">
            <div class="col-lg-12">
                <div class="mr-table allproduct">
                    <h4 class="mb-3 text-primary">1 Month Expired List (11-30 Days)</h4>
                    <div class="table-responsive">
                        <table id="expired1monthtable" class="table table-hover dt-responsive nowrap" cellspacing="0" width="100%">
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
                                    <th>{{ __('Total Posts') }}</th>
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

        <div class="row expired-more-section" id="expired-more-section" style="display: none;">
            <div class="col-lg-12">
                <div class="mr-table allproduct">
                    <h4 class="mb-3 text-warning">Expired More Than 1 Month List (31-90 Days)</h4>
                    <div class="table-responsive">
                        <table id="expiredmoretable" class="table table-hover dt-responsive nowrap" cellspacing="0" width="100%">
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
                                    <th>{{ __('Total Posts') }}</th>
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

        <div class="row expired-inactive-section" id="expired-inactive-section" style="display: none;">
            <div class="col-lg-12">
                <div class="mr-table allproduct">
                    <h4 class="mb-3 text-danger">Inactive Expired List (More Than 3 Months / >90 Days)</h4>
                    <div class="table-responsive">
                        <table id="expiredinactivetable" class="table table-hover dt-responsive nowrap" cellspacing="0" width="100%">
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
                                    <th>{{ __('Total Posts') }}</th>
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
                <div id="ordersModalLoader" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255,255,255,0.7); z-index: 10; display: none; align-items: center; justify-content: center;">
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
    var noPurchaseWithPostsTable = null;

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

    function reporterColumns(statusFilter = '') {
        var cols = [
            { data: 'photo', name: 'users.photo', orderable: false, searchable: false },
            { data: 'name', name: 'users.name' },
            { data: 'report_type', name: 'users.report_type' },
            { data: 'email', name: 'users.email', className: 'email-cell' },
            { data: 'phone', name: 'users.phone' },
            {
                data: 'is_approve',
                name: 'users.is_approve',
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
            { data: 'division_name', name: 'divisions.name', defaultContent: '' },
            { data: 'district_name', name: 'districts.name', defaultContent: '' },
            { data: 'next_payment_date', name: 'users.next_payment_date' }
        ];

        cols.push({ data: 'total_posts_count', name: 'total_posts_count', searchable: false, defaultContent: '0' });
        cols.push({ data: 'last_7_days_posts_count', name: 'last_7_days_posts_count', searchable: false, defaultContent: '0' });
        cols.push({ data: 'pending_posts_count', name: 'pending_posts_count', searchable: false, defaultContent: '0' });
        cols.push({ data: 'rejected_posts_count', name: 'rejected_posts_count', searchable: false, defaultContent: '0' });

        cols.push(
            { data: 'total_views', name: 'users.views' },
            { data: 'total_commission', name: 'users.balance' },
            { data: 'orders', name: 'orders', searchable: false, orderable: false },
            { data: 'created_at', name: 'users.created_at' },
            { data: 'action', searchable: false, orderable: false }
        );

        return cols;
    }

    function reporterButtons(title) {
        var prependSerial = function (data) {
            data.header.unshift("SL No");
            for (var i = 0; i < data.body.length; i++) {
                data.body[i].unshift(i + 1);
            }
        };
        return [
            {
                extend: 'excelHtml5',
                title: title,
                exportOptions: {
                    columns: ':visible:not(:last-child)',
                    customizeData: prependSerial
                }
            },
            {
                extend: 'pdfHtml5',
                title: title,
                orientation: 'landscape',
                pageSize: 'A4',
                exportOptions: {
                    columns: ':visible:not(:last-child)',
                    customizeData: prependSerial
                },
                customize: function(doc) {
                    doc.styles.tableHeader.alignment = 'left';
                    doc.defaultStyle.alignment = 'left';
                    doc.content[1].table.widths = "*";
                }
            },
            {
                extend: 'csvHtml5',
                title: title,
                exportOptions: {
                    columns: ':visible:not(:last-child)',
                    customizeData: prependSerial
                }
            },
            {
                extend: 'print',
                title: title,
                exportOptions: {
                    columns: ':visible:not(:last-child)',
                    customizeData: prependSerial
                }
            }
        ];
    }

    function buildReporterTable(selector, statusFilter, title) {
        return $(selector).DataTable({
            ordering: false,
            processing: true,
            serverSide: true,
            pageLength: 100,
            lengthMenu: [100, 200, 300, 400, 500],
            dom: 'Blfrtip',
            ajax: {
                url: '{{ route('admin.staff.datatables') }}',
                data: function(data) {
                    $.extend(data, reporterFilters(), { status_filter: statusFilter });
                }
            },
            columns: reporterColumns(statusFilter),
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
    var rejectedTable = null;
    var noPostTable = null;
    var noPurchaseWithPostsTable = null;
    var expiredRecentTable = null;
    var expired1monthTable = null;
    var expiredMoreTable = null;
    var expiredInactiveTable = null;

    var activeExpiredSubFilter = 'expired_recent';

    function showReporterPanel(showType) {
        if (showType === true) showType = 'rejected';
        if (showType === false) showType = 'active';

        var expiredTypes = ['expired_recent', 'expired_1month', 'expired_more', 'expired_inactive'];
        var isExpiredType = expiredTypes.indexOf(showType) !== -1;

        $('#reporters-section').toggle(showType === 'active');
        $('#rejected-reporters-section').toggle(showType === 'rejected');
        $('#no-purchase-reporters-section').toggle(showType === 'no_purchase');
        $('#no-post-reporters-section').toggle(showType === 'no_posts');
        $('#no-purchase-with-posts-section').toggle(showType === 'no_purchase_with_posts');
        $('#expired-recent-section').toggle(showType === 'expired_recent');
        $('#expired-1month-section').toggle(showType === 'expired_1month');
        $('#expired-more-section').toggle(showType === 'expired_more');
        $('#expired-inactive-section').toggle(showType === 'expired_inactive');

        // Toggle sub-filters row visibility
        $('#expired-subfilters-row').toggle(isExpiredType);

        var buttons = [
            { id: '#toggle-rejected-reporters', activeClass: 'btn-danger', outlineClass: 'btn-outline-danger', type: 'rejected', activeHtml: '<i class="fas fa-user-slash mr-2"></i> Hide Rejected', defaultHtml: '<i class="fas fa-user-slash mr-2"></i> Show Rejected' },
            { id: '#toggle-no-purchase-reporters', activeClass: 'btn-secondary', outlineClass: 'btn-outline-secondary', type: 'no_purchase', activeHtml: '<i class="fas fa-shopping-cart mr-2"></i> Hide No Purchase', defaultHtml: '<i class="fas fa-shopping-cart mr-2"></i> No purchased Reporters' },
            { id: '#toggle-no-post-reporters', activeClass: 'btn-info', outlineClass: 'btn-outline-info', type: 'no_posts', activeHtml: '<i class="fas fa-newspaper mr-2"></i> Hide No Post', defaultHtml: '<i class="fas fa-newspaper mr-2"></i> No Post/Pending/Rejected Post Only' },
            { id: '#toggle-no-purchase-with-posts', activeClass: 'btn-warning', outlineClass: 'btn-outline-warning', type: 'no_purchase_with_posts', activeHtml: '<i class="fas fa-shopping-basket mr-2"></i> Hide Reporters with Post without Purchase', defaultHtml: '<i class="fas fa-shopping-basket mr-2"></i> Reporters with Post without Purchase' },
            { id: '#toggle-expired-reporters', activeClass: 'btn-danger', outlineClass: 'btn-outline-danger', type: 'expired_parent', activeHtml: '<i class="fas fa-hourglass-half mr-2"></i> Hide Expired Payments', defaultHtml: '<i class="fas fa-hourglass-half mr-2"></i> Expired Payments' }
        ];

        buttons.forEach(function(btn) {
            var isActive = (showType === btn.type) || (btn.type === 'expired_parent' && isExpiredType);
            if (isActive) {
                $(btn.id).addClass('active ' + btn.activeClass).removeClass(btn.outlineClass).html(btn.activeHtml);
            } else {
                $(btn.id).removeClass('active ' + btn.activeClass).addClass(btn.outlineClass).html(btn.defaultHtml);
            }
        });

        // Sub-buttons styling
        var subButtons = [
            { id: '#sub-expired-recent', activeClass: 'btn-success', outlineClass: 'btn-outline-success', type: 'expired_recent' },
            { id: '#sub-expired-1month', activeClass: 'btn-primary', outlineClass: 'btn-outline-primary', type: 'expired_1month' },
            { id: '#sub-expired-more', activeClass: 'btn-warning', outlineClass: 'btn-outline-warning', type: 'expired_more' },
            { id: '#sub-expired-inactive', activeClass: 'btn-danger', outlineClass: 'btn-outline-danger', type: 'expired_inactive' }
        ];

        subButtons.forEach(function(btn) {
            if (showType === btn.type) {
                $(btn.id).addClass('active ' + btn.activeClass).removeClass(btn.outlineClass);
            } else {
                $(btn.id).removeClass('active ' + btn.activeClass).addClass(btn.outlineClass);
            }
        });

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
        } else if (showType === 'no_purchase_with_posts') {
            if (!noPurchaseWithPostsTable) {
                noPurchaseWithPostsTable = buildReporterTable('#nopurchasewithpoststable', 'no_purchase_with_posts', 'Post with No Purchase Reporters List');
            } else {
                noPurchaseWithPostsTable.draw();
            }
        } else if (showType === 'expired_recent') {
            if (!expiredRecentTable) {
                expiredRecentTable = buildReporterTable('#expiredrecenttable', 'expired_recent', 'Recently Expired Reporter List');
            } else {
                expiredRecentTable.draw();
            }
        } else if (showType === 'expired_1month') {
            if (!expired1monthTable) {
                expired1monthTable = buildReporterTable('#expired1monthtable', 'expired_1month', '1 Month Expired Reporter List');
            } else {
                expired1monthTable.draw();
            }
        } else if (showType === 'expired_more') {
            if (!expiredMoreTable) {
                expiredMoreTable = buildReporterTable('#expiredmoretable', 'expired_more', 'More than 1 Month Expired Reporter List');
            } else {
                expiredMoreTable.draw();
            }
        } else if (showType === 'expired_inactive') {
            if (!expiredInactiveTable) {
                expiredInactiveTable = buildReporterTable('#expiredinactivetable', 'expired_inactive', 'Inactive Reporter List');
            } else {
                expiredInactiveTable.draw();
            }
        } else {
            table.draw();
        }
    }

    function redrawAllTables() {
        table.draw();
        if (rejectedTable) rejectedTable.draw();
        if (noPurchaseTable) noPurchaseTable.draw();
        if (noPostTable) noPostTable.draw();
        if (noPurchaseWithPostsTable) noPurchaseWithPostsTable.draw();
        if (expiredRecentTable) expiredRecentTable.draw();
        if (expired1monthTable) expired1monthTable.draw();
        if (expiredMoreTable) expiredMoreTable.draw();
        if (expiredInactiveTable) expiredInactiveTable.draw();
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

    $('#toggle-no-purchase-with-posts').click(function() {
        var willShow = !$('#no-purchase-with-posts-section').is(':visible');
        showReporterPanel(willShow ? 'no_purchase_with_posts' : 'active');
    });

    $('#toggle-expired-reporters').click(function() {
        var willShow = !$('#expired-subfilters-row').is(':visible');
        showReporterPanel(willShow ? activeExpiredSubFilter : 'active');
    });

    $('#sub-expired-recent').click(function() {
        activeExpiredSubFilter = 'expired_recent';
        showReporterPanel('expired_recent');
    });

    $('#sub-expired-1month').click(function() {
        activeExpiredSubFilter = 'expired_1month';
        showReporterPanel('expired_1month');
    });

    $('#sub-expired-more').click(function() {
        activeExpiredSubFilter = 'expired_more';
        showReporterPanel('expired_more');
    });

    $('#sub-expired-inactive').click(function() {
        activeExpiredSubFilter = 'expired_inactive';
        showReporterPanel('expired_inactive');
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
        let reporterId = $(this).attr('data-id');
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
        var userId = $(this).attr('data-id');
        var userName = $(this).attr('data-name');
        
        $('#ordersModalUserName').text(userName);
        $('#user-orders-table tbody').html('');
        $('#ordersModalLoader').css('display', 'flex');
        $('#userOrdersModal').modal('show');
        
        $.ajax({
            url: "{{ route('admin.staff.orders', ['id' => ':id'], false) }}".replace(':id', userId),
            type: 'GET',
            success: function(orders) {
                $('#ordersModalLoader').css('display', 'none');
                var html = '';
                if (!orders || orders.length === 0) {
                    html = '<tr><td colspan="5" class="text-center text-muted">No orders found for this user.</td></tr>';
                } else {
                    orders.forEach(function(order) {
                        var itemsHtml = '<ul class="pl-3 mb-0 text-left">';
                        if (order.items && order.items.length > 0) {
                            order.items.forEach(function(item) {
                                var productName = item.product ? item.product.name : 'Unknown Product';
                                itemsHtml += '<li>' + productName + ' <strong class="text-muted">x' + item.quantity + '</strong> (<span class="text-success">৳' + parseFloat(item.price || 0).toFixed(2) + '</span>)</li>';
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

                        var formattedDate = 'N/A';
                        if (order.created_at) {
                            var dateStr = order.created_at.toString().trim();
                            if (dateStr.indexOf(' ') > 0 && dateStr.indexOf('T') === -1) {
                                dateStr = dateStr.replace(/-/g, '/');
                            }
                            var date = new Date(dateStr);
                            if (!isNaN(date.getTime())) {
                                formattedDate = date.toLocaleDateString('en-US', { day: 'numeric', month: 'short', year: 'numeric' }) + ' ' + date.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
                            }
                        }

                        html += '<tr>' +
                            '<td>' + (order.transaction_id || order.id) + '</td>' +
                            '<td>' + itemsHtml + '</td>' +
                            '<td>' + statusBadge + '</td>' +
                            '<td class="text-right font-weight-bold text-success">৳' + parseFloat(order.total_amount || 0).toFixed(2) + '</td>' +
                            '<td>' + formattedDate + '</td>' +
                            '</tr>';
                    });
                }
                $('#user-orders-table tbody').html(html);
            },
            error: function() {
                $('#ordersModalLoader').css('display', 'none');
                $('#user-orders-table tbody').html('<tr><td colspan="5" class="text-center text-danger">Error loading orders.</td></tr>');
            }
        });
    });
});
</script>
@endsection

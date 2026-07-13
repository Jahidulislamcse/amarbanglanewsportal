@extends('layouts.admin')

@section('content')
<input type="hidden" id="headerdata" value="{{ __('REJECTED NEWS') }}">
<div class="content-area">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="heading">{{ __('Rejected News') }}</h4>
                <ul class="links">
                    <li>
                        <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                    </li>
                    <li>
                        <a href="{{ route('post.rejected') }}">{{ __('Rejected News') }}</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="product-area">
        {{-- Bulk Actions --}}
        <div class="row m-2 p-2 selectPost" style="display:none;">
            <div class="col-lg-12">
                <button class="btn btn-sm btn-danger delete m-1" data-toggle="modal" data-target="#confirm-delete-option">
                    {{__('Delete')}}
                </button>
            </div>
        </div>

        {{-- Filters --}}
        <div class="row">
            <div class="col-sm-2 m-3">
                <label><b>{{__('Language')}}</b></label>
                <select id="filter_lang">
                    @foreach ($languages as $language)
                        <option data-href="{{ route('post.rejected.datatables') }}?lang={{ $language->id }}"
                                value="{{ $language->id }}"
                                {{ $language->is_default==1 ? 'selected':''}}>
                            {{ $language->language }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-sm-2 m-3">
                <label><b>{{__('Category')}}</b></label>
                <select id="category_id"></select>
            </div>
        </div>

        {{-- Table --}}
        <div class="row">
            <div class="col-lg-12">
                <div class="mr-table allproduct">
                    @include('includes.admin.form-success')
                    @include('includes.admin.flash-message')

                    <div class="table-responsiv">
                        <table id="geniustable" class="table table-hover dt-responsive" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" class="form-check-input m-0 p-0" id="headercheck"></th>
                                    <th>{{ __('Image') }}</th>
                                    <th>{{ __('Title') }}</th>
                                    <th>{{ __('Rejection Reason') }}</th>
                                    <th>{{ __('Category') }}</th>
                                    <th>{{ __('Language') }}</th>
                                    <th>{{ __('Post Type') }}</th>
                                    <th>{{ __('Author') }}</th>
                                    <th>{{ __('Post Status') }}</th>
                                    <th>{{ __('Rejected By') }}</th>
                                    <th>{{ __('Created At') }}</th>
                                    <th>{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

{{-- DELETE CONFIRMATION MODAL --}}
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
                <p class="text-center">{{ __('You are about to delete this post.') }}</p>
                <p class="text-center">{{ __('Do you want to proceed?') }}</p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Cancel') }}</button>
                <a class="btn btn-danger btn-ok">{{ __('Delete') }}</a>
            </div>
        </div>
    </div>
</div>

{{-- BULK DELETE CONFIRMATION MODAL --}}
<div class="modal fade-scale" id="confirm-delete-option" tabindex="-1" role="dialog" aria-labelledby="modal1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header d-block text-center">
                <h4 class="modal-title d-inline-block">{{ __('Confirm Delete') }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="text-center">{{ __('You are about to delete the selected posts.') }}</p>
                <p class="text-center">{{ __('Do you want to proceed?') }}</p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Cancel') }}</button>
                <button id="delete-selected" type="button" class="btn btn-danger">{{ __('Delete') }}</button>
            </div>
        </div>
    </div>
</div>

{{-- REJECTION REASON DETAIL MODAL --}}
<div class="modal fade-scale" id="reject-reason-modal" tabindex="-1" role="dialog" aria-labelledby="reject-reason-modal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" style="font-weight: bold; color: #721c24;">{{ __('সংবাদ প্রত্যাখ্যানের কারণ / Rejection Reason') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-justify" style="font-size: 15px; line-height: 1.6; color: #495057; text-align: justify !important; padding: 20px; white-space: pre-wrap;">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
            </div>
        </div>
    </div>
</div>
{{-- REJECTION REASON DETAIL MODAL ENDS --}}

@endsection


@section('scripts')

<script>
"use strict";

var table = $('#geniustable').DataTable({
    ordering: false,
    processing: true,
    serverSide: true,
    ajax: '{{ route('post.rejected.datatables') }}', 
    columns: [
        {data: 'checkbox',name: 'checkbox'},
        {data: 'image_big',name: 'image_big'},
        {data: 'title',name: 'title'},
        {
            data: 'reject_reason',
            name: 'reject_reason',
            render: function(data, type, row) {
                if (row.reject_reason) {
                    let reason = row.reject_reason;
                    let title = 'Rejected';
                    
                    if (reason.includes('বিজ্ঞাপন/প্রচারমূলক') || reason.includes('বিজ্ঞাপন ফি')) {
                        title = 'বিজ্ঞাপন/প্রচারমূলক (Promotional)';
                    } else if (reason.includes('কর্মএলাকার বাইরে')) {
                        title = 'কর্মএলাকার বাইরে (Outside Area)';
                    } else if (reason.includes('ইতোমধ্যে প্রকাশিত')) {
                        title = 'ইতোমধ্যে প্রকাশিত (Already Published)';
                    } else if (reason.includes('ঘটনাস্থল, সময় ও তারিখ')) {
                        title = 'ঘটনাস্থল, সময় ও তারিখের অনুপস্থিতি';
                    } else if (reason.includes('অপর্যাপ্ত তথ্য ও বিবরণ')) {
                        title = 'অপর্যাপ্ত তথ্য ও বিবরণ';
                    } else if (reason.includes('যাচাই-বাছাই ছাড়া') || reason.includes('যাচাই করছি')) {
                        title = 'তথ্য যাচাই-বাছাইধীন (Under Verification)';
                    } else if (reason.includes('ছবিটি পর্যাপ্ত পরিষ্কার') || reason.includes('ছবির ওপর কোনো ধরনের')) {
                        title = 'অস্পষ্ট/নীতিমালা পরিপন্থী ছবি';
                    } else if (reason.includes('দীর্ঘদিন আগের') || reason.includes('পূর্বে ঘটে যাওয়া')) {
                        title = 'পুরোনো বা অপ্রাসঙ্গিক সংবাদ';
                    } else if (reason.includes('পাঠকসংখ্যা (ভিউ)') || reason.includes('পাঠকসংখ্যা')) {
                        title = 'কম ভিউ/পাঠক সংখ্যা (Low Views/Engagement)';
                    } else {
                        let lines = reason.split('\n');
                        let firstLine = lines[0].trim();
                        if (firstLine.length > 35) {
                            title = firstLine.substring(0, 35) + '...';
                        } else {
                            title = firstLine || 'Rejected';
                        }
                    }
                    
                    let encodedReason = encodeURIComponent(reason);
                    let escapedTitle = $('<div>').text(title).html();
                    
                    return `
                    <div class="reject-title" style="font-weight: bold; font-size: 11px; white-space: normal; line-height: 1.3;">
                        কারণ: <span style="font-weight: normal; color: #495057;">${escapedTitle}</span>
                        <a href="javascript:void(0);" class="show-details-btn" data-reason="${encodedReason}" style="display:block; color:#0f766e; text-decoration:underline; font-weight: bold; margin-top:2px;">বিস্তারিত দেখুন</a>
                    </div>`;
                }
                return '<span class="text-muted">No reason provided</span>';
            }
        },
        {data: 'category_id',name: 'category_id'},
        {data: 'language_id',name: 'language_id'},
        {data: 'post_type',name: 'post_type'},
        {data: 'admin_id',name: 'admin_id'},
        {data: 'is_approve',name: 'is_approve'},
        {data: 'rejected_by',name: 'rejected_by'},
        {data: 'created_at',name: 'created_at'},
        {data: 'action',searchable: false,orderable: false}
    ],
    language: {
        processing: '<img src="{{asset('assets/images/'.$gs->admin_loader)}}">'
    },
    drawCallback: function () {
        $('.select').niceSelect();
    }
});

    $(document).on('click', function(e) {
        if (!$(e.target).closest('.godropdown').length) {
            $('.godropdown').removeClass('active'); 
        }
    });
    
    $(document).on('click', '.go-dropdown-toggle', function(e){
        e.stopPropagation(); 
        var parent = $(this).closest('.godropdown');
        parent.toggleClass('active');
      
        $('.godropdown').not(parent).removeClass('active');
    });

    // Show rejection reason modal details
    $(document).on('click', '.show-details-btn', function() {
        const encoded = $(this).data('reason');
        if (encoded) {
            const reasonText = decodeURIComponent(encoded);
            $('#reject-reason-modal .modal-body').text(reasonText);
            $('#reject-reason-modal').modal('show');
        }
    });

    // Explicitly close rejection reason modal on clicking close/dismiss buttons
    $(document).on('click', '#reject-reason-modal [data-dismiss="modal"], #reject-reason-modal [data-bs-dismiss="modal"]', function() {
        $('#reject-reason-modal').modal('hide');
    });

</script>

<script src="{{asset('assets/admin/js/post.js')}}"></script>
<script src="{{asset('assets/admin/js/bulk.js')}}"></script>

@endsection

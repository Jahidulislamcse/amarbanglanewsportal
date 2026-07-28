@extends('layouts.admin')

@section('content')
<input type="hidden" id="headerdata" value="{{ __('CRITICAL NEWS') }}">
<div class="content-area">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="heading">{{ __('Critical News') }}</h4>
                <ul class="links">
                    <li>
                        <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                    </li>
                    <li>
                        <a href="{{ route('post.critical') }}">{{ __('Critical News') }}</a>
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
                        <option data-href="{{ route('post.critical.datatables') }}?lang={{ $language->id }}"
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
                                    <th>{{ __('Category') }}</th>
                                    <th>{{ __('Language') }}</th>
                                    <th>{{ __('Post Type') }}</th>
                                    <th>{{ __('Author') }}</th>
                                    <th>{{ __('Post Status') }}</th>
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

@endsection


@section('scripts')

<script>
"use strict";

var table = $('#geniustable').DataTable({
    ordering: false,
    processing: true,
    serverSide: true,
    ajax: '{{ route('post.critical.datatables') }}', 
    columns: [
        {data: 'checkbox',name: 'checkbox'},
        {data: 'image_big',name: 'image_big'},
        {data: 'title',name: 'title'},
        {data: 'category_id',name: 'category_id'},
        {data: 'language_id',name: 'language_id'},
        {data: 'post_type',name: 'post_type'},
        {data: 'admin_id',name: 'admin_id'},
        {data: 'is_approve',name: 'is_approve'},
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

</script>

<script src="{{asset('assets/admin/js/post.js')}}"></script>
<script src="{{asset('assets/admin/js/bulk.js')}}"></script>

@endsection

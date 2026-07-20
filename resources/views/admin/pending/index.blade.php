@extends('layouts.admin')

@section('content')
<input type="hidden" id="headerdata" value="{{ __('CATEGORY') }}">
<input type="hidden" id="attribute_data" value="{{ __('ADD NEW ATTRIBUTE') }}">
<div class="content-area">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="heading">{{ __('Pending News') }}</h4>
                <ul class="links">
                    <li>
                        <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                    </li>
                    <li>
                        <a href="{{ route('slider.index') }}">{{ __('Pending News') }}</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="product-area">
        <div class="row m-2 p-2 selectPost" style="display:none;">
            <div class="col-lg-12">
                <button class="btn btn-sm btn-danger delete m-1" data-toggle="modal" data-target="#confirm-delete-option" style="display: inline-block;">{{__('Delete')}}</button>
            
                <button id="add-to-breaking" class="btn btn-sm btn-secondary m-1" style="display: inline-block;"><i class="fa fa-plus option-icon"></i> {{__('Add to Breaking')}}</button>
                <button id="add-to-feature" class="btn btn-sm btn-secondary m-1" style="display: inline-block;"><i class="fa fa-plus option-icon"></i> {{__('Add to Feature')}}</button>
  
                <button id="remove-to-breaking" class="btn btn-sm btn-secondary m-1" style="display: inline-block;"><i class="fa fa-minus option-icon"></i> {{__('Remove to Breaking')}}</button>
                <button id="remove-to-feature" class="btn btn-sm btn-secondary m-1" style="display: inline-block;"><i class="fa fa-minus option-icon"></i> {{__('Remove to Feature')}}</button>
         
            </div>
        </div>
        <div class="row">
            <div class="col-sm-2 m-3">
                <label ><b>{{__ ('Language')}}</b></label>
                <select id="pending_filter_lang">
                    @foreach ($languages as $language)
                        <option data-href="{{ route('pending.datatables') }}?lang={{ $language->id }}" value="{{ $language->id }}" {{ $language->is_default==1 ? 'selected':''}}>{{ $language->language }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-2 m-3">
                <label ><b>{{__('Category')}}</b></label>
                <select id="category_id">

                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="mr-table allproduct">
                    @include('includes.admin.form-success')
                    @include('includes.admin.flash-message')
                    <div class="table-responsiv">
                        <table id="geniustable" class="table table-hover dt-responsive" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>
                                        <input type="checkbox" class="form-check-input m-0 p-0" id="headercheck">
                                    </th>
                                    <th>{{ __('Image') }}</th>
                                    <th>{{ __('Title') }}</th>
                                    <th>{{ __('Category') }}</th>
                                    <th>{{ __('Reporter Phone') }}</th>
                                    <th>Reporter Area</th>
                                    <th>{{ __('Reporter') }}</th>
                                    <th>{{ __('Orders') }}</th>
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

{{-- ADD / EDIT MODAL --}}

<div class="modal fade-scale" id="modal1" tabindex="-1" role="dialog" aria-labelledby="modal1" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="submit-loader">
                <img src="" alt="">
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

<div class="modal fade-scale" id="confirm-delete-option" tabindex="-1" role="dialog" aria-labelledby="modal1" aria-hidden="true">
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
                <p class="text-center">
                    {{ __('You are trying to delete post.') }}
                </p>
                <p class="text-center">{{ __('Do you want to proceed?') }}</p>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Cancel') }}</button>
                <a class="btn btn-danger bulk-delete">{{ __('Delete') }}</a>
            </div>

        </div>
    </div>
</div>

{{-- DELETE MODAL ENDS --}}


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
                <p class="text-center">
                    {{ __('You are about to delete this Pending Post.Everything under this Pending Post will be deleted') }}.
                </p>
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

{{-- DATA TABLE --}}
<script type="text/javascript">
"use strict";

    var table = $('#geniustable').DataTable({
        ordering: false,
        processing: true,
        serverSide: true,
        ajax: '{{ route('pending.datatables') }}',
        columns: [
            {data: 'checkbox',name: 'checkbox'},
            {data: 'image_big',name: 'image_big'},
            {data: 'title',name: 'title'},
            {data: 'category_id',name: 'category_id'},
            {data: 'phone',name: 'phone'},
            {data: 'reporter_area', name: 'reporter_area'},
            {data: 'reporter_id',name: 'reporter_id'},
            {data: 'orders', name: 'orders', searchable: false, orderable: false},
            {data: 'action',searchable: false,orderable: false}

        ],
        language : {
            processing: '<img src="{{asset('assets/images/'.$gs->admin_loader)}}">'
        },
        drawCallback: function (settings) {
            $('.select').niceSelect();
        }
    });

    $(document).on('click', '.view-orders', function() {
        const userId = $(this).attr('data-id');
        const userName = $(this).attr('data-name');
        
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

</script>
<script src="{{asset('assets/admin/js/pending.js')}}"></script>
<script src="{{asset('assets/admin/js/bulk.js')}}"></script>

@endsection

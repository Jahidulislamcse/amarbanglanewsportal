@extends('layouts.admin')

@section('content')
<input type="hidden" id="headerdata" value="{{ __('DISTRICT / CITY CORP') }}">
<div class="content-area">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="heading">{{ __('Districts / City Corporations') }}</h4>
                <ul class="links">
                    <li>
                        <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                    </li>
                    <li><a href="javascript:;">{{ __('Location Management') }}</a></li>
                    <li>
                        <a href="{{ route('admin.districts.index') }}">{{ __('Districts / City Corp') }}</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="product-area">
        <div class="row">
            <div class="col-lg-12">
                <div class="mr-table allproduct">
                    @include('includes.admin.form-success')
                    @include('includes.admin.flash-message')
                    <div class="table-responsiv">
                        <table id="geniustable" class="table table-hover dt-responsive" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>{{ __('English Name') }}</th>
                                    <th>{{ __('Bengali Name') }}</th>
                                    <th>{{ __('Division') }}</th>
                                    <th>{{ __('Type') }}</th>
                                    <th>{{ __('URL') }}</th>
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
                <h5 class="modal-title" id="modalTitle">{{ __('Add District / City Corp') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="add-product-content p-0 shadow-none">
                    <div class="product-description">
                        <div class="body-area shadow-none p-0">
                            <!-- Local error/success alert inside modal -->
                            <div class="alert alert-danger" style="display: none;">
                                <ul class="text-left mb-0"></ul>
                            </div>
                            <div class="alert alert-success" style="display: none;">
                                <p class="text-left mb-0"></p>
                            </div>
                            <div class="gocover" style="background: url({{ asset('assets/images/'.$gs->admin_loader) }}) no-repeat scroll center center rgba(45, 45, 45, 0.5); display: none; z-index: 9999;"></div>
                            
                            <form id="locationForm" action="{{ route('admin.districts.store') }}" method="POST">
                                {{ csrf_field() }}
                                <input type="hidden" id="editId" name="id" value="">

                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('Division') }} *</h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <select name="division_id" id="division_id_select" required class="form-control select">
                                            <option value="">{{ __('Select Division') }}</option>
                                            @foreach ($divisions as $division)
                                                <option value="{{ $division->id }}">{{ $division->name }} / {{ $division->bn_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-lg-12">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('English Name') }} *</h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <input type="text" class="input-field" name="name" id="name_input" placeholder="{{ __('e.g. Dhaka') }}" required autocomplete="off">
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-lg-12">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('Bengali Name') }} *</h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <input type="text" class="input-field" name="bn_name" id="bn_name_input" placeholder="{{ __('e.g. ঢাকা') }}" required autocomplete="off">
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-lg-12">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('URL') }}</h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <input type="text" class="input-field" name="url" id="url_input" placeholder="{{ __('e.g. www.dhaka.gov.bd') }}" autocomplete="off">
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-lg-8">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('Is City Corporation?') }}</h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 text-right">
                                        <label class="switch">
                                            <input type="checkbox" name="is_city_corporation" id="is_city_corporation_checkbox" value="1">
                                            <span class="slider round"></span>
                                        </label>
                                    </div>
                                </div>

                                <br>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <button class="addProductSubmit-btn" type="submit" id="submitBtn">{{ __('Create') }}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
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
            <div class="modal-body">
                <p class="text-center">
                    {{ __('You are about to delete this District/City Corporation.') }}
                </p>
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
@endsection

@section('scripts')
<script type="text/javascript">
    var table = $('#geniustable').DataTable({
        ordering: false,
        processing: true,
        serverSide: true,
        ajax: '{{ route('admin.districts.datatables') }}',
        columns: [
            { data: 'name', name: 'name' },
            { data: 'bn_name', name: 'bn_name' },
            { data: 'division_id', name: 'division_id' },
            { data: 'is_city_corporation', name: 'is_city_corporation' },
            { data: 'url', name: 'url' },
            { data: 'action', searchable: false, orderable: false }
        ],
        language: {
            processing: '<img src="{{ asset('assets/images/'.$gs->admin_loader) }}">',
        },
        drawCallback: function (settings) {
            $('.select').niceSelect();
        }
    });

    $(function () {
        $(".btn-area").append('<div class="col-sm-4 table-contents">' +
            '<a class="add-btn" href="javascript:;" id="btn-add-location" data-toggle="modal" data-target="#modal1">' +
            '<i class="fas fa-plus"></i>{{ __('Add District / City Corp') }}' +
            '</a>' +
            '</div>');
    });

    // Reset form for Add Operation
    $(document).on('click', '#btn-add-location', function() {
        $('#modalTitle').html('{{ __('Add District / City Corp') }}');
        $('#locationForm').attr('action', '{{ route('admin.districts.store') }}');
        $('#locationForm')[0].reset();
        $('#editId').val('');
        $('#division_id_select').val('').trigger('change');
        $('#is_city_corporation_checkbox').prop('checked', false);
        $('#submitBtn').html('{{ __('Create') }}');
        $('#modal1 .alert-danger').hide();
        $('#modal1 .alert-success').hide();
    });

    // Populate form for Edit Operation
    $(document).on('click', '.edit-location-btn', function() {
        $('#modalTitle').html('{{ __('Edit District / City Corp') }}');
        
        var id = $(this).data('id');
        var division_id = $(this).data('division_id');
        var name = $(this).data('name');
        var bn_name = $(this).data('bn_name');
        var url = $(this).data('url');
        var is_city_corporation = $(this).data('is_city_corporation');

        $('#locationForm').attr('action', mainurl + 'admin/districts/update/' + id);
        $('#editId').val(id);
        $('#division_id_select').val(division_id).trigger('change');
        $('#name_input').val(name);
        $('#bn_name_input').val(bn_name);
        $('#url_input').val(url);
        
        if (is_city_corporation == 1) {
            $('#is_city_corporation_checkbox').prop('checked', true);
        } else {
            $('#is_city_corporation_checkbox').prop('checked', false);
        }

        $('#submitBtn').html('{{ __('Update') }}');
        $('#modal1 .alert-danger').hide();
        $('#modal1 .alert-success').hide();
    });

    // Handle AJAX Form Submission
    $(document).on('submit', '#locationForm', function(e) {
        e.preventDefault();
        $('#modal1 .gocover').show();
        $('#submitBtn').prop('disabled', true);

        $.ajax({
            method: "POST",
            url: $(this).prop('action'),
            data: new FormData(this),
            dataType: 'JSON',
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {
                $('#modal1 .gocover').hide();
                $('#submitBtn').prop('disabled', false);

                if (data.errors) {
                    $('#modal1 .alert-success').hide();
                    $('#modal1 .alert-danger').show();
                    $('#modal1 .alert-danger ul').html('');
                    for (var error in data.errors) {
                        $('#modal1 .alert-danger ul').append('<li>' + data.errors[error] + '</li>');
                    }
                } else {
                    $('#geniustable').DataTable().ajax.reload();
                    $('.alert-danger').hide();
                    $('.alert-success').show();
                    $('.alert-success p').html(data);
                    $('#modal1').modal('hide');
                }
            }
        });
    });
</script>
@endsection

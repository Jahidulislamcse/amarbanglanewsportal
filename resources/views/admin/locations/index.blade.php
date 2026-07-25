@extends('layouts.admin')

@section('styles')
<style>
    /* Child row form adjustments */
    .child-edit-form {
        background-color: #f7fafc !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 6px;
        box-shadow: inset 0 2px 4px 0 rgba(0,0,0,0.06);
    }
    
    /* Tabs styling */
    .nav-tabs .nav-link {
        font-weight: 600;
        color: #4a5568;
        border: 1px solid transparent;
        border-top-left-radius: .25rem;
        border-top-right-radius: .25rem;
    }
    .nav-tabs .nav-link.active {
        color: #2d3748;
        background-color: #fff;
        border-color: #dee2e6 #dee2e6 #fff;
    }
</style>
@endsection

@section('content')
<div class="content-area">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="heading">{{ __('Location Management') }}</h4>
                <ul class="links">
                    <li>
                        <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                    </li>
                    <li><a href="javascript:;">{{ __('Location Management') }}</a></li>
                </ul>
            </div>
        </div>
    </div>
    
    <div class="product-area">
        <div class="row">
            <div class="col-lg-12">
                
                <!-- Navigation Tabs -->
                <ul class="nav nav-tabs mb-4" id="locationTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="districts-tab" data-toggle="tab" href="#districts-content" role="tab" aria-controls="districts-content" aria-selected="true">
                            <i class="fas fa-map-marker-alt mr-1"></i> {{ __('City Corporations') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="thanas-tab" data-toggle="tab" href="#thanas-content" role="tab" aria-controls="thanas-content" aria-selected="false">
                            <i class="fas fa-map mr-1"></i> {{ __('Thanas') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="wards-tab" data-toggle="tab" href="#wards-content" role="tab" aria-controls="wards-content" aria-selected="false">
                            <i class="fas fa-city mr-1"></i> {{ __('Wards') }}
                        </a>
                    </li>
                </ul>
                
                <!-- Global messages -->
                <div class="alert alert-success" style="display: none;" id="globalSuccessAlert">
                    <p class="mb-0"></p>
                </div>
                <div class="alert alert-danger" style="display: none;" id="globalErrorAlert">
                    <ul class="mb-0"></ul>
                </div>
                
                <div class="tab-content" id="locationTabsContent">
                    
                    <!-- TAB 1: CITY CORPORATIONS -->
                    <div class="tab-pane fade show active" id="districts-content" role="tabpanel" aria-labelledby="districts-tab">
                        <div class="text-right mb-3">
                            <button class="btn btn-primary btn-sm" type="button" data-toggle="collapse" data-target="#addDistrictCollapse" aria-expanded="false" aria-controls="addDistrictCollapse">
                                <i class="fas fa-plus mr-1"></i> {{ __('Add City Corporation') }}
                            </button>
                        </div>
                        
                        <div class="collapse mb-4" id="addDistrictCollapse">
                            <div class="card card-body" style="border: 1px solid #dee2e6; border-radius: 6px;">
                                <h5>{{ __('Add New City Corporation') }}</h5>
                                <form class="ajax-location-add-form" action="{{ route('admin.districts.store') }}" method="POST">
                                    {{ csrf_field() }}
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>{{ __('Division') }} *</label>
                                                <select name="division_id" class="form-control" required style="height: calc(2.25rem + 2px);">
                                                    <option value="">{{ __('Select Division') }}</option>
                                                    @foreach ($divisions as $division)
                                                        <option value="{{ $division->id }}">{{ $division->name }} / {{ $division->bn_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>{{ __('English Name') }} *</label>
                                                <input type="text" name="name" class="form-control" placeholder="{{ __('e.g. Dhaka North') }}" required autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>{{ __('Bengali Name') }} *</label>
                                                <input type="text" name="bn_name" class="form-control" placeholder="{{ __('e.g. ঢাকা উত্তর') }}" required autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>{{ __('URL') }}</label>
                                                <input type="text" name="url" class="form-control" placeholder="{{ __('e.g. www.dncc.gov.bd') }}" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-12 text-right">
                                            <button type="button" class="btn btn-secondary btn-sm" data-toggle="collapse" data-target="#addDistrictCollapse">{{ __('Cancel') }}</button>
                                            <button type="submit" class="btn btn-primary btn-sm">{{ __('Save') }}</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        
                        <div class="mr-table allproduct">
                            <div class="table-responsiv">
                                <table id="districtTable" class="table table-hover dt-responsive" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>{{ __('English Name') }}</th>
                                            <th>{{ __('Bengali Name') }}</th>
                                            <th>{{ __('Division') }}</th>
                                            <th>{{ __('URL') }}</th>
                                            <th>{{ __('Actions') }}</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <!-- TAB 2: THANAS -->
                    <div class="tab-pane fade" id="thanas-content" role="tabpanel" aria-labelledby="thanas-tab">
                        <div class="text-right mb-3">
                            <button class="btn btn-primary btn-sm" type="button" data-toggle="collapse" data-target="#addThanaCollapse" aria-expanded="false" aria-controls="addThanaCollapse">
                                <i class="fas fa-plus mr-1"></i> {{ __('Add Thana') }}
                            </button>
                        </div>
                        
                        <div class="collapse mb-4" id="addThanaCollapse">
                            <div class="card card-body" style="border: 1px solid #dee2e6; border-radius: 6px;">
                                <h5>{{ __('Add New Thana') }}</h5>
                                <form class="ajax-location-add-form" action="{{ route('admin.thanas.store') }}" method="POST">
                                    {{ csrf_field() }}
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>{{ __('City Corporation') }} *</label>
                                                <select name="district_id" class="form-control" required style="height: calc(2.25rem + 2px);">
                                                    <option value="">{{ __('Select City Corporation') }}</option>
                                                    @foreach ($districts as $district)
                                                        <option value="{{ $district->id }}">
                                                            {{ $district->name }} / {{ $district->bn_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>{{ __('English Name') }} *</label>
                                                <input type="text" name="name" class="form-control" placeholder="{{ __('e.g. Mirpur') }}" required autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>{{ __('Bengali Name') }} *</label>
                                                <input type="text" name="bn_name" class="form-control" placeholder="{{ __('e.g. মিরপুর') }}" required autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>{{ __('URL') }}</label>
                                                <input type="text" name="url" class="form-control" placeholder="{{ __('e.g. www.mirpur.gov.bd') }}" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-12 text-right">
                                            <button type="button" class="btn btn-secondary btn-sm" data-toggle="collapse" data-target="#addThanaCollapse">{{ __('Cancel') }}</button>
                                            <button type="submit" class="btn btn-primary btn-sm">{{ __('Save') }}</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        
                        <div class="mr-table allproduct">
                            <div class="table-responsiv">
                                <table id="thanaTable" class="table table-hover dt-responsive" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>{{ __('English Name') }}</th>
                                            <th>{{ __('Bengali Name') }}</th>
                                            <th>{{ __('City Corporation') }}</th>
                                            <th>{{ __('URL') }}</th>
                                            <th>{{ __('Actions') }}</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <!-- TAB 3: WARDS -->
                    <div class="tab-pane fade" id="wards-content" role="tabpanel" aria-labelledby="wards-tab">
                        <div class="text-right mb-3">
                            <button class="btn btn-primary btn-sm" type="button" data-toggle="collapse" data-target="#addWardCollapse" aria-expanded="false" aria-controls="addWardCollapse">
                                <i class="fas fa-plus mr-1"></i> {{ __('Add Ward') }}
                            </button>
                        </div>
                        
                        <div class="collapse mb-4" id="addWardCollapse">
                            <div class="card card-body" style="border: 1px solid #dee2e6; border-radius: 6px;">
                                <h5>{{ __('Add New Ward') }}</h5>
                                <form class="ajax-location-add-form" action="{{ route('admin.wards.store') }}" method="POST">
                                    {{ csrf_field() }}
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>{{ __('Thana') }} *</label>
                                                <select name="upazilla_id" class="form-control" required style="height: calc(2.25rem + 2px);">
                                                    <option value="">{{ __('Select Thana') }}</option>
                                                    @foreach ($thanas as $thana)
                                                        @php
                                                            $dName = $districts->firstWhere('id', $thana->district_id);
                                                            $districtLabel = $dName ? $dName->name : '';
                                                        @endphp
                                                        <option value="{{ $thana->id }}">
                                                            {{ $thana->name }} / {{ $thana->bn_name }}
                                                            @if ($districtLabel)
                                                                [{{ $districtLabel }}]
                                                            @endif
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>{{ __('English Name') }} *</label>
                                                <input type="text" name="name" class="form-control" placeholder="{{ __('e.g. Ward 1') }}" required autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>{{ __('Bengali Name') }} *</label>
                                                <input type="text" name="bn_name" class="form-control" placeholder="{{ __('e.g. ওয়ার্ড ১') }}" required autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>{{ __('URL') }}</label>
                                                <input type="text" name="url" class="form-control" placeholder="{{ __('e.g. www.ward1.gov.bd') }}" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-12 text-right">
                                            <button type="button" class="btn btn-secondary btn-sm" data-toggle="collapse" data-target="#addWardCollapse">{{ __('Cancel') }}</button>
                                            <button type="submit" class="btn btn-primary btn-sm">{{ __('Save') }}</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        
                        <div class="mr-table allproduct">
                            <div class="table-responsiv">
                                <table id="wardTable" class="table table-hover dt-responsive" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>{{ __('English Name') }}</th>
                                            <th>{{ __('Bengali Name') }}</th>
                                            <th>{{ __('Thana') }}</th>
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
    </div>
</div>

{{-- DELETE CONFIRM MODAL --}}
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
                    {{ __('You are about to delete this item. This action cannot be undone.') }}
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

<div class="gocover" style="background: url({{ asset('assets/images/'.$gs->admin_loader) }}) no-repeat scroll center center rgba(45, 45, 45, 0.5); display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 99999;"></div>

@endsection

@section('scripts')
<script type="text/javascript">
    
    // Inject location datasets
    const allDivisions = @json($divisions);
    const allDistricts = @json($districts);
    const allThanas = @json($thanas);
    const csrfTokenHtml = '{{ csrf_field() }}';

    // 1. Initialize Districts Datatable
    var districtTable = $('#districtTable').DataTable({
        ordering: false,
        processing: true,
        serverSide: true,
        ajax: '{{ route('admin.districts.datatables') }}',
        columns: [
            { data: 'name', name: 'name' },
            { data: 'bn_name', name: 'bn_name' },
            { data: 'division_id', name: 'division_id' },
            { data: 'url', name: 'url' },
            { data: 'action', searchable: false, orderable: false }
        ],
        language: {
            processing: '<img src="{{ asset('assets/images/'.$gs->admin_loader) }}">',
        }
    });

    // 2. Initialize Thanas Datatable
    var thanaTable = $('#thanaTable').DataTable({
        ordering: false,
        processing: true,
        serverSide: true,
        ajax: '{{ route('admin.thanas.datatables') }}',
        columns: [
            { data: 'name', name: 'name' },
            { data: 'bn_name', name: 'bn_name' },
            { data: 'district_id', name: 'district_id' },
            { data: 'url', name: 'url' },
            { data: 'action', searchable: false, orderable: false }
        ],
        language: {
            processing: '<img src="{{ asset('assets/images/'.$gs->admin_loader) }}">',
        }
    });

    // 3. Initialize Wards Datatable
    var wardTable = $('#wardTable').DataTable({
        ordering: false,
        processing: true,
        serverSide: true,
        ajax: '{{ route('admin.wards.datatables') }}',
        columns: [
            { data: 'name', name: 'name' },
            { data: 'bn_name', name: 'bn_name' },
            { data: 'upazilla_id', name: 'upazilla_id' },
            { data: 'url', name: 'url' },
            { data: 'action', searchable: false, orderable: false }
        ],
        language: {
            processing: '<img src="{{ asset('assets/images/'.$gs->admin_loader) }}">',
        }
    });

    /* =========================================================================
     * IN-LINE TOGGLE EDIT SECTIONS (Child Rows)
     * ========================================================================= */

    // Districts edit toggle HTML generator
    function formatDistrictEditForm(d) {
        let divisionsHtml = '';
        allDivisions.forEach(function(division) {
            let selected = (division.id == d.division_id) ? 'selected' : '';
            divisionsHtml += `<option value="${division.id}" ${selected}>${division.name} / ${division.bn_name}</option>`;
        });

        return `
            <div class="card card-body child-edit-form p-3 mt-2 mb-2">
                <h5>{{ __('Edit City Corporation') }}</h5>
                <form class="ajax-location-edit-form-submit" action="${mainurl}admin/districts/update/${d.id}" method="POST" data-table="district">
                    ${csrfTokenHtml}
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group mb-0">
                                <label class="font-weight-bold">{{ __('Division') }} *</label>
                                <select name="division_id" class="form-control" required style="height: calc(2.25rem + 2px);">
                                    ${divisionsHtml}
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mb-0">
                                <label class="font-weight-bold">{{ __('English Name') }} *</label>
                                <input type="text" name="name" class="form-control" value="${d.name}" required autocomplete="off">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mb-0">
                                <label class="font-weight-bold">{{ __('Bengali Name') }} *</label>
                                <input type="text" name="bn_name" class="form-control" value="${d.bn_name}" required autocomplete="off">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mb-0">
                                <label class="font-weight-bold">{{ __('URL') }}</label>
                                <input type="text" name="url" class="form-control" value="${d.url || ''}" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12 text-right">
                            <button type="button" class="btn btn-secondary btn-sm cancel-edit-btn">{{ __('Cancel') }}</button>
                            <button type="submit" class="btn btn-primary btn-sm">{{ __('Save Changes') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        `;
    }

    // Thana edit toggle HTML generator
    function formatThanaEditForm(d) {
        let districtsHtml = '';
        allDistricts.forEach(function(district) {
            let selected = (district.id == d.district_id) ? 'selected' : '';
            districtsHtml += `<option value="${district.id}" ${selected}>${district.name} / ${district.bn_name}</option>`;
        });

        return `
            <div class="card card-body child-edit-form p-3 mt-2 mb-2">
                <h5>{{ __('Edit Thana') }}</h5>
                <form class="ajax-location-edit-form-submit" action="${mainurl}admin/thanas/update/${d.id}" method="POST" data-table="thana">
                    ${csrfTokenHtml}
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group mb-0">
                                <label class="font-weight-bold">{{ __('City Corporation') }} *</label>
                                <select name="district_id" class="form-control" required style="height: calc(2.25rem + 2px);">
                                    ${districtsHtml}
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mb-0">
                                <label class="font-weight-bold">{{ __('English Name') }} *</label>
                                <input type="text" name="name" class="form-control" value="${d.name}" required autocomplete="off">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mb-0">
                                <label class="font-weight-bold">{{ __('Bengali Name') }} *</label>
                                <input type="text" name="bn_name" class="form-control" value="${d.bn_name}" required autocomplete="off">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mb-0">
                                <label class="font-weight-bold">{{ __('URL') }}</label>
                                <input type="text" name="url" class="form-control" value="${d.url || ''}" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12 text-right">
                            <button type="button" class="btn btn-secondary btn-sm cancel-edit-btn">{{ __('Cancel') }}</button>
                            <button type="submit" class="btn btn-primary btn-sm">{{ __('Save Changes') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        `;
    }

    // Ward edit toggle HTML generator
    function formatWardEditForm(d) {
        let thanasHtml = '';
        allThanas.forEach(function(thana) {
            let selected = (thana.id == d.upazilla_id) ? 'selected' : '';
            let district = allDistricts.find(d => d.id == thana.district_id);
            let districtLabel = district ? district.name : '';
            let label = thana.name + (districtLabel ? ' [' + districtLabel + ']' : '');
            
            thanasHtml += `<option value="${thana.id}" ${selected}>${label}</option>`;
        });

        return `
            <div class="card card-body child-edit-form p-3 mt-2 mb-2">
                <h5>{{ __('Edit Ward') }}</h5>
                <form class="ajax-location-edit-form-submit" action="${mainurl}admin/wards/update/${d.id}" method="POST" data-table="ward">
                    ${csrfTokenHtml}
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group mb-0">
                                <label class="font-weight-bold">{{ __('Thana') }} *</label>
                                <select name="upazilla_id" class="form-control" required style="height: calc(2.25rem + 2px);">
                                    ${thanasHtml}
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mb-0">
                                <label class="font-weight-bold">{{ __('English Name') }} *</label>
                                <input type="text" name="name" class="form-control" value="${d.name}" required autocomplete="off">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mb-0">
                                <label class="font-weight-bold">{{ __('Bengali Name') }} *</label>
                                <input type="text" name="bn_name" class="form-control" value="${d.bn_name}" required autocomplete="off">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mb-0">
                                <label class="font-weight-bold">{{ __('URL') }}</label>
                                <input type="text" name="url" class="form-control" value="${d.url || ''}" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12 text-right">
                            <button type="button" class="btn btn-secondary btn-sm cancel-edit-btn">{{ __('Cancel') }}</button>
                            <button type="submit" class="btn btn-primary btn-sm">{{ __('Save Changes') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        `;
    }

    // Toggle Child Row for Districts Edit
    $(document).on('click', '.edit-district-btn', function() {
        var tr = $(this).closest('tr');
        var row = districtTable.row(tr);

        if (row.child.isShown()) {
            row.child.hide();
            tr.removeClass('shown');
        } else {
            var data = {
                id: $(this).data('id'),
                division_id: $(this).data('division_id'),
                name: $(this).data('name'),
                bn_name: $(this).data('bn_name'),
                url: $(this).data('url')
            };
            row.child(formatDistrictEditForm(data)).show();
            tr.addClass('shown');
        }
    });

    // Toggle Child Row for Thanas Edit
    $(document).on('click', '.edit-thana-btn', function() {
        var tr = $(this).closest('tr');
        var row = thanaTable.row(tr);

        if (row.child.isShown()) {
            row.child.hide();
            tr.removeClass('shown');
        } else {
            var data = {
                id: $(this).data('id'),
                district_id: $(this).data('district_id'),
                name: $(this).data('name'),
                bn_name: $(this).data('bn_name'),
                url: $(this).data('url')
            };
            row.child(formatThanaEditForm(data)).show();
            tr.addClass('shown');
        }
    });

    // Toggle Child Row for Wards Edit
    $(document).on('click', '.edit-ward-btn', function() {
        var tr = $(this).closest('tr');
        var row = wardTable.row(tr);

        if (row.child.isShown()) {
            row.child.hide();
            tr.removeClass('shown');
        } else {
            var data = {
                id: $(this).data('id'),
                upazilla_id: $(this).data('upazilla_id'),
                name: $(this).data('name'),
                bn_name: $(this).data('bn_name'),
                url: $(this).data('url')
            };
            row.child(formatWardEditForm(data)).show();
            tr.addClass('shown');
        }
    });

    // Handle child-row cancel button click
    $(document).on('click', '.cancel-edit-btn', function() {
        var tr = $(this).closest('tr').prev();
        var activeTab = $('.nav-tabs .nav-link.active').attr('id');
        var currentTable = districtTable;
        
        if (activeTab === 'thanas-tab') {
            currentTable = thanaTable;
        } else if (activeTab === 'wards-tab') {
            currentTable = wardTable;
        }
        
        var row = currentTable.row(tr);
        row.child.hide();
        tr.removeClass('shown');
    });

    /* =========================================================================
     * AJAX SUBMISSIONS
     * ========================================================================= */

    function displayErrors(errors) {
        $('#globalSuccessAlert').hide();
        $('#globalErrorAlert').show();
        $('#globalErrorAlert ul').html('');
        for (var error in errors) {
            $('#globalErrorAlert ul').append('<li>' + errors[error] + '</li>');
        }
        $('html, body').animate({ scrollTop: $('#locationTabs').offset().top - 20 }, 'slow');
    }

    function displaySuccess(message) {
        $('#globalErrorAlert').hide();
        $('#globalSuccessAlert').show();
        $('#globalSuccessAlert p').html(message);
        setTimeout(function() {
            $('#globalSuccessAlert').fadeOut();
        }, 5000);
        $('html, body').animate({ scrollTop: $('#locationTabs').offset().top - 20 }, 'slow');
    }

    // Submit Add forms
    $(document).on('submit', '.ajax-location-add-form', function(e) {
        e.preventDefault();
        $('.gocover').show();
        var form = $(this);
        form.find('button[type="submit"]').prop('disabled', true);

        $.ajax({
            method: "POST",
            url: form.prop('action'),
            data: new FormData(this),
            dataType: 'JSON',
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {
                $('.gocover').hide();
                form.find('button[type="submit"]').prop('disabled', false);

                if (data.errors) {
                    displayErrors(data.errors);
                } else {
                    var activeTab = $('.nav-tabs .nav-link.active').attr('id');
                    if (activeTab === 'districts-tab') {
                        districtTable.ajax.reload();
                        $('#addDistrictCollapse').collapse('hide');
                    } else if (activeTab === 'thanas-tab') {
                        thanaTable.ajax.reload();
                        $('#addThanaCollapse').collapse('hide');
                    } else if (activeTab === 'wards-tab') {
                        wardTable.ajax.reload();
                        $('#addWardCollapse').collapse('hide');
                    }
                    
                    form[0].reset();
                    displaySuccess(data);
                }
            }
        });
    });

    // Submit Edit forms (child rows)
    $(document).on('submit', '.ajax-location-edit-form-submit', function(e) {
        e.preventDefault();
        $('.gocover').show();
        var form = $(this);
        var tableType = form.data('table');
        form.find('button[type="submit"]').prop('disabled', true);

        $.ajax({
            method: "POST",
            url: form.prop('action'),
            data: new FormData(this),
            dataType: 'JSON',
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {
                $('.gocover').hide();
                form.find('button[type="submit"]').prop('disabled', false);

                if (data.errors) {
                    displayErrors(data.errors);
                } else {
                    var parentTr = form.closest('tr').prev();
                    if (tableType === 'district') {
                        districtTable.ajax.reload();
                        districtTable.row(parentTr).child.hide();
                    } else if (tableType === 'thana') {
                        thanaTable.ajax.reload();
                        thanaTable.row(parentTr).child.hide();
                    } else if (tableType === 'ward') {
                        wardTable.ajax.reload();
                        wardTable.row(parentTr).child.hide();
                    }
                    parentTr.removeClass('shown');
                    displaySuccess(data);
                }
            }
        });
    });

</script>
@endsection

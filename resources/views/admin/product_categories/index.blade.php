@extends('layouts.admin')

@section('content')
<input type="hidden" id="headerdata" value="{{ __('PRODUCT CATEGORY') }}">

<div class="content-area">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="heading">{{ __('Product Categories') }}</h4>
                <ul class="links">
                    <li><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                    <li><a href="javascript:;">{{ __('Ecommerce') }}</a></li>
                    <li><a href="{{ route('admin.productCategories.index') }}">{{ __('Product Categories') }}</a></li>
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
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Slug') }}</th>
                                    <th>{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($product_categories as $category)
                                <tr>
                                    <td>{{ $category->name }}</td>
                                    <td>{{ $category->slug }}</td>
                                    <td>
                                        <!-- EDIT BUTTON -->
                                        <a href="javascript:;" 
                                           class="btn btn-sm btn-primary edit"
                                           data-id="{{ $category->id }}"
                                           data-name="{{ $category->name }}"
                                           data-slug="{{ $category->slug }}"
                                           data-href="{{ route('admin.productCategories.update', $category->id) }}"
                                           data-toggle="modal"
                                           data-target="#editModal">
                                            {{ __('Edit') }}
                                        </a>

                                        <!-- DELETE BUTTON -->
                                        <a href="javascript:;" 
                                           class="btn btn-sm btn-danger"
                                           data-href="{{ route('admin.productCategories.delete', $category->id) }}"
                                           data-toggle="modal"
                                           data-target="#confirm-delete">
                                            {{ __('Delete') }}
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- ADD BUTTON -->
                    <div class="btn-area">
                        <a class="add-btn" data-toggle="modal" data-target="#addModal">
                            <i class="fas fa-plus"></i>{{ __('Add New Category') }}
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- ADD MODAL -->
<div class="modal fade-scale" id="addModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Add Category') }}</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <form method="POST" action="{{ route('admin.productCategories.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>{{ __('Name') }}</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary">{{ __('Save') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- EDIT MODAL -->
<div class="modal fade-scale" id="editModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Edit Category') }}</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <form method="POST" id="editForm">
                @csrf
                @method('PUT')

                <div class="modal-body">
                    <div class="form-group">
                        <label>{{ __('Name') }}</label>
                        <input type="text" id="edit_name" name="name" class="form-control" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary">{{ __('Update') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- DELETE MODAL -->
<div class="modal fade-scale" id="confirm-delete" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header text-center d-block">
                <h4>{{ __('Confirm Delete') }}</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body text-center">
                <p>{{ __('You are about to delete this category.') }}</p>
                <p>{{ __('Do you want to proceed?') }}</p>
            </div>

            <div class="modal-footer justify-content-center">
                <button class="btn btn-secondary" data-dismiss="modal">{{ __('Cancel') }}</button>
                <a class="btn btn-danger btn-ok">{{ __('Delete') }}</a>
            </div>

        </div>
    </div>
</div>
@endsection


@section('scripts')
<script>
$(document).ready(function() {

    // DELETE Modal
    $('#confirm-delete').on('show.bs.modal', function(e) {
        $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
    });

    // EDIT Button
    $(document).on('click', '.edit', function() {
        $('#edit_name').val($(this).data('name'));
        $('#editForm').attr('action', $(this).data('href'));
    });

});
</script>
@endsection

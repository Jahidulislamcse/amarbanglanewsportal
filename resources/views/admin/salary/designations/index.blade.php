@extends('layouts.admin')

@section('content')
<div class="content-area">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="heading">{{ __('Designations (Admin Roles)') }}</h4>
                <ul class="links">
                    <li>
                        <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                    </li>
                    <li><a href="javascript:;">{{ __('Staff Salary') }}</a></li>
                    <li>
                        <a href="{{ route('admin.designations.index') }}">{{ __('Designations') }}</a>
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
                    @include('includes.admin.form-error')
                    @include('includes.admin.flash-message')
                    
                    <div class="row mb-3 p-3">
                        <div class="col-sm-12 text-right">
                            <button class="btn btn-primary" data-toggle="modal" data-target="#addModal">
                                <i class="fas fa-plus"></i> {{ __('Add New Designation') }}
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('System Code') }}</th>
                                    <th>{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($designations as $designation)
                                    <tr>
                                        <td><strong>{{ $designation->name }}</strong></td>
                                        <td><code>{{ $designation->code }}</code></td>
                                        <td>
                                            <div class="action-list">
                                                <button class="btn btn-warning edit-btn" 
                                                    data-id="{{ $designation->id }}" 
                                                    data-name="{{ $designation->name }}" 
                                                    data-toggle="modal" 
                                                    data-target="#editModal">
                                                    <i class="fas fa-edit"></i> Edit
                                                </button>
                                                <a href="{{ route('admin.designations.delete', $designation->id) }}" 
                                                    onclick="return confirm('Are you sure you want to delete this designation (role)? This will remove it from the system.');" 
                                                    class="btn btn-danger">
                                                    <i class="fas fa-trash-alt"></i> Delete
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">No designations found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        
                        <div class="p-3">
                            {{ $designations->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ADD MODAL --}}
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="{{ route('admin.designations.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">{{ __('Add Designation (Role)') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">{{ __('Designation Name') }} *</label>
                        <input type="text" class="form-control" name="name" id="name" required placeholder="e.g. Editor, Accounts Officer">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Designation</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- EDIT MODAL --}}
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form id="editForm" method="POST" action="">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">{{ __('Edit Designation (Role)') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit_name">{{ __('Designation Name') }} *</label>
                        <input type="text" class="form-control" name="name" id="edit_name" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Designation</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('.edit-btn').on('click', function() {
            var id = $(this).data('id');
            var name = $(this).data('name');
            var url = "{{ route('admin.designations.update', ':id') }}".replace(':id', id);
            
            $('#editForm').attr('action', url);
            $('#edit_name').val(name);
        });
    });
</script>
@endsection

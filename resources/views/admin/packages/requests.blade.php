@extends('layouts.admin')

@section('content')
<div class="content-area">

    <div class="mr-breadcrumb">
        <h4 class="heading">Upgrade Requests</h4>
    </div>

    @include('includes.admin.form-success')
    @include('includes.admin.flash-message')

    {{-- Toggle Buttons --}}
    <div class="mb-3">
        <div class="d-inline-block me-2">
            <button class="btn btn-warning px-3" type="button"
                data-bs-toggle="collapse" data-bs-target="#pendingRequests">
                Pending ({{ $pendingRequests->count() }})
            </button>
        </div>

        <div class="d-inline-block">
            <button class="btn btn-success px-3" type="button"
                data-bs-toggle="collapse" data-bs-target="#approvedRequests">
                Approved ({{ $approvedRequests->count() }})
            </button>
        </div>
        <div class="d-inline-block ms-2">
            <button class="btn btn-danger px-3" type="button"
                data-bs-toggle="collapse" data-bs-target="#rejectedRequests">
                Rejected ({{ $rejectedRequests->count() }})
            </button>
        </div>
    </div>

    <div class="accordion" id="upgradeAccordion">

        {{-- Pending --}}
        <div class="accordion-item">
            <div id="pendingRequests" class="accordion-collapse collapse show" data-bs-parent="#upgradeAccordion">
                <div class="accordion-body p-0">

                    <table class="table table-hover mb-0">
                        <thead>
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
                            @foreach($pendingRequests as $req)
                                <tr>
                                    <td> {{ $req->user->name ?? 'Deleted User' }}</td>
                                    <td>{{ ucfirst($req->package) }}</td>
                                    <td>৳{{ $req->amount }}</td>
                                    <td>{{ $req->phone_number }}</td>
                                    <td>{{ $req->operator }}</td>
                                    <td><span class="badge bg-warning">Pending</span></td>
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
                            @endforeach
                        </tbody>

                    </table>

                </div>
            </div>
        </div>

        {{-- Approved --}}
        <div class="accordion-item">
            <div id="approvedRequests" class="accordion-collapse collapse" data-bs-parent="#upgradeAccordion">
                <div class="accordion-body p-0">

                    <table class="table table-hover mb-0">
                        <thead>
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
                            @foreach($approvedRequests as $req)
                                <tr>
                                    <td>{{ $req->user->name ?? 'Deleted User' }}</td>
                                    <td>{{ ucfirst($req->package) }}</td>
                                    <td>৳{{ $req->amount }}</td>
                                    <td>{{ $req->phone_number }}</td>
                                    <td>{{ $req->operator }}</td>
                                    <td><span class="badge bg-success">Approved</span></td>
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
                            @endforeach
                        </tbody>

                    </table>

                </div>
            </div>
        </div>
        
        <div class="accordion-item">
            <div id="rejectedRequests" class="accordion-collapse collapse" data-bs-parent="#upgradeAccordion">
                <div class="accordion-body p-0">
        
                    <table class="table table-hover mb-0">
                        <thead>
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
                            @foreach($rejectedRequests as $req)
                                <tr>
                                    <td>{{ $req->user->name ?? 'Deleted User' }}</td>
                                    <td>{{ ucfirst($req->package) }}</td>
                                    <td>৳{{ $req->amount }}</td>
                                    <td>{{ $req->phone_number }}</td>
                                    <td>{{ $req->operator }}</td>
                                    <td><span class="badge bg-danger">Rejected</span></td>
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
                            @endforeach
                        </tbody>
        
                    </table>
        
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
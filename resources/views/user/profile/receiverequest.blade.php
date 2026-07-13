@extends('layouts.user')

@section('content')
<style>
    label {
        font-size: 14px;
        color: #555;
        margin-right: 8px;
    }

    .table th, .table td {
        vertical-align: middle;
        font-size: 14px;
    }

    .badge {
        padding: 5px 10px;
        border-radius: 6px;
        font-size: 12px;
    }

    .badge-success {
        background: #28a745;
        color: #fff;
    }

    .badge-warning {
        background: #ffc107;
        color: #000;
    }

    .badge-danger {
        background: #dc3545;
        color: #fff;
    }
</style>

<input type="hidden" id="headerdata" value="PAYMENT">

<div class="content-area">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="heading">Monthly Payment Requests</h4>
                <ul class="links">
                    <li>
                        <a href="{{ route('user.dashboard') }}">Dashboard</a>
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

                    <div class="table-responsive">

                        <div class="row mb-3">
                            <div class="col-md-12 text-right">
                                <a href="{{ route('monthly-fee.pay') }}" class="btn btn-success">
                                    <i class="fas fa-credit-card"></i>
                                    Pay Monthly Fee
                                </a>
                            </div>
                        </div>

                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Reporter Name</th>
                                    <th>Reporter Email</th>
                                    <th>Reporter Phone</th>
                                    <th>Payment Date</th>
                                    <th>Amount</th>
                                    <th>TXN ID</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($payments as $payment)
                                    <tr>
                                        <td>{{ $payment->user->name ?? '-' }}</td>
                                        <td>{{ $payment->user->email ?? '-' }}</td>
                                        <td>{{ $payment->user->phone ?? '-' }}</td>

                                        <td>
                                            {{ $payment->paid_at ? $payment->paid_at->format('Y-m-d H:i') : '-' }}
                                        </td>

                                        <td>{{ $payment->amount }}</td>

                                        <td>{{ $payment->transaction_id }}</td>

                                        <td>
                                            @if($payment->status == 'paid')
                                                <span class="badge badge-success">Paid</span>
                                            @elseif($payment->status == 'pending')
                                                <span class="badge badge-warning">Pending</span>
                                            @else
                                                <span class="badge badge-danger">{{ ucfirst($payment->status) }}</span>
                                            @endif
                                        </td>

                                        <td>
                                            @if($payment->status == 'pending')
                                                <a href="{{ route('monthly-fee.pay') }}" class="btn btn-sm btn-primary">
                                                    Pay Now
                                                </a>
                                            @else
                                                <span class="text-muted">Completed</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">
                                            No payment requests found
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
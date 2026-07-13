@extends('layouts.admin')

@section('content')

<style>
    .card-box {
        background: #fff;
        padding: 18px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        margin-bottom: 20px;
    }

    .stat-title {
        font-size: 13px;
        color: #666;
    }

    .stat-value {
        font-size: 20px;
        font-weight: 600;
    }

    .filter-bar {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 10px;
        margin-bottom: 20px;
    }

    .table th {
        background: #f1f1f1;
        font-size: 13px;
    }

    .table td {
        font-size: 13px;
        vertical-align: middle;
    }

    .badge-success { background: #28a745; color: #fff; }
    .badge-warning { background: #ffc107; color: #000; }
    .badge-danger { background: #dc3545; color: #fff; }

    .table-scroll {
        max-height: 500px;
        overflow-y: auto;
        overflow-x: auto;
        border: 1px solid #eee;
        border-radius: 8px;
    }

    .table-scroll thead th {
        position: sticky;
        top: 0;
        background: #f1f1f1;
        z-index: 2;
    }
</style>

<div class="content-area">

    <div class="mr-breadcrumb">
        <h4 class="heading">Package Upgrade Payments</h4>
    </div>

    {{-- STATS --}}
    <div class="row">
        <div class="col-md-4">
            <div class="card-box">
                <div class="stat-title">This Month Total</div>
                <div class="stat-value">৳ {{ number_format($thisMonthTotal, 2) }}</div>
            </div>
        </div>
    </div>

    {{-- FILTER --}}
    <form method="GET" class="filter-bar">
        <div class="row">

            <div class="col-md-3 mb-2">
                <select name="user_id" class="form-control">
                    <option value="">All Users</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2 mb-2">
                <select name="package" class="form-control">
                    <option value="">Package</option>
                    <option value="executive" {{ request('package') == 'executive' ? 'selected' : '' }}>Executive</option>
                    <option value="vip" {{ request('package') == 'vip' ? 'selected' : '' }}>VIP</option>
                </select>
            </div>

            <div class="col-md-2 mb-2">
                <select name="month" class="form-control">
                    <option value="">Month</option>
                    @for($m=1;$m<=12;$m++)
                        <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                            {{ date('F', mktime(0,0,0,$m,1)) }}
                        </option>
                    @endfor
                </select>
            </div>

            <div class="col-md-2 mb-2">
                <select name="status" class="form-control">
                    <option value="">All Status</option>
                    @foreach(['pending', 'paid', 'failed', 'cancelled'] as $status)
                        <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                            {{ ucfirst($status) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2 mb-2">
                <input type="number" name="year" class="form-control"
                       placeholder="Year" value="{{ request('year') }}">
            </div>

            <div class="col-md-3 mb-2">
                <input type="text" name="search" class="form-control"
                       placeholder="TXN / Name / Email"
                       value="{{ request('search') }}">
            </div>

        </div>

        <div class="row mt-2">
            <div class="col-md-12 d-flex gap-2">

                <button class="btn btn-primary">
                    Filter
                </button>

                <a href="{{ route('admin.administator.packageUpgradePayments') }}"
                   class="btn btn-secondary ml-2">
                    Reset
                </a>

            </div>
        </div>
    </form>

    {{-- TABLE --}}
    <div class="card-box">
        <div class="table-scroll">

            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Email</th>
                        <th>Package</th>
                        <th>Paid Date</th>
                        <th>Amount</th>
                        <th>TXN ID</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($payments as $payment)
                        <tr>
                            <td>{{ $payment->user->name ?? '-' }}</td>
                            <td>{{ $payment->user->email ?? '-' }}</td>
                            <td>
                                <span class="badge badge-success">
                                    {{ strtoupper($payment->package) }}
                                </span>
                            </td>

                            <td>
                                {{ $payment->paid_at ? $payment->paid_at->format('d M Y H:i') : '-' }}
                            </td>

                            <td>৳ {{ $payment->amount }}</td>
                            <td>{{ $payment->transaction_id }}</td>

                            <td>
                                @if($payment->status == 'paid')
                                    <span class="badge badge-success">Paid</span>
                                @elseif($payment->status == 'pending')
                                    <span class="badge badge-warning">Pending</span>
                                @else
                                    <span class="badge badge-danger">{{ $payment->status }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No payments found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>

        <div class="mt-3">
            {{ $payments->appends(request()->query())->links() }}
        </div>
    </div>

</div>

@endsection

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
    
    .badge {
        padding: 5px 10px;
        border-radius: 6px;
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
        <h4 class="heading">Book Purchase Payments</h4>
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

    @include('includes.admin.form-success')
    @include('includes.admin.flash-message')

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

            <div class="col-md-3 mb-2">
                <select name="book_id" class="form-control">
                    <option value="">All Books</option>
                    @foreach($books as $book)
                        <option value="{{ $book->id }}" {{ request('book_id') == $book->id ? 'selected' : '' }}>
                            {{ $book->title }} (৳{{ $book->price }})
                        </option>
                    @endforeach
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
                    @foreach(['pending', 'approved', 'rejected'] as $status)
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
    
            <div class="col-md-4 mb-2">
                <input type="text" name="search" class="form-control"
                       placeholder="TXN / Phone / Name / Email / Book"
                       value="{{ request('search') }}">
            </div>
    
            <div class="col-md-3 mb-2 d-flex gap-2">
                <button class="btn btn-primary px-3">
                    Filter
                </button>
                <a href="{{ route('admin.administator.bookPurchasePayments') }}"
                   class="btn btn-secondary px-3 ml-2">
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
                        <th>Phone</th>
                        <th>Book Title</th>
                        <th>Book Price</th>
                        <th>Amount Paid</th>
                        <th>Operator</th>
                        <th>TXN ID</th>
                        <th>Paid/Created Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
        
                <tbody>
                    @forelse($payments as $payment)
                        <tr>
                            <td>{{ $payment->user->name ?? '-' }}</td>
                            <td>{{ $payment->user->email ?? '-' }}</td>
                            <td>{{ $payment->phone_number ?: ($payment->user->phone ?? '-') }}</td>
                            <td>{{ $payment->book->title ?? '-' }}</td>
                            <td>৳ {{ number_format($payment->book->price ?? 0, 2) }}</td>
                            <td>৳ {{ number_format($payment->amount > 0 ? $payment->amount : ($payment->book->price ?? 0), 2) }}</td>
                            <td>{{ $payment->operator ?? '-' }}</td>
                            <td>{{ $payment->transaction_id ?: '-' }}</td>
                            <td>
                                {{ $payment->paid_at ? $payment->paid_at->format('d M Y H:i') : ($payment->created_at ? $payment->created_at->format('d M Y H:i') : '-') }}
                            </td>
                            <td>
                                @if($payment->status == 'approved')
                                    <span class="badge badge-success">Approved</span>
                                @elseif($payment->status == 'pending')
                                    <span class="badge badge-warning">Pending</span>
                                @else
                                    <span class="badge badge-danger">{{ ucfirst($payment->status) }}</span>
                                @endif
                            </td>
                            <td>
                                @if($payment->status == 'pending')
                                    <form action="{{ route('admin.books.purchase.approve', $payment->id) }}" method="POST" style="display:inline-block">
                                        @csrf
                                        <button class="btn btn-success btn-sm px-2 py-1" style="font-size: 11px;">Approve</button>
                                    </form>
                                    <form action="{{ route('admin.books.purchase.reject', $payment->id) }}" method="POST" style="display:inline-block">
                                        @csrf
                                        <button class="btn btn-danger btn-sm px-2 py-1" style="font-size: 11px;">Reject</button>
                                    </form>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="text-center">No book purchases found</td>
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

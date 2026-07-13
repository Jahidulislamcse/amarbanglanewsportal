@extends('layouts.admin')

@section('content')

<style>
    .order-card {
        background: #fff;
        border: 1px solid #eee;
        border-radius: 8px;
        padding: 16px;
        margin-bottom: 16px;
    }

    .order-items {
        margin: 0;
        padding-left: 18px;
    }

    .order-items li {
        margin-bottom: 6px;
    }

    .badge-pending { background: #ffc107; color: #111; }
    .badge-shipped { background: #17a2b8; color: #fff; }
    .badge-delivered { background: #28a745; color: #fff; }
</style>

<div class="content-area">
    <div class="mr-breadcrumb">
        <h4 class="heading">Product Orders</h4>
    </div>

    <form method="GET" class="mb-3 p-3 bg-light border rounded">
        <div class="row">
            <div class="col-md-3 mb-2">
                <select name="status" class="form-control">
                    <option value="">All Status</option>
                    @foreach(['pending', 'shipped', 'delivered'] as $status)
                        <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>
                            {{ ucfirst($status) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-5 mb-2">
                <input type="text" name="search" class="form-control"
                       placeholder="Search transaction, user, phone, product"
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-4 mb-2">
                <button class="btn btn-primary">Filter</button>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary ml-2">Reset</a>
            </div>
        </div>
    </form>

    @forelse($orders as $order)
        <div class="order-card">
            <div class="row">
                <div class="col-md-3">
                    <strong>Order #{{ $order->id }}</strong><br>
                    <small>{{ $order->created_at->format('d M Y H:i') }}</small><br>
                    <small>TXN: {{ $order->transaction_id ?: '-' }}</small>
                </div>

                <div class="col-md-3">
                    <strong>{{ $order->user->name ?? '-' }}</strong><br>
                    <small>{{ $order->user->email ?? '-' }}</small><br>
                    <small>{{ $order->phone_number ?: ($order->user->phone ?? '-') }}</small>
                </div>

                <div class="col-md-3">
                    <strong>Items</strong>
                    <ul class="order-items">
                        @foreach($order->items as $item)
                            <li>
                                {{ $item->product->name ?? 'Deleted product' }}
                                x {{ $item->quantity }}
                                (&#2547; {{ number_format($item->price, 2) }})
                            </li>
                        @endforeach
                    </ul>
                    <strong>Total: &#2547; {{ number_format($order->total_amount, 2) }}</strong>
                </div>

                <div class="col-md-3">
                    <p class="mb-2">
                        <span class="badge badge-{{ $order->status }}">
                            {{ ucfirst($order->status) }}
                        </span>
                        @if($order->payment)
                            <span class="badge badge-success">
                                {{ ucfirst($order->payment->status) }}
                            </span>
                        @endif
                    </p>

                    <p class="small mb-2">
                        {{ $order->address ?: '-' }}
                    </p>

                    <form method="POST" action="{{ route('admin.orders.updateStatus', $order->id) }}">
                        @csrf
                        <div class="input-group">
                            <select name="status" class="form-control">
                                @foreach(['pending', 'shipped', 'delivered'] as $status)
                                    <option value="{{ $status }}" {{ $order->status === $status ? 'selected' : '' }}>
                                        {{ ucfirst($status) }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="input-group-append">
                                <button class="btn btn-success">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="text-center text-muted p-4 bg-white border rounded">
            No orders found.
        </div>
    @endforelse

    {{ $orders->appends(request()->query())->links() }}
</div>

@endsection

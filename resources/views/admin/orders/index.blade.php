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
                                @if($item->size)
                                    <span class="badge badge-secondary" style="font-size: 11px; background: #6c757d; color: #fff;">Size: {{ $item->size }}</span>
                                @endif
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

                    <button type="button" class="btn btn-info btn-block btn-sm mt-3 download-slip-btn"
                            data-order-id="{{ $order->id }}"
                            data-customer-name="{{ $order->user->name ?? '-' }}"
                            data-customer-phone="{{ $order->phone_number ?: ($order->user->phone ?? '-') }}"
                            data-customer-address="{{ $order->address ?: '-' }}"
                            data-site-name="{{ $gs->title ?? 'Amar Bangla' }}"
                            data-site-name-bn="আমার বাংলা- 24"
                            data-site-phone="{{ optional($contact)->phone ?? ($gs->payment_number ?? '-') }}"
                            data-site-address-bn="{{ optional($contact)->address_bn ?? '-' }}"
                            data-site-address="{{ optional($contact)->address ?? '-' }}"
                    >
                        <i class="fas fa-download"></i> Download Slip
                    </button>
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

@section('scripts')
<script>
$(document).ready(function() {
    $('.download-slip-btn').on('click', async function() {
        const btn = $(this);
        const originalHtml = btn.html();
        
        // Disable button & show loader
        btn.prop('disabled', true);
        btn.html('<i class="fas fa-spinner fa-spin"></i> Generating...');
        
        try {
            // Get data
            const orderId = btn.data('order-id');
            const customerName = btn.data('customer-name');
            const customerPhone = btn.data('customer-phone');
            const customerAddress = String(btn.data('customer-address') || '').replace(/\s*\[Zone:\s*[^\]]+\]/gi, '');
            const siteName = btn.data('site-name');
            const siteNameBn = btn.data('site-name-bn');
            const sitePhone = btn.data('site-phone');
            const siteAddressBn = btn.data('site-address-bn');
            const siteAddress = btn.data('site-address');

            // Setup Canvas (reduced height by 20%)
            const canvas = document.createElement('canvas');
            canvas.width = 1500;
            canvas.height = 520;
            const ctx = canvas.getContext('2d');

            // Background (Pure white)
            ctx.fillStyle = '#ffffff';
            ctx.fillRect(0, 0, canvas.width, canvas.height);

            // Envelope border: simple black border inset by 20px
            ctx.strokeStyle = '#000000';
            ctx.lineWidth = 3;
            ctx.strokeRect(20, 20, 1460, 480);

            // Divider line in the horizontal center (vertical divider)
            ctx.lineWidth = 2;
            ctx.beginPath();
            ctx.moveTo(750, 20);
            ctx.lineTo(750, 500);
            ctx.stroke();

            // Setup text settings
            ctx.fillStyle = '#000000';
            ctx.textAlign = 'left';
            ctx.textBaseline = 'alphabetic';
            const fontStack = '"Segoe UI", Arial, Nikosh, SolaimanLipi, sans-serif';

            function drawWrappedText(context, text, x, y, maxWidth, lineHeight) {
                if (!text) return y;
                const paragraphs = String(text).split('\n');
                let currentY = y;
                for (let i = 0; i < paragraphs.length; i++) {
                    const words = paragraphs[i].split(' ');
                    let line = '';
                    for (let n = 0; n < words.length; n++) {
                        let testLine = line + words[n] + ' ';
                        let metrics = context.measureText(testLine);
                        let testWidth = metrics.width;
                        if (testWidth > maxWidth && n > 0) {
                            context.fillText(line.trim(), x, currentY);
                            line = words[n] + ' ';
                            currentY += lineHeight;
                        } else {
                            line = testLine;
                        }
                    }
                    context.fillText(line.trim(), x, currentY);
                    currentY += lineHeight;
                }
                return currentY;
            }

            // Left Column (Sender / প্রেরক)
            ctx.font = 'bold 36px ' + fontStack;
            ctx.fillText('প্রেরক,', 80, 95);

            const indentXLeft = 110;
            let currentYLeft = 155;

            // Sender Name (Bengali name preferred, fallback to siteName)
            ctx.font = 'bold 30px ' + fontStack;
            ctx.fillText(siteNameBn || siteName || '-', indentXLeft, currentYLeft);
            currentYLeft += 50;

            // Sender Phone
            ctx.font = '30px ' + fontStack;
            ctx.fillText('ফোন: ' + (sitePhone || '-'), indentXLeft, currentYLeft);
            currentYLeft += 50;

            // Sender Address
            ctx.font = '30px ' + fontStack;
            const siteAddressText = 'ঠিকানা: ' + (siteAddressBn || siteAddress || '-');
            drawWrappedText(ctx, siteAddressText, indentXLeft, currentYLeft, 550, 42);

            // Right Column (Receiver / প্রাপক)
            ctx.font = 'bold 36px ' + fontStack;
            ctx.fillText('প্রাপক,', 800, 95);

            const indentXRight = 830;
            let currentYRight = 155;

            // Receiver Name
            ctx.font = 'bold 30px ' + fontStack;
            ctx.fillText(customerName, indentXRight, currentYRight);
            currentYRight += 50;

            // Receiver Phone
            ctx.font = '30px ' + fontStack;
            ctx.fillText('ফোন: ' + customerPhone, indentXRight, currentYRight);
            currentYRight += 50;

            // Receiver Address
            ctx.font = '30px ' + fontStack;
            const customerAddressText = 'ঠিকানা: ' + customerAddress;
            drawWrappedText(ctx, customerAddressText, indentXRight, currentYRight, 580, 42);

            // Download PNG
            const dataUrl = canvas.toDataURL('image/png');
            const link = document.createElement('a');
            link.download = `delivery_slip_order_${orderId}.png`;
            link.href = dataUrl;
            link.click();
            
        } catch (error) {
            console.error('Error generating payment slip:', error);
            alert('Failed to generate payment slip PNG.');
        } finally {
            // Restore button state
            btn.prop('disabled', false);
            btn.html(originalHtml);
        }
    });
});
</script>
@endsection

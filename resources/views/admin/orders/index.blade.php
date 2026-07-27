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

                    {{-- <button type="button" class="btn btn-info btn-block btn-sm mt-3 download-slip-btn"
                            data-order-id="{{ $order->id }}"
                            data-order-date="{{ $order->created_at->format('d M Y') }}"
                            data-order-total="{{ number_format($order->total_amount, 2) }}"
                            data-customer-name="{{ $order->user->name ?? '-' }}"
                            data-customer-phone="{{ $order->phone_number ?: ($order->user->phone ?? '-') }}"
                            data-customer-address="{{ $order->address ?: '-' }}"
                            data-logo-url="{{ asset('assets/amarbangla.png') }}"
                            data-site-name="{{ $gs->title ?? 'Amar Bangla' }}"
                            data-site-url="amarbangla24.com.bd"
                            data-site-phone="{{ optional($contact)->phone ?? ($gs->payment_number ?? '-') }}"
                    >
                        <i class="fas fa-download"></i> Download Slip
                    </button> --}}
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
            const orderDate = btn.data('order-date');
            const orderTotal = btn.data('order-total');
            const customerName = btn.data('customer-name');
            const customerPhone = btn.data('customer-phone');
            const customerAddress = btn.data('customer-address');
            const logoUrl = btn.data('logo-url');
            const siteName = btn.data('site-name');
            const siteUrl = btn.data('site-url');
            const sitePhone = btn.data('site-phone');

            // Load logo image
            const loadImage = (src) => {
                return new Promise((resolve) => {
                    const img = new Image();
                    img.onload = () => resolve(img);
                    img.onerror = () => resolve(null);
                    setTimeout(() => resolve(null), 1500); // 1.5s timeout
                    img.src = src;
                });
            };

            const logoImg = logoUrl ? await loadImage(logoUrl) : null;

            // Setup Canvas
            const canvas = document.createElement('canvas');
            canvas.width = 1500;
            canvas.height = 1000;
            const ctx = canvas.getContext('2d');

            // Background
            ctx.fillStyle = '#ffffff';
            ctx.fillRect(0, 0, canvas.width, canvas.height);

            // Double border
            ctx.strokeStyle = '#1e293b';
            ctx.lineWidth = 6;
            ctx.strokeRect(30, 30, 1440, 940);
            ctx.lineWidth = 2;
            ctx.strokeRect(45, 45, 1410, 910);

            // Header Banner
            ctx.fillStyle = '#1e293b';
            ctx.fillRect(45, 45, 1410, 110);

            // Header Text
            ctx.fillStyle = '#ffffff';
            ctx.font = 'bold 44px Arial';
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            ctx.fillText('DELIVERY SLIP', 750, 100);

            // Vertical divider line
            ctx.setLineDash([15, 10]);
            ctx.lineWidth = 3;
            ctx.strokeStyle = '#94a3b8';
            ctx.beginPath();
            ctx.moveTo(750, 190);
            ctx.lineTo(750, 810);
            ctx.stroke();

            // Reset text settings
            ctx.setLineDash([]);
            ctx.textAlign = 'left';
            ctx.textBaseline = 'alphabetic';

            // Left Column (Merchant Details)
            let startY = 250;
            if (logoImg) {
                let drawWidth = logoImg.width;
                let drawHeight = logoImg.height;
                const maxW = 400;
                const maxH = 160;
                const ratio = Math.min(maxW / drawWidth, maxH / drawHeight);
                drawWidth = drawWidth * ratio;
                drawHeight = drawHeight * ratio;
                ctx.drawImage(logoImg, 100, 210 + (maxH - drawHeight) / 2, drawWidth, drawHeight);
                startY = 430;
            }

            // Draw Site Name
            ctx.fillStyle = '#0f172a';
            ctx.font = 'bold 48px Arial';
            ctx.fillText(siteName, 100, startY);

            // Draw Site URL
            ctx.fillStyle = '#0284c7';
            ctx.font = '34px Arial';
            ctx.fillText(siteUrl, 100, startY + 70);

            // Draw Site Phone
            ctx.fillStyle = '#334155';
            ctx.font = '34px Arial';
            ctx.fillText('Phone: ' + sitePhone, 100, startY + 130);

            // Right Column (Customer Details)
            // Deliver To Badge
            ctx.fillStyle = '#f1f5f9';
            ctx.fillRect(820, 210, 240, 50);
            ctx.fillStyle = '#64748b';
            ctx.font = 'bold 26px Arial';
            ctx.fillText('DELIVER TO', 840, 245);

            // Customer Name
            ctx.fillStyle = '#0f172a';
            ctx.font = 'bold 50px Arial';
            ctx.fillText(customerName, 820, 320);

            // Customer Phone
            ctx.fillStyle = '#0f172a';
            ctx.font = 'bold 38px Arial';
            ctx.fillText('Phone: ' + customerPhone, 820, 390);

            // Customer Address (Wrapped)
            ctx.fillStyle = '#334155';
            ctx.font = '34px Arial';
            
            function drawWrappedText(context, text, x, y, maxWidth, lineHeight) {
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
                            context.fillText(line, x, currentY);
                            line = words[n] + ' ';
                            currentY += lineHeight;
                        } else {
                            line = testLine;
                        }
                    }
                    context.fillText(line, x, currentY);
                    currentY += lineHeight;
                }
            }

            drawWrappedText(ctx, customerAddress, 820, 460, 580, 46);

            // Footer background
            ctx.fillStyle = '#f8fafc';
            ctx.fillRect(45, 831, 1410, 124);

            // Footer separator
            ctx.strokeStyle = '#e2e8f0';
            ctx.lineWidth = 2;
            ctx.beginPath();
            ctx.moveTo(45, 830);
            ctx.lineTo(1455, 830);
            ctx.stroke();

            // Draw Order ID
            ctx.fillStyle = '#0f172a';
            ctx.font = 'bold 36px Arial';
            ctx.fillText('Order ID: #' + orderId, 100, 905);

            // Draw Date
            ctx.fillStyle = '#64748b';
            ctx.font = '32px Arial';
            ctx.fillText('Date: ' + orderDate, 550, 905);

            // Draw Total Amount
            ctx.textAlign = 'right';
            ctx.fillStyle = '#0f172a';
            ctx.font = 'bold 44px Arial';
            ctx.fillText('Total Amount: ৳ ' + orderTotal, 1400, 905);

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

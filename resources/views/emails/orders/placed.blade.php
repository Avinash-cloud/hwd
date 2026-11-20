@extends('emails.layouts.sacred-mail')

@section('content')
    <div class="greeting">Jai Gange, {{ $order->customer_name }}! 🙏</div>

    <p style="font-size: 14px; color: #475569;">
        With the divine blessings of Maa Ganga, we have received your sacred order <strong>#{{ $order->order_number }}</strong>.
        Our team at Har Ki Pauri is preparing your consecrated offerings with deep reverence.
    </p>

    <div class="blessing-badge">
        "सर्वे भवन्तु सुखिनः सर्वे सन्तु निरामयाः ।"<br>
        May this blessed water from the holy ghats of Haridwar bring peace, purity, and prosperity to your household.
    </div>

    <!-- Order Summary Card -->
    <div class="card">
        <table style="width: 100%; font-size: 13px;">
            <tr>
                <td style="color: #64748B; padding-bottom: 6px;">Order Number:</td>
                <td style="text-align: right; font-weight: 700; color: #0F172A; font-family: monospace;">{{ $order->order_number }}</td>
            </tr>
            <tr>
                <td style="color: #64748B; padding-bottom: 6px;">Order Date:</td>
                <td style="text-align: right; color: #0F172A;">{{ $order->created_at->format('M d, Y h:i A') }}</td>
            </tr>
            <tr>
                <td style="color: #64748B; padding-bottom: 6px;">Payment Method:</td>
                <td style="text-align: right; font-weight: 600; text-transform: uppercase;">{{ $order->payment_method }}</td>
            </tr>
            <tr>
                <td style="color: #64748B;">Payment Status:</td>
                <td style="text-align: right; font-weight: 700; color: {{ $order->payment_status === 'paid' ? '#047857' : '#D97706' }}; text-transform: uppercase;">
                    {{ $order->payment_status }}
                </td>
            </tr>
            @if($order->batch)
                <tr>
                    <td style="color: #64748B; padding-top: 6px;">Consecrated Batch:</td>
                    <td style="text-align: right; font-weight: 700; color: #B45309;">
                        {{ $order->batch->batch_number }} ({{ $order->batch->sourcing_ghat }})
                    </td>
                </tr>
            @endif
        </table>
    </div>

    <!-- Items Table -->
    <h3 style="font-size: 15px; font-weight: 700; color: #0F172A; margin: 20px 0 10px;">Ordered Offerings</h3>
    <table class="items-table">
        <thead>
            <tr>
                <th>Item</th>
                <th style="text-align: center;">Qty</th>
                <th style="text-align: right;">Price</th>
                <th style="text-align: right;">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
                <tr>
                    <td>
                        <strong>{{ $item->product_name }}</strong>
                        @if($item->variant_name)
                            <div style="font-size: 11px; color: #64748B;">{{ $item->variant_name }}</div>
                        @endif
                        @if($item->is_free_monthly_bottle)
                            <span style="font-size: 10px; color: #0284C7; font-weight: 700;">★ Monthly Member Benefit</span>
                        @endif
                    </td>
                    <td style="text-align: center;">{{ $item->quantity }}</td>
                    <td style="text-align: right; font-family: monospace;">₹{{ number_format($item->unit_price, 2) }}</td>
                    <td style="text-align: right; font-family: monospace; font-weight: 600;">₹{{ number_format($item->total_price, 2) }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="3" style="text-align: right; color: #64748B;">Subtotal:</td>
                <td style="text-align: right; font-family: monospace;">₹{{ number_format($order->subtotal, 2) }}</td>
            </tr>
            @if($order->discount_amount > 0)
                <tr>
                    <td colspan="3" style="text-align: right; color: #047857;">Member Savings:</td>
                    <td style="text-align: right; font-family: monospace; color: #047857;">-₹{{ number_format($order->discount_amount, 2) }}</td>
                </tr>
            @endif
            @if($order->coupon_discount > 0)
                <tr>
                    <td colspan="3" style="text-align: right; color: #D97706;">Coupon ({{ $order->coupon_code }}):</td>
                    <td style="text-align: right; font-family: monospace; color: #D97706;">-₹{{ number_format($order->coupon_discount, 2) }}</td>
                </tr>
            @endif
            <tr>
                <td colspan="3" style="text-align: right; color: #64748B;">Shipping & Sacred Packaging:</td>
                <td style="text-align: right; font-family: monospace;">
                    {{ $order->shipping_amount > 0 ? '₹'.number_format($order->shipping_amount, 2) : 'FREE' }}
                </td>
            </tr>
            <tr class="total-row">
                <td colspan="3" style="text-align: right;">Total Amount:</td>
                <td style="text-align: right; font-family: monospace; color: #B45309;">₹{{ number_format($order->total_amount, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Destination Address -->
    <div class="card" style="margin-top: 25px;">
        <h4 style="margin: 0 0 10px; font-size: 13px; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px;">Shipping Destination</h4>
        <p style="margin: 0; font-size: 13px; color: #1E293B; line-height: 1.5;">
            <strong>{{ $order->customer_name }}</strong><br>
            {{ $order->shipping_address['address_line1'] ?? '' }}
            @if(!empty($order->shipping_address['address_line2']))
                , {{ $order->shipping_address['address_line2'] }}
            @endif
            <br>
            {{ $order->shipping_address['city'] ?? '' }}, {{ $order->shipping_address['state'] ?? '' }} - {{ $order->shipping_address['postal_code'] ?? '' }}<br>
            Phone: {{ $order->customer_phone ?? '—' }}
        </p>
    </div>

    <div style="text-align: center; margin: 30px 0;">
        <a href="{{ route('orders.show', $order->order_number) }}" class="btn">
            Track Order Status ↗
        </a>
        <a href="{{ route('orders.invoice', $order->order_number) }}" class="btn-outline">
            Download Tax Invoice
        </a>
    </div>
@endsection

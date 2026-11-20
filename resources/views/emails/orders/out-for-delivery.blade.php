@extends('emails.layouts.sacred-mail')

@section('content')
    <div class="greeting">Har Har Gange! Out for Delivery Today 🚩</div>

    <p style="font-size: 14px; color: #475569;">
        Pious devotee <strong>{{ $order->customer_name }}</strong>, your sacred Gangajal from Haridwar is out for delivery today with the delivery executive.
    </p>

    <div class="blessing-badge">
        Please ensure someone is available at the delivery address to receive this holy offering.
    </div>

    <!-- Out for Delivery Details Card -->
    <div class="card" style="border: 2px solid #38BDF8; background: #F0F9FF;">
        <table style="width: 100%; font-size: 13px; color: #0C4A6E;">
            <tr>
                <td style="padding-bottom: 6px;">Order Number:</td>
                <td style="text-align: right; font-weight: 700; font-family: monospace;">{{ $order->order_number }}</td>
            </tr>
            <tr>
                <td style="padding-bottom: 6px;">Courier Partner:</td>
                <td style="text-align: right; font-weight: 700;">{{ $order->courier_name ?? 'NimbusPost Partner' }}</td>
            </tr>
            <tr>
                <td style="padding-bottom: 6px;">Tracking AWB:</td>
                <td style="text-align: right; font-weight: 700; font-family: monospace; color: #0284C7;">
                    {{ $order->awb_number ?: $order->tracking_number }}
                </td>
            </tr>
            <tr>
                <td style="vertical-align: top;">Delivery Address:</td>
                <td style="text-align: right; font-weight: 600;">
                    {{ $order->shipping_address['address_line1'] ?? '' }}, {{ $order->shipping_address['city'] ?? '' }}
                </td>
            </tr>
        </table>
    </div>

    <div style="text-align: center; margin: 25px 0;">
        <a href="{{ $order->tracking_url ?? route('orders.show', $order->order_number) }}" class="btn" style="background-color: #0284C7;">
            Track Delivery Executive ↗
        </a>
    </div>

    <p style="font-size: 12px; color: #64748B; text-align: center;">
        If you need delivery coordination, our support is available to assist you.
    </p>
@endsection

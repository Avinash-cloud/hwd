@extends('emails.layouts.sacred-mail')

@section('content')
    <div class="greeting">Har Har Gange, {{ $order->customer_name }}! 🚚</div>

    <p style="font-size: 14px; color: #475569;">
        Blessed news! Your sacred order <strong>#{{ $order->order_number }}</strong> has been carefully consecrated, packaged with Vedic purity, and dispatched via our logistics partner.
    </p>

    <!-- Shipment Details Card -->
    <div class="card" style="border: 2px solid #FDE68A; background: #FFFDF7;">
        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #FDE68A; padding-bottom: 12px; margin-bottom: 12px;">
            <div>
                <span style="font-size: 11px; text-transform: uppercase; color: #B45309; font-weight: 700; letter-spacing: 1px;">Logistics Partner</span>
                <div style="font-size: 16px; font-weight: 800; color: #0F172A; margin-top: 2px;">
                    {{ $order->courier_name ?? 'NimbusPost Express' }}
                </div>
            </div>
            <div style="text-align: right;">
                <span style="font-size: 11px; text-transform: uppercase; color: #64748B; font-weight: 700; letter-spacing: 1px;">Air Waybill (AWB)</span>
                <div style="font-size: 15px; font-weight: 800; color: #D97706; font-family: monospace; margin-top: 2px;">
                    {{ $order->awb_number ?: $order->tracking_number }}
                </div>
            </div>
        </div>

        <table style="width: 100%; font-size: 13px; color: #334155;">
            <tr>
                <td style="color: #64748B; padding-bottom: 6px;">Dispatched On:</td>
                <td style="text-align: right; font-weight: 600;">{{ ($order->dispatched_at ?? now())->format('M d, Y h:i A') }}</td>
            </tr>
            @if($order->batch)
                <tr>
                    <td style="color: #64748B; padding-bottom: 6px;">Sourced From:</td>
                    <td style="text-align: right; color: #B45309; font-weight: 600;">{{ $order->batch->sourcing_ghat }}, Haridwar</td>
                </tr>
            @endif
            <tr>
                <td style="color: #64748B;">Destination:</td>
                <td style="text-align: right; font-weight: 600;">{{ $order->shipping_address['city'] ?? '' }}, {{ $order->shipping_address['state'] ?? '' }} ({{ $order->shipping_address['postal_code'] ?? '' }})</td>
            </tr>
        </table>
    </div>

    <div style="text-align: center; margin: 30px 0 20px;">
        <a href="{{ $order->tracking_url ?? route('orders.show', $order->order_number) }}" class="btn" style="background-color: #0369A1;">
            Live Tracking on Courier Network ↗
        </a>
        <br>
        <a href="{{ route('orders.show', $order->order_number) }}" class="btn-outline">
            View Order & Batch Provenance
        </a>
    </div>

    <!-- Items Preview -->
    <div class="card">
        <h4 style="margin: 0 0 10px; font-size: 12px; color: #64748B; text-transform: uppercase;">Items in this Parcel</h4>
        <ul style="margin: 0; padding-left: 20px; font-size: 13px; color: #334155;">
            @foreach($order->items as $item)
                <li style="margin-bottom: 4px;">
                    <strong>{{ $item->product_name }}</strong> (Qty: {{ $item->quantity }})
                    @if($item->variant_name) - {{ $item->variant_name }} @endif
                </li>
            @endforeach
        </ul>
    </div>
@endsection

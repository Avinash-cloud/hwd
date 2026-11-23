@extends('emails.layouts.sacred-mail')

@section('content')
    <div class="greeting">Order Cancellation Notice</div>

    <p style="font-size: 14px; color: #475569;">
        Dear {{ $order->customer_name }}, order <strong>#{{ $order->order_number }}</strong> has been cancelled.
    </p>

    <div class="card" style="border-left: 4px solid #E11D48; background: #FFF1F2;">
        <div style="font-size: 13px; color: #9F1239; font-weight: 700; margin-bottom: 4px;">Cancellation Details:</div>
        <div style="font-size: 13px; color: #4C0519;">
            {{ $order->cancellation_reason ?? 'Cancelled as requested by the customer or store administration.' }}
        </div>
        <div style="font-size: 11px; color: #9F1239; margin-top: 6px;">
            Cancelled On: {{ ($order->cancelled_at ?? now())->format('M d, Y h:i A') }}
        </div>
    </div>

    <!-- Refund Info for Prepaid -->
    @if(in_array($order->payment_method, ['razorpay', 'mock']) && $order->payment_status === 'paid')
        <div class="card" style="background: #F8FAFC;">
            <h4 style="margin: 0 0 8px; font-size: 13px; color: #0F172A;">Refund Information</h4>
            <p style="margin: 0; font-size: 13px; color: #475569; line-height: 1.6;">
                Since your order was paid online (₹{{ number_format($order->total_amount, 2) }} via {{ strtoupper($order->payment_method) }}),
                a refund will be processed back to your original payment method within <strong>5 to 7 business days</strong>.
            </p>
        </div>
    @endif

    <p style="font-size: 13px; color: #64748B; margin-top: 20px;">
        If you have any questions or if this cancellation was made in error, please contact our devotee support team at
        <a href="mailto:sales@haridwarbliss.com" style="color: #D97706; font-weight: 600;">sales@haridwarbliss.com</a>.
    </p>

    <div style="text-align: center; margin: 30px 0;">
        <a href="{{ url('/products') }}" class="btn">
            Browse Sacred Offerings ↗
        </a>
    </div>
@endsection

@extends('emails.layouts.sacred-mail', [
    'subject' => 'Your Blu Bliss Sacred Membership Is Active',
])

@section('content')
    <div class="greeting">Namaste {{ $membership->user->name }},</div>

    <p style="font-size: 14px; color: #334155; margin-bottom: 16px;">
        Har Har Gange! Thank you for enrolling in the <strong>Blu Bliss {{ $membership->validity_years }}-Year Sacred Membership</strong>.
        Your previously completed offline membership payment has now been updated on our online portal so you can view your
        membership certificate, download your GST invoice, and track monthly Haridwar Gangajal deliveries.
    </p>

    <div class="card">
        <p style="margin: 0 0 10px; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; color: #B45309; font-weight: 700;">
            Membership Summary
        </p>
        <table class="items-table" style="margin: 0;">
            <tbody>
                <tr>
                    <td><strong>Invoice Reference</strong></td>
                    <td style="text-align: right; font-family: monospace;">{{ $membership->resolved_invoice_number }}</td>
                </tr>
                <tr>
                    <td><strong>Payment Method / Ref</strong></td>
                    <td style="text-align: right;">{{ strtoupper((string) $membership->payment_method) }}{{ $membership->payment_id ? ' (' . $membership->payment_id . ')' : '' }}</td>
                </tr>
                <tr>
                    <td><strong>Commitment Fee Paid</strong></td>
                    <td style="text-align: right;">₹{{ number_format((float) $membership->fee_paid, 2) }}</td>
                </tr>
                <tr>
                    <td><strong>Validity Period</strong></td>
                    <td style="text-align: right;">{{ $membership->starts_at?->format('d M Y') }} &rarr; {{ $membership->expires_at?->format('d M Y') }}</td>
                </tr>
                @if ($prepaidUntil)
                    <tr>
                        <td><strong>Prepaid Monthly Supply Until</strong></td>
                        <td style="text-align: right;">{{ $prepaidUntil->format('d M Y') }}</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    @if ($setPasswordUrl)
        <p style="font-size: 14px; color: #334155;">
            We created your portal login using <strong>{{ $membership->user->email }}</strong>. Set your password once using
            the button below to access your dashboard:
        </p>
        <div style="text-align: center;">
            <a href="{{ $setPasswordUrl }}" class="btn">Set Password &amp; Open Portal</a>
        </div>
    @else
        <div style="text-align: center;">
            <a href="{{ route('dashboard') }}" class="btn">Open Member Dashboard</a>
        </div>
    @endif

    @unless ($hasRazorpayMandate)
        <div class="blessing-badge">
            <strong>Automated Monthly Deliveries (₹0 Joining Fee):</strong> Since your one-time membership commitment fee
            is already recorded, you can activate automated monthly Gangajal doorstep deliveries directly from your
            Member Dashboard via Razorpay AutoPay without paying any joining fee again.
        </div>
    @endunless
@endsection

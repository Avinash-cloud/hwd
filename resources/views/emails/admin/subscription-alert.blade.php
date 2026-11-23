<x-mail::message>
# ⚠️ {{ $alertSubject }}

**Time:** {{ now()->format('d M Y, h:i A') }}

@if($subscription)
## Subscription Details
- **Subscription ID:** #{{ $subscription->id }}
- **Razorpay Sub ID:** {{ $subscription->razorpay_subscription_id ?? 'N/A' }}
- **Member:** {{ $subscription->user?->name ?? 'Unknown' }} ({{ $subscription->user?->email ?? 'N/A' }})
- **Status:** {{ $subscription->status }}
- **Billing Amount:** ₹{{ $subscription->billing_amount }}
- **Next Billing Date:** {{ $subscription->next_billing_date?->format('d M Y') ?? 'N/A' }}
- **Payment Failures:** {{ $subscription->payment_failures_count ?? 0 }}
@endif

@if($order)
## Order Details
- **Order Number:** {{ $order->order_number }}
- **Total Amount:** ₹{{ $order->total_amount }}
- **Payment Status:** {{ $order->payment_status }}
- **Order Status:** {{ $order->order_status }}
@endif

@if(!empty($details))
## Additional Details
@foreach($details as $key => $value)
- **{{ ucwords(str_replace('_', ' ', $key)) }}:** {{ is_array($value) ? json_encode($value) : $value }}
@endforeach
@endif

---

Please investigate and take appropriate action from the admin panel.

<x-mail::button :url="config('app.url') . '/admin/orders'">
View Admin Orders
</x-mail::button>

Thanks,<br>
{{ config('app.name') }} System
</x-mail::message>

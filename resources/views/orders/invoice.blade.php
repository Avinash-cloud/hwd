<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Tax Invoice - {{ $order->order_number }} - Haridwar Bliss</title>
        @vite(['resources/css/app.css'])
        <style>
            @media print {
                .no-print { display: none !important; }
                body { background: white !important; font-size: 11pt !important; }
            }
        </style>
    </head>
    <body class="bg-slate-100 p-4 sm:p-10 text-slate-900 font-sans">
        <div class="max-w-3xl mx-auto bg-white border border-slate-300 p-8 sm:p-12 rounded-2xl shadow-sm">
            <!-- Print / Back Controls -->
            <div class="no-print mb-6 flex justify-between items-center pb-4 border-b border-slate-200">
                <a href="{{ route('orders.show', $order->order_number) }}" class="text-xs font-bold text-[#D97706] hover:underline">
                    ← Back to Order
                </a>
                <button onclick="window.print()" class="px-4 py-2 bg-slate-900 text-white text-xs font-bold rounded-lg shadow hover:bg-slate-800 transition">
                    Print / Save as PDF
                </button>
            </div>

            <!-- Invoice Header -->
            <div class="flex justify-between items-start pb-8 border-b-2 border-[#D97706]">
                <div>
                    <h1 class="font-serif font-extrabold text-2xl text-slate-900">Haridwar Bliss</h1>
                    <p class="text-xs text-[#881337] font-bold uppercase tracking-wider">Sacred Brahmakund Gangajal & Vedic Essentials</p>
                    <p class="text-xs text-slate-500 mt-2 leading-tight">
                        Har Ki Pauri, Brahmakund Sanctum<br>
                        Haridwar, Uttarakhand - 249401, India<br>
                        GSTIN: 05AAACH1234F1Z8 | FSSAI Certified
                    </p>
                </div>

                <div class="text-right">
                    <span class="inline-block px-3 py-1 bg-[#D97706]/10 text-[#D97706] text-xs font-bold rounded-md mb-2">
                        TAX INVOICE
                    </span>
                    <p class="text-xs text-slate-500">Invoice No: <strong class="text-slate-900 font-mono">{{ $order->order_number }}</strong></p>
                    <p class="text-xs text-slate-500">Date: <strong class="text-slate-900">{{ $order->created_at->format('d/m/Y') }}</strong></p>
                    <p class="text-xs text-slate-500">Payment: <strong class="text-slate-900 uppercase">{{ $order->payment_method }} ({{ $order->payment_status }})</strong></p>
                </div>
            </div>

            <!-- Billed To & Traceability Info -->
            <div class="grid grid-cols-2 gap-8 py-6 border-b border-slate-200 text-xs">
                <div>
                    <h3 class="font-bold text-slate-400 uppercase tracking-wider text-[10px] mb-2">Customer & Delivery Address</h3>
                    <p class="font-bold text-sm text-slate-900">{{ $order->shipping_address['recipient_name'] ?? $order->user->name }}</p>
                    <p class="text-slate-600 mt-1">{{ $order->shipping_address['address_line1'] ?? '' }} {{ $order->shipping_address['address_line2'] ?? '' }}</p>
                    <p class="text-slate-600">{{ $order->shipping_address['city'] ?? '' }}, {{ $order->shipping_address['state'] ?? '' }} - {{ $order->shipping_address['postal_code'] ?? '' }}</p>
                    <p class="text-slate-600 mt-1">Phone: {{ $order->shipping_address['phone'] ?? $order->user->phone }}</p>
                </div>

                <div class="flex items-start justify-end gap-4">
                    <div class="text-right space-y-1">
                        <h3 class="font-bold text-slate-400 uppercase tracking-wider text-[10px] mb-2">Batch Traceability QR</h3>
                        <p class="text-slate-500 text-[11px]">Batch: <strong class="text-slate-900 font-mono">{{ $order->batch?->batch_number ?? 'HB-GANG-001' }}</strong></p>
                        <p class="text-slate-500 text-[11px]">Ghat: <strong class="text-slate-900">{{ $order->batch?->sourcing_ghat ?? 'Brahmakund, Har Ki Pauri' }}</strong></p>
                        <p class="text-[10px] text-[#0369A1] font-bold">Scan QR for Lab Purity Verification →</p>
                    </div>
                    <div class="w-20 h-20 p-1 border border-slate-300 rounded-lg shrink-0 flex items-center justify-center">
                        {{-- {!! $qrSvg !!} --}}
                        <img src={!! $qrSvg !!} alt="">
                    </div>
                </div>
            </div>

            <!-- Line Items Table -->
            <div class="py-6">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="border-b-2 border-slate-200 text-slate-500 font-bold uppercase text-[10px]">
                            <th class="py-2">Item Description</th>
                            <th class="py-2 text-center">Qty</th>
                            <th class="py-2 text-right">Unit Rate</th>
                            <th class="py-2 text-right">Amount (INR)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($order->items as $item)
                            <tr>
                                <td class="py-3">
                                    <strong class="text-slate-900">{{ $item->product_name }}</strong>
                                    @if ($item->is_free_monthly_bottle)
                                        <span class="block text-[10px] text-[#0369A1] font-bold">★ 5-Year Member Monthly Free Bottle</span>
                                    @endif
                                </td>
                                <td class="py-3 text-center">{{ $item->quantity }}</td>
                                <td class="py-3 text-right">₹{{ number_format($item->unit_price, 2) }}</td>
                                <td class="py-3 text-right font-bold">₹{{ number_format($item->total_price, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Invoice Totals -->
            <div class="pt-4 border-t-2 border-slate-200 flex justify-end text-xs">
                <div class="w-64 space-y-2">
                    <div class="flex justify-between text-slate-600">
                        <span>Items Subtotal:</span>
                        <span>₹{{ number_format($order->subtotal, 2) }}</span>
                    </div>

                    @if ($order->discount_amount > 0)
                        <div class="flex justify-between text-[#0369A1] font-bold">
                            <span>Member Free Bottle Discount:</span>
                            <span>- ₹{{ number_format($order->discount_amount, 2) }}</span>
                        </div>
                    @endif

                    <div class="flex justify-between text-slate-600">
                        <span>Shiprocket Express Shipping:</span>
                        <span>₹{{ number_format($order->shipping_amount, 2) }}</span>
                    </div>

                    <div class="pt-2 border-t border-slate-200 flex justify-between font-bold text-sm text-slate-900">
                        <span>Grand Total (INR):</span>
                        <span class="text-[#D97706] font-serif text-base">₹{{ number_format($order->total_amount, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Tax declaration note -->
            <div class="mt-10 pt-6 border-t border-slate-200 text-[10px] text-slate-400 text-center space-y-1">
                <p>Sacred Brahmakund Gangajal bottled under strict Vedic sanctification protocols in Haridwar, Uttarakhand.</p>
                <p>This is a computer-generated tax invoice and requires no physical signature. Har Har Gange!</p>
            </div>
        </div>
    </body>
</html>

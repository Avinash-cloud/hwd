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
            <div class="flex justify-between items-start pb-6 border-b-2 border-[#D97706]">
                <div class="flex items-start gap-4">
                    <img src="{{ asset('images/logo.png') }}" alt="Blu Bliss" class="w-16 h-16 rounded-xl object-cover ring-1 ring-slate-200 shrink-0 mt-1">
                    <div>
                        <div class="flex items-center gap-2">
                            <h1 class="font-serif font-bold italic text-2xl text-slate-900 leading-tight drop-shadow-[1.5px_1.5px_0px_#00E5FF]">Blu Bliss</h1>
                            <span class="text-[10px] px-2 py-0.5 rounded bg-amber-100 text-amber-900 font-bold uppercase tracking-wider">Packaged Drinking Water</span>
                        </div>
                        <p class="text-xs text-slate-900 font-bold mt-0.5">HARIDWAR BLISS FOOD & BEVERAGES PRIVATE LIMITED</p>
                        <p class="text-[11px] text-slate-600 font-mono mt-0.5">CIN: <strong class="text-slate-800">U11040UT2025PTC019429</strong></p>
                        <p class="text-[11px] text-slate-600 leading-tight mt-1">
                            G-46, Shivalik Nagar, BHEL, Ranipur More, Haridwar, Uttarakhand - 249403<br>
                            Phone: <span class="font-mono">+91 9891289446</span> | Email: <span class="font-mono">sdenterprisesghaziabad@gmail.com</span><br>
                            GSTIN: 05AAACH1234F1Z8 | FSSAI Certified
                        </p>
                    </div>
                </div>

                <div class="text-right shrink-0">
                    <span class="inline-block px-3 py-1 bg-[#D97706]/10 text-[#D97706] text-xs font-bold rounded-md mb-2">
                        TAX INVOICE
                    </span>
                    <p class="text-xs text-slate-500">Invoice No: <strong class="text-slate-900 font-mono">{{ $order->order_number }}</strong></p>
                    <p class="text-xs text-slate-500">Date: <strong class="text-slate-900">{{ $order->created_at->format('d/m/Y') }}</strong></p>
                    <p class="text-xs text-slate-500">Payment: <strong class="text-slate-900 uppercase">{{ $order->payment_method }} ({{ $order->payment_status }})</strong></p>
                </div>
            </div>

            <!-- Mandatory Legal & Product Designation Banner -->
            <div class="my-4 py-2.5 px-4 bg-amber-50/90 border border-amber-200/90 rounded-xl flex flex-wrap items-center justify-between gap-2 text-xs">
                <div>
                    <span class="text-slate-500 text-[11px]">Product Billing Designation:</span>
                    <strong class="text-amber-950 font-bold block text-sm tracking-wide">Pure Haridwar Gangajal (Packaged)</strong>
                </div>
                <div class="text-right text-[11px] text-slate-600">
                    <span>Corporate Identity: <strong class="font-mono text-slate-800">U11040UT2025PTC019429</strong></span><br>
                    <span>Sourcing Sanctum: <strong class="text-slate-800">{{ $order->batch?->sourcing_ghat ?? 'Brahmakund, Har Ki Pauri' }}</strong></span>
                </div>
            </div>

            <!-- Billed To & Traceability Info -->
            <div class="grid grid-cols-2 gap-8 py-6 border-b border-slate-200 text-xs">
                <div>
                    <h3 class="font-bold text-slate-400 uppercase tracking-wider text-[10px] mb-2">Customer & Delivery Address</h3>
                    <p class="font-bold text-sm text-slate-900">{{ $order->customer_name }}</p>
                    <p class="text-slate-600 mt-1">{{ $order->shipping_address['address_line1'] ?? '' }} {{ $order->shipping_address['address_line2'] ?? '' }}</p>
                    <p class="text-slate-600">{{ $order->shipping_address['city'] ?? '' }}, {{ $order->shipping_address['state'] ?? '' }} - {{ $order->shipping_address['postal_code'] ?? '' }}</p>
                    <p class="text-slate-600 mt-1">Phone: {{ $order->customer_phone ?? '—' }}</p>
                    @if ($order->customer_email)
                        <p class="text-slate-500 text-[11px]">Email: {{ $order->customer_email }}</p>
                    @endif
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
                            <th class="py-2 text-center">HSN</th>
                            <th class="py-2 text-center">GST %</th>
                            <th class="py-2 text-center">Qty</th>
                            <th class="py-2 text-right">Taxable</th>
                            <th class="py-2 text-right">GST</th>
                            <th class="py-2 text-right">Total (INR)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($order->items as $item)
                            <tr>
                                <td class="py-3">
                                    <strong class="text-slate-900">{{ $item->product_name }}</strong>
                                    @if ($item->variant_name)
                                        <span class="block text-[11px] text-amber-800 font-semibold">Size: {{ $item->variant_name }}</span>
                                    @endif
                                    @if ($item->is_free_monthly_bottle)
                                        <span class="block text-[10px] text-[#0369A1] font-bold">★ 5-Year Member Monthly Free Bottle</span>
                                    @endif
                                </td>
                                <td class="py-3 text-center font-mono text-[11px] text-slate-600">{{ $item->hsn_code ?? '22019010' }}</td>
                                <td class="py-3 text-center font-mono text-[11px] text-slate-600">{{ number_format($item->gst_percentage ?? 18, 0) }}%</td>
                                <td class="py-3 text-center">{{ $item->quantity }}</td>
                                <td class="py-3 text-right font-mono">₹{{ number_format($item->taxable_amount ?? round($item->total_price / 1.18, 2), 2) }}</td>
                                <td class="py-3 text-right font-mono">₹{{ number_format($item->gst_amount ?? round($item->total_price - ($item->total_price / 1.18), 2), 2) }}</td>
                                <td class="py-3 text-right font-bold font-mono">₹{{ number_format($item->total_price, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Invoice Totals -->
            <div class="pt-4 border-t-2 border-slate-200 flex justify-end text-xs">
                <div class="w-80 space-y-2">
                    <div class="flex justify-between text-slate-600">
                        <span>Items Subtotal (Inclusive of GST):</span>
                        <span class="font-mono">₹{{ number_format($order->subtotal, 2) }}</span>
                    </div>

                    <div class="p-2.5 bg-slate-50 border border-slate-200 rounded-lg space-y-1 text-[11px] text-slate-600">
                        <div class="flex justify-between">
                            <span>Taxable Value:</span>
                            <span class="font-mono">₹{{ number_format($order->total_taxable_amount ?? 0, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>CGST:</span>
                            <span class="font-mono">₹{{ number_format($order->cgst_amount ?? ($order->total_gst_amount / 2), 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>SGST:</span>
                            <span class="font-mono">₹{{ number_format($order->sgst_amount ?? ($order->total_gst_amount / 2), 2) }}</span>
                        </div>
                        <div class="flex justify-between font-bold text-slate-800 pt-1 border-t border-slate-200">
                            <span>Total GST:</span>
                            <span class="font-mono">₹{{ number_format($order->total_gst_amount ?? 0, 2) }}</span>
                        </div>
                    </div>

                    @if ($order->discount_amount > 0)
                        <div class="flex justify-between text-[#0369A1] font-bold">
                            <span>Devotee Discount:</span>
                            <span class="font-mono">- ₹{{ number_format($order->discount_amount, 2) }}</span>
                        </div>
                    @endif

                    <div class="flex justify-between text-slate-600">
                        <span>Shiprocket Express Shipping:</span>
                        <span class="font-mono">₹{{ number_format($order->shipping_amount, 2) }}</span>
                    </div>

                    <div class="pt-2 border-t border-slate-200 flex justify-between font-bold text-sm text-slate-900">
                        <span>Total Payable (INR):</span>
                        <span class="text-[#D97706] font-serif text-base font-mono">₹{{ number_format($order->total_amount, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Tax declaration note -->
            <div class="mt-10 pt-6 border-t border-slate-200 text-[10px] text-slate-500 text-center space-y-1 leading-relaxed">
                <p class="font-bold text-slate-800 text-xs">Pure Haridwar Gangajal (Packaged)</p>
                <p>Marketed & Billed by <strong>HARIDWAR BLISS FOOD & BEVERAGES PRIVATE LIMITED</strong> · CIN: <strong class="font-mono text-slate-700">U11040UT2025PTC019429</strong></p>
                <p>Registered Address: G-46, Shivalik Nagar, BHEL, Ranipur More, Haridwar, Shivalik Nagar, Hardwar, Haridwar- 249403, Uttarakhand</p>
                <p>Devotee Helpline: +91 9891289446 · Official Email: sdenterprisesghaziabad@gmail.com</p>
                <p class="text-slate-400 pt-1">This is a computer-generated tax invoice and requires no physical signature. Har Har Gange!</p>
            </div>
        </div>
    </body>
</html>

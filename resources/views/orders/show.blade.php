<x-app-layout>
    <div class="bg-[#FFFDF7] border-b border-[#D97706]/30 py-8">
        <div
            class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <span class="text-xs font-bold uppercase tracking-widest text-[#D97706] font-mono">Order
                    Confirmation</span>
                <h1 class="text-3xl font-serif font-extrabold text-slate-900 mt-1">Order #{{ $order->order_number }}
                </h1>
                <p class="text-xs text-slate-500 mt-1">Placed on {{ $order->created_at->format('M d, Y h:i A') }}</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('orders.invoice', $order->order_number) }}" target="_blank"
                    class="px-4 py-2 bg-white border border-[#D97706]/40 text-slate-800 text-xs font-bold rounded-xl hover:bg-[#FFFDF7] shadow-xs transition flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#D97706]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Print Tax Invoice
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-10">
        @if ($order->isGuest() && !Auth::check())
            <!-- Guest Account Creation Callout -->
            <div class="bg-gradient-to-r from-amber-50 to-[#FFFDF7] border-2 border-amber-300 rounded-3xl p-6 sm:p-8 shadow-sm space-y-4">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-[#D97706]/10 text-[#D97706] flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                    </div>
                    <div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-[#D97706]/10 text-[#D97706] uppercase tracking-wider mb-1">
                            Save for Future Tracking
                        </span>
                        <h3 class="font-serif font-bold text-lg text-slate-900">Create an Account to Track Your Sacred Order</h3>
                        <p class="text-xs text-slate-600 mt-1 leading-relaxed">
                            You completed this order as a guest using <strong>{{ $order->customer_email }}</strong>. Set a secure password below to create your Haridwar Bliss account instantly so you can track dispatch milestones, download invoices, and access devotee privileges in the future.
                        </p>
                    </div>
                </div>

                <form method="POST" action="{{ route('orders.createAccount', $order->order_number) }}" class="pt-2 grid grid-cols-1 sm:grid-cols-3 gap-3">
                    @csrf
                    <div>
                        <input type="password"
                               name="password"
                               required
                               placeholder="Create password (min 8 chars)"
                               class="w-full px-3.5 py-2.5 text-xs bg-white border border-slate-300 rounded-xl focus:border-[#D97706] focus:ring-1 focus:ring-[#D97706]">
                        @error('password')
                            <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <input type="password"
                               name="password_confirmation"
                               required
                               placeholder="Confirm your password"
                               class="w-full px-3.5 py-2.5 text-xs bg-white border border-slate-300 rounded-xl focus:border-[#D97706] focus:ring-1 focus:ring-[#D97706]">
                    </div>
                    <div>
                        <button type="submit"
                                class="w-full py-2.5 px-4 bg-[#D97706] hover:bg-[#F97316] text-white font-bold text-xs rounded-xl shadow-xs transition flex items-center justify-center gap-1.5 h-full">
                            <span>Create Account & Link Order</span>
                            <span>→</span>
                        </button>
                    </div>
                </form>
            </div>
        @endif

        <!-- Status Timeline -->
        <div class="bg-white rounded-3xl border border-[#D97706]/30 p-6 sm:p-8 shadow-xs">
            <h2 class="text-xs font-bold uppercase tracking-widest text-slate-400 font-mono mb-6">Delivery Progress</h2>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-center">
                <div
                    class="p-4 rounded-2xl {{ in_array($order->order_status, ['placed', 'confirmed', 'batched', 'dispatched', 'in_transit', 'delivered']) ? 'bg-[#0369A1]/10 border border-[#0369A1]/30 text-[#0369A1]' : 'bg-slate-50 text-slate-400' }}">
                    <div class="w-7 h-7 mx-auto mb-1.5 rounded-full flex items-center justify-center {{ in_array($order->order_status, ['placed', 'confirmed', 'batched', 'dispatched', 'in_transit', 'delivered']) ? 'bg-[#0369A1]/20 text-[#0369A1]' : 'bg-slate-200 text-slate-400' }}">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <strong class="text-xs block">Order Confirmed</strong>
                    <span class="text-[10px] opacity-75">{{ $order->created_at->format('M d') }}</span>
                </div>

                <div
                    class="p-4 rounded-2xl {{ in_array($order->order_status, ['batched', 'dispatched', 'in_transit', 'delivered']) ? 'bg-[#0369A1]/10 border border-[#0369A1]/30 text-[#0369A1]' : 'bg-slate-50 text-slate-400' }}">
                    <div class="w-7 h-7 mx-auto mb-1.5 rounded-full flex items-center justify-center {{ in_array($order->order_status, ['batched', 'dispatched', 'in_transit', 'delivered']) ? 'bg-[#0369A1]/20 text-[#0369A1]' : 'bg-slate-200 text-slate-400' }}">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <strong class="text-xs block">Batch Consecrated</strong>
                    <span class="text-[10px] opacity-75">{{ $order->batch?->batch_number ?? 'Haridwar Batch' }}</span>
                </div>

                <div
                    class="p-4 rounded-2xl {{ in_array($order->order_status, ['dispatched', 'in_transit', 'delivered']) ? 'bg-[#0369A1]/10 border border-[#0369A1]/30 text-[#0369A1]' : 'bg-slate-50 text-slate-400' }}">
                    <div class="w-7 h-7 mx-auto mb-1.5 rounded-full flex items-center justify-center {{ in_array($order->order_status, ['dispatched', 'in_transit', 'delivered']) ? 'bg-[#0369A1]/20 text-[#0369A1]' : 'bg-slate-200 text-slate-400' }}">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                        </svg>
                    </div>
                    <strong class="text-xs block">Dispatched</strong>
                    <span class="text-[10px] opacity-75">{{ $order->tracking_number ?? 'Shiprocket' }}</span>
                </div>

                <div
                    class="p-4 rounded-2xl {{ $order->order_status === 'delivered' ? 'bg-[#0369A1]/10 border border-[#0369A1]/30 text-[#0369A1]' : 'bg-slate-50 text-slate-400' }}">
                    <div class="w-7 h-7 mx-auto mb-1.5 rounded-full flex items-center justify-center {{ $order->order_status === 'delivered' ? 'bg-[#0369A1]/20 text-[#0369A1]' : 'bg-slate-200 text-slate-400' }}">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                    </div>
                    <strong class="text-xs block">Delivered</strong>
                    <span
                        class="text-[10px] opacity-75">{{ $order->delivered_at ? $order->delivered_at->format('M d') : 'Pending' }}</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            <!-- Left 2 Cols: Order Items & Delivery Address -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Items Table -->
                <div class="bg-white rounded-3xl border border-[#D97706]/30 p-8 shadow-xs space-y-6">
                    <h2 class="text-xl font-serif font-bold text-slate-900">Ordered Sacred Items</h2>
                    <div class="divide-y divide-slate-100">
                        @foreach ($order->items as $item)
                            <div class="py-4 flex items-center justify-between gap-4">
                                <div>
                                    <h4 class="font-serif font-bold text-slate-900 text-sm">{{ $item->product_name }}</h4>
                                    @if ($item->variant_name)
                                        <span class="inline-block px-2 py-0.5 mt-0.5 rounded text-[10px] font-bold bg-amber-50 text-amber-900 border border-amber-200">
                                            Size: {{ $item->variant_name }}
                                        </span>
                                    @endif
                                    <div class="flex items-center gap-3 text-xs text-slate-500 mt-1">
                                        <span>Qty: {{ $item->quantity }}</span>
                                        <span>·</span>
                                        <span>Unit: ₹{{ number_format($item->unit_price, 2) }}</span>
                                        <span class="text-[11px] text-slate-400">· HSN: {{ $item->hsn_code ?? '22019010' }} ({{ number_format($item->gst_percentage ?? 18, 0) }}% GST)</span>
                                        @if ($item->is_free_monthly_bottle)
                                            <span
                                                class="px-2 py-0.5 rounded-full font-bold bg-[#0369A1]/10 text-[#0369A1] text-[10px]">
                                                Monthly Free Bottle (₹0)
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <span class="font-bold text-sm text-slate-900 font-serif">
                                    ₹{{ number_format($item->total_price, 2) }}
                                </span>
                            </div>
                        @endforeach
                    </div>

                    <div class="pt-4 border-t border-slate-200 space-y-2 text-xs">
                        <div class="flex justify-between text-slate-600">
                            <span>Subtotal (Inclusive of GST):</span>
                            <span>₹{{ number_format($order->subtotal, 2) }}</span>
                        </div>
                        <div class="pl-2.5 py-1.5 border-l-2 border-amber-300 bg-amber-50/40 rounded-r-lg space-y-1 text-[11px] text-slate-600">
                            <div class="flex justify-between">
                                <span>Taxable Base Value:</span>
                                <span class="font-mono font-medium">₹{{ number_format($order->total_taxable_amount ?? 0, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>GST Breakdown (CGST+SGST):</span>
                                <span class="font-mono font-medium text-slate-800">₹{{ number_format($order->total_gst_amount ?? 0, 2) }}</span>
                            </div>
                        </div>
                        @if ($order->discount_amount > 0)
                            <div class="flex justify-between text-[#0369A1] font-bold">
                                <span>Monthly Member Discount:</span>
                                <span>- ₹{{ number_format($order->discount_amount, 2) }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between text-slate-600">
                            <span>Shipping (Shiprocket Flat):</span>
                            <span>₹{{ number_format($order->shipping_amount, 2) }}</span>
                        </div>
                        <div
                            class="flex justify-between text-sm font-bold text-slate-900 pt-2 border-t border-slate-100 font-serif">
                            <span>Total Paid:</span>
                            <span class="text-[#D97706]">₹{{ number_format($order->total_amount, 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Shipping Address Detail -->
                <div class="bg-white rounded-3xl border border-[#D97706]/30 p-8 shadow-xs space-y-4">
                    <h3 class="text-base font-serif font-bold text-slate-900">Destination Address</h3>
                    <div class="text-xs text-slate-600 space-y-1">
                        <strong class="text-slate-900 font-bold">{{ $order->customer_name }}</strong>
                        <p>{{ $order->shipping_address['address_line1'] ?? '' }}
                            {{ $order->shipping_address['address_line2'] ?? '' }}</p>
                        <p>{{ $order->shipping_address['city'] ?? '' }}, {{ $order->shipping_address['state'] ?? '' }} -
                            {{ $order->shipping_address['postal_code'] ?? '' }}</p>
                        <p class="text-slate-500">Phone: {{ $order->customer_phone ?? '—' }}</p>
                        @if ($order->customer_email)
                            <p class="text-slate-500">Email: {{ $order->customer_email }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Col: Traceability QR Code & Batch Sanctity Details -->
            <div class="space-y-6">
                <div class="bg-[#FFFDF7] border-2 border-[#D97706]/40 rounded-3xl p-8 shadow-md text-center space-y-4">
                    <span class="text-[11px] font-bold uppercase tracking-widest text-[#881337] font-mono">Live Batch
                        Traceability</span>
                    <h3 class="font-serif font-bold text-lg text-slate-900">Scan to Verify Sacred Provenance</h3>

                    <div
                        class="bg-white p-4 rounded-2xl shadow-sm inline-flex items-center justify-center mx-auto border border-[#D97706]/30 w-44 h-44">
                        {{-- {!! $qrSvg !!} --}}
                        <img src={!! $qrSvg !!} alt="">
                    </div>

                    <p class="text-xs text-slate-600 leading-relaxed">
                        This unique QR code is encoded onto your shipment packaging. Scan it with any smartphone camera
                        to inspect the lab certificate and Haridwar collection video.
                    </p>

                    <div class="pt-2">
                        <a href="{{ $verificationUrl }}" target="_blank"
                            class="w-full inline-flex items-center justify-center py-2.5 px-4 bg-[#D97706] hover:bg-[#F97316] text-white font-bold text-xs rounded-xl shadow-xs transition">
                            Open Authenticity Portal →
                        </a>
                    </div>
                </div>

                @if ($order->batch)
                    <div class="bg-white border border-[#D97706]/30 rounded-3xl p-6 shadow-xs text-xs space-y-3">
                        <h4 class="font-serif font-bold text-slate-900 text-sm">Assigned Consecrated Batch</h4>
                        <div class="space-y-1.5 text-slate-600">
                            <div class="flex justify-between">
                                <span>Batch Number:</span>
                                <span class="font-bold text-slate-900 font-mono">{{ $order->batch->batch_number }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Sourcing Ghat:</span>
                                <span class="font-bold text-slate-900">{{ $order->batch->sourcing_ghat }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Collection Date:</span>
                                <span
                                    class="font-bold text-slate-900">{{ $order->batch->collection_date->format('M d, Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Bottling Date:</span>
                                <span
                                    class="font-bold text-slate-900">{{ $order->batch->packaging_date->format('M d, Y') }}</span>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
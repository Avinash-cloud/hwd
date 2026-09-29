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
        <!-- Status Timeline -->
        <div class="bg-white rounded-3xl border border-[#D97706]/30 p-6 sm:p-8 shadow-xs">
            <h2 class="text-xs font-bold uppercase tracking-widest text-slate-400 font-mono mb-6">Delivery Progress</h2>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-center">
                <div
                    class="p-4 rounded-2xl {{ in_array($order->order_status, ['placed', 'confirmed', 'batched', 'dispatched', 'in_transit', 'delivered']) ? 'bg-[#0369A1]/10 border border-[#0369A1]/30 text-[#0369A1]' : 'bg-slate-50 text-slate-400' }}">
                    <span class="text-lg block mb-1">✓</span>
                    <strong class="text-xs block">Order Confirmed</strong>
                    <span class="text-[10px] opacity-75">{{ $order->created_at->format('M d') }}</span>
                </div>

                <div
                    class="p-4 rounded-2xl {{ in_array($order->order_status, ['batched', 'dispatched', 'in_transit', 'delivered']) ? 'bg-[#0369A1]/10 border border-[#0369A1]/30 text-[#0369A1]' : 'bg-slate-50 text-slate-400' }}">
                    <span class="text-lg block mb-1">🕉️</span>
                    <strong class="text-xs block">Batch Consecrated</strong>
                    <span class="text-[10px] opacity-75">{{ $order->batch?->batch_number ?? 'Har Ki Pauri' }}</span>
                </div>

                <div
                    class="p-4 rounded-2xl {{ in_array($order->order_status, ['dispatched', 'in_transit', 'delivered']) ? 'bg-[#0369A1]/10 border border-[#0369A1]/30 text-[#0369A1]' : 'bg-slate-50 text-slate-400' }}">
                    <span class="text-lg block mb-1">🚚</span>
                    <strong class="text-xs block">Dispatched</strong>
                    <span class="text-[10px] opacity-75">{{ $order->tracking_number ?? 'Shiprocket' }}</span>
                </div>

                <div
                    class="p-4 rounded-2xl {{ $order->order_status === 'delivered' ? 'bg-[#0369A1]/10 border border-[#0369A1]/30 text-[#0369A1]' : 'bg-slate-50 text-slate-400' }}">
                    <span class="text-lg block mb-1">🏠</span>
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
                                    <div class="flex items-center gap-3 text-xs text-slate-500 mt-1">
                                        <span>Qty: {{ $item->quantity }}</span>
                                        <span>·</span>
                                        <span>Unit: ₹{{ number_format($item->unit_price, 2) }}</span>
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
                            <span>Subtotal:</span>
                            <span>₹{{ number_format($order->subtotal, 2) }}</span>
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
                        <strong
                            class="text-slate-900 font-bold">{{ $order->shipping_address['recipient_name'] ?? $order->user->name }}</strong>
                        <p>{{ $order->shipping_address['address_line1'] ?? '' }}
                            {{ $order->shipping_address['address_line2'] ?? '' }}</p>
                        <p>{{ $order->shipping_address['city'] ?? '' }}, {{ $order->shipping_address['state'] ?? '' }} -
                            {{ $order->shipping_address['postal_code'] ?? '' }}</p>
                        <p class="text-slate-500">Phone: {{ $order->shipping_address['phone'] ?? $order->user->phone }}
                        </p>
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
                        to inspect the lab certificate and Har Ki Pauri collection video.
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
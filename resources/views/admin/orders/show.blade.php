<x-admin-layout title="Fulfill Order">
    <x-slot name="header">
        Fulfill Order #{{ $order->order_number }}
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 text-xs text-slate-300">
        <!-- Left 2 Cols: Order Summary & Status Update Form -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Status Update Form -->
            <div class="bg-slate-950/80 border border-slate-800 rounded-3xl p-8 shadow-sm space-y-6">
                <div class="flex items-center justify-between pb-4 border-b border-slate-800">
                    <h3 class="font-serif font-bold text-base text-white">Update Dispatch & Fulfillment Status</h3>
                    <span class="px-2.5 py-1 rounded text-xs font-bold uppercase bg-[#D97706]/20 text-[#D97706] border border-[#D97706]/40">
                        Current: {{ $order->order_status }}
                    </span>
                </div>

                <form method="POST" action="{{ route('admin.orders.status', $order->id) }}" class="space-y-4">
                    @csrf

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold text-white mb-1">Update Status *</label>
                            <select name="order_status" class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white">
                                <option value="placed" {{ $order->order_status === 'placed' ? 'selected' : '' }}>Placed</option>
                                <option value="confirmed" {{ $order->order_status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="batched" {{ $order->order_status === 'batched' ? 'selected' : '' }}>Batched</option>
                                <option value="dispatched" {{ $order->order_status === 'dispatched' ? 'selected' : '' }}>Dispatched (In Courier Transit)</option>
                                <option value="delivered" {{ $order->order_status === 'delivered' ? 'selected' : '' }}>Delivered to Devotee</option>
                                <option value="cancelled" {{ $order->order_status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>

                        <div>
                            <label class="block font-bold text-white mb-1">Assign Consecrated Batch</label>
                            <select name="batch_id" class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white">
                                <option value="">-- No Batch Assigned --</option>
                                @foreach ($batches as $b)
                                    <option value="{{ $b->id }}" {{ $order->batch_id === $b->id ? 'selected' : '' }}>
                                        {{ $b->batch_number }} ({{ $b->sourcing_ghat }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold text-white mb-1">Logistics Courier Partner</label>
                            <input type="text" name="courier_name" value="{{ old('courier_name', $order->courier_name ?? 'Shiprocket Express') }}" class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white" />
                        </div>
                        <div>
                            <label class="block font-bold text-white mb-1">Shiprocket Tracking AWB No.</label>
                            <input type="text" name="tracking_number" value="{{ old('tracking_number', $order->tracking_number) }}" placeholder="e.g. SR-IND-998877" class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white font-mono" />
                        </div>
                    </div>

                    <div>
                        <label class="block font-bold text-white mb-1">Admin Internal Notes</label>
                        <input type="text" name="admin_notes" value="{{ old('admin_notes', $order->admin_notes) }}" class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white" />
                    </div>

                    <div class="pt-2 flex justify-end">
                        <button type="submit" class="px-6 py-2.5 bg-[#D97706] hover:bg-[#F97316] text-white font-bold rounded-xl shadow-xs transition">
                            Save Dispatch Changes
                        </button>
                    </div>
                </form>
            </div>

            <!-- Items Table -->
            <div class="bg-slate-950/80 border border-slate-800 rounded-3xl p-8 shadow-sm space-y-4">
                <h3 class="font-serif font-bold text-base text-white">Order Items</h3>
                <div class="divide-y divide-slate-800">
                    @foreach ($order->items as $item)
                        <div class="py-3 flex justify-between items-center">
                            <div>
                                <strong class="text-white">{{ $item->product_name }}</strong>
                                <span class="block text-slate-400">Qty: {{ $item->quantity }} × ₹{{ number_format($item->unit_price, 2) }}</span>
                                @if ($item->is_free_monthly_bottle)
                                    <span class="text-[10px] text-[#0369A1] font-bold">★ Monthly Member Free Bottle</span>
                                @endif
                            </div>
                            <span class="font-mono font-bold text-white">₹{{ number_format($item->total_price, 2) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Right Col: Devotee & QR Code -->
        <div class="space-y-6">
            <!-- QR Traceability Box -->
            <div class="bg-slate-950/80 border border-slate-800 rounded-3xl p-6 text-center space-y-3">
                <span class="text-[10px] uppercase font-mono text-[#D97706] font-bold">Generated Verification QR</span>
                <div class="p-3 bg-white rounded-2xl w-40 h-40 mx-auto border border-slate-700 flex items-center justify-center">
                    {{-- {!! $qrSvg !!} --}}
                    <img src={!! $qrSvg !!} alt="">
                </div>
                <p class="text-[11px] text-slate-400">
                    Encodes batch verification URL on public portal.
                </p>
                <div class="pt-2 flex flex-col gap-2">
                    <a href="{{ $verificationUrl }}" target="_blank" class="w-full py-2 bg-slate-800 hover:bg-slate-700 text-white font-bold rounded-xl border border-slate-700 text-center">
                        Open Public Verify Page ↗
                    </a>
                    <a href="{{ route('admin.orders.packingSlip', $order->id) }}" target="_blank" class="w-full py-2 bg-[#D97706]/20 text-[#D97706] hover:bg-[#D97706]/30 font-bold rounded-xl border border-[#D97706]/40 text-center">
                        Print Warehouse Packing Slip ↗
                    </a>
                </div>
            </div>

            <!-- Devotee Address -->
            <div class="bg-slate-950/80 border border-slate-800 rounded-3xl p-6 space-y-3">
                <h4 class="font-serif font-bold text-white text-sm">Delivery Destination</h4>
                <div class="text-slate-400 space-y-1">
                    <strong class="text-white block">{{ $order->shipping_address['recipient_name'] ?? $order->user->name }}</strong>
                    <p>{{ $order->shipping_address['address_line1'] ?? '' }} {{ $order->shipping_address['address_line2'] ?? '' }}</p>
                    <p>{{ $order->shipping_address['city'] ?? '' }}, {{ $order->shipping_address['state'] ?? '' }} - {{ $order->shipping_address['postal_code'] ?? '' }}</p>
                    <p>Phone: {{ $order->shipping_address['phone'] ?? $order->user->phone }}</p>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>

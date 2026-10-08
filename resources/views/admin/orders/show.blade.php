<x-admin-layout title="Order #{{ $order->order_number }}">
    <x-slot name="header">Order Details</x-slot>

    <!-- Top Navigation / Title -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('admin.orders.index') }}" class="text-xs text-slate-500 hover:text-slate-800 transition inline-flex items-center gap-1 font-medium">
                    ← Back to Orders
                </a>
            </div>
            <h1 class="text-xl font-semibold text-slate-900">Order #{{ $order->order_number }}</h1>
            <p class="text-xs text-slate-500 mt-0.5">Placed on {{ $order->created_at->format('M d, Y \a\t h:i A') }}</p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.orders.packingSlip', $order->id) }}"
               target="_blank"
               class="px-3.5 py-1.5 bg-white hover:bg-slate-50 text-slate-700 border border-slate-300 rounded-lg text-xs font-medium transition inline-flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                <span>Print Packing Slip ↗</span>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 text-xs text-slate-700">
        <!-- Left 2 Columns: Status Update & Items -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Fulfillment Form -->
            <div class="bg-white border border-slate-200 rounded-xl p-6">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-5">
                    <div>
                        <h2 class="text-sm font-semibold text-slate-900">Fulfillment & Dispatch</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Update status, assign consecrated batch, and add courier tracking details.</p>
                    </div>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium uppercase {{ $order->order_status === 'delivered' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-800 border border-amber-200' }}">
                        {{ $order->order_status }}
                    </span>
                </div>

                <form method="POST" action="{{ route('admin.orders.status', $order->id) }}" class="space-y-4">
                    @csrf

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-medium text-slate-700 mb-1">Order Status *</label>
                            <select name="order_status" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-800 focus:border-amber-600 focus:ring-1 focus:ring-amber-600">
                                <option value="placed" {{ $order->order_status === 'placed' ? 'selected' : '' }}>Placed</option>
                                <option value="confirmed" {{ $order->order_status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="batched" {{ $order->order_status === 'batched' ? 'selected' : '' }}>Batched</option>
                                <option value="dispatched" {{ $order->order_status === 'dispatched' ? 'selected' : '' }}>Dispatched (In Transit)</option>
                                <option value="delivered" {{ $order->order_status === 'delivered' ? 'selected' : '' }}>Delivered to Devotee</option>
                                <option value="cancelled" {{ $order->order_status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>

                        <div>
                            <label class="block font-medium text-slate-700 mb-1">Assign Batch</label>
                            <select name="batch_id" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-800 focus:border-amber-600 focus:ring-1 focus:ring-amber-600">
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
                            <label class="block font-medium text-slate-700 mb-1">Logistics Courier Partner</label>
                            <input type="text"
                                   name="courier_name"
                                   value="{{ old('courier_name', $order->courier_name ?? 'Shiprocket Express') }}"
                                   class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-800 focus:border-amber-600 focus:ring-1 focus:ring-amber-600" />
                        </div>
                        <div>
                            <label class="block font-medium text-slate-700 mb-1">Tracking AWB Number</label>
                            <input type="text"
                                   name="tracking_number"
                                   value="{{ old('tracking_number', $order->tracking_number) }}"
                                   placeholder="e.g. SR-IND-998877"
                                   class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-800 font-mono focus:border-amber-600 focus:ring-1 focus:ring-amber-600" />
                        </div>
                    </div>

                    <div>
                        <label class="block font-medium text-slate-700 mb-1">Internal Notes</label>
                        <input type="text"
                               name="admin_notes"
                               value="{{ old('admin_notes', $order->admin_notes) }}"
                               placeholder="Optional notes for warehouse team..."
                               class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-800 focus:border-amber-600 focus:ring-1 focus:ring-amber-600" />
                    </div>

                    <div class="pt-3 flex justify-end">
                        <button type="submit" class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white font-medium rounded-lg text-xs shadow-xs transition">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>

            <!-- Ordered Items List -->
            <div class="bg-white border border-slate-200 rounded-xl p-6">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-3">
                    <h2 class="text-sm font-semibold text-slate-900">Order Items</h2>
                    <span class="font-medium text-slate-900 font-mono text-xs">Total: ₹{{ number_format($order->total_amount, 2) }}</span>
                </div>
                <div class="divide-y divide-slate-100">
                    @foreach ($order->items as $item)
                        <div class="py-3 flex justify-between items-center">
                            <div>
                                <div class="font-medium text-slate-900 text-xs">{{ $item->product_name }}</div>
                                <div class="text-slate-500 text-[11px] mt-0.5">Qty: {{ $item->quantity }} × ₹{{ number_format($item->unit_price, 2) }}</div>
                                @if ($item->is_free_monthly_bottle)
                                    <span class="inline-block mt-1 px-2 py-0.5 rounded text-[10px] font-medium bg-sky-50 text-sky-800 border border-sky-200">
                                        Monthly Free Member Bottle
                                    </span>
                                @endif
                            </div>
                            <span class="font-mono font-medium text-slate-900 text-xs">₹{{ number_format($item->total_price, 2) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Right Column: Verification & Devotee Address -->
        <div class="space-y-6">
            <!-- QR Traceability -->
            <div class="bg-white border border-slate-200 rounded-xl p-5 text-center space-y-3">
                <div class="text-[11px] uppercase font-semibold text-slate-500 tracking-wider">Verification QR</div>
                <div class="p-3 bg-white rounded-lg w-36 h-36 mx-auto border border-slate-200 flex items-center justify-center">
                    <img src="{!! $qrSvg !!}" alt="Batch QR Code" class="w-full h-full object-contain">
                </div>
                <p class="text-[11px] text-slate-500 leading-relaxed">
                    Encodes batch verification URL for public provenance authenticity checks.
                </p>
                <div class="pt-2 flex flex-col gap-2">
                    <a href="{{ $verificationUrl }}"
                       target="_blank"
                       class="w-full py-2 bg-slate-900 hover:bg-slate-800 text-white font-medium rounded-lg text-center text-xs transition">
                        Open Public Verify Page ↗
                    </a>
                </div>
            </div>

            <!-- Devotee Address -->
            <div class="bg-white border border-slate-200 rounded-xl p-5 space-y-3">
                <h2 class="text-sm font-semibold text-slate-900 pb-2 border-b border-slate-100">Shipping Destination</h2>
                <div class="text-slate-600 text-xs space-y-1">
                    <div class="font-medium text-slate-900">{{ $order->shipping_address['recipient_name'] ?? $order->user->name }}</div>
                    <p>{{ $order->shipping_address['address_line1'] ?? '' }} {{ $order->shipping_address['address_line2'] ?? '' }}</p>
                    <p>{{ $order->shipping_address['city'] ?? '' }}, {{ $order->shipping_address['state'] ?? '' }} - {{ $order->shipping_address['postal_code'] ?? '' }}</p>
                    <div class="pt-2 border-t border-slate-100 mt-2 text-slate-700">
                        Phone: <span class="font-medium text-slate-900">{{ $order->shipping_address['phone'] ?? $order->user->phone ?? '—' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>

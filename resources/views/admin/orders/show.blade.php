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
            <div class="flex items-center gap-2 flex-wrap">
                <h1 class="text-xl font-semibold text-slate-900">Order #{{ $order->order_number }}</h1>
                @if ($order->order_status === 'new')
                    <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase bg-amber-500 text-white shadow-xs">
                        NEW
                    </span>
                @endif
                @if ($order->hasFreeMonthlyItem())
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-sky-100 text-sky-800 border border-sky-300">
                        ★ Monthly Member Free Quota
                    </span>
                @endif
            </div>
            <p class="text-xs text-slate-500 mt-0.5">Placed on {{ $order->created_at->format('M d, Y \a\t h:i A') }}</p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.orders.packingSlip', $order->id) }}"
               target="_blank"
               class="px-3.5 py-1.5 bg-white hover:bg-slate-50 text-slate-700 border border-slate-300 rounded-lg text-xs font-medium transition inline-flex items-center gap-1.5 shadow-xs">
                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                <span>Print Packing Slip ↗</span>
            </a>
        </div>
    </div>

    @if ($order->hasFreeMonthlyItem())
        <div class="mb-6 p-4 bg-sky-50 border border-sky-200 rounded-2xl flex items-center gap-3 text-xs text-sky-900 shadow-xs">
            <span class="w-7 h-7 rounded-full bg-sky-100 text-sky-700 flex items-center justify-center font-bold text-sm shrink-0">★</span>
            <div>
                <strong class="font-bold">Devotee Monthly Benefit Quota:</strong>
                <span>This order includes 1 consecrated product redeemed under the 5-Year Membership complimentary monthly quota (₹0.00 product charge).</span>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 text-xs text-slate-700">
        <!-- Left 2 Columns: Status Update & Items & Financials -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Fulfillment Form -->
            <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-xs">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-5">
                    <div>
                        <h2 class="text-sm font-semibold text-slate-900">Fulfillment, Traceability & Status</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Assign consecrated batch and update tracking. Inventory automatically syncs on confirmation & cancellation.</p>
                    </div>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold uppercase
                        {{ $order->order_status === 'new' ? 'bg-amber-500 text-white' : '' }}
                        {{ $order->order_status === 'confirmed' ? 'bg-blue-50 text-blue-700 border border-blue-200' : '' }}
                        {{ $order->order_status === 'dispatched' ? 'bg-sky-50 text-sky-700 border border-sky-200' : '' }}
                        {{ $order->order_status === 'delivered' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : '' }}
                        {{ $order->order_status === 'cancelled' ? 'bg-rose-50 text-rose-700 border border-rose-200' : '' }}">
                        {{ $order->order_status }}
                    </span>
                </div>

                <form method="POST" action="{{ route('admin.orders.status', $order->id) }}" class="space-y-4">
                    @csrf

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-medium text-slate-700 mb-1">Order Status *</label>
                            <select name="order_status" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-800 focus:border-amber-600 focus:ring-1 focus:ring-amber-600">
                                <option value="new" {{ $order->order_status === 'new' ? 'selected' : '' }}>New Order (Pending Review)</option>
                                <option value="confirmed" {{ $order->order_status === 'confirmed' ? 'selected' : '' }}>Confirmed (Auto-Deduct Stock)</option>
                                <option value="batched" {{ $order->order_status === 'batched' ? 'selected' : '' }}>Batched</option>
                                <option value="dispatched" {{ $order->order_status === 'dispatched' ? 'selected' : '' }}>Dispatched (In Transit)</option>
                                <option value="delivered" {{ $order->order_status === 'delivered' ? 'selected' : '' }}>Delivered to Devotee</option>
                                <option value="cancelled" {{ $order->order_status === 'cancelled' ? 'selected' : '' }}>Cancelled (Auto-Restore Stock)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block font-medium text-slate-700 mb-1">Assign Traceable Batch</label>
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
                            <label class="block font-medium text-slate-700 mb-1">Courier Logistics Partner</label>
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
                        <label class="block font-medium text-slate-700 mb-1">Internal Warehouse Notes</label>
                        <input type="text"
                               name="admin_notes"
                               value="{{ old('admin_notes', $order->admin_notes) }}"
                               placeholder="Optional notes for packing & fulfillment team..."
                               class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-800 focus:border-amber-600 focus:ring-1 focus:ring-amber-600" />
                    </div>

                    <div class="pt-3 flex items-center justify-between border-t border-slate-100">
                        <span class="text-[11px] text-slate-500 font-medium">
                            Stock Status: <strong class="{{ $order->inventory_deducted ? 'text-emerald-700' : 'text-slate-600' }}">{{ $order->inventory_deducted ? 'Inventory Deducted' : 'Inventory Not Deducted' }}</strong>
                        </span>
                        <button type="submit" class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white font-medium rounded-lg text-xs shadow-xs transition">
                            Update Order & Inventory
                        </button>
                    </div>
                </form>
            </div>

            <!-- Ordered Items List -->
            <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-xs">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-3">
                    <h2 class="text-sm font-semibold text-slate-900">Ordered Products ({{ $order->items->count() }})</h2>
                    <span class="font-medium text-slate-900 font-mono text-xs">Items Subtotal: ₹{{ number_format($order->subtotal, 2) }}</span>
                </div>
                <div class="divide-y divide-slate-100">
                    @foreach ($order->items as $item)
                        <div class="py-3 flex justify-between items-center gap-4">
                            <div class="flex items-center gap-3">
                                @if($item->product)
                                    <div class="w-12 h-12 rounded-lg bg-slate-100 border border-slate-200 overflow-hidden shrink-0">
                                        <img src="{{ $item->product->image_url }}" alt="{{ $item->product_name }}" class="w-full h-full object-cover">
                                    </div>
                                @endif
                                <div>
                                    <div class="font-medium text-slate-900 text-xs">{{ $item->product_name }}</div>
                                    <div class="text-slate-500 text-[11px] mt-0.5">
                                        Qty: {{ $item->quantity }} × ₹{{ number_format($item->unit_price, 2) }}
                                    </div>
                                    @if ($item->is_free_monthly_bottle)
                                        <span class="inline-block mt-1 px-2 py-0.5 rounded text-[10px] font-bold bg-sky-50 text-sky-800 border border-sky-200">
                                            ★ 1 Unit Monthly Free Member Benefit (₹0.00)
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="font-mono font-bold text-slate-900 text-xs block">₹{{ number_format($item->total_price, 2) }}</span>
                                @if($item->is_free_monthly_bottle)
                                    <span class="text-[10px] text-slate-400 line-through font-mono">₹{{ number_format($item->unit_price * $item->quantity, 2) }}</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Granular Financial & Billing Card for Store Manager -->
            <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-xs space-y-4">
                <h2 class="text-sm font-semibold text-slate-900 pb-2 border-b border-slate-100">Store Financial & Accounting Summary</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-2 text-xs">
                        <div class="flex justify-between">
                            <span class="text-slate-500">Products Subtotal:</span>
                            <span class="font-mono font-medium text-slate-900">₹{{ number_format($order->subtotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Member Quota / Savings:</span>
                            <span class="font-mono font-medium text-emerald-700">- ₹{{ number_format($order->discount_amount, 2) }}</span>
                        </div>
                        @if($order->coupon_code)
                            <div class="flex justify-between">
                                <span class="text-slate-500">Promotional Coupon:</span>
                                <span class="font-mono font-medium text-amber-700">{{ $order->coupon_code }} (-₹{{ number_format($order->coupon_discount, 2) }})</span>
                            </div>
                        @endif
                        <div class="flex justify-between">
                            <span class="text-slate-500">Shipping Charge:</span>
                            <span class="font-mono font-medium text-slate-900">
                                @if($order->shipping_amount <= 0)
                                    <span class="text-emerald-700 font-bold">FREE Delivery</span>
                                @else
                                    ₹{{ number_format($order->shipping_amount, 2) }}
                                @endif
                            </span>
                        </div>
                        <div class="pt-2 border-t border-slate-100 flex justify-between text-sm font-bold">
                            <span class="text-slate-900">Total Collected:</span>
                            <span class="font-mono text-amber-700">₹{{ number_format($order->total_amount, 2) }}</span>
                        </div>
                    </div>

                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 space-y-1.5 text-xs">
                        <div>
                            <span class="text-slate-400 block text-[11px]">Payment Method</span>
                            <span class="font-bold text-slate-900 uppercase font-mono">{{ $order->payment_method }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block text-[11px]">Payment Gateway Ref</span>
                            <span class="font-mono text-slate-800 break-all">{{ $order->payment_id ?? 'N/A' }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block text-[11px]">Payment Status</span>
                            <span class="px-2 py-0.2 rounded-full text-[10px] font-bold uppercase {{ $order->payment_status === 'paid' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                {{ $order->payment_status }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Verification & Devotee Address -->
        <div class="space-y-6">
            <!-- Full Devotee Shipping Address -->
            <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-xs space-y-3">
                <h2 class="text-sm font-semibold text-slate-900 pb-2 border-b border-slate-100">Full Shipping Destination</h2>
                <div class="text-slate-600 text-xs space-y-1.5 leading-relaxed">
                    <div class="font-bold text-slate-900 text-sm">
                        {{ $order->shipping_address['recipient_name'] ?? $order->user->name }}
                    </div>
                    <p>{{ $order->shipping_address['address_line1'] ?? '' }}</p>
                    @if(!empty($order->shipping_address['address_line2']))
                        <p>{{ $order->shipping_address['address_line2'] }}</p>
                    @endif
                    @if(!empty($order->shipping_address['landmark']))
                        <p class="text-slate-500">Landmark: {{ $order->shipping_address['landmark'] }}</p>
                    @endif
                    <p class="font-medium text-slate-800">
                        {{ $order->shipping_address['city'] ?? '' }}, {{ $order->shipping_address['state'] ?? '' }} - <span class="font-mono font-bold">{{ $order->shipping_address['postal_code'] ?? '' }}</span>
                    </p>
                    <p class="text-slate-500">{{ $order->shipping_address['country'] ?? 'India' }}</p>
                    <div class="pt-2 border-t border-slate-100 mt-2 text-slate-700">
                        Contact Phone: <span class="font-bold font-mono text-slate-900">{{ $order->shipping_address['phone'] ?? $order->user->phone ?? '—' }}</span>
                    </div>
                    <div class="text-slate-500">
                        Email: <a href="mailto:{{ $order->user->email ?? '' }}" class="text-amber-700 hover:underline">{{ $order->user->email ?? '—' }}</a>
                    </div>
                </div>
            </div>

            <!-- QR Traceability -->
            <div class="bg-white border border-slate-200 rounded-xl p-5 text-center space-y-3 shadow-xs">
                <div class="text-[11px] uppercase font-semibold text-slate-500 tracking-wider">Provenance Verification QR</div>
                <div class="p-3 bg-white rounded-lg w-36 h-36 mx-auto border border-slate-200 flex items-center justify-center">
                    <img src="{!! $qrSvg !!}" alt="Batch QR Code" class="w-full h-full object-contain">
                </div>
                <p class="text-[11px] text-slate-500 leading-relaxed">
                    Direct public batch provenance check URL for devotees.
                </p>
                <div class="pt-2 flex flex-col gap-2">
                    <a href="{{ $verificationUrl }}"
                       target="_blank"
                       class="w-full py-2 bg-slate-900 hover:bg-slate-800 text-white font-medium rounded-lg text-center text-xs transition">
                        Open Public Verify Page ↗
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>

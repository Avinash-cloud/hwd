<x-admin-layout title="Orders">
    <x-slot name="header">Orders</x-slot>

    <div x-data="{
        selectedOrders: [],
        allIds: {{ json_encode($orders->pluck('id')->toArray()) }},
        toggleAll() {
            if (this.selectedOrders.length === this.allIds.length) {
                this.selectedOrders = [];
            } else {
                this.selectedOrders = [...this.allIds];
            }
        }
    }" class="space-y-4 mb-8">

        <!-- Page Header & Filters -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-xl font-semibold text-slate-900">Orders & Fulfillment</h1>
                <p class="text-xs text-slate-500 mt-0.5">Track and process customer orders, batch traceability, and
                    courier dispatch.</p>
            </div>

            <!-- Search Form -->
            <form method="GET" action="{{ route('admin.orders.index') }}" class="flex items-center gap-2">
                <input type="hidden" name="status" value="{{ $currentStatus }}">
                @if(request('member_free_only'))
                    <input type="hidden" name="member_free_only" value="1">
                @endif
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Search order #, customer, phone, AWB..."
                        class="w-64 pl-9 pr-3 py-1.5 text-xs bg-white border border-slate-300 rounded-lg text-slate-800 placeholder-slate-400 focus:border-amber-600 focus:ring-1 focus:ring-amber-600" />
                    <svg class="w-4 h-4 text-slate-400 absolute left-2.5 top-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <button type="submit"
                    class="px-3.5 py-1.5 bg-slate-900 hover:bg-slate-800 text-white text-xs font-medium rounded-lg transition">
                    Search
                </button>
                @if(request('search'))
                    <a href="{{ route('admin.orders.index', ['status' => $currentStatus, 'member_free_only' => request('member_free_only')]) }}"
                        class="px-2 py-1.5 text-slate-500 hover:text-slate-800 text-xs">
                        Clear
                    </a>
                @endif
            </form>
        </div>

        <!-- Status Filter Tabs -->
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-200 pb-3">
            <div class="flex flex-wrap items-center gap-1.5 text-xs">
                <!-- Default New Orders Tab -->
                <a href="{{ route('admin.orders.index', ['status' => 'new', 'search' => request('search')]) }}"
                    class="px-3 py-1.5 rounded-lg font-medium transition flex items-center gap-1.5 {{ $currentStatus === 'new' ? 'bg-amber-500 text-white font-bold shadow-xs' : 'text-slate-700 hover:text-slate-900 hover:bg-slate-100 bg-white border border-slate-200' }}">
                    <span>New Orders</span>
                    <span
                        class="px-1.5 py-0.2 rounded-full text-[10px] {{ $currentStatus === 'new' ? 'bg-white text-amber-700' : 'bg-amber-100 text-amber-800 font-bold' }}">
                        {{ $counts['new'] }}
                    </span>
                </a>

                <a href="{{ route('admin.orders.index', ['status' => 'confirmed', 'search' => request('search')]) }}"
                    class="px-3 py-1.5 rounded-lg font-medium transition flex items-center gap-1.5 {{ $currentStatus === 'confirmed' ? 'bg-slate-900 text-white font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                    <span>Confirmed</span>
                    <span class="text-[11px] text-slate-400">({{ $counts['confirmed'] }})</span>
                </a>
                
                <a href="{{ route('admin.orders.index', ['status' => 'batched', 'search' => request('search')]) }}"
                    class="px-3 py-1.5 rounded-lg font-medium transition flex items-center gap-1.5 {{ $currentStatus === 'batched' ? 'bg-slate-900 text-white font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                    <span>Batched</span>
                    <span class="text-[11px] text-slate-400">({{ $counts['batched'] }})</span>
                </a>

                <a href="{{ route('admin.orders.index', ['status' => 'dispatched', 'search' => request('search')]) }}"
                    class="px-3 py-1.5 rounded-lg font-medium transition flex items-center gap-1.5 {{ $currentStatus === 'dispatched' ? 'bg-slate-900 text-white font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                    <span>Dispatched</span>
                    <span class="text-[11px] text-slate-400">({{ $counts['dispatched'] }})</span>
                </a>

                <a href="{{ route('admin.orders.index', ['status' => 'delivered', 'search' => request('search')]) }}"
                    class="px-3 py-1.5 rounded-lg font-medium transition flex items-center gap-1.5 {{ $currentStatus === 'delivered' ? 'bg-slate-900 text-white font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                    <span>Delivered</span>
                    <span class="text-[11px] text-slate-400">({{ $counts['delivered'] }})</span>
                </a>
                <a href="{{ route('admin.orders.index', ['status' => 'cancelled', 'search' => request('search')]) }}"
                    class="px-3 py-1.5 rounded-lg font-medium transition flex items-center gap-1.5 {{ $currentStatus === 'cancelled' ? 'bg-slate-900 text-white font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                    <span>Cancelled</span>
                    <span class="text-[11px] text-slate-400">({{ $counts['cancelled'] }})</span>
                </a>

                <a href="{{ route('admin.orders.index', ['status' => 'all', 'search' => request('search')]) }}"
                    class="px-3 py-1.5 rounded-lg font-medium transition {{ $currentStatus === 'all' ? 'bg-slate-900 text-white font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                    All Orders ({{ $counts['all'] }})
                </a>
            </div>

            <!-- Member Free Product Filter Toggle -->
            <div>
                <a href="{{ route('admin.orders.index', ['status' => $currentStatus, 'member_free_only' => request('member_free_only') ? null : 1, 'search' => request('search')]) }}"
                    class="px-3 py-1.5 rounded-lg text-xs font-semibold transition inline-flex items-center gap-1.5 {{ request('member_free_only') ? 'bg-sky-100 text-sky-900 border border-sky-300 ring-1 ring-sky-300' : 'bg-slate-50 text-slate-600 border border-slate-200 hover:bg-slate-100' }}">
                    <span>★ Member Free Quota Orders</span>
                    <span
                        class="px-1.5 py-0.2 rounded-full text-[10px] bg-sky-200 text-sky-800">{{ $counts['member_free'] }}</span>
                </a>
            </div>
        </div>

        <!-- Bulk Action Toolbar (appears when 1 or more orders are selected) -->
        <div x-show="selectedOrders.length > 0" x-transition x-cloak
            class="p-4 bg-amber-50 border border-amber-300 rounded-xl flex flex-col md:flex-row md:items-center justify-between gap-4 text-xs shadow-sm">
            <div class="flex items-center gap-2 font-bold text-amber-900">
                <span
                    class="w-6 h-6 rounded-full bg-amber-200 text-amber-800 flex items-center justify-center font-mono text-xs"
                    x-text="selectedOrders.length"></span>
                <span>Order(s) Selected for Bulk Action</span>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <!-- Bulk Assign Batch Form -->
                <form method="POST" action="{{ route('admin.orders.bulkUpdate') }}" class="flex items-center gap-1.5">
                    @csrf
                    <input type="hidden" name="bulk_action" value="assign_batch">
                    <template x-for="id in selectedOrders" :key="'b_'+id">
                        <input type="hidden" name="order_ids[]" :value="id">
                    </template>
                    <select name="target_batch_id" required
                        class="px-2.5 py-1.5 bg-white border border-amber-300 rounded-lg text-xs text-slate-800">
                        <option value="">-- Assign Batch --</option>
                        @foreach ($batches as $b)
                            <option value="{{ $b->id }}">{{ $b->batch_number }} ({{ $b->sourcing_ghat }})</option>
                        @endforeach
                    </select>
                    <button type="submit"
                        class="px-3 py-1.5 bg-amber-700 hover:bg-amber-800 text-white font-medium rounded-lg transition">
                        Assign Batch
                    </button>
                </form>

                <span class="text-amber-300 hidden sm:inline">|</span>

                <!-- Bulk Status Update Form -->
                <form method="POST" action="{{ route('admin.orders.bulkUpdate') }}" class="flex items-center gap-1.5">
                    @csrf
                    <input type="hidden" name="bulk_action" value="update_status">
                    <template x-for="id in selectedOrders" :key="'s_'+id">
                        <input type="hidden" name="order_ids[]" :value="id">
                    </template>
                    <select name="target_status" required
                        class="px-2.5 py-1.5 bg-white border border-amber-300 rounded-lg text-xs text-slate-800">
                        <option value="">-- Change Status --</option>
                        <option value="confirmed">Mark Confirmed (Deduct Stock)</option>
                        <option value="batched">Mark Batched</option>
                        <option value="dispatched">Mark Dispatched</option>
                        <option value="delivered">Mark Delivered</option>
                        <option value="cancelled">Mark Cancelled (Restore Stock)</option>
                    </select>
                    <button type="submit"
                        class="px-3 py-1.5 bg-slate-900 hover:bg-slate-800 text-white font-medium rounded-lg transition">
                        Update Status
                    </button>
                </form>

                <button type="button" @click="selectedOrders = []"
                    class="text-slate-500 hover:text-slate-800 underline text-xs">
                    Clear
                </button>
            </div>
        </div>

        <!-- Orders Table -->
        <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-xs">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr
                            class="bg-slate-50 border-b border-slate-200 text-slate-600 font-semibold text-[11px] uppercase tracking-wider">
                            <th class="py-3 px-3 w-10 text-center">
                                <input type="checkbox" @click="toggleAll()"
                                    :checked="selectedOrders.length === allIds.length && allIds.length > 0"
                                    class="w-4 h-4 text-amber-600 rounded border-slate-300 focus:ring-amber-500">
                            </th>
                            <th class="py-3 px-4">Order & Badges</th>
                            <th class="py-3 px-4">Devotee Customer</th>
                            <th class="py-3 px-4">Delivery Address</th>
                            <th class="py-3 px-4">Items & Financials</th>
                            <th class="py-3 px-4">Batch / Tracking</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($orders as $o)
                            <tr
                                class="hover:bg-slate-50/80 transition-colors {{ $o->order_status === 'new' ? 'bg-amber-50/20' : '' }}">
                                <!-- Checkbox -->
                                <td class="py-3 px-3 text-center">
                                    <input type="checkbox" :value="{{ $o->id }}" x-model="selectedOrders"
                                        class="w-4 h-4 text-amber-600 rounded border-slate-300 focus:ring-amber-500">
                                </td>

                                <!-- Order # & Badges -->
                                <td class="py-3 px-4">
                                    <div class="flex items-center gap-1.5 flex-wrap">
                                        <a href="{{ route('admin.orders.show', $o->id) }}"
                                            class="font-mono font-bold text-slate-900 hover:text-amber-700 hover:underline">
                                            {{ $o->order_number }}
                                        </a>
                                        @if ($o->order_status === 'new')
                                            <span
                                                class="px-1.5 py-0.2 rounded text-[9px] font-extrabold uppercase bg-amber-500 text-white shadow-xs tracking-wider">
                                                NEW
                                            </span>
                                        @endif
                                    </div>
                                    <div class="text-[11px] text-slate-500 mt-0.5">
                                        {{ $o->created_at->format('M d, Y h:i A') }}
                                    </div>
                                    @if ($o->hasFreeMonthlyItem())
                                        <div class="mt-1">
                                            <span
                                                class="inline-block px-1.5 py-0.2 rounded text-[9px] font-bold bg-sky-100 text-sky-800 border border-sky-300">
                                                ★ Member Free Quota
                                            </span>
                                        </div>
                                    @endif
                                </td>

                                <!-- Customer -->
                                <td class="py-3 px-4">
                                    <div class="font-semibold text-slate-900">
                                        {{ $o->user->name ?? ($o->shipping_address['recipient_name'] ?? 'Devotee') }}</div>
                                    <div class="text-[11px] text-slate-500">{{ $o->user->email ?? '—' }}</div>
                                    <div class="text-[11px] text-slate-600 font-mono">
                                        {{ $o->shipping_address['phone'] ?? $o->user->phone ?? '—' }}</div>
                                </td>

                                <!-- Address Summary -->
                                <td class="py-3 px-4 text-slate-700 max-w-xs">
                                    <p class="truncate">{{ $o->shipping_address['address_line1'] ?? '' }}</p>
                                    <p class="text-[11px] text-slate-500">
                                        {{ $o->shipping_address['city'] ?? '' }}, {{ $o->shipping_address['state'] ?? '' }}
                                        - <span class="font-mono">{{ $o->shipping_address['postal_code'] ?? '' }}</span>
                                    </p>
                                </td>

                                <!-- Items & Financial Breakdown -->
                                <td class="py-3 px-4">
                                    <div class="space-y-0.5">
                                        <div class="font-mono text-slate-900 font-bold text-sm">
                                            ₹{{ number_format($o->total_amount, 2) }}
                                        </div>
                                        <div class="text-[10px] text-slate-500 space-y-0.5">
                                            <div>Items ({{ $o->items->sum('quantity') ?: ($o->items->count() ?: 1) }}): ₹{{ number_format($o->subtotal, 2) }}
                                            </div>
                                            @if($o->discount_amount > 0)
                                                <div class="text-emerald-700 font-semibold">
                                                    Discount: -₹{{ number_format($o->discount_amount, 2) }}
                                                </div>
                                            @endif
                                            @if($o->coupon_code)
                                                <div class="text-amber-700 font-mono">
                                                    Coupon: {{ $o->coupon_code }}
                                                    (-₹{{ number_format($o->coupon_discount, 2) }})
                                                </div>
                                            @endif
                                            <div>
                                                Shipping:
                                                @if($o->shipping_amount <= 0)
                                                    <span class="text-emerald-700 font-bold">FREE Delivery</span>
                                                @else
                                                    <span class="font-mono">₹{{ number_format($o->shipping_amount, 2) }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Batch & AWB -->
                                <td class="py-3 px-4 font-mono text-[11px]">
                                    @if ($o->batch)
                                        <span
                                            class="px-2 py-0.5 rounded text-[10px] font-medium bg-slate-100 text-slate-800 border border-slate-200 block w-max">
                                            {{ $o->batch->batch_number }}
                                        </span>
                                    @else
                                        <span class="text-slate-400 italic block">No batch</span>
                                    @endif
                                    <span class="text-slate-500 mt-1 block">AWB: {{ $o->tracking_number ?? '—' }}</span>
                                </td>

                                <!-- Status -->
                                <td class="py-3 px-4">
                                    @if ($o->order_status === 'new')
                                        <span
                                            class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase bg-amber-500 text-white shadow-xs">
                                            New
                                        </span>
                                    @elseif ($o->order_status === 'confirmed')
                                        <span
                                            class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-blue-50 text-blue-700 border border-blue-200">
                                            Confirmed
                                        </span>
                                    @elseif ($o->order_status === 'batched')
                                        <span
                                            class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-indigo-50 text-indigo-700 border border-indigo-200">
                                            Batched
                                        </span>
                                    @elseif ($o->order_status === 'dispatched')
                                        <span
                                            class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-sky-50 text-sky-700 border border-sky-200">
                                            Dispatched
                                        </span>
                                    @elseif ($o->order_status === 'delivered')
                                        <span
                                            class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            Delivered
                                        </span>
                                    @elseif ($o->order_status === 'cancelled')
                                        <span
                                            class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-rose-50 text-rose-700 border border-rose-200">
                                            Cancelled
                                        </span>
                                    @else
                                        <span
                                            class="px-2.5 py-0.5 rounded-full text-[10px] font-medium uppercase bg-slate-100 text-slate-700 border border-slate-200">
                                            {{ $o->order_status }}
                                        </span>
                                    @endif
                                </td>

                                <!-- Actions -->
                                <td class="py-3 px-4 text-right space-x-2 whitespace-nowrap">
                                    <a href="{{ route('admin.orders.show', $o->id) }}"
                                        class="text-amber-700 hover:text-amber-800 font-bold">
                                        Fulfill
                                    </a>
                                    <a href="{{ route('admin.orders.packingSlip', $o->id) }}" target="_blank"
                                        class="text-slate-500 hover:text-slate-800 font-medium">
                                        Slip ↗
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-12 text-center text-slate-400">
                                    No orders found matching the filter criteria.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($orders->hasPages())
                <div class="p-4 border-t border-slate-200 bg-white">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>
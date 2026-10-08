<x-admin-layout title="Orders">
    <x-slot name="header">Orders</x-slot>

    <!-- Page Header & Filters -->
    <div class="mb-6 space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-xl font-semibold text-slate-900">Orders & Fulfillment</h1>
                <p class="text-xs text-slate-500 mt-0.5">Track customer orders, batch traceability assignments, and courier dispatch status.</p>
            </div>

            <!-- Search Form -->
            <form method="GET" action="{{ route('admin.orders.index') }}" class="flex items-center gap-2">
                <input type="hidden" name="status" value="{{ request('status') }}">
                <div class="relative">
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Search order #, customer, AWB..."
                           class="w-64 pl-9 pr-3 py-1.5 text-xs bg-white border border-slate-300 rounded-lg text-slate-800 placeholder-slate-400 focus:border-amber-600 focus:ring-1 focus:ring-amber-600" />
                    <svg class="w-4 h-4 text-slate-400 absolute left-2.5 top-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <button type="submit" class="px-3.5 py-1.5 bg-slate-900 hover:bg-slate-800 text-white text-xs font-medium rounded-lg transition">
                    Search
                </button>
                @if(request('search'))
                    <a href="{{ route('admin.orders.index', ['status' => request('status')]) }}" class="px-2 py-1.5 text-slate-500 hover:text-slate-800 text-xs">
                        Clear
                    </a>
                @endif
            </form>
        </div>

        <!-- Status Filter Tabs -->
        <div class="flex items-center gap-1.5 border-b border-slate-200 pb-3">
            <a href="{{ route('admin.orders.index', ['search' => request('search')]) }}"
               class="px-3 py-1.5 rounded-lg text-xs font-medium transition {{ !request('status') ? 'bg-amber-50 text-amber-900 font-semibold border border-amber-200' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                All ({{ $orders->total() }})
            </a>
            <a href="{{ route('admin.orders.index', ['status' => 'confirmed', 'search' => request('search')]) }}"
               class="px-3 py-1.5 rounded-lg text-xs font-medium transition {{ request('status') === 'confirmed' ? 'bg-amber-50 text-amber-900 font-semibold border border-amber-200' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                Confirmed
            </a>
            <a href="{{ route('admin.orders.index', ['status' => 'dispatched', 'search' => request('search')]) }}"
               class="px-3 py-1.5 rounded-lg text-xs font-medium transition {{ request('status') === 'dispatched' ? 'bg-amber-50 text-amber-900 font-semibold border border-amber-200' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                Dispatched
            </a>
            <a href="{{ route('admin.orders.index', ['status' => 'delivered', 'search' => request('search')]) }}"
               class="px-3 py-1.5 rounded-lg text-xs font-medium transition {{ request('status') === 'delivered' ? 'bg-amber-50 text-amber-900 font-semibold border border-amber-200' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                Delivered
            </a>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="bg-white border border-slate-200 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 font-semibold text-[11px] uppercase tracking-wider">
                        <th class="py-3 px-4">Order Number</th>
                        <th class="py-3 px-4">Customer</th>
                        <th class="py-3 px-4">Date</th>
                        <th class="py-3 px-4">Batch</th>
                        <th class="py-3 px-4">Tracking AWB</th>
                        <th class="py-3 px-4">Total</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($orders as $o)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-3 px-4 font-mono font-medium text-slate-900">
                                <a href="{{ route('admin.orders.show', $o->id) }}" class="hover:text-amber-700 hover:underline">
                                    {{ $o->order_number }}
                                </a>
                            </td>
                            <td class="py-3 px-4">
                                <div class="font-medium text-slate-900">{{ $o->user->name ?? 'Guest' }}</div>
                                <div class="text-[11px] text-slate-500">{{ $o->user->email ?? '' }}</div>
                            </td>
                            <td class="py-3 px-4 text-slate-600">
                                {{ $o->created_at->format('M d, Y') }}
                            </td>
                            <td class="py-3 px-4 font-mono">
                                @if ($o->batch)
                                    <span class="px-2 py-0.5 rounded text-[11px] font-medium bg-slate-100 text-slate-700 border border-slate-200">
                                        {{ $o->batch->batch_number }}
                                    </span>
                                @else
                                    <span class="text-slate-400 text-[11px] italic">Not assigned</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 font-mono text-[11px] text-slate-600">
                                {{ $o->tracking_number ?? '—' }}
                            </td>
                            <td class="py-3 px-4 font-medium text-slate-900 font-mono">
                                ₹{{ number_format($o->total_amount, 2) }}
                            </td>
                            <td class="py-3 px-4">
                                @if ($o->order_status === 'delivered')
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-medium uppercase bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        {{ $o->order_status }}
                                    </span>
                                @elseif ($o->order_status === 'dispatched')
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-medium uppercase bg-sky-50 text-sky-700 border border-sky-200">
                                        {{ $o->order_status }}
                                    </span>
                                @elseif ($o->order_status === 'cancelled')
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-medium uppercase bg-rose-50 text-rose-700 border border-rose-200">
                                        {{ $o->order_status }}
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-medium uppercase bg-amber-50 text-amber-800 border border-amber-200">
                                        {{ $o->order_status }}
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-right space-x-3 whitespace-nowrap">
                                <a href="{{ route('admin.orders.show', $o->id) }}" class="text-amber-700 hover:text-amber-800 font-medium">
                                    Fulfill
                                </a>
                                <a href="{{ route('admin.orders.packingSlip', $o->id) }}" target="_blank" class="text-slate-500 hover:text-slate-800 font-medium">
                                    Slip ↗
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-slate-400">
                                No orders found matching the criteria.
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
</x-admin-layout>

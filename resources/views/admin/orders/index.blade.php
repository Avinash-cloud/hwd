<x-admin-layout title="Manage Orders">
    <x-slot name="header">
        Orders & Logistics Fulfillment
    </x-slot>

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.orders.index') }}" class="px-3 py-1.5 rounded-lg text-xs font-bold transition {{ !request('status') ? 'bg-[#D97706] text-white' : 'bg-slate-800 text-slate-300' }}">
                All ({{ $orders->total() }})
            </a>
            <a href="{{ route('admin.orders.index', ['status' => 'confirmed']) }}" class="px-3 py-1.5 rounded-lg text-xs font-bold transition {{ request('status') === 'confirmed' ? 'bg-[#D97706] text-white' : 'bg-slate-800 text-slate-300' }}">
                Confirmed
            </a>
            <a href="{{ route('admin.orders.index', ['status' => 'dispatched']) }}" class="px-3 py-1.5 rounded-lg text-xs font-bold transition {{ request('status') === 'dispatched' ? 'bg-[#D97706] text-white' : 'bg-slate-800 text-slate-300' }}">
                Dispatched
            </a>
            <a href="{{ route('admin.orders.index', ['status' => 'delivered']) }}" class="px-3 py-1.5 rounded-lg text-xs font-bold transition {{ request('status') === 'delivered' ? 'bg-[#D97706] text-white' : 'bg-slate-800 text-slate-300' }}">
                Delivered
            </a>
        </div>

        <form method="GET" action="{{ route('admin.orders.index') }}" class="flex items-center gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by order #, AWB, customer..." class="text-xs px-3 py-1.5 bg-slate-900 border border-slate-700 rounded-xl text-white w-64" />
            <button type="submit" class="px-3 py-1.5 bg-slate-800 text-white text-xs font-bold rounded-xl border border-slate-700">Search</button>
        </form>
    </div>

    <div class="bg-slate-950/80 border border-slate-800 rounded-3xl p-6 shadow-sm overflow-x-auto">
        <table class="w-full text-left text-xs">
            <thead>
                <tr class="text-slate-400 border-b border-slate-800 font-mono text-[11px]">
                    <th class="pb-3">Order Number</th>
                    <th class="pb-3">Devotee Name</th>
                    <th class="pb-3">Date</th>
                    <th class="pb-3">Batch Assigned</th>
                    <th class="pb-3">Tracking AWB</th>
                    <th class="pb-3">Total</th>
                    <th class="pb-3">Status</th>
                    <th class="pb-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-800/80">
                @foreach ($orders as $o)
                    <tr>
                        <td class="py-3 font-mono font-bold text-[#D97706]">
                            <a href="{{ route('admin.orders.show', $o->id) }}">{{ $o->order_number }}</a>
                        </td>
                        <td class="py-3">
                            <strong class="text-white">{{ $o->user->name ?? 'Guest' }}</strong>
                            <span class="block text-[10px] text-slate-500">{{ $o->user->email ?? '' }}</span>
                        </td>
                        <td class="py-3 text-slate-400">{{ $o->created_at->format('M d, Y') }}</td>
                        <td class="py-3 font-mono text-slate-300">
                            {{ $o->batch?->batch_number ?? 'Pending' }}
                        </td>
                        <td class="py-3 font-mono text-[11px] text-[#0369A1]">
                            {{ $o->tracking_number ?? 'Not dispatched' }}
                        </td>
                        <td class="py-3 font-bold text-white">₹{{ number_format($o->total_amount, 2) }}</td>
                        <td class="py-3">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $o->order_status === 'delivered' ? 'bg-[#0369A1]/20 text-[#0369A1] border border-[#0369A1]/40' : 'bg-[#D97706]/20 text-[#D97706] border border-[#D97706]/40' }}">
                                {{ $o->order_status }}
                            </span>
                        </td>
                        <td class="py-3 text-right space-x-3">
                            <a href="{{ route('admin.orders.show', $o->id) }}" class="text-[#D97706] hover:underline">Fulfill</a>
                            <a href="{{ route('admin.orders.packingSlip', $o->id) }}" target="_blank" class="text-slate-400 hover:text-white">Slip ↗</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-6">
            {{ $orders->links() }}
        </div>
    </div>
</x-admin-layout>

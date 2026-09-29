<x-admin-layout title="Admin Dashboard">
    <x-slot name="header">
        Operational Dashboard
    </x-slot>

    <!-- Top KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Revenue Card -->
        <div class="bg-slate-950/80 border border-slate-800 rounded-3xl p-6 shadow-sm">
            <span class="text-xs uppercase font-mono text-slate-400 font-bold block">Total Realized Revenue</span>
            <div class="mt-3 flex items-baseline justify-between">
                <span class="text-3xl font-extrabold text-[#D97706] font-serif">₹{{ number_format($grandRevenue, 2) }}</span>
                <span class="text-xs text-[#0369A1] font-bold">Paid</span>
            </div>
            <p class="text-[11px] text-slate-500 mt-2">
                Orders: ₹{{ number_format($totalRevenue, 2) }} | Subs: ₹{{ number_format($membershipRevenue, 2) }}
            </p>
        </div>

        <!-- Active Members Card -->
        <div class="bg-slate-950/80 border border-slate-800 rounded-3xl p-6 shadow-sm">
            <span class="text-xs uppercase font-mono text-slate-400 font-bold block">Active 5-Yr Members</span>
            <div class="mt-3 flex items-baseline justify-between">
                <span class="text-3xl font-extrabold text-white font-serif">{{ $activeMembers }}</span>
                <span class="text-xs text-[#D97706] font-bold">₹500 / 5-Yr</span>
            </div>
            <p class="text-[11px] text-slate-500 mt-2">
                Total Registered Users: {{ $totalUsers }}
            </p>
        </div>

        <!-- Orders Card -->
        <div class="bg-slate-950/80 border border-slate-800 rounded-3xl p-6 shadow-sm">
            <span class="text-xs uppercase font-mono text-slate-400 font-bold block">Total Dispatches</span>
            <div class="mt-3 flex items-baseline justify-between">
                <span class="text-3xl font-extrabold text-white font-serif">{{ $totalOrders }}</span>
                <span class="text-xs text-[#0369A1] font-bold">Shiprocket</span>
            </div>
            <p class="text-[11px] text-slate-500 mt-2">
                All confirmed shipments
            </p>
        </div>

        <!-- Batches Summary Card -->
        <div class="bg-slate-950/80 border border-slate-800 rounded-3xl p-6 shadow-sm">
            <span class="text-xs uppercase font-mono text-slate-400 font-bold block">Consecrated Batches</span>
            <div class="mt-3 flex items-baseline justify-between">
                <span class="text-3xl font-extrabold text-white font-serif">{{ $recentBatches->count() }}</span>
                <span class="text-xs text-[#0369A1] font-bold">Brahmakund</span>
            </div>
            <p class="text-[11px] text-slate-500 mt-2">
                Active QR verification enabled
            </p>
        </div>
    </div>

    <!-- Quick Actions Bar -->
    <div class="flex flex-wrap items-center gap-3 mb-8">
        <a href="{{ route('admin.batches.create') }}" class="px-4 py-2 bg-[#D97706] hover:bg-[#F97316] text-white font-bold text-xs rounded-xl shadow-xs transition">
            + Consecrate New Batch
        </a>
        <a href="{{ route('admin.products.create') }}" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-white font-bold text-xs rounded-xl border border-slate-700 transition">
            + Add New Product
        </a>
        <a href="{{ route('admin.settings.index') }}" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-[#D97706] font-bold text-xs rounded-xl border border-slate-700 transition">
            ⚙️ Dynamic Pricing Settings
        </a>
        <a href="{{ route('admin.reports.index') }}" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold text-xs rounded-xl border border-slate-700 transition">
            📊 Sales & GST Reports
        </a>
    </div>

    <!-- Tables Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left 2 Cols: Recent Orders -->
        <div class="lg:col-span-2 bg-slate-950/80 border border-slate-800 rounded-3xl p-6 shadow-sm space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-800">
                <h3 class="font-serif font-bold text-base text-white">Recent Customer Orders</h3>
                <a href="{{ route('admin.orders.index') }}" class="text-xs text-[#D97706] hover:underline">View All Orders →</a>
            </div>

            @if ($recentOrders->isEmpty())
                <p class="text-xs text-slate-500 py-6 text-center">No orders recorded yet.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="text-slate-400 border-b border-slate-800 font-mono text-[11px]">
                                <th class="pb-3">Order No</th>
                                <th class="pb-3">Devotee</th>
                                <th class="pb-3">Total (INR)</th>
                                <th class="pb-3">Status</th>
                                <th class="pb-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/80">
                            @foreach ($recentOrders as $o)
                                <tr>
                                    <td class="py-3 font-mono font-bold text-[#D97706]">
                                        <a href="{{ route('admin.orders.show', $o->id) }}">{{ $o->order_number }}</a>
                                    </td>
                                    <td class="py-3">{{ $o->user->name ?? 'Guest' }}</td>
                                    <td class="py-3 font-bold text-white">₹{{ number_format($o->total_amount, 2) }}</td>
                                    <td class="py-3">
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase {{ $o->order_status === 'delivered' ? 'bg-[#0369A1]/20 text-[#0369A1] border border-[#0369A1]/40' : 'bg-[#D97706]/20 text-[#D97706] border border-[#D97706]/40' }}">
                                            {{ $o->order_status }}
                                        </span>
                                    </td>
                                    <td class="py-3 text-right space-x-2">
                                        <a href="{{ route('admin.orders.show', $o->id) }}" class="text-[#D97706] hover:underline">Fulfill</a>
                                        <a href="{{ route('admin.orders.packingSlip', $o->id) }}" target="_blank" class="text-slate-400 hover:text-white">Slip</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <!-- Right Col: Active Batches -->
        <div class="bg-slate-950/80 border border-slate-800 rounded-3xl p-6 shadow-sm space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-800">
                <h3 class="font-serif font-bold text-base text-white">Batches & Traceability</h3>
                <a href="{{ route('admin.batches.index') }}" class="text-xs text-[#D97706] hover:underline">All Batches →</a>
            </div>

            <div class="space-y-3">
                @foreach ($recentBatches as $batch)
                    <div class="p-3.5 rounded-2xl bg-slate-900 border border-slate-800 text-xs space-y-1">
                        <div class="flex items-center justify-between">
                            <strong class="font-mono text-[#D97706] text-xs">{{ $batch->batch_number }}</strong>
                            <span class="px-1.5 py-0.5 rounded text-[10px] uppercase bg-[#0369A1]/20 text-[#0369A1] border border-[#0369A1]/40">{{ $batch->status }}</span>
                        </div>
                        <p class="text-slate-300 text-[11px] truncate">{{ $batch->sourcing_ghat }}</p>
                        <p class="text-slate-500 text-[10px]">Collection: {{ $batch->collection_date->format('M d, Y') }} · Orders: {{ $batch->orders_count }}</p>
                        <div class="pt-1 flex gap-2">
                            <a href="{{ route('verify.batch', $batch->batch_number) }}" target="_blank" class="text-[10px] text-[#D97706] hover:underline">Verify QR Page ↗</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-admin-layout>

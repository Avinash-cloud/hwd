<x-admin-layout title="Admin Dashboard">
    <x-slot name="header">
        Dashboard
    </x-slot>

    <!-- Page Title & Header Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-xl font-semibold text-slate-900">Dashboard</h1>
            <p class="text-xs text-slate-500 mt-0.5">Overview of store revenue, fulfillment, batches, and recent activity.</p>
        </div>

        <!-- Quick Action Buttons -->
        <div class="flex items-center gap-2 flex-wrap">
            <a href="{{ route('admin.products.create') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-amber-600 hover:bg-amber-700 text-white text-xs font-medium rounded-lg transition shadow-xs">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Add Product</span>
            </a>
            <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center gap-1.5 px-3 py-2 bg-white hover:bg-slate-50 text-slate-700 border border-slate-300 text-xs font-medium rounded-lg transition">
                <span>Add Category</span>
            </a>
            <a href="{{ route('admin.batches.create') }}" class="inline-flex items-center gap-1.5 px-3 py-2 bg-white hover:bg-slate-50 text-slate-700 border border-slate-300 text-xs font-medium rounded-lg transition">
                <span>Consecrate Batch</span>
            </a>
            <a href="{{ route('admin.blogs.create') }}" class="inline-flex items-center gap-1.5 px-3 py-2 bg-white hover:bg-slate-50 text-slate-700 border border-slate-300 text-xs font-medium rounded-lg transition">
                <span>Write Article</span>
            </a>
            <a href="{{ route('admin.gallery.create') }}" class="inline-flex items-center gap-1.5 px-3 py-2 bg-white hover:bg-slate-50 text-slate-700 border border-slate-300 text-xs font-medium rounded-lg transition">
                <span>Add Gallery</span>
            </a>
        </div>
    </div>

    <!-- KPI Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <!-- Revenue Card -->
        <div class="bg-white border border-slate-200 rounded-xl p-5">
            <div class="flex items-center justify-between">
                <span class="text-xs font-medium text-slate-500 uppercase tracking-wider">Total Revenue</span>
                <span class="text-xs px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 font-medium">Paid</span>
            </div>
            <div class="mt-2.5">
                <span class="text-2xl font-bold text-slate-900 tracking-tight">₹{{ number_format($grandRevenue, 2) }}</span>
            </div>
            <p class="text-xs text-slate-500 mt-2 pt-2 border-t border-slate-100 flex justify-between">
                <span>Orders: ₹{{ number_format($totalRevenue, 2) }}</span>
                <span>Subs: ₹{{ number_format($membershipRevenue, 2) }}</span>
            </p>
        </div>

        <!-- Active Members Card -->
        <div class="bg-white border border-slate-200 rounded-xl p-5">
            <div class="flex items-center justify-between">
                <span class="text-xs font-medium text-slate-500 uppercase tracking-wider">Active Members</span>
                <span class="text-xs px-2 py-0.5 rounded-full bg-amber-50 text-amber-800 font-medium">5-Yr Plan</span>
            </div>
            <div class="mt-2.5">
                <span class="text-2xl font-bold text-slate-900 tracking-tight">{{ $activeMembers }}</span>
            </div>
            <p class="text-xs text-slate-500 mt-2 pt-2 border-t border-slate-100">
                Registered devotees: <strong class="text-slate-700 font-medium">{{ $totalUsers }}</strong>
            </p>
        </div>

        <!-- Orders Card -->
        <div class="bg-white border border-slate-200 rounded-xl p-5">
            <div class="flex items-center justify-between">
                <span class="text-xs font-medium text-slate-500 uppercase tracking-wider">Total Dispatches</span>
                <span class="text-xs px-2 py-0.5 rounded-full bg-slate-100 text-slate-600 font-medium">Shiprocket</span>
            </div>
            <div class="mt-2.5">
                <span class="text-2xl font-bold text-slate-900 tracking-tight">{{ $totalOrders }}</span>
            </div>
            <p class="text-xs text-slate-500 mt-2 pt-2 border-t border-slate-100">
                All confirmed shipments & dispatches
            </p>
        </div>

        <!-- Batches Summary Card -->
        <div class="bg-white border border-slate-200 rounded-xl p-5">
            <div class="flex items-center justify-between">
                <span class="text-xs font-medium text-slate-500 uppercase tracking-wider">Consecrated Batches</span>
                <span class="text-xs px-2 py-0.5 rounded-full bg-sky-50 text-sky-700 font-medium">Haridwar</span>
            </div>
            <div class="mt-2.5">
                <span class="text-2xl font-bold text-slate-900 tracking-tight">{{ $recentBatches->count() }}</span>
            </div>
            <p class="text-xs text-slate-500 mt-2 pt-2 border-t border-slate-100">
                Active QR verification batches
            </p>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left 2 Cols: Recent Customer Orders -->
        <div class="lg:col-span-2 bg-white border border-slate-200 rounded-xl overflow-hidden">
            <div class="p-4 sm:p-5 border-b border-slate-200 flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-semibold text-slate-900">Recent Customer Orders</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Latest shipments and orders received</p>
                </div>
                <a href="{{ route('admin.orders.index') }}" class="text-xs text-amber-700 hover:text-amber-800 font-medium">View all →</a>
            </div>

            @if ($recentOrders->isEmpty())
                <div class="py-10 text-center">
                    <p class="text-xs text-slate-400">No orders recorded yet.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="bg-slate-50/75 border-b border-slate-200 text-slate-600 font-semibold uppercase text-[11px] tracking-wider">
                                <th class="py-2.5 px-4">Order No</th>
                                <th class="py-2.5 px-4">Devotee</th>
                                <th class="py-2.5 px-4">Total</th>
                                <th class="py-2.5 px-4">Status</th>
                                <th class="py-2.5 px-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($recentOrders as $o)
                                <tr class="hover:bg-slate-50/60 transition">
                                    <td class="py-3 px-4 font-mono font-medium text-slate-900">
                                        <a href="{{ route('admin.orders.show', $o->id) }}" class="hover:text-amber-700">{{ $o->order_number }}</a>
                                    </td>
                                    <td class="py-3 px-4 text-slate-700">{{ $o->user->name ?? 'Guest' }}</td>
                                    <td class="py-3 px-4 font-medium text-slate-900">₹{{ number_format($o->total_amount, 2) }}</td>
                                    <td class="py-3 px-4">
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-medium uppercase {{ $o->order_status === 'delivered' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-800 border border-amber-200' }}">
                                            {{ $o->order_status }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-right space-x-2">
                                        <a href="{{ route('admin.orders.show', $o->id) }}" class="text-amber-700 hover:text-amber-800 font-medium">Fulfill</a>
                                        <span class="text-slate-300">·</span>
                                        <a href="{{ route('admin.orders.packingSlip', $o->id) }}" target="_blank" class="text-slate-500 hover:text-slate-800">Slip ↗</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <!-- Right Col: Active Batches -->
        <div class="bg-white border border-slate-200 rounded-xl overflow-hidden">
            <div class="p-4 sm:p-5 border-b border-slate-200 flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-semibold text-slate-900">Traceability Batches</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Consecrated Ganga water batches</p>
                </div>
                <a href="{{ route('admin.batches.index') }}" class="text-xs text-amber-700 hover:text-amber-800 font-medium">All batches →</a>
            </div>

            <div class="p-4 space-y-3">
                @foreach ($recentBatches as $batch)
                    <div class="p-3 rounded-lg border border-slate-200 text-xs space-y-1">
                        <div class="flex items-center justify-between">
                            <span class="font-mono text-slate-900 font-semibold">{{ $batch->batch_number }}</span>
                            <span class="px-1.5 py-0.5 rounded text-[10px] uppercase font-medium bg-sky-50 text-sky-700 border border-sky-200">{{ $batch->status }}</span>
                        </div>
                        <p class="text-slate-600 text-[11px] truncate">{{ $batch->sourcing_ghat }}</p>
                        <div class="pt-1 flex items-center justify-between text-[11px]">
                            <span class="text-slate-400">Date: {{ $batch->collection_date->format('M d, Y') }}</span>
                            <a href="{{ route('verify.batch', $batch->batch_number) }}" target="_blank" class="text-sky-700 hover:text-sky-800 font-medium">Verify QR ↗</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-admin-layout>

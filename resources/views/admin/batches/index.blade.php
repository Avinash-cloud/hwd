<x-admin-layout title="Batches">
    <x-slot name="header">Batches</x-slot>

    <!-- Page Header & Action -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h1 class="text-xl font-semibold text-slate-900">Consecrated Batches ({{ $batches->total() }})</h1>
            <p class="text-xs text-slate-500 mt-0.5">Each batch links to customer orders for public QR provenance and lab verification.</p>
        </div>
        <a href="{{ route('admin.batches.create') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-amber-600 hover:bg-amber-700 text-white font-medium text-xs rounded-lg shadow-xs transition">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            <span>Record New Batch</span>
        </a>
    </div>

    <!-- Batches Table -->
    <div class="bg-white border border-slate-200 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 font-semibold text-[11px] uppercase tracking-wider">
                        <th class="py-3 px-4">Batch Code</th>
                        <th class="py-3 px-4">Sourcing Ghat</th>
                        <th class="py-3 px-4">Collection Date</th>
                        <th class="py-3 px-4">Packaging Date</th>
                        <th class="py-3 px-4 text-center">Status</th>
                        <th class="py-3 px-4 text-center">Orders Filled</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($batches as $b)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-3 px-4 font-mono font-medium text-slate-900">
                                {{ $b->batch_number }}
                            </td>
                            <td class="py-3 px-4 text-slate-700 font-medium">
                                {{ $b->sourcing_ghat }}
                            </td>
                            <td class="py-3 px-4 text-slate-600">
                                {{ $b->collection_date->format('d M Y') }}
                            </td>
                            <td class="py-3 px-4 text-slate-600">
                                {{ $b->packaging_date->format('d M Y') }}
                            </td>
                            <td class="py-3 px-4 text-center">
                                @if ($b->status === 'ready')
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-medium uppercase bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        Ready
                                    </span>
                                @elseif ($b->status === 'dispatched')
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-medium uppercase bg-sky-50 text-sky-700 border border-sky-200">
                                        Dispatched
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-medium uppercase bg-slate-100 text-slate-600 border border-slate-200">
                                        {{ $b->status }}
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center font-medium text-slate-900 font-mono">
                                {{ $b->orders_count }} orders
                            </td>
                            <td class="py-3 px-4 text-right space-x-3 whitespace-nowrap">
                                <a href="{{ route('verify.batch', $b->batch_number) }}" target="_blank" class="text-slate-600 hover:text-slate-900 font-medium">
                                    Public QR ↗
                                </a>
                                <a href="{{ route('admin.batches.edit', $b->id) }}" class="text-amber-700 hover:text-amber-800 font-medium">
                                    Edit
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400">
                                No batches recorded yet. Click "Record New Batch" to add one.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($batches->hasPages())
            <div class="p-4 border-t border-slate-200 bg-white">
                {{ $batches->links() }}
            </div>
        @endif
    </div>
</x-admin-layout>

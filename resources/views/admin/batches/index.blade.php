<x-admin-layout title="Consecrated Batches">
    <x-slot name="header">
        Batches & Traceability Management
    </x-slot>

    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-xl font-serif font-bold text-white">Gangajal Batches ({{ $batches->total() }})</h2>
            <p class="text-xs text-slate-400 mt-1">Each batch links to customer order bottles via dynamic QR verification.</p>
        </div>
        <a href="{{ route('admin.batches.create') }}" class="px-4 py-2 bg-[#D97706] hover:bg-[#F97316] text-white font-bold text-xs rounded-xl shadow-xs transition">
            + Consecrate New Batch
        </a>
    </div>

    <div class="bg-slate-950/80 border border-slate-800 rounded-3xl p-6 shadow-sm overflow-x-auto">
        <table class="w-full text-left text-xs">
            <thead>
                <tr class="text-slate-400 border-b border-slate-800 font-mono text-[11px]">
                    <th class="pb-3">Batch Code</th>
                    <th class="pb-3">Sourcing Ghat</th>
                    <th class="pb-3">Collection Date</th>
                    <th class="pb-3">Packaging Date</th>
                    <th class="pb-3">Status</th>
                    <th class="pb-3">Orders Filled</th>
                    <th class="pb-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-800/80">
                @foreach ($batches as $b)
                    <tr>
                        <td class="py-3 font-mono font-bold text-[#D97706]">
                            {{ $b->batch_number }}
                        </td>
                        <td class="py-3 text-slate-300">{{ $b->sourcing_ghat }}</td>
                        <td class="py-3">{{ $b->collection_date->format('d M Y') }}</td>
                        <td class="py-3">{{ $b->packaging_date->format('d M Y') }}</td>
                        <td class="py-3">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $b->status === 'ready' ? 'bg-[#0369A1]/20 text-[#0369A1] border border-[#0369A1]/40' : 'bg-slate-800 text-slate-400' }}">
                                {{ $b->status }}
                            </span>
                        </td>
                        <td class="py-3 font-bold text-white">{{ $b->orders_count }} orders</td>
                        <td class="py-3 text-right space-x-3">
                            <a href="{{ route('verify.batch', $b->batch_number) }}" target="_blank" class="text-[#0369A1] hover:underline">Public QR Portal ↗</a>
                            <a href="{{ route('admin.batches.edit', $b->id) }}" class="text-[#D97706] hover:underline">Edit</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-6">
            {{ $batches->links() }}
        </div>
    </div>
</x-admin-layout>

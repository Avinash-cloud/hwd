<x-admin-layout title="Bulk Product Enquiries">
    <x-slot name="header">Bulk Product Enquiries</x-slot>

    <!-- Page Header & Filters -->
    <div class="mb-6 space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-xl font-semibold text-slate-900">Bulk Product Enquiries</h1>
                <p class="text-xs text-slate-500 mt-0.5">Wholesale, temple, institutional, and bulk purchase inquiries submitted by devotees and vendors.</p>
            </div>

            <!-- Search Form -->
            <form method="GET" action="{{ route('admin.bulk-inquiries.index') }}" class="flex items-center gap-2">
                <input type="hidden" name="status" value="{{ request('status') }}">
                <div class="relative">
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Search name, phone, product..."
                           class="w-64 pl-9 pr-3 py-1.5 text-xs bg-white border border-slate-300 rounded-lg text-slate-800 placeholder-slate-400 focus:border-amber-600 focus:ring-1 focus:ring-amber-600" />
                    <svg class="w-4 h-4 text-slate-400 absolute left-2.5 top-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <button type="submit" class="px-3.5 py-1.5 bg-slate-900 hover:bg-slate-800 text-white text-xs font-medium rounded-lg transition">
                    Search
                </button>
                @if(request('search'))
                    <a href="{{ route('admin.bulk-inquiries.index', ['status' => request('status')]) }}" class="px-2 py-1.5 text-slate-500 hover:text-slate-800 text-xs">
                        Clear
                    </a>
                @endif
            </form>
        </div>

        <!-- Status Filter Tabs -->
        <div class="flex items-center gap-1.5 border-b border-slate-200 pb-3 overflow-x-auto scrollbar-none">
            <a href="{{ route('admin.bulk-inquiries.index', ['search' => request('search')]) }}"
               class="px-3 py-1.5 rounded-lg text-xs font-medium transition whitespace-nowrap {{ !request('status') ? 'bg-amber-50 text-amber-900 font-semibold border border-amber-200' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                All ({{ $inquiries->total() }})
            </a>
            <a href="{{ route('admin.bulk-inquiries.index', ['status' => 'pending', 'search' => request('search')]) }}"
               class="px-3 py-1.5 rounded-lg text-xs font-medium transition flex items-center gap-1.5 whitespace-nowrap {{ request('status') === 'pending' ? 'bg-amber-50 text-amber-900 font-semibold border border-amber-200' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                <span>Pending</span>
                @if($pendingCount > 0)
                    <span class="px-1.5 py-0.2 rounded-full text-[10px] font-bold bg-amber-600 text-white">{{ $pendingCount }}</span>
                @endif
            </a>
            <a href="{{ route('admin.bulk-inquiries.index', ['status' => 'contacted', 'search' => request('search')]) }}"
               class="px-3 py-1.5 rounded-lg text-xs font-medium transition whitespace-nowrap {{ request('status') === 'contacted' ? 'bg-blue-50 text-blue-900 font-semibold border border-blue-200' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                Contacted
            </a>
            <a href="{{ route('admin.bulk-inquiries.index', ['status' => 'quoted', 'search' => request('search')]) }}"
               class="px-3 py-1.5 rounded-lg text-xs font-medium transition whitespace-nowrap {{ request('status') === 'quoted' ? 'bg-purple-50 text-purple-900 font-semibold border border-purple-200' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                Quoted
            </a>
            <a href="{{ route('admin.bulk-inquiries.index', ['status' => 'completed', 'search' => request('search')]) }}"
               class="px-3 py-1.5 rounded-lg text-xs font-medium transition whitespace-nowrap {{ request('status') === 'completed' ? 'bg-emerald-50 text-emerald-900 font-semibold border border-emerald-200' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                Completed
            </a>
            <a href="{{ route('admin.bulk-inquiries.index', ['status' => 'cancelled', 'search' => request('search')]) }}"
               class="px-3 py-1.5 rounded-lg text-xs font-medium transition whitespace-nowrap {{ request('status') === 'cancelled' ? 'bg-slate-100 text-slate-800 font-semibold border border-slate-300' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                Cancelled
            </a>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 font-semibold text-[11px] uppercase tracking-wider">
                        <th class="py-3 px-4">Date</th>
                        <th class="py-3 px-4">Client / Devotee</th>
                        <th class="py-3 px-4">Product</th>
                        <th class="py-3 px-4">Quantity</th>
                        <th class="py-3 px-4">Message Preview</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($inquiries as $inquiry)
                        <tr class="hover:bg-slate-50/80 transition-colors {{ $inquiry->status === 'pending' ? 'bg-amber-50/30 font-semibold' : '' }}">
                            <td class="py-3 px-4 text-slate-600 whitespace-nowrap">
                                {{ $inquiry->created_at?->format('M d, Y h:i A') ?? 'N/A' }}
                            </td>
                            <td class="py-3 px-4">
                                <div class="text-slate-900">{{ $inquiry->name }}</div>
                                <div class="text-[11px] text-emerald-700 font-mono font-medium flex items-center gap-1 mt-0.5">
                                    <svg class="w-3 h-3 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                                    </svg>
                                    <span>{{ $inquiry->phone }}</span>
                                </div>
                                @if($inquiry->email)
                                    <div class="text-[11px] text-slate-500 font-normal">{{ $inquiry->email }}</div>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                @if($inquiry->product)
                                    <a href="{{ route('products.show', $inquiry->product->slug) }}" target="_blank" class="text-slate-900 font-medium hover:text-amber-700 hover:underline flex items-center gap-1">
                                        <span>{{ $inquiry->product_name }}</span>
                                        <svg class="w-3 h-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                        </svg>
                                    </a>
                                @else
                                    <span class="text-slate-900 font-medium">{{ $inquiry->product_name }}</span>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-amber-100 text-amber-900 border border-amber-200">
                                    {{ number_format($inquiry->quantity) }} units
                                </span>
                            </td>
                            <td class="py-3 px-4 text-slate-500 font-normal max-w-xs truncate">
                                {{ $inquiry->message ? Str::limit($inquiry->message, 70) : '—' }}
                            </td>
                            <td class="py-3 px-4">
                                @if ($inquiry->status === 'pending')
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase bg-amber-100 text-amber-800 border border-amber-300">
                                        Pending
                                    </span>
                                @elseif ($inquiry->status === 'contacted')
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase bg-blue-50 text-blue-700 border border-blue-200">
                                        Contacted
                                    </span>
                                @elseif ($inquiry->status === 'quoted')
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase bg-purple-50 text-purple-700 border border-purple-200">
                                        Quoted
                                    </span>
                                @elseif ($inquiry->status === 'completed')
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        Completed
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-medium uppercase bg-slate-100 text-slate-700 border border-slate-200">
                                        Cancelled
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-right space-x-2 whitespace-nowrap">
                                @php
                                    $cleanPhone = preg_replace('/[^0-9]/', '', $inquiry->phone);
                                    if (strlen($cleanPhone) === 10) {
                                        $cleanPhone = '91' . $cleanPhone;
                                    }
                                    $waText = rawurlencode("Namaste " . $inquiry->name . ", thank you for your bulk inquiry regarding *" . $inquiry->product_name . "* (" . $inquiry->quantity . " units) on Haridwar Bliss. We are pleased to assist you with special wholesale pricing.");
                                @endphp
                                <a href="https://wa.me/{{ $cleanPhone }}?text={{ $waText }}" target="_blank"
                                   class="inline-flex items-center gap-1 text-emerald-700 hover:text-emerald-800 font-medium"
                                   title="Chat on WhatsApp">
                                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                                    </svg>
                                    <span>WhatsApp</span>
                                </a>
                                <a href="{{ route('admin.bulk-inquiries.show', $inquiry->id) }}" class="text-amber-700 hover:text-amber-800 font-medium">
                                    View
                                </a>
                                <form method="POST" action="{{ route('admin.bulk-inquiries.destroy', $inquiry->id) }}" class="inline" onsubmit="return confirm('Delete this bulk inquiry?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-slate-400 hover:text-rose-600 transition">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400">
                                No bulk product enquiries found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($inquiries->hasPages())
            <div class="p-4 border-t border-slate-200 bg-white">
                {{ $inquiries->links() }}
            </div>
        @endif
    </div>
</x-admin-layout>


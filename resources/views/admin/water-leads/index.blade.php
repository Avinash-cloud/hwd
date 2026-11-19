<x-admin-layout title="Kumbh Water Collection Leads">
    <x-slot name="header">Kumbh Water Collection Leads</x-slot>

    <!-- Page Header & Filters -->
    <div class="mb-6 space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-xl font-semibold text-slate-900 flex items-center gap-2">
                    <span>Haridwar Kumbh Water Collection Leads</span>
                    <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-amber-100 text-amber-800">
                        {{ $counts['total'] }} Total
                    </span>
                </h1>
                <p class="text-xs text-slate-500 mt-0.5">
                    Devotee requests for holy Ganga Jal pickup from Haridwar hotels / dharamshalas and delivery to resident addresses.
                </p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('admin.water-collection-packages.index') }}"
                   class="px-3 py-1.5 bg-amber-100 hover:bg-amber-200 text-amber-900 text-xs font-semibold rounded-lg transition flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-amber-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>Manage Water Pricing & Tiers</span>
                </a>

                <!-- Search Form -->
                <form method="GET" action="{{ route('admin.water-collection-leads.index') }}" class="flex items-center gap-2">
                    <input type="hidden" name="status" value="{{ request('status') }}">
                    <div class="relative">
                        <input type="text"
                               name="search"
                               value="{{ request('search') }}"
                               placeholder="Search lead #, name, phone, hotel..."
                               class="w-64 pl-9 pr-3 py-1.5 text-xs bg-white border border-slate-300 rounded-lg text-slate-800 placeholder-slate-400 focus:border-amber-600 focus:ring-1 focus:ring-amber-600" />
                        <svg class="w-4 h-4 text-slate-400 absolute left-2.5 top-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <button type="submit" class="px-3.5 py-1.5 bg-slate-900 hover:bg-slate-800 text-white text-xs font-medium rounded-lg transition cursor-pointer">
                        Search
                    </button>
                    @if(request('search'))
                        <a href="{{ route('admin.water-collection-leads.index', ['status' => request('status')]) }}" class="px-2 py-1.5 text-slate-500 hover:text-slate-800 text-xs">
                            Clear
                        </a>
                    @endif
                </form>
            </div>
        </div>

        <!-- Metric Counter Cards -->
        <div class="grid grid-cols-2 sm:grid-cols-6 gap-3 pt-1">
            <div class="bg-white p-3 rounded-xl border border-slate-200">
                <span class="text-[11px] font-medium text-slate-500 block">Pending</span>
                <span class="text-lg font-bold text-amber-600">{{ $counts['pending'] }}</span>
            </div>
            <div class="bg-white p-3 rounded-xl border border-slate-200">
                <span class="text-[11px] font-medium text-slate-500 block">Contacted</span>
                <span class="text-lg font-bold text-blue-600">{{ $counts['contacted'] }}</span>
            </div>
            <div class="bg-white p-3 rounded-xl border border-slate-200">
                <span class="text-[11px] font-medium text-slate-500 block">Scheduled</span>
                <span class="text-lg font-bold text-indigo-600">{{ $counts['scheduled'] }}</span>
            </div>
            <div class="bg-white p-3 rounded-xl border border-slate-200">
                <span class="text-[11px] font-medium text-slate-500 block">Collected</span>
                <span class="text-lg font-bold text-purple-600">{{ $counts['collected'] }}</span>
            </div>
            <div class="bg-white p-3 rounded-xl border border-slate-200">
                <span class="text-[11px] font-medium text-slate-500 block">Dispatched</span>
                <span class="text-lg font-bold text-orange-600">{{ $counts['dispatched'] }}</span>
            </div>
            <div class="bg-white p-3 rounded-xl border border-slate-200">
                <span class="text-[11px] font-medium text-slate-500 block">Delivered</span>
                <span class="text-lg font-bold text-emerald-600">{{ $counts['delivered'] }}</span>
            </div>
        </div>

        <!-- Status Filter Tabs -->
        <div class="flex items-center gap-1.5 border-b border-slate-200 pb-3 overflow-x-auto scrollbar-none">
            <a href="{{ route('admin.water-collection-leads.index', ['search' => request('search')]) }}"
               class="px-3 py-1.5 rounded-lg text-xs font-medium transition whitespace-nowrap {{ !request('status') ? 'bg-amber-50 text-amber-900 font-semibold border border-amber-200' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                All ({{ $counts['total'] }})
            </a>
            <a href="{{ route('admin.water-collection-leads.index', ['status' => 'pending', 'search' => request('search')]) }}"
               class="px-3 py-1.5 rounded-lg text-xs font-medium transition flex items-center gap-1.5 whitespace-nowrap {{ request('status') === 'pending' ? 'bg-amber-50 text-amber-900 font-semibold border border-amber-200' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                <span>Pending</span>
                @if($counts['pending'] > 0)
                    <span class="px-1.5 py-0.2 rounded-full text-[10px] font-bold bg-amber-600 text-white">{{ $counts['pending'] }}</span>
                @endif
            </a>
            <a href="{{ route('admin.water-collection-leads.index', ['status' => 'contacted', 'search' => request('search')]) }}"
               class="px-3 py-1.5 rounded-lg text-xs font-medium transition whitespace-nowrap {{ request('status') === 'contacted' ? 'bg-blue-50 text-blue-900 font-semibold border border-blue-200' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                Contacted
            </a>
            <a href="{{ route('admin.water-collection-leads.index', ['status' => 'scheduled', 'search' => request('search')]) }}"
               class="px-3 py-1.5 rounded-lg text-xs font-medium transition whitespace-nowrap {{ request('status') === 'scheduled' ? 'bg-indigo-50 text-indigo-900 font-semibold border border-indigo-200' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                Scheduled
            </a>
            <a href="{{ route('admin.water-collection-leads.index', ['status' => 'collected', 'search' => request('search')]) }}"
               class="px-3 py-1.5 rounded-lg text-xs font-medium transition whitespace-nowrap {{ request('status') === 'collected' ? 'bg-purple-50 text-purple-900 font-semibold border border-purple-200' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                Collected
            </a>
            <a href="{{ route('admin.water-collection-leads.index', ['status' => 'dispatched', 'search' => request('search')]) }}"
               class="px-3 py-1.5 rounded-lg text-xs font-medium transition whitespace-nowrap {{ request('status') === 'dispatched' ? 'bg-orange-50 text-orange-900 font-semibold border border-orange-200' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                Dispatched
            </a>
            <a href="{{ route('admin.water-collection-leads.index', ['status' => 'delivered', 'search' => request('search')]) }}"
               class="px-3 py-1.5 rounded-lg text-xs font-medium transition whitespace-nowrap {{ request('status') === 'delivered' ? 'bg-emerald-50 text-emerald-900 font-semibold border border-emerald-200' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                Delivered
            </a>
            <a href="{{ route('admin.water-collection-leads.index', ['status' => 'cancelled', 'search' => request('search')]) }}"
               class="px-3 py-1.5 rounded-lg text-xs font-medium transition whitespace-nowrap {{ request('status') === 'cancelled' ? 'bg-rose-50 text-rose-900 font-semibold border border-rose-200' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                Cancelled
            </a>
        </div>
    </div>

    <!-- Leads Table -->
    <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 font-semibold text-[11px] uppercase tracking-wider">
                        <th class="py-3 px-4">Date / Ref #</th>
                        <th class="py-3 px-4">Devotee</th>
                        <th class="py-3 px-4">Haridwar Hotel / Pickup</th>
                        <th class="py-3 px-4">Delivery Resident Address</th>
                        <th class="py-3 px-4">Water Quantity</th>
                        <th class="py-3 px-4">Price</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($leads as $lead)
                        <tr class="hover:bg-slate-50/80 transition-colors {{ $lead->status === 'pending' ? 'bg-amber-50/30' : '' }}">
                            <td class="py-3 px-4 whitespace-nowrap">
                                <div class="font-mono font-bold text-slate-900">{{ $lead->lead_number }}</div>
                                <div class="text-[11px] text-slate-500">{{ $lead->created_at?->format('M d, Y h:i A') }}</div>
                            </td>

                            <td class="py-3 px-4">
                                <div class="font-semibold text-slate-900">{{ $lead->name }}</div>
                                <div class="text-[11px] text-emerald-700 font-mono font-medium flex items-center gap-1 mt-0.5">
                                    @php
                                        $cleanPhone = preg_replace('/[^0-9]/', '', $lead->mobile);
                                    @endphp
                                    <a href="https://wa.me/{{ $cleanPhone }}" target="_blank" class="hover:underline flex items-center gap-1 text-emerald-700">
                                        <span>💬 {{ $lead->mobile }}</span>
                                    </a>
                                </div>
                                @if($lead->email)
                                    <div class="text-[11px] text-slate-500">{{ $lead->email }}</div>
                                @endif
                            </td>

                            <td class="py-3 px-4 max-w-xs">
                                <div class="font-semibold text-slate-800">{{ $lead->hotel_or_dharamshala }}</div>
                                @if($lead->pickup_room_or_landmark)
                                    <div class="text-[11px] text-slate-500">{{ $lead->pickup_room_or_landmark }}</div>
                                @endif
                                @if($lead->pickup_date)
                                    <div class="text-[10px] text-amber-800 font-semibold mt-0.5">Pickup: {{ $lead->pickup_date->format('M d, Y') }}</div>
                                @endif
                            </td>

                            <td class="py-3 px-4 max-w-xs">
                                <div class="text-slate-800 truncate">{{ $lead->delivery_address }}</div>
                                <div class="text-[11px] text-slate-500">
                                    {{ implode(', ', array_filter([$lead->delivery_city, $lead->delivery_state, $lead->delivery_pincode])) }}
                                </div>
                            </td>

                            <td class="py-3 px-4 whitespace-nowrap">
                                <div class="font-bold text-[#881337]">{{ $lead->water_quantity }}</div>
                                <div class="text-[11px] text-slate-500">{{ $lead->package_name }}</div>
                            </td>

                            <td class="py-3 px-4 whitespace-nowrap">
                                <div class="font-extrabold text-slate-900">₹{{ number_format($lead->total_price, 2) }}</div>
                                <div class="mt-0.5">
                                    <span class="px-1.5 py-0.5 rounded text-[10px] font-semibold border uppercase tracking-wider {{ $lead->payment_status_badge_classes }}">
                                        @if($lead->payment_status === 'paid')
                                            ✓ Paid (Razorpay)
                                        @elseif($lead->payment_status === 'cod')
                                            COD (At Pickup)
                                        @else
                                            {{ ucfirst($lead->payment_status ?? 'Pending') }}
                                        @endif
                                    </span>
                                </div>
                            </td>

                            <td class="py-3 px-4 whitespace-nowrap">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold border uppercase tracking-wider {{ $lead->status_badge_classes }}">
                                    {{ $lead->status }}
                                </span>
                            </td>

                            <td class="py-3 px-4 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.water-collection-leads.show', $lead) }}"
                                       class="px-2.5 py-1 bg-amber-50 hover:bg-amber-100 text-amber-900 font-medium rounded-md transition text-xs">
                                        View & Manage
                                    </a>

                                    <form method="POST" action="{{ route('admin.water-collection-leads.destroy', $lead) }}"
                                          onsubmit="return confirm('Are you sure you want to delete lead #{{ $lead->lead_number }}?');"
                                          class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1 text-slate-400 hover:text-rose-600 transition cursor-pointer" title="Delete Lead">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-8 text-center text-slate-500">
                                <div class="space-y-1">
                                    <span class="block text-xl">🌊</span>
                                    <span class="font-medium">No Kumbh water collection leads found.</span>
                                    @if(request('search') || request('status'))
                                        <p class="text-xs text-slate-400">Try clearing your search query or status filter.</p>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($leads->hasPages())
            <div class="p-4 border-t border-slate-200">
                {{ $leads->links() }}
            </div>
        @endif
    </div>
</x-admin-layout>

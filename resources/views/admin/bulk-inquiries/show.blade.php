<x-admin-layout title="Bulk Inquiry #{{ $inquiry->id }} - {{ $inquiry->name }}">
    <x-slot name="header">Bulk Order Inquiry Details</x-slot>

    <!-- Top Navigation / Title -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('admin.bulk-inquiries.index') }}" class="text-xs text-slate-500 hover:text-slate-800 transition inline-flex items-center gap-1 font-medium">
                    ← Back to Bulk Enquiries
                </a>
            </div>
            <h1 class="text-xl font-semibold text-slate-900">
                Bulk Inquiry for {{ $inquiry->product_name }}
            </h1>
            <p class="text-xs text-slate-500 mt-0.5">Submitted on {{ $inquiry->created_at?->format('M d, Y \a\t h:i A') ?? 'N/A' }}</p>
        </div>

        <div class="flex items-center gap-2">
            @php
                $cleanPhone = preg_replace('/[^0-9]/', '', $inquiry->phone);
                if (strlen($cleanPhone) === 10) {
                    $cleanPhone = '91' . $cleanPhone;
                }
                $waText = rawurlencode("Namaste " . $inquiry->name . ", thank you for your bulk inquiry regarding *" . $inquiry->product_name . "* (" . $inquiry->quantity . " units) on Haridwar Bliss. We are pleased to assist you with special wholesale pricing.");
            @endphp
            <a href="https://wa.me/{{ $cleanPhone }}?text={{ $waText }}" target="_blank"
               class="px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-medium transition inline-flex items-center gap-1.5 shadow-xs">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                </svg>
                <span>WhatsApp</span>
            </a>

            <form method="POST" action="{{ route('admin.bulk-inquiries.destroy', $inquiry->id) }}" onsubmit="return confirm('Delete this inquiry permanently?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-3 py-1.5 bg-white text-rose-600 border border-rose-200 hover:bg-rose-50 rounded-lg text-xs font-medium transition">
                    Delete
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 text-xs">
        <!-- Main Details (2 cols) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Order Specifications Card -->
            <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-xs space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h2 class="text-sm font-semibold text-slate-900">Requested Product & Quantity</h2>
                    @if ($inquiry->status === 'pending')
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-amber-100 text-amber-800 border border-amber-300">
                            Pending
                        </span>
                    @elseif ($inquiry->status === 'contacted')
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-blue-50 text-blue-700 border border-blue-200">
                            Contacted
                        </span>
                    @elseif ($inquiry->status === 'quoted')
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-purple-50 text-purple-700 border border-purple-200">
                            Quoted
                        </span>
                    @elseif ($inquiry->status === 'completed')
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-emerald-50 text-emerald-700 border border-emerald-200">
                            Completed
                        </span>
                    @else
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-medium uppercase bg-slate-100 text-slate-700 border border-slate-200">
                            Cancelled
                        </span>
                    @endif
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="p-4 bg-amber-50/50 rounded-xl border border-amber-100">
                        <span class="text-[11px] font-medium text-amber-800 block">Requested Product</span>
                        <div class="text-sm font-bold text-slate-900 mt-1">
                            {{ $inquiry->product_name }}
                        </div>
                        @if($inquiry->product)
                            <a href="{{ route('products.show', $inquiry->product->slug) }}" target="_blank"
                               class="text-xs text-amber-700 hover:text-amber-800 hover:underline inline-flex items-center gap-1 mt-1 font-medium">
                                <span>View Product Page</span>
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                </svg>
                            </a>
                        @endif
                    </div>

                    <div class="p-4 bg-slate-50 rounded-xl border border-slate-100">
                        <span class="text-[11px] font-medium text-slate-500 block">Requested Quantity</span>
                        <div class="text-2xl font-black text-amber-600 mt-0.5">
                            {{ number_format($inquiry->quantity) }} <span class="text-xs font-semibold text-slate-600">units</span>
                        </div>
                        <p class="text-[10px] text-slate-400 mt-1">Special bulk pricing tier applicable</p>
                    </div>
                </div>

                <div>
                    <h3 class="text-xs font-semibold text-slate-700 mb-2">Devotee Message / Specific Requirements</h3>
                    <div class="p-4 bg-slate-50 rounded-xl border border-slate-100 text-slate-800 leading-relaxed whitespace-pre-line text-sm">
                        {{ $inquiry->message ?: 'No additional message provided.' }}
                    </div>
                </div>
            </div>

            <!-- Admin Status & Notes Update -->
            <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-xs">
                <h2 class="text-sm font-semibold text-slate-900 pb-3 border-b border-slate-100 mb-4">Internal Workflow & Notes</h2>
                <form method="POST" action="{{ route('admin.bulk-inquiries.update', $inquiry->id) }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block font-medium text-slate-700 mb-1">Status</label>
                        <select name="status" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs focus:ring-1 focus:ring-amber-500 focus:border-amber-500">
                            <option value="pending" {{ $inquiry->status === 'pending' ? 'selected' : '' }}>Pending (Needs Contact)</option>
                            <option value="contacted" {{ $inquiry->status === 'contacted' ? 'selected' : '' }}>Contacted (In Discussion)</option>
                            <option value="quoted" {{ $inquiry->status === 'quoted' ? 'selected' : '' }}>Quoted (Wholesale Price Sent)</option>
                            <option value="completed" {{ $inquiry->status === 'completed' ? 'selected' : '' }}>Completed (Order Placed / Finalized)</option>
                            <option value="cancelled" {{ $inquiry->status === 'cancelled' ? 'selected' : '' }}>Cancelled / Dropped</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-medium text-slate-700 mb-1">Admin Notes</label>
                        <textarea name="admin_notes" rows="4" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs focus:ring-1 focus:ring-amber-500 focus:border-amber-500"
                                  placeholder="Record quotation details, special shipping arrangements, batch allocated, WhatsApp notes...">{{ old('admin_notes', $inquiry->admin_notes) }}</textarea>
                    </div>

                    <div class="pt-2 flex justify-end">
                        <button type="submit" class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white font-medium rounded-lg text-xs shadow-xs transition">
                            Save Notes & Status
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sidebar / Contact Details (1 col) -->
        <div class="space-y-6">
            <!-- Client Contact Details -->
            <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-xs space-y-4">
                <h2 class="text-sm font-semibold text-slate-900 pb-2 border-b border-slate-100">
                    Client Contact Details
                </h2>

                <div class="space-y-3">
                    <div>
                        <span class="text-[11px] text-slate-400 uppercase font-semibold">Name</span>
                        <p class="text-slate-900 font-medium text-sm">{{ $inquiry->name }}</p>
                    </div>

                    <div>
                        <span class="text-[11px] text-slate-400 uppercase font-semibold">Phone / WhatsApp Number</span>
                        <div class="flex items-center gap-2 mt-0.5">
                            <span class="text-slate-900 font-mono font-medium text-sm">{{ $inquiry->phone }}</span>
                        </div>
                    </div>

                    @if($inquiry->email)
                        <div>
                            <span class="text-[11px] text-slate-400 uppercase font-semibold">Email</span>
                            <p class="text-slate-900">{{ $inquiry->email }}</p>
                        </div>
                    @endif

                    @if($inquiry->ip_address)
                        <div>
                            <span class="text-[11px] text-slate-400 uppercase font-semibold">Submission IP</span>
                            <p class="text-slate-500 font-mono text-[11px]">{{ $inquiry->ip_address }}</p>
                        </div>
                    @endif
                </div>

                <div class="pt-3 border-t border-slate-100 flex flex-col gap-2">
                    <a href="https://wa.me/{{ $cleanPhone }}?text={{ $waText }}" target="_blank"
                       class="w-full py-2 px-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-medium transition flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                        </svg>
                        <span>Direct WhatsApp</span>
                    </a>

                    <a href="tel:{{ $inquiry->phone }}"
                       class="w-full py-2 px-3 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 rounded-lg text-xs font-medium transition flex items-center justify-center gap-2">
                        <svg class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                        <span>Call Devotee</span>
                    </a>

                    @if($inquiry->email)
                        <a href="mailto:{{ $inquiry->email }}?subject=Haridwar Bliss - Bulk Inquiry for {{ rawurlencode($inquiry->product_name) }}"
                           class="w-full py-2 px-3 bg-slate-900 hover:bg-slate-800 text-white rounded-lg text-xs font-medium transition flex items-center justify-center gap-2">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <span>Send Email</span>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>


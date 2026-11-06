<x-admin-layout title="Memberships">
    <x-slot name="header">Memberships</x-slot>

    <!-- Page Header & Filters -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-xl font-semibold text-slate-900">5-Year Devotee Memberships ({{ $memberships->total() }})</h1>
            <p class="text-xs text-slate-500 mt-0.5">Devotees entitled to monthly ₹0 Gangajal deliveries and exclusive catalog access.</p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('admin.membership-benefits.index') }}"
               class="px-3.5 py-1.5 bg-amber-600 hover:bg-amber-700 text-white rounded-lg text-xs font-medium transition inline-flex items-center gap-1.5 shadow-xs">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                <span>Manage Monthly Benefits (0-N)</span>
            </a>

            <div class="flex items-center gap-1.5 border-b sm:border-b-0 pb-2 sm:pb-0">
                <a href="{{ route('admin.memberships.index') }}"
                   class="px-3 py-1.5 rounded-lg text-xs font-medium transition {{ !request('status') ? 'bg-amber-50 text-amber-900 font-semibold border border-amber-200' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                    All
                </a>
                <a href="{{ route('admin.memberships.index', ['status' => 'active']) }}"
                   class="px-3 py-1.5 rounded-lg text-xs font-medium transition {{ request('status') === 'active' ? 'bg-amber-50 text-amber-900 font-semibold border border-amber-200' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                    Active
                </a>
                <a href="{{ route('admin.memberships.index', ['status' => 'cancelled']) }}"
                   class="px-3 py-1.5 rounded-lg text-xs font-medium transition {{ request('status') === 'cancelled' ? 'bg-amber-50 text-amber-900 font-semibold border border-amber-200' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                    Cancelled
                </a>
            </div>
        </div>
    </div>

    <!-- Memberships Table -->
    <div class="bg-white border border-slate-200 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 font-semibold text-[11px] uppercase tracking-wider">
                        <th class="py-3 px-4">Devotee</th>
                        <th class="py-3 px-4">Contact</th>
                        <th class="py-3 px-4">Fee Paid</th>
                        <th class="py-3 px-4">Validity Period</th>
                        <th class="py-3 px-4 text-center">Days Left</th>
                        <th class="py-3 px-4 text-center">Status</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($memberships as $m)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-3 px-4 font-medium text-slate-900">
                                {{ $m->user->name }}
                            </td>
                            <td class="py-3 px-4">
                                <div class="text-slate-700">{{ $m->user->email }}</div>
                                <div class="text-[11px] text-slate-400">{{ $m->user->phone ?? 'No phone' }}</div>
                            </td>
                            <td class="py-3 px-4 font-mono font-medium text-slate-900">
                                ₹{{ number_format($m->fee_paid, 2) }}
                            </td>
                            <td class="py-3 px-4 text-slate-600">
                                {{ $m->starts_at->format('d M Y') }} → {{ $m->expires_at->format('d M Y') }}
                            </td>
                            <td class="py-3 px-4 text-center font-mono">
                                @if ($m->expires_at->isFuture())
                                    <span class="px-2 py-0.5 rounded text-[11px] font-medium bg-slate-100 text-slate-700 border border-slate-200">
                                        {{ (int) now()->diffInDays($m->expires_at) }} days
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded text-[11px] font-medium bg-rose-50 text-rose-700 border border-rose-200">
                                        Expired
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center">
                                @if ($m->status === 'active')
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-medium uppercase bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        Active
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-medium uppercase bg-rose-50 text-rose-700 border border-rose-200">
                                        Cancelled
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-right">
                                <a href="{{ route('membership.invoice', $m->id) }}" target="_blank" class="text-xs text-amber-700 hover:text-amber-900 font-semibold mr-3 transition inline-flex items-center gap-1">
                                    <span>Invoice</span>
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                </a>
                                <form method="POST" action="{{ route('admin.memberships.toggle', $m->id) }}" class="inline">
                                    @csrf
                                    <button type="submit"
                                            class="text-xs {{ $m->status === 'active' ? 'text-slate-500 hover:text-red-600' : 'text-amber-700 hover:text-amber-800' }} font-medium transition"
                                            onclick="return confirm('Toggle status for this member?')">
                                        {{ $m->status === 'active' ? 'Deactivate' : 'Reactivate' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400">
                                No memberships found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($memberships->hasPages())
            <div class="p-4 border-t border-slate-200 bg-white">
                {{ $memberships->links() }}
            </div>
        @endif
    </div>
</x-admin-layout>

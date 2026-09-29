<x-admin-layout title="Manage Memberships">
    <x-slot name="header">
        5-Year Sacred Devotee Memberships
    </x-slot>

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-xl font-serif font-bold text-white">All Memberships ({{ $memberships->total() }})</h2>
            <p class="text-xs text-slate-400 mt-1">Devotees entitled to monthly ₹0 Gangajal deliveries and exclusive catalog access.</p>
        </div>

        <div class="flex items-center gap-2 text-xs">
            <a href="{{ route('admin.memberships.index') }}" class="px-3 py-1.5 rounded-lg font-bold {{ !request('status') ? 'bg-[#D97706] text-white' : 'bg-slate-800 text-slate-300' }}">All</a>
            <a href="{{ route('admin.memberships.index', ['status' => 'active']) }}" class="px-3 py-1.5 rounded-lg font-bold {{ request('status') === 'active' ? 'bg-[#D97706] text-white' : 'bg-slate-800 text-slate-300' }}">Active</a>
            <a href="{{ route('admin.memberships.index', ['status' => 'cancelled']) }}" class="px-3 py-1.5 rounded-lg font-bold {{ request('status') === 'cancelled' ? 'bg-[#D97706] text-white' : 'bg-slate-800 text-slate-300' }}">Cancelled</a>
        </div>
    </div>

    <div class="bg-slate-950/80 border border-slate-800 rounded-3xl p-6 shadow-sm overflow-x-auto">
        <table class="w-full text-left text-xs">
            <thead>
                <tr class="text-slate-400 border-b border-slate-800 font-mono text-[11px]">
                    <th class="pb-3">Devotee</th>
                    <th class="pb-3">Contact</th>
                    <th class="pb-3">Fee Paid</th>
                    <th class="pb-3">Validity</th>
                    <th class="pb-3">Days Left</th>
                    <th class="pb-3">Status</th>
                    <th class="pb-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-800/80">
                @foreach ($memberships as $m)
                    <tr>
                        <td class="py-3 font-bold text-white">
                            {{ $m->user->name }}
                        </td>
                        <td class="py-3">
                            <span class="block text-slate-300">{{ $m->user->email }}</span>
                            <span class="block text-[10px] text-slate-500">{{ $m->user->phone ?? 'No phone' }}</span>
                        </td>
                        <td class="py-3 font-mono font-bold text-[#D97706]">₹{{ number_format($m->fee_paid, 2) }}</td>
                        <td class="py-3 text-slate-400">
                            {{ $m->starts_at->format('d M Y') }} → {{ $m->expires_at->format('d M Y') }}
                        </td>
                        <td class="py-3">
                            @if ($m->expires_at->isFuture())
                                <span class="font-mono text-[#0369A1] font-bold">{{ (int) now()->diffInDays($m->expires_at) }} days</span>
                            @else
                                <span class="text-[#991B1B] font-bold">Expired</span>
                            @endif
                        </td>
                        <td class="py-3">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $m->status === 'active' ? 'bg-[#0369A1]/20 text-[#0369A1] border border-[#0369A1]/40' : 'bg-[#991B1B]/20 text-[#991B1B] border border-[#991B1B]/40' }}">
                                {{ $m->status }}
                            </span>
                        </td>
                        <td class="py-3 text-right">
                            <form method="POST" action="{{ route('admin.memberships.toggle', $m->id) }}" class="inline">
                                @csrf
                                <button type="submit" class="text-xs text-[#D97706] hover:underline" onclick="return confirm('Toggle status for this member?')">
                                    {{ $m->status === 'active' ? 'Deactivate' : 'Reactivate' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-6">
            {{ $memberships->links() }}
        </div>
    </div>
</x-admin-layout>

<x-admin-layout title="Memberships">
    <x-slot name="header">Memberships</x-slot>

    <!-- Page Header & Filters -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-xl font-semibold text-slate-900">5-Year Devotee Memberships ({{ $memberships->total() }})</h1>
            <p class="text-xs text-slate-500 mt-0.5">Manage online &amp; offline memberships, recurring Razorpay mandates, and monthly NimbusPost dispatches (₹{{ number_format($monthlyAutoDebit ?? 149) }}/mo).</p>
        </div>

        <div class="flex flex-wrap items-center gap-2.5">
            <a href="{{ route('admin.memberships.offline.create') }}"
               class="px-3.5 py-1.5 bg-[#881337] hover:bg-[#6B0E29] text-white rounded-lg text-xs font-medium transition inline-flex items-center gap-1.5 shadow-xs">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg>
                <span>+ Onboard Offline Member / CSV</span>
            </a>

            <a href="{{ route('admin.membership-benefits.index') }}"
               class="px-3.5 py-1.5 bg-amber-600 hover:bg-amber-700 text-white rounded-lg text-xs font-medium transition inline-flex items-center gap-1.5 shadow-xs">
                <span>Monthly Benefits (0-N)</span>
            </a>

            <div class="flex items-center gap-1.5">
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

    <!-- Search Bar -->
    <form method="GET" action="{{ route('admin.memberships.index') }}" class="mb-4 flex items-center gap-2 max-w-md">
        @if (request('status'))
            <input type="hidden" name="status" value="{{ request('status') }}">
        @endif
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name, email, phone, invoice, receipt..."
               class="flex-1 px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 focus:border-amber-600 focus:ring-1 focus:ring-amber-600" />
        <button type="submit" class="px-3.5 py-2 bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold rounded-lg transition">
            Search
        </button>
    </form>

    @if (session('import_errors'))
        <div class="mb-5 p-4 rounded-xl bg-amber-50 border border-amber-200 text-xs text-amber-950 space-y-1">
            <strong class="block font-semibold">CSV Row Warnings:</strong>
            <ul class="list-disc pl-5 space-y-0.5">
                @foreach (session('import_errors') as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Memberships Table -->
    <div class="bg-white border border-slate-200 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 font-semibold text-[11px] uppercase tracking-wider">
                        <th class="py-3 px-4">Devotee</th>
                        <th class="py-3 px-4">Contact</th>
                        <th class="py-3 px-4">Fee &amp; Mode</th>
                        <th class="py-3 px-4">Validity Period</th>
                        <th class="py-3 px-4 text-center">Monthly Mandate</th>
                        <th class="py-3 px-4 text-center">Status</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($memberships as $m)
                        @php($sub = $m->activeSubscription)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-3 px-4 font-medium text-slate-900">
                                <div>{{ $m->user->name }}</div>
                                <div class="text-[10px] font-mono text-slate-400">{{ $m->resolved_invoice_number }}</div>
                            </td>
                            <td class="py-3 px-4">
                                <div class="text-slate-700">{{ $m->user->email }}</div>
                                <div class="text-[11px] text-slate-400">{{ $m->user->phone ?? 'No phone' }}</div>
                            </td>
                            <td class="py-3 px-4 font-mono font-medium text-slate-900">
                                <div>₹{{ number_format($m->fee_paid, 2) }}</div>
                                <div class="text-[10px] font-sans uppercase text-slate-500">
                                    {{ str_replace('_', ' ', (string) ($m->payment_method ?? 'online')) }}
                                </div>
                            </td>
                            <td class="py-3 px-4 text-slate-600">
                                <div>{{ $m->starts_at->format('d M Y') }} &rarr; {{ $m->expires_at->format('d M Y') }}</div>
                                <div class="text-[10px] font-mono text-slate-400">
                                    {{ $m->expires_at->isFuture() ? ((int) now()->diffInDays($m->expires_at)) . ' days left' : 'Expired' }}
                                </div>
                            </td>
                            <td class="py-3 px-4 text-center">
                                @if ($sub && $sub->isRazorpayMandate())
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        Razorpay AutoPay
                                    </span>
                                @elseif ($sub && $sub->isOfflinePrepaid())
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-amber-50 text-amber-800 border border-amber-200">
                                        Offline{{ $sub->prepaid_until ? ' · Prepaid ' . $sub->prepaid_until->format('d M Y') : '' }}
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                                        No Auto-Debit Yet
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
                            <td class="py-3 px-4 text-right whitespace-nowrap space-x-2">
                                <a href="{{ route('membership.invoice', $m->id) }}" target="_blank" class="text-xs text-amber-700 hover:text-amber-900 font-semibold transition inline-flex items-center gap-1">
                                    <span>Invoice</span>
                                </a>
                                @if ($m->isActive() && (! $sub || ! $sub->isRazorpayMandate()))
                                    <form method="POST" action="{{ route('admin.memberships.dispatchMonthly', $m->id) }}" class="inline">
                                        @csrf
                                        <button type="submit"
                                                class="text-xs text-sky-700 hover:text-sky-900 font-semibold transition"
                                                onclick="return confirm('Create & book current month Gangajal shipment via NimbusPost for this member?')">
                                            Dispatch Bottle
                                        </button>
                                    </form>
                                @endif
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

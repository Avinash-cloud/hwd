<x-admin-layout title="Coupons & Marketing">
    <x-slot name="header">Coupons</x-slot>

    <!-- Page Header & Actions -->
    <div class="mb-6 space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-xl font-semibold text-slate-900">Promotional Coupons & Marketing</h1>
                <p class="text-xs text-slate-500 mt-0.5">Create and distribute promotional codes with fixed or percentage discounts.</p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('admin.coupons.create') }}"
                   class="px-3.5 py-1.5 bg-amber-600 hover:bg-amber-700 text-white rounded-lg text-xs font-medium transition inline-flex items-center gap-1.5 shadow-xs">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    <span>Create New Coupon</span>
                </a>
            </div>
        </div>

        <!-- Filter & Search -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-200 pb-3">
            <div class="flex items-center gap-1.5">
                <a href="{{ route('admin.coupons.index') }}"
                   class="px-3 py-1.5 rounded-lg text-xs font-medium transition {{ !request()->has('status') ? 'bg-amber-50 text-amber-900 font-semibold border border-amber-200' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                    All ({{ $coupons->total() }})
                </a>
                <a href="{{ route('admin.coupons.index', ['status' => 'active']) }}"
                   class="px-3 py-1.5 rounded-lg text-xs font-medium transition {{ request('status') === 'active' ? 'bg-amber-50 text-amber-900 font-semibold border border-amber-200' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                    Active
                </a>
                <a href="{{ route('admin.coupons.index', ['status' => 'inactive']) }}"
                   class="px-3 py-1.5 rounded-lg text-xs font-medium transition {{ request('status') === 'inactive' ? 'bg-amber-50 text-amber-900 font-semibold border border-amber-200' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                    Inactive
                </a>
            </div>

            <form method="GET" action="{{ route('admin.coupons.index') }}" class="flex items-center gap-2">
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Search coupon code or name..."
                       class="w-60 px-3 py-1.5 text-xs bg-white border border-slate-300 rounded-lg text-slate-800 placeholder-slate-400 focus:border-amber-600 focus:ring-1 focus:ring-amber-600" />
                <button type="submit" class="px-3 py-1.5 bg-slate-900 hover:bg-slate-800 text-white text-xs font-medium rounded-lg transition">
                    Search
                </button>
            </form>
        </div>
    </div>

    <!-- Coupons Table -->
    <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 font-semibold text-[11px] uppercase tracking-wider">
                        <th class="py-3 px-4">Coupon Code</th>
                        <th class="py-3 px-4">Discount</th>
                        <th class="py-3 px-4">Min Spend</th>
                        <th class="py-3 px-4">Usage</th>
                        <th class="py-3 px-4">Validity</th>
                        <th class="py-3 px-4 text-center">Status</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($coupons as $coupon)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-3 px-4">
                                <div class="font-mono font-bold text-slate-900 text-sm tracking-wider">{{ $coupon->code }}</div>
                                <div class="text-[11px] text-slate-500 font-normal">{{ $coupon->name ?? 'No label' }}</div>
                            </td>
                            <td class="py-3 px-4 font-medium text-slate-900">
                                @if($coupon->discount_type === 'percentage')
                                    <span class="text-amber-700 font-bold font-mono">{{ (int) $coupon->discount_value }}% OFF</span>
                                    @if($coupon->max_discount)
                                        <div class="text-[10px] text-slate-400">Up to ₹{{ number_format($coupon->max_discount, 2) }}</div>
                                    @endif
                                @else
                                    <span class="text-emerald-700 font-bold font-mono">₹{{ number_format($coupon->discount_value, 2) }} OFF</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 font-mono text-slate-600">
                                {{ $coupon->min_spend ? '₹'.number_format($coupon->min_spend, 2) : 'No min' }}
                            </td>
                            <td class="py-3 px-4 font-mono text-slate-600">
                                {{ $coupon->used_count }} {{ $coupon->usage_limit ? '/ '.$coupon->usage_limit : 'uses' }}
                            </td>
                            <td class="py-3 px-4 text-slate-600">
                                @if($coupon->expires_at)
                                    <div>Expires: {{ $coupon->expires_at->format('M d, Y') }}</div>
                                    @if($coupon->expires_at->isPast())
                                        <span class="text-[10px] text-rose-600 font-bold">Expired</span>
                                    @endif
                                @else
                                    <span class="text-slate-400">Lifetime</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center">
                                @if ($coupon->is_active)
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-medium uppercase bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        Active
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-medium uppercase bg-slate-100 text-slate-500 border border-slate-200">
                                        Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-right space-x-2 whitespace-nowrap">
                                <a href="{{ route('admin.coupons.edit', $coupon->id) }}" class="text-amber-700 hover:text-amber-800 font-medium">
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('admin.coupons.toggle', $coupon->id) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="text-slate-500 hover:text-slate-800 transition">
                                        {{ $coupon->is_active ? 'Disable' : 'Enable' }}
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.coupons.destroy', $coupon->id) }}" class="inline" onsubmit="return confirm('Delete this coupon code?')">
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
                                No coupons found. Click "Create New Coupon" to start.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($coupons->hasPages())
            <div class="p-4 border-t border-slate-200 bg-white">
                {{ $coupons->links() }}
            </div>
        @endif
    </div>
</x-admin-layout>


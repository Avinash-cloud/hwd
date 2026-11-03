<x-admin-layout title="Membership Monthly Benefits">
    <x-slot name="header">Membership Benefits</x-slot>

    <!-- Page Header -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('admin.memberships.index') }}" class="text-xs text-slate-500 hover:text-slate-800 transition inline-flex items-center gap-1 font-medium">
                    ← Back to Memberships
                </a>
            </div>
            <h1 class="text-xl font-semibold text-slate-900">Monthly Free Offering Benefit Catalog</h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Select which products active 5-year members can claim for <strong>FREE (₹0)</strong> once per calendar month.
            </p>
        </div>

        <div class="flex items-center gap-2">
            <span class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-amber-50 text-amber-900 border border-amber-200">
                {{ $eligibleCount }} of {{ $products->count() }} Products Eligible
            </span>
        </div>
    </div>

    <!-- Benefits Configuration Form -->
    <form method="POST" action="{{ route('admin.membership-benefits.update') }}">
        @csrf
        <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-xs">
            <div class="p-4 bg-slate-50 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
                <div class="text-slate-600">
                    Check products to allow members to claim 1 free item per month. If multiple eligible products are added to their cart, the system automatically discounts the lower-priced eligible item to ₹0.
                </div>
                <div class="flex items-center gap-2 shrink-0">
                    <button type="button" onclick="document.querySelectorAll('.benefit-checkbox').forEach(c => c.checked = true)"
                            class="px-2.5 py-1 text-xs text-slate-600 hover:text-slate-900 font-medium">
                        Select All
                    </button>
                    <span class="text-slate-300">|</span>
                    <button type="button" onclick="document.querySelectorAll('.benefit-checkbox').forEach(c => c.checked = false)"
                            class="px-2.5 py-1 text-xs text-slate-600 hover:text-slate-900 font-medium">
                        Deselect All
                    </button>
                    <button type="submit"
                            class="px-4 py-1.5 bg-amber-600 hover:bg-amber-700 text-white font-medium rounded-lg text-xs shadow-xs transition ml-2">
                        Save Benefit Catalog
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-200 text-slate-600 font-semibold text-[11px] uppercase tracking-wider">
                            <th class="py-3 px-4 w-12 text-center">Free Quota</th>
                            <th class="py-3 px-4">Product Details</th>
                            <th class="py-3 px-4">Category</th>
                            <th class="py-3 px-4">Price</th>
                            <th class="py-3 px-4">Member Price</th>
                            <th class="py-3 px-4">Stock</th>
                            <th class="py-3 px-4">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($products as $product)
                            <tr class="hover:bg-slate-50/80 transition-colors {{ $product->is_member_free_eligible ? 'bg-amber-50/20' : '' }}">
                                <td class="py-3 px-4 text-center">
                                    <input type="checkbox"
                                           name="eligible_product_ids[]"
                                           value="{{ $product->id }}"
                                           id="p_{{ $product->id }}"
                                           class="benefit-checkbox w-4 h-4 text-amber-600 rounded border-slate-300 focus:ring-amber-500"
                                           {{ $product->is_member_free_eligible ? 'checked' : '' }}>
                                </td>
                                <td class="py-3 px-4">
                                    <label for="p_{{ $product->id }}" class="flex items-center gap-3 cursor-pointer">
                                        <div class="w-10 h-10 rounded-lg bg-slate-100 border border-slate-200 overflow-hidden shrink-0">
                                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                        </div>
                                        <div>
                                            <div class="font-medium text-slate-900">{{ $product->name }}</div>
                                            <div class="text-[11px] text-slate-400">SKU: {{ $product->sku ?? '—' }}</div>
                                        </div>
                                    </label>
                                </td>
                                <td class="py-3 px-4 text-slate-600">
                                    {{ $product->category->name ?? 'Uncategorized' }}
                                </td>
                                <td class="py-3 px-4 font-mono font-medium text-slate-900">
                                    ₹{{ number_format($product->price, 2) }}
                                </td>
                                <td class="py-3 px-4 font-mono text-slate-600">
                                    {{ $product->member_price ? '₹'.number_format($product->member_price, 2) : '—' }}
                                </td>
                                <td class="py-3 px-4 font-mono">
                                    <span class="{{ $product->stock < 10 ? 'text-rose-600 font-bold' : 'text-slate-700' }}">
                                        {{ $product->stock }}
                                    </span>
                                </td>
                                <td class="py-3 px-4">
                                    @if ($product->is_active)
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-medium uppercase bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            Active
                                        </span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-medium uppercase bg-slate-100 text-slate-500 border border-slate-200">
                                            Inactive
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center text-slate-400">
                                    No products found in catalog.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 bg-slate-50 border-t border-slate-200 flex justify-end">
                <button type="submit"
                        class="px-5 py-2 bg-amber-600 hover:bg-amber-700 text-white font-medium rounded-lg text-xs shadow-xs transition">
                    Save Benefit Catalog
                </button>
            </div>
        </div>
    </form>
</x-admin-layout>


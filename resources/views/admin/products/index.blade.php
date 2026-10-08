<x-admin-layout title="Manage Products">
    <x-slot name="header">
        Products & Offerings Catalog
    </x-slot>

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h2 class="text-xl font-serif font-bold text-white">All Products ({{ $products->total() }})</h2>
            <p class="text-xs text-slate-400 mt-1">Manage catalog items, pricing, inventory, homepage feature spots, and bestsellers.</p>
        </div>
        <a href="{{ route('admin.products.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#D97706] hover:bg-[#F97316] text-white font-bold text-xs rounded-xl shadow-md transition">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            <span>+ Add New Product</span>
        </a>
    </div>

    <!-- Filters & Search Bar -->
    <div class="bg-slate-950/80 border border-slate-800 rounded-2xl p-4 mb-6">
        <form method="GET" action="{{ route('admin.products.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3 text-xs">
            <div class="lg:col-span-2">
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Search by name, SKU..."
                       class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:border-[#D97706]" />
            </div>

            <div>
                <select name="category_id" class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white">
                    <option value="">-- All Categories --</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <select name="is_bestseller" class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white">
                    <option value="">-- Bestsellers: All --</option>
                    <option value="1" {{ request('is_bestseller') === '1' ? 'selected' : '' }}>Bestsellers Only</option>
                    <option value="0" {{ request('is_bestseller') === '0' ? 'selected' : '' }}>Non-Bestsellers</option>
                </select>
            </div>

            <div>
                <select name="is_featured" class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white">
                    <option value="">-- Featured: All --</option>
                    <option value="1" {{ request('is_featured') === '1' ? 'selected' : '' }}>Featured Only</option>
                    <option value="0" {{ request('is_featured') === '0' ? 'selected' : '' }}>Non-Featured</option>
                </select>
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="flex-1 px-4 py-2 bg-slate-800 hover:bg-slate-700 text-white font-bold rounded-xl border border-slate-700 transition">
                    Filter
                </button>
                @if (request()->hasAny(['search', 'category_id', 'is_bestseller', 'is_featured', 'is_gangajal']))
                    <a href="{{ route('admin.products.index') }}" class="px-3 py-2 text-slate-400 hover:text-white" title="Reset Filters">
                        ✕
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Products Table -->
    <div class="bg-slate-950/80 border border-slate-800 rounded-3xl p-6 shadow-sm overflow-x-auto">
        <table class="w-full text-left text-xs">
            <thead>
                <tr class="text-slate-400 border-b border-slate-800 font-mono text-[11px]">
                    <th class="pb-3 w-14">Image</th>
                    <th class="pb-3">Product Name & Category</th>
                    <th class="pb-3">Price / MRP</th>
                    <th class="pb-3">Member Price</th>
                    <th class="pb-3">Stock</th>
                    <th class="pb-3 text-center">Featured</th>
                    <th class="pb-3 text-center">Bestseller</th>
                    <th class="pb-3 text-center">Status</th>
                    <th class="pb-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-800/80">
                @forelse ($products as $p)
                    <tr class="hover:bg-slate-900/40 transition">
                        <!-- Product Image Thumbnail -->
                        <td class="py-3 pr-3">
                            <div class="w-12 h-12 rounded-xl overflow-hidden bg-slate-900 border border-slate-700 shrink-0 relative">
                                <img src="{{ $p->image_url }}"
                                     alt="{{ $p->name }}"
                                     class="w-full h-full object-cover">
                            </div>
                        </td>

                        <!-- Name & Category -->
                        <td class="py-3 pr-4 max-w-xs">
                            <div class="flex items-center gap-1.5 flex-wrap">
                                <span class="font-bold text-white text-xs hover:text-[#D97706] transition">
                                    {{ $p->name }}
                                </span>
                                @if ($p->badge)
                                    <span class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-[#D97706]/20 text-amber-300 border border-[#D97706]/40">
                                        {{ $p->badge }}
                                    </span>
                                @endif
                            </div>

                            <div class="flex items-center gap-2 mt-1 text-[11px] text-slate-400">
                                <span>{{ $p->category->name }}</span>
                                @if ($p->sku)
                                    <span>·</span>
                                    <span class="font-mono text-[10px] text-slate-500">{{ $p->sku }}</span>
                                @endif
                                @if ($p->is_gangajal)
                                    <span>·</span>
                                    <span class="text-sky-400 font-semibold">{{ $p->volume_ml }}ml Gangajal</span>
                                @endif
                            </div>
                        </td>

                        <!-- Pricing -->
                        <td class="py-3 pr-3 font-mono">
                            <span class="font-bold text-white">₹{{ number_format($p->price, 0) }}</span>
                            @if ($p->compare_price && $p->compare_price > $p->price)
                                <del class="block text-[10px] text-slate-500">₹{{ number_format($p->compare_price, 0) }}</del>
                            @endif
                        </td>

                        <!-- Member Price -->
                        <td class="py-3 pr-3 font-mono text-[#0369A1]">
                            @if ($p->is_gangajal)
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-[#0369A1]/20 text-sky-300 border border-[#0369A1]/40">
                                    Free 1st / Mo
                                </span>
                            @else
                                <span class="font-bold text-sky-400">₹{{ number_format($p->member_price ?? $p->price, 0) }}</span>
                            @endif
                        </td>

                        <!-- Stock -->
                        <td class="py-3 pr-3">
                            <span class="px-2 py-0.5 rounded-lg text-[10px] font-bold {{ $p->stock > 10 ? 'bg-slate-800 text-slate-200' : 'bg-red-950/80 text-rose-300 border border-rose-800/60' }}">
                                {{ $p->stock }} in stock
                            </span>
                        </td>

                        <!-- Featured Toggle -->
                        <td class="py-3 text-center">
                            <form method="POST" action="{{ route('admin.products.toggleFeatured', $p->id) }}" class="inline">
                                @csrf
                                <button type="submit"
                                        class="px-2 py-0.5 rounded-full text-[10px] font-bold transition {{ $p->is_featured ? 'bg-amber-500/20 text-amber-300 border border-amber-500/40' : 'bg-slate-900 text-slate-600 border border-slate-800' }}"
                                        title="Click to toggle featured on homepage">
                                    {{ $p->is_featured ? '★ Featured' : '☆ Off' }}
                                </button>
                            </form>
                        </td>

                        <!-- Bestseller Toggle -->
                        <td class="py-3 text-center">
                            <form method="POST" action="{{ route('admin.products.toggleBestseller', $p->id) }}" class="inline">
                                @csrf
                                <button type="submit"
                                        class="px-2 py-0.5 rounded-full text-[10px] font-bold transition {{ $p->is_bestseller ? 'bg-rose-500/20 text-rose-300 border border-rose-500/40' : 'bg-slate-900 text-slate-600 border border-slate-800' }}"
                                        title="Click to toggle bestseller badge">
                                    {{ $p->is_bestseller ? '🔥 Best' : 'Normal' }}
                                </button>
                            </form>
                        </td>

                        <!-- Active Toggle -->
                        <td class="py-3 text-center">
                            <form method="POST" action="{{ route('admin.products.toggleActive', $p->id) }}" class="inline">
                                @csrf
                                <button type="submit"
                                        class="px-2 py-0.5 rounded-full text-[10px] font-bold transition {{ $p->is_active ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/40' : 'bg-slate-900 text-slate-500 border border-slate-800' }}"
                                        title="Click to toggle active status">
                                    {{ $p->is_active ? 'Active' : 'Draft' }}
                                </button>
                            </form>
                        </td>

                        <!-- Actions -->
                        <td class="py-3 text-right space-x-3 whitespace-nowrap">
                            <a href="{{ route('admin.products.edit', $p->id) }}" class="text-[#D97706] hover:text-[#F97316] font-bold">Edit</a>
                            <form method="POST" action="{{ route('admin.products.destroy', $p->id) }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-[#991B1B] hover:text-red-400 font-bold" onclick="return confirm('Are you sure you want to delete product: {{ addslashes($p->name) }}?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="py-8 text-center text-slate-400">
                            No products found matching your filter criteria.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-6">
            {{ $products->links() }}
        </div>
    </div>
</x-admin-layout>

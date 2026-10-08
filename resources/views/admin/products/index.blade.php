<x-admin-layout title="Manage Products">
    <x-slot name="header">
        Products
    </x-slot>

    <!-- Page Title & Actions -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h1 class="text-xl font-semibold text-slate-900">Products ({{ $products->total() }})</h1>
            <p class="text-xs text-slate-500 mt-0.5">Manage catalog offerings, inventory levels, pricing, and visibility.</p>
        </div>
        <a href="{{ route('admin.products.create') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-amber-600 hover:bg-amber-700 text-white text-xs font-medium rounded-lg transition shadow-xs">
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            <span>Add Product</span>
        </a>
    </div>

    <!-- Filters & Search Bar -->
    <div class="bg-white border border-slate-200 rounded-xl p-3.5 mb-6">
        <form method="GET" action="{{ route('admin.products.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3 text-xs">
            <div class="lg:col-span-2">
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Search by name, SKU..."
                       class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-slate-900 placeholder-slate-400 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 text-xs" />
            </div>

            <div>
                <select name="category_id" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-slate-700 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 text-xs">
                    <option value="">All Categories</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <select name="is_bestseller" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-slate-700 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 text-xs">
                    <option value="">Bestsellers: All</option>
                    <option value="1" {{ request('is_bestseller') === '1' ? 'selected' : '' }}>Bestsellers Only</option>
                    <option value="0" {{ request('is_bestseller') === '0' ? 'selected' : '' }}>Non-Bestsellers</option>
                </select>
            </div>

            <div>
                <select name="is_featured" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-slate-700 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 text-xs">
                    <option value="">Featured: All</option>
                    <option value="1" {{ request('is_featured') === '1' ? 'selected' : '' }}>Featured Only</option>
                    <option value="0" {{ request('is_featured') === '0' ? 'selected' : '' }}>Non-Featured</option>
                </select>
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="flex-1 px-3 py-2 bg-slate-900 hover:bg-slate-800 text-white font-medium rounded-lg text-xs transition">
                    Filter
                </button>
                @if (request()->hasAny(['search', 'category_id', 'is_bestseller', 'is_featured', 'is_gangajal']))
                    <a href="{{ route('admin.products.index') }}" class="px-2.5 py-2 text-slate-400 hover:text-slate-600 text-xs" title="Reset Filters">
                        ✕
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Products Table -->
    <div class="bg-white border border-slate-200 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-50/75 border-b border-slate-200 text-slate-600 font-semibold uppercase text-[11px] tracking-wider">
                        <th class="py-2.5 px-4 w-14">Image</th>
                        <th class="py-2.5 px-4">Product Name & Category</th>
                        <th class="py-2.5 px-4">Price</th>
                        <th class="py-2.5 px-4">Member Price</th>
                        <th class="py-2.5 px-4">Stock</th>
                        <th class="py-2.5 px-4 text-center">Featured</th>
                        <th class="py-2.5 px-4 text-center">Bestseller</th>
                        <th class="py-2.5 px-4 text-center">Status</th>
                        <th class="py-2.5 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($products as $p)
                        <tr class="hover:bg-slate-50/60 transition">
                            <!-- Image Thumbnail -->
                            <td class="py-3 px-4">
                                <div class="w-10 h-10 rounded-md overflow-hidden bg-slate-100 border border-slate-200 shrink-0">
                                    <img src="{{ $p->image_url }}"
                                         alt="{{ $p->name }}"
                                         class="w-full h-full object-cover">
                                </div>
                            </td>

                            <!-- Name & Category -->
                            <td class="py-3 px-4 max-w-xs">
                                <div class="flex items-center gap-1.5 flex-wrap">
                                    <span class="font-medium text-slate-900 text-xs">
                                        {{ $p->name }}
                                    </span>
                                    @if ($p->badge)
                                        <span class="px-1.5 py-0.5 rounded text-[10px] font-medium bg-amber-50 text-amber-800 border border-amber-200">
                                            {{ $p->badge }}
                                        </span>
                                    @endif
                                </div>

                                <div class="flex items-center gap-2 mt-0.5 text-[11px] text-slate-500">
                                    <span>{{ $p->category->name }}</span>
                                    @if ($p->sku)
                                        <span>·</span>
                                        <span class="font-mono text-slate-400">{{ $p->sku }}</span>
                                    @endif
                                    @if ($p->is_gangajal)
                                        <span>·</span>
                                        <span class="text-sky-700 font-medium">{{ $p->volume_ml }}ml</span>
                                    @endif
                                </div>
                            </td>

                            <!-- Pricing -->
                            <td class="py-3 px-4 font-mono">
                                <span class="font-medium text-slate-900">₹{{ number_format($p->price, 0) }}</span>
                                @if ($p->compare_price && $p->compare_price > $p->price)
                                    <del class="block text-[10px] text-slate-400">₹{{ number_format($p->compare_price, 0) }}</del>
                                @endif
                            </td>

                            <!-- Member Price -->
                            <td class="py-3 px-4 font-mono text-slate-600">
                                @if ($p->is_gangajal)
                                    <span class="px-1.5 py-0.5 rounded text-[10px] font-medium bg-sky-50 text-sky-700 border border-sky-200">
                                        Free 1st / Mo
                                    </span>
                                @else
                                    <span>₹{{ number_format($p->member_price ?? $p->price, 0) }}</span>
                                @endif
                            </td>

                            <!-- Stock -->
                            <td class="py-3 px-4">
                                <span class="text-xs {{ $p->stock <= 10 ? 'text-red-600 font-medium' : 'text-slate-600' }}">
                                    {{ $p->stock }} in stock
                                </span>
                            </td>

                            <!-- Featured Toggle -->
                            <td class="py-3 px-4 text-center">
                                <form method="POST" action="{{ route('admin.products.toggleFeatured', $p->id) }}" class="inline">
                                    @csrf
                                    <button type="submit"
                                            class="px-2 py-0.5 rounded text-[10px] font-medium transition {{ $p->is_featured ? 'bg-amber-50 text-amber-800 border border-amber-200' : 'bg-slate-100 text-slate-500 border border-slate-200' }}"
                                            title="Click to toggle featured">
                                        {{ $p->is_featured ? 'Featured' : 'Off' }}
                                    </button>
                                </form>
                            </td>

                            <!-- Bestseller Toggle -->
                            <td class="py-3 px-4 text-center">
                                <form method="POST" action="{{ route('admin.products.toggleBestseller', $p->id) }}" class="inline">
                                    @csrf
                                    <button type="submit"
                                            class="px-2 py-0.5 rounded text-[10px] font-medium transition {{ $p->is_bestseller ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-slate-100 text-slate-500 border border-slate-200' }}"
                                            title="Click to toggle bestseller">
                                        {{ $p->is_bestseller ? 'Best' : 'Normal' }}
                                    </button>
                                </form>
                            </td>

                            <!-- Active Toggle -->
                            <td class="py-3 px-4 text-center">
                                <form method="POST" action="{{ route('admin.products.toggleActive', $p->id) }}" class="inline">
                                    @csrf
                                    <button type="submit"
                                            class="px-2 py-0.5 rounded text-[10px] font-medium transition {{ $p->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-500 border border-slate-200' }}"
                                            title="Click to toggle active status">
                                        {{ $p->is_active ? 'Active' : 'Draft' }}
                                    </button>
                                </form>
                            </td>

                            <!-- Actions -->
                            <td class="py-3 px-4 text-right space-x-2.5 whitespace-nowrap">
                                <a href="{{ route('admin.products.edit', $p->id) }}" class="text-amber-700 hover:text-amber-800 font-medium">Edit</a>
                                <form method="POST" action="{{ route('admin.products.destroy', $p->id) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="text-red-600 hover:text-red-700 font-medium"
                                            onclick="return confirm('Are you sure you want to delete product: {{ addslashes($p->name) }}?')">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-10 text-center text-slate-400">
                                No products found. Click "Add Product" to create one.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-3.5 border-t border-slate-200">
            {{ $products->links() }}
        </div>
    </div>
</x-admin-layout>

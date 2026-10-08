<x-admin-layout title="Manage Categories">
    <x-slot name="header">
        Categories & Collections
    </x-slot>

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h2 class="text-xl font-serif font-bold text-white">Catalog Categories ({{ $categories->total() }})</h2>
            <p class="text-xs text-slate-400 mt-1">
                Manage product taxonomies, subcategories, collection imagery, and circular stories featured on the homepage.
            </p>
        </div>
        <a href="{{ route('admin.categories.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#D97706] hover:bg-[#F97316] text-white font-bold text-xs rounded-xl shadow-md transition">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            <span>+ Add New Category</span>
        </a>
    </div>

    <!-- Filters & Search Bar -->
    <div class="bg-slate-950/80 border border-slate-800 rounded-2xl p-4 mb-6">
        <form method="GET" action="{{ route('admin.categories.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 text-xs">
            <div class="lg:col-span-2">
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Search category name, slug..."
                       class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:border-[#D97706]" />
            </div>

            <div>
                <select name="parent_id" class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white">
                    <option value="">-- All Levels --</option>
                    <option value="root" {{ request('parent_id') === 'root' ? 'selected' : '' }}>Root Categories Only</option>
                    @foreach ($rootCategories as $rcat)
                        <option value="{{ $rcat->id }}" {{ request('parent_id') == $rcat->id ? 'selected' : '' }}>
                            Subcategories of: {{ $rcat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <select name="is_featured" class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white">
                    <option value="">-- Homepage Stories: All --</option>
                    <option value="1" {{ request('is_featured') === '1' ? 'selected' : '' }}>Featured on Homepage</option>
                    <option value="0" {{ request('is_featured') === '0' ? 'selected' : '' }}>Not Featured</option>
                </select>
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="flex-1 px-4 py-2 bg-slate-800 hover:bg-slate-700 text-white font-bold rounded-xl border border-slate-700 transition">
                    Filter
                </button>
                @if (request()->hasAny(['search', 'parent_id', 'is_featured', 'is_active']))
                    <a href="{{ route('admin.categories.index') }}" class="px-3 py-2 text-slate-400 hover:text-white" title="Reset Filters">
                        ✕
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Categories Table -->
    <div class="bg-slate-950/80 border border-slate-800 rounded-3xl p-6 shadow-sm overflow-x-auto">
        <table class="w-full text-left text-xs">
            <thead>
                <tr class="text-slate-400 border-b border-slate-800 font-mono text-[11px]">
                    <th class="pb-3 w-16">Photo</th>
                    <th class="pb-3">Category Name & Icon</th>
                    <th class="pb-3">Slug</th>
                    <th class="pb-3">Hierarchy / Parent</th>
                    <th class="pb-3 text-center">Products</th>
                    <th class="pb-3 text-center">Order</th>
                    <th class="pb-3 text-center">Homepage</th>
                    <th class="pb-3 text-center">Status</th>
                    <th class="pb-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-800/80">
                @forelse ($categories as $cat)
                    <tr class="hover:bg-slate-900/40 transition">
                        <!-- Image Preview -->
                        <td class="py-3.5 pr-3">
                            <div class="w-12 h-12 rounded-full overflow-hidden bg-slate-900 border-2 border-amber-500/40 shrink-0 relative p-0.5">
                                <img src="{{ $cat->image_url }}"
                                     alt="{{ $cat->name }}"
                                     class="w-full h-full object-cover rounded-full">
                            </div>
                        </td>

                        <!-- Name & Icon -->
                        <td class="py-3.5 pr-4 max-w-xs">
                            <div class="flex items-center gap-2">
                                @if ($cat->icon)
                                    <span class="text-base">{{ $cat->icon }}</span>
                                @endif
                                <span class="font-bold text-white text-xs">
                                    {{ $cat->name }}
                                </span>
                            </div>
                            @if ($cat->description)
                                <p class="text-[11px] text-slate-400 line-clamp-1 mt-0.5">
                                    {{ $cat->description }}
                                </p>
                            @endif
                        </td>

                        <!-- Slug -->
                        <td class="py-3.5 pr-3 font-mono text-slate-400 text-[11px]">
                            <a href="{{ route('products.index', ['category' => $cat->slug]) }}" target="_blank" class="hover:text-amber-400 transition underline">
                                {{ $cat->slug }}
                            </a>
                        </td>

                        <!-- Parent Category -->
                        <td class="py-3.5 pr-3">
                            @if ($cat->parent)
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-[10px] font-semibold bg-slate-800 text-slate-300 border border-slate-700">
                                    <span>↳</span>
                                    <span>{{ $cat->parent->name }}</span>
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-bold bg-[#D97706]/15 text-amber-300 border border-[#D97706]/30">
                                    ★ Root Category
                                </span>
                            @endif
                        </td>

                        <!-- Products Count -->
                        <td class="py-3.5 text-center font-mono">
                            <span class="px-2 py-0.5 rounded-lg bg-slate-900 text-slate-200 border border-slate-800">
                                {{ $cat->products_count }} items
                            </span>
                        </td>

                        <!-- Sort Order -->
                        <td class="py-3.5 text-center font-mono text-slate-400">
                            #{{ $cat->sort_order }}
                        </td>

                        <!-- Homepage Featured Toggle -->
                        <td class="py-3.5 text-center">
                            <form method="POST" action="{{ route('admin.categories.toggleFeatured', $cat->id) }}" class="inline">
                                @csrf
                                <button type="submit"
                                        class="px-2 py-0.5 rounded-full text-[10px] font-bold transition {{ $cat->is_featured ? 'bg-amber-500/20 text-amber-300 border border-amber-500/40 hover:bg-amber-500/30' : 'bg-slate-900 text-slate-600 border border-slate-800 hover:text-slate-400' }}"
                                        title="Click to toggle in Sacred Collections circular stories">
                                    {{ $cat->is_featured ? '★ Circular Story' : '☆ Not Shown' }}
                                </button>
                            </form>
                        </td>

                        <!-- Active Toggle -->
                        <td class="py-3.5 text-center">
                            <form method="POST" action="{{ route('admin.categories.toggleActive', $cat->id) }}" class="inline">
                                @csrf
                                <button type="submit"
                                        class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold transition {{ $cat->is_active ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/40' : 'bg-slate-900 text-slate-500 border border-slate-800' }}"
                                        title="Click to toggle active status">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $cat->is_active ? 'bg-emerald-400' : 'bg-slate-600' }}"></span>
                                    <span>{{ $cat->is_active ? 'Active' : 'Draft' }}</span>
                                </button>
                            </form>
                        </td>

                        <!-- Actions -->
                        <td class="py-3.5 text-right space-x-3 whitespace-nowrap">
                            <a href="{{ route('admin.categories.edit', $cat->id) }}" class="text-[#D97706] hover:text-[#F97316] font-bold">Edit</a>
                            <form method="POST" action="{{ route('admin.categories.destroy', $cat->id) }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="text-[#991B1B] hover:text-red-400 font-bold"
                                        onclick="return confirm('Are you sure you want to delete category: {{ addslashes($cat->name) }}?')">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="py-8 text-center text-slate-400">
                            No categories found matching your search.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-6">
            {{ $categories->links() }}
        </div>
    </div>
</x-admin-layout>

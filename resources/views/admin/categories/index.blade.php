<x-admin-layout title="Manage Categories">
    <x-slot name="header">
        Categories
    </x-slot>

    <!-- Page Title & Action -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h1 class="text-xl font-semibold text-slate-900">Categories ({{ $categories->total() }})</h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Manage taxonomies, parent-child hierarchies, and homepage featured collections.
            </p>
        </div>
        <a href="{{ route('admin.categories.create') }}"
           class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-amber-600 hover:bg-amber-700 text-white text-xs font-medium rounded-lg transition shadow-xs">
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            <span>Add Category</span>
        </a>
    </div>

    <!-- Filters & Search Bar -->
    <div class="bg-white border border-slate-200 rounded-xl p-3.5 mb-6">
        <form method="GET" action="{{ route('admin.categories.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 text-xs">
            <div class="lg:col-span-2">
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Search category name, slug..."
                       class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-slate-900 placeholder-slate-400 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 text-xs" />
            </div>

            <div>
                <select name="parent_id" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-slate-700 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 text-xs">
                    <option value="">All Levels</option>
                    <option value="root" {{ request('parent_id') === 'root' ? 'selected' : '' }}>Root Categories Only</option>
                    @foreach ($rootCategories as $rcat)
                        <option value="{{ $rcat->id }}" {{ request('parent_id') == $rcat->id ? 'selected' : '' }}>
                            Subcategories of: {{ $rcat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <select name="is_featured" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-slate-700 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 text-xs">
                    <option value="">Homepage Stories: All</option>
                    <option value="1" {{ request('is_featured') === '1' ? 'selected' : '' }}>Featured on Homepage</option>
                    <option value="0" {{ request('is_featured') === '0' ? 'selected' : '' }}>Not Featured</option>
                </select>
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="flex-1 px-3 py-2 bg-slate-900 hover:bg-slate-800 text-white font-medium rounded-lg text-xs transition">
                    Filter
                </button>
                @if (request()->hasAny(['search', 'parent_id', 'is_featured', 'is_active']))
                    <a href="{{ route('admin.categories.index') }}" class="px-2.5 py-2 text-slate-400 hover:text-slate-600 text-xs" title="Reset Filters">
                        ✕
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Categories Table -->
    <div class="bg-white border border-slate-200 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-50/75 border-b border-slate-200 text-slate-600 font-semibold uppercase text-[11px] tracking-wider">
                        <th class="py-2.5 px-4 w-14">Photo</th>
                        <th class="py-2.5 px-4">Name & Icon</th>
                        <th class="py-2.5 px-4">Slug</th>
                        <th class="py-2.5 px-4">Hierarchy</th>
                        <th class="py-2.5 px-4 text-center">Products</th>
                        <th class="py-2.5 px-4 text-center">Order</th>
                        <th class="py-2.5 px-4 text-center">Homepage</th>
                        <th class="py-2.5 px-4 text-center">Status</th>
                        <th class="py-2.5 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($categories as $cat)
                        <tr class="hover:bg-slate-50/60 transition">
                            <!-- Image Preview -->
                            <td class="py-3 px-4">
                                <div class="w-10 h-10 rounded-md overflow-hidden bg-slate-100 border border-slate-200 shrink-0">
                                    <img src="{{ $cat->image_url }}"
                                         alt="{{ $cat->name }}"
                                         class="w-full h-full object-cover">
                                </div>
                            </td>

                            <!-- Name & Icon -->
                            <td class="py-3 px-4 max-w-xs">
                                <div class="flex items-center gap-1.5">
                                    @if ($cat->icon)
                                        <span class="text-sm">{{ $cat->icon }}</span>
                                    @endif
                                    <span class="font-medium text-slate-900 text-xs">
                                        {{ $cat->name }}
                                    </span>
                                </div>
                                @if ($cat->description)
                                    <p class="text-[11px] text-slate-500 line-clamp-1 mt-0.5">
                                        {{ $cat->description }}
                                    </p>
                                @endif
                            </td>

                            <!-- Slug -->
                            <td class="py-3 px-4 font-mono text-slate-500 text-[11px]">
                                <a href="{{ route('products.index', ['category' => $cat->slug]) }}" target="_blank" class="hover:text-amber-700 underline">
                                    {{ $cat->slug }}
                                </a>
                            </td>

                            <!-- Parent Category -->
                            <td class="py-3 px-4">
                                @if ($cat->parent)
                                    <span class="inline-flex items-center gap-1 text-[11px] text-slate-600">
                                        <span class="text-slate-400">↳</span>
                                        <span>{{ $cat->parent->name }}</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-slate-100 text-slate-700">
                                        Root Category
                                    </span>
                                @endif
                            </td>

                            <!-- Products Count -->
                            <td class="py-3 px-4 text-center font-mono text-slate-600">
                                {{ $cat->products_count }} items
                            </td>

                            <!-- Sort Order -->
                            <td class="py-3 px-4 text-center font-mono text-slate-500">
                                #{{ $cat->sort_order }}
                            </td>

                            <!-- Homepage Featured Toggle -->
                            <td class="py-3 px-4 text-center">
                                <form method="POST" action="{{ route('admin.categories.toggleFeatured', $cat->id) }}" class="inline">
                                    @csrf
                                    <button type="submit"
                                            class="px-2 py-0.5 rounded text-[10px] font-medium transition {{ $cat->is_featured ? 'bg-amber-50 text-amber-800 border border-amber-200 hover:bg-amber-100' : 'bg-slate-100 text-slate-500 border border-slate-200 hover:text-slate-800' }}"
                                            title="Click to toggle homepage circular story">
                                        {{ $cat->is_featured ? 'Featured' : 'Standard' }}
                                    </button>
                                </form>
                            </td>

                            <!-- Active Toggle -->
                            <td class="py-3 px-4 text-center">
                                <form method="POST" action="{{ route('admin.categories.toggleActive', $cat->id) }}" class="inline">
                                    @csrf
                                    <button type="submit"
                                            class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-medium transition {{ $cat->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-500 border border-slate-200' }}"
                                            title="Click to toggle status">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $cat->is_active ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                                        <span>{{ $cat->is_active ? 'Active' : 'Draft' }}</span>
                                    </button>
                                </form>
                            </td>

                            <!-- Actions -->
                            <td class="py-3 px-4 text-right space-x-2.5 whitespace-nowrap">
                                <a href="{{ route('admin.categories.edit', $cat->id) }}" class="text-amber-700 hover:text-amber-800 font-medium">Edit</a>
                                <form method="POST" action="{{ route('admin.categories.destroy', $cat->id) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="text-red-600 hover:text-red-700 font-medium"
                                            onclick="return confirm('Are you sure you want to delete category: {{ addslashes($cat->name) }}?')">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-10 text-center text-slate-400">
                                No categories found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-3.5 border-t border-slate-200">
            {{ $categories->links() }}
        </div>
    </div>
</x-admin-layout>

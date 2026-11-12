<x-admin-layout title="Manage Gallery">
    <x-slot name="header">Media Gallery</x-slot>

    <!-- Page Header & Action -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h1 class="text-xl font-semibold text-slate-900">Sacred Media Gallery ({{ $items->total() }})</h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Manage high-resolution photography and Aarti ceremony videos of Haridwar.
            </p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('gallery.index') }}"
               target="_blank"
               class="px-3.5 py-2 bg-white hover:bg-slate-50 text-slate-700 border border-slate-300 text-xs font-medium rounded-lg transition inline-flex items-center gap-1.5">
                <span>View Public Gallery ↗</span>
            </a>
            <a href="{{ route('admin.gallery.create') }}"
               class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-amber-600 hover:bg-amber-700 text-white text-xs font-medium rounded-lg transition shadow-xs">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Add Media</span>
            </a>
        </div>
    </div>

    <!-- Search & Filters -->
    <div class="bg-white border border-slate-200 rounded-xl p-3.5 mb-6">
        <form method="GET" action="{{ route('admin.gallery.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 text-xs">
            <div class="lg:col-span-2">
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Search by title, location, caption..."
                       class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-slate-900 placeholder-slate-400 focus:outline-none focus:border-amber-600 focus:ring-1 focus:ring-amber-600 text-xs" />
            </div>

            <div>
                <select name="category" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-slate-700 focus:outline-none focus:border-amber-600 focus:ring-1 focus:ring-amber-600 text-xs">
                    <option value="">All Categories</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>
                            {{ $cat }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <select name="media_type" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-slate-700 focus:outline-none focus:border-amber-600 focus:ring-1 focus:ring-amber-600 text-xs">
                    <option value="">All Types</option>
                    <option value="image" {{ request('media_type') === 'image' ? 'selected' : '' }}>Photos Only</option>
                    <option value="video" {{ request('media_type') === 'video' ? 'selected' : '' }}>Videos Only</option>
                </select>
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="flex-1 px-3 py-2 bg-slate-900 hover:bg-slate-800 text-white font-medium rounded-lg text-xs transition">
                    Filter
                </button>

                @if (request()->hasAny(['search', 'category', 'media_type', 'status']))
                    <a href="{{ route('admin.gallery.index') }}" class="px-2 py-2 text-slate-400 hover:text-slate-600 text-xs" title="Reset Filters">
                        ✕
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Gallery Table -->
    <div class="bg-white border border-slate-200 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 font-semibold uppercase text-[11px] tracking-wider">
                        <th class="py-3 px-4 w-20">Media</th>
                        <th class="py-3 px-4">Title & Location</th>
                        <th class="py-3 px-4">Category</th>
                        <th class="py-3 px-4 text-center">Type</th>
                        <th class="py-3 px-4 text-center">Sort Order</th>
                        <th class="py-3 px-4 text-center">Status</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($items as $item)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <!-- Preview Thumbnail -->
                            <td class="py-3 px-4">
                                <div class="w-16 h-11 rounded-md overflow-hidden bg-slate-100 border border-slate-200 shrink-0 relative group">
                                    <img src="{{ $item->image_url }}"
                                         alt="{{ $item->title }}"
                                         class="w-full h-full object-cover">
                                    @if ($item->media_type === 'video')
                                        <div class="absolute inset-0 bg-slate-900/30 flex items-center justify-center">
                                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M6.3 2.841A1.5 1.5 0 004 4.11v11.78a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                            </td>

                            <!-- Title & Location -->
                            <td class="py-3 px-4 max-w-sm">
                                <div class="font-medium text-slate-900 line-clamp-1">
                                    {{ $item->title }}
                                </div>
                                <div class="text-[11px] text-slate-500 mt-0.5 flex items-center gap-1.5">
                                    <svg class="w-3 h-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span>{{ $item->location }}</span>
                                </div>
                            </td>

                            <!-- Category -->
                            <td class="py-3 px-4">
                                <span class="px-2 py-0.5 rounded text-[11px] font-medium bg-slate-100 text-slate-700 border border-slate-200">
                                    {{ $item->category }}
                                </span>
                            </td>

                            <!-- Media Type -->
                            <td class="py-3 px-4 text-center">
                                @if ($item->media_type === 'video')
                                    <span class="px-2 py-0.5 rounded text-[10px] font-medium uppercase bg-amber-50 text-amber-800 border border-amber-200">
                                        Video
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded text-[10px] font-medium uppercase bg-slate-100 text-slate-600 border border-slate-200">
                                        Photo
                                    </span>
                                @endif
                            </td>

                            <!-- Sort Order -->
                            <td class="py-3 px-4 text-center font-mono text-slate-500 text-xs">
                                #{{ $item->sort_order }}
                            </td>

                            <!-- Status Toggle -->
                            <td class="py-3 px-4 text-center">
                                <form method="POST" action="{{ route('admin.gallery.toggleActive', $item->id) }}" class="inline">
                                    @csrf
                                    <button type="submit"
                                            class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-medium uppercase transition {{ $item->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-500 border border-slate-200' }}"
                                            title="Click to toggle visibility">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $item->is_active ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                                        <span>{{ $item->is_active ? 'Live' : 'Hidden' }}</span>
                                    </button>
                                </form>
                            </td>

                            <!-- Actions -->
                            <td class="py-3 px-4 text-right space-x-2.5 whitespace-nowrap">
                                <a href="{{ route('admin.gallery.edit', $item->id) }}" class="text-amber-700 hover:text-amber-800 font-medium">Edit</a>
                                <form method="POST" action="{{ route('admin.gallery.destroy', $item->id) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="text-red-600 hover:text-red-700 font-medium"
                                            onclick="return confirm('Are you sure you want to remove item: {{ addslashes($item->title) }}?')">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400">
                                No media items found. Click "Add Media" to upload photos and videos.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($items->hasPages())
            <div class="p-4 border-t border-slate-200 bg-white">
                {{ $items->links() }}
            </div>
        @endif
    </div>
</x-admin-layout>

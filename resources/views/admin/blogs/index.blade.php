<x-admin-layout title="Manage Blogs">
    <x-slot name="header">Blogs & Articles</x-slot>

    <!-- Page Header & Action -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h1 class="text-xl font-semibold text-slate-900">Spiritual Articles & Guides ({{ $blogs->total() }})</h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Publish devotee guidance, Vedic wisdom articles, and Haridwar holy provenance stories.
            </p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('blogs.index') }}"
               target="_blank"
               class="px-3.5 py-2 bg-white hover:bg-slate-50 text-slate-700 border border-slate-300 text-xs font-medium rounded-lg transition inline-flex items-center gap-1.5">
                <span>View Public Blog ↗</span>
            </a>
            <a href="{{ route('admin.blogs.create') }}"
               class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-amber-600 hover:bg-amber-700 text-white text-xs font-medium rounded-lg transition shadow-xs">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Write Article</span>
            </a>
        </div>
    </div>

    <!-- Search & Filters -->
    <div class="bg-white border border-slate-200 rounded-xl p-3.5 mb-6">
        <form method="GET" action="{{ route('admin.blogs.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 text-xs">
            <div class="lg:col-span-2">
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Search by title, excerpt, author..."
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

            <div class="flex items-center gap-2">
                <select name="status" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-slate-700 focus:outline-none focus:border-amber-600 focus:ring-1 focus:ring-amber-600 text-xs">
                    <option value="">All Statuses</option>
                    <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published Only</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Drafts Only</option>
                </select>

                <button type="submit" class="px-3.5 py-2 bg-slate-900 hover:bg-slate-800 text-white font-medium rounded-lg text-xs transition">
                    Filter
                </button>

                @if (request()->hasAny(['search', 'category', 'status']))
                    <a href="{{ route('admin.blogs.index') }}" class="px-2 py-2 text-slate-400 hover:text-slate-600 text-xs" title="Reset Filters">
                        ✕
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Blogs Table -->
    <div class="bg-white border border-slate-200 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 font-semibold uppercase text-[11px] tracking-wider">
                        <th class="py-3 px-4 w-16">Cover</th>
                        <th class="py-3 px-4">Title & Details</th>
                        <th class="py-3 px-4">Category</th>
                        <th class="py-3 px-4">Author</th>
                        <th class="py-3 px-4 text-center">Featured</th>
                        <th class="py-3 px-4 text-center">Status</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($blogs as $blog)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <!-- Thumbnail -->
                            <td class="py-3 px-4">
                                <div class="w-12 h-9 rounded-md overflow-hidden bg-slate-100 border border-slate-200 shrink-0">
                                    <img src="{{ $blog->image_url }}"
                                         alt="{{ $blog->title }}"
                                         class="w-full h-full object-cover">
                                </div>
                            </td>

                            <!-- Title & Excerpt -->
                            <td class="py-3 px-4 max-w-sm">
                                <a href="{{ route('blogs.show', $blog->slug) }}"
                                   target="_blank"
                                   class="font-medium text-slate-900 hover:text-amber-700 line-clamp-1">
                                    {{ $blog->title }}
                                </a>
                                <div class="text-[11px] text-slate-400 mt-0.5 flex items-center gap-2">
                                    <span>{{ $blog->reading_time_minutes }} min read</span>
                                    <span>·</span>
                                    <span>{{ $blog->views_count }} views</span>
                                    <span>·</span>
                                    <span>{{ $blog->published_at ? $blog->published_at->format('M d, Y') : 'Draft' }}</span>
                                </div>
                            </td>

                            <!-- Category -->
                            <td class="py-3 px-4">
                                <span class="px-2 py-0.5 rounded text-[11px] font-medium bg-slate-100 text-slate-700 border border-slate-200">
                                    {{ $blog->category }}
                                </span>
                            </td>

                            <!-- Author -->
                            <td class="py-3 px-4 text-slate-700">
                                {{ $blog->author_name }}
                            </td>

                            <!-- Featured Toggle -->
                            <td class="py-3 px-4 text-center">
                                <form method="POST" action="{{ route('admin.blogs.toggleFeatured', $blog->id) }}" class="inline">
                                    @csrf
                                    <button type="submit"
                                            class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded text-[10px] font-medium transition {{ $blog->is_featured ? 'bg-amber-50 text-amber-800 border border-amber-200 font-semibold' : 'bg-slate-50 text-slate-400 hover:text-slate-600 border border-slate-200' }}"
                                            title="Click to toggle featured spotlight">
                                        <span>{{ $blog->is_featured ? '★ Spotlight' : 'Standard' }}</span>
                                    </button>
                                </form>
                            </td>

                            <!-- Status Toggle -->
                            <td class="py-3 px-4 text-center">
                                <form method="POST" action="{{ route('admin.blogs.togglePublished', $blog->id) }}" class="inline">
                                    @csrf
                                    <button type="submit"
                                            class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-medium uppercase transition {{ $blog->is_published ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-500 border border-slate-200' }}"
                                            title="Click to toggle status">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $blog->is_published ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                                        <span>{{ $blog->is_published ? 'Live' : 'Draft' }}</span>
                                    </button>
                                </form>
                            </td>

                            <!-- Actions -->
                            <td class="py-3 px-4 text-right space-x-2.5 whitespace-nowrap">
                                <a href="{{ route('blogs.show', $blog->slug) }}" target="_blank" class="text-slate-500 hover:text-slate-800 font-medium">View ↗</a>
                                <a href="{{ route('admin.blogs.edit', $blog->id) }}" class="text-amber-700 hover:text-amber-800 font-medium">Edit</a>
                                <form method="POST" action="{{ route('admin.blogs.destroy', $blog->id) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="text-red-600 hover:text-red-700 font-medium"
                                            onclick="return confirm('Are you sure you want to delete article: {{ addslashes($blog->title) }}?')">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400">
                                No articles found. Click "Write Article" to publish divine wisdom.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($blogs->hasPages())
            <div class="p-4 border-t border-slate-200 bg-white">
                {{ $blogs->links() }}
            </div>
        @endif
    </div>
</x-admin-layout>

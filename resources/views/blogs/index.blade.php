<x-app-layout>
    <!-- 1. Hero Header -->
    <section class="relative bg-gradient-to-b from-amber-900/10 via-amber-500/5 to-transparent pt-12 pb-10 border-b border-amber-200/40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-widest bg-amber-100 text-amber-900 border border-amber-300 mb-3">
                <span>Vedic Wisdom & Darshan</span>
            </span>
            <h1 class="font-serif font-extrabold text-3xl sm:text-5xl text-slate-900 tracking-tight">
                Spiritual Knowledge & Ganga Lore
            </h1>
            <p class="max-w-2xl mx-auto text-sm sm:text-base text-slate-600 mt-3 leading-relaxed">
                Discover the sacred significance of holy Maa Ganga in Haridwar, ancient Ayurvedic rituals, and authentic Vedic wisdom passed down through generations.
            </p>

            <!-- Search Bar -->
            <div class="max-w-md mx-auto mt-6">
                <form method="GET" action="{{ route('blogs.index') }}" class="relative">
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Search articles, rituals, scriptures..."
                           class="w-full pl-10 pr-24 py-2.5 bg-white border border-amber-300 rounded-full text-xs sm:text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-amber-500 shadow-xs" />
                    <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <button type="submit"
                            class="absolute right-1.5 top-1 px-4 py-1.5 bg-[#881337] hover:bg-[#D97706] text-white text-xs font-bold rounded-full transition shadow-xs">
                        Search
                    </button>
                </form>
            </div>

            <!-- Category Filter Tabs -->
            <div class="flex items-center justify-center gap-2 flex-wrap mt-6">
                <a href="{{ route('blogs.index') }}"
                   class="px-3.5 py-1.5 rounded-full text-xs font-bold transition {{ !request('category') ? 'bg-[#881337] text-white shadow-xs' : 'bg-white hover:bg-amber-50 text-slate-700 border border-amber-200' }}">
                    All Articles
                </a>
                @foreach ($categories as $cat)
                    <a href="{{ route('blogs.index', ['category' => $cat]) }}"
                       class="px-3.5 py-1.5 rounded-full text-xs font-bold transition {{ request('category') === $cat ? 'bg-[#881337] text-white shadow-xs' : 'bg-white hover:bg-amber-50 text-slate-700 border border-amber-200' }}">
                        {{ $cat }}
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- 2. Main Blog Grid & Featured Spotlight -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        @if (request('search'))
            <div class="mb-8 flex items-center justify-between pb-4 border-b border-slate-200">
                <h2 class="text-sm font-semibold text-slate-700">
                    Search results for: <span class="font-bold text-slate-900">"{{ request('search') }}"</span> ({{ $blogs->total() }})
                </h2>
                <a href="{{ route('blogs.index') }}" class="text-xs text-[#D97706] hover:underline font-bold">
                    Clear Search ✕
                </a>
            </div>
        @endif

        <!-- Featured Article Spotlight (Hero Card) -->
        @if ($featuredBlog && !request('search') && !request('category'))
            <div class="mb-12 bg-white rounded-3xl overflow-hidden border border-amber-200 shadow-md hover:shadow-lg transition">
                <div class="grid grid-cols-1 lg:grid-cols-12">
                    <div class="lg:col-span-7 relative h-72 sm:h-96 lg:h-auto overflow-hidden">
                        <img src="{{ $featuredBlog->image_url }}"
                             alt="{{ $featuredBlog->title }}"
                             class="w-full h-full object-cover transform hover:scale-105 transition duration-500">
                        <div class="absolute top-4 left-4">
                            <span class="px-3 py-1 rounded-full text-xs font-bold uppercase bg-amber-500 text-white shadow-md">
                                ★ Featured Sacred Guide
                            </span>
                        </div>
                    </div>
                    <div class="lg:col-span-5 p-6 sm:p-10 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center gap-3 text-xs text-slate-500 mb-3">
                                <span class="font-bold text-[#D97706] uppercase tracking-wider">{{ $featuredBlog->category }}</span>
                                <span>·</span>
                                <span>{{ $featuredBlog->reading_time_minutes }} min read</span>
                                <span>·</span>
                                <span>{{ $featuredBlog->published_at ? $featuredBlog->published_at->format('M d, Y') : '' }}</span>
                            </div>
                            <h2 class="font-serif font-bold text-2xl sm:text-3xl text-slate-900 leading-tight">
                                <a href="{{ route('blogs.show', $featuredBlog->slug) }}" class="hover:text-[#881337] transition">
                                    {{ $featuredBlog->title }}
                                </a>
                            </h2>
                            <p class="text-slate-600 text-sm mt-3 leading-relaxed line-clamp-3">
                                {{ $featuredBlog->excerpt }}
                            </p>
                        </div>

                        <div class="pt-6 border-t border-amber-100 flex items-center justify-between mt-6">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-full bg-amber-100 border border-amber-300 flex items-center justify-center font-bold text-xs text-[#D97706]">
                                    {{ strtoupper(substr($featuredBlog->author_name, 0, 1)) }}
                                </div>
                                <span class="text-xs font-semibold text-slate-800">{{ $featuredBlog->author_name }}</span>
                            </div>
                            <a href="{{ route('blogs.show', $featuredBlog->slug) }}"
                               class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#881337] hover:bg-[#D97706] text-white text-xs font-bold rounded-xl transition shadow-xs">
                                <span>Read Sacred Guide</span>
                                <span>→</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Articles Grid -->
        @if ($blogs->isEmpty())
            <div class="text-center py-20 bg-white rounded-3xl border border-dashed border-amber-300 p-8">
                <span class="text-4xl block mb-2">📜</span>
                <h3 class="font-serif font-bold text-lg text-slate-800">No articles found</h3>
                <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">
                    Try searching for another keyword or clear active category filters.
                </p>
                <a href="{{ route('blogs.index') }}" class="inline-block mt-4 px-4 py-2 bg-[#D97706] text-white text-xs font-bold rounded-xl">
                    View All Articles
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($blogs as $blog)
                    <article class="bg-white rounded-2xl overflow-hidden border border-amber-200/60 shadow-xs hover:shadow-md transition flex flex-col justify-between group">
                        <div>
                            <!-- Card Image -->
                            <a href="{{ route('blogs.show', $blog->slug) }}" class="block relative h-48 sm:h-52 overflow-hidden bg-slate-100">
                                <img src="{{ $blog->image_url }}"
                                     alt="{{ $blog->title }}"
                                     class="w-full h-full object-cover transform group-hover:scale-105 transition duration-500">
                                <div class="absolute top-3 left-3">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase bg-white/95 text-[#D97706] shadow-xs border border-amber-200">
                                        {{ $blog->category }}
                                    </span>
                                </div>
                            </a>

                            <!-- Card Content -->
                            <div class="p-5 sm:p-6">
                                <div class="flex items-center gap-2 text-[11px] text-slate-400 mb-2">
                                    <span>{{ $blog->reading_time_minutes }} min read</span>
                                    <span>·</span>
                                    <span>{{ $blog->published_at ? $blog->published_at->format('M d, Y') : '' }}</span>
                                </div>

                                <h3 class="font-serif font-bold text-lg text-slate-900 group-hover:text-[#881337] transition leading-snug line-clamp-2">
                                    <a href="{{ route('blogs.show', $blog->slug) }}">
                                        {{ $blog->title }}
                                    </a>
                                </h3>

                                <p class="text-xs text-slate-600 mt-2 line-clamp-3 leading-relaxed">
                                    {{ $blog->excerpt }}
                                </p>
                            </div>
                        </div>

                        <!-- Card Footer -->
                        <div class="px-5 sm:px-6 pb-5 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                            <span class="text-slate-500 font-medium truncate max-w-[150px]">
                                By {{ $blog->author_name }}
                            </span>
                            <a href="{{ route('blogs.show', $blog->slug) }}" class="font-bold text-[#D97706] group-hover:text-[#881337] inline-flex items-center gap-1">
                                <span>Read More</span>
                                <span class="transform group-hover:translate-x-1 transition">→</span>
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>

            <!-- Pagination -->
            @if ($blogs->hasPages())
                <div class="mt-12">
                    {{ $blogs->links() }}
                </div>
            @endif
        @endif
    </div>
</x-app-layout>

<x-app-layout>
    <article class="py-10">
        <!-- 1. Breadcrumbs -->
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 mb-6">
            <nav class="flex items-center gap-2 text-xs text-slate-500 font-medium overflow-x-auto whitespace-nowrap pb-2">
                <a href="{{ route('home') }}" class="hover:text-[#D97706] transition">Home</a>
                <span>/</span>
                <a href="{{ route('blogs.index') }}" class="hover:text-[#D97706] transition">Spiritual Blogs</a>
                <span>/</span>
                <a href="{{ route('blogs.index', ['category' => $blog->category]) }}" class="hover:text-[#D97706] transition">
                    {{ $blog->category }}
                </a>
                <span>/</span>
                <span class="text-slate-800 font-bold truncate max-w-xs">{{ $blog->title }}</span>
            </nav>
        </div>

        <!-- 2. Article Header -->
        <header class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 mb-8 text-center sm:text-left">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-amber-100 text-[#D97706] border border-amber-300 mb-4">
                <span>{{ $blog->category }}</span>
            </div>

            <h1 class="font-serif font-extrabold text-3xl sm:text-4xl lg:text-5xl text-slate-900 leading-tight">
                {{ $blog->title }}
            </h1>

            @if ($blog->excerpt)
                <p class="text-base sm:text-lg text-slate-600 mt-4 leading-relaxed font-normal">
                    {{ $blog->excerpt }}
                </p>
            @endif

            <!-- Author & Meta bar -->
            <div class="flex flex-wrap items-center justify-between gap-4 mt-6 pt-6 border-t border-amber-200/50 text-xs text-slate-500">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-amber-100 border border-amber-300 flex items-center justify-center font-bold text-sm text-[#D97706]">
                        {{ strtoupper(substr($blog->author_name, 0, 1)) }}
                    </div>
                    <div>
                        <strong class="block text-slate-900 font-semibold text-sm">{{ $blog->author_name }}</strong>
                        <span class="text-slate-400">Haridwar Vedic Scholar</span>
                    </div>
                </div>

                <div class="flex items-center gap-4 text-xs font-medium">
                    <span>{{ $blog->published_at ? $blog->published_at->format('F d, Y') : 'Recently Published' }}</span>
                    <span>·</span>
                    <span>{{ $blog->reading_time_minutes }} min read</span>
                    <span>·</span>
                    <span class="text-slate-400">{{ $blog->views_count }} devotees read</span>
                </div>
            </div>
        </header>

        <!-- 3. Featured Hero Image -->
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 mb-10">
            <div class="rounded-3xl overflow-hidden border border-amber-200 shadow-md">
                <img src="{{ $blog->image_url }}"
                     alt="{{ $blog->title }}"
                     class="w-full h-72 sm:h-96 md:h-[420px] object-cover">
            </div>
        </div>

        <!-- 4. Article Body Content -->
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="prose prose-slate max-w-none text-slate-800 text-sm sm:text-base leading-relaxed space-y-6">
                {!! $blog->content !!}
            </div>

            <!-- Social Share Bar -->
            <div class="mt-12 pt-6 border-t border-b border-amber-200/60 py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">
                    Share Divine Wisdom with Family & Friends:
                </span>
                <div class="flex items-center gap-3" x-data="{ copied: false }">
                    <a href="https://api.whatsapp.com/send?text={{ urlencode($blog->title . ' - Read this divine guide on Blu Bliss: ' . url()->current()) }}"
                       target="_blank"
                       class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg transition shadow-xs">
                        <span>WhatsApp</span>
                    </a>

                    <button type="button"
                            @click="navigator.clipboard.writeText(window.location.href); copied = true; setTimeout(() => copied = false, 2500)"
                            class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-white hover:bg-slate-50 text-slate-700 border border-slate-300 text-xs font-bold rounded-lg transition shadow-xs">
                        <span x-text="copied ? '✓ Link Copied!' : 'Copy Link'"></span>
                    </button>
                </div>
            </div>

            <!-- Back to Articles Button -->
            <div class="mt-8 flex justify-between items-center">
                <a href="{{ route('blogs.index') }}"
                   class="inline-flex items-center gap-1.5 text-xs font-bold text-[#D97706] hover:underline">
                    <span>← Back to all Articles</span>
                </a>
            </div>
        </div>

        <!-- 5. Recommended Sacred Offerings (Contextual Cross-sell) -->
        @if ($recommendedProducts->isNotEmpty())
            <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-16 pt-12 border-t border-amber-200/50">
                <div class="text-center max-w-xl mx-auto mb-8">
                    <span class="text-xs font-bold uppercase tracking-wider text-[#D97706]">Vedic Essentials</span>
                    <h2 class="font-serif font-bold text-2xl sm:text-3xl text-slate-900 mt-1">
                        Consecrated Offerings from Sacred Haridwar
                    </h2>
                    <p class="text-xs text-slate-500 mt-1">
                        Enhance your pooja ritual with certified authentic Ganga water collected at dawn.
                    </p>
                </div>

                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                    @foreach ($recommendedProducts as $prod)
                        <div class="bg-white rounded-2xl overflow-hidden border border-amber-200/60 shadow-xs hover:shadow-md transition p-3 flex flex-col justify-between">
                            <a href="{{ route('products.show', $prod->slug) }}" class="block">
                                <div class="h-36 rounded-xl overflow-hidden bg-slate-50 mb-3">
                                    <img src="{{ $prod->image_url }}" alt="{{ $prod->name }}" class="w-full h-full object-cover">
                                </div>
                                <h3 class="font-bold text-slate-900 text-xs line-clamp-1 hover:text-[#D97706]">
                                    {{ $prod->name }}
                                </h3>
                                <div class="mt-1 flex items-center justify-between">
                                    @if ($prod->isInStock())
                                        <span class="font-bold font-mono text-xs text-slate-900">₹{{ number_format($prod->price, 0) }}</span>
                                        @if ($prod->is_gangajal)
                                            <span class="text-[10px] text-sky-700 font-bold bg-sky-50 px-1.5 py-0.5 rounded">Free for Members</span>
                                        @endif
                                    @else
                                        <span class="text-[10px] text-rose-600 font-bold uppercase tracking-wider">Out of Stock</span>
                                    @endif
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        <!-- 6. Related Articles -->
        @if ($relatedBlogs->isNotEmpty())
            <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-16 pt-12 border-t border-amber-200/50">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-[#D97706]">Continue Reading</span>
                        <h2 class="font-serif font-bold text-2xl text-slate-900 mt-0.5">Related Spiritual Wisdom</h2>
                    </div>
                    <a href="{{ route('blogs.index') }}" class="text-xs font-bold text-[#D97706] hover:underline">
                        View all articles →
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach ($relatedBlogs as $rel)
                        <article class="bg-white rounded-2xl overflow-hidden border border-amber-200/60 shadow-xs hover:shadow-md transition flex flex-col justify-between group">
                            <a href="{{ route('blogs.show', $rel->slug) }}" class="block">
                                <div class="h-40 overflow-hidden bg-slate-100">
                                    <img src="{{ $rel->image_url }}" alt="{{ $rel->title }}" class="w-full h-full object-cover transform group-hover:scale-105 transition duration-500">
                                </div>
                                <div class="p-5">
                                    <span class="text-[10px] font-bold uppercase text-[#D97706] block mb-1">{{ $rel->category }}</span>
                                    <h3 class="font-serif font-bold text-base text-slate-900 group-hover:text-[#881337] transition line-clamp-2">
                                        {{ $rel->title }}
                                    </h3>
                                    <p class="text-xs text-slate-500 mt-1 line-clamp-2">{{ $rel->excerpt }}</p>
                                </div>
                            </a>
                            <div class="px-5 pb-4 text-xs font-bold text-[#D97706]">
                                Read Guide →
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>
        @endif
    </article>
</x-app-layout>

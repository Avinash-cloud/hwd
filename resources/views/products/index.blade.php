<x-app-layout>
    <!-- Breadcrumb & Header Banner -->
    <div class="bg-[#FFFDF7] border-b border-amber-200/60 py-6 sm:py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumbs -->
            <nav class="flex items-center gap-2 text-xs text-slate-500 mb-2">
                <a href="{{ route('home') }}" class="hover:text-[#D97706] transition">Home</a>
                <span>/</span>
                <a href="{{ route('products.index') }}" class="hover:text-[#D97706] transition">Catalog</a>
                @if (request('category'))
                    <span>/</span>
                    <span class="font-bold text-slate-800 capitalize">{{ str_replace('-', ' ', request('category')) }}</span>
                @endif
            </nav>

            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-serif font-extrabold text-slate-900 tracking-tight">
                        @if (request('category'))
                            {{ ucwords(str_replace('-', ' ', request('category'))) }}
                        @elseif (request('type') === 'gangajal')
                            Sacred Brahmakund Gangajal Editions
                        @elseif (request('search'))
                            Search Results for "{{ request('search') }}"
                        @else
                            Sacred Spiritual Catalog & Puja Samagri
                        @endif
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-600 mt-1 max-w-2xl">
                        Explore authentic Brahmakund Gangajal, cow dung hawan cups, pure cotton battis, and temple utensils blessed at Har Ki Pauri.
                    </p>
                </div>

                <div class="text-xs text-slate-500 font-semibold shrink-0">
                    Showing <span class="font-bold text-slate-900">{{ $products->total() }}</span> sacred offerings
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Area with Sidebar Filters + Product Grid -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Left Sidebar Filters -->
            <aside class="space-y-6">
                <!-- Non-Member Reminder Callout -->
                @if (!Auth::check() || !Auth::user()->hasActiveMembership())
                    <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200/80 text-xs shadow-xs space-y-2">
                        <div class="flex items-center gap-2 text-[#D97706] font-bold">
                            <span class="w-2 h-2 rounded-full bg-[#D97706]"></span>
                            <span>Devotee Privilege</span>
                        </div>
                        <p class="text-slate-600 leading-relaxed text-[11px]">
                            Join our 5-Year Membership (₹{{ number_format($membershipFee) }}) to receive your 1st Brahmakund bottle FREE every month and unlock member rates.
                        </p>
                        <a href="{{ route('membership.join') }}"
                           class="block text-center w-full py-2 bg-[#D97706] hover:bg-[#F97316] text-white rounded-xl font-bold text-[11px] shadow-xs transition">
                            Join 5-Year Membership
                        </a>
                    </div>
                @endif

                <div class="bg-white p-5 rounded-2xl border border-amber-200/60 shadow-xs space-y-6">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <h3 class="font-bold text-sm text-slate-900">Filter Offerings</h3>
                        @if (request()->hasAny(['category', 'type', 'search', 'min_price', 'max_price', 'sort']))
                            <a href="{{ route('products.index') }}" class="text-[11px] font-bold text-[#D97706] hover:underline">
                                Reset All
                            </a>
                        @endif
                    </div>

                    <!-- Category Hierarchy Filter -->
                    <div class="space-y-2">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 font-mono">
                            Collections
                        </h4>
                        <div class="space-y-1 text-xs">
                            <a href="{{ route('products.index') }}"
                               class="flex items-center justify-between py-1.5 px-2 rounded-lg transition {{ !request()->has('category') && !request()->has('type') ? 'bg-amber-100/70 text-[#D97706] font-bold' : 'text-slate-700 hover:bg-slate-50' }}">
                                <span>All Categories</span>
                            </a>
                            <a href="{{ route('products.index', ['type' => 'gangajal']) }}"
                               class="flex items-center justify-between py-1.5 px-2 rounded-lg transition {{ request('type') === 'gangajal' ? 'bg-amber-100/70 text-[#D97706] font-bold' : 'text-slate-700 hover:bg-slate-50' }}">
                                <span>Sacred Gangajal Only</span>
                            </a>
                            @foreach ($categories as $cat)
                                <div x-data="{ catExpanded: {{ request('category') === $cat->slug || ($cat->children && $cat->children->pluck('slug')->contains(request('category'))) ? 'true' : 'false' }} }">
                                    <div class="flex items-center justify-between py-1.5 px-2 rounded-lg transition {{ request('category') === $cat->slug ? 'bg-amber-100/70 text-[#D97706] font-bold' : 'text-slate-700 hover:bg-slate-50' }}">
                                        <a href="{{ route('products.index', ['category' => $cat->slug]) }}" class="flex-1">
                                            {{ $cat->name }}
                                        </a>
                                        @if ($cat->children && $cat->children->count() > 0)
                                            <button type="button" @click="catExpanded = !catExpanded" class="p-1 text-slate-400 hover:text-slate-600">
                                                <svg class="w-3.5 h-3.5 transition transform" :class="catExpanded ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                                </svg>
                                            </button>
                                        @endif
                                    </div>
                                    @if ($cat->children && $cat->children->count() > 0)
                                        <div x-show="catExpanded" class="pl-4 py-1 space-y-1 border-l-2 border-amber-200/50 ml-2">
                                            @foreach ($cat->children as $sub)
                                                <a href="{{ route('products.index', ['category' => $sub->slug]) }}"
                                                   class="block py-1 px-2 rounded text-[11px] transition {{ request('category') === $sub->slug ? 'text-[#D97706] font-bold bg-amber-50' : 'text-slate-600 hover:text-slate-900' }}">
                                                    {{ $sub->name }}
                                                </a>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Price Filter -->
                    <div class="pt-4 border-t border-slate-100 space-y-2">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 font-mono">
                            Price Range
                        </h4>
                        <div class="space-y-1.5 text-xs text-slate-700">
                            <a href="{{ request()->fullUrlWithQuery(['min_price' => null, 'max_price' => 200]) }}"
                               class="flex items-center gap-2 py-1 px-2 rounded hover:bg-slate-50 {{ request('max_price') == '200' ? 'font-bold text-[#D97706]' : '' }}">
                                <span>Under ₹200</span>
                            </a>
                            <a href="{{ request()->fullUrlWithQuery(['min_price' => 200, 'max_price' => 400]) }}"
                               class="flex items-center gap-2 py-1 px-2 rounded hover:bg-slate-50 {{ request('min_price') == '200' && request('max_price') == '400' ? 'font-bold text-[#D97706]' : '' }}">
                                <span>₹200 - ₹400</span>
                            </a>
                            <a href="{{ request()->fullUrlWithQuery(['min_price' => 400, 'max_price' => 700]) }}"
                               class="flex items-center gap-2 py-1 px-2 rounded hover:bg-slate-50 {{ request('min_price') == '400' && request('max_price') == '700' ? 'font-bold text-[#D97706]' : '' }}">
                                <span>₹400 - ₹700</span>
                            </a>
                            <a href="{{ request()->fullUrlWithQuery(['min_price' => 700, 'max_price' => null]) }}"
                               class="flex items-center gap-2 py-1 px-2 rounded hover:bg-slate-50 {{ request('min_price') == '700' ? 'font-bold text-[#D97706]' : '' }}">
                                <span>Above ₹700</span>
                            </a>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- Right Product Grid Area -->
            <main class="lg:col-span-3 space-y-6">
                <!-- Sorting & View Controls Bar -->
                <div class="bg-white p-3.5 sm:p-4 rounded-2xl border border-amber-200/60 shadow-xs flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 text-xs">
                    <!-- Search Indicator if active -->
                    <div>
                        @if (request('search'))
                            <span class="text-slate-600">
                                Results for "<strong class="text-slate-900">{{ request('search') }}</strong>"
                            </span>
                        @else
                            <span class="text-slate-500">Sorted by best divine quality</span>
                        @endif
                    </div>

                    <!-- Sort Dropdown -->
                    <div class="flex items-center gap-2">
                        <label for="sort" class="text-slate-500 font-semibold shrink-0">Sort By:</label>
                        <select id="sort"
                                onchange="window.location.href = this.value"
                                class="text-xs font-bold text-slate-800 bg-stone-50 border border-amber-200 rounded-xl px-3 py-1.5 focus:ring-[#D97706] focus:border-[#D97706]">
                            <option value="{{ request()->fullUrlWithQuery(['sort' => 'featured']) }}" {{ request('sort', 'featured') === 'featured' ? 'selected' : '' }}>
                                Featured Offerings
                            </option>
                            <option value="{{ request()->fullUrlWithQuery(['sort' => 'rating_high']) }}" {{ request('sort') === 'rating_high' ? 'selected' : '' }}>
                                Highest Customer Rating
                            </option>
                            <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_low']) }}" {{ request('sort') === 'price_low' ? 'selected' : '' }}>
                                Price: Low to High
                            </option>
                            <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_high']) }}" {{ request('sort') === 'price_high' ? 'selected' : '' }}>
                                Price: High to Low
                            </option>
                            <option value="{{ request()->fullUrlWithQuery(['sort' => 'newest']) }}" {{ request('sort') === 'newest' ? 'selected' : '' }}>
                                Newest Arrivals
                            </option>
                        </select>
                    </div>
                </div>

                <!-- Products Grid -->
                @if ($products->count() > 0)
                    <div class="grid grid-cols-2 sm:grid-cols-2 xl:grid-cols-3 gap-4 sm:gap-6">
                        @foreach ($products as $prod)
                            <x-product-card :product="$prod" />
                        @endforeach
                    </div>

                    <!-- Pagination Links -->
                    <div class="pt-6">
                        {{ $products->links() }}
                    </div>
                @else
                    <div class="bg-white rounded-3xl border border-amber-200/80 p-12 text-center space-y-4 shadow-xs">
                        <div class="w-16 h-16 rounded-full bg-amber-50 border border-amber-200 flex items-center justify-center mx-auto text-[#D97706]">
                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-serif font-bold text-slate-900">No offerings match your selected criteria</h3>
                        <p class="text-xs text-slate-500 max-w-md mx-auto">
                            Try broadening your price filters or exploring other sacred categories such as Puja Essentials, Hawan Samagri, or Sacred Gangajal.
                        </p>
                        <a href="{{ route('products.index') }}"
                           class="inline-flex items-center gap-2 px-6 py-2.5 bg-[#D97706] hover:bg-[#F97316] text-white rounded-xl font-bold text-xs shadow-xs transition">
                            Reset All Filters
                        </a>
                    </div>
                @endif
            </main>
        </div>
    </div>
</x-app-layout>

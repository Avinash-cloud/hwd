<header x-data="navigationComponent()" class="sticky top-0 z-40 bg-white shadow-xs w-full">
    <!-- 1. Top Announcement Bar (99Pandit style) -->
    <div class="bg-[#881337] text-white text-xs py-2 px-3 sm:px-4 shadow-inner overflow-hidden w-full">
        <div class="max-w-7xl mx-auto flex items-center justify-between gap-3 min-w-0">
            <!-- Left: Rotating / Static Message -->
            <div class="flex items-center gap-2 overflow-hidden whitespace-nowrap min-w-0 flex-1">
                <span class="inline-flex items-center text-amber-400 shrink-0">
                    <svg class="w-3.5 h-3.5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.527.82-1.124 1.903-1.685 3.226C7.302 8.877 6 12.188 6 15a6 6 0 1012 0c0-3.327-1.89-6.44-3.605-8.892a29.13 29.13 0 00-2-2.555z" clip-rule="evenodd" />
                    </svg>
                </span>
                <span class="font-bold text-amber-200 tracking-wide text-xs">हर हर गंगे!</span>
                <span class="font-medium text-white/95 truncate">
                    100% Authentic Brahmakund Gangajal & Vedic Essentials · Directly Sourced from Har Ki Pauri
                </span>
                <span class="hidden md:inline text-white/40 shrink-0">|</span>
                <a href="{{ route('membership.join') }}" class="hidden md:inline text-amber-200 hover:text-white font-bold underline decoration-amber-400 decoration-1 underline-offset-2 shrink-0">
                    🌟 5-Year Members Receive 1 Free Gangajal Bottle Every Month
                </a>
            </div>

            <!-- Right: Batch Verification & Support Links -->
            <div class="hidden lg:flex items-center gap-4 text-[11px] text-white/80 shrink-0">
                <a href="{{ route('about') }}" class="hover:text-amber-200 transition">Brahmakund Sanctity</a>
                <span>·</span>
                <a href="{{ route('contact') }}" class="hover:text-amber-200 transition">Devotee Support</a>
                <span>·</span>
                <span class="inline-flex items-center gap-1 text-amber-300 font-bold">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                    <span>+91 7042482555</span>
                </span>
            </div>
        </div>
    </div>

    <!-- 2. Main Header Bar (Logo, Predictive Search, Account, Wishlist, Cart) -->
    <div class="relative z-20 border-b border-amber-200/50 bg-white w-full">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 sm:h-20 gap-2 sm:gap-4 lg:gap-8 min-w-0">
                <!-- Mobile Menu Button -->
                <div class="flex lg:hidden items-center shrink-0">
                    <button type="button"
                            @click="mobileMenuOpen = !mobileMenuOpen"
                            class="p-1.5 sm:p-2 text-slate-700 hover:text-[#D97706] hover:bg-amber-50 rounded-xl transition touch-manipulation"
                            aria-label="Toggle Navigation Menu">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path x-show="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Brand Logo -->
                <div class="min-w-0 flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center gap-2 sm:gap-3 group min-w-0">
                        <!-- <x-application-logo class="block h-9 w-9 sm:h-12 sm:w-12 rounded-xl object-cover shadow-sm ring-1 ring-amber-400/40 transition transform group-hover:scale-105 shrink-0" /> -->
                        <div class="leading-tight min-w-0">
                            <span class="block font-serif font-extrabold italic text-base sm:text-2xl text-[#701A28] tracking-tight drop-shadow-[1.5px_1.5px_0px_#06b6d4] group-hover:text-[#881337] transition truncate">
                                BluBliss
                            </span>
                            <span class="hidden sm:block text-[9px] font-bold tracking-widest text-[#B45309] uppercase">
                                Brahmakund Haridwar Gangajal
                            </span>
                        </div>
                    </a>
                </div>

                <!-- Central Predictive Search Bar -->
                <div class="flex-1 max-w-2xl hidden md:block relative"
                     @click.away="searchOpen = false">
                    <form action="{{ route('products.index') }}" method="GET" class="relative">
                        <div class="relative flex items-center">
                            <input type="search"
                                   name="search"
                                   x-model="searchQuery"
                                   @input.debounce.250ms="performSearch()"
                                   @focus="if(searchResults.length > 0) searchOpen = true"
                                   placeholder="Search pure gangajal, cotton wicks, hawan cups, rudraksha, dhoop..."
                                   autocomplete="off"
                                   class="w-full pl-11 pr-24 py-2.5 bg-amber-50/40 hover:bg-white focus:bg-white border border-amber-300/80 focus:border-[#D97706] focus:ring-2 focus:ring-[#D97706]/20 rounded-full text-sm text-[#3B2314] placeholder:text-stone-400 transition shadow-inner">
                            
                            <!-- Search Icon -->
                            <div class="absolute left-4 text-slate-400 pointer-events-none">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>

                            <!-- Search Button -->
                            <button type="submit"
                                    class="absolute right-1.5 px-4 py-1.5 bg-[#D97706] hover:bg-[#F97316] text-white rounded-full text-xs font-bold transition shadow-xs">
                                Search
                            </button>
                        </div>
                    </form>

                    <!-- Predictive Search Results Dropdown -->
                    <div x-show="searchOpen && searchResults.length > 0"
                         x-cloak
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 translate-y-1"
                         class="absolute left-0 right-0 mt-2 bg-white rounded-2xl shadow-2xl border border-amber-200/80 overflow-hidden z-50 divide-y divide-slate-100">
                        <div class="p-2 max-h-96 overflow-y-auto space-y-1">
                            <template x-for="item in searchResults" :key="item.id">
                                <a :href="item.url"
                                   class="flex items-center gap-3 p-2 rounded-xl hover:bg-amber-50/70 transition group">
                                    <img :src="item.image || 'https://images.unsplash.com/photo-1606787366850-de6330128bfc?w=120&fit=crop'"
                                         :alt="item.name"
                                         class="w-12 h-12 rounded-lg object-cover bg-slate-50 border border-slate-200 shrink-0">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-1.5 text-[10px] text-slate-500">
                                            <span class="font-bold text-[#D97706]" x-text="item.category"></span>
                                            <span>·</span>
                                            <span class="inline-flex items-center gap-0.5 text-amber-500 font-bold">
                                                <svg class="w-3 h-3 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                                <span x-text="item.rating"></span>
                                            </span>
                                        </div>
                                        <h6 class="text-xs font-bold text-[#701A28] truncate group-hover:text-[#D97706] transition"
                                            x-text="item.name"></h6>
                                        <div class="flex items-center gap-2 mt-0.5">
                                            <template x-if="item.in_stock">
                                                <div class="flex items-center gap-2">
                                                    <span class="text-xs font-extrabold text-[#701A28]" x-text="'₹' + item.price"></span>
                                                    <template x-if="item.compare_price">
                                                        <span class="text-[10px] text-slate-400 line-through" x-text="'₹' + item.compare_price"></span>
                                                    </template>
                                                    <template x-if="item.discount">
                                                        <span class="text-[9px] font-bold text-emerald-700 bg-emerald-50 px-1 rounded" x-text="item.discount + '% OFF'"></span>
                                                    </template>
                                                </div>
                                            </template>
                                            <template x-if="!item.in_stock">
                                                <span class="text-[10px] font-bold text-rose-600 uppercase tracking-wider">Out of Stock</span>
                                            </template>
                                        </div>
                                    </div>
                                    <svg class="w-4 h-4 text-slate-300 group-hover:text-[#D97706] group-hover:translate-x-0.5 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </template>
                        </div>
                        <div class="bg-stone-50 px-4 py-2.5 text-center">
                            <a :href="'{{ route('products.index') }}?search=' + encodeURIComponent(searchQuery)"
                               class="text-xs font-bold text-[#D97706] hover:underline">
                                View all matching sacred offerings →
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Right Action Icons: 5-Yr Membership, Account, Wishlist, Cart Drawer -->
                <div class="flex items-center gap-1.5 sm:gap-4 shrink-0">
                    <!-- 5-Year Devotee Membership Pill -->
                    @auth
                        @if (Auth::user()->hasActiveMembership())
                            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-[#D97706]/15 text-[#881337] border border-[#D97706]/40 hover:bg-[#D97706]/25 transition">
                                <span class="w-2 h-2 rounded-full bg-[#D97706] animate-pulse"></span>
                                <span>🌟 5-Yr Devotee Pass (Active)</span>
                            </a>
                        @else
                            <a href="{{ route('membership.join') }}"
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 sm:px-4 sm:py-2 rounded-xl text-xs font-extrabold bg-gradient-to-r from-[#881337] via-[#991B1B] to-[#D97706] text-white hover:brightness-110 transition shadow-md shadow-amber-900/20 transform hover:-translate-y-0.5 animate-pulse">
                                <span>🌟 5-Yr Devotee Pass</span>
                                <span class="hidden md:inline text-amber-200 text-[11px] font-semibold">· Free Monthly Gangajal</span>
                            </a>
                        @endif
                    @else
                        <a href="{{ route('membership.join') }}"
                           class="inline-flex items-center gap-1.5 px-3 py-1.5 sm:px-4 sm:py-2 rounded-xl text-xs font-extrabold bg-gradient-to-r from-[#881337] via-[#991B1B] to-[#D97706] text-white hover:brightness-110 transition shadow-md shadow-amber-900/20 transform hover:-translate-y-0.5 animate-pulse">
                            <span>🌟 5-Yr Devotee Pass</span>
                            <span class="hidden md:inline text-amber-200 text-[11px] font-semibold">· Free Monthly Gangajal</span>
                        </a>
                    @endauth

                    <!-- Account Menu -->
                    <div class="relative" x-data="{ userMenuOpen: false }" @scroll.window="userMenuOpen = false">
                        @auth
                            <button type="button"
                                    @click="userMenuOpen = !userMenuOpen"
                                    class="flex items-center gap-1 sm:gap-2 p-1 sm:p-2 text-slate-700 hover:text-[#D97706] hover:bg-amber-50 rounded-xl transition touch-manipulation">
                                <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-amber-100 border border-amber-300 flex items-center justify-center font-bold text-xs text-[#D97706]">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                <span class="hidden lg:inline text-xs font-semibold text-slate-800">
                                    {{ Str::limit(Auth::user()->name, 10) }}
                                </span>
                                <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>

                            <div x-show="userMenuOpen"
                                 x-cloak
                                 @click.away="userMenuOpen = false"
                                 x-transition:enter="transition ease-out duration-150"
                                 x-transition:enter-start="opacity-0 translate-y-1 scale-95"
                                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                 x-transition:leave="transition ease-in duration-100"
                                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                 x-transition:leave-end="opacity-0 translate-y-1 scale-95"
                                 class="absolute right-0 top-full mt-2 w-56 bg-white rounded-2xl shadow-2xl border border-amber-200/80 py-1.5 z-50 text-xs">
                                <div class="px-4 py-2.5 border-b border-slate-100 bg-amber-50/40 rounded-t-2xl">
                                    <p class="font-bold text-[#701A28] truncate">{{ Auth::user()->name }}</p>
                                    <p class="text-[11px] text-slate-500 truncate">{{ Auth::user()->email }}</p>
                                </div>
                                <div class="py-1">
                                    @if (Auth::user()->is_admin)
                                        <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-amber-700 font-bold hover:bg-amber-50 transition">
                                            Admin Portal
                                        </a>
                                    @endif
                                    <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-slate-700 hover:bg-amber-50 font-medium transition">
                                        My Orders & Address
                                    </a>
                                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-slate-700 hover:bg-amber-50 font-medium transition">
                                        Profile Settings
                                    </a>
                                </div>
                                <div class="border-t border-slate-100 pt-1">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full text-left px-4 py-2 text-rose-600 hover:bg-rose-50 font-bold transition">
                                            Sign Out
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <a href="{{ route('login') }}"
                               class="flex items-center gap-1.5 p-2 text-slate-700 hover:text-[#D97706] hover:bg-amber-50 rounded-xl transition text-xs font-bold"
                               title="Account Login">
                                <svg class="w-5 h-5 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                <span class="hidden sm:inline">Sign In</span>
                            </a>
                        @endauth
                    </div>

                    <!-- Cart Drawer Trigger Button (99Pandit Style) -->
                    @php
                        $cartCount = app(\App\Services\CartService::class)->count();
                    @endphp
                    <button type="button"
                            @click="$dispatch('open-cart-drawer')"
                            class="relative flex items-center gap-1.5 px-2.5 sm:px-3 py-1.5 sm:py-2 bg-amber-50 hover:bg-amber-100 border border-amber-300 text-[#701A28] rounded-xl transition shadow-xs group touch-manipulation"
                            aria-label="Shopping Cart">
                        <div class="relative">
                            <svg class="w-5 h-5 text-[#701A28] group-hover:text-[#D97706] transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                            <span class="cart-count-badge absolute -top-2 -right-2.5 inline-flex items-center justify-center px-1.5 py-0.5 text-[10px] font-extrabold leading-none text-white bg-[#D97706] rounded-full shadow {{ $cartCount > 0 ? '' : 'hidden' }}">
                                {{ $cartCount }}
                            </span>
                        </div>
                        <span class="hidden lg:inline text-xs font-bold">Cart</span>
                    </button>
                </div>
            </div>

            <!-- Mobile Search Bar (visible on mobile only) -->
            <div class="md:hidden pb-3">
                <form action="{{ route('products.index') }}" method="GET" class="relative">
                    <input type="search"
                           name="search"
                           placeholder="Search Gangajal, Wicks, Hawan Cups..."
                           class="w-full pl-10 pr-4 py-2 bg-amber-50/50 border border-amber-200 rounded-xl text-xs text-[#3B2314] placeholder:text-stone-400">
                    <div class="absolute left-3 top-2.5 text-slate-400">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- 3. Category Horizontal Navigation Menu (Single Line, 99Pandit Style) -->
    <nav class="relative z-10 hidden lg:block bg-[#FFFDF7] border-b border-[#D97706]/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-11 text-xs">
                <!-- Left: Category List with dropdowns in a single continuous line -->
                <div class="flex-1 min-w-0 flex items-center gap-0.5 xl:gap-1.5 font-medium text-slate-700 whitespace-nowrap overflow-visible py-1">
                    <!-- Home -->
                    <a href="{{ route('home') }}"
                       class="whitespace-nowrap shrink-0 px-2 xl:px-2.5 py-1.5 rounded-lg text-[11.5px] xl:text-xs font-semibold hover:text-[#D97706] hover:bg-amber-100/50 transition {{ request()->routeIs('home') ? 'text-[#D97706] font-bold bg-amber-100/60' : '' }}">
                        Home
                    </a>

                    <!-- Dynamic Categories from navCategories -->
                    @if (isset($navCategories) && $navCategories->count() > 0)
                        @foreach ($navCategories->take(4) as $cat)
                            <div class="relative group shrink-0" x-data="{ catOpen: false }" @mouseenter="catOpen = true" @mouseleave="catOpen = false">
                                <a href="{{ route('products.index', ['category' => $cat->slug]) }}"
                                   class="whitespace-nowrap shrink-0 inline-flex items-center gap-0.5 xl:gap-1 px-2 xl:px-2.5 py-1.5 rounded-lg text-[11.5px] xl:text-xs font-semibold hover:text-[#D97706] hover:bg-amber-100/50 transition {{ request()->get('category') === $cat->slug ? 'text-[#D97706] font-bold bg-amber-100/60' : '' }}">
                                    <span>{{ $cat->name }}</span>
                                    @if ($cat->children && $cat->children->count() > 0)
                                        <svg class="w-3 h-3 text-slate-400 group-hover:text-[#D97706] transition transform group-hover:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    @endif
                                </a>

                                <!-- Subcategories Dropdown -->
                                @if ($cat->children && $cat->children->count() > 0)
                                    <div x-show="catOpen"
                                         x-cloak
                                         x-transition:enter="transition ease-out duration-150"
                                         x-transition:enter-start="opacity-0 translate-y-1"
                                         x-transition:enter-end="opacity-100 translate-y-0"
                                         x-transition:leave="transition ease-in duration-100"
                                         x-transition:leave-start="opacity-100 translate-y-0"
                                         x-transition:leave-end="opacity-0 translate-y-1"
                                         class="absolute left-0 top-full mt-0 w-56 bg-white rounded-xl shadow-xl border border-amber-200 py-2 z-40 divide-y divide-slate-100">
                                        <div class="px-3 py-1.5 text-[10px] uppercase font-bold text-slate-400">
                                            {{ $cat->name }} Collections
                                        </div>
                                        <div class="py-1">
                                            @foreach ($cat->children as $sub)
                                                <a href="{{ route('products.index', ['category' => $sub->slug]) }}"
                                                   class="block px-3 py-1.5 text-xs text-slate-700 hover:text-[#D97706] hover:bg-amber-50 font-medium transition">
                                                    {{ $sub->name }}
                                                </a>
                                            @endforeach
                                        </div>
                                        <div class="pt-1 px-3">
                                            <a href="{{ route('products.index', ['category' => $cat->slug]) }}"
                                               class="text-[11px] font-bold text-[#D97706] hover:underline">
                                                All in {{ $cat->name }} →
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach

                        <!-- More Categories Dropdown (if more than 4) -->
                        @if ($navCategories->count() > 4)
                            <div class="relative group shrink-0" x-data="{ moreCatOpen: false }" @mouseenter="moreCatOpen = true" @mouseleave="moreCatOpen = false">
                                <button type="button"
                                        @click="moreCatOpen = !moreCatOpen"
                                        class="whitespace-nowrap shrink-0 inline-flex items-center gap-0.5 xl:gap-1 px-2 xl:px-2.5 py-1.5 rounded-lg text-[11.5px] xl:text-xs font-semibold hover:text-[#D97706] hover:bg-amber-100/50 transition text-slate-700">
                                    <span>More Collections</span>
                                    <svg class="w-3 h-3 text-slate-400 group-hover:text-[#D97706] transition transform group-hover:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>

                                <div x-show="moreCatOpen"
                                     x-cloak
                                     x-transition:enter="transition ease-out duration-150"
                                     x-transition:enter-start="opacity-0 translate-y-1"
                                     x-transition:enter-end="opacity-100 translate-y-0"
                                     x-transition:leave="transition ease-in duration-100"
                                     x-transition:leave-start="opacity-100 translate-y-0"
                                     x-transition:leave-end="opacity-0 translate-y-1"
                                     class="absolute left-0 top-full mt-0 w-60 bg-white rounded-xl shadow-xl border border-amber-200 py-2 z-40 divide-y divide-slate-100">
                                    <div class="py-1">
                                        @foreach ($navCategories->slice(4) as $moreCat)
                                            <a href="{{ route('products.index', ['category' => $moreCat->slug]) }}"
                                               class="block px-3 py-1.5 text-xs text-slate-700 hover:text-[#D97706] hover:bg-amber-50 font-semibold transition">
                                                {{ $moreCat->name }}
                                            </a>
                                        @endforeach
                                    </div>
                                    <div class="pt-1.5 px-3">
                                        <a href="{{ route('products.index') }}" class="text-[11px] font-bold text-[#D97706] hover:underline">
                                            View Complete Catalog →
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @else
                        <a href="{{ route('products.index', ['category' => 'puja-essentials']) }}" class="whitespace-nowrap px-2.5 py-1.5 rounded-lg text-xs font-semibold hover:text-[#D97706] transition">Puja Essentials</a>
                        <a href="{{ route('products.index', ['category' => 'hawan-samagri']) }}" class="whitespace-nowrap px-2.5 py-1.5 rounded-lg text-xs font-semibold hover:text-[#D97706] transition">Hawan & Samagri</a>
                        <a href="{{ route('products.index', ['category' => 'sacred-gangajal']) }}" class="whitespace-nowrap px-2.5 py-1.5 rounded-lg text-xs font-semibold hover:text-[#D97706] transition">Sacred Gangajal</a>
                    @endif

                    <!-- All Sacred Catalog -->
                    <a href="{{ route('products.index') }}"
                       class="whitespace-nowrap shrink-0 px-2 xl:px-2.5 py-1.5 rounded-lg text-[11.5px] xl:text-xs font-semibold hover:text-[#D97706] hover:bg-amber-100/50 transition {{ request()->routeIs('products.index') && !request()->has('category') ? 'text-[#D97706] font-bold' : '' }}">
                        Complete Catalog
                    </a>

                    <!-- Spiritual Wisdom Blogs (hidden on lg, visible on xl) -->
                    <a href="{{ route('blogs.index') }}"
                       class="hidden xl:inline-block whitespace-nowrap shrink-0 px-2 xl:px-2.5 py-1.5 rounded-lg text-[11.5px] xl:text-xs font-semibold hover:text-[#D97706] hover:bg-amber-100/50 transition {{ request()->routeIs('blogs.*') ? 'text-[#D97706] font-bold bg-amber-100/60' : '' }}">
                        Spiritual Blogs
                    </a>

                    <!-- Sacred Gallery (hidden on lg/xl, visible on 2xl) -->
                    <a href="{{ route('gallery.index') }}"
                       class="hidden 2xl:inline-block whitespace-nowrap shrink-0 px-2 xl:px-2.5 py-1.5 rounded-lg text-[11.5px] xl:text-xs font-semibold hover:text-[#D97706] hover:bg-amber-100/50 transition {{ request()->routeIs('gallery.*') ? 'text-[#D97706] font-bold bg-amber-100/60' : '' }}">
                        Sacred Gallery
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- 4. Mobile Navigation Menu Accordion Drawer -->
    <div x-show="mobileMenuOpen"
         x-cloak
         x-transition
         class="lg:hidden bg-white border-b border-amber-200 divide-y divide-slate-100 text-sm">
        <div class="p-4 space-y-1">
            <a href="{{ route('home') }}" class="block px-3 py-2 rounded-lg font-bold text-[#701A28] hover:bg-amber-50">
                Home
            </a>
            @if (isset($navCategories))
                @foreach ($navCategories as $cat)
                    <div x-data="{ subOpen: false }">
                        <div class="flex items-center justify-between px-3 py-2 text-slate-800 font-semibold hover:bg-amber-50 rounded-lg">
                            <a href="{{ route('products.index', ['category' => $cat->slug]) }}" class="flex-1">
                                {{ $cat->name }}
                            </a>
                            @if ($cat->children && $cat->children->count() > 0)
                                <button type="button" @click="subOpen = !subOpen" class="p-1 text-slate-400">
                                    <svg class="w-4 h-4 transition transform" :class="subOpen ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                            @endif
                        </div>
                        @if ($cat->children && $cat->children->count() > 0)
                            <div x-show="subOpen" x-cloak class="pl-6 py-1 space-y-1 bg-amber-50/50 rounded-lg text-xs">
                                @foreach ($cat->children as $sub)
                                    <a href="{{ route('products.index', ['category' => $sub->slug]) }}"
                                       class="block py-1.5 text-slate-600 hover:text-[#D97706]">
                                        {{ $sub->name }}
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            @endif
            <a href="{{ route('products.index') }}" class="block px-3 py-2 rounded-lg font-bold text-[#701A28] hover:bg-amber-50">
                All Products Catalog
            </a>
            <a href="{{ route('blogs.index') }}" class="block px-3 py-2 rounded-lg font-semibold text-slate-800 hover:bg-amber-50">
                Spiritual Blogs & Wisdom
            </a>
            <a href="{{ route('gallery.index') }}" class="block px-3 py-2 rounded-lg font-semibold text-slate-800 hover:bg-amber-50">
                Sacred Haridwar Gallery
            </a>
            <a href="{{ route('membership.join') }}" class="block px-3 py-2 rounded-lg font-bold text-[#D97706] hover:bg-amber-50">
                5-Year Membership (₹500)
            </a>
            <a href="{{ route('about') }}" class="block px-3 py-2 rounded-lg font-semibold text-slate-700 hover:bg-amber-50">
                Brahmakund Sanctity Story
            </a>
        </div>
    </div>
</header>

<script>
if (typeof window.navigationComponent === 'undefined') {
    window.navigationComponent = function() {
        return {
            mobileMenuOpen: false,
            searchQuery: '',
            searchResults: [],
            searchOpen: false,

            performSearch() {
                if (this.searchQuery.trim().length < 2) {
                    this.searchResults = [];
                    this.searchOpen = false;
                    return;
                }

                fetch('{{ route('api.search') }}?q=' + encodeURIComponent(this.searchQuery), {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    this.searchResults = data || [];
                    this.searchOpen = this.searchResults.length > 0;
                })
                .catch(() => {
                    this.searchResults = [];
                });
            }
        };
    };
}
</script>
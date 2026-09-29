<nav x-data="{ open: false }"
    class="bg-white/95 backdrop-blur-md border-b border-[#D97706]/30 sticky top-0 z-50 shadow-xs">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-18 items-center">
            <!-- Left Brand & Links -->
            <div class="flex items-center gap-6">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                        <x-application-logo
                            class="block h-10 w-10 shadow-sm rounded-xl transition transform group-hover:scale-105" />
                        <div>
                            <span
                                class="block font-serif font-extrabold text-xl text-slate-900 tracking-tight leading-none group-hover:text-[#D97706] transition">Haridwar
                                Bliss</span>
                            <span class="block text-[10px] uppercase font-bold tracking-widest text-[#D97706]">Sacred
                                Gangajal</span>
                        </div>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-6 lg:-my-px lg:ms-6 lg:flex">
                    <x-nav-link :href="route('home')" :active="request()->routeIs('home')">
                        {{ __('Home') }}
                    </x-nav-link>
                    <x-nav-link :href="route('products.index', ['type' => 'gangajal'])"
                        :active="request()->fullUrlIs(route('products.index', ['type' => 'gangajal']))">
                        {{ __('Sacred Gangajal') }}
                    </x-nav-link>
                    <x-nav-link :href="route('products.index')" :active="request()->routeIs('products.*') && !request()->has('type')">
                        {{ __('Spiritual Catalog') }}
                    </x-nav-link>
                    <x-nav-link :href="route('membership.join')" :active="request()->routeIs('membership.*')"
                        class="text-[#D97706] font-semibold">
                        {{ __('5-Yr Membership') }}
                    </x-nav-link>
                    <x-nav-link :href="route('about')" :active="request()->routeIs('about')">
                        {{ __('Brahmakund Sanctity') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Right Actions: Cart & Auth -->
            <div class="hidden lg:flex lg:items-center lg:gap-4">
                <!-- Cart Icon -->
                @php
                    $cartCount = app(\App\Services\CartService::class)->count();
                @endphp
                <a href="{{ route('cart.index') }}" class="relative p-2 text-slate-700 hover:text-[#D97706] transition"
                    title="View Sacred Cart">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    @if ($cartCount > 0)
                        <span
                            class="absolute top-0 right-0 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-white transform translate-x-1/4 -translate-y-1/4 bg-[#D97706] rounded-full shadow">
                            {{ $cartCount }}
                        </span>
                    @endif
                </a>

                @auth
                    <!-- Active Member Badge -->
                    @if (Auth::user()->hasActiveMembership())
                        <span
                            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-[#D97706]/10 text-[#D97706] border border-[#D97706]/30 shadow-xs">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#D97706] animate-pulse"></span>
                            5-Yr Member
                        </span>
                    @else
                        <a href="{{ route('membership.join') }}"
                            class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-[#D97706]/10 text-[#D97706] border border-[#D97706]/30 hover:bg-[#D97706]/20 transition">
                            Join for ₹500
                        </a>
                    @endif

                    @if (Auth::user()->is_admin)
                        <a href="{{ route('admin.dashboard') }}"
                            class="inline-flex items-center px-2.5 py-1 text-xs font-bold rounded-md bg-[#991B1B]/10 text-[#991B1B] border border-[#991B1B]/30 hover:bg-[#991B1B]/20 transition">
                            Admin Panel
                        </a>
                    @endif

                    <!-- User Dropdown -->
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button
                                class="inline-flex items-center px-3 py-2 border border-[#D97706]/30 text-sm leading-4 font-semibold rounded-xl text-slate-800 bg-[#FFFDF7] hover:bg-[#D97706]/10 focus:outline-none transition ease-in-out duration-150">
                                <div>{{ Auth::user()->name }}</div>
                                <div class="ms-1.5">
                                    <svg class="fill-current h-4 w-4 text-slate-600" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('dashboard')">
                                {{ __('My Account & Orders') }}
                            </x-dropdown-link>

                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile Settings') }}
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')" onclick="event.preventDefault();
                                                        this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <a href="{{ route('login') }}"
                        class="text-sm font-semibold text-slate-700 hover:text-[#D97706] px-3 py-2 transition">
                        Sign In
                    </a>
                    <a href="{{ route('membership.join') }}"
                        class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-[#D97706] hover:bg-[#F97316] rounded-xl shadow-sm transition">
                        Join Membership (₹500)
                    </a>
                @endauth
            </div>

            <!-- Mobile Hamburger -->
            <div class="-me-2 flex items-center lg:hidden gap-2">
                <a href="{{ route('cart.index') }}" class="relative p-2 text-slate-700">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    @if (($cartCount ?? 0) > 0)
                        <span
                            class="absolute top-1 right-1 px-1.5 py-0.5 text-[10px] font-bold text-white bg-[#D97706] rounded-full">
                            {{ $cartCount }}
                        </span>
                    @endif
                </a>
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-xl text-slate-500 hover:text-slate-700 hover:bg-[#FFFDF7] focus:outline-none transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Mobile Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden lg:hidden border-t border-[#D97706]/30 bg-white/98">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')">
                {{ __('Home') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('products.index', ['type' => 'gangajal'])">
                {{ __('Sacred Gangajal') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('products.index')">
                {{ __('Spiritual Catalog') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('membership.join')" class="text-[#D97706] font-bold">
                {{ __('5-Year Membership (₹500)') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('about')">
                {{ __('Brahmakund Sanctity') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('faqs')">
                {{ __('FAQs') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Auth Settings -->
        <div class="pt-4 pb-3 border-t border-[#D97706]/20 bg-[#FFFDF7]">
            @auth
                <div class="px-4 flex items-center justify-between mb-3">
                    <div>
                        <div class="font-bold text-base text-slate-800">{{ Auth::user()->name }}</div>
                        <div class="font-medium text-sm text-slate-500">{{ Auth::user()->email }}</div>
                    </div>
                    @if (Auth::user()->hasActiveMembership())
                        <span class="px-2.5 py-0.5 text-xs font-bold rounded-full bg-[#D97706]/10 text-[#D97706]">5-Yr
                            Member</span>
                    @endif
                </div>

                <div class="space-y-1">
                    <x-responsive-nav-link :href="route('dashboard')">
                        {{ __('My Account & Orders') }}
                    </x-responsive-nav-link>
                    @if (Auth::user()->is_admin)
                        <x-responsive-nav-link :href="route('admin.dashboard')" class="text-[#991B1B] font-bold">
                            {{ __('Admin Dashboard') }}
                        </x-responsive-nav-link>
                    @endif
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault();
                                                this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            @else
                <div class="px-4 py-2 flex flex-col gap-2">
                    <a href="{{ route('login') }}"
                        class="w-full text-center py-2 text-sm font-bold text-slate-700 bg-white border border-[#D97706]/30 rounded-xl">
                        Sign In
                    </a>
                    <a href="{{ route('membership.join') }}"
                        class="w-full text-center py-2 text-sm font-bold text-white bg-[#D97706] hover:bg-[#F97316] rounded-xl shadow">
                        Join 5-Year Membership (₹500)
                    </a>
                </div>
            @endauth
        </div>
    </div>
</nav>
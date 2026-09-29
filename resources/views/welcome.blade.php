<x-app-layout>
    <!-- 1. Hero Promotional Slideshow (Shop.99Pandit Style) -->
    <section class="relative overflow-hidden bg-stone-900 text-white"
             x-data="{
                 activeSlide: 0,
                 timer: null,
                 init() {
                     this.timer = setInterval(() => { this.nextSlide(); }, 6000);
                 },
                 nextSlide() {
                     this.activeSlide = (this.activeSlide + 1) % 3;
                 },
                 prevSlide() {
                     this.activeSlide = (this.activeSlide - 1 + 3) % 3;
                 },
                 goToSlide(idx) {
                     this.activeSlide = idx;
                 }
             }">
        <!-- Slides Wrapper -->
        <div class="relative w-full min-h-[380px] sm:min-h-[460px] lg:min-h-[520px] flex items-center">
            <!-- Slide 1: Sacred Gangajal & Membership -->
            <div x-show="activeSlide === 0"
                 x-transition:enter="transition ease-out duration-700"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-500"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="absolute inset-0 w-full h-full">
                <!-- Background Image & Gradient Overlay -->
                <img src="https://images.unsplash.com/photo-1596176530529-78163a4f7af2?w=1600&auto=format&fit=crop&q=80"
                     alt="Sacred Har Ki Pauri Haridwar"
                     class="w-full h-full object-cover object-center opacity-40">
                <div class="absolute inset-0 bg-gradient-to-r from-stone-950 via-stone-900/80 to-transparent"></div>

                <!-- Slide Content -->
                <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full flex items-center">
                    <div class="max-w-2xl space-y-4 sm:space-y-6 py-12">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold bg-[#D97706]/20 text-amber-300 border border-[#D97706]/40 backdrop-blur-xs">
                            <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                            <span>Directly from Har Ki Pauri · Brahmakund Consecration</span>
                        </div>

                        <h1 class="text-3xl sm:text-5xl lg:text-6xl font-serif font-extrabold tracking-tight leading-tight text-white">
                            Pure, Untouched <span class="text-[#D97706]">Gangajal</span> for Your Daily Abhishek
                        </h1>

                        <p class="text-sm sm:text-base text-slate-300 leading-relaxed max-w-xl">
                            Collected at Brahma Muhurta during morning Ganga Aarti from Har Ki Pauri. Hermetically sealed, lab tested, and trackable via unique batch QR codes.
                        </p>

                        <div class="flex flex-wrap items-center gap-3 pt-2">
                            <a href="{{ route('membership.join') }}"
                               class="inline-flex items-center gap-2 px-6 py-3.5 bg-[#D97706] hover:bg-[#F97316] text-white rounded-xl font-bold text-sm shadow-xl transition transform hover:-translate-y-0.5">
                                <span>Join 5-Year Membership (₹{{ number_format($membershipFee) }})</span>
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                </svg>
                            </a>
                            <a href="{{ route('products.index', ['category' => 'sacred-gangajal']) }}"
                               class="inline-flex items-center gap-2 px-6 py-3.5 bg-white/10 hover:bg-white/20 text-white border border-white/20 rounded-xl font-bold text-sm backdrop-blur-xs transition">
                                <span>Explore Holy Waters</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slide 2: Puja & Hawan Essentials -->
            <div x-show="activeSlide === 1"
                 x-transition:enter="transition ease-out duration-700"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-500"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="absolute inset-0 w-full h-full">
                <!-- Background Image & Gradient Overlay -->
                <img src="https://images.unsplash.com/photo-1544717305-2782549b5136?w=1600&auto=format&fit=crop&q=80"
                     alt="Sacred Hawan Ceremony"
                     class="w-full h-full object-cover object-center opacity-40">
                <div class="absolute inset-0 bg-gradient-to-r from-stone-950 via-stone-900/80 to-transparent"></div>

                <!-- Slide Content -->
                <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full flex items-center">
                    <div class="max-w-2xl space-y-4 sm:space-y-6 py-12">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold bg-[#881337]/50 text-rose-300 border border-[#881337] backdrop-blur-xs">
                            <span class="w-1.5 h-1.5 rounded-full bg-rose-400"></span>
                            <span>100% Charcoal-Free & Desi Cow Dung Base</span>
                        </div>

                        <h2 class="text-3xl sm:text-5xl lg:text-6xl font-serif font-extrabold tracking-tight leading-tight text-white">
                            Ready-To-Use <span class="text-amber-400">Hawan Cups</span> & Pure Cotton Battis
                        </h2>

                        <p class="text-sm sm:text-base text-slate-300 leading-relaxed max-w-xl">
                            Infuse your home with sacred Vedic vibrations in just 15 minutes. Premium cow dung hawan cups with pure loban, guggal, and long-burning cotton wicks.
                        </p>

                        <div class="flex flex-wrap items-center gap-3 pt-2">
                            <a href="{{ route('products.index', ['category' => 'hawan-samagri']) }}"
                               class="inline-flex items-center gap-2 px-6 py-3.5 bg-amber-500 hover:bg-amber-600 text-stone-950 rounded-xl font-bold text-sm shadow-xl transition transform hover:-translate-y-0.5">
                                <span>Shop Hawan Specials</span>
                                <span>→</span>
                            </a>
                            <a href="{{ route('products.index', ['category' => 'puja-essentials']) }}"
                               class="inline-flex items-center gap-2 px-6 py-3.5 bg-white/10 hover:bg-white/20 text-white border border-white/20 rounded-xl font-bold text-sm backdrop-blur-xs transition">
                                <span>Diya Wicks & Ghee</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slide 3: Spiritual Malas & Brass Ware -->
            <div x-show="activeSlide === 2"
                 x-transition:enter="transition ease-out duration-700"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-500"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="absolute inset-0 w-full h-full">
                <!-- Background Image & Gradient Overlay -->
                <img src="https://images.unsplash.com/photo-1545235617-9465d2a55698?w=1600&auto=format&fit=crop&q=80"
                     alt="Sacred Rudraksha Beads"
                     class="w-full h-full object-cover object-center opacity-40">
                <div class="absolute inset-0 bg-gradient-to-r from-stone-950 via-stone-900/80 to-transparent"></div>

                <!-- Slide Content -->
                <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full flex items-center">
                    <div class="max-w-2xl space-y-4 sm:space-y-6 py-12">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold bg-[#0369A1]/30 text-sky-300 border border-[#0369A1]/50 backdrop-blur-xs">
                            <span class="w-1.5 h-1.5 rounded-full bg-sky-400"></span>
                            <span>Energized in Haridwar Altar Prayers</span>
                        </div>

                        <h2 class="text-3xl sm:text-5xl lg:text-6xl font-serif font-extrabold tracking-tight leading-tight text-white">
                            Authentic <span class="text-amber-400">Rudraksha</span> & Pure Brass Aarti Lamps
                        </h2>

                        <p class="text-sm sm:text-base text-slate-300 leading-relaxed max-w-xl">
                            5-Mukhi Indonesian Rudraksha Japa Malas, pure Karungali wood bracelets, and heavy hand-cast brass panchamukhi diyas with peacock handles.
                        </p>

                        <div class="flex flex-wrap items-center gap-3 pt-2">
                            <a href="{{ route('products.index', ['category' => 'spiritual-items']) }}"
                               class="inline-flex items-center gap-2 px-6 py-3.5 bg-[#D97706] hover:bg-[#F97316] text-white rounded-xl font-bold text-sm shadow-xl transition transform hover:-translate-y-0.5">
                                <span>Explore Spiritual Store</span>
                                <span>→</span>
                            </a>
                            <a href="{{ route('products.index', ['category' => 'brass-pooja-ware']) }}"
                               class="inline-flex items-center gap-2 px-6 py-3.5 bg-white/10 hover:bg-white/20 text-white border border-white/20 rounded-xl font-bold text-sm backdrop-blur-xs transition">
                                <span>Brass Pooja Ware</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Carousel Navigation Controls -->
        <button type="button"
                @click="prevSlide()"
                class="absolute left-4 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white/20 hover:bg-white/40 text-white flex items-center justify-center backdrop-blur-xs transition z-20"
                aria-label="Previous Slide">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </button>

        <button type="button"
                @click="nextSlide()"
                class="absolute right-4 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white/20 hover:bg-white/40 text-white flex items-center justify-center backdrop-blur-xs transition z-20"
                aria-label="Next Slide">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </button>

        <!-- Carousel Indicators / Dots -->
        <div class="absolute bottom-5 left-1/2 -translate-x-1/2 flex items-center gap-2.5 z-20">
            <template x-for="(dot, idx) in 3" :key="idx">
                <button type="button"
                        @click="goToSlide(idx)"
                        class="h-2 rounded-full transition-all duration-300"
                        :class="activeSlide === idx ? 'w-8 bg-[#D97706]' : 'w-2 bg-white/40 hover:bg-white/70'"></button>
            </template>
        </div>
    </section>

    <!-- 2. Circular Category Grid / Stories Row (Shop.99Pandit "Our Collections" Style) -->
    <section class="py-8 sm:py-12 bg-white border-b border-amber-200/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-6 sm:mb-8">
                <h2 class="text-xl sm:text-2xl font-serif font-bold text-slate-900 tracking-tight">
                    Sacred Collections
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">
                    Select a category to discover consecrated offerings & daily pooja essentials
                </p>
            </div>

            <!-- Horizontal Circular Category Cards Grid -->
            <div class="grid grid-cols-4 sm:grid-cols-4 lg:grid-cols-7 gap-3 sm:gap-6">
                @foreach ($circularCategories as $cat)
                    <a href="{{ route('products.index', ['category' => $cat->slug]) }}"
                       class="group flex flex-col items-center text-center">
                        <!-- Circular Image Container with Gold Ring -->
                        <div class="w-16 h-16 sm:w-24 sm:h-24 rounded-full p-1 border-2 border-amber-300 group-hover:border-[#D97706] group-hover:shadow-lg transition-all duration-300 bg-amber-50/50 overflow-hidden relative transform group-hover:scale-105">
                            <img src="{{ $cat->image ?: 'https://images.unsplash.com/photo-1606787366850-de6330128bfc?w=200&fit=crop' }}"
                                 alt="{{ $cat->name }}"
                                 loading="lazy"
                                 onerror="this.onerror=null; this.src='data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 100 100\' fill=\'%23fef3c7\'><rect width=\'100\' height=\'100\' fill=\'%23fef3c7\'/><circle cx=\'50\' cy=\'50\' r=\'20\' fill=\'%23d97706\' opacity=\'0.2\'/><path d=\'M50 25 C45 38 38 48 38 58 C38 67 43 74 50 74 C57 74 62 67 62 58 C62 48 55 38 50 25 Z\' fill=\'%23d97706\'/></svg>';"
                                 class="w-full h-full object-cover rounded-full group-hover:brightness-105 transition duration-300">
                        </div>

                        <!-- Category Title -->
                        <span class="mt-2 text-[11px] sm:text-xs font-bold text-slate-800 group-hover:text-[#D97706] transition text-center leading-tight">
                            {{ $cat->name }}
                        </span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- 3. BEST SELLERS SECTION (High-Density Product Carousel/Grid) -->
    <section class="py-12 sm:py-16 bg-[#FFFDF7] border-b border-amber-200/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-8 gap-4">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-serif font-extrabold text-slate-900">
                        Best Selling Sacred Samagri
                    </h2>
                </div>
                <a href="{{ route('products.index', ['sort' => 'rating_high']) }}"
                   class="inline-flex items-center gap-1 text-xs sm:text-sm font-bold text-[#D97706] hover:text-[#F97316] transition">
                    <span>View all Best Sellers</span>
                    <span>→</span>
                </a>
            </div>

            <!-- Products Grid (4-to-5 columns on desktop, 2 on mobile) -->
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
                @foreach ($bestSellers as $prod)
                    <x-product-card :product="$prod" />
                @endforeach
            </div>
        </div>
    </section>

    <!-- 4. Sacred Gangajal & QR Traceability Seal (Core Brand USP) -->
    <section class="py-14 sm:py-20 bg-slate-900 text-white relative overflow-hidden">
        <div class="absolute -right-24 -bottom-24 w-96 h-96 rounded-full bg-[#D97706]/10 blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
                <!-- Left: Consecrated Sourcing Story -->
                <div class="lg:col-span-7 space-y-6">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold bg-[#D97706]/20 text-[#D97706] border border-[#D97706]/40">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                        <span>Har Ki Pauri · Brahma Muhurta Sanctity</span>
                    </div>

                    <h2 class="text-3xl sm:text-4xl lg:text-5xl font-serif font-extrabold text-white leading-tight">
                        Untouched Brahmakund Gangajal With Complete QR Batch Traceability
                    </h2>

                    <p class="text-sm sm:text-base text-slate-300 leading-relaxed">
                        Unlike ordinary tap-filled commercial bottles, Haridwar Bliss holy water is directly collected at the divine Brahma Muhurta (04:45 AM) during morning Ganga Aarti from the sanctified Brahmakund at Har Ki Pauri.
                    </p>

                    <!-- Sacred Quality Badges -->
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 pt-2">
                        <div class="bg-slate-800/80 p-3.5 rounded-xl border border-slate-700/80">
                            <svg class="w-6 h-6 mb-2 text-[#D97706]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                            </svg>
                            <span class="block font-bold text-xs text-white">Lab Certified Purity</span>
                            <span class="block text-[11px] text-slate-400">Tested pH 7.8 & minerals</span>
                        </div>
                        <div class="bg-slate-800/80 p-3.5 rounded-xl border border-slate-700/80">
                            <svg class="w-6 h-6 mb-2 text-[#D97706]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                            <span class="block font-bold text-xs text-white">Touchless UV Seal</span>
                            <span class="block text-[11px] text-slate-400">100% hermetic capping</span>
                        </div>
                        <div class="bg-slate-800/80 p-3.5 rounded-xl border border-slate-700/80 col-span-2 sm:col-span-1">
                            <svg class="w-6 h-6 mb-2 text-[#D97706]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                            </svg>
                            <span class="block font-bold text-xs text-white">Unique QR Code</span>
                            <span class="block text-[11px] text-slate-400">Verifiable collection data</span>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-3 pt-2">
                        <a href="{{ route('verify.batch', 'HB-VERIFY-2026-SAMPLE') }}"
                           class="inline-flex items-center gap-2 px-6 py-3.5 rounded-xl text-sm font-bold text-white bg-[#D97706] hover:bg-[#F97316] shadow-xl transition">
                            <span>Test Live Batch QR Portal</span>
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                        </a>
                        <a href="{{ route('products.index', ['category' => 'sacred-gangajal']) }}"
                           class="inline-flex items-center gap-2 px-6 py-3.5 rounded-xl text-sm font-bold text-white bg-slate-800 hover:bg-slate-700 border border-slate-700 transition">
                            <span>View Gangajal Editions</span>
                        </a>
                    </div>
                </div>

                <!-- Right: Interactive Batch Seal Card -->
                <div class="lg:col-span-5 bg-slate-800/90 border border-slate-700 p-6 sm:p-8 rounded-3xl shadow-2xl relative">
                    <div class="flex items-center justify-between pb-5 border-b border-slate-700">
                        <div>
                            <span class="text-xs font-mono uppercase text-[#D97706] font-bold">Haridwar Bliss Traceability Seal</span>
                            <h3 class="text-base sm:text-lg font-serif font-bold text-white mt-0.5">
                                Brahmakund Batch #HB-GANG-2026-001
                            </h3>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-[#D97706]/20 border border-[#D97706]/40 flex items-center justify-center text-[#D97706]">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                    </div>

                    <div class="py-5 space-y-3.5 text-xs">
                        <div class="flex justify-between">
                            <span class="text-slate-400">Sourcing Ghat:</span>
                            <span class="font-bold text-white text-right">Har Ki Pauri, Brahmakund, Haridwar</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400">Collection Auspicious Time:</span>
                            <span class="font-bold text-white text-right">Brahma Muhurta (04:45 AM)</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400">Bottling Technique:</span>
                            <span class="font-bold text-sky-400">Touchless Ultra-Violet Hermetic Capping</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400">Lab Analysis Status:</span>
                            <span class="font-bold text-emerald-400">Passed All Potability Standards</span>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-700 text-center">
                        <p class="text-[11px] text-slate-400 mb-3">
                            Unique QR code printed on every bottle package & invoice
                        </p>
                        <div class="inline-block p-2 bg-white rounded-xl shadow-inner">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=110x110&data={{ urlencode(url('/verify/HB-VERIFY-2026-SAMPLE')) }}"
                                 alt="Sample QR" class="w-24 h-24 mx-auto" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 5. CATEGORY SPOTLIGHT: Puja & Hawan Essentials -->
    <section class="py-12 sm:py-16 bg-white border-b border-amber-200/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-8 gap-4">
                <div>
                    <span class="text-xs font-extrabold text-[#D97706] uppercase tracking-widest font-mono">
                        Daily Altar Worship
                    </span>
                    <h2 class="text-2xl sm:text-3xl font-serif font-extrabold text-slate-900 mt-1">
                        Puja & Hawan Essentials
                    </h2>
                </div>
                <a href="{{ route('products.index', ['category' => 'puja-essentials']) }}"
                   class="inline-flex items-center gap-1 text-xs sm:text-sm font-bold text-[#D97706] hover:text-[#F97316] transition">
                    <span>Explore Puja Range</span>
                    <span>→</span>
                </a>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
                @foreach ($pujaEssentials as $prod)
                    <x-product-card :product="$prod" />
                @endforeach
            </div>
        </div>
    </section>

    <!-- 6. 5-Year Membership Banner (High-Conversion Devotee Call to Action) -->
    <section class="py-14 sm:py-18 bg-[#881337] text-white relative overflow-hidden">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-gradient-to-r from-[#881337] via-[#991B1B] to-[#7F1D1D] border border-amber-300/40 rounded-3xl p-8 sm:p-12 shadow-2xl relative">
                <div class="max-w-3xl space-y-4">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-400/20 text-amber-200 border border-amber-300/40">
                        <span>Haridwar Bliss 5-Year Devotee Circle</span>
                    </span>

                    <h2 class="text-2xl sm:text-4xl font-serif font-extrabold text-white leading-tight">
                        Receive Fresh Consecrated Gangajal at Your Doorstep Every Month
                    </h2>

                    <p class="text-sm sm:text-base text-white/90 leading-relaxed">
                        For a one-time enrollment of ₹{{ number_format($membershipFee) }} valid for 5 full years, receive 1 free 500ml bottle of authentic Brahmakund Gangajal every month (pay only shipping), plus special devotee discounts across our entire catalog.
                    </p>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-2 text-xs">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-amber-300 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>1st Bottle Free Every Month</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-amber-300 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Member-Only Discounted Rates</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-amber-300 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Priority Delivery Across India</span>
                        </div>
                    </div>

                    <div class="pt-4 flex flex-wrap items-center gap-3">
                        <a href="{{ route('membership.join') }}"
                           class="inline-flex items-center gap-2 px-8 py-4 bg-white text-slate-900 hover:bg-amber-50 rounded-2xl font-bold text-sm shadow-xl transition transform hover:-translate-y-0.5">
                            <span>Join 5-Year Membership (₹{{ number_format($membershipFee) }})</span>
                            <span>→</span>
                        </a>
                        <a href="{{ route('about') }}"
                           class="inline-flex items-center gap-2 px-6 py-4 bg-white/10 hover:bg-white/20 text-white border border-white/20 rounded-2xl font-bold text-sm transition">
                            <span>Read Brahmakund Story</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 7. CATEGORY SPOTLIGHT: Spiritual & Aromatics (Rudraksha, Malas, Dhoop) -->
    <section class="py-12 sm:py-16 bg-[#FFFDF7] border-b border-amber-200/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-8 gap-4">
                <div>
                    <span class="text-xs font-extrabold text-[#D97706] uppercase tracking-widest font-mono">
                        Divine Energy & Fragrance
                    </span>
                    <h2 class="text-2xl sm:text-3xl font-serif font-extrabold text-slate-900 mt-1">
                        Spiritual Store & Temple Aromas
                    </h2>
                </div>
                <a href="{{ route('products.index', ['category' => 'spiritual-items']) }}"
                   class="inline-flex items-center gap-1 text-xs sm:text-sm font-bold text-[#D97706] hover:text-[#F97316] transition">
                    <span>View All Spiritual Items</span>
                    <span>→</span>
                </a>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
                @foreach ($spiritualItems as $prod)
                    <x-product-card :product="$prod" />
                @endforeach
            </div>
        </div>
    </section>

    <!-- 8. Devotee Blessings & Testimonials -->
    <section class="py-14 sm:py-18 bg-white border-b border-amber-200/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-12">
                <span class="text-xs font-bold uppercase tracking-widest text-[#D97706] font-mono">
                    Devotee Blessings
                </span>
                <h2 class="mt-2 text-2xl sm:text-3xl font-serif font-extrabold text-slate-900">
                    What Devotees Say Across India
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-8 text-sm">
                <div class="bg-[#FFFDF7] p-6 sm:p-8 rounded-3xl border border-[#D97706]/20 space-y-4 shadow-xs">
                    <div class="text-amber-500 flex gap-0.5">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    </div>
                    <p class="text-slate-700 leading-relaxed italic text-xs sm:text-sm">
                        "Living in Chennai, getting fresh holy water from Har Ki Pauri every month seemed impossible until we joined Haridwar Bliss. The QR code showing the exact morning collection video brought tears of devotion to our family."
                    </p>
                    <div class="pt-2 border-t border-amber-200/50">
                        <p class="font-bold text-slate-900">Dr. K. S. Narayanan</p>
                        <p class="text-xs text-slate-500">Chennai · 5-Year Member</p>
                    </div>
                </div>

                <div class="bg-[#FFFDF7] p-6 sm:p-8 rounded-3xl border border-[#D97706]/20 space-y-4 shadow-xs">
                    <div class="text-amber-500 flex gap-0.5">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    </div>
                    <p class="text-slate-700 leading-relaxed italic text-xs sm:text-sm">
                        "The packaging is immaculate. Clean, touch-free hermetic seals with zero leakage. The cow dung hawan cups smell heavenly and create the exact ambience of a temple morning aarti."
                    </p>
                    <div class="pt-2 border-t border-amber-200/50">
                        <p class="font-bold text-slate-900">Smt. Meenakshi Agrawal</p>
                        <p class="text-xs text-slate-500">Ahmedabad · 5-Year Member</p>
                    </div>
                </div>

                <div class="bg-[#FFFDF7] p-6 sm:p-8 rounded-3xl border border-[#D97706]/20 space-y-4 shadow-xs">
                    <div class="text-amber-500 flex gap-0.5">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    </div>
                    <p class="text-slate-700 leading-relaxed italic text-xs sm:text-sm">
                        "The brass panchamukhi aarti diya and pure sandalwood dhoop sticks transform our pooja altar into Har Ki Pauri every evening. Truly authentic Vedic service and fast dispatch."
                    </p>
                    <div class="pt-2 border-t border-amber-200/50">
                        <p class="font-bold text-slate-900">Shri Rajesh Vardhan</p>
                        <p class="text-xs text-slate-500">Bengaluru · 5-Year Member</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 9. Trust & Quality Assurance Pillars (99Pandit Style) -->
    <section class="py-10 bg-amber-50/60 border-b border-amber-200/60">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
                <div class="space-y-2 p-3">
                    <div class="w-12 h-12 mx-auto rounded-2xl bg-amber-100/80 border border-amber-300/60 flex items-center justify-center text-[#D97706]">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                        </svg>
                    </div>
                    <h4 class="font-bold text-slate-900 text-xs sm:text-sm">100% Brahmakund</h4>
                    <p class="text-[11px] text-slate-500">Sourced directly from Har Ki Pauri</p>
                </div>
                <div class="space-y-2 p-3">
                    <div class="w-12 h-12 mx-auto rounded-2xl bg-amber-100/80 border border-amber-300/60 flex items-center justify-center text-[#D97706]">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <h4 class="font-bold text-slate-900 text-xs sm:text-sm">Lab Tested Purity</h4>
                    <p class="text-[11px] text-slate-500">Certified chemical & microbe-free</p>
                </div>
                <div class="space-y-2 p-3">
                    <div class="w-12 h-12 mx-auto rounded-2xl bg-amber-100/80 border border-amber-300/60 flex items-center justify-center text-[#D97706]">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                    <h4 class="font-bold text-slate-900 text-xs sm:text-sm">Hermetic UV Capping</h4>
                    <p class="text-[11px] text-slate-500">Untouched sanitary bottling</p>
                </div>
                <div class="space-y-2 p-3">
                    <div class="w-12 h-12 mx-auto rounded-2xl bg-amber-100/80 border border-amber-300/60 flex items-center justify-center text-[#D97706]">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <h4 class="font-bold text-slate-900 text-xs sm:text-sm">Pan-India Express</h4>
                    <p class="text-[11px] text-slate-500">Insured express delivery</p>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>
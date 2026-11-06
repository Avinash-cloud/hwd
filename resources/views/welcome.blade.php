<x-app-layout>
    <!-- 1. Hero Promotional Slideshow (Shop.99Pandit Style - Dynamic from Admin Panel) -->
    <section class="relative overflow-hidden bg-stone-900 text-white"
             x-data="{
                 activeSlide: 0,
                 totalSlides: {{ max(1, $sliders->count()) }},
                 timer: null,
                 init() {
                     if (this.totalSlides > 1) {
                         this.timer = setInterval(() => { this.nextSlide(); }, 6000);
                     }
                 },
                 nextSlide() {
                     this.activeSlide = (this.activeSlide + 1) % this.totalSlides;
                 },
                 prevSlide() {
                     this.activeSlide = (this.activeSlide - 1 + this.totalSlides) % this.totalSlides;
                 },
                 goToSlide(idx) {
                     this.activeSlide = idx;
                 }
             }">
        <!-- Slides Wrapper (Responsive for Desktop 1920x700, Tablet 1024x600, Mobile 750x1000) -->
        <div class="relative w-full min-h-[440px] sm:min-h-[520px] lg:min-h-[620px] flex items-center">
            @foreach ($sliders as $index => $slide)
                @php
                    $badgeBg = match($slide->badge_color) {
                        'rose' => 'bg-[#881337]/50 text-rose-300 border-[#881337]',
                        'sky' => 'bg-[#0369A1]/30 text-sky-300 border-[#0369A1]/50',
                        'emerald' => 'bg-emerald-950/60 text-emerald-300 border-emerald-800',
                        default => 'bg-[#D97706]/20 text-amber-300 border-[#D97706]/40',
                    };
                    $dotBg = match($slide->badge_color) {
                        'rose' => 'bg-rose-400',
                        'sky' => 'bg-sky-400',
                        'emerald' => 'bg-emerald-400',
                        default => 'bg-amber-400',
                    };
                @endphp
                <div x-show="activeSlide === {{ $index }}"
                     x-transition:enter="transition ease-out duration-700"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-500"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="absolute inset-0 w-full h-full">

                    @if ($slide->is_video && $slide->resolved_video_url)
                        <!-- Video Slide Background with Muted Autoplay and Unmute Toggle -->
                        <div class="absolute inset-0 w-full h-full overflow-hidden" x-data="{ isMuted: true }">
                            <video :muted="isMuted"
                                   playsinline
                                   autoplay
                                   loop
                                   poster="{{ $slide->image_url }}"
                                   class="w-full h-full object-cover object-center opacity-50">
                                <source src="{{ $slide->resolved_video_url }}" type="video/mp4">
                            </video>

                            <!-- Devotee Unmute / Mute Toggle Button -->
                            <button type="button"
                                    @click="isMuted = !isMuted"
                                    class="absolute top-4 right-4 sm:top-6 sm:right-6 z-30 inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-slate-900/80 hover:bg-slate-900 text-amber-300 border border-white/20 text-xs font-bold backdrop-blur-xs shadow-lg transition touch-manipulation cursor-pointer">
                                <span x-text="isMuted ? '🔇 Unmute Video' : '🔊 Mute Video'"></span>
                            </button>
                        </div>
                    @else
                        <!-- Responsive Media with Art Direction (Desktop, Tablet, Mobile) -->
                        <picture class="w-full h-full absolute inset-0">
                            @if ($slide->mobile_image)
                                <source media="(max-width: 640px)" srcset="{{ $slide->mobile_image_url }}">
                            @endif
                            @if ($slide->tablet_image)
                                <source media="(max-width: 1024px)" srcset="{{ $slide->tablet_image_url }}">
                            @endif
                            <img src="{{ $slide->image_url }}"
                                 alt="{{ $slide->title }}"
                                 class="w-full h-full object-cover object-center opacity-45">
                        </picture>
                    @endif

                    <div class="absolute inset-0 bg-gradient-to-r from-stone-950 via-stone-900/80 to-transparent"></div>

                    <!-- Slide Content -->
                    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full flex items-center">
                        <div class="max-w-2xl space-y-4 sm:space-y-6 py-12">
                            @if ($slide->subtitle)
                                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold border backdrop-blur-xs {{ $badgeBg }}">
                                    <span class="w-2 h-2 rounded-full animate-pulse {{ $dotBg }}"></span>
                                    <span>{{ $slide->subtitle }}</span>
                                </div>
                            @endif

                            @if ($slide->title)
                                <h1 class="text-3xl sm:text-5xl lg:text-6xl font-serif font-extrabold tracking-tight leading-tight text-white">
                                    {{ $slide->title }}
                                </h1>
                            @endif

                            @if ($slide->description)
                                <p class="text-sm sm:text-base text-slate-300 leading-relaxed max-w-xl">
                                    {{ $slide->description }}
                                </p>
                            @endif

                            <div class="flex flex-wrap items-center gap-3 pt-2">
                                @if ($slide->button_text && $slide->button_link)
                                    <a href="{{ $slide->button_link }}"
                                       class="inline-flex items-center gap-2 px-6 py-3.5 bg-[#D97706] hover:bg-[#F97316] text-white rounded-xl font-bold text-sm shadow-xl transition transform hover:-translate-y-0.5">
                                        <span>{{ $slide->button_text }}</span>
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                        </svg>
                                    </a>
                                @endif

                                @if ($slide->secondary_button_text && $slide->secondary_button_link)
                                    <a href="{{ $slide->secondary_button_link }}"
                                       class="inline-flex items-center gap-2 px-6 py-3.5 bg-white/10 hover:bg-white/20 text-white border border-white/20 rounded-xl font-bold text-sm backdrop-blur-xs transition">
                                        <span>{{ $slide->secondary_button_text }}</span>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if ($sliders->count() > 1)
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
                @foreach ($sliders as $index => $slide)
                    <button type="button"
                            @click="goToSlide({{ $index }})"
                            class="h-2 rounded-full transition-all duration-300"
                            :class="activeSlide === {{ $index }} ? 'w-8 bg-[#D97706]' : 'w-2 bg-white/40 hover:bg-white/70'"></button>
                @endforeach
            </div>
        @endif
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
                            <img src="{{ $cat->image_url }}"
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
        <!-- Ambient authentic holy water backdrop -->
        <img src="{{ asset('images/ganga-holy-waters.jpg') }}"
             alt="Sacred Holy Maa Ganga Water"
             class="absolute inset-0 w-full h-full object-cover opacity-20 pointer-events-none mix-blend-luminosity">
        <div class="absolute inset-0 bg-gradient-to-r from-slate-950 via-slate-900/90 to-slate-950/85 pointer-events-none"></div>
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

                    @php
                        $activeBatchCode = $latestBatch ? $latestBatch->batch_number : 'HB-VERIFY-2026-SAMPLE';
                        $activeBatchGhat = $latestBatch ? $latestBatch->sourcing_ghat : 'Har Ki Pauri, Brahmakund, Haridwar';
                        $activeBatchDate = $latestBatch && $latestBatch->collection_date ? $latestBatch->collection_date->format('d M Y') : 'Brahma Muhurta (04:45 AM)';
                    @endphp

                    <div class="flex flex-wrap items-center gap-3 pt-2">
                        <a href="{{ route('verify.batch', $activeBatchCode) }}"
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
                <div class="lg:col-span-5 bg-slate-850/95 border border-slate-700/80 p-6 sm:p-8 rounded-3xl shadow-2xl relative backdrop-blur-md">
                    <!-- Photo preview of Brahmakund collection site -->
                    <div class="relative h-44 rounded-2xl overflow-hidden mb-5 border border-slate-700 shadow-md group">
                        <img src="{{ asset('images/har-ki-pauri-temples.jpg') }}"
                             alt="Brahmakund Har Ki Pauri Haridwar"
                             class="w-full h-full object-cover transition transform duration-500 group-hover:scale-105">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/90 via-slate-950/30 to-transparent"></div>
                        <div class="absolute bottom-3 left-3 right-3 flex items-center justify-between">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[11px] font-bold bg-[#881337]/90 text-white backdrop-blur-xs">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                Brahmakund Consecration Sanctum
                            </span>
                            <span class="text-[10px] text-amber-300 font-mono font-bold">Har Ki Pauri</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between pb-5 border-b border-slate-700">
                        <div>
                            <span class="text-xs font-mono uppercase text-[#D97706] font-bold">Haridwar Bliss Traceability Seal</span>
                            <h3 class="text-base sm:text-lg font-serif font-bold text-white mt-0.5">
                                Brahmakund Batch #{{ $activeBatchCode }}
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
                            <span class="font-bold text-white text-right">{{ $activeBatchGhat }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400">Collection Date / Time:</span>
                            <span class="font-bold text-white text-right">{{ $activeBatchDate }}</span>
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
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=110x110&data={{ urlencode(route('verify.batch', $activeBatchCode)) }}"
                                 alt="Sample QR" class="w-24 h-24 mx-auto" />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dynamic Sacred Gangajal Editions Showcase -->
            @if ($featuredGangajal->isNotEmpty())
                <div class="mt-14 pt-10 border-t border-slate-800">
                    <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-6 gap-2">
                        <div>
                            <span class="text-xs font-mono uppercase text-[#D97706] font-bold">Consecrated Offerings</span>
                            <h3 class="text-xl sm:text-2xl font-serif font-bold text-white mt-1">
                                Sacred Brahmakund Gangajal Editions
                            </h3>
                        </div>
                        <a href="{{ route('products.index', ['type' => 'gangajal']) }}" class="text-xs font-bold text-amber-400 hover:text-amber-300 transition">
                            View All Sizes & Casks →
                        </a>
                    </div>
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
                        @foreach ($featuredGangajal as $prod)
                            <x-product-card :product="$prod" />
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>

    <!-- Sacred Haridwar Darshan & Provenance Showcase (Authentic Sourcing Ghats) -->
    <section class="py-14 sm:py-20 bg-[#FFFDF7] border-b border-amber-200/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-10 sm:mb-14">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold bg-[#881337]/10 text-[#881337] border border-[#881337]/20 mb-3">
                    <span class="w-2 h-2 rounded-full bg-[#881337]"></span>
                    <span>Direct From The Holy Source</span>
                </div>
                <h2 class="text-2xl sm:text-4xl font-serif font-extrabold text-slate-900 tracking-tight">
                    Sacred Haridwar Darshan & Brahmakund Sanctum
                </h2>
                <p class="text-xs sm:text-sm text-slate-600 mt-2 leading-relaxed">
                    Witness the divine riverside ghats and ancient temples of Haridwar where every drop of Blu Bliss Gangajal is collected during Brahma Muhurta.
                </p>
            </div>

            <!-- 4-Card Photogrid with Sacred Captions -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Card 1: Clock Tower Panorama -->
                <div class="group bg-white rounded-3xl border border-amber-200/80 overflow-hidden shadow-xs hover:shadow-xl transition-all duration-300 flex flex-col">
                    <div class="relative h-56 overflow-hidden">
                        <img src="{{ asset('images/haridwar-panorama-clocktower.jpg') }}"
                             alt="Har Ki Pauri & Historic Clock Tower"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent"></div>
                        <span class="absolute bottom-3 left-3 text-xs font-bold text-white bg-slate-900/70 backdrop-blur-xs px-2.5 py-1 rounded-lg">
                            Har Ki Pauri Ghat
                        </span>
                    </div>
                    <div class="p-5 flex-1 flex flex-col justify-between space-y-2">
                        <div>
                            <h3 class="font-serif font-bold text-base text-slate-900 group-hover:text-[#881337] transition">
                                Historic Ghanta Ghar & Ghats
                            </h3>
                            <p class="text-xs text-slate-600 mt-1.5 leading-relaxed">
                                The iconic Har Ki Pauri where millions gather for morning & evening Maha Aarti amid glowing oil lamps and sacred bells.
                            </p>
                        </div>
                        <div class="pt-3 border-t border-amber-100 flex items-center justify-between text-[11px] text-amber-700 font-semibold">
                            <span>Auspicious Pilgrimage</span>
                            <span>Uttarakhand</span>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Brahmakund Sanctum -->
                <div class="group bg-white rounded-3xl border border-amber-200/80 overflow-hidden shadow-xs hover:shadow-xl transition-all duration-300 flex flex-col">
                    <div class="relative h-56 overflow-hidden">
                        <img src="{{ asset('images/har-ki-pauri-temples.jpg') }}"
                             alt="Brahmakund Sanctum Sanctorum"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent"></div>
                        <span class="absolute bottom-3 left-3 text-xs font-bold text-white bg-[#881337]/90 backdrop-blur-xs px-2.5 py-1 rounded-lg">
                            Brahmakund Sanctum
                        </span>
                    </div>
                    <div class="p-5 flex-1 flex flex-col justify-between space-y-2">
                        <div>
                            <h3 class="font-serif font-bold text-base text-slate-900 group-hover:text-[#881337] transition">
                                The Consecrated Nectar Pool
                            </h3>
                            <p class="text-xs text-slate-600 mt-1.5 leading-relaxed">
                                The holiest epicenter where celestial Amrit drops fell during Samudra Manthan. Collected fresh at 04:45 AM.
                            </p>
                        </div>
                        <div class="pt-3 border-t border-amber-100 flex items-center justify-between text-[11px] text-[#881337] font-semibold">
                            <span>Brahma Muhurta Sourced</span>
                            <span>100% Untouched</span>
                        </div>
                    </div>
                </div>

                <!-- Card 3: Vedic Riverfront Shrines -->
                <div class="group bg-white rounded-3xl border border-amber-200/80 overflow-hidden shadow-xs hover:shadow-xl transition-all duration-300 flex flex-col">
                    <div class="relative h-56 overflow-hidden">
                        <img src="{{ asset('images/brahmakund-ghat-shrines.jpg') }}"
                             alt="Vedic Riverside Shrines & Temples"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent"></div>
                        <span class="absolute bottom-3 left-3 text-xs font-bold text-white bg-slate-900/70 backdrop-blur-xs px-2.5 py-1 rounded-lg">
                            Ghat Shrines
                        </span>
                    </div>
                    <div class="p-5 flex-1 flex flex-col justify-between space-y-2">
                        <div>
                            <h3 class="font-serif font-bold text-base text-slate-900 group-hover:text-[#881337] transition">
                                Ancient Riverside Temples
                            </h3>
                            <p class="text-xs text-slate-600 mt-1.5 leading-relaxed">
                                Centuries-old temples energizing the river with non-stop Vedic chanting, hawans, and Ganga Lahari stotras.
                            </p>
                        </div>
                        <div class="pt-3 border-t border-amber-100 flex items-center justify-between text-[11px] text-amber-700 font-semibold">
                            <span>Vedic Chanting Site</span>
                            <span>Sanctified Energy</span>
                        </div>
                    </div>
                </div>

                <!-- Card 4: Living Waters of Maa Ganga -->
                <div class="group bg-white rounded-3xl border border-amber-200/80 overflow-hidden shadow-xs hover:shadow-xl transition-all duration-300 flex flex-col">
                    <div class="relative h-56 overflow-hidden">
                        <img src="{{ asset('images/ganga-holy-waters.jpg') }}"
                             alt="Living Waters of Maa Ganga"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent"></div>
                        <span class="absolute bottom-3 left-3 text-xs font-bold text-white bg-sky-900/80 backdrop-blur-xs px-2.5 py-1 rounded-lg">
                            Living Holy Water
                        </span>
                    </div>
                    <div class="p-5 flex-1 flex flex-col justify-between space-y-2">
                        <div>
                            <h3 class="font-serif font-bold text-base text-slate-900 group-hover:text-[#881337] transition">
                                Pristine Himalayan Flow
                            </h3>
                            <p class="text-xs text-slate-600 mt-1.5 leading-relaxed">
                                Pure Himalayan glacier water flowing directly into Haridwar, naturally rich in mineral ions and bactericidal purity.
                            </p>
                        </div>
                        <div class="pt-3 border-t border-amber-100 flex items-center justify-between text-[11px] text-sky-700 font-semibold">
                            <span>pH 7.8 Alkaline</span>
                            <span>Never Spoils</span>
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
            <div class="relative overflow-hidden bg-gradient-to-r from-[#881337] via-[#991B1B] to-[#7F1D1D] border border-amber-300/40 rounded-3xl p-8 sm:p-12 shadow-2xl">
                <!-- Background authentic Haridwar ghat photo -->
                <img src="{{ asset('images/har-ki-pauri-temples.jpg') }}"
                     alt="Har Ki Pauri Haridwar"
                     class="absolute inset-0 w-full h-full object-cover opacity-20 mix-blend-overlay pointer-events-none">
                <div class="relative z-10 max-w-3xl space-y-4">
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
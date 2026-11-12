<x-app-layout>
    <!-- 1. Hero Promotional Slideshow (Shop.99Pandit Style - Dynamic from Admin Panel) -->
    <section class="relative overflow-hidden bg-[#25050A] text-white"
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
                    $hasText = ! empty(trim($slide->title ?? ''))
                        || ! empty(trim($slide->subtitle ?? ''))
                        || ! empty(trim($slide->description ?? ''))
                        || ! empty(trim($slide->button_text ?? ''))
                        || ! empty(trim($slide->secondary_button_text ?? ''));
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
                                   class="w-full h-full object-cover object-center {{ $hasText ? 'opacity-90' : 'opacity-100' }}">
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
                                 alt="{{ $slide->title ?: 'Sacred Hero Banner' }}"
                                 class="w-full h-full object-cover object-center {{ $hasText ? 'opacity-95' : 'opacity-100' }}">
                        </picture>
                    @endif

                    @if ($hasText)
                        <!-- Slightest black shadow gradient to ensure text readability without darkening the whole banner -->
                        <div class="absolute inset-0 bg-gradient-to-r from-black/55 via-black/25 to-transparent pointer-events-none"></div>

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
                                    <h1 class="text-3xl sm:text-5xl lg:text-6xl font-serif font-extrabold tracking-tight leading-tight text-white drop-shadow-[0_2px_4px_rgba(0,0,0,0.6)]">
                                        {{ $slide->title }}
                                    </h1>
                                @endif

                                @if ($slide->description)
                                    <p class="text-sm sm:text-base text-amber-100/90 leading-relaxed max-w-xl drop-shadow-[0_1px_2px_rgba(0,0,0,0.6)]">
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
                    @else
                        {{-- If no text, and slide has a link, make the whole banner clickable --}}
                        @if ($slide->button_link)
                            <a href="{{ $slide->button_link }}" class="absolute inset-0 z-10 block" aria-label="Explore Banner"></a>
                        @endif
                    @endif
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

    <!-- 2. Sacred Consecrated Gangajal Sanctum & Bottle Editions (The Core Purpose) -->
    <section class="py-12 sm:py-16 bg-[#F97316] border-b border-orange-600/40 relative overflow-hidden">
        <!-- Subtle sacred background water ripple aura -->
        <div class="absolute -top-32 -right-32 w-96 h-96 rounded-full bg-sky-200/20 blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-32 -left-32 w-96 h-96 rounded-full bg-amber-200/20 blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <!-- Devotional Vedic Intro -->
            <div class="text-center max-w-4xl mx-auto mb-10 sm:mb-12 px-2">
                <div class="inline-flex items-center gap-1.5 sm:gap-2 px-3 sm:px-4 py-1 sm:py-1.5 rounded-full text-[10px] min-[360px]:text-[11px] sm:text-xs font-bold bg-[#881337] text-[#FDE68A] border border-amber-300/40 mb-3 sm:mb-4 shadow-md max-w-full">
                    <span class="w-1.5 h-1.5 sm:w-2 sm:h-2 rounded-full bg-[#F59E0B] animate-pulse shrink-0"></span>
                    <span class="font-serif tracking-normal sm:tracking-wider font-extrabold whitespace-nowrap">ॐ हर हर गंगे · नमामि गंगे तव पादपंकजम्</span>
                </div>
                <div class="w-full flex justify-center">
                    <h2 class="inline-block whitespace-nowrap text-[11px] min-[360px]:text-[13px] min-[400px]:text-[15px] sm:text-2xl md:text-3xl lg:text-4xl xl:text-5xl font-serif font-extrabold text-[#FDE68A] bg-[#881337] border border-amber-300/40 px-3 min-[360px]:px-4 sm:px-8 py-1.5 min-[360px]:py-2 sm:py-3.5 rounded-full sm:rounded-3xl shadow-xl tracking-tight leading-tight max-w-full">
                        Pure Haridwar Brahmakund Gangajal
                    </h2>
                </div>
                <p class="text-xs sm:text-sm text-white font-semibold mt-4 max-w-2xl mx-auto leading-relaxed drop-shadow-2xs">
                    Collected directly from the sanctified Brahmakund at Har Ki Pauri during auspicious Brahma Muhurta (04:45 AM) morning Ganga Aarti. 100% untouched by human hands with verifiable batch QR video traceability.
                </p>

                <!-- 4 Sacred Purity Pillars Micro-Row -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 sm:gap-4 mt-6 text-left">
                    <div class="flex items-center gap-2.5 p-3 rounded-2xl bg-white/95 border border-amber-300/80 shadow-xs">
                        <div class="w-8 h-8 rounded-xl bg-[#881337] text-amber-200 flex items-center justify-center shrink-0">
                            <span class="text-base">🌅</span>
                        </div>
                        <div class="min-w-0">
                            <span class="block text-[11px] font-extrabold text-[#881337] truncate">04:45 AM Aarti</span>
                            <span class="block text-[10px] text-amber-900 font-semibold truncate">Brahma Muhurta Sourced</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-2.5 p-3 rounded-2xl bg-white/95 border border-amber-300/80 shadow-xs">
                        <div class="w-8 h-8 rounded-xl bg-[#0369A1] text-sky-100 flex items-center justify-center shrink-0">
                            <span class="text-base">🔬</span>
                        </div>
                        <div class="min-w-0">
                            <span class="block text-[11px] font-extrabold text-[#0369A1] truncate">Lab Tested Purity</span>
                            <span class="block text-[10px] text-amber-900 font-semibold truncate">pH 7.8 · Natural Minerals</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-2.5 p-3 rounded-2xl bg-white/95 border border-amber-300/80 shadow-xs">
                        <div class="w-8 h-8 rounded-xl bg-[#881337] text-amber-200 flex items-center justify-center shrink-0">
                            <span class="text-base">🪷</span>
                        </div>
                        <div class="min-w-0">
                            <span class="block text-[11px] font-extrabold text-[#881337] truncate">Hermetic UV Capping</span>
                            <span class="block text-[10px] text-amber-900 font-semibold truncate">Touchless Sanitary Seal</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-2.5 p-3 rounded-2xl bg-white/95 border border-amber-300/80 shadow-xs">
                        <div class="w-8 h-8 rounded-xl bg-emerald-800 text-emerald-100 flex items-center justify-center shrink-0">
                            <span class="text-base">📱</span>
                        </div>
                        <div class="min-w-0">
                            <span class="block text-[11px] font-extrabold text-emerald-800 truncate">Scannable Batch QR</span>
                            <span class="block text-[10px] text-amber-900 font-semibold truncate">Har Ki Pauri Video Assay</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dynamic Consecrated Gangajal Bottles Grid -->
            @if ($featuredGangajal->isNotEmpty())
                <div class="mb-2">
                    <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-6 gap-2">
                        <div>
                            <span class="text-[11px] font-mono uppercase text-amber-200 font-extrabold tracking-wider bg-[#881337] px-2.5 py-0.5 rounded-full inline-block mb-1">Consecrated Editions</span>
                            <h3 class="text-lg sm:text-2xl font-serif font-bold text-white">
                                Select Sacred Gangajal Size for Your Puja Ghar
                            </h3>
                        </div>
                        <a href="{{ route('products.index', ['type' => 'gangajal']) }}"
                           class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-[#881337] hover:bg-[#9F1239] text-white rounded-full text-xs font-bold transition shadow-md border border-white/20">
                            <span>View All Gangajal Bottles & Casks</span>
                            <span>→</span>
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

    <!-- 3. 5-Year Devotee Circle Membership (High-Conversion Devotee Call to Action) -->
    <section class="py-12 sm:py-16 bg-[#FFA028] text-white relative overflow-hidden border-b border-orange-600/40">
        <!-- Background authentic Haridwar ghat photo -->
        <!-- <img src="{{ asset('images/har-ki-pauri-temples.jpg') }}"
             alt="Har Ki Pauri Haridwar"
             class="absolute inset-0 w-full h-full object-cover opacity-15 mix-blend-overlay pointer-events-none"> -->
        <div class="absolute -right-32 -bottom-32 w-96 h-96 rounded-full bg-amber-400/20 blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <!-- Header Tag -->
            <div class="text-center max-w-3xl mx-auto mb-8 sm:mb-10">
                <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-extrabold bg-[#881337] text-[#FDE68A] border border-amber-300/40 shadow-xs mb-3">
                    <span class="w-2 h-2 rounded-full bg-[#F59E0B] animate-pulse"></span>
                    <span>🌟 Haridwar Bliss 5-Year Devotee Circle · Household Privilege</span>
                </span>
                <div>
                    <h2 class="inline-block text-xl sm:text-3xl lg:text-4xl font-serif font-extrabold text-[#FDE68A] bg-[#881337] border border-amber-300/40 px-6 sm:px-8 py-2.5 sm:py-3 rounded-2xl sm:rounded-3xl shadow-xl leading-tight">
                        Receive Fresh Consecrated Gangajal at Your Doorstep Every Single Month
                    </h2>
                </div>
                <p class="text-xs sm:text-base text-amber-950 font-bold leading-relaxed mt-3.5 max-w-2xl mx-auto">
                    Never worry about holy water for daily morning puja or abhishek. For a single one-time contribution of ₹{{ number_format($membershipFee) }} valid for 5 full years (60 months), receive 1 free 500ml bottle of authentic Brahmakund Gangajal every month directly from Har Ki Pauri.
                </p>
            </div>

            <!-- Value Math & Privileges Interactive Card Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-stretch">
                <!-- Left 8 Cols: 4 Core Devotee Privileges -->
                <div class="lg:col-span-8 bg-[#FEF4CF] rounded-3xl border-2 border-amber-300/50 p-6 sm:p-8 flex flex-col justify-between shadow-2xl">
                    <div>
                        <div class="flex items-center justify-between pb-4 border-b border-amber-400/30 mb-6">
                            <div>
                                <span class="text-xs font-bold uppercase tracking-widest text-[#6B0E29] font-mono">Devotee Privilege Suite</span>
                                <h3 class="text-lg sm:text-xl font-serif font-bold text-[#6B0E29] mt-0.5" style="color:#6B0E29 ;">What You Receive for 5 Full Years</h3>
                            </div>
                            <span class="px-3 py-1 bg-amber-400/20 text-[#6B0E29] border border-amber-400/40 rounded-full text-xs font-bold">
                                60 Months Total Validity
                            </span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                            <!-- Benefit 1 -->
                            <div class="p-4 rounded-2xl bg-[#6B0E29] border border-amber-300/30 hover:border-amber-400/60 transition shadow-sm">
                                <div class="flex items-start gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-amber-400/20 text-amber-300 flex items-center justify-center shrink-0 font-extrabold text-base">
                                        🪷
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-white text-sm">60 Free Gangajal Bottles</h4>
                                        <p class="text-amber-100 mt-1 leading-relaxed text-[11px]">
                                            1 free 500ml bottle every 30 days (₹0 product price; nominal delivery applies). <strong class="text-[#FDE68A]">Real Market Value: ₹8,940+</strong>.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Benefit 2 -->
                            <div class="p-4 rounded-2xl bg-[#6B0E29] border border-amber-300/30 hover:border-amber-400/60 transition shadow-sm">
                                <div class="flex items-start gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-amber-400/20 text-amber-300 flex items-center justify-center shrink-0 font-extrabold text-base">
                                        🏷️
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-white text-sm">Member-Only Catalog Discounts</h4>
                                        <p class="text-amber-100 mt-1 leading-relaxed text-[11px]">
                                            Save 15% to 20% on all handcrafted brassware, dhoop, and hawan samagri. <strong class="text-[#FDE68A]">Estimated Savings: ₹2,500+</strong>.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Benefit 3 -->
                            <div class="p-4 rounded-2xl bg-[#6B0E29] border border-amber-300/30 hover:border-amber-400/60 transition shadow-sm">
                                <div class="flex items-start gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-amber-400/20 text-amber-300 flex items-center justify-center shrink-0 font-extrabold text-base">
                                        ⚡
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-white text-sm">Priority Festive Dispatch</h4>
                                        <p class="text-amber-100 mt-1 leading-relaxed text-[11px]">
                                            Guaranteed priority shipping during Maha Shivratri, Ganga Dussehra, Navratri, Shravan Maas & Diwali.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Benefit 4 -->
                            <div class="p-4 rounded-2xl bg-[#6B0E29] border border-amber-300/30 hover:border-amber-400/60 transition shadow-sm">
                                <div class="flex items-start gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-amber-400/20 text-amber-300 flex items-center justify-center shrink-0 font-extrabold text-base">
                                        📱
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-white text-sm">Brahmakund Batch QR Video</h4>
                                        <p class="text-amber-100 mt-1 leading-relaxed text-[11px]">
                                            Scan each bottle's unique QR code to view the morning Aarti video, sourcing timestamp, and lab analysis report.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 pt-4 border-t border-amber-400/30 flex flex-wrap items-center justify-between gap-3 text-xs text-amber-200">
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="text-[#6B0E29] font-medium">No Annual Subscriptions · No Renewal Fees</span>
                        </span>
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="text-[#6B0E29] font-medium">Claim Bottles Anytime via Devotee Dashboard</span>
                        </span>
                    </div>
                </div>

                <!-- Right 4 Cols: Value Math & Activation Box -->
                <div class="lg:col-span-4 bg-gradient-to-b from-[#FFFDF7] to-amber-50 rounded-3xl p-6 sm:p-8 text-[#881337] shadow-2xl flex flex-col justify-between border-2 border-amber-300">
                    <div>
                        <div class="text-center pb-4 border-b border-amber-200">
                            <span class="inline-block px-3 py-1 bg-[#881337] text-white text-[10px] font-extrabold uppercase tracking-widest rounded-full mb-2">
                                Auspicious Devotee Offering
                            </span>
                            <h4 class="text-xs font-bold text-amber-900 uppercase tracking-wider">One-Time 5-Year Dakshina</h4>
                            <div class="mt-1 flex items-baseline justify-center gap-1">
                                <span class="text-4xl sm:text-5xl font-extrabold font-serif text-[#881337]">₹{{ number_format($membershipFee) }}</span>
                                <span class="text-xs font-bold text-amber-900">/ 5 Full Years</span>
                            </div>
                            <span class="inline-block mt-2 text-[11px] font-bold text-[#0369A1] bg-sky-100/80 border border-sky-300 px-3 py-0.5 rounded-full">
                                Equivalent to ₹8.33 / month · GST Included
                            </span>
                        </div>

                        <!-- Side by Side Comparison -->
                        <div class="py-4 space-y-2.5 text-xs">
                            <div class="flex justify-between items-center text-amber-900">
                                <span>60 Monthly Bottles (Non-Member):</span>
                                <span class="line-through font-mono">₹8,940</span>
                            </div>
                            <div class="flex justify-between items-center text-amber-900">
                                <span>Puja Samagri Member Savings:</span>
                                <span class="font-mono text-emerald-700 font-bold">+ ₹2,500</span>
                            </div>
                            <div class="flex justify-between items-center pt-2 border-t border-amber-200 text-sm font-extrabold text-[#881337]">
                                <span>Total Value Delivered:</span>
                                <span class="font-mono">₹11,440+</span>
                            </div>
                            <div class="flex justify-between items-center bg-amber-100/80 p-2.5 rounded-xl border border-amber-300/80 text-xs">
                                <span class="font-bold text-[#881337]">Your 5-Year Cost:</span>
                                <span class="font-extrabold text-[#881337] font-mono text-base">₹{{ number_format($membershipFee) }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 space-y-2">
                        <a href="{{ route('membership.join') }}"
                           class="w-full inline-flex items-center justify-center gap-2 py-4 bg-gradient-to-r from-[#881337] to-[#D97706] hover:brightness-110 text-white rounded-2xl font-extrabold text-sm shadow-xl transition transform hover:-translate-y-0.5">
                            <span>Join 5-Year Devotee Circle</span>
                            <span>→</span>
                        </a>
                        <p class="text-[10px] text-center text-amber-900 font-semibold">
                            Instant tax invoice (SAC 9995) & member portal activation
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 4. Circular Category Grid / Stories Row (Shop.99Pandit "Our Collections" Style) -->
    <section class="py-10 sm:py-14 bg-[#F97316] border-b border-orange-600/40 relative overflow-hidden">
        <!-- Subtle sacred background aura -->
        <div class="absolute -top-24 -left-24 w-80 h-80 rounded-full bg-amber-200/20 blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -right-24 w-80 h-80 rounded-full bg-sky-200/20 blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-8 sm:mb-10">
                <div class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full text-[10px] sm:text-xs font-bold bg-[#881337] text-[#FDE68A] border border-amber-300/40 mb-3 shadow-md">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#F59E0B] animate-pulse"></span>
                    <span class="font-serif tracking-wider font-extrabold uppercase">Consecrated Collections & Puja Essentials</span>
                </div>
                <div class="w-full flex justify-center">
                    <h2 class="inline-block text-base sm:text-2xl md:text-3xl font-serif font-extrabold text-[#FDE68A] bg-[#881337] border border-amber-300/40 px-6 sm:px-8 py-2 sm:py-2.5 rounded-full shadow-xl tracking-tight leading-tight">
                        Sacred Collections
                    </h2>
                </div>
                <p class="text-xs sm:text-sm text-white font-semibold mt-3 max-w-xl mx-auto leading-relaxed drop-shadow-2xs">
                    Select an auspicious category to discover consecrated offerings & daily pooja essentials
                </p>
            </div>

            <!-- Horizontal Circular Category Cards Grid -->
            <div class="grid grid-cols-4 sm:grid-cols-4 lg:grid-cols-7 gap-3 sm:gap-6">
                @foreach ($circularCategories as $cat)
                    <a href="{{ route('products.index', ['category' => $cat->slug]) }}"
                       class="group flex flex-col items-center text-center">
                        <!-- Circular Image Container with Gold Ring -->
                        <div class="w-16 h-16 sm:w-24 sm:h-24 rounded-full p-1 border-2 border-amber-300/80 group-hover:border-white group-hover:shadow-xl transition-all duration-300 bg-white overflow-hidden relative transform group-hover:scale-105 shadow-md">
                            <img src="{{ $cat->image_url }}"
                                 alt="{{ $cat->name }}"
                                 loading="lazy"
                                 onerror="this.onerror=null; this.src='data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 100 100\' fill=\'%23fef3c7\'><rect width=\'100\' height=\'100\' fill=\'%23fef3c7\'/><circle cx=\'50\' cy=\'50\' r=\'20\' fill=\'%23d97706\' opacity=\'0.2\'/><path d=\'M50 25 C45 38 38 48 38 58 C38 67 43 74 50 74 C57 74 62 67 62 58 C62 48 55 38 50 25 Z\' fill=\'%23d97706\'/></svg>';"
                                 class="w-full h-full object-cover rounded-full group-hover:brightness-105 transition duration-300">
                        </div>

                        <!-- Category Title -->
                        <span class="mt-2 text-[11px] sm:text-xs font-extrabold text-white group-hover:text-[#FDE68A] transition text-center leading-tight drop-shadow-2xs">
                            {{ $cat->name }}
                        </span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- 5. BEST SELLERS SECTION (High-Density Product Carousel/Grid) -->
    <section class="py-12 sm:py-16 bg-[#FFA028] border-b border-orange-600/40 relative overflow-hidden">
        <!-- Subtle sacred background aura -->
        <div class="absolute -top-32 -right-32 w-96 h-96 rounded-full bg-amber-300/20 blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-32 -left-32 w-96 h-96 rounded-full bg-orange-400/20 blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-8 gap-4">
                <div>
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] sm:text-xs font-bold bg-[#881337] text-[#FDE68A] border border-amber-300/40 mb-2 shadow-xs">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#F59E0B] animate-pulse"></span>
                        <span class="uppercase tracking-wider font-extrabold">Most Revered Vedic Essentials</span>
                    </div>
                    <div>
                        <h2 class="inline-block text-xl sm:text-3xl font-serif font-extrabold text-[#FDE68A] bg-[#881337] border border-amber-300/40 px-5 sm:px-7 py-2 sm:py-2.5 rounded-2xl sm:rounded-3xl shadow-xl leading-tight">
                            Best Selling Sacred Samagri
                        </h2>
                    </div>
                </div>
                <a href="{{ route('products.index', ['sort' => 'rating_high']) }}"
                   class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#881337] hover:bg-[#9F1239] text-[#FDE68A] rounded-full text-xs sm:text-sm font-bold transition shadow-md border border-amber-300/40">
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

    <!-- 6. Sacred Gangajal & QR Traceability Seal (Core Brand USP) -->
    <section class="py-14 sm:py-20 bg-[#F97316] text-white relative overflow-hidden border-b border-orange-600/40">
        <!-- Ambient authentic holy water backdrop -->
        <img src="{{ asset('images/ganga-holy-waters.jpg') }}"
             alt="Sacred Holy Maa Ganga Water"
             class="absolute inset-0 w-full h-full object-cover opacity-10 mix-blend-overlay pointer-events-none">
        <div class="absolute -right-32 -bottom-32 w-96 h-96 rounded-full bg-amber-200/20 blur-3xl pointer-events-none"></div>
        <div class="absolute -left-32 -top-32 w-96 h-96 rounded-full bg-sky-200/20 blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
                <!-- Left: Consecrated Sourcing Story -->
                <div class="lg:col-span-7 space-y-6">
                    <div class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-extrabold bg-[#881337] text-[#FDE68A] border border-amber-300/40 shadow-xs mb-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#F59E0B] animate-pulse"></span>
                        <span>Har Ki Pauri · Brahma Muhurta Sanctity</span>
                    </div>

                    <div>
                        <h2 class="inline-block text-xl sm:text-3xl lg:text-4xl font-serif font-extrabold text-[#FDE68A] bg-[#881337] border border-amber-300/40 px-6 sm:px-8 py-2.5 sm:py-3.5 rounded-2xl sm:rounded-3xl shadow-xl leading-tight">
                            Untouched Brahmakund Gangajal With Complete QR Batch Traceability
                        </h2>
                    </div>

                    <p class="text-xs sm:text-base text-white font-semibold leading-relaxed drop-shadow-2xs">
                        Unlike ordinary tap-filled commercial bottles, Haridwar Bliss holy water is directly collected at the divine Brahma Muhurta (04:45 AM) during morning Ganga Aarti from the sanctified Brahmakund at Har Ki Pauri.
                    </p>

                    <!-- Sacred Quality Badges -->
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 pt-2">
                        <div class="bg-[#FEF4CF] p-4 rounded-2xl border-2 border-amber-300/80 shadow-md flex flex-col justify-between">
                            <div class="w-9 h-9 rounded-xl bg-[#881337] text-[#FDE68A] flex items-center justify-center mb-2 shadow-xs">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                </svg>
                            </div>
                            <div>
                                <span class="block font-extrabold text-xs text-[#881337]">Lab Certified Purity</span>
                                <span class="block text-[11px] text-amber-950 font-semibold mt-0.5">Tested pH 7.8 & minerals</span>
                            </div>
                        </div>
                        <div class="bg-[#FEF4CF] p-4 rounded-2xl border-2 border-amber-300/80 shadow-md flex flex-col justify-between">
                            <div class="w-9 h-9 rounded-xl bg-[#881337] text-[#FDE68A] flex items-center justify-center mb-2 shadow-xs">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </div>
                            <div>
                                <span class="block font-extrabold text-xs text-[#881337]">Touchless UV Seal</span>
                                <span class="block text-[11px] text-amber-950 font-semibold mt-0.5">100% hermetic capping</span>
                            </div>
                        </div>
                        <div class="bg-[#FEF4CF] p-4 rounded-2xl border-2 border-amber-300/80 shadow-md flex flex-col justify-between col-span-2 sm:col-span-1">
                            <div class="w-9 h-9 rounded-xl bg-[#881337] text-[#FDE68A] flex items-center justify-center mb-2 shadow-xs">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                                </svg>
                            </div>
                            <div>
                                <span class="block font-extrabold text-xs text-[#881337]">Unique QR Code</span>
                                <span class="block text-[11px] text-amber-950 font-semibold mt-0.5">Verifiable collection data</span>
                            </div>
                        </div>
                    </div>

                    @php
                        $activeBatchCode = $latestBatch ? $latestBatch->batch_number : 'HB-VERIFY-2026-SAMPLE';
                        $activeBatchGhat = $latestBatch ? $latestBatch->sourcing_ghat : 'Har Ki Pauri, Brahmakund, Haridwar';
                        $activeBatchDate = $latestBatch && $latestBatch->collection_date ? $latestBatch->collection_date->format('d M Y') : 'Brahma Muhurta (04:45 AM)';
                    @endphp

                    <div class="flex flex-wrap items-center gap-3 pt-2">
                        <a href="{{ route('verify.batch', $activeBatchCode) }}"
                           class="inline-flex items-center gap-2 px-6 py-3.5 rounded-xl text-sm font-bold text-[#FDE68A] bg-[#881337] hover:bg-[#9F1239] border border-amber-300/40 shadow-xl transition transform hover:-translate-y-0.5">
                            <span>Test Live Batch QR Portal</span>
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                        </a>
                        <a href="{{ route('products.index', ['category' => 'sacred-gangajal']) }}"
                           class="inline-flex items-center gap-2 px-6 py-3.5 rounded-xl text-sm font-bold text-[#6B0E29] bg-[#FEF4CF] hover:bg-white border-2 border-amber-300/80 shadow-md transition">
                            <span>View Gangajal Editions</span>
                        </a>
                    </div>
                </div>

                <!-- Right: Interactive Batch Seal Card (Section 3 Style) -->
                <div class="lg:col-span-5 bg-gradient-to-b from-[#FFFDF7] to-amber-50 rounded-3xl p-6 sm:p-8 text-[#881337] shadow-2xl flex flex-col justify-between border-2 border-amber-300">
                    <!-- Photo preview of Brahmakund collection site -->
                    <div class="relative h-44 rounded-2xl overflow-hidden mb-5 border-2 border-amber-300 shadow-md group">
                        <img src="{{ asset('images/har-ki-pauri-temples.jpg') }}"
                             alt="Brahmakund Har Ki Pauri Haridwar"
                             class="w-full h-full object-cover transition transform duration-500 group-hover:scale-105">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/30 to-transparent"></div>
                        <div class="absolute bottom-3 left-3 right-3 flex items-center justify-between">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[11px] font-bold bg-[#881337] text-[#FDE68A] border border-amber-300/40 backdrop-blur-xs">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                Brahmakund Consecration Sanctum
                            </span>
                            <span class="text-[10px] text-amber-300 font-mono font-bold">Har Ki Pauri</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between pb-4 border-b border-amber-200">
                        <div>
                            <span class="text-xs font-mono uppercase text-[#881337] font-extrabold bg-amber-200/60 px-2.5 py-0.5 rounded-full inline-block">Haridwar Bliss Traceability Seal</span>
                            <h3 class="text-base sm:text-lg font-serif font-bold text-[#881337] mt-1.5">
                                Brahmakund Batch #{{ $activeBatchCode }}
                            </h3>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-[#881337] text-[#FDE68A] border border-amber-300/50 flex items-center justify-center shadow-md">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                    </div>

                    <div class="py-4 space-y-3 text-xs">
                        <div class="flex justify-between items-center">
                            <span class="text-amber-900 font-semibold">Sourcing Ghat:</span>
                            <span class="font-extrabold text-[#881337] text-right">{{ $activeBatchGhat }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-amber-900 font-semibold">Collection Date / Time:</span>
                            <span class="font-extrabold text-[#881337] text-right">{{ $activeBatchDate }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-amber-900 font-semibold">Bottling Technique:</span>
                            <span class="font-bold text-[#0369A1]">Touchless Ultra-Violet Hermetic Capping</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-amber-900 font-semibold">Lab Analysis Status:</span>
                            <span class="font-bold text-emerald-800">Passed All Potability Standards</span>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-amber-200 text-center">
                        <p class="text-[11px] text-amber-900 font-bold mb-3">
                            Unique QR code printed on every bottle package & invoice
                        </p>
                        <div class="inline-block p-2.5 bg-white rounded-2xl border-2 border-amber-300 shadow-md">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=110x110&data={{ urlencode(route('verify.batch', $activeBatchCode)) }}"
                                 alt="Sample QR" class="w-24 h-24 mx-auto" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Sacred Haridwar Darshan & Provenance Showcase (Authentic Sourcing Ghats) -->
    <section class="py-14 sm:py-20 bg-[#FFA028] border-b border-orange-600/40 relative overflow-hidden">
        <!-- Subtle sacred background aura -->
        <div class="absolute -top-32 -left-32 w-96 h-96 rounded-full bg-amber-300/20 blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-32 -right-32 w-96 h-96 rounded-full bg-orange-400/20 blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center max-w-3xl mx-auto mb-10 sm:mb-14">
                <div class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-extrabold bg-[#881337] text-[#FDE68A] border border-amber-300/40 mb-3 shadow-xs">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#F59E0B] animate-pulse"></span>
                    <span>Direct From The Holy Source · Har Ki Pauri</span>
                </div>
                <div>
                    <h2 class="inline-block text-xl sm:text-3xl lg:text-4xl font-serif font-extrabold text-[#FDE68A] bg-[#881337] border border-amber-300/40 px-6 sm:px-8 py-2.5 sm:py-3.5 rounded-2xl sm:rounded-3xl shadow-xl leading-tight">
                        Sacred Haridwar Darshan & Brahmakund Sanctum
                    </h2>
                </div>
                <p class="text-xs sm:text-base text-amber-950 font-bold mt-3.5 max-w-2xl mx-auto leading-relaxed">
                    Witness the divine riverside ghats and ancient temples of Haridwar where every drop of BluBliss Gangajal is collected during Brahma Muhurta.
                </p>
            </div>

            <!-- 4-Card Photogrid with Sacred Captions (Matching Section 3 Card Style) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Card 1: Clock Tower Panorama -->
                <div class="group bg-[#FEF4CF] rounded-3xl border-2 border-amber-300/70 overflow-hidden shadow-lg hover:shadow-2xl hover:border-amber-400 transition-all duration-300 flex flex-col">
                    <div class="relative h-56 overflow-hidden">
                        <img src="{{ asset('images/haridwar-panorama-clocktower.jpg') }}"
                             alt="Har Ki Pauri & Historic Clock Tower"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
                        <span class="absolute bottom-3 left-3 text-xs font-bold text-[#FDE68A] bg-[#881337] border border-amber-300/40 backdrop-blur-xs px-2.5 py-1 rounded-xl shadow-md">
                            Har Ki Pauri Ghat
                        </span>
                    </div>
                    <div class="p-5 flex-1 flex flex-col justify-between space-y-2">
                        <div>
                            <h3 class="font-serif font-bold text-base text-[#881337] group-hover:text-[#D97706] transition">
                                Historic Ghanta Ghar & Ghats
                            </h3>
                            <p class="text-xs text-amber-950 font-semibold mt-1.5 leading-relaxed">
                                The iconic Har Ki Pauri where millions gather for morning & evening Maha Aarti amid glowing oil lamps and sacred bells.
                            </p>
                        </div>
                        <div class="pt-3 border-t border-amber-300/60 flex items-center justify-between text-[11px] text-amber-900 font-extrabold">
                            <span>Auspicious Pilgrimage</span>
                            <span>Uttarakhand</span>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Brahmakund Sanctum -->
                <div class="group bg-[#FEF4CF] rounded-3xl border-2 border-amber-300/70 overflow-hidden shadow-lg hover:shadow-2xl hover:border-amber-400 transition-all duration-300 flex flex-col">
                    <div class="relative h-56 overflow-hidden">
                        <img src="{{ asset('images/har-ki-pauri-temples.jpg') }}"
                             alt="Brahmakund Sanctum Sanctorum"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
                        <span class="absolute bottom-3 left-3 text-xs font-bold text-[#FDE68A] bg-[#881337] border border-amber-300/40 backdrop-blur-xs px-2.5 py-1 rounded-xl shadow-md">
                            Brahmakund Sanctum
                        </span>
                    </div>
                    <div class="p-5 flex-1 flex flex-col justify-between space-y-2">
                        <div>
                            <h3 class="font-serif font-bold text-base text-[#881337] group-hover:text-[#D97706] transition">
                                Brahmakund Sourcing Ghat
                            </h3>
                            <p class="text-xs text-amber-950 font-semibold mt-1.5 leading-relaxed">
                                The holiest epicenter where celestial nectar (Amrit) fell. Water is consecrated here during Brahma Muhurta morning Aarti.
                            </p>
                        </div>
                        <div class="pt-3 border-t border-amber-300/60 flex items-center justify-between text-[11px] text-amber-900 font-extrabold">
                            <span>Brahma Muhurta (04:45 AM)</span>
                            <span>Holy Consecration</span>
                        </div>
                    </div>
                </div>

                <!-- Card 3: Holy Ganga Waters -->
                <div class="group bg-[#FEF4CF] rounded-3xl border-2 border-amber-300/70 overflow-hidden shadow-lg hover:shadow-2xl hover:border-amber-400 transition-all duration-300 flex flex-col">
                    <div class="relative h-56 overflow-hidden">
                        <img src="{{ asset('images/ganga-holy-waters.jpg') }}"
                             alt="Pristine Himalayan Holy Ganga Waters"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
                        <span class="absolute bottom-3 left-3 text-xs font-bold text-[#FDE68A] bg-[#881337] border border-amber-300/40 backdrop-blur-xs px-2.5 py-1 rounded-xl shadow-md">
                            Pristine Himalayan Current
                        </span>
                    </div>
                    <div class="p-5 flex-1 flex flex-col justify-between space-y-2">
                        <div>
                            <h3 class="font-serif font-bold text-base text-[#881337] group-hover:text-[#D97706] transition">
                                Pure Himalayan Flow
                            </h3>
                            <p class="text-xs text-amber-950 font-semibold mt-1.5 leading-relaxed">
                                Flowing directly from Gangotri and Gaumukh glaciers, enriched with natural Himalayan minerals, copper, and self-purifying bacteriophages.
                            </p>
                        </div>
                        <div class="pt-3 border-t border-amber-300/60 flex items-center justify-between text-[11px] text-amber-900 font-extrabold">
                            <span>Tested pH 7.8</span>
                            <span>Micro-Filtered</span>
                        </div>
                    </div>
                </div>

                <!-- Card 4: Divine Evening Aarti -->
                <div class="group bg-[#FEF4CF] rounded-3xl border-2 border-amber-300/70 overflow-hidden shadow-lg hover:shadow-2xl hover:border-amber-400 transition-all duration-300 flex flex-col">
                    <div class="relative h-56 overflow-hidden">
                        <img src="{{ asset('images/evening-aarti-diyas.jpg') }}"
                             alt="Ganga Aarti Floating Diyas"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
                        <span class="absolute bottom-3 left-3 text-xs font-bold text-[#FDE68A] bg-[#881337] border border-amber-300/40 backdrop-blur-xs px-2.5 py-1 rounded-xl shadow-md">
                            Maha Ganga Aarti
                        </span>
                    </div>
                    <div class="p-5 flex-1 flex flex-col justify-between space-y-2">
                        <div>
                            <h3 class="font-serif font-bold text-base text-[#881337] group-hover:text-[#D97706] transition">
                                Ganga Aarti Devotion
                            </h3>
                            <p class="text-xs text-amber-950 font-semibold mt-1.5 leading-relaxed">
                                Thousands of floating marigold diyas and Vedic chanting invoke peace and prosperity for every home receiving our consecrated offerings.
                            </p>
                        </div>
                        <div class="pt-3 border-t border-amber-300/60 flex items-center justify-between text-[11px] text-amber-900 font-extrabold">
                            <span>Vedic Blessings</span>
                            <span>Haridwar Sanctum</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 7. CATEGORY SPOTLIGHT: Spiritual & Aromatics (Rudraksha, Malas, Dhoop) -->
    <section class="py-12 sm:py-16 bg-[#F97316] border-b border-orange-600/40 relative overflow-hidden">
        <!-- Subtle sacred background aura -->
        <div class="absolute -top-32 -right-32 w-96 h-96 rounded-full bg-amber-200/20 blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-32 -left-32 w-96 h-96 rounded-full bg-orange-400/20 blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-8 gap-4">
                <div>
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] sm:text-xs font-bold bg-[#881337] text-[#FDE68A] border border-amber-300/40 mb-2 shadow-xs">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#F59E0B] animate-pulse"></span>
                        <span class="uppercase tracking-wider font-extrabold">Divine Energy & Temple Fragrances</span>
                    </div>
                    <div>
                        <h2 class="inline-block text-xl sm:text-3xl font-serif font-extrabold text-[#FDE68A] bg-[#881337] border border-amber-300/40 px-5 sm:px-7 py-2 sm:py-2.5 rounded-2xl sm:rounded-3xl shadow-xl leading-tight">
                            Spiritual Store & Temple Aromas
                        </h2>
                    </div>
                </div>
                <a href="{{ route('products.index', ['category' => 'spiritual-items']) }}"
                   class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#881337] hover:bg-[#9F1239] text-[#FDE68A] rounded-full text-xs sm:text-sm font-bold transition shadow-md border border-amber-300/40">
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

    <!-- 8. Devotee Testimonials & Trust Grid -->
    <section class="py-14 sm:py-20 bg-[#FFA028] border-b border-orange-600/40 relative overflow-hidden">
        <!-- Subtle sacred background aura -->
        <div class="absolute -top-32 -right-32 w-96 h-96 rounded-full bg-amber-400/20 blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-32 -left-32 w-96 h-96 rounded-full bg-orange-400/20 blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center max-w-2xl mx-auto mb-12 sm:mb-16">
                <div class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-extrabold bg-[#881337] text-[#FDE68A] border border-amber-300/40 shadow-xs mb-3">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#F59E0B] animate-pulse"></span>
                    <span>Vedic Experiences & Devotee Blessings</span>
                </div>
                <div>
                    <h2 class="inline-block text-xl sm:text-3xl lg:text-4xl font-serif font-extrabold text-[#FDE68A] bg-[#881337] border border-amber-300/40 px-6 sm:px-8 py-2.5 sm:py-3.5 rounded-2xl sm:rounded-3xl shadow-xl leading-tight">
                        What Devotees Say Across India
                    </h2>
                </div>
                <p class="text-xs sm:text-base text-amber-950 font-bold mt-3.5 max-w-2xl mx-auto leading-relaxed">
                    Hear from blessed households receiving sanctified Brahmakund Gangajal & pure puja samagri across India.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-8 text-sm">
                <div class="bg-[#FEF4CF] p-6 sm:p-8 rounded-3xl border-2 border-amber-300/70 space-y-4 shadow-xl hover:shadow-2xl transition flex flex-col justify-between">
                    <div>
                        <div class="text-amber-500 flex gap-0.5">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        </div>
                        <p class="text-[#6B0E29] font-semibold leading-relaxed italic text-xs sm:text-sm mt-3">
                            "Living in Chennai, getting fresh holy water from Har Ki Pauri every month seemed impossible until we joined Haridwar Bliss. The QR code showing the exact morning collection video brought tears of devotion to our family."
                        </p>
                    </div>
                    <div class="pt-3 border-t border-amber-400/40">
                        <p class="font-extrabold text-[#881337] text-sm">Dr. K. S. Narayanan</p>
                        <p class="text-xs text-amber-900 font-bold">Chennai · 5-Year Devotee Member</p>
                    </div>
                </div>

                <div class="bg-[#FEF4CF] p-6 sm:p-8 rounded-3xl border-2 border-amber-300/70 space-y-4 shadow-xl hover:shadow-2xl transition flex flex-col justify-between">
                    <div>
                        <div class="text-amber-500 flex gap-0.5">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        </div>
                        <p class="text-[#6B0E29] font-semibold leading-relaxed italic text-xs sm:text-sm mt-3">
                            "The packaging is immaculate. Clean, touch-free hermetic seals with zero leakage. The cow dung hawan cups smell heavenly and create the exact ambience of a temple morning aarti."
                        </p>
                    </div>
                    <div class="pt-3 border-t border-amber-400/40">
                        <p class="font-extrabold text-[#881337] text-sm">Smt. Meenakshi Agrawal</p>
                        <p class="text-xs text-amber-900 font-bold">Ahmedabad · 5-Year Devotee Member</p>
                    </div>
                </div>

                <div class="bg-[#FEF4CF] p-6 sm:p-8 rounded-3xl border-2 border-amber-300/70 space-y-4 shadow-xl hover:shadow-2xl transition flex flex-col justify-between">
                    <div>
                        <div class="text-amber-500 flex gap-0.5">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        </div>
                        <p class="text-[#6B0E29] font-semibold leading-relaxed italic text-xs sm:text-sm mt-3">
                            "The brass panchamukhi aarti diya and pure sandalwood dhoop sticks transform our pooja altar into Har Ki Pauri every evening. Truly authentic Vedic service and fast dispatch."
                        </p>
                    </div>
                    <div class="pt-3 border-t border-amber-400/40">
                        <p class="font-extrabold text-[#881337] text-sm">Shri Rajesh Vardhan</p>
                        <p class="text-xs text-amber-900 font-bold">Bengaluru · 5-Year Devotee Member</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 9. Trust & Quality Assurance Pillars (99Pandit Style) -->
    <section class="py-10 sm:py-14 bg-[#F97316] border-b border-orange-600/40 relative overflow-hidden">
        <!-- Subtle sacred background aura -->
        <div class="absolute -bottom-24 -left-24 w-80 h-80 rounded-full bg-amber-200/20 blur-3xl pointer-events-none"></div>
        <div class="absolute -top-24 -right-24 w-80 h-80 rounded-full bg-orange-400/20 blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6 text-center">
                <div class="flex flex-col items-center text-center p-4 sm:p-5 rounded-2xl bg-[#FEF4CF] border-2 border-amber-300/80 shadow-md hover:shadow-xl transition transform hover:-translate-y-0.5">
                    <div class="w-12 h-12 rounded-2xl bg-[#881337] border border-amber-300/50 flex items-center justify-center text-[#FDE68A] shadow-md mb-2.5">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                        </svg>
                    </div>
                    <h4 class="font-extrabold text-[#881337] text-xs sm:text-sm">100% Brahmakund</h4>
                    <p class="text-[11px] text-amber-950 font-semibold mt-0.5">Sourced directly from Har Ki Pauri</p>
                </div>
                <div class="flex flex-col items-center text-center p-4 sm:p-5 rounded-2xl bg-[#FEF4CF] border-2 border-amber-300/80 shadow-md hover:shadow-xl transition transform hover:-translate-y-0.5">
                    <div class="w-12 h-12 rounded-2xl bg-[#881337] border border-amber-300/50 flex items-center justify-center text-[#FDE68A] shadow-md mb-2.5">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <h4 class="font-extrabold text-[#881337] text-xs sm:text-sm">Lab Tested Purity</h4>
                    <p class="text-[11px] text-amber-950 font-semibold mt-0.5">Certified chemical & microbe-free</p>
                </div>
                <div class="flex flex-col items-center text-center p-4 sm:p-5 rounded-2xl bg-[#FEF4CF] border-2 border-amber-300/80 shadow-md hover:shadow-xl transition transform hover:-translate-y-0.5">
                    <div class="w-12 h-12 rounded-2xl bg-[#881337] border border-amber-300/50 flex items-center justify-center text-[#FDE68A] shadow-md mb-2.5">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                    <h4 class="font-extrabold text-[#881337] text-xs sm:text-sm">Hermetic UV Capping</h4>
                    <p class="text-[11px] text-amber-950 font-semibold mt-0.5">Untouched sanitary bottling</p>
                </div>
                <div class="flex flex-col items-center text-center p-4 sm:p-5 rounded-2xl bg-[#FEF4CF] border-2 border-amber-300/80 shadow-md hover:shadow-xl transition transform hover:-translate-y-0.5">
                    <div class="w-12 h-12 rounded-2xl bg-[#881337] border border-amber-300/50 flex items-center justify-center text-[#FDE68A] shadow-md mb-2.5">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <h4 class="font-extrabold text-[#881337] text-xs sm:text-sm">Pan-India Express</h4>
                    <p class="text-[11px] text-amber-950 font-semibold mt-0.5">Insured express delivery</p>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>

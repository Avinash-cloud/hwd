<x-app-layout>
    @php
        $discount = $product->discount_percentage;
        $imgUrl = $product->image_url;
        $productGallery = $product->gallery_urls;
        $gallery = !empty($productGallery) ? array_values(array_unique(array_merge([$imgUrl], $productGallery))) : [
            $imgUrl,
            asset('images/haridwar-panorama-clocktower.jpg'),
            asset('images/ganga-holy-waters.jpg'),
            asset('images/evening-aarti-diyas.jpg'),
        ];
        $hasVideo = !empty($product->video_url);
        $activeVariants = $product->variants ? $product->variants->where('is_active', true)->values() : collect();
        $hasVariants = $activeVariants->count() > 0;
        $defaultVariant = $activeVariants->first();

        $selectedVariant = null;
        if (request()->filled('variant')) {
            $variantParam = request('variant');
            $selectedVariant = $activeVariants->first(function ($v) use ($variantParam) {
                return (string) $v->id === (string) $variantParam
                    || (string) $v->volume_ml === (string) $variantParam
                    || strtolower(str_replace(' ', '', $v->name)) === strtolower(str_replace(' ', '', $variantParam));
            });
        }
        if (!$selectedVariant && $hasVariants) {
            $selectedVariant = $defaultVariant;
        }
        $selectedVariantId = $selectedVariant ? $selectedVariant->id : ($defaultVariant ? $defaultVariant->id : null);
    @endphp

    <!-- Top-Level Alpine Controller Wrapping All Sections & Sticky Bar -->
    <div x-data="{
        currentImage: '{{ addslashes($imgUrl) }}',
        selectedThumb: 0,
        isVideoActive: false,
        quantity: 1,
        adding: false,
        hasVariants: {{ $hasVariants ? 'true' : 'false' }},
        activeVariantId: {{ $selectedVariantId ? $selectedVariantId : 'null' }},
        variants: {{ Js::from($activeVariants) }},
        basePrice: {{ (float) $product->price }},
        baseComparePrice: {{ $product->compare_price ? (float) $product->compare_price : 'null' }},
        baseMemberPrice: {{ (float) ($product->member_price ?? ($product->price * 0.85)) }},
        baseStock: {{ (int) $product->stock }},
        baseHsn: '{{ $product->hsn_code ?? '22019010' }}',
        baseGst: {{ (float) ($product->gst_percentage ?: 18.00) }},

        get activeVariant() {
            if (!this.hasVariants || !this.variants || this.variants.length === 0) return null;
            return this.variants.find(v => v.id === this.activeVariantId) || this.variants[0];
        },

        get currentPrice() {
            if (this.activeVariant) {
                return Number(this.activeVariant.price);
            }
            return this.basePrice;
        },

        get currentComparePrice() {
            if (this.activeVariant) {
                return this.activeVariant.compare_price ? Number(this.activeVariant.compare_price) : null;
            }
            return this.baseComparePrice;
        },

        get currentDiscount() {
            if (this.currentComparePrice && this.currentComparePrice > this.currentPrice) {
                return Math.round(((this.currentComparePrice - this.currentPrice) / this.currentComparePrice) * 100);
            }
            return {{ $discount ?? 0 }};
        },

        get currentMemberPrice() {
            if (this.activeVariant && this.activeVariant.member_price) {
                return Number(this.activeVariant.member_price);
            }
            return this.baseMemberPrice;
        },

        get currentStock() {
            if (this.activeVariant) {
                return Number(this.activeVariant.stock);
            }
            return this.baseStock;
        },

        get isInStock() {
            return this.currentStock > 0;
        },

        get currentHsn() {
            return (this.activeVariant && this.activeVariant.hsn_code) ? this.activeVariant.hsn_code : this.baseHsn;
        },

        get currentGst() {
            return (this.activeVariant && this.activeVariant.gst_percentage) ? Number(this.activeVariant.gst_percentage) : this.baseGst;
        },

        get taxableAmount() {
            return (this.currentPrice / (1 + (this.currentGst / 100))).toFixed(2);
        },

        get gstAmount() {
            return (this.currentPrice - Number(this.taxableAmount)).toFixed(2);
        },

        selectVariant(id) {
            this.activeVariantId = id;
            try {
                const url = new URL(window.location.href);
                url.searchParams.set('variant', id);
                window.history.replaceState({}, '', url.toString());
            } catch (e) {}
        },

        setMainImage(url, index) {
            this.currentImage = url;
            this.selectedThumb = index;
            this.isVideoActive = false;
        },

        showVideo() {
            this.isVideoActive = true;
            this.selectedThumb = 'video';
        },

        toggleVideo() {
            this.isVideoActive = !this.isVideoActive;
            if (!this.isVideoActive) {
                this.selectedThumb = 0;
            } else {
                this.selectedThumb = 'video';
            }
        },

        addToCart(redirectCheckout = false) {
            if (!this.isInStock) return;
            this.adding = true;
            const csrfToken = document.querySelector('meta[name=\'csrf-token\']')?.getAttribute('content');

            fetch('{{ route('cart.add') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    product_id: {{ $product->id }},
                    variant_id: this.activeVariant ? this.activeVariant.id : null,
                    quantity: this.quantity
                })
            })
            .then(res => {
                if (res.status === 401 || res.status === 403) {
                    return res.json().then(data => {
                        window.location.href = data.redirect || '{{ route('login') }}';
                    });
                }
                return res.json();
            })
            .then(data => {
                this.adding = false;
                if (data && data.success) {
                    window.dispatchEvent(new CustomEvent('cart-updated'));
                    if (redirectCheckout) {
                        window.location.href = '{{ route('checkout.index') }}';
                    } else {
                        window.dispatchEvent(new CustomEvent('open-cart-drawer'));
                    }
                }
            })
            .catch(() => {
                this.adding = false;
            });
        }
    }">

        <!-- 1. Breadcrumb Strip (Primary Orange Band) -->
        <div class="bg-[#F97316] border-b border-orange-600/40 py-3 sm:py-3.5 overflow-hidden w-full relative z-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-xs text-amber-100 flex items-center gap-2 overflow-x-auto whitespace-nowrap scrollbar-none w-full min-w-0">
                <a href="{{ route('home') }}" class="hover:text-white transition shrink-0 font-medium">Home</a>
                <span class="shrink-0 text-amber-200/60">/</span>
                <a href="{{ route('products.index') }}" class="hover:text-white transition shrink-0 font-medium">Catalog</a>
                <span class="shrink-0 text-amber-200/60">/</span>
                <a href="{{ route('products.index', ['category' => $product->category->slug]) }}"
                   class="hover:text-white transition capitalize shrink-0 font-medium">{{ $product->category->name }}</a>
                <span class="shrink-0 text-amber-200/60">/</span>
                <span class="text-white font-bold truncate max-w-[140px] sm:max-w-xs shrink-0 drop-shadow-2xs">{{ $product->name }}</span>
            </div>
        </div>

        <!-- 2. Product Showcase Main Section (Light Orange Band with Elevated Cream Cards) -->
        <section class="py-8 sm:py-14 bg-[#FFA028] border-b border-orange-600/40 relative overflow-hidden">
            <!-- Subtle sacred aura glows -->
            <div class="absolute -top-32 -right-32 w-96 h-96 rounded-full bg-amber-300/20 blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-32 -left-32 w-96 h-96 rounded-full bg-sky-200/20 blur-3xl pointer-events-none"></div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

                <!-- Signature Maroon Heading Header for the Product (Matching User Request) -->
                <div class="text-center max-w-4xl mx-auto mb-8 sm:mb-10 px-2">
                    <div class="inline-flex items-center gap-1.5 sm:gap-2 px-3.5 py-1 sm:py-1.5 rounded-full text-xs font-bold bg-[#881337] text-[#FDE68A] border border-amber-300/40 mb-3 shadow-md">
                        <span class="w-2 h-2 rounded-full bg-[#F59E0B] animate-pulse"></span>
                        <span class="font-serif tracking-wider font-extrabold uppercase">{{ $product->category->name }}</span>
                    </div>
                    <div class="w-full flex justify-center">
                        <h1 class="inline-block text-center text-xl sm:text-3xl lg:text-4xl font-serif font-extrabold text-[#FDE68A] italic bg-[#881337] border border-amber-300/40 px-6 sm:px-8 py-2.5 sm:py-3 rounded-2xl sm:rounded-3xl shadow-xl leading-tight text-wrap">
                            {{ $product->name }}
                        </h1>
                    </div>
                    @if ($product->short_description)
                        <p class="text-xs sm:text-base text-white font-medium leading-relaxed mt-3.5 max-w-2xl mx-auto drop-shadow-2xs">
                            {{ $product->short_description }}
                        </p>
                    @endif
                </div>

                <!-- Showcase Grid: Left Gallery + Right Buy Box inside elevated cream cards -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 items-start w-full min-w-0">

                    <!-- Left Column: Image Gallery Card (6 cols on lg) -->
                    <div class="lg:col-span-6 bg-[#FEF4CF] rounded-3xl border-2 border-amber-300/70 p-4 sm:p-6 shadow-xl space-y-4 w-full min-w-0 max-w-full">
                        <!-- Main Image Display with Badges and Video Player -->
                        <div class="relative w-full aspect-square bg-white border border-amber-200/80 rounded-2xl sm:rounded-3xl overflow-hidden shadow-xs">
                            <!-- Image View -->
                            <div x-show="!isVideoActive" class="w-full h-full">
                                <img :src="currentImage"
                                     alt="{{ $product->name }}"
                                     class="w-full h-full object-contain object-center transition-all duration-300">
                            </div>

                            <!-- Video View -->
                            @if ($hasVideo)
                                <div x-show="isVideoActive" x-cloak class="w-full h-full bg-slate-950 flex items-center justify-center">
                                    @if ($product->is_video_embed)
                                        <iframe src="{{ $product->video_embed_url }}"
                                                class="w-full h-full"
                                                frameborder="0"
                                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                                allowfullscreen></iframe>
                                    @else
                                        <video controls autoplay class="w-full h-full object-contain">
                                            <source src="{{ $product->resolved_video_url }}" type="video/mp4">
                                            Your browser does not support the video tag.
                                        </video>
                                    @endif
                                </div>
                            @endif

                            <!-- Badges -->
                            <div class="absolute top-3 left-3 sm:top-4 sm:left-4 flex flex-col gap-1.5 z-10 pointer-events-none">
                                @if ($discount)
                                    <span class="inline-flex items-center px-2.5 sm:px-3 py-0.5 sm:py-1 rounded-xl text-[11px] sm:text-xs font-extrabold bg-[#881337] text-[#FDE68A] border border-amber-300/30 shadow-md">
                                        {{ $discount }}% OFF
                                    </span>
                                @elseif ($product->badge)
                                    <span class="inline-flex items-center px-2.5 sm:px-3 py-0.5 sm:py-1 rounded-xl text-[11px] sm:text-xs font-extrabold bg-[#F97316] text-white shadow-md">
                                        {{ $product->badge }}
                                    </span>
                                @endif

                                @if ($product->is_gangajal)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 sm:py-1 rounded-xl text-[10px] sm:text-[11px] font-extrabold bg-[#0369A1] text-white shadow-md">
                                        <span class="w-1.5 h-1.5 rounded-full bg-sky-200"></span> Haridwar Certified
                                    </span>
                                @endif
                            </div>

                            <!-- Video Trigger Button Overlay -->
                            @if ($hasVideo)
                                <div class="absolute top-3 right-3 sm:top-4 sm:right-4 z-10">
                                    <button type="button"
                                            @click="toggleVideo()"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-extrabold transition shadow-md touch-manipulation cursor-pointer"
                                            :class="isVideoActive ? 'bg-[#881337] text-[#FDE68A]' : 'bg-[#3B2314]/90 text-amber-200 hover:bg-[#3B2314] border border-white/20'">
                                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                        <span x-text="isVideoActive ? 'View Photos' : 'Watch Video'"></span>
                                    </button>
                                </div>
                            @endif

                            @if ($product->batch)
                                <div class="absolute bottom-3 left-3 sm:bottom-4 sm:left-4 z-10">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 sm:px-3 py-0.5 sm:py-1 rounded-lg text-[10px] sm:text-[11px] font-bold bg-[#3B2314]/85 text-amber-300 backdrop-blur-xs border border-white/20">
                                        <span>Batch #{{ $product->batch->batch_number }}</span>
                                    </span>
                                </div>
                            @endif
                        </div>

                        <!-- Thumbnail Gallery Row -->
                        <div class="flex items-center gap-2 sm:gap-3 overflow-x-auto pb-2 scrollbar-none snap-x snap-mandatory">
                            @foreach ($gallery as $idx => $thumb)
                                <button type="button"
                                        @click="setMainImage('{{ $thumb }}', {{ $idx }})"
                                        class="w-14 h-14 sm:w-20 sm:h-20 rounded-xl sm:rounded-2xl bg-white border-2 overflow-hidden shrink-0 snap-start transition touch-manipulation cursor-pointer"
                                        :class="(!isVideoActive && selectedThumb === {{ $idx }}) ? 'border-[#F97316] shadow-md ring-2 ring-[#F97316]/40' : 'border-amber-200/80 hover:border-amber-400 opacity-75 hover:opacity-100'">
                                    <img src="{{ $thumb }}" alt="Thumbnail" class="w-full h-full object-cover">
                                </button>
                            @endforeach

                            @if ($hasVideo)
                                <button type="button"
                                        @click="showVideo()"
                                        class="w-14 h-14 sm:w-20 sm:h-20 rounded-xl sm:rounded-2xl bg-[#3B2314] border-2 overflow-hidden shrink-0 snap-start transition touch-manipulation flex flex-col items-center justify-center text-white cursor-pointer"
                                        :class="isVideoActive ? 'border-[#F97316] shadow-md ring-2 ring-[#F97316]/40' : 'border-amber-300 hover:border-amber-400 opacity-85 hover:opacity-100'">
                                    <div class="w-6 h-6 rounded-full bg-[#F97316] text-white flex items-center justify-center shadow">
                                        <svg class="w-3 h-3 fill-current ml-0.5" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                    </div>
                                    <span class="text-[9px] font-bold mt-1 text-amber-200 tracking-tight">Video</span>
                                </button>
                            @endif
                        </div>

                        <!-- Purity / Origin Guarantee Box -->
                        @if ($product->purity_details)
                            <div class="bg-white/95 border border-amber-300/80 rounded-2xl p-3.5 sm:p-5 space-y-2.5 sm:space-y-3 text-xs shadow-xs">
                                <div class="flex items-center gap-2 font-serif font-bold text-[#881337] text-xs sm:text-sm">
                                    <svg class="w-4 h-4 shrink-0 text-[#881337]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                    <span>Consecration & Sanctity Assurance</span>
                                </div>
                                <div class="flex gap-2.5 sm:gap-3 items-center">
                                    <img src="{{ asset('images/haridwar-panorama-clocktower.jpg') }}" alt="Sacred Haridwar" class="w-12 h-12 sm:w-16 sm:h-16 rounded-xl object-cover shrink-0 border border-amber-300/60 shadow-xs">
                                    <p class="text-[#3B2314] leading-relaxed text-[11px] sm:text-xs">
                                        {{ $product->purity_details }}
                                    </p>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Right Column: Product Info & Purchase Actions (6 cols on lg) -->
                    <div class="lg:col-span-6 bg-[#FEF4CF] rounded-3xl border-2 border-amber-300/70 p-6 sm:p-8 shadow-xl space-y-6 text-[#3B2314] w-full min-w-0 max-w-full">
                        <div>
                            <!-- Category & Ratings -->
                            <div class="flex flex-wrap items-center justify-between gap-2 mb-2">
                                <span class="text-xs font-bold uppercase tracking-widest text-[#881337] font-mono">
                                    {{ $product->category->name }}
                                </span>

                                <div class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-white border border-amber-300/80 rounded-lg text-xs font-bold text-[#3B2314] shrink-0 shadow-2xs">
                                    <div class="flex items-center text-amber-500">
                                        @for ($i = 0; $i < 5; $i++)
                                            <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        @endfor
                                    </div>
                                    <span>{{ number_format($product->rating ?? 4.8, 1) }}</span>
                                    <a href="#devotee-reviews" class="text-amber-800 hover:text-[#881337] font-normal underline transition">
                                        ({{ $product->approvedReviews->count() > 0 ? $product->approvedReviews->count() : ($product->reviews_count ?? 15) }} reviews)
                                    </a>
                                </div>
                            </div>

                            <!-- Title with Signature Maroon Style Effect -->
                            <div class="pt-1">
                                <h2 class="text-xl sm:text-2xl lg:text-3xl font-serif font-extrabold text-[#881337] leading-tight">
                                    {{ $product->name }}
                                </h2>
                            </div>

                            <!-- Short Description -->
                            <p class="mt-2.5 sm:mt-3 text-xs sm:text-sm text-[#3B2314]/85 leading-relaxed">
                                {{ $product->short_description }}
                            </p>
                        </div>

                        @if ($product->isInStock() || $hasVariants)
                            @if ($hasVariants)
                                <!-- Variant / Size Selector -->
                                <div class="space-y-2.5 pt-2">
                                    <div class="flex items-center justify-between">
                                        <label class="text-xs font-bold text-[#3B2314] uppercase tracking-wider flex items-center gap-1.5">
                                            <span>Select Size / Variant:</span>
                                            <span class="text-[#881337] font-extrabold normal-case text-sm" x-text="activeVariant ? activeVariant.name : ''"></span>
                                        </label>
                                        <span class="text-[11px] text-[#3B2314]/70 font-mono" x-show="activeVariant && activeVariant.sku" x-text="'SKU: ' + activeVariant.sku"></span>
                                    </div>
                                    <div class="flex flex-wrap gap-2.5">
                                        <template x-for="v in variants" :key="v.id">
                                            <button type="button"
                                                    @click="selectVariant(v.id)"
                                                    class="px-4 py-2.5 rounded-xl border-2 text-xs font-bold transition flex items-center gap-2 touch-manipulation cursor-pointer"
                                                    :class="activeVariantId === v.id ? 'border-[#881337] bg-white text-[#881337] shadow-md ring-2 ring-[#881337]/30' : 'border-amber-300/60 bg-white/80 text-[#3B2314] hover:border-amber-400 hover:bg-white'">
                                                <span x-text="v.name"></span>
                                                <span class="text-[11px] font-semibold text-amber-900 font-mono" x-text="'₹' + Math.round(v.price)"></span>
                                                <span x-show="v.stock <= 0" class="text-[9px] px-1.5 py-0.5 rounded bg-rose-100 text-rose-700 font-medium">Sold Out</span>
                                            </button>
                                        </template>
                                    </div>
                                </div>
                            @endif

                            <!-- Price Block with GST Breakdown -->
                            <div class="bg-white border border-amber-300/80 rounded-2xl p-4 sm:p-6 shadow-xs space-y-3">
                                <div class="flex items-baseline gap-2.5 sm:gap-3 flex-wrap">
                                    <span class="text-2xl sm:text-4xl font-extrabold text-[#3B2314]" x-text="'₹' + Math.round(currentPrice)">
                                        ₹{{ number_format($selectedVariant ? $selectedVariant->price : ($defaultVariant ? $defaultVariant->price : $product->price), 0) }}
                                    </span>

                                    <template x-if="currentComparePrice && currentComparePrice > currentPrice">
                                        <div class="inline-flex items-baseline gap-2">
                                            <del class="text-base sm:text-lg text-slate-400 font-semibold">
                                                ₹<span x-text="Math.round(currentComparePrice)"></span>
                                            </del>
                                            <span class="px-2 py-0.5 sm:px-2.5 sm:py-1 rounded-lg text-[11px] sm:text-xs font-extrabold bg-[#881337] text-[#FDE68A] border border-amber-300/40 shadow-xs">
                                                Save <span x-text="currentDiscount"></span>%
                                            </span>
                                        </div>
                                    </template>
                                </div>

                                <!-- GST & HSN Breakdown -->
                                <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-[11px] text-[#3B2314]/70">
                                    <span>Inclusive of <span x-text="currentGst"></span>% GST</span>
                                    <span class="text-amber-300">·</span>
                                    <span class="font-mono text-[#3B2314]/80">Taxable Value: ₹<span x-text="taxableAmount"></span> + GST: ₹<span x-text="gstAmount"></span></span>
                                    <template x-if="currentHsn">
                                        <span class="font-mono text-[#3B2314]/80">· HSN: <span x-text="currentHsn"></span></span>
                                    </template>
                                </div>

                                <!-- Member Pricing Benefit Banner -->
                                <div class="pt-3 border-t border-amber-200/60 flex flex-col sm:flex-row sm:items-center justify-between gap-2.5 text-xs">
                                    <div class="flex items-center gap-2">
                                        <span class="w-6 h-6 rounded-full bg-[#881337]/10 text-[#881337] flex items-center justify-center font-bold text-xs shrink-0">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </span>
                                        <div>
                                            <span class="font-bold text-[#3B2314]">5-Year Member Rate:</span>
                                            @if ($product->is_gangajal)
                                                <span class="text-[#881337] font-bold">Monthly Auto-Order Catalog</span>
                                            @else
                                                <span class="text-[#F97316] font-bold">₹<span x-text="Math.round(currentMemberPrice)"></span></span>
                                            @endif
                                        </div>
                                    </div>

                                    @if (!Auth::check() || !Auth::user()->hasActiveMembership())
                                        <a href="{{ route('membership.join') }}" class="font-bold text-[#881337] hover:underline text-[11px] self-start sm:self-auto">
                                            Join Membership →
                                        </a>
                                    @endif
                                </div>
                            </div>

                            <!-- Quantity & Actions Box -->
                            <div class="space-y-3 sm:space-y-4 pt-1 sm:pt-2">
                                <!-- Clean Responsive Quantity & Stock Row -->
                                <div class="bg-white/80 border border-amber-300/80 rounded-2xl p-3 sm:p-4">
                                    <div class="flex items-center justify-between gap-3">
                                        <div>
                                            <label class="block text-xs font-bold text-[#3B2314] mb-1">Quantity</label>
                                            <div class="inline-flex items-center border border-amber-300 rounded-xl overflow-hidden bg-white shadow-xs">
                                                <button type="button"
                                                        @click="if(quantity > 1) quantity--"
                                                        class="w-9 sm:w-10 py-2 sm:py-2.5 text-[#3B2314] hover:bg-amber-50 active:bg-amber-100 font-bold text-sm transition touch-manipulation cursor-pointer">
                                                    −
                                                </button>
                                                <input type="number"
                                                       x-model.number="quantity"
                                                       min="1" max="50"
                                                       class="w-12 sm:w-14 text-center border-0 text-sm font-bold text-[#3B2314] focus:ring-0 p-0" />
                                                <button type="button"
                                                        @click="quantity++"
                                                        class="w-9 sm:w-10 py-2 sm:py-2.5 text-[#3B2314] hover:bg-amber-50 active:bg-amber-100 font-bold text-sm transition touch-manipulation cursor-pointer">
                                                    +
                                                </button>
                                            </div>
                                        </div>

                                        <div class="text-right">
                                            <template x-if="isInStock">
                                                <div>
                                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200 shadow-2xs">
                                                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                                        <span>In Stock</span>
                                                    </span>
                                                    <span class="block text-[11px] text-[#3B2314]/70 mt-0.5 font-medium">Ready to Dispatch</span>
                                                </div>
                                            </template>
                                            <template x-if="!isInStock">
                                                <div>
                                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-800 border border-rose-200">
                                                        <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                                                        <span>Out of Stock</span>
                                                    </span>
                                                    <span class="block text-[11px] text-rose-600 mt-0.5 font-medium">Bottling in Progress</span>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>

                                <!-- Dual CTA Buttons: Add to Cart + Buy It Now -->
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 sm:gap-3 pt-1">
                                    <!-- Add to Cart (Ajax + Drawer) -->
                                    <button type="button"
                                            @click="addToCart(false)"
                                            :disabled="adding || !isInStock"
                                            class="w-full inline-flex items-center justify-center gap-2 py-3.5 sm:py-4 px-4 sm:px-6 bg-[#F97316] hover:bg-[#ea580c] active:scale-98 text-white rounded-xl font-bold text-sm shadow-md transition transform touch-manipulation disabled:opacity-40 disabled:cursor-not-allowed cursor-pointer">
                                        <template x-if="!adding">
                                            <span class="flex items-center gap-2">
                                                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                                </svg>
                                                <span x-text="isInStock ? 'Add to Sacred Cart' : 'Out of Stock'"></span>
                                            </span>
                                        </template>
                                        <template x-if="adding">
                                            <span>Adding to Cart...</span>
                                        </template>
                                    </button>

                                    <!-- Buy It Now -->
                                    <button type="button"
                                            @click="addToCart(true)"
                                            :disabled="adding || !isInStock"
                                            class="w-full inline-flex items-center justify-center gap-2 py-3.5 sm:py-4 px-4 sm:px-6 bg-[#881337] hover:bg-[#9F1239] active:scale-98 text-white rounded-xl font-bold text-sm shadow-md transition transform touch-manipulation disabled:opacity-40 disabled:cursor-not-allowed cursor-pointer">
                                        <span x-text="isInStock ? 'Buy It Now' : 'Currently Unavailable'"></span>
                                        <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                        </svg>
                                    </button>
                                </div>

                                <!-- Bulk Product Inquiry Button -->
                                <div class="pt-2">
                                    <button type="button"
                                            @click="window.dispatchEvent(new CustomEvent('open-bulk-inquiry', { detail: { productId: {{ $product->id }} } }))"
                                            class="w-full inline-flex items-center justify-center gap-2 py-3 px-4 bg-white hover:bg-amber-50 border-2 border-amber-300/90 hover:border-amber-400 text-[#881337] rounded-xl font-bold text-xs sm:text-sm shadow-xs transition hover:shadow-sm active:scale-98 touch-manipulation cursor-pointer">
                                        <svg class="w-4 h-4 text-[#881337] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                        </svg>
                                        <span>Bulk Product Inquiry</span>
                                        <span class="hidden sm:inline-flex items-center text-[10px] font-semibold text-[#881337] bg-amber-200/70 px-2 py-0.5 rounded-full ml-1">Wholesale & Temple Orders</span>
                                    </button>
                                </div>

                                @php
                                    $guestCheckoutActive = \App\Models\SiteSetting::get('guest_checkout_enabled', '0') === '1';
                                @endphp

                                @if (!Auth::check() && $guestCheckoutActive)
                                    <p class="text-[11px] text-emerald-700 font-medium text-center flex items-center justify-center gap-1.5">
                                        <svg class="w-3.5 h-3.5 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                        </svg>
                                        <span>Guest Checkout Available · Instant Pan-India Delivery</span>
                                    </p>
                                @elseif (!Auth::check() || !Auth::user()->hasActiveMembership())
                                    <p class="text-[11px] text-[#3B2314]/70 italic text-center">
                                        Note: Placing orders requires signing in or joining the {{ $validityYears ?? 5 }}-Year Membership (₹{{ number_format($membershipFee ?? 500) }} for {{ $validityYears ?? 5 }} years).
                                    </p>
                                @endif
                            </div>
                        @else
                            <!-- Out of Stock: No price shown anywhere, disabled actions -->
                            <div class="bg-rose-50/70 border border-rose-200 rounded-2xl p-4 sm:p-6 shadow-xs space-y-2">
                                <div class="flex items-center justify-between gap-2">
                                    <div class="flex items-center gap-2 text-rose-800 font-bold text-base sm:text-xl font-serif">
                                        <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                                        <span>Currently Out of Stock</span>
                                    </div>
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-100 text-rose-800 border border-rose-300">
                                        Out of Stock
                                    </span>
                                </div>
                                <p class="text-xs text-rose-700 leading-relaxed">
                                    Sacred collection and consecrated hermetic bottling is in progress at Haridwar. Please check back soon or explore our other available sacred items.
                                </p>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 sm:gap-3 pt-2">
                                <button type="button"
                                        disabled
                                        class="w-full py-3.5 px-4 bg-slate-100 text-slate-400 border border-slate-200 rounded-xl font-bold text-xs sm:text-sm cursor-not-allowed text-center">
                                    Add to Sacred Cart
                                </button>

                                <button type="button"
                                        disabled
                                        class="w-full py-3.5 px-4 bg-slate-200 text-slate-500 rounded-xl font-bold text-xs sm:text-sm cursor-not-allowed text-center shadow-xs">
                                    Out of Stock
                                </button>
                            </div>

                            <!-- Bulk Product Inquiry for Out of Stock items -->
                            <div class="pt-2">
                                <button type="button"
                                        @click="window.dispatchEvent(new CustomEvent('open-bulk-inquiry', { detail: { productId: {{ $product->id }} } }))"
                                        class="w-full inline-flex items-center justify-center gap-2 py-3 px-4 bg-white hover:bg-amber-50 border-2 border-amber-300/90 hover:border-amber-400 text-[#881337] rounded-xl font-bold text-xs sm:text-sm shadow-xs transition hover:shadow-sm active:scale-98 touch-manipulation cursor-pointer">
                                    <svg class="w-4 h-4 text-[#881337] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                    </svg>
                                    <span>Bulk Product Inquiry</span>
                                    <span class="text-[10px] font-semibold text-[#881337] bg-amber-200/70 px-2 py-0.5 rounded-full ml-1">Pre-order for Events & Temples</span>
                                </button>
                            </div>
                        @endif

                        <!-- Devotee Trust Badges -->
                        <div class="grid grid-cols-3 gap-2 sm:gap-3 pt-4 border-t border-amber-300/50 text-center text-xs">
                            <div class="p-2 sm:p-3 bg-white rounded-xl space-y-1 border border-amber-200/80 shadow-2xs">
                                <div class="w-6 h-6 sm:w-7 sm:h-7 mx-auto rounded-full bg-[#881337]/10 text-[#881337] flex items-center justify-center">
                                    <svg class="w-3.5 h-3.5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.527.82-1.124 1.903-1.685 3.226C7.302 8.877 6 12.188 6 15a6 6 0 1012 0c0-3.327-1.89-6.44-3.605-8.892a29.13 29.13 0 00-2-2.555z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <span class="font-bold text-[#3B2314] text-[10px] sm:text-[11px] block truncate">Consecrated</span>
                                <span class="text-[9px] sm:text-[10px] text-[#3B2314]/70 block truncate">Sacred Haridwar</span>
                            </div>
                            <div class="p-2 sm:p-3 bg-white rounded-xl space-y-1 border border-amber-200/80 shadow-2xs">
                                <div class="w-6 h-6 sm:w-7 sm:h-7 mx-auto rounded-full bg-[#881337]/10 text-[#881337] flex items-center justify-center">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>
                                </div>
                                <span class="font-bold text-[#3B2314] text-[10px] sm:text-[11px] block truncate">Damage-Free</span>
                                <span class="text-[9px] sm:text-[10px] text-[#3B2314]/70 block truncate">Hermetic Pack</span>
                            </div>
                            <div class="p-2 sm:p-3 bg-white rounded-xl space-y-1 border border-amber-200/80 shadow-2xs">
                                <div class="w-6 h-6 sm:w-7 sm:h-7 mx-auto rounded-full bg-[#881337]/10 text-[#881337] flex items-center justify-center">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                </div>
                                <span class="font-bold text-[#3B2314] text-[10px] sm:text-[11px] block truncate">Easy Support</span>
                                <span class="text-[9px] sm:text-[10px] text-[#3B2314]/70 block truncate">Devotee Care</span>
                            </div>
                        </div>

                        <!-- Expandable Accordion Tabs (Vedic Significance, Specs, Shipping) -->
                        <div class="pt-4 border-t border-amber-300/50 space-y-2 text-xs" x-data="{ openTab: 1 }">
                            <!-- Tab 1: Vedic Significance & Description -->
                            <div class="border border-amber-300/80 rounded-xl overflow-hidden bg-white shadow-2xs">
                                <button type="button"
                                        @click="openTab = (openTab === 1 ? null : 1)"
                                        class="w-full px-4 py-3 bg-amber-50/60 hover:bg-amber-100/60 flex items-center justify-between text-left font-serif font-bold text-[#881337] transition cursor-pointer">
                                    <span>Vedic Significance & Detailed Usage</span>
                                    <span x-text="openTab === 1 ? '−' : '+'" class="text-base font-mono"></span>
                                </button>
                                <div x-show="openTab === 1" class="p-4 bg-white text-[#3B2314] leading-relaxed border-t border-amber-200/60 space-y-3">
                                    @if ($product->description)
                                        <div class="wysiwyg-content text-[#3B2314] text-xs sm:text-sm">
                                            @if (strip_tags($product->description) !== $product->description)
                                                {!! $product->description !!}
                                            @else
                                                <p class="whitespace-pre-line">{{ $product->description }}</p>
                                            @endif
                                        </div>
                                    @endif
                                    @if ($product->benefits)
                                        <div class="bg-amber-50/80 p-3 rounded-xl border border-amber-200">
                                            <strong class="font-bold text-[#881337] block mb-1">Spiritual & Domestic Benefits:</strong>
                                            <p>{{ $product->benefits }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Tab 2: Specifications -->
                            @if ($product->specifications)
                                <div class="border border-amber-300/80 rounded-xl overflow-hidden bg-white shadow-2xs">
                                    <button type="button"
                                            @click="openTab = (openTab === 2 ? null : 2)"
                                            class="w-full px-4 py-3 bg-amber-50/60 hover:bg-amber-100/60 flex items-center justify-between text-left font-serif font-bold text-[#881337] transition cursor-pointer">
                                        <span>Specifications & Materials</span>
                                        <span x-text="openTab === 2 ? '−' : '+'" class="text-base font-mono"></span>
                                    </button>
                                    <div x-show="openTab === 2" class="p-4 bg-white border-t border-amber-200/60">
                                        <dl class="divide-y divide-amber-100">
                                            @foreach ($product->specifications as $key => $val)
                                                <div class="py-2 flex justify-between">
                                                    <dt class="text-[#3B2314]/70 font-medium">{{ $key }}:</dt>
                                                    <dd class="text-[#3B2314] font-bold">{{ $val }}</dd>
                                                </div>
                                            @endforeach
                                        </dl>
                                    </div>
                                </div>
                            @endif

                            <!-- Tab 3: Shipping & Delivery Timelines -->
                            <div class="border border-amber-300/80 rounded-xl overflow-hidden bg-white shadow-2xs">
                                <button type="button"
                                        @click="openTab = (openTab === 3 ? null : 3)"
                                        class="w-full px-4 py-3 bg-amber-50/60 hover:bg-amber-100/60 flex items-center justify-between text-left font-serif font-bold text-[#881337] transition cursor-pointer">
                                    <span>Shipping, Packaging & Delivery Information</span>
                                    <span x-text="openTab === 3 ? '−' : '+'" class="text-base font-mono"></span>
                                </button>
                                <div x-show="openTab === 3" class="p-4 bg-white text-[#3B2314] leading-relaxed border-t border-amber-200/60 space-y-2">
                                    <p>• Dispatched within 24-48 hours directly from our Haridwar center.</p>
                                    <p>• Pan-India express delivery typically arrives within 3-5 business days via NimbusPost partner couriers.</p>
                                    <p>• Standard &amp; member shipping rates apply at checkout — see our <a href="{{ route('payment-delivery-policy') }}" class="text-[#D97706] font-bold hover:underline">Payment &amp; Delivery Policy</a>.</p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </section>

        <!-- 3. Devotee Ratings & Verified Reviews Section (Primary Orange Band with Sacred Styling) -->
        <section id="devotee-reviews" class="py-14 sm:py-20 bg-[#F97316] border-b border-orange-600/40 relative overflow-hidden text-white">
            <!-- Subtle sacred aura glows -->
            <div class="absolute -top-32 -left-32 w-96 h-96 rounded-full bg-amber-200/20 blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-32 -right-32 w-96 h-96 rounded-full bg-sky-200/20 blur-3xl pointer-events-none"></div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

                <!-- Signature Maroon Heading Card (Matching Home Page Format) -->
                <div class="text-center max-w-3xl mx-auto mb-10 sm:mb-12">
                    <div class="inline-flex items-center gap-1.5 sm:gap-2 px-3.5 py-1 rounded-full text-xs font-bold bg-[#881337] text-[#FDE68A] border border-amber-300/40 mb-3 shadow-md">
                        <span class="w-2 h-2 rounded-full bg-[#F59E0B] animate-pulse"></span>
                        <span class="font-serif tracking-wider font-extrabold">Devotee Feedback & Blessings</span>
                    </div>
                    <div class="w-full flex justify-center">
                        <h2 class="inline-block text-xl sm:text-3xl lg:text-4xl font-serif font-extrabold text-[#FDE68A] italic bg-[#881337] border border-amber-300/40 px-6 sm:px-8 py-2.5 sm:py-3 rounded-2xl sm:rounded-3xl shadow-xl leading-tight text-wrap">
                            Devotee Reviews & Ratings
                        </h2>
                    </div>
                    <p class="text-xs sm:text-base text-white font-medium leading-relaxed mt-3.5 max-w-2xl mx-auto drop-shadow-2xs">
                        Only verified purchasers of consecrated items can submit temple reviews. All submissions are moderated.
                    </p>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 sm:gap-8 items-start">
                    <!-- Left Column: Add / Manage Review Form (5 cols) -->
                    <div class="lg:col-span-5 bg-[#FEF4CF] rounded-3xl border-2 border-amber-300/70 p-6 sm:p-8 shadow-xl text-[#3B2314] space-y-4 sm:space-y-5">
                        @if ($isVerifiedPurchaser)
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                                    <h3 class="font-serif font-bold text-base sm:text-lg text-[#881337]">
                                        {{ $userReview ? 'Update Your Review' : 'Share Your Sacred Review' }}
                                    </h3>
                                </div>
                                <p class="text-xs text-[#3B2314]/80">
                                    You are a verified purchaser of this offering. Your review will be published upon temple moderation.
                                </p>
                            </div>

                            @if ($userReview)
                                @if ($userReview->isPending())
                                    <div class="p-3 bg-amber-50 border border-amber-200 rounded-xl text-xs text-amber-800 flex items-center gap-2">
                                        <svg class="w-4 h-4 text-amber-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span>Your review is currently <strong>pending admin approval</strong>.</span>
                                    </div>
                                @elseif ($userReview->isApproved())
                                    <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-xl text-xs text-emerald-800 flex items-center gap-2">
                                        <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span>Your review is <strong>approved and published</strong> on our store!</span>
                                    </div>
                                @elseif ($userReview->isRejected())
                                    <div class="p-3 bg-rose-50 border border-rose-200 rounded-xl text-xs text-rose-800 flex items-center gap-2">
                                        <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                        </svg>
                                        <span>Your previous review was rejected. You may submit an updated review below.</span>
                                    </div>
                                @endif
                            @endif

                            <!-- Review Submission Form -->
                            <form method="POST" action="{{ route('reviews.store', $product->id) }}" class="space-y-4"
                                  x-data="{
                                      rating: {{ old('rating', $userReview?->rating ?? 5) }},
                                      hoverRating: 0
                                  }">
                                @csrf

                                <!-- Interactive Star Rating Input -->
                                <div>
                                    <label class="block text-xs font-bold text-[#3B2314] mb-1.5">Your Rating</label>
                                    <input type="hidden" name="rating" :value="rating">
                                    <div class="flex items-center gap-1.5">
                                        <template x-for="star in [1, 2, 3, 4, 5]" :key="star">
                                            <button type="button"
                                                    @click="rating = star"
                                                    @mouseenter="hoverRating = star"
                                                    @mouseleave="hoverRating = 0"
                                                    class="p-1 focus:outline-none transition transform hover:scale-110 cursor-pointer">
                                                <svg class="w-6 h-6 transition-colors"
                                                     :class="(hoverRating ? hoverRating >= star : rating >= star) ? 'text-amber-500 fill-current' : 'text-amber-200 fill-current'"
                                                     viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                            </button>
                                        </template>
                                        <span class="text-xs font-bold text-[#3B2314] ml-2" x-text="rating + ' of 5 Stars'"></span>
                                    </div>
                                    @error('rating')
                                        <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Review Headline / Title -->
                                <div>
                                    <label for="review_title" class="block text-xs font-bold text-[#3B2314] mb-1">Review Headline (Optional)</label>
                                    <input type="text"
                                           id="review_title"
                                           name="title"
                                           value="{{ old('title', $userReview?->title) }}"
                                           placeholder="e.g. Sacred Haridwar water of supreme purity"
                                           class="w-full text-xs bg-white border border-amber-300 rounded-xl px-3.5 py-2.5 text-[#3B2314] placeholder-amber-700/50 focus:bg-white focus:border-[#881337] focus:ring-1 focus:ring-[#881337]" />
                                    @error('title')
                                        <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Review Comments -->
                                <div>
                                    <label for="review_comment" class="block text-xs font-bold text-[#3B2314] mb-1">Your Sacred Feedback & Experience</label>
                                    <textarea id="review_comment"
                                              name="comment"
                                              rows="4"
                                              required
                                              placeholder="Share your spiritual experience with this product, its authenticity, hermetic packaging, or delivery..."
                                              class="w-full text-xs bg-white border border-amber-300 rounded-xl px-3.5 py-2.5 text-[#3B2314] placeholder-amber-700/50 focus:bg-white focus:border-[#881337] focus:ring-1 focus:ring-[#881337]">{{ old('comment', $userReview?->comment) }}</textarea>
                                    @error('comment')
                                        <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <button type="submit"
                                        class="w-full py-3 px-6 bg-[#881337] hover:bg-[#9F1239] text-[#FDE68A] font-serif font-bold text-sm rounded-xl shadow-md transition transform hover:-translate-y-0.5 border border-amber-300/40 cursor-pointer">
                                    {{ $userReview ? 'Submit Updated Review' : 'Submit Verified Devotee Review' }}
                                </button>
                            </form>
                        @elseif (Auth::check())
                            <!-- Logged In Devotee, but Not a Verified Purchaser -->
                            <div class="text-center py-6 space-y-3">
                                <div class="w-12 h-12 rounded-full bg-amber-100 text-[#881337] flex items-center justify-center mx-auto">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                </div>
                                <h4 class="font-serif font-bold text-base text-[#881337]">Verified Purchases Only</h4>
                                <p class="text-xs text-[#3B2314]/80 max-w-xs mx-auto leading-relaxed">
                                    Only devotees with an authentic, verified order of <strong>{{ $product->name }}</strong> may submit reviews to maintain genuine sanctity.
                                </p>
                                @if ($product->isInStock())
                                    <button type="button"
                                            @click="addToCart(true)"
                                            class="inline-block mt-2 px-5 py-2.5 bg-[#881337] hover:bg-[#9F1239] text-[#FDE68A] text-xs font-serif font-bold rounded-xl shadow-xs transition border border-amber-300/40 cursor-pointer">
                                        Order Item to Leave Review
                                    </button>
                                @endif
                            </div>
                        @else
                            <!-- Guest User -->
                            <div class="text-center py-6 space-y-3">
                                <div class="w-12 h-12 rounded-full bg-white border border-[#881337]/30 text-[#881337] flex items-center justify-center mx-auto">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <h4 class="font-serif font-bold text-base text-[#881337]">Devotee Sign In Required</h4>
                                <p class="text-xs text-[#3B2314]/80 max-w-xs mx-auto leading-relaxed">
                                    Please sign in with your verified devotee account to submit a review for this sacred item.
                                </p>
                                <a href="{{ route('login') }}"
                                   class="inline-block mt-2 px-6 py-2.5 bg-[#881337] hover:bg-[#9F1239] text-[#FDE68A] text-xs font-serif font-bold rounded-xl shadow-xs transition border border-amber-300/40 cursor-pointer">
                                    Sign In to Review
                                </a>
                            </div>
                        @endif
                    </div>

                    <!-- Right Column: Approved Reviews List (7 cols) -->
                    <div class="lg:col-span-7 bg-[#FEF4CF] rounded-3xl border-2 border-amber-300/70 p-6 sm:p-8 shadow-xl text-[#3B2314] space-y-4">
                        <div class="flex items-center justify-between border-b border-amber-300/60 pb-3">
                            <h3 class="font-serif font-bold text-lg text-[#881337]">
                                Devotee Testimonials ({{ $product->approvedReviews->count() }})
                            </h3>
                            <span class="text-[11px] font-semibold text-emerald-800 bg-emerald-50 px-2.5 py-1 rounded-full border border-emerald-300">
                                ✓ 100% Verified Purchases
                            </span>
                        </div>

                        <!-- Aggregate Rating Summary Badge -->
                        <div class="flex items-center gap-3 sm:gap-4 bg-white border border-amber-300/80 rounded-2xl p-3 sm:p-4 shadow-xs">
                            <div class="text-2xl sm:text-3xl font-extrabold text-[#881337] font-serif leading-none">
                                {{ number_format($product->rating ?? 4.8, 1) }}
                            </div>
                            <div>
                                <div class="flex items-center text-amber-500">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <svg class="w-4 h-4 {{ $i <= round($product->rating ?? 4.8) ? 'fill-current' : 'text-slate-300 fill-current' }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    @endfor
                                </div>
                                <span class="text-[10px] sm:text-[11px] text-[#3B2314]/70 font-medium block mt-0.5">
                                    Based on {{ $product->approvedReviews->count() > 0 ? $product->approvedReviews->count() : ($product->reviews_count ?? 15) }} devotee reviews
                                </span>
                            </div>
                        </div>

                        @forelse ($product->approvedReviews as $rev)
                            <div class="bg-white rounded-2xl border border-amber-200/80 p-5 shadow-xs space-y-2">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <span class="font-bold text-sm text-[#3B2314]">{{ $rev->user->name ?? 'Devotee' }}</span>
                                            @if ($rev->is_verified_purchase)
                                                <span class="inline-flex items-center gap-1 px-1.5 py-0.2 rounded text-[10px] font-bold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                                    <svg class="w-3 h-3 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                                    <span>Verified Purchase</span>
                                                </span>
                                            @endif
                                        </div>
                                        <span class="text-[10px] text-[#3B2314]/60 block mt-0.5">
                                            Reviewed on {{ $rev->created_at->format('M d, Y') }}
                                        </span>
                                    </div>

                                    <!-- Star Rating -->
                                    <div class="flex items-center text-amber-500">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <svg class="w-3.5 h-3.5 {{ $i <= $rev->rating ? 'fill-current' : 'text-slate-200 fill-current' }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        @endfor
                                    </div>
                                </div>

                                @if ($rev->title)
                                    <h4 class="font-bold text-xs sm:text-sm text-[#881337] leading-snug">{{ $rev->title }}</h4>
                                @endif

                                <p class="text-xs text-[#3B2314]/85 leading-relaxed whitespace-pre-line">{{ $rev->comment }}</p>
                            </div>
                        @empty
                            <div class="text-center py-12 bg-white rounded-2xl border border-amber-200/80 p-8 space-y-2">
                                <div class="w-10 h-10 rounded-full bg-amber-50 text-[#881337] flex items-center justify-center mx-auto mb-2">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                    </svg>
                                </div>
                                <h4 class="font-serif font-bold text-[#881337] text-sm">No devotee reviews yet</h4>
                                <p class="text-xs text-[#3B2314]/70 max-w-sm mx-auto">
                                    Be the first verified devotee to share your thoughts on this sacred offering once you have placed an order.
                                </p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </section>

        <!-- 4. Related Products Section (Light Orange Band with Home Page Heading Format) -->
        @if ($relatedProducts && $relatedProducts->count() > 0)
            <section class="py-14 sm:py-20 bg-[#FFA028] border-b border-orange-600/40 relative overflow-hidden">
                <!-- Subtle sacred background aura -->
                <div class="absolute -top-32 -right-32 w-96 h-96 rounded-full bg-amber-300/20 blur-3xl pointer-events-none"></div>

                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                    <!-- Signature Maroon Heading Card -->
                    <div class="text-center max-w-3xl mx-auto mb-10 sm:mb-12">
                        <div class="inline-flex items-center gap-1.5 sm:gap-2 px-3.5 py-1 rounded-full text-xs font-bold bg-[#881337] text-[#FDE68A] border border-amber-300/40 mb-3 shadow-md">
                            <span class="w-2 h-2 rounded-full bg-[#F59E0B] animate-pulse"></span>
                            <span class="font-serif tracking-wider font-extrabold">Complementary Devotional Items</span>
                        </div>
                        <div class="w-full flex justify-center">
                            <h2 class="inline-block text-xl sm:text-3xl lg:text-4xl font-serif font-extrabold text-[#FDE68A] italic bg-[#881337] border border-amber-300/40 px-6 sm:px-8 py-2.5 sm:py-3 rounded-2xl sm:rounded-3xl shadow-xl leading-tight text-wrap">
                                You May Also Need for Your Puja
                            </h2>
                        </div>
                        <p class="text-xs sm:text-base text-white font-medium leading-relaxed mt-3.5 max-w-2xl mx-auto drop-shadow-2xs">
                            Complete your daily worship and home temple rituals with authentically consecrated offerings.
                        </p>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                        @foreach ($relatedProducts as $rel)
                            <x-product-card :product="$rel" />
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        <!-- Mobile Sticky Bottom Purchase Bar (Inside Alpine scope) -->
        <div class="lg:hidden fixed bottom-0 inset-x-0 z-40 bg-white/95 backdrop-blur-md border-t border-amber-300/80 px-4 py-3 shadow-[0_-4px_20px_rgba(0,0,0,0.08)]">
            <div class="max-w-md mx-auto flex items-center justify-between gap-3">
                @if ($product->isInStock())
                    <div class="min-w-0">
                        <span class="text-[10px] font-bold text-[#3B2314]/70 uppercase tracking-wider block">Price</span>
                        <div class="flex items-baseline gap-1.5">
                            <span class="text-xl font-extrabold text-[#3B2314] leading-none" x-text="'₹' + Math.round(currentPrice)">
                                ₹{{ number_format($selectedVariant ? $selectedVariant->price : ($defaultVariant ? $defaultVariant->price : $product->price), 0) }}
                            </span>
                            @if ($product->compare_price && $product->compare_price > $product->price)
                                <del class="text-xs text-slate-400">
                                    ₹{{ number_format($product->compare_price, 0) }}
                                </del>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <!-- Quick Add to Cart button with Cart Icon -->
                        <button type="button"
                                @click="addToCart(false)"
                                :disabled="adding"
                                class="p-2.5 bg-amber-50 hover:bg-amber-100 active:bg-amber-200 text-[#F97316] border border-[#F97316]/40 rounded-xl transition touch-manipulation disabled:opacity-50 cursor-pointer"
                                title="Add to Sacred Cart">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                        </button>

                        <!-- Buy It Now Button -->
                        <button type="button"
                                @click="addToCart(true)"
                                :disabled="adding"
                                class="px-5 py-2.5 bg-[#881337] hover:bg-[#9F1239] active:scale-98 text-[#FDE68A] text-xs font-serif font-bold rounded-xl shadow-md transition touch-manipulation disabled:opacity-50 flex items-center gap-1.5 whitespace-nowrap border border-amber-300/40 cursor-pointer">
                            <span>Buy Now</span>
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </button>
                    </div>
                @else
                    <div class="min-w-0">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-800 border border-rose-300">
                            <span class="w-1.5 h-1.5 rounded-full bg-rose-600"></span>
                            <span>Currently Out of Stock</span>
                        </span>
                    </div>

                    <div>
                        <button type="button"
                                disabled
                                class="px-5 py-2.5 bg-slate-200 text-slate-500 text-xs font-bold rounded-xl cursor-not-allowed">
                            Out of Stock
                        </button>
                    </div>
                @endif
            </div>
        </div>

    </div>

    <!-- Bulk Product Inquiry Modal -->
    <x-bulk-inquiry-modal :products="$allProducts ?? collect([$product])" :currentProductId="$product->id" />

    <style>
        .wysiwyg-content {
            line-height: 1.65;
        }
        .wysiwyg-content p {
            margin-bottom: 0.75rem;
        }
        .wysiwyg-content p:last-child {
            margin-bottom: 0;
        }
        .wysiwyg-content h2 {
            font-family: 'Liberation Serif', Georgia, serif;
            font-size: 1.15rem;
            font-weight: 700;
            color: #881337;
            margin-top: 1.25rem;
            margin-bottom: 0.5rem;
            line-height: 1.35;
        }
        .wysiwyg-content h3 {
            font-family: 'Liberation Serif', Georgia, serif;
            font-size: 1.025rem;
            font-weight: 600;
            color: #881337;
            margin-top: 1rem;
            margin-bottom: 0.375rem;
            line-height: 1.4;
        }
        .wysiwyg-content ul {
            list-style-type: disc !important;
            padding-left: 1.25rem !important;
            margin-top: 0.5rem;
            margin-bottom: 0.75rem;
        }
        .wysiwyg-content ol {
            list-style-type: decimal !important;
            padding-left: 1.25rem !important;
            margin-top: 0.5rem;
            margin-bottom: 0.75rem;
        }
        .wysiwyg-content li {
            margin-bottom: 0.25rem;
            line-height: 1.55;
        }
        .wysiwyg-content strong,
        .wysiwyg-content b {
            font-weight: 600;
            color: #3B2314;
        }
        .wysiwyg-content em,
        .wysiwyg-content i {
            font-style: italic;
        }
        .wysiwyg-content u {
            text-decoration: underline;
            text-underline-offset: 2px;
        }
        .wysiwyg-content s {
            text-decoration: line-through;
        }
        .wysiwyg-content a {
            color: #b45309;
            text-decoration: underline;
            font-weight: 500;
            text-underline-offset: 2px;
            transition: color 0.15s ease;
        }
        .wysiwyg-content a:hover {
            color: #78350f;
        }
        .wysiwyg-content blockquote {
            border-left: 3px solid #d97706;
            padding-left: 0.85rem;
            margin: 0.75rem 0;
            color: #475569;
            font-style: italic;
        }
    </style>
</x-app-layout>

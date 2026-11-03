<x-app-layout>
    @php
        $discount = $product->discount_percentage;
        $imgUrl = $product->image_url;
        $gallery = !empty($product->gallery) ? $product->gallery : [
            $imgUrl,
            asset('images/haridwar-panorama-clocktower.jpg'),
            asset('images/har-ki-pauri-temples.jpg'),
            asset('images/brahmakund-ghat-shrines.jpg'),
            asset('images/ganga-holy-waters.jpg'),
        ];
    @endphp

    <!-- Breadcrumb -->
    <div class="bg-[#FFFDF7] border-b border-amber-200/60 py-3 sm:py-4 overflow-hidden w-full">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-xs text-slate-500 flex items-center gap-2 overflow-x-auto whitespace-nowrap scrollbar-none w-full min-w-0">
            <a href="{{ route('home') }}" class="hover:text-[#D97706] transition shrink-0">Home</a>
            <span class="shrink-0 text-slate-300">/</span>
            <a href="{{ route('products.index') }}" class="hover:text-[#D97706] transition shrink-0">Catalog</a>
            <span class="shrink-0 text-slate-300">/</span>
            <a href="{{ route('products.index', ['category' => $product->category->slug]) }}"
               class="hover:text-[#D97706] transition capitalize shrink-0">{{ $product->category->name }}</a>
            <span class="shrink-0 text-slate-300">/</span>
            <span class="text-slate-900 font-bold truncate max-w-[140px] sm:max-w-xs shrink-0">{{ $product->name }}</span>
        </div>
    </div>

    <!-- Product Details Main Section (Shop.99Pandit Style) -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-14 pb-28 lg:pb-14 w-full min-w-0 overflow-hidden"
         x-data="pdpManager({{ $product->id }}, '{{ $imgUrl }}')">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-start w-full min-w-0">
            
            <!-- Left Column: Image Gallery (5 cols on lg) -->
            <div class="lg:col-span-6 space-y-4 w-full min-w-0 max-w-full">
                <!-- Main Image Display with Badges -->
                <div class="relative w-full aspect-square bg-[#FFFDF7] border border-amber-200/80 rounded-2xl sm:rounded-3xl overflow-hidden shadow-xs">
                    <img :src="currentImage"
                         alt="{{ $product->name }}"
                         class="w-full h-full object-cover object-center transition-all duration-300">

                    <!-- Badges -->
                    <div class="absolute top-3 left-3 sm:top-4 sm:left-4 flex flex-col gap-1.5 z-10 pointer-events-none">
                        @if ($discount)
                            <span class="inline-flex items-center px-2.5 sm:px-3 py-0.5 sm:py-1 rounded-xl text-[11px] sm:text-xs font-extrabold bg-[#881337] text-white shadow-md">
                                {{ $discount }}% OFF
                            </span>
                        @elseif ($product->badge)
                            <span class="inline-flex items-center px-2.5 sm:px-3 py-0.5 sm:py-1 rounded-xl text-[11px] sm:text-xs font-extrabold bg-[#D97706] text-white shadow-md">
                                {{ $product->badge }}
                            </span>
                        @endif

                        @if ($product->is_gangajal)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 sm:py-1 rounded-xl text-[10px] sm:text-[11px] font-extrabold bg-[#0369A1] text-white shadow-md">
                                <span class="w-1.5 h-1.5 rounded-full bg-sky-200"></span> Brahmakund Certified
                            </span>
                        @endif
                    </div>

                    @if ($product->batch)
                        <div class="absolute bottom-3 left-3 sm:bottom-4 sm:left-4 z-10">
                            <span class="inline-flex items-center gap-1.5 px-2.5 sm:px-3 py-0.5 sm:py-1 rounded-lg text-[10px] sm:text-[11px] font-bold bg-slate-900/80 text-amber-300 backdrop-blur-xs border border-white/20">
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
                                class="w-14 h-14 sm:w-20 sm:h-20 rounded-xl sm:rounded-2xl bg-white border-2 overflow-hidden shrink-0 snap-start transition touch-manipulation"
                                :class="selectedThumb === {{ $idx }} ? 'border-[#D97706] shadow-md ring-2 ring-[#D97706]/30' : 'border-slate-200 hover:border-amber-300 opacity-75 hover:opacity-100'">
                            <img src="{{ $thumb }}" alt="Thumbnail" class="w-full h-full object-cover">
                        </button>
                    @endforeach
                </div>

                <!-- Purity / Origin Guarantee Box -->
                @if ($product->purity_details)
                    <div class="bg-[#FFFDF7] border border-amber-300/60 rounded-2xl p-3.5 sm:p-5 space-y-2.5 sm:space-y-3 text-xs shadow-xs">
                        <div class="flex items-center gap-2 font-serif font-bold text-[#881337] text-xs sm:text-sm">
                            <svg class="w-4 h-4 shrink-0 text-[#881337]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                            <span>Consecration & Sanctity Assurance</span>
                        </div>
                        <div class="flex gap-2.5 sm:gap-3 items-center">
                            <img src="{{ asset('images/har-ki-pauri-temples.jpg') }}" alt="Har Ki Pauri Sanctum" class="w-12 h-12 sm:w-16 sm:h-16 rounded-xl object-cover shrink-0 border border-amber-300/60 shadow-xs">
                            <p class="text-slate-700 leading-relaxed text-[11px] sm:text-xs">
                                {{ $product->purity_details }}
                            </p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Right Column: Product Info & Purchase Actions (6 cols on lg) -->
            <div class="lg:col-span-6 space-y-6 w-full min-w-0 max-w-full">
                <div>
                    <!-- Category & Ratings -->
                    <div class="flex flex-wrap items-center justify-between gap-2 mb-2">
                        <span class="text-xs font-bold uppercase tracking-widest text-[#D97706] font-mono">
                            {{ $product->category->name }}
                        </span>

                        <div class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-amber-50 border border-amber-200 rounded-lg text-xs font-bold text-slate-800 shrink-0">
                            <div class="flex items-center text-amber-500">
                                @for ($i = 0; $i < 5; $i++)
                                    <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                @endfor
                            </div>
                            <span>{{ number_format($product->rating ?? 4.8, 1) }}</span>
                            <a href="#devotee-reviews" class="text-slate-500 hover:text-[#D97706] font-normal underline transition">
                                ({{ $product->approvedReviews->count() > 0 ? $product->approvedReviews->count() : ($product->reviews_count ?? 15) }} reviews)
                            </a>
                        </div>
                    </div>

                    <!-- Title -->
                    <h1 class="text-xl sm:text-3xl lg:text-4xl font-serif font-extrabold text-slate-900 leading-tight">
                        {{ $product->name }}
                    </h1>

                    <!-- Short Description -->
                    <p class="mt-2.5 sm:mt-3 text-xs sm:text-sm text-slate-600 leading-relaxed">
                        {{ $product->short_description }}
                    </p>
                </div>

                @if ($product->isInStock())
                    <!-- Price Block (99Pandit Style) -->
                    <div class="bg-[#FFFDF7] border border-amber-200/80 rounded-2xl p-4 sm:p-6 shadow-xs space-y-3">
                        <div class="flex items-baseline gap-2.5 sm:gap-3 flex-wrap">
                            <span class="text-2xl sm:text-4xl font-extrabold text-slate-900">
                                ₹{{ number_format($product->price, 0) }}
                            </span>

                            @if ($product->compare_price && $product->compare_price > $product->price)
                                <del class="text-base sm:text-lg text-slate-400 font-semibold">
                                    ₹{{ number_format($product->compare_price, 0) }}
                                </del>
                                <span class="px-2 py-0.5 sm:px-2.5 sm:py-1 rounded-lg text-[11px] sm:text-xs font-extrabold bg-[#881337] text-white">
                                    Save {{ $discount }}%
                                </span>
                            @endif
                        </div>

                        <p class="text-[11px] text-slate-500">
                            Inclusive of all taxes · Free Sacred Delivery on orders above ₹499
                        </p>

                        <!-- Member Pricing Benefit Banner -->
                        <div class="pt-3 border-t border-amber-200/60 flex flex-col sm:flex-row sm:items-center justify-between gap-2.5 text-xs">
                            <div class="flex items-center gap-2">
                                <span class="w-6 h-6 rounded-full bg-[#D97706]/10 text-[#D97706] flex items-center justify-center font-bold text-xs shrink-0">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </span>
                                <div>
                                    <span class="font-bold text-slate-800">5-Year Member Rate:</span>
                                    @if ($product->is_gangajal)
                                        <span class="text-[#0369A1] font-bold">1st Bottle Free / Month</span>
                                    @else
                                        <span class="text-[#D97706] font-bold">₹{{ number_format($product->member_price ?? ($product->price * 0.85), 0) }}</span>
                                    @endif
                                </div>
                            </div>

                            @if (!Auth::check() || !Auth::user()->hasActiveMembership())
                                <a href="{{ route('membership.join') }}" class="font-bold text-[#D97706] hover:underline text-[11px] self-start sm:self-auto">
                                    Join Membership →
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Quantity & Actions Box -->
                    <div class="space-y-3 sm:space-y-4 pt-1 sm:pt-2">
                        <!-- Clean Responsive Quantity & Stock Row (No awkward misalignment) -->
                        <div class="bg-amber-50/40 border border-amber-200/60 rounded-2xl p-3 sm:p-4">
                            <div class="flex items-center justify-between gap-3">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Quantity</label>
                                    <div class="inline-flex items-center border border-slate-300 rounded-xl overflow-hidden bg-white shadow-xs">
                                        <button type="button"
                                                @click="if(quantity > 1) quantity--"
                                                class="w-9 sm:w-10 py-2 sm:py-2.5 text-slate-600 hover:bg-slate-100 active:bg-slate-200 font-bold text-sm transition touch-manipulation">
                                            −
                                        </button>
                                        <input type="number"
                                               x-model.number="quantity"
                                               min="1" max="50"
                                               class="w-12 sm:w-14 text-center border-0 text-sm font-bold text-slate-900 focus:ring-0 p-0" />
                                        <button type="button"
                                                @click="quantity++"
                                                class="w-9 sm:w-10 py-2 sm:py-2.5 text-slate-600 hover:bg-slate-100 active:bg-slate-200 font-bold text-sm transition touch-manipulation">
                                            +
                                        </button>
                                    </div>
                                </div>

                                <div class="text-right">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200 shadow-2xs">
                                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                        <span>In Stock</span>
                                    </span>
                                    <span class="block text-[11px] text-slate-500 mt-0.5 font-medium">Ready to Dispatch</span>
                                </div>
                            </div>
                        </div>

                        <!-- Dual CTA Buttons: Add to Cart + Buy It Now -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 sm:gap-3 pt-1">
                            <!-- Add to Cart (Ajax + Drawer) -->
                            <button type="button"
                                    @click="addToCart(false)"
                                    :disabled="adding"
                                    class="w-full inline-flex items-center justify-center gap-2 py-3.5 sm:py-4 px-4 sm:px-6 bg-[#D97706] hover:bg-[#F97316] active:scale-98 text-white rounded-xl font-bold text-sm shadow-md transition transform touch-manipulation disabled:opacity-50">
                                <template x-if="!adding">
                                    <span class="flex items-center gap-2">
                                        <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                        </svg>
                                        <span>Add to Sacred Cart</span>
                                    </span>
                                </template>
                                <template x-if="adding">
                                    <span>Adding to Cart...</span>
                                </template>
                            </button>

                            <!-- Buy It Now -->
                            <button type="button"
                                    @click="addToCart(true)"
                                    :disabled="adding"
                                    class="w-full inline-flex items-center justify-center gap-2 py-3.5 sm:py-4 px-4 sm:px-6 bg-[#881337] hover:bg-[#991B1B] active:scale-98 text-white rounded-xl font-bold text-sm shadow-md transition transform touch-manipulation disabled:opacity-50">
                                <span>Buy It Now</span>
                                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </button>
                        </div>

                        @if (!Auth::check() || !Auth::user()->hasActiveMembership())
                            <p class="text-[11px] text-slate-500 italic text-center">
                                Note: Placing orders requires signing in or joining the 5-Year Membership (₹500 for 5 years).
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
                @endif

                <!-- Devotee Trust Badges -->
                <div class="grid grid-cols-3 gap-2 sm:gap-3 pt-4 border-t border-slate-100 text-center text-xs">
                    <div class="p-2 sm:p-3 bg-stone-50 rounded-xl space-y-1">
                        <div class="w-6 h-6 sm:w-7 sm:h-7 mx-auto rounded-full bg-amber-100/70 text-[#D97706] flex items-center justify-center">
                            <svg class="w-3.5 h-3.5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.527.82-1.124 1.903-1.685 3.226C7.302 8.877 6 12.188 6 15a6 6 0 1012 0c0-3.327-1.89-6.44-3.605-8.892a29.13 29.13 0 00-2-2.555z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <span class="font-bold text-slate-800 text-[10px] sm:text-[11px] block truncate">Consecrated</span>
                        <span class="text-[9px] sm:text-[10px] text-slate-500 block truncate">Har Ki Pauri</span>
                    </div>
                    <div class="p-2 sm:p-3 bg-stone-50 rounded-xl space-y-1">
                        <div class="w-6 h-6 sm:w-7 sm:h-7 mx-auto rounded-full bg-amber-100/70 text-[#D97706] flex items-center justify-center">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                        <span class="font-bold text-slate-800 text-[10px] sm:text-[11px] block truncate">Damage-Free</span>
                        <span class="text-[9px] sm:text-[10px] text-slate-500 block truncate">Hermetic Pack</span>
                    </div>
                    <div class="p-2 sm:p-3 bg-stone-50 rounded-xl space-y-1">
                        <div class="w-6 h-6 sm:w-7 sm:h-7 mx-auto rounded-full bg-amber-100/70 text-[#D97706] flex items-center justify-center">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <span class="font-bold text-slate-800 text-[10px] sm:text-[11px] block truncate">Easy Support</span>
                        <span class="text-[9px] sm:text-[10px] text-slate-500 block truncate">Devotee Care</span>
                    </div>
                </div>

                <!-- Expandable Accordion Tabs (Vedic Significance, Specs, Shipping) -->
                <div class="pt-4 border-t border-slate-100 space-y-2 text-xs" x-data="{ openTab: 1 }">
                    <!-- Tab 1: Vedic Significance & Description -->
                    <div class="border border-amber-200/60 rounded-xl overflow-hidden">
                        <button type="button"
                                @click="openTab = (openTab === 1 ? null : 1)"
                                class="w-full px-4 py-3 bg-[#FFFDF7] flex items-center justify-between text-left font-bold text-slate-800 hover:text-[#D97706] transition">
                            <span>Vedic Significance & Detailed Usage</span>
                            <span x-text="openTab === 1 ? '−' : '+'" class="text-base font-mono"></span>
                        </button>
                        <div x-show="openTab === 1" class="p-4 bg-white text-slate-700 leading-relaxed border-t border-amber-200/50 space-y-3">
                            <p class="whitespace-pre-line">{{ $product->description }}</p>
                            @if ($product->benefits)
                                <div class="bg-amber-50/70 p-3 rounded-xl border border-amber-200/50">
                                    <strong class="font-bold text-[#881337] block mb-1">Spiritual & Domestic Benefits:</strong>
                                    <p>{{ $product->benefits }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Tab 2: Specifications -->
                    @if ($product->specifications)
                        <div class="border border-amber-200/60 rounded-xl overflow-hidden">
                            <button type="button"
                                    @click="openTab = (openTab === 2 ? null : 2)"
                                    class="w-full px-4 py-3 bg-[#FFFDF7] flex items-center justify-between text-left font-bold text-slate-800 hover:text-[#D97706] transition">
                                <span>Specifications & Materials</span>
                                <span x-text="openTab === 2 ? '−' : '+'" class="text-base font-mono"></span>
                            </button>
                            <div x-show="openTab === 2" class="p-4 bg-white border-t border-amber-200/50">
                                <dl class="divide-y divide-slate-100">
                                    @foreach ($product->specifications as $key => $val)
                                        <div class="py-2 flex justify-between">
                                            <dt class="text-slate-500 font-medium">{{ $key }}:</dt>
                                            <dd class="text-slate-900 font-bold">{{ $val }}</dd>
                                        </div>
                                    @endforeach
                                </dl>
                            </div>
                        </div>
                    @endif

                    <!-- Tab 3: Shipping & Delivery Timelines -->
                    <div class="border border-amber-200/60 rounded-xl overflow-hidden">
                        <button type="button"
                                @click="openTab = (openTab === 3 ? null : 3)"
                                class="w-full px-4 py-3 bg-[#FFFDF7] flex items-center justify-between text-left font-bold text-slate-800 hover:text-[#D97706] transition">
                            <span>Shipping, Packaging & Delivery Information</span>
                            <span x-text="openTab === 3 ? '−' : '+'" class="text-base font-mono"></span>
                        </button>
                        <div x-show="openTab === 3" class="p-4 bg-white text-slate-700 leading-relaxed border-t border-amber-200/50 space-y-2">
                            <p>• Dispatched within 24-48 hours directly from our Haridwar sanctum.</p>
                            <p>• Pan-India express delivery typically arrives within 3-5 business days via Shiprocket insured couriers.</p>
                            <p>• Free shipping applied automatically on all orders over ₹499.</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Devotee Ratings & Verified Reviews Section -->
        <section id="devotee-reviews" class="mt-14 sm:mt-24 pt-10 sm:pt-12 border-t border-amber-200/60">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 sm:gap-6 mb-8 sm:mb-10">
                <div>
                    <span class="text-xs font-bold uppercase tracking-widest text-[#D97706] font-mono">
                        Devotee Feedback & Blessings
                    </span>
                    <h2 class="text-xl sm:text-3xl font-serif font-extrabold text-slate-900 mt-1">
                        Devotee Reviews & Ratings
                    </h2>
                    <p class="text-xs text-slate-500 mt-1">
                        Only verified purchasers of consecrated items can submit temple reviews. All submissions are moderated.
                    </p>
                </div>

                <!-- Aggregate Rating Summary Badge -->
                <div class="flex items-center gap-3 sm:gap-4 bg-amber-50/80 border border-amber-200/80 rounded-2xl p-3 sm:p-4 shrink-0 shadow-xs self-start sm:self-auto">
                    <div class="text-2xl sm:text-3xl font-extrabold text-slate-900 font-serif leading-none">
                        {{ number_format($product->rating ?? 4.8, 1) }}
                    </div>
                    <div>
                        <div class="flex items-center text-amber-500">
                            @for ($i = 1; $i <= 5; $i++)
                                <svg class="w-4 h-4 {{ $i <= round($product->rating ?? 4.8) ? 'fill-current' : 'text-slate-300 fill-current' }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            @endfor
                        </div>
                        <span class="text-[10px] sm:text-[11px] text-slate-500 font-medium block mt-0.5">
                            Based on {{ $product->approvedReviews->count() > 0 ? $product->approvedReviews->count() : ($product->reviews_count ?? 15) }} devotee reviews
                        </span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 sm:gap-8 items-start">
                <!-- Left Column: Add / Manage Review Form (5 cols) -->
                <div class="lg:col-span-5 bg-white rounded-2xl sm:rounded-3xl border border-amber-200/80 p-4 sm:p-6 shadow-xs space-y-4 sm:space-y-5">
                    @if ($isVerifiedPurchaser)
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                                <h3 class="font-serif font-bold text-base sm:text-lg text-slate-900">
                                    {{ $userReview ? 'Update Your Review' : 'Share Your Sacred Review' }}
                                </h3>
                            </div>
                            <p class="text-xs text-slate-500">
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
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Your Rating</label>
                                <input type="hidden" name="rating" :value="rating">
                                <div class="flex items-center gap-1.5">
                                    <template x-for="star in [1, 2, 3, 4, 5]" :key="star">
                                        <button type="button"
                                                @click="rating = star"
                                                @mouseenter="hoverRating = star"
                                                @mouseleave="hoverRating = 0"
                                                class="p-1 focus:outline-none transition transform hover:scale-110">
                                            <svg class="w-6 h-6 transition-colors"
                                                 :class="(hoverRating ? hoverRating >= star : rating >= star) ? 'text-amber-500 fill-current' : 'text-slate-200 fill-current'"
                                                 viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        </button>
                                    </template>
                                    <span class="text-xs font-bold text-slate-700 ml-2" x-text="rating + ' of 5 Stars'"></span>
                                </div>
                                @error('rating')
                                    <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Review Headline / Title -->
                            <div>
                                <label for="review_title" class="block text-xs font-bold text-slate-700 mb-1">Review Headline (Optional)</label>
                                <input type="text"
                                       id="review_title"
                                       name="title"
                                       value="{{ old('title', $userReview?->title) }}"
                                       placeholder="e.g. Sacred Brahmakund water of supreme purity"
                                       class="w-full text-xs bg-slate-50 border border-slate-300 rounded-xl px-3.5 py-2.5 text-slate-800 placeholder-slate-400 focus:bg-white focus:border-[#D97706] focus:ring-1 focus:ring-[#D97706]" />
                                @error('title')
                                    <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Review Comments -->
                            <div>
                                <label for="review_comment" class="block text-xs font-bold text-slate-700 mb-1">Your Sacred Feedback & Experience</label>
                                <textarea id="review_comment"
                                          name="comment"
                                          rows="4"
                                          required
                                          placeholder="Share your spiritual experience with this product, its authenticity, hermetic packaging, or delivery..."
                                          class="w-full text-xs bg-slate-50 border border-slate-300 rounded-xl px-3.5 py-2.5 text-slate-800 placeholder-slate-400 focus:bg-white focus:border-[#D97706] focus:ring-1 focus:ring-[#D97706]">{{ old('comment', $userReview?->comment) }}</textarea>
                                @error('comment')
                                    <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <button type="submit"
                                    class="w-full py-3 px-6 bg-[#D97706] hover:bg-[#F97316] text-white font-bold text-xs rounded-xl shadow-md transition transform hover:-translate-y-0.5">
                                {{ $userReview ? 'Submit Updated Review' : 'Submit Verified Devotee Review' }}
                            </button>
                        </form>
                    @elseif (Auth::check())
                        <!-- Logged In Devotee, but Not a Verified Purchaser -->
                        <div class="text-center py-6 space-y-3">
                            <div class="w-12 h-12 rounded-full bg-amber-50 text-[#D97706] flex items-center justify-center mx-auto">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </div>
                            <h4 class="font-serif font-bold text-base text-slate-900">Verified Purchases Only</h4>
                            <p class="text-xs text-slate-500 max-w-xs mx-auto leading-relaxed">
                                Only devotees with an authentic, verified order of <strong>{{ $product->name }}</strong> may submit reviews to maintain genuine sanctity.
                            </p>
                            @if ($product->isInStock())
                                <button type="button"
                                        @click="addToCart(true)"
                                        class="inline-block mt-2 px-5 py-2.5 bg-[#D97706] hover:bg-[#F97316] text-white text-xs font-bold rounded-xl shadow-xs transition">
                                    Order Item to Leave Review
                                </button>
                            @endif
                        </div>
                    @else
                        <!-- Guest User -->
                        <div class="text-center py-6 space-y-3">
                            <div class="w-12 h-12 rounded-full bg-[#FFFDF7] border border-[#D97706]/30 text-[#D97706] flex items-center justify-center mx-auto">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <h4 class="font-serif font-bold text-base text-slate-900">Devotee Sign In Required</h4>
                            <p class="text-xs text-slate-500 max-w-xs mx-auto leading-relaxed">
                                Please sign in with your verified devotee account to submit a review for this sacred item.
                            </p>
                            <a href="{{ route('login') }}"
                               class="inline-block mt-2 px-6 py-2.5 bg-[#D97706] hover:bg-[#F97316] text-white text-xs font-bold rounded-xl shadow-xs transition">
                                Sign In to Review
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Right Column: Approved Reviews List (7 cols) -->
                <div class="lg:col-span-7 space-y-4">
                    <div class="flex items-center justify-between border-b border-amber-200/50 pb-3">
                        <h3 class="font-serif font-bold text-lg text-slate-900">
                            Devotee Testimonials ({{ $product->approvedReviews->count() }})
                        </h3>
                        <span class="text-[11px] font-semibold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-full border border-emerald-200">
                            ✓ 100% Verified Purchases
                        </span>
                    </div>

                    @forelse ($product->approvedReviews as $rev)
                        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs space-y-2">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="font-bold text-sm text-slate-900">{{ $rev->user->name ?? 'Devotee' }}</span>
                                        @if ($rev->is_verified_purchase)
                                            <span class="inline-flex items-center gap-1 px-1.5 py-0.2 rounded text-[10px] font-bold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                                <svg class="w-3 h-3 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                                <span>Verified Purchase</span>
                                            </span>
                                        @endif
                                    </div>
                                    <span class="text-[10px] text-slate-400 block mt-0.5">
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
                                <h4 class="font-bold text-xs sm:text-sm text-slate-900 leading-snug">{{ $rev->title }}</h4>
                            @endif

                            <p class="text-xs text-slate-600 leading-relaxed whitespace-pre-line">{{ $rev->comment }}</p>
                        </div>
                    @empty
                        <div class="text-center py-12 bg-white rounded-2xl border border-slate-200 p-8 space-y-2">
                            <div class="w-10 h-10 rounded-full bg-amber-50 text-[#D97706] flex items-center justify-center mx-auto mb-2">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                </svg>
                            </div>
                            <h4 class="font-serif font-bold text-slate-800 text-sm">No devotee reviews yet</h4>
                            <p class="text-xs text-slate-500 max-w-sm mx-auto">
                                Be the first verified devotee to share your thoughts on this sacred offering once you have placed an order.
                            </p>
                        </div>
                    @endforelse
                </div>
            </div>
        </section>

        <!-- Related Products Section (Shop.99Pandit Style) -->
        @if ($relatedProducts && $relatedProducts->count() > 0)
            <div class="mt-16 sm:mt-24 pt-12 border-t border-amber-200/60">
                <div class="text-center mb-8">
                    <span class="text-xs font-bold uppercase tracking-widest text-[#D97706] font-mono">
                        Complementary Devotional Items
                    </span>
                    <h3 class="text-xl sm:text-2xl font-serif font-extrabold text-slate-900 mt-1">
                        You May Also Need for Your Puja
                    </h3>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                    @foreach ($relatedProducts as $rel)
                        <x-product-card :product="$rel" />
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Mobile Sticky Bottom Purchase Bar (Shop.99Pandit / Blinkit Style) -->
        <div class="lg:hidden fixed bottom-0 inset-x-0 z-40 bg-white/95 backdrop-blur-md border-t border-amber-200/80 px-4 py-3 shadow-[0_-4px_20px_rgba(0,0,0,0.08)]">
            <div class="max-w-md mx-auto flex items-center justify-between gap-3">
                @if ($product->isInStock())
                    <div class="min-w-0">
                        <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Price</span>
                        <div class="flex items-baseline gap-1.5">
                            <span class="text-xl font-extrabold text-slate-900 leading-none">
                                ₹{{ number_format($product->price, 0) }}
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
                                class="p-2.5 bg-amber-50 hover:bg-amber-100 active:bg-amber-200 text-[#D97706] border border-[#D97706]/40 rounded-xl transition touch-manipulation disabled:opacity-50"
                                title="Add to Sacred Cart">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                        </button>

                        <!-- Buy It Now Button -->
                        <button type="button"
                                @click="addToCart(true)"
                                :disabled="adding"
                                class="px-5 py-2.5 bg-[#881337] hover:bg-[#991B1B] active:scale-98 text-white text-xs font-bold rounded-xl shadow-md transition touch-manipulation disabled:opacity-50 flex items-center gap-1.5 whitespace-nowrap">
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
</x-app-layout>

<script>
if (typeof window.pdpManager === 'undefined') {
    window.pdpManager = function(productId, initialImage) {
        return {
            currentImage: initialImage,
            selectedThumb: 0,
            quantity: 1,
            adding: false,

            setMainImage(url, index) {
                this.currentImage = url;
                this.selectedThumb = index;
            },

            addToCart(redirectCheckout = false) {
                this.adding = true;
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

                fetch('{{ route('cart.add') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ product_id: productId, quantity: this.quantity })
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
        };
    };
}
</script>
<x-app-layout>
    @php
        $discount = $product->discount_percentage;
        $imgUrl = $product->image ?: 'https://images.unsplash.com/photo-1606787366850-de6330128bfc?w=800&auto=format&fit=crop&q=80';
        $gallery = $product->gallery ?: [
            $imgUrl,
            'https://images.unsplash.com/photo-1544717305-2782549b5136?w=800&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1596176530529-78163a4f7af2?w=800&auto=format&fit=crop&q=80',
        ];
    @endphp

    <!-- Breadcrumb -->
    <div class="bg-[#FFFDF7] border-b border-amber-200/60 py-4">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-xs text-slate-500 flex items-center gap-2">
            <a href="{{ route('home') }}" class="hover:text-[#D97706] transition">Home</a>
            <span>/</span>
            <a href="{{ route('products.index') }}" class="hover:text-[#D97706] transition">Catalog</a>
            <span>/</span>
            <a href="{{ route('products.index', ['category' => $product->category->slug]) }}"
               class="hover:text-[#D97706] transition capitalize">{{ $product->category->name }}</a>
            <span>/</span>
            <span class="text-slate-900 font-bold truncate max-w-xs">{{ $product->name }}</span>
        </div>
    </div>

    <!-- Product Details Main Section (Shop.99Pandit Style) -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-14"
         x-data="pdpManager({{ $product->id }}, '{{ $imgUrl }}')">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-start">
            
            <!-- Left Column: Image Gallery (5 cols on lg) -->
            <div class="lg:col-span-6 space-y-4">
                <!-- Main Image Display with Badges -->
                <div class="relative w-full aspect-square bg-[#FFFDF7] border border-amber-200/80 rounded-3xl overflow-hidden shadow-xs">
                    <img :src="currentImage"
                         alt="{{ $product->name }}"
                         class="w-full h-full object-cover object-center transition-all duration-300">

                    <!-- Badges -->
                    <div class="absolute top-4 left-4 flex flex-col gap-1.5 z-10 pointer-events-none">
                        @if ($discount)
                            <span class="inline-flex items-center px-3 py-1 rounded-xl text-xs font-extrabold bg-[#881337] text-white shadow-md">
                                {{ $discount }}% OFF
                            </span>
                        @elseif ($product->badge)
                            <span class="inline-flex items-center px-3 py-1 rounded-xl text-xs font-extrabold bg-[#D97706] text-white shadow-md">
                                {{ $product->badge }}
                            </span>
                        @endif

                        @if ($product->is_gangajal)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-[11px] font-extrabold bg-[#0369A1] text-white shadow-md">
                                <span>🕉️</span> Brahmakund Certified
                            </span>
                        @endif
                    </div>

                    @if ($product->batch)
                        <div class="absolute bottom-4 left-4 z-10">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-[11px] font-bold bg-slate-900/80 text-amber-300 backdrop-blur-xs border border-white/20">
                                <span>Batch #{{ $product->batch->batch_number }}</span>
                            </span>
                        </div>
                    @endif
                </div>

                <!-- Thumbnail Gallery Row -->
                <div class="flex items-center gap-3 overflow-x-auto pb-2">
                    @foreach ($gallery as $idx => $thumb)
                        <button type="button"
                                @click="setMainImage('{{ $thumb }}', {{ $idx }})"
                                class="w-20 h-20 rounded-2xl bg-white border-2 overflow-hidden shrink-0 transition"
                                :class="selectedThumb === {{ $idx }} ? 'border-[#D97706] shadow-md ring-2 ring-[#D97706]/30' : 'border-slate-200 hover:border-amber-300 opacity-75 hover:opacity-100'">
                            <img src="{{ $thumb }}" alt="Thumbnail" class="w-full h-full object-cover">
                        </button>
                    @endforeach
                </div>

                <!-- Purity / Origin Guarantee Box -->
                @if ($product->purity_details)
                    <div class="bg-[#FFFDF7] border border-amber-300/60 rounded-2xl p-4 sm:p-5 space-y-1.5 text-xs shadow-xs">
                        <div class="flex items-center gap-2 font-serif font-bold text-[#881337] text-sm">
                            <span>🛡️ Consecration & Sanctity Assurance</span>
                        </div>
                        <p class="text-slate-700 leading-relaxed">
                            {{ $product->purity_details }}
                        </p>
                    </div>
                @endif
            </div>

            <!-- Right Column: Product Info & Purchase Actions (6 cols on lg) -->
            <div class="lg:col-span-6 space-y-6">
                <div>
                    <!-- Category & Ratings -->
                    <div class="flex items-center justify-between gap-2 mb-2">
                        <span class="text-xs font-bold uppercase tracking-widest text-[#D97706] font-mono">
                            {{ $product->category->name }}
                        </span>

                        <div class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-amber-50 border border-amber-200 rounded-lg text-xs font-bold text-slate-800">
                            <span class="text-amber-500">★★★★★</span>
                            <span>{{ number_format($product->rating ?? 4.8, 1) }}</span>
                            <span class="text-slate-400 font-normal">({{ $product->reviews_count ?? 15 }} devotee reviews)</span>
                        </div>
                    </div>

                    <!-- Title -->
                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-serif font-extrabold text-slate-900 leading-tight">
                        {{ $product->name }}
                    </h1>

                    <!-- Short Description -->
                    <p class="mt-3 text-sm text-slate-600 leading-relaxed">
                        {{ $product->short_description }}
                    </p>
                </div>

                <!-- Price Block (99Pandit Style) -->
                <div class="bg-[#FFFDF7] border border-amber-200/80 rounded-2xl p-5 sm:p-6 shadow-xs space-y-3">
                    <div class="flex items-baseline gap-3 flex-wrap">
                        <span class="text-3xl sm:text-4xl font-extrabold text-slate-900">
                            ₹{{ number_format($product->price, 0) }}
                        </span>

                        @if ($product->compare_price && $product->compare_price > $product->price)
                            <del class="text-lg text-slate-400 font-semibold">
                                ₹{{ number_format($product->compare_price, 0) }}
                            </del>
                            <span class="px-2.5 py-1 rounded-lg text-xs font-extrabold bg-[#881337] text-white">
                                Save {{ $discount }}%
                            </span>
                        @endif
                    </div>

                    <p class="text-[11px] text-slate-500">
                        Inclusive of all taxes · Free Sacred Delivery on orders above ₹499
                    </p>

                    <!-- Member Pricing Benefit Banner -->
                    <div class="pt-3 border-t border-amber-200/60 flex items-center justify-between text-xs">
                        <div class="flex items-center gap-2">
                            <span class="w-6 h-6 rounded-full bg-[#D97706]/10 text-[#D97706] flex items-center justify-center font-bold text-xs">
                                🕉️
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
                            <a href="{{ route('membership.join') }}" class="font-bold text-[#D97706] hover:underline text-[11px]">
                                Join Membership →
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Quantity & Actions Box -->
                <div class="space-y-4 pt-2">
                    <div class="flex items-center gap-4">
                        <div class="w-36 shrink-0">
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Quantity</label>
                            <div class="inline-flex items-center w-full border border-slate-300 rounded-xl overflow-hidden bg-white shadow-xs">
                                <button type="button"
                                        @click="if(quantity > 1) quantity--"
                                        class="w-10 py-2.5 text-slate-600 hover:bg-slate-100 font-bold text-sm transition">
                                    -
                                </button>
                                <input type="number"
                                       x-model.number="quantity"
                                       min="1" max="50"
                                       class="w-full text-center border-0 text-sm font-bold text-slate-900 focus:ring-0 p-0" />
                                <button type="button"
                                        @click="quantity++"
                                        class="w-10 py-2.5 text-slate-600 hover:bg-slate-100 font-bold text-sm transition">
                                    +
                                </button>
                            </div>
                        </div>

                        <div class="pt-6">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                <span>In Stock ({{ $product->stock }} units ready to dispatch)</span>
                            </span>
                        </div>
                    </div>

                    <!-- Dual CTA Buttons: Add to Cart + Buy It Now -->
                    <div class="flex flex-col sm:flex-row gap-3 pt-2">
                        <!-- Add to Cart (Ajax + Drawer) -->
                        <button type="button"
                                @click="addToCart(false)"
                                :disabled="adding"
                                class="flex-1 inline-flex items-center justify-center gap-2 py-4 px-6 bg-[#D97706] hover:bg-[#F97316] text-white rounded-xl font-bold text-sm shadow-xl transition transform hover:-translate-y-0.5 disabled:opacity-50">
                            <template x-if="!adding">
                                <span class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
                                class="flex-1 inline-flex items-center justify-center gap-2 py-4 px-6 bg-[#881337] hover:bg-[#991B1B] text-white rounded-xl font-bold text-sm shadow-xl transition transform hover:-translate-y-0.5 disabled:opacity-50">
                            <span>Buy It Now</span>
                            <span>⚡</span>
                        </button>
                    </div>

                    @if (!Auth::check() || !Auth::user()->hasActiveMembership())
                        <p class="text-[11px] text-slate-500 italic text-center">
                            Note: Placing orders requires signing in or joining the 5-Year Membership (₹500 for 5 years).
                        </p>
                    @endif
                </div>

                <!-- Devotee Trust Badges -->
                <div class="grid grid-cols-3 gap-3 pt-4 border-t border-slate-100 text-center text-xs">
                    <div class="p-3 bg-stone-50 rounded-xl space-y-1">
                        <span class="text-xl block">🕉️</span>
                        <span class="font-bold text-slate-800 text-[11px] block">Consecrated</span>
                        <span class="text-[10px] text-slate-500 block">Har Ki Pauri</span>
                    </div>
                    <div class="p-3 bg-stone-50 rounded-xl space-y-1">
                        <span class="text-xl block">📦</span>
                        <span class="font-bold text-slate-800 text-[11px] block">Damage-Free</span>
                        <span class="text-[10px] text-slate-500 block">Hermetic Packaging</span>
                    </div>
                    <div class="p-3 bg-stone-50 rounded-xl space-y-1">
                        <span class="text-xl block">🔄</span>
                        <span class="font-bold text-slate-800 text-[11px] block">Easy Support</span>
                        <span class="text-[10px] text-slate-500 block">Devotee Care</span>
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
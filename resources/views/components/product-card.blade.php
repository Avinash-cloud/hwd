@props(['product'])

@php
    $discount = $product->discount_percentage;
    $imgUrl = $product->image ?: 'https://images.unsplash.com/photo-1606787366850-de6330128bfc?w=600&auto=format&fit=crop&q=80';
@endphp

<div x-data="productCard({{ $product->id }})"
     class="product-card group relative bg-white border border-amber-200/70 hover:border-[#D97706]/70 rounded-2xl overflow-hidden shadow-xs hover:shadow-xl transition-all duration-300 flex flex-col justify-between">
    
    <!-- Top Image Container -->
    <div class="relative w-full aspect-square bg-[#FFFDF7] overflow-hidden">
        <a href="{{ route('products.show', $product->slug) }}" class="block w-full h-full">
            <img src="{{ $imgUrl }}"
                 alt="{{ $product->name }}"
                 loading="lazy"
                 onerror="this.onerror=null; this.src='data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 400 400\' fill=\'%23fffbeb\'><rect width=\'400\' height=\'400\' fill=\'%23fef3c7\'/><text x=\'50%25\' y=\'45%25\' dominant-baseline=\'middle\' text-anchor=\'middle\' font-size=\'72\'>🪔</text><text x=\'50%25\' y=\'65%25\' dominant-baseline=\'middle\' text-anchor=\'middle\' font-size=\'16\' fill=\'%23b45309\' font-family=\'sans-serif\' font-weight=\'bold\'>Haridwar Bliss</text></svg>';"
                 class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-500">
        </a>

        <!-- Discount / Offer Badge (Top Left) -->
        <div class="absolute top-2.5 left-2.5 flex flex-col gap-1 z-10 pointer-events-none">
            @if ($discount)
                <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-extrabold bg-[#881337] text-white shadow-xs">
                    {{ $discount }}% OFF
                </span>
            @elseif ($product->badge)
                <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-extrabold bg-[#D97706] text-white shadow-xs">
                    {{ $product->badge }}
                </span>
            @endif

            @if ($product->is_gangajal)
                <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[9px] font-extrabold bg-[#0369A1] text-white shadow-xs">
                    <span>🕉️</span> Brahmakund
                </span>
            @endif
        </div>

        <!-- Bestseller / Rating Flag (Top Right) -->
        @if ($product->is_bestseller)
            <div class="absolute top-2.5 right-2.5 z-10 pointer-events-none">
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-bold bg-amber-100 text-amber-900 border border-amber-300 shadow-xs">
                    ★ Bestseller
                </span>
            </div>
        @endif
    </div>

    <!-- Product Info Container -->
    <div class="p-3.5 sm:p-4 flex-1 flex flex-col justify-between">
        <div>
            <!-- Category & Rating -->
            <div class="flex items-center justify-between gap-1 text-[11px] mb-1">
                <span class="font-bold text-[#D97706] uppercase tracking-wider text-[10px] truncate">
                    {{ $product->category->name ?? 'Sacred Item' }}
                </span>

                <!-- Rating -->
                <div class="inline-flex items-center gap-1 text-slate-600 font-semibold text-[11px] shrink-0">
                    <span class="text-amber-500">★</span>
                    <span>{{ number_format($product->rating ?? 4.8, 1) }}</span>
                    @if ($product->reviews_count)
                        <span class="text-slate-400 text-[10px]">({{ $product->reviews_count }})</span>
                    @endif
                </div>
            </div>

            <!-- Title -->
            <h3 class="font-bold text-xs sm:text-sm text-slate-900 leading-snug line-clamp-2 group-hover:text-[#D97706] transition mb-1">
                <a href="{{ route('products.show', $product->slug) }}">
                    {{ $product->name }}
                </a>
            </h3>

            <!-- Volume / Sourcing Badge if available -->
            @if ($product->volume_ml)
                <span class="inline-block text-[11px] text-slate-500 mb-2">
                    {{ $product->volume_ml }} ml Hermetic Bottle
                </span>
            @endif
        </div>

        <!-- Price & Quick Add Button Row (99Pandit Style) -->
        <div class="pt-3 border-t border-slate-100 mt-2 flex items-center justify-between gap-1.5">
            <!-- Price Display -->
            <div class="flex flex-col min-w-0">
                <div class="flex items-baseline gap-1 sm:gap-1.5 flex-wrap">
                    <span class="font-extrabold text-sm sm:text-base text-slate-900 whitespace-nowrap">
                        ₹{{ number_format($product->price, 0) }}
                    </span>
                    @if ($product->compare_price && $product->compare_price > $product->price)
                        <del class="text-[11px] sm:text-xs text-slate-400 whitespace-nowrap">
                            ₹{{ number_format($product->compare_price, 0) }}
                        </del>
                    @endif
                </div>
                @if ($discount)
                    <span class="text-[10px] font-bold text-emerald-700 leading-none whitespace-nowrap">
                        Save {{ $discount }}%
                    </span>
                @endif
            </div>

            <!-- Quick Add to Cart Button -->
            <button type="button"
                    @click="addToCart()"
                    :disabled="adding"
                    class="shrink-0 inline-flex items-center justify-center gap-1 px-2.5 py-1.5 sm:px-3 sm:py-2 bg-amber-50 hover:bg-[#D97706] text-[#D97706] hover:text-white border border-amber-300 hover:border-[#D97706] rounded-xl text-xs font-bold transition shadow-xs disabled:opacity-50 whitespace-nowrap">
                <template x-if="!adding">
                    <span class="inline-flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span>Add</span>
                    </span>
                </template>
                <template x-if="adding">
                    <svg class="animate-spin h-3.5 w-3.5 shrink-0" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </template>
            </button>
        </div>
    </div>
</div>

<script>
if (typeof window.productCard === 'undefined') {
    window.productCard = function(productId) {
        return {
            adding: false,
            addToCart() {
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
                    body: JSON.stringify({ product_id: productId, quantity: 1 })
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
                        window.dispatchEvent(new CustomEvent('open-cart-drawer'));
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

<div x-data="cartDrawer()"
     @open-cart-drawer.window="openDrawer()"
     @cart-updated.window="fetchCart()"
     x-cloak
     class="relative z-50">
    <!-- Backdrop -->
    <div x-show="isOpen"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="closeDrawer()"
         class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>

    <!-- Slide-Over Drawer Container -->
    <div x-show="isOpen"
         class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute inset-0 overflow-hidden">
            <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
                <div x-show="isOpen"
                     x-transition:enter="transform transition ease-in-out duration-300 sm:duration-400"
                     x-transition:enter-start="translate-x-full"
                     x-transition:enter-end="translate-x-0"
                     x-transition:leave="transform transition ease-in-out duration-300 sm:duration-400"
                     x-transition:leave-start="translate-x-0"
                     x-transition:leave-end="translate-x-full"
                     class="pointer-events-auto w-screen max-w-md bg-white shadow-2xl flex flex-col">

                    <!-- Drawer Header -->
                    <div class="px-6 py-5 bg-[#FFFDF7] border-b border-[#D97706]/20 flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <span class="w-8 h-8 rounded-lg bg-[#D97706]/10 text-[#D97706] flex items-center justify-center font-bold text-sm">
                                🛒
                            </span>
                            <div>
                                <h3 class="font-serif font-bold text-slate-900 text-lg leading-tight">Your Sacred Cart</h3>
                                <p class="text-xs text-slate-500 font-medium" x-text="itemsCountText()"></p>
                            </div>
                        </div>
                        <button type="button"
                                @click="closeDrawer()"
                                class="p-2 text-slate-400 hover:text-slate-700 hover:bg-slate-100 rounded-full transition"
                                aria-label="Close cart">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Free Shipping Threshold Banner -->
                    <div class="px-6 py-3 bg-amber-50 border-b border-amber-200/60 text-xs">
                        <template x-if="subtotal >= 499">
                            <div class="flex items-center gap-2 text-emerald-800 font-semibold">
                                <span>🎉</span>
                                <span>Congratulations! You qualify for <strong>FREE Sacred Delivery</strong> across India.</span>
                            </div>
                        </template>
                        <template x-if="subtotal < 499">
                            <div>
                                <div class="flex justify-between text-slate-700 font-medium mb-1.5">
                                    <span>Add <strong class="text-[#D97706]" x-text="'₹' + (499 - subtotal).toFixed(0)"></strong> more for <strong>FREE Delivery</strong></span>
                                    <span x-text="Math.min(100, Math.round((subtotal / 499) * 100)) + '%'"></span>
                                </div>
                                <div class="w-full bg-amber-200/60 rounded-full h-1.5 overflow-hidden">
                                    <div class="bg-[#D97706] h-1.5 rounded-full transition-all duration-300"
                                         :style="'width: ' + Math.min(100, Math.round((subtotal / 499) * 100)) + '%'"></div>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Cart Items Body -->
                    <div class="flex-1 overflow-y-auto px-6 py-4 divide-y divide-slate-100">
                        <!-- Loading State -->
                        <div x-show="loading" class="py-12 text-center text-slate-400">
                            <svg class="animate-spin h-8 w-8 text-[#D97706] mx-auto mb-3" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <p class="text-xs">Updating sacred basket...</p>
                        </div>

                        <!-- Empty State -->
                        <template x-if="!loading && items.length === 0">
                            <div class="py-16 text-center">
                                <div class="w-16 h-16 rounded-full bg-[#FFFDF7] border-2 border-dashed border-[#D97706]/40 flex items-center justify-center text-2xl mx-auto mb-4">
                                    🕉️
                                </div>
                                <h4 class="font-serif font-bold text-slate-800 text-base mb-1">Your cart is empty</h4>
                                <p class="text-xs text-slate-500 max-w-xs mx-auto mb-6">
                                    Explore pure Brahmakund holy water, cow dung hawan cups, and temple pooja essentials.
                                </p>
                                <a href="{{ route('products.index') }}"
                                   @click="closeDrawer()"
                                   class="inline-flex items-center gap-2 px-6 py-2.5 bg-[#D97706] hover:bg-[#F97316] text-white rounded-xl font-bold text-xs shadow-md transition">
                                    <span>Browse Sacred Catalog</span>
                                    <span>→</span>
                                </a>
                            </div>
                        </template>

                        <!-- Items List -->
                        <template x-if="!loading && items.length > 0">
                            <div class="space-y-4">
                                <template x-for="item in items" :key="item.product_id">
                                    <div class="py-3 flex gap-3 group">
                                        <!-- Thumbnail -->
                                        <div class="w-18 h-18 rounded-xl bg-slate-50 border border-slate-200/80 p-1 shrink-0 overflow-hidden relative">
                                            <img :src="item.image || 'https://images.unsplash.com/photo-1606787366850-de6330128bfc?w=200&fit=crop'"
                                                 :alt="item.name"
                                                 class="w-full h-full object-cover rounded-lg">
                                            <template x-if="item.is_free_monthly_bottle">
                                                <span class="absolute top-1 left-1 px-1.5 py-0.5 rounded text-[9px] font-extrabold bg-[#0369A1] text-white shadow">
                                                    FREE
                                                </span>
                                            </template>
                                        </div>

                                        <!-- Details -->
                                        <div class="flex-1 min-w-0">
                                            <div class="flex justify-between items-start gap-1">
                                                <h5 class="text-xs font-bold text-slate-900 leading-snug line-clamp-2"
                                                    x-text="item.name"></h5>
                                                <button type="button"
                                                        @click="removeItem(item.product_id)"
                                                        class="text-slate-400 hover:text-rose-600 transition p-1"
                                                        title="Remove item">
                                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </div>

                                            <div class="flex items-center gap-2 mt-1">
                                                <span class="text-xs font-extrabold text-slate-900" x-text="'₹' + item.unit_price"></span>
                                                <template x-if="item.compare_price && item.compare_price > item.unit_price">
                                                    <span class="text-[11px] text-slate-400 line-through" x-text="'₹' + item.compare_price"></span>
                                                </template>
                                            </div>

                                            <!-- Qty Controls & Total -->
                                            <div class="flex items-center justify-between mt-2 pt-1 border-t border-slate-100">
                                                <div class="inline-flex items-center border border-slate-200 rounded-lg overflow-hidden bg-slate-50">
                                                    <button type="button"
                                                            @click="updateQty(item.product_id, item.quantity - 1)"
                                                            class="px-2 py-1 text-slate-600 hover:bg-slate-200 text-xs font-bold transition">
                                                        -
                                                    </button>
                                                    <span class="px-2.5 py-1 text-xs font-bold text-slate-900 bg-white"
                                                          x-text="item.quantity"></span>
                                                    <button type="button"
                                                            @click="updateQty(item.product_id, item.quantity + 1)"
                                                            class="px-2 py-1 text-slate-600 hover:bg-slate-200 text-xs font-bold transition">
                                                        +
                                                    </button>
                                                </div>

                                                <span class="text-xs font-bold text-[#D97706]"
                                                      x-text="'₹' + item.total_price.toFixed(2)"></span>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </template>
                    </div>

                    <!-- Drawer Footer / Checkout -->
                    <template x-if="items.length > 0">
                        <div class="px-6 py-4 bg-[#FFFDF7] border-t border-[#D97706]/20 space-y-3">
                            <div class="space-y-1.5 text-xs text-slate-600">
                                <div class="flex justify-between">
                                    <span>Subtotal</span>
                                    <span class="font-bold text-slate-900" x-text="'₹' + subtotal.toFixed(2)"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Shipping Est.</span>
                                    <span class="font-bold"
                                          :class="shipping > 0 ? 'text-slate-900' : 'text-emerald-700'"
                                          x-text="shipping > 0 ? '₹' + shipping.toFixed(2) : 'FREE'"></span>
                                </div>
                                <div class="flex justify-between text-sm font-bold text-slate-900 pt-2 border-t border-slate-200">
                                    <span>Total Payable</span>
                                    <span class="text-[#D97706] text-base" x-text="'₹' + total.toFixed(2)"></span>
                                </div>
                            </div>

                            <a href="{{ route('checkout.index') }}"
                               class="w-full inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-[#D97706] hover:bg-[#F97316] text-white rounded-xl font-bold text-sm shadow-xl transition transform hover:-translate-y-0.5">
                                <span>Proceed to Sacred Checkout</span>
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                </svg>
                            </a>

                            <div class="flex items-center justify-center gap-3 text-[10px] text-slate-500 pt-1">
                                <span class="flex items-center gap-1">🔒 100% Secure Checkout</span>
                                <span>·</span>
                                <span class="flex items-center gap-1">🕉️ Consecrated at Haridwar</span>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
if (typeof window.cartDrawer === 'undefined') {
    window.cartDrawer = function() {
    return {
        isOpen: false,
        loading: false,
        items: [],
        subtotal: 0,
        shipping: 0,
        total: 0,
        itemsCount: 0,

        init() {
            this.fetchCart();
        },

        openDrawer() {
            this.isOpen = true;
            this.fetchCart();
        },

        closeDrawer() {
            this.isOpen = false;
        },

        itemsCountText() {
            if (this.itemsCount === 0) return '0 items';
            return this.itemsCount + (this.itemsCount === 1 ? ' sacred item' : ' sacred items');
        },

        fetchCart() {
            fetch('{{ route('cart.api.details') }}', {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.cart) {
                    this.applyCartData(data.cart);
                }
            })
            .catch(() => {});
        },

        applyCartData(cart) {
            this.items = cart.items || [];
            this.subtotal = parseFloat(cart.subtotal) || 0;
            this.shipping = parseFloat(cart.shipping_amount) || 0;
            this.total = parseFloat(cart.total_amount) || 0;
            this.itemsCount = parseInt(cart.total_items_count) || 0;

            // Update badge on any cart icon in DOM
            const badges = document.querySelectorAll('.cart-count-badge');
            badges.forEach(b => {
                b.textContent = this.itemsCount;
                if (this.itemsCount > 0) {
                    b.classList.remove('hidden');
                } else {
                    b.classList.add('hidden');
                }
            });
        },

        updateQty(productId, newQty) {
            this.loading = true;
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            fetch('{{ route('cart.update') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ product_id: productId, quantity: newQty })
            })
            .then(res => res.json())
            .then(data => {
                this.loading = false;
                if (data.cart) {
                    this.applyCartData(data.cart);
                }
            })
            .catch(() => { this.loading = false; });
        },

        removeItem(productId) {
            this.loading = true;
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            fetch('{{ route('cart.remove') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ product_id: productId })
            })
            .then(res => res.json())
            .then(data => {
                this.loading = false;
                if (data.cart) {
                    this.applyCartData(data.cart);
                }
            })
            .catch(() => { this.loading = false; });
        }
    };
};
}
</script>

<x-app-layout>
    <div class="bg-[#FFFDF7] border-b border-[#D97706]/30 py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-xs text-slate-500 flex items-center gap-2">
            <a href="{{ route('home') }}" class="hover:text-[#D97706]">Home</a>
            <span>/</span>
            <a href="{{ route('products.index') }}" class="hover:text-[#D97706]">Catalog</a>
            <span>/</span>
            <a href="{{ route('products.index', ['category' => $product->category->slug]) }}"
                class="hover:text-[#D97706]">{{ $product->category->name }}</a>
            <span>/</span>
            <span class="text-slate-900 font-bold truncate">{{ $product->name }}</span>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Left: Visual / Image Container -->
            <div class="space-y-4">
                <div
                    class="w-full h-96 bg-white border border-[#D97706]/30 rounded-3xl p-8 flex items-center justify-center shadow-xs overflow-hidden relative">
                    <div class="text-center">
                        <div class="w-28 h-28 mx-auto mb-4 text-[#D97706]">
                            @if ($product->is_gangajal)
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-full h-full"
                                    stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
                                </svg>
                            @else
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-full h-full"
                                    stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15.362 5.214A8.252 8.252 0 0112 21 8.25 8.25 0 016.038 7.048 8.287 8.287 0 009 9.6a8.983 8.983 0 013.361-6.867 8.21 8.21 0 003 2.48z" />
                                </svg>
                            @endif
                        </div>
                        <span
                            class="text-xs font-mono font-bold text-slate-500 uppercase tracking-widest">{{ $product->sku }}</span>
                    </div>

                    @if ($product->is_gangajal)
                        <div
                            class="absolute top-4 left-4 bg-[#D97706] text-white px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-widest shadow-xs">
                            Brahmakund Certified
                        </div>
                    @endif
                </div>

                <!-- Purity Highlight Box -->
                @if ($product->purity_details)
                    <div class="bg-[#FFFDF7] border border-[#D97706]/30 rounded-2xl p-5 space-y-2 text-xs">
                        <div class="flex items-center gap-2 font-serif font-bold text-[#881337] text-sm">
                            <span>🛡️ Verified Sanctity & Lab Details</span>
                        </div>
                        <p class="text-slate-700 leading-relaxed">
                            {{ $product->purity_details }}
                        </p>
                    </div>
                @endif
            </div>

            <!-- Right: Product Specifications & Order Box -->
            <div class="space-y-6">
                <div>
                    <span
                        class="text-xs font-bold uppercase tracking-widest text-[#D97706] font-mono">{{ $product->category->name }}</span>
                    <h1 class="text-3xl font-serif font-extrabold text-slate-900 mt-1">{{ $product->name }}</h1>
                    <p class="mt-3 text-sm text-slate-600 leading-relaxed">{{ $product->short_description }}</p>
                </div>

                <!-- Price Card with Member Comparison -->
                <div class="bg-white border border-[#D97706]/20 rounded-2xl p-6 shadow-xs space-y-4">
                    <div class="flex items-baseline justify-between">
                        <div>
                            <span class="text-xs text-slate-500 block">Devotee Member Price</span>
                            <div class="flex items-baseline gap-2">
                                @if ($product->is_gangajal)
                                    <span class="text-3xl font-extrabold text-[#0369A1] font-serif">₹0</span>
                                    <span class="text-xs font-bold text-[#0369A1]">(1st Bottle / Month Free)</span>
                                @else
                                    <span
                                        class="text-3xl font-extrabold text-[#0369A1] font-serif">₹{{ number_format($product->member_price ?? $product->price, 2) }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="text-right">
                            <span class="text-xs text-slate-400 block">Regular Price</span>
                            <span
                                class="text-base font-semibold text-slate-400 line-through">₹{{ number_format($product->price, 2) }}</span>
                        </div>
                    </div>

                    <div
                        class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                        <span>Standard Shipping: ₹{{ number_format($shippingRate) }} flat per order</span>
                        <span class="font-bold text-[#0369A1]">In Stock ({{ $product->stock }} units)</span>
                    </div>

                    <!-- Add to Cart Action -->
                    @auth
                        @if (Auth::user()->hasActiveMembership() || Auth::user()->is_admin)
                            <form method="POST" action="{{ route('cart.add') }}" class="space-y-4 pt-2">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">

                                <div class="flex items-center gap-4">
                                    <div class="w-32">
                                        <label for="quantity"
                                            class="block text-xs font-bold text-slate-700 mb-1">Quantity</label>
                                        <input type="number" id="quantity" name="quantity" value="1" min="1" max="10"
                                            class="w-full px-3 py-2 text-sm border border-slate-300 rounded-xl focus:ring-[#D97706] focus:border-[#D97706]" />
                                    </div>

                                    <div class="flex-1 pt-5">
                                        <button type="submit"
                                            class="w-full py-3.5 px-6 rounded-xl font-bold text-sm text-white bg-[#D97706] hover:bg-[#F97316] shadow-md transition">
                                            Add to Sacred Cart
                                        </button>
                                    </div>
                                </div>
                            </form>
                        @else
                            <div class="bg-[#FFFDF7] p-4 rounded-xl border border-[#D97706]/30 space-y-3">
                                <div class="flex items-center gap-2 text-xs font-bold text-[#881337]">
                                    <span>🔒 Member Exclusive Sacred Item</span>
                                </div>
                                <p class="text-xs text-slate-700 leading-relaxed">
                                    You are signed in as <strong>{{ Auth::user()->name }}</strong>. To order sacred Gangajal and
                                    enjoy free monthly deliveries, please activate your 5-Year Membership.
                                </p>
                                <a href="{{ route('membership.join') }}"
                                    class="w-full inline-flex items-center justify-center py-2.5 px-4 bg-[#D97706] hover:bg-[#F97316] text-white font-bold text-xs rounded-xl shadow-xs transition">
                                    Activate 5-Year Membership (₹{{ number_format($membershipFee) }})
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="bg-[#FFFDF7] p-4 rounded-xl border border-[#D97706]/30 space-y-3">
                            <div class="flex items-center gap-2 text-xs font-bold text-[#881337]">
                                <span>🔒 Devotee Membership Required to Order</span>
                            </div>
                            <p class="text-xs text-slate-700 leading-relaxed">
                                Please sign in or join our 5-Year Sacred Membership (₹{{ number_format($membershipFee) }})
                                to add this consecrated offering to your cart.
                            </p>
                            <div class="flex gap-2">
                                <a href="{{ route('login') }}"
                                    class="flex-1 text-center py-2.5 px-3 bg-white border border-[#D97706]/30 text-slate-800 font-bold text-xs rounded-xl hover:bg-[#FFFDF7] transition">
                                    Sign In
                                </a>
                                <a href="{{ route('membership.join') }}"
                                    class="flex-1 text-center py-2.5 px-3 bg-[#D97706] hover:bg-[#F97316] text-white font-bold text-xs rounded-xl transition">
                                    Join for ₹{{ number_format($membershipFee) }}
                                </a>
                            </div>
                        </div>
                    @endauth
                </div>

                <!-- Description & Specifications Accordion / Sections -->
                <div class="space-y-6 pt-4 border-t border-slate-200">
                    <div>
                        <h3 class="font-serif font-bold text-base text-slate-900 mb-2">Detailed Description</h3>
                        <div class="text-xs text-slate-600 leading-relaxed whitespace-pre-line">
                            {{ $product->description }}
                        </div>
                    </div>

                    @if ($product->specifications)
                        <div>
                            <h3 class="font-serif font-bold text-base text-slate-900 mb-2">Specifications</h3>
                            <div class="bg-white border border-slate-200 rounded-xl divide-y divide-slate-100 text-xs">
                                @foreach ($product->specifications as $label => $val)
                                    <div class="flex justify-between p-3">
                                        <span class="text-slate-500 font-medium">{{ $label }}</span>
                                        <span class="text-slate-900 font-bold">{{ $val }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if ($product->benefits)
                        <div>
                            <h3 class="font-serif font-bold text-base text-slate-900 mb-2">Spiritual & Ritual Benefits</h3>
                            <div
                                class="bg-[#FFFDF7] border border-[#D97706]/20 p-4 rounded-xl text-xs text-slate-700 leading-relaxed">
                                {{ $product->benefits }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Related Offerings -->
        @if ($relatedProducts->isNotEmpty())
            <div class="mt-20 pt-12 border-t border-[#D97706]/20">
                <h2 class="text-2xl font-serif font-bold text-slate-900 mb-8">Related Sacred Offerings</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach ($relatedProducts as $rel)
                        <div
                            class="bg-white rounded-2xl border border-[#D97706]/20 p-4 shadow-xs flex flex-col justify-between hover:shadow-md transition">
                            <div>
                                <h4 class="font-serif font-bold text-sm text-slate-900 mb-1">
                                    <a href="{{ route('products.show', $rel->slug) }}"
                                        class="hover:text-[#D97706]">{{ $rel->name }}</a>
                                </h4>
                                <span
                                    class="text-xs font-bold text-[#D97706]">₹{{ number_format($rel->member_price ?? $rel->price, 2) }}</span>
                            </div>
                            <div class="mt-4">
                                <a href="{{ route('products.show', $rel->slug) }}"
                                    class="block text-center py-2 bg-[#FFFDF7] hover:bg-[#D97706]/10 text-[#D97706] text-xs font-bold rounded-lg border border-[#D97706]/30 transition">
                                    View Details
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
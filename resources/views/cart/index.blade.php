<x-app-layout>
    <div class="bg-[#FFFDF7] border-b border-[#D97706]/30 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <span class="text-xs font-bold uppercase tracking-widest text-[#D97706] font-mono">Devotee Checkout</span>
            <h1 class="text-3xl font-serif font-extrabold text-slate-900 mt-1">Your Sacred Offerings Cart</h1>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        @if (empty($cartDetails['items']))
            <div class="text-center py-20 bg-white rounded-3xl border border-[#D97706]/30 p-8 shadow-xs max-w-xl mx-auto">
                <div class="w-16 h-16 rounded-2xl bg-[#D97706]/10 text-[#D97706] flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
                <h3 class="text-xl font-serif font-bold text-slate-900">Your sacred cart is currently empty</h3>
                <p class="text-xs text-slate-500 mt-2">Claim your monthly sacred offering or explore our consecrated items.</p>
                <div class="mt-6 flex flex-col sm:flex-row items-center justify-center gap-3">
                    <a href="{{ route('products.index', ['type' => 'gangajal']) }}"
                        class="px-6 py-2.5 bg-[#D97706] hover:bg-[#F97316] text-white font-bold text-xs rounded-xl shadow-xs transition">
                        Order Monthly Gangajal
                    </a>
                    <a href="{{ route('products.index') }}"
                        class="px-6 py-2.5 bg-white border border-[#D97706]/30 text-slate-700 hover:bg-[#FFFDF7] font-bold text-xs rounded-xl transition">
                        Browse Catalog
                    </a>
                </div>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10 items-start">
                <!-- Cart Items List (2 cols) -->
                <div class="lg:col-span-2 space-y-4">
                    @if ($cartDetails['free_bottle_applied'])
                        <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-4 flex items-center gap-3 text-xs text-emerald-800 shadow-xs">
                            <div class="w-7 h-7 rounded-full bg-emerald-100 flex items-center justify-center shrink-0 text-emerald-700 font-bold">
                                ✓
                            </div>
                            <div>
                                <strong class="font-bold block sm:inline">Monthly Member Benefit Applied:</strong>
                                <span>You have received 1 complimentary sacred offering for <strong>₹0.00</strong> (Standard shipping fee applies).</span>
                            </div>
                        </div>
                    @elseif ($cartDetails['monthly_quota_used'])
                        <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4 flex items-center gap-3 text-xs text-amber-900 shadow-xs">
                            <div class="w-7 h-7 rounded-full bg-amber-100 flex items-center justify-center shrink-0 text-[#D97706]">
                                ℹ
                            </div>
                            <div>
                                <strong class="font-bold block sm:inline">Devotee Monthly Status:</strong>
                                <span>You have already claimed your 1 free devotee offering for {{ now()->format('F Y') }}. Additional items are billed at exclusive member prices.</span>
                            </div>
                        </div>
                    @endif

                    <div class="bg-white rounded-3xl border border-[#D97706]/20 shadow-xs divide-y divide-slate-100 overflow-hidden">
                        @foreach ($cartDetails['items'] as $item)
                            <div class="p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-16 h-16 rounded-xl bg-slate-50 border border-slate-200 overflow-hidden shrink-0">
                                        <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover">
                                    </div>
                                    <div>
                                        <h3 class="font-serif font-bold text-base text-slate-900">
                                            <a href="{{ route('products.show', $item['product']->slug) }}" class="hover:text-[#D97706]">
                                                {{ $item['product']->name }}
                                            </a>
                                        </h3>
                                        <div class="flex items-center gap-2 mt-1 text-xs text-slate-500">
                                            @if ($item['has_free_bottle'])
                                                <span class="line-through text-slate-400">₹{{ number_format($item['unit_price'], 2) }}</span>
                                                <span class="font-bold text-emerald-700 font-mono">₹0.00</span>
                                                <span class="px-2 py-0.5 rounded-md font-bold bg-emerald-100 text-emerald-800 text-[10px]">
                                                    1 Unit FREE (₹0)
                                                </span>
                                            @else
                                                <span>Unit Price: ₹{{ number_format($item['unit_price'], 2) }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between sm:justify-end gap-6">
                                    <!-- Quantity Update Form -->
                                    <form method="POST" action="{{ route('cart.update') }}" class="flex items-center gap-2">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $item['product']->id }}">
                                        <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" max="20"
                                            class="w-16 px-2 py-1 text-xs border border-slate-300 rounded-lg text-center"
                                            onchange="this.form.submit()">
                                    </form>

                                    <!-- Price -->
                                    <div class="text-right min-w-20">
                                        <span class="block text-base font-bold text-slate-900 font-serif font-mono">
                                            ₹{{ number_format($item['line_total'], 2) }}
                                        </span>
                                        @if ($item['line_discount'] > 0)
                                            <span class="block text-[11px] text-slate-400 line-through font-mono">
                                                ₹{{ number_format($item['line_subtotal'], 2) }}
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Remove -->
                                    <form method="POST" action="{{ route('cart.remove') }}">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $item['product']->id }}">
                                        <button type="submit" class="text-slate-400 hover:text-[#991B1B] transition" title="Remove item">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Coupon Code Card -->
                    <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div>
                                <span class="text-xs font-bold text-slate-900 block">Have a Promotional Coupon?</span>
                                <span class="text-[11px] text-slate-500">Enter your code below to apply marketing or festive savings.</span>
                            </div>

                            @if($cartDetails['coupon_code'])
                                <div class="flex items-center gap-2">
                                    <span class="px-3 py-1 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-lg text-xs font-mono font-bold">
                                        {{ $cartDetails['coupon_code'] }} (-₹{{ number_format($cartDetails['coupon_discount'], 2) }})
                                    </span>
                                    <form method="POST" action="{{ route('cart.coupon.remove') }}">
                                        @csrf
                                        <button type="submit" class="text-xs text-rose-600 hover:underline font-medium">Remove</button>
                                    </form>
                                </div>
                            @else
                                <form method="POST" action="{{ route('cart.coupon.apply') }}" class="flex items-center gap-2">
                                    @csrf
                                    <input type="text"
                                           name="coupon_code"
                                           required
                                           placeholder="Coupon Code"
                                           class="w-40 px-3 py-1.5 text-xs font-mono uppercase bg-slate-50 border border-slate-300 rounded-lg focus:border-amber-600 focus:ring-1 focus:ring-amber-600">
                                    <button type="submit" class="px-3.5 py-1.5 bg-slate-900 hover:bg-slate-800 text-white font-medium rounded-lg text-xs transition">
                                        Apply
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Order Summary (1 col) -->
                <div class="bg-white rounded-3xl border border-[#D97706]/20 p-8 shadow-sm space-y-6">
                    <h2 class="text-xl font-serif font-bold text-slate-900 pb-4 border-b border-slate-100">
                        Order Summary
                    </h2>

                    <div class="space-y-3 text-xs">
                        <div class="flex justify-between text-slate-600">
                            <span>Subtotal (Items)</span>
                            <span class="font-bold text-slate-900 font-mono">₹{{ number_format($cartDetails['subtotal'], 2) }}</span>
                        </div>

                        @if ($cartDetails['member_discount'] > 0)
                            <div class="flex justify-between text-emerald-700 font-bold">
                                <span>Member Savings / Free Quota</span>
                                <span class="font-mono">- ₹{{ number_format($cartDetails['member_discount'], 2) }}</span>
                            </div>
                        @endif

                        @if ($cartDetails['coupon_discount'] > 0)
                            <div class="flex justify-between text-amber-700 font-bold">
                                <span>Coupon ({{ $cartDetails['coupon_code'] }})</span>
                                <span class="font-mono">- ₹{{ number_format($cartDetails['coupon_discount'], 2) }}</span>
                            </div>
                        @endif

                        <div class="flex justify-between text-slate-600">
                            <span>Shipping (Shiprocket Logistics)</span>
                            <span class="font-bold text-slate-900 font-mono">₹{{ number_format($cartDetails['shipping'], 2) }}</span>
                        </div>

                        <div class="pt-4 border-t border-slate-200 flex justify-between items-baseline">
                            <span class="text-base font-bold text-slate-900 font-serif">Total Amount</span>
                            <span class="text-2xl font-extrabold text-[#D97706] font-serif font-mono">
                                ₹{{ number_format($cartDetails['total'], 2) }}
                            </span>
                        </div>
                    </div>

                    <div class="pt-2">
                        <a href="{{ route('checkout.index') }}"
                            class="w-full py-4 rounded-xl text-center block font-bold text-sm text-white bg-[#D97706] hover:bg-[#F97316] shadow-md transition">
                            Proceed to Sacred Checkout
                        </a>
                    </div>

                    <div class="p-3 bg-[#FFFDF7] border border-[#D97706]/20 rounded-xl text-[11px] text-[#D97706] flex items-center justify-center gap-1.5 font-semibold text-center">
                        <svg class="w-3.5 h-3.5 text-[#D97706]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        <span>Traceable Delivery with Consecrated Batch QR</span>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>

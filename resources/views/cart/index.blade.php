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
                <div
                    class="w-16 h-16 rounded-2xl bg-[#D97706]/10 text-[#D97706] flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
                <h3 class="text-xl font-serif font-bold text-slate-900">Your sacred cart is currently empty</h3>
                <p class="text-xs text-slate-500 mt-2">Claim your monthly Brahmakund Gangajal bottle or explore our
                    consecrated offerings.</p>
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
                        <div
                            class="bg-[#0369A1]/10 border border-[#0369A1]/30 rounded-2xl p-4 flex items-center gap-3 text-xs text-[#0369A1] shadow-xs">
                            <span class="text-lg">🎉</span>
                            <div>
                                <strong class="font-bold block sm:inline">Monthly Member Privilege Applied:</strong>
                                <span>Your 1st bottle of sacred Brahmakund Gangajal is <strong>FREE (₹0)</strong>! Flat shipping
                                    fee of ₹{{ number_format($cartDetails['shipping'], 2) }} applies.</span>
                            </div>
                        </div>
                    @endif

                    <div
                        class="bg-white rounded-3xl border border-[#D97706]/20 shadow-xs divide-y divide-slate-100 overflow-hidden">
                        @foreach ($cartDetails['items'] as $item)
                            <div class="p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="w-16 h-16 rounded-xl bg-[#FFFDF7] border border-[#D97706]/20 flex items-center justify-center shrink-0 text-[#D97706] font-bold">
                                        @if ($item['product']->is_gangajal)
                                            🕉️
                                        @else
                                            🪔
                                        @endif
                                    </div>
                                    <div>
                                        <h3 class="font-serif font-bold text-base text-slate-900">
                                            <a href="{{ route('products.show', $item['product']->slug) }}"
                                                class="hover:text-[#D97706]">
                                                {{ $item['product']->name }}
                                            </a>
                                        </h3>
                                        <div class="flex items-center gap-2 mt-1 text-xs text-slate-500">
                                            <span>Unit Price: ₹{{ number_format($item['unit_price'], 2) }}</span>
                                            @if ($item['has_free_bottle'])
                                                <span
                                                    class="px-2 py-0.5 rounded-md font-bold bg-[#0369A1]/10 text-[#0369A1] text-[10px]">
                                                    1st Bottle FREE
                                                </span>
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
                                        <span class="block text-base font-bold text-slate-900 font-serif">
                                            ₹{{ number_format($item['line_total'], 2) }}
                                        </span>
                                        @if ($item['line_discount'] > 0)
                                            <span class="block text-[11px] text-[#0369A1] line-through">
                                                ₹{{ number_format($item['line_subtotal'], 2) }}
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Remove -->
                                    <form method="POST" action="{{ route('cart.remove') }}">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $item['product']->id }}">
                                        <button type="submit" class="text-slate-400 hover:text-[#991B1B] transition"
                                            title="Remove item">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
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
                            <span class="font-bold text-slate-900">₹{{ number_format($cartDetails['subtotal'], 2) }}</span>
                        </div>

                        @if ($cartDetails['discount'] > 0)
                            <div class="flex justify-between text-[#0369A1] font-bold">
                                <span>Member Savings / Free Bottle</span>
                                <span>- ₹{{ number_format($cartDetails['discount'], 2) }}</span>
                            </div>
                        @endif

                        <div class="flex justify-between text-slate-600">
                            <span>Flat Shipping (Shiprocket)</span>
                            <span class="font-bold text-slate-900">₹{{ number_format($cartDetails['shipping'], 2) }}</span>
                        </div>

                        <div class="pt-4 border-t border-slate-200 flex justify-between items-baseline">
                            <span class="text-base font-bold text-slate-900 font-serif">Total Amount</span>
                            <span class="text-2xl font-extrabold text-[#D97706] font-serif">
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

                    <div
                        class="p-3 bg-[#FFFDF7] border border-[#D97706]/20 rounded-xl text-[11px] text-[#D97706] space-y-1 text-center">
                        <span>🕉️ Traceable Delivery with Consecrated Batch QR</span>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
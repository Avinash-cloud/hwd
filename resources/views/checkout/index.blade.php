<x-app-layout>
    <div class="bg-[#FFFDF7] border-b border-[#D97706]/30 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <span class="text-xs font-bold uppercase tracking-widest text-[#D97706] font-mono">Sacred Delivery</span>
            <h1 class="text-3xl font-serif font-extrabold text-slate-900 mt-1">Shipping & Checkout</h1>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <form method="POST" action="{{ route('checkout.store') }}" class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            @csrf

            <!-- Left 2 Cols: Address & Payment Selection -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Shipping Address Card -->
                <div class="bg-white rounded-3xl border border-[#D97706]/20 p-8 shadow-xs space-y-6">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-serif font-bold text-slate-900">1. Shipping Address</h2>
                        <span class="text-xs text-[#D97706] font-bold">India-wide Shiprocket Delivery</span>
                    </div>

                    @if ($addresses->isNotEmpty())
                        <div class="space-y-3">
                            <label class="block text-xs font-bold text-slate-700">Select Saved Address:</label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                @foreach ($addresses as $addr)
                                    <label
                                        class="border border-[#D97706]/30 rounded-2xl p-4 flex items-start gap-3 cursor-pointer hover:bg-[#FFFDF7] transition">
                                        <input type="radio" name="address_id" value="{{ $addr->id }}" {{ $addr->is_default ? 'checked' : '' }} class="mt-1 text-[#D97706] focus:ring-[#D97706]">
                                        <div class="text-xs space-y-0.5">
                                            <strong class="font-bold text-slate-900">{{ $addr->recipient_name }}</strong>
                                            <p class="text-slate-600">{{ $addr->address_line1 }}</p>
                                            <p class="text-slate-600">{{ $addr->city }}, {{ $addr->state }} -
                                                {{ $addr->postal_code }}</p>
                                            <p class="text-slate-500">Phone: {{ $addr->phone }}</p>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                            <input type="hidden" name="use_existing_address" value="1">
                        </div>

                        <div class="pt-2">
                            <details class="text-xs">
                                <summary class="font-bold text-[#D97706] cursor-pointer hover:underline">Or enter a new
                                    delivery address</summary>
                                <div class="mt-4 space-y-4 pt-4 border-t border-slate-100">
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs font-semibold text-slate-700 mb-1">Recipient
                                                Name</label>
                                            <input type="text" name="recipient_name" placeholder="Full Name"
                                                class="w-full text-xs px-3 py-2 border border-slate-300 rounded-xl" />
                                        </div>
                                        <div>
                                            <label class="block text-xs font-semibold text-slate-700 mb-1">Contact
                                                Phone</label>
                                            <input type="text" name="phone" placeholder="+91 9876543210"
                                                class="w-full text-xs px-3 py-2 border border-slate-300 rounded-xl" />
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 mb-1">Address Line
                                            1</label>
                                        <input type="text" name="address_line1"
                                            placeholder="House / Flat No., Building, Street"
                                            class="w-full text-xs px-3 py-2 border border-slate-300 rounded-xl" />
                                    </div>
                                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                        <div>
                                            <label class="block text-xs font-semibold text-slate-700 mb-1">City</label>
                                            <input type="text" name="city" placeholder="City"
                                                class="w-full text-xs px-3 py-2 border border-slate-300 rounded-xl" />
                                        </div>
                                        <div>
                                            <label class="block text-xs font-semibold text-slate-700 mb-1">State</label>
                                            <input type="text" name="state" placeholder="State"
                                                class="w-full text-xs px-3 py-2 border border-slate-300 rounded-xl" />
                                        </div>
                                        <div>
                                            <label class="block text-xs font-semibold text-slate-700 mb-1">PIN Code</label>
                                            <input type="text" name="postal_code" placeholder="Postal Code"
                                                class="w-full text-xs px-3 py-2 border border-slate-300 rounded-xl" />
                                        </div>
                                    </div>
                                </div>
                            </details>
                        </div>
                    @else
                        <!-- Direct New Address Form -->
                        <div class="space-y-4 text-xs">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block font-bold text-slate-700 mb-1">Recipient Name *</label>
                                    <input type="text" name="recipient_name" value="{{ $user->name }}" required
                                        class="w-full px-3 py-2 border border-slate-300 rounded-xl" />
                                </div>
                                <div>
                                    <label class="block font-bold text-slate-700 mb-1">Phone Number *</label>
                                    <input type="text" name="phone" value="{{ $user->phone }}" required
                                        class="w-full px-3 py-2 border border-slate-300 rounded-xl" />
                                </div>
                            </div>
                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Address Line 1 (Flat, House, Building)
                                    *</label>
                                <input type="text" name="address_line1" required
                                    class="w-full px-3 py-2 border border-slate-300 rounded-xl" />
                            </div>
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Landmark (Optional)</label>
                                <input type="text" name="landmark"
                                    class="w-full px-3 py-2 border border-slate-300 rounded-xl" />
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div>
                                    <label class="block font-bold text-slate-700 mb-1">City *</label>
                                    <input type="text" name="city" required
                                        class="w-full px-3 py-2 border border-slate-300 rounded-xl" />
                                </div>
                                <div>
                                    <label class="block font-bold text-slate-700 mb-1">State *</label>
                                    <input type="text" name="state" required
                                        class="w-full px-3 py-2 border border-slate-300 rounded-xl" />
                                </div>
                                <div>
                                    <label class="block font-bold text-slate-700 mb-1">PIN Code *</label>
                                    <input type="text" name="postal_code" required
                                        class="w-full px-3 py-2 border border-slate-300 rounded-xl" />
                                </div>
                            </div>
                            <label class="flex items-center gap-2 pt-2">
                                <input type="checkbox" name="save_address" value="1" checked class="text-[#D97706] rounded">
                                <span class="text-slate-600">Save to address book for future monthly deliveries</span>
                            </label>
                        </div>
                    @endif
                </div>

                <!-- Payment Method Card -->
                <div class="bg-white rounded-3xl border border-[#D97706]/20 p-8 shadow-xs space-y-4">
                    <h2 class="text-xl font-serif font-bold text-slate-900">2. Payment Method</h2>

                    <div class="space-y-3 text-xs">
                        <label
                            class="border-2 border-[#D97706] bg-[#FFFDF7] rounded-2xl p-4 flex items-center justify-between cursor-pointer">
                            <div class="flex items-center gap-3">
                                <input type="radio" name="payment_method" value="razorpay" checked
                                    class="text-[#D97706] focus:ring-[#D97706]">
                                <div>
                                    <strong class="font-bold text-slate-900 block text-sm">Online Gateway (Razorpay /
                                        UPI / Cards)</strong>
                                    <span class="text-slate-500">Instant confirmation & priority Shiprocket
                                        dispatch</span>
                                </div>
                            </div>
                            <span class="text-[#D97706] font-bold">Fast & Secure</span>
                        </label>

                        <label
                            class="border border-slate-200 hover:bg-slate-50 rounded-2xl p-4 flex items-center justify-between cursor-pointer">
                            <div class="flex items-center gap-3">
                                <input type="radio" name="payment_method" value="cod"
                                    class="text-[#D97706] focus:ring-[#D97706]">
                                <div>
                                    <strong class="font-bold text-slate-900 block text-sm">Cash on Delivery
                                        (COD)</strong>
                                    <span class="text-slate-500">Pay when your sacred package arrives</span>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Right Col: Order Summary & Place Order -->
            <div class="bg-white rounded-3xl border border-[#D97706]/20 p-8 shadow-sm space-y-6">
                <h2 class="text-xl font-serif font-bold text-slate-900 pb-4 border-b border-slate-100">
                    Order Summary
                </h2>

                <div class="space-y-3 text-xs">
                    @foreach ($cartDetails['items'] as $item)
                        <div class="flex justify-between py-1">
                            <span class="text-slate-700">{{ $item['product']->name }} (x{{ $item['quantity'] }})</span>
                            <span class="font-bold text-slate-900">₹{{ number_format($item['line_total'], 2) }}</span>
                        </div>
                    @endforeach

                    <div class="pt-3 border-t border-slate-100 space-y-2">
                        <div class="flex justify-between text-slate-600">
                            <span>Subtotal:</span>
                            <span>₹{{ number_format($cartDetails['subtotal'], 2) }}</span>
                        </div>

                        @if ($cartDetails['discount'] > 0)
                            <div class="flex justify-between text-[#0369A1] font-bold">
                                <span>Monthly 1st Bottle Discount:</span>
                                <span>- ₹{{ number_format($cartDetails['discount'], 2) }}</span>
                            </div>
                        @endif

                        <div class="flex justify-between text-slate-600">
                            <span>Shipping (Shiprocket Flat):</span>
                            <span>₹{{ number_format($cartDetails['shipping'], 2) }}</span>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-200 flex justify-between items-baseline">
                        <span class="text-base font-bold text-slate-900 font-serif">Total Payable:</span>
                        <span class="text-2xl font-extrabold text-[#D97706] font-serif">
                            ₹{{ number_format($cartDetails['total'], 2) }}
                        </span>
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit"
                        class="w-full py-4 rounded-xl font-bold text-sm text-white bg-[#D97706] hover:bg-[#F97316] shadow-lg transition">
                        Confirm & Place Sacred Order
                    </button>
                </div>

                <div class="text-[11px] text-slate-400 text-center space-y-1">
                    <p class="inline-flex items-center justify-center gap-1.5 font-semibold text-slate-700">
                        <svg class="w-3.5 h-3.5 text-[#D97706]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        <span>Haridwar Bliss Purity Guarantee</span>
                    </p>
                    <p>Includes scannable batch QR code on the bottle package.</p>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>
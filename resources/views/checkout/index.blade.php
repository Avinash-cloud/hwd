<x-app-layout>
    <div class="bg-[#FFFDF7] border-b border-[#D97706]/30 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <span class="text-xs font-bold uppercase tracking-widest text-[#D97706] font-mono">Sacred Delivery</span>
            <h1 class="text-3xl font-serif font-extrabold text-slate-900 mt-1">Shipping & Checkout</h1>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        @if (!$user)
            <div class="mb-8 p-4 bg-amber-50/80 border border-amber-200 rounded-2xl flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs shadow-xs">
                <div class="flex items-center gap-3">
                    <span class="w-8 h-8 rounded-xl bg-amber-100 text-[#D97706] flex items-center justify-center font-bold text-sm shrink-0">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                    </span>
                    <div>
                        <strong class="font-bold text-slate-900 block sm:inline">Guest Checkout Active:</strong>
                        <span class="text-slate-600">You can complete your sacred offering purchase without creating an account.</span>
                    </div>
                </div>
                <a href="{{ route('login') }}" class="font-bold text-[#D97706] hover:underline self-start sm:self-auto shrink-0 flex items-center gap-1">
                    <span>Already a member? Sign In</span>
                    <span>→</span>
                </a>
            </div>
        @endif

        <form id="checkout-form" method="POST" action="{{ route('checkout.store') }}" class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            @csrf
            <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
            <input type="hidden" name="razorpay_order_id" id="razorpay_order_id">
            <input type="hidden" name="razorpay_signature" id="razorpay_signature">

            <!-- Left 2 Cols: Address & Payment Selection -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Shipping Address Card -->
                <div class="bg-white rounded-3xl border border-[#D97706]/20 p-8 shadow-xs space-y-6">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-serif font-bold text-slate-900">1. Customer & Shipping Details</h2>
                        <span class="text-xs text-[#D97706] font-bold">India-wide Insured Logistics</span>
                    </div>

                    @if ($addresses->isNotEmpty())
                        <div class="space-y-3">
                            <label class="block text-xs font-bold text-slate-700">Select Saved Delivery Address:</label>
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
                                <summary class="font-bold text-[#D97706] cursor-pointer hover:underline">Or enter a new delivery address</summary>
                                <div class="mt-4 space-y-4 pt-4 border-t border-slate-100">
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs font-semibold text-slate-700 mb-1">Recipient Name *</label>
                                            <input type="text" name="recipient_name" placeholder="Full Name"
                                                class="w-full text-xs px-3 py-2 border border-slate-300 rounded-xl" />
                                        </div>
                                        <div>
                                            <label class="block text-xs font-semibold text-slate-700 mb-1">Contact Phone *</label>
                                            <input type="text" name="phone" placeholder="+91 9876543210"
                                                class="w-full text-xs px-3 py-2 border border-slate-300 rounded-xl" />
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 mb-1">Address Line 1 *</label>
                                        <input type="text" name="address_line1"
                                            placeholder="House / Flat No., Building, Street"
                                            class="w-full text-xs px-3 py-2 border border-slate-300 rounded-xl" />
                                    </div>
                                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                        <div>
                                            <label class="block text-xs font-semibold text-slate-700 mb-1">City *</label>
                                            <input type="text" name="city" placeholder="City"
                                                class="w-full text-xs px-3 py-2 border border-slate-300 rounded-xl" />
                                        </div>
                                        <div>
                                            <label class="block text-xs font-semibold text-slate-700 mb-1">State *</label>
                                            <input type="text" name="state" placeholder="State"
                                                class="w-full text-xs px-3 py-2 border border-slate-300 rounded-xl" />
                                        </div>
                                        <div>
                                            <label class="block text-xs font-semibold text-slate-700 mb-1">PIN Code *</label>
                                            <input type="text" name="postal_code" placeholder="PIN Code"
                                                class="w-full text-xs px-3 py-2 border border-slate-300 rounded-xl" />
                                        </div>
                                    </div>
                                </div>
                            </details>
                        </div>
                    @else
                        <!-- Direct Address & Contact Form -->
                        <div class="space-y-4 text-xs">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block font-bold text-slate-700 mb-1">Recipient Name *</label>
                                    <input type="text"
                                           name="recipient_name"
                                           value="{{ old('recipient_name', $user?->name) }}"
                                           required
                                           placeholder="Full Name"
                                           class="w-full px-3 py-2 border border-slate-300 rounded-xl text-xs focus:border-[#D97706] focus:ring-1 focus:ring-[#D97706]" />
                                    @error('recipient_name')
                                        <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block font-bold text-slate-700 mb-1">Contact Phone Number *</label>
                                    <input type="text"
                                           name="phone"
                                           value="{{ old('phone', $user?->phone) }}"
                                           required
                                           placeholder="+91 9876543210"
                                           class="w-full px-3 py-2 border border-slate-300 rounded-xl text-xs focus:border-[#D97706] focus:ring-1 focus:ring-[#D97706]" />
                                    @error('phone')
                                        <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            @if (!$user)
                                <div>
                                    <label class="block font-bold text-slate-700 mb-1">Email Address *</label>
                                    <input type="email"
                                           name="email"
                                           value="{{ old('email') }}"
                                           required
                                           placeholder="devotee@example.com"
                                           class="w-full px-3 py-2 border border-slate-300 rounded-xl text-xs focus:border-[#D97706] focus:ring-1 focus:ring-[#D97706]" />
                                    <p class="text-[11px] text-slate-500 mt-1">We will send your order confirmation, batch verification link, and tax invoice to this email.</p>
                                    @error('email')
                                        <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            @endif

                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Delivery Address Line 1 (Flat, House, Building, Street) *</label>
                                <input type="text"
                                       name="address_line1"
                                       value="{{ old('address_line1') }}"
                                       required
                                       placeholder="e.g. 104, Shanti Kunj, Near Ram Mandir"
                                       class="w-full px-3 py-2 border border-slate-300 rounded-xl text-xs focus:border-[#D97706] focus:ring-1 focus:ring-[#D97706]" />
                                @error('address_line1')
                                    <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block font-semibold text-slate-700 mb-1">Address Line 2 (Area / Locality)</label>
                                    <input type="text"
                                           name="address_line2"
                                           value="{{ old('address_line2') }}"
                                           placeholder="Optional area details"
                                           class="w-full px-3 py-2 border border-slate-300 rounded-xl text-xs focus:border-[#D97706] focus:ring-1 focus:ring-[#D97706]" />
                                </div>
                                <div>
                                    <label class="block font-semibold text-slate-700 mb-1">Landmark (Optional)</label>
                                    <input type="text"
                                           name="landmark"
                                           value="{{ old('landmark') }}"
                                           placeholder="e.g. Opposite Shiv Temple"
                                           class="w-full px-3 py-2 border border-slate-300 rounded-xl text-xs focus:border-[#D97706] focus:ring-1 focus:ring-[#D97706]" />
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div>
                                    <label class="block font-bold text-slate-700 mb-1">City *</label>
                                    <input type="text"
                                           name="city"
                                           value="{{ old('city') }}"
                                           required
                                           placeholder="City"
                                           class="w-full px-3 py-2 border border-slate-300 rounded-xl text-xs focus:border-[#D97706] focus:ring-1 focus:ring-[#D97706]" />
                                    @error('city')
                                        <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block font-bold text-slate-700 mb-1">State *</label>
                                    <input type="text"
                                           name="state"
                                           value="{{ old('state') }}"
                                           required
                                           placeholder="State"
                                           class="w-full px-3 py-2 border border-slate-300 rounded-xl text-xs focus:border-[#D97706] focus:ring-1 focus:ring-[#D97706]" />
                                    @error('state')
                                        <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block font-bold text-slate-700 mb-1">PIN Code *</label>
                                    <input type="text"
                                           name="postal_code"
                                           value="{{ old('postal_code') }}"
                                           required
                                           placeholder="6-digit PIN"
                                           class="w-full px-3 py-2 border border-slate-300 rounded-xl text-xs focus:border-[#D97706] focus:ring-1 focus:ring-[#D97706]" />
                                    @error('postal_code')
                                        <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            @if (!$user)
                                <!-- Option to Create Account in Future / Right at Checkout -->
                                <div x-data="{ createAccount: {{ old('create_account') ? 'true' : 'false' }} }"
                                     class="mt-4 p-4 bg-amber-50/70 border border-amber-200/80 rounded-2xl space-y-3">
                                    <label class="flex items-start gap-3 cursor-pointer">
                                        <input type="checkbox"
                                               name="create_account"
                                               value="1"
                                               x-model="createAccount"
                                               class="mt-1 text-[#D97706] focus:ring-[#D97706] rounded border-slate-300">
                                        <div>
                                            <strong class="font-bold text-slate-900 block text-xs">Create an account for faster checkout & live tracking</strong>
                                            <p class="text-[11px] text-slate-500 mt-0.5">
                                                Easily view tracking updates, save addresses, and earn devotee member rewards on future purchases.
                                            </p>
                                        </div>
                                    </label>

                                    <div x-show="createAccount" x-cloak class="pt-3 border-t border-amber-200/60 grid grid-cols-1 sm:grid-cols-2 gap-3">
                                        <div>
                                            <label class="block font-bold text-slate-700 mb-1">Create Password *</label>
                                            <input type="password"
                                                   name="password"
                                                   placeholder="Minimum 8 characters"
                                                   :required="createAccount"
                                                   class="w-full px-3 py-2 border border-slate-300 rounded-xl text-xs bg-white focus:border-[#D97706] focus:ring-1 focus:ring-[#D97706]">
                                            @error('password')
                                                <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div>
                                            <label class="block font-bold text-slate-700 mb-1">Confirm Password *</label>
                                            <input type="password"
                                                   name="password_confirmation"
                                                   placeholder="Repeat password"
                                                   :required="createAccount"
                                                   class="w-full px-3 py-2 border border-slate-300 rounded-xl text-xs bg-white focus:border-[#D97706] focus:ring-1 focus:ring-[#D97706]">
                                        </div>
                                    </div>
                                </div>
                            @else
                                <label class="flex items-center gap-2 pt-2">
                                    <input type="checkbox" name="save_address" value="1" checked class="text-[#D97706] rounded">
                                    <span class="text-slate-600">Save to address book for future deliveries</span>
                                </label>
                            @endif
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
                                    <strong class="font-bold text-slate-900 block text-sm">Online Gateway (Razorpay / UPI / Cards / NetBanking)</strong>
                                    <span class="text-slate-500">Instant confirmation & priority Shiprocket dispatch</span>
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
                                    <strong class="font-bold text-slate-900 block text-sm">Cash on Delivery (COD)</strong>
                                    <span class="text-slate-500">Pay when your sacred package arrives at your doorstep</span>
                                </div>
                            </div>
                            <span class="text-slate-400 font-medium">Available</span>
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
                        <div class="flex justify-between items-start py-1.5 border-b border-slate-50 last:border-0">
                            <div class="truncate max-w-[200px]">
                                <span class="font-bold text-slate-800">{{ $item['product']->name }}</span>
                                @if (!empty($item['variant_name']))
                                    <span class="block text-[10px] text-[#D97706] font-bold">Size: {{ $item['variant_name'] }}</span>
                                @endif
                                <span class="text-[10px] text-slate-400">Qty: {{ $item['quantity'] }} · HSN: {{ $item['hsn_code'] ?? '22019010' }}</span>
                            </div>
                            <span class="font-bold text-slate-900 font-mono text-right">₹{{ number_format($item['line_total'], 2) }}</span>
                        </div>
                    @endforeach

                    <div class="pt-3 border-t border-slate-100 space-y-2">
                        <div class="flex justify-between text-slate-600">
                            <span>Subtotal (Inclusive of GST):</span>
                            <span class="font-mono">₹{{ number_format($cartDetails['subtotal'], 2) }}</span>
                        </div>

                        <div class="pl-2.5 py-1.5 border-l-2 border-amber-300 bg-amber-50/40 rounded-r-lg space-y-1 text-[11px] text-slate-600">
                            <div class="flex justify-between">
                                <span>Taxable Base Value</span>
                                <span class="font-mono font-medium">₹{{ number_format($cartDetails['total_taxable_amount'] ?? 0, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Total GST Breakdown</span>
                                <span class="font-mono font-medium text-slate-800">₹{{ number_format($cartDetails['total_gst_amount'] ?? 0, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-[10px] text-slate-400 font-mono">
                                <span>(CGST: ₹{{ number_format(($cartDetails['total_gst_amount'] ?? 0) / 2, 2) }} + SGST: ₹{{ number_format(($cartDetails['total_gst_amount'] ?? 0) / 2, 2) }})</span>
                            </div>
                        </div>

                        @if ($cartDetails['member_discount'] > 0)
                            <div class="flex justify-between text-emerald-700 font-bold">
                                <span>Member Savings / Free Offering:</span>
                                <span class="font-mono">- ₹{{ number_format($cartDetails['member_discount'], 2) }}</span>
                            </div>
                        @endif

                        @if ($cartDetails['coupon_discount'] > 0)
                            <div class="flex justify-between text-amber-700 font-bold">
                                <span>Coupon ({{ $cartDetails['coupon_code'] }}):</span>
                                <span class="font-mono">- ₹{{ number_format($cartDetails['coupon_discount'], 2) }}</span>
                            </div>
                        @endif

                        <div class="flex justify-between text-slate-600">
                            <span class="flex items-center gap-1.5">
                                <span>Shipping (Shiprocket Logistics):</span>
                                @if (!empty($cartDetails['is_member_shipping']))
                                    <span class="text-[10px] font-bold uppercase tracking-wider px-1.5 py-0.5 bg-emerald-100 text-emerald-800 rounded">Member Rate</span>
                                @endif
                            </span>
                            <span class="font-mono">
                                @if ($cartDetails['shipping'] <= 0 && $cartDetails['subtotal'] > 0)
                                    <span class="text-emerald-700 font-bold">FREE</span>
                                @else
                                    ₹{{ number_format($cartDetails['shipping'], 2) }}
                                @endif
                            </span>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-200 flex justify-between items-baseline">
                        <span class="text-base font-bold text-slate-900 font-serif">Total Payable:</span>
                        <span class="text-2xl font-extrabold text-[#D97706] font-serif font-mono">
                            ₹{{ number_format($cartDetails['total'], 2) }}
                        </span>
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit"
                        id="place-order-btn"
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
                    <p>Includes scannable batch QR code on package for complete Vedic origin verification.</p>
                </div>
            </div>
        </form>
    </div>

    <!-- Razorpay Checkout Integration Script -->
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const checkoutForm = document.getElementById('checkout-form');
        const submitBtn = document.getElementById('place-order-btn');
        if (!checkoutForm || !submitBtn) return;

        let razorpayHandled = false;

        checkoutForm.addEventListener('submit', function (e) {
            const paymentMethod = checkoutForm.querySelector('input[name="payment_method"]:checked')?.value;

            if (paymentMethod === 'cod' || razorpayHandled) {
                return;
            }

            e.preventDefault();

            if (!checkoutForm.checkValidity()) {
                checkoutForm.reportValidity();
                return;
            }

            submitBtn.disabled = true;
            submitBtn.innerText = 'Initializing Razorpay Payment...';

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            fetch('{{ route('checkout.razorpay.order') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => {
                if (!res.ok) throw new Error('Failed to create Razorpay order');
                return res.json();
            })
            .then(data => {
                if (data.is_mock) {
                    document.getElementById('razorpay_payment_id').value = 'pay_mock_' + Math.random().toString(36).substring(2, 12);
                    document.getElementById('razorpay_order_id').value = data.order_id;
                    document.getElementById('razorpay_signature').value = 'sig_mock_' + Math.random().toString(36).substring(2, 12);
                    razorpayHandled = true;
                    checkoutForm.submit();
                    return;
                }

                const options = {
                    key: data.key,
                    amount: data.amount,
                    currency: data.currency,
                    name: data.name,
                    description: data.description,
                    order_id: data.order_id,
                    prefill: data.prefill,
                    theme: { color: '#D97706' },
                    handler: function (response) {
                        document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
                        document.getElementById('razorpay_order_id').value = response.razorpay_order_id;
                        document.getElementById('razorpay_signature').value = response.razorpay_signature;
                        razorpayHandled = true;
                        checkoutForm.submit();
                    },
                    modal: {
                        ondismiss: function () {
                            submitBtn.disabled = false;
                            submitBtn.innerText = 'Confirm & Place Sacred Order';
                        }
                    }
                };

                const rzp = new Razorpay(options);
                rzp.on('payment.failed', function (response) {
                    alert('Payment failed: ' + (response.error.description || 'Transaction unsuccessful'));
                    submitBtn.disabled = false;
                    submitBtn.innerText = 'Confirm & Place Sacred Order';
                });
                rzp.open();
            })
            .catch(err => {
                alert('Unable to initialize Razorpay payment. Please try again or select Cash on Delivery.');
                submitBtn.disabled = false;
                submitBtn.innerText = 'Confirm & Place Sacred Order';
            });
        });
    });
    </script>
</x-app-layout>

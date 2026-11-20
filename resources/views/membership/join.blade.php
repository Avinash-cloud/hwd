<x-app-layout>
    <div class="relative bg-gradient-to-b from-[#4A0E17] via-[#5C1521] to-[#380B12] text-white overflow-hidden py-14 sm:py-20 border-b border-amber-400/30">
        <!-- <img src="{{ asset('images/haridwar-panorama-clocktower.jpg') }}"
             alt="Haridwar Ganga Temples"
             class="absolute inset-0 w-full h-full object-cover opacity-25 object-center"> -->
        <div class="absolute inset-0 bg-gradient-to-t from-[#25050A] via-[#380B12]/80 to-transparent"></div>

        <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-[#D97706]/30 text-amber-300 border border-[#D97706]/50 backdrop-blur-xs mb-3">
                <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                <span>🌟 Sacred 5-Year Devotee Circle · Household Privilege</span>
            </span>
            <h1 class="text-3xl sm:text-5xl font-serif font-extrabold text-white">
                Blu Bliss 5-Year Sacred Devotee Membership
            </h1>
            <p class="mt-4 text-sm sm:text-base text-amber-100/90 max-w-2xl mx-auto leading-relaxed">
                Connect your household directly to the divine blessings of Maa Ganga in sacred Haridwar with uninterrupted
                monthly sacred deliveries for 5 full years (60 months total).
            </p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12"
         x-data="{
             termsAccepted: false,
             termsModalOpen: false,
             paymentMethod: 'razorpay',
             isProcessing: false,
             paymentId: '',
             orderId: '',
             subscriptionId: '',
             signature: '',
             shippingPhone: '{{ old('shipping_phone', $defaultAddress?->phone ?? $user?->phone ?? '') }}',
             shippingAddress: '{{ old('shipping_address', $defaultAddress?->address_line1 ?? '') }}',
             shippingCity: '{{ old('shipping_city', $defaultAddress?->city ?? '') }}',
             shippingState: '{{ old('shipping_state', $defaultAddress?->state ?? '') }}',
             shippingPincode: '{{ old('shipping_pincode', $defaultAddress?->postal_code ?? '') }}',

             initiatePayment() {
                 if (!this.termsAccepted) {
                     alert('Please accept the Membership Terms & Conditions to proceed.');
                     return;
                 }

                 if (!this.shippingPhone.trim() || !this.shippingAddress.trim() || !this.shippingCity.trim() || !this.shippingState.trim() || !this.shippingPincode.trim()) {
                     alert('Please complete your monthly doorstep delivery address details below.');
                     return;
                 }

                 if (this.paymentMethod === 'mock') {
                     this.paymentId = 'pay_HB_MEM_' + Math.random().toString(36).substring(2, 10).toUpperCase();
                     this.subscriptionId = 'sub_mock_' + Math.random().toString(36).substring(2, 14);
                     this.signature = 'mock_signature';
                     this.$nextTick(() => {
                         document.getElementById('membership-form').submit();
                     });
                     return;
                 }

                 this.isProcessing = true;

                 // Create Razorpay subscription mandate & order
                 fetch('{{ route('membership.razorpay.order') }}', {
                     method: 'POST',
                     headers: {
                         'Content-Type': 'application/json',
                         'X-CSRF-TOKEN': '{{ csrf_token() }}'
                     }
                 })
                 .then(res => res.json())
                 .then(data => {
                     if (data.is_mock || typeof Razorpay === 'undefined') {
                         this.paymentId = 'pay_HB_MEM_' + Math.random().toString(36).substring(2, 10).toUpperCase();
                         this.subscriptionId = data.subscription_id || ('sub_mock_' + Math.random().toString(36).substring(2, 14));
                         this.orderId = data.order_id || 'order_mock';
                         this.signature = 'mock_signature';
                         this.$nextTick(() => {
                             document.getElementById('membership-form').submit();
                         });
                         return;
                     }

                     const options = {
                         key: data.key,
                         name: data.name,
                         description: data.description,
                         prefill: {
                             name: data.prefill.name,
                             email: data.prefill.email,
                             contact: this.shippingPhone || data.prefill.contact
                         },
                         theme: { color: '#D97706' },
                         handler: (response) => {
                             this.paymentId = response.razorpay_payment_id;
                             this.subscriptionId = response.razorpay_subscription_id || data.subscription_id;
                             this.orderId = response.razorpay_order_id || '';
                             this.signature = response.razorpay_signature;
                             this.$nextTick(() => {
                                 document.getElementById('membership-form').submit();
                             });
                         },
                         modal: {
                             ondismiss: () => {
                                 this.isProcessing = false;
                             }
                         }
                     };

                     if (data.subscription_id) {
                         options.subscription_id = data.subscription_id;
                     } else if (data.order_id) {
                         options.order_id = data.order_id;
                     }

                     const rzp = new Razorpay(options);
                     rzp.open();
                 })
                 .catch(err => {
                     console.error('Payment error:', err);
                     this.isProcessing = false;
                     // Fallback submission
                     this.subscriptionId = 'sub_fallback_' + Math.random().toString(36).substring(2, 12);
                     this.paymentId = 'pay_fallback_' + Math.random().toString(36).substring(2, 12);
                     this.signature = 'fallback_signature';
                     this.$nextTick(() => {
                         document.getElementById('membership-form').submit();
                     });
                 });
             }
         }">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left 2 Cols: Privileges & How it works -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Privileges Card -->
                <div class="bg-white rounded-3xl border border-[#D97706]/20 p-8 shadow-xs space-y-6">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-[#D97706] font-mono">Sacred Devotee Privilege</span>
                        <h2 class="text-2xl font-serif font-bold text-[#881337] mt-1">Benefits of Blu Bliss 5-Year Sacred Membership</h2>
                        <p class="text-xs text-slate-500 mt-1">
                            An auspicious 60-month commitment ensuring sacred purity, unbroken supply, and temple-grade sanctity for your home altar.
                        </p>
                    </div>

                    <div class="space-y-4">
                        <!-- 1. Uninterrupted Monthly Supply -->
                        <div class="flex items-start gap-4 p-4 rounded-2xl bg-amber-50/40 border border-amber-200/50 hover:bg-amber-50/80 transition">
                            <div class="w-10 h-10 rounded-xl bg-[#881337] text-white flex items-center justify-center shrink-0 font-bold shadow-xs">
                                1
                            </div>
                            <div>
                                <h3 class="font-bold text-[#881337] text-sm sm:text-base">Uninterrupted Monthly Supply</h3>
                                <p class="text-xs text-[#3B2314]/85 leading-relaxed mt-1">
                                    Receive a fresh 500ml bottle of pure Haridwar Gangajal delivered directly to your doorstep every single month for 5 years (60 months total)—ensuring your home altar never runs out of sacred water at the monthly charge of ₹{{ number_format($monthlyAutoDebit ?? $shippingRate) }}/month (Product: ₹{{ number_format($monthlyProductPrice ?? 0) }} + Delivery: ₹{{ number_format($monthlyDeliveryCharge ?? $shippingRate) }}, including GST).
                                </p>
                            </div>
                        </div>

                        <!-- 2. Brahma Muhurta Purity Guaranteed -->
                        <div class="flex items-start gap-4 p-4 rounded-2xl bg-amber-50/40 border border-amber-200/50 hover:bg-amber-50/80 transition">
                            <div class="w-10 h-10 rounded-xl bg-[#881337] text-white flex items-center justify-center shrink-0 font-bold shadow-xs">
                                2
                            </div>
                            <div>
                                <h3 class="font-bold text-[#881337] text-sm sm:text-base">Brahma Muhurta Purity Guaranteed</h3>
                                <p class="text-xs text-[#3B2314]/85 leading-relaxed mt-1">
                                    Every drop is sacredly collected during the auspicious hours of Brahma Muhurta in Haridwar and sealed hygienically with zero chemical interference under continuous Vedic recitation.
                                </p>
                            </div>
                        </div>

                        <!-- 3. 5-Year Price Lock & Convenience -->
                        <div class="flex items-start gap-4 p-4 rounded-2xl bg-amber-50/40 border border-amber-200/50 hover:bg-amber-50/80 transition">
                            <div class="w-10 h-10 rounded-xl bg-[#881337] text-white flex items-center justify-center shrink-0 font-bold shadow-xs">
                                3
                            </div>
                            <div>
                                <h3 class="font-bold text-[#881337] text-sm sm:text-base">5-Year Commitment & Price Lock</h3>
                                <p class="text-xs text-[#3B2314]/85 leading-relaxed mt-1">
                                    A one-time commitment fee of <strong>₹{{ number_format($membershipFee) }}</strong> secures your membership for 60 months. The monthly auto-debit charge of <strong>₹{{ number_format($monthlyAutoDebit ?? $shippingRate) }}/month</strong> (Product: ₹{{ number_format($monthlyProductPrice ?? 0) }} + Delivery: ₹{{ number_format($monthlyDeliveryCharge ?? $shippingRate) }}) per bottle is payable by the 5th of every month.
                                    <span class="block text-[11px] text-amber-900/80 font-medium mt-1">
                                        *(Note: Service rate of ₹{{ number_format($monthlyAutoDebit ?? $shippingRate) }} ensured for the agreement period. In case of any future price revision, 30 days prior written notice will be provided.)*
                                    </span>
                                </p>
                            </div>
                        </div>

                        <!-- 4. Special Member Privileges on Sacred Kits -->
                        <div class="flex items-start gap-4 p-4 rounded-2xl bg-amber-50/40 border border-amber-200/50 hover:bg-amber-50/80 transition">
                            <div class="w-10 h-10 rounded-xl bg-[#881337] text-white flex items-center justify-center shrink-0 font-bold shadow-xs">
                                4
                            </div>
                            <div>
                                <h3 class="font-bold text-[#881337] text-sm sm:text-base">Special Member Privileges on Sacred Kits</h3>
                                <p class="text-xs text-[#3B2314]/85 leading-relaxed mt-1">
                                    Receive exclusive member benefits and early access to consecrated Vedic Puja Essentials, festive kits, handcrafted brass diyas, and Haridwar Ghat Prasadam with special devotee discount pricing.
                                </p>
                            </div>
                        </div>

                        <!-- 5. Contribute to Maa Ganga Cleanliness -->
                        <div class="flex items-start gap-4 p-4 rounded-2xl bg-emerald-50/40 border border-emerald-200/60 hover:bg-emerald-50/80 transition">
                            <div class="w-10 h-10 rounded-xl bg-emerald-700 text-white flex items-center justify-center shrink-0 font-bold shadow-xs">
                                5
                            </div>
                            <div>
                                <h3 class="font-bold text-emerald-900 text-sm sm:text-base flex items-center gap-1.5">
                                    <span>Contribute to Maa Ganga Cleanliness</span>
                                    <span class="text-[10px] bg-emerald-100 text-emerald-800 font-bold px-2 py-0.5 rounded-full uppercase">Eco-Clean</span>
                                </h3>
                                <p class="text-xs text-[#3B2314]/85 leading-relaxed mt-1">
                                    Every monthly delivery directly supports our <strong>Eco-Clean Ganga Pledge</strong>, contributing toward river waste removal, ghat maintenance, and environmental conservation in Haridwar.
                                </p>
                            </div>
                        </div>

                        <!-- 6. Tamper-Proof & Safe Doorstep Delivery -->
                        <div class="flex items-start gap-4 p-4 rounded-2xl bg-amber-50/40 border border-amber-200/50 hover:bg-amber-50/80 transition">
                            <div class="w-10 h-10 rounded-xl bg-[#881337] text-white flex items-center justify-center shrink-0 font-bold shadow-xs">
                                6
                            </div>
                            <div>
                                <h3 class="font-bold text-[#881337] text-sm sm:text-base">Tamper-Proof & Safe Doorstep Delivery</h3>
                                <p class="text-xs text-[#3B2314]/85 leading-relaxed mt-1">
                                    Your sacred Gangajal is packed in leak-proof, food-grade protective packaging and shipped reliably with unbroken batch QR code tracking from Haridwar straight to your sacred altar anywhere in India.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Terms & Transparency -->
                <div class="bg-[#FFFDF7] rounded-3xl border border-[#D97706]/20 p-6 space-y-3 text-xs text-[#3B2314]/85">
                    <h4 class="font-serif font-bold text-[#881337] text-sm">Agreement Summary & Devotee Assurance</h4>
                    <p>
                        • <strong>Agreement Period:</strong> 60 Months (5 Continuous Years) of monthly 500ml pure Haridwar Gangajal delivered to your address.
                    </p>
                    <p>
                        • <strong>Financial Terms:</strong> One-time commitment fee of ₹{{ number_format($membershipFee) }} (non-refundable) + monthly delivery charge of ₹{{ number_format($shippingRate) }}/month (including GST) payable by the 5th of every month.
                    </p>
                    <p>
                        • <strong>Sanctity Assurance:</strong> Sacred water collected during Brahma Muhurta morning Aarti under continuous Vedic recitation. Untouched by human hands and hermetically sealed.
                    </p>
                    <p>
                        • <strong>Tax Invoicing:</strong> A formal GST invoice (SAC 9995 / HSN 22019010) is generated upon confirmation and downloadable from your member portal.
                    </p>
                    <p>
                        • <strong>Eco-Clean Ganga Pledge:</strong> Your monthly deliveries directly aid our local Haridwar river waste removal and ghat conservation initiatives.
                    </p>
                </div>
            </div>

            <!-- Right Col: Activation Form / Summary -->
            <div class="space-y-6">
                <div class="bg-white border-2 border-[#D97706]/40 rounded-3xl p-8 shadow-lg shadow-amber-950/5 relative overflow-hidden">
                    <div class="bg-[#881337] text-white text-center py-2 px-4 -mx-8 -mt-8 mb-6 font-bold text-xs uppercase tracking-widest">
                        Auspicious Devotee Offering
                    </div>

                    <div class="text-center pb-6 border-b border-amber-100">
                        <span class="text-xs font-bold uppercase tracking-wider text-[#D97706]">5-Year Devotee Membership</span>
                        <div class="mt-2 flex items-baseline justify-center gap-1">
                            <span class="text-4xl font-extrabold font-serif text-[#881337]">₹{{ number_format($membershipFee) }}</span>
                            <span class="text-xs font-bold text-stone-600">Registration</span>
                        </div>
                        <div class="mt-2 flex items-center justify-center gap-1.5 text-xs text-amber-900 font-medium">
                            <span>+ ₹{{ number_format($monthlyAutoDebit ?? $shippingRate) }}/month</span>
                            <span class="text-emerald-700 font-bold bg-emerald-100/80 px-2 py-0.5 rounded-full text-[10px]">Auto-Debit Mandate</span>
                        </div>
                        <div class="text-[11px] text-slate-500 mt-1 font-mono">
                            Product: ₹{{ number_format($monthlyProductPrice ?? 0) }} + Delivery: ₹{{ number_format($monthlyDeliveryCharge ?? $shippingRate) }}
                        </div>
                        <span class="inline-block mt-2 text-[11px] font-semibold text-[#0369A1] bg-[#0369A1]/10 border border-[#0369A1]/30 px-3 py-0.5 rounded-full">
                            5 Years (60 Months) Uninterrupted Doorstep Delivery • Auto-Renews
                        </span>
                    </div>

                    @auth
                        @if (Auth::user()->hasActiveMembership())
                            <div class="py-6 text-center space-y-3">
                                <div class="w-12 h-12 rounded-full bg-[#0369A1]/10 text-[#0369A1] flex items-center justify-center mx-auto">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <h4 class="font-serif font-bold text-base text-[#881337]">You Are Already a Sacred Member!</h4>
                                <p class="text-xs text-slate-500">
                                    Your 5-Year Membership is currently active. You can claim your monthly Gangajal bottle right now.
                                </p>
                                <div class="space-y-2 pt-2">
                                    <a href="{{ route('dashboard') }}" class="w-full inline-block py-3 bg-[#D97706] hover:bg-[#F97316] text-white font-bold text-xs rounded-xl shadow">
                                        Go to Member Dashboard
                                    </a>
                                    @if (Auth::user()->activeMembership)
                                        <a href="{{ route('membership.invoice', Auth::user()->activeMembership->id) }}" target="_blank" class="w-full inline-block py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold text-xs rounded-xl transition">
                                            📄 Download Membership Tax Invoice
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @else
                            <form id="membership-form" method="POST" action="{{ route('membership.subscribe') }}" class="py-6 space-y-4">
                                @csrf
                                <input type="hidden" name="payment_method" :value="paymentMethod">
                                <input type="hidden" name="razorpay_payment_id" :value="paymentId">
                                <input type="hidden" name="razorpay_subscription_id" :value="subscriptionId">
                                <input type="hidden" name="razorpay_order_id" :value="orderId">
                                <input type="hidden" name="razorpay_signature" :value="signature">

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Subscriber Name</label>
                                    <input type="text" value="{{ Auth::user()->name }}" readonly
                                        class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl text-slate-500" />
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Registered Email</label>
                                    <input type="email" value="{{ Auth::user()->email }}" readonly
                                        class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl text-slate-500" />
                                </div>

                                <!-- Doorstep Delivery Address -->
                                <div class="p-3.5 bg-amber-50/60 rounded-2xl border border-amber-200/80 space-y-3">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-bold text-[#881337] flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5 text-[#D97706]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            <span>Doorstep Delivery Address</span>
                                        </span>
                                        <span class="text-[10px] bg-amber-100 text-amber-800 font-bold px-2 py-0.5 rounded-full">Monthly Dispatch</span>
                                    </div>
                                    <p class="text-[11px] text-slate-600 leading-normal">
                                        Address where your monthly 500ml sacred Gangajal bottles will be safely delivered:
                                    </p>

                                    <div>
                                        <label class="block text-[11px] font-bold text-slate-700 mb-0.5">Contact Phone Number *</label>
                                        <input type="tel" name="shipping_phone" x-model="shippingPhone" required placeholder="e.g. 9876543210"
                                               class="w-full px-3 py-2 text-xs bg-white border border-slate-200 rounded-xl focus:border-[#D97706] focus:ring-1 focus:ring-[#D97706]">
                                        @error('shipping_phone')
                                            <p class="text-red-600 text-[10px] mt-0.5">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block text-[11px] font-bold text-slate-700 mb-0.5">Street Address / House No / Area *</label>
                                        <textarea name="shipping_address" x-model="shippingAddress" rows="2" required placeholder="Flat / House No, Building, Street, Area"
                                                  class="w-full px-3 py-2 text-xs bg-white border border-slate-200 rounded-xl focus:border-[#D97706] focus:ring-1 focus:ring-[#D97706]"></textarea>
                                        @error('shipping_address')
                                            <p class="text-red-600 text-[10px] mt-0.5">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="grid grid-cols-2 gap-2">
                                        <div>
                                            <label class="block text-[11px] font-bold text-slate-700 mb-0.5">City *</label>
                                            <input type="text" name="shipping_city" x-model="shippingCity" required placeholder="e.g. New Delhi"
                                                   class="w-full px-3 py-2 text-xs bg-white border border-slate-200 rounded-xl focus:border-[#D97706] focus:ring-1 focus:ring-[#D97706]">
                                            @error('shipping_city')
                                                <p class="text-red-600 text-[10px] mt-0.5">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div>
                                            <label class="block text-[11px] font-bold text-slate-700 mb-0.5">PIN Code *</label>
                                            <input type="text" name="shipping_pincode" x-model="shippingPincode" required placeholder="e.g. 110001"
                                                   class="w-full px-3 py-2 text-xs bg-white border border-slate-200 rounded-xl focus:border-[#D97706] focus:ring-1 focus:ring-[#D97706]">
                                            @error('shipping_pincode')
                                                <p class="text-red-600 text-[10px] mt-0.5">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-[11px] font-bold text-slate-700 mb-0.5">State *</label>
                                        <input type="text" name="shipping_state" x-model="shippingState" required placeholder="e.g. Delhi"
                                               class="w-full px-3 py-2 text-xs bg-white border border-slate-200 rounded-xl focus:border-[#D97706] focus:ring-1 focus:ring-[#D97706]">
                                        @error('shipping_state')
                                            <p class="text-red-600 text-[10px] mt-0.5">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Select Payment Gateway</label>
                                    <div class="space-y-2 text-xs">
                                        <label class="flex items-center gap-2 p-2.5 border rounded-xl cursor-pointer transition"
                                               :class="paymentMethod === 'razorpay' ? 'border-[#D97706] bg-amber-50/60 font-bold' : 'border-slate-200 hover:bg-slate-50'">
                                            <input type="radio" x-model="paymentMethod" value="razorpay" class="text-[#D97706] focus:ring-[#D97706]">
                                            <div class="flex items-center justify-between w-full">
                                                <span class="text-slate-800">Razorpay Subscriptions (AutoPay UPI, Cards)</span>
                                                <span class="text-[10px] text-amber-700 bg-amber-100 px-1.5 py-0.5 rounded font-bold">Auto-Debit</span>
                                            </div>
                                        </label>
                                        <label class="flex items-center gap-2 p-2.5 border rounded-xl cursor-pointer transition"
                                               :class="paymentMethod === 'mock' ? 'border-[#D97706] bg-amber-50/60 font-bold' : 'border-slate-200 hover:bg-slate-50'">
                                            <input type="radio" x-model="paymentMethod" value="mock" class="text-[#D97706] focus:ring-[#D97706]">
                                            <span class="text-slate-700">Sandbox Instant Activation (Testing)</span>
                                        </label>
                                    </div>
                                </div>

                                <!-- Mandatory Terms & Conditions Checkbox -->
                                <div class="p-3 bg-amber-50/70 border border-amber-200/80 rounded-xl">
                                    <label class="flex items-start gap-2.5 cursor-pointer">
                                        <input type="checkbox" name="terms_accepted" value="1" x-model="termsAccepted" required
                                               class="rounded text-[#D97706] focus:ring-[#D97706] border-slate-300 mt-0.5">
                                        <span class="text-xs text-slate-700 leading-normal">
                                            I agree to the
                                            <button type="button" @click="termsModalOpen = true" class="text-[#D97706] font-bold underline hover:text-[#B45309]">
                                                Membership Terms & Conditions
                                            </button>
                                            governing the 5-year tenure, monthly recurring auto-debit of ₹{{ number_format($monthlyAutoDebit ?? $shippingRate) }}/month (Product: ₹{{ number_format($monthlyProductPrice ?? 0) }} + Delivery: ₹{{ number_format($monthlyDeliveryCharge ?? $shippingRate) }}), and sacred digital delivery.
                                        </span>
                                    </label>
                                    @error('terms_accepted')
                                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="pt-2">
                                    <button type="button"
                                            @click="initiatePayment()"
                                            :disabled="!termsAccepted || isProcessing"
                                            :class="(!termsAccepted || isProcessing) ? 'opacity-60 cursor-not-allowed bg-slate-400' : 'bg-[#D97706] hover:bg-[#F97316] shadow-lg'"
                                            class="w-full py-3.5 px-4 rounded-xl text-sm font-bold text-white transition flex items-center justify-center gap-2">
                                        <span x-show="!isProcessing">Pay ₹{{ number_format($membershipFee) }} & Authorize Monthly Auto-Debit (₹{{ number_format($monthlyAutoDebit ?? $shippingRate) }}/mo)</span>
                                        <span x-show="isProcessing" class="flex items-center gap-2">
                                            <svg class="animate-spin h-4 w-4 text-white" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                                            <span>Processing Mandate...</span>
                                        </span>
                                    </button>
                                </div>

                                <p class="text-[10px] text-center text-slate-400">
                                    Safe 256-bit encrypted checkout. Instant invoice issued upon payment.
                                </p>
                            </form>
                        @endif
                    @else
                        <div class="py-6 space-y-4 text-center">
                            <p class="text-xs text-slate-600">
                                Please sign in or register your account to attach your 5-year sacred subscription.
                            </p>
                            <a href="{{ route('register') }}"
                                class="w-full inline-block py-3.5 px-4 bg-[#D97706] hover:bg-[#F97316] text-white font-bold text-xs rounded-xl shadow">
                                Create Account & Join (₹{{ number_format($membershipFee) }})
                            </a>
                            <a href="{{ route('login') }}"
                                class="w-full inline-block py-2.5 px-4 bg-white border border-[#D97706]/30 text-slate-800 font-bold text-xs rounded-xl hover:bg-[#FFFDF7]">
                                Already Registered? Sign In
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>

        <!-- Terms & Conditions Modal -->
        <div x-show="termsModalOpen" x-cloak
             class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
            <div @click.away="termsModalOpen = false"
                 class="bg-white rounded-2xl max-w-xl w-full p-6 sm:p-8 shadow-2xl border border-slate-200 text-left space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-amber-200">
                    <h3 class="font-serif font-bold text-lg text-[#881337]">Blu Bliss 5-Year Membership Terms & Conditions</h3>
                    <button type="button" @click="termsModalOpen = false" class="text-stone-400 hover:text-stone-600 p-1">✕</button>
                </div>

                <div class="text-xs text-slate-700 space-y-4 max-h-[28rem] overflow-y-auto pr-2 leading-relaxed divide-y divide-slate-100 font-sans">
                    <div class="pt-1">
                        <h4 class="font-bold text-xs uppercase tracking-wide text-[#881337]">1. Description of Service</h4>
                        <p class="mt-1 text-slate-600">The Company shall provide pure Ganga Jal sourced from Haridwar, packed in a 500ml PET bottle, and delivered monthly to the Customer's address mentioned above.</p>
                    </div>

                    <div class="pt-3">
                        <h4 class="font-bold text-xs uppercase tracking-wide text-[#881337]">2. Agreement Period</h4>
                        <p class="mt-1 text-slate-600">The term of this Agreement shall be <strong>60 Months [5 Years]</strong>, commencing from the date of membership joining.</p>
                    </div>

                    <div class="pt-3">
                        <h4 class="font-bold text-xs uppercase tracking-wide text-[#881337]">3. Financial Terms & Payment</h4>
                        <ul class="mt-1 space-y-1.5 text-slate-600">
                            <li><strong>A] One Time Commitment Fee:</strong> ₹{{ number_format($membershipFee) }}/- (This fee is charged as a one-time charge for the 5-year service commitment. This fee is <strong>Non-Refundable</strong> and shall not be refunded under any circumstances).</li>
                            <li><strong>B] Monthly Charge:</strong> ₹{{ number_format($monthlyAutoDebit ?? $shippingRate) }}/- per bottle per month (Product Price: ₹{{ number_format($monthlyProductPrice ?? 0) }} + Delivery: ₹{{ number_format($monthlyDeliveryCharge ?? $shippingRate) }}, Including GST).</li>
                            <li><strong>C] Payment:</strong> The Commitment Fee is payable in advance at the time of signing the Agreement. The Monthly Charge is payable by the 5th of every month.</li>
                        </ul>
                    </div>

                    <div class="pt-3">
                        <h4 class="font-bold text-xs uppercase tracking-wide text-[#881337]">4. Customer Obligations</h4>
                        <ul class="mt-1 space-y-1 text-slate-600">
                            <li><strong>4.1</strong> The Customer is committed to availing the monthly service continuously for 60 months.</li>
                            <li><strong>4.2</strong> If the Customer discontinues the service before the expiry of the Agreement period, the Commitment Fee deposited shall be forfeited.</li>
                            <li><strong>4.3</strong> The Customer must provide 15 days prior written notice for any change of address.</li>
                        </ul>
                    </div>

                    <div class="pt-3">
                        <h4 class="font-bold text-xs uppercase tracking-wide text-[#881337]">5. Company Obligations</h4>
                        <ul class="mt-1 space-y-1 text-slate-600">
                            <li><strong>5.1</strong> The Company shall ensure service at the rate of ₹{{ number_format($monthlyAutoDebit ?? $shippingRate) }} for the Agreement period.</li>
                            <li><strong>5.2</strong> In case of a price revision, the Company shall provide 30 days prior written notice. The Customer shall have the right to terminate the Agreement in case of disagreement; however, the Commitment Fee shall remain non-refundable.</li>
                            <li><strong>5.3</strong> Service may be interrupted due to natural calamities/legal restrictions, for which the Company shall not be liable.</li>
                        </ul>
                    </div>

                    <div class="pt-3">
                        <h4 class="font-bold text-xs uppercase tracking-wide text-[#881337]">6. Taxation & Accounting</h4>
                        <ul class="mt-1 space-y-1 text-slate-600">
                            <li><strong>6.1</strong> GST applicable on all charges as per law. SAC Code: 9995 / HSN Code: 22019010.</li>
                            <li><strong>6.2</strong> A valid Tax Invoice shall be issued by the Company.</li>
                            <li><strong>6.3</strong> The Commitment Fee shall be treated as "Deferred Revenue" in the Company's books of accounts.</li>
                        </ul>
                    </div>

                    <div class="pt-3">
                        <h4 class="font-bold text-xs uppercase tracking-wide text-[#881337]">7. Termination of Agreement</h4>
                        <p class="mt-1 text-slate-600">This Agreement shall automatically terminate upon completion of 60 months. Renewal shall be at the Customer's discretion.</p>
                    </div>

                    <div class="pt-3">
                        <h4 class="font-bold text-xs uppercase tracking-wide text-[#881337]">8. Dispute Resolution</h4>
                        <p class="mt-1 text-slate-600">All disputes related to this Agreement shall be subject to the jurisdiction of Haridwar District Court.</p>
                    </div>

                    <div class="pt-3">
                        <h4 class="font-bold text-xs uppercase tracking-wide text-[#881337]">9. Service Cancellation Policy</h4>
                        <ul class="mt-1 space-y-1 text-slate-600">
                            <li><strong>A.</strong> The Customer may discontinue the service in any month by giving written notice or e-mail.</li>
                            <li><strong>B.</strong> The Commitment Fee of ₹{{ number_format($membershipFee) }} is non-refundable under any circumstances.</li>
                            <li><strong>C.</strong> Any monthly fee paid in advance shall be adjusted in the subsequent month's bill.</li>
                            <li><strong>D.</strong> After completion of 60 months, no further Commitment Fee shall be charged.</li>
                            <li><strong>E.</strong> For renewal after 5 years, a fresh Agreement may be executed if required.</li>
                        </ul>
                    </div>
                </div>

                <div class="pt-3 border-t border-slate-200 flex justify-end">
                    <button type="button" @click="termsAccepted = true; termsModalOpen = false"
                            class="px-4 py-2 bg-[#D97706] hover:bg-[#B45309] text-white text-xs font-bold rounded-lg transition">
                        I Understand & Accept
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Razorpay Checkout Library -->
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
</x-app-layout>

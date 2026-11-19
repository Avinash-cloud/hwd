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
             signature: '',

             initiatePayment() {
                 if (!this.termsAccepted) {
                     alert('Please accept the Membership Terms & Conditions to proceed.');
                     return;
                 }

                 if (this.paymentMethod === 'mock') {
                     document.getElementById('membership-form').submit();
                     return;
                 }

                 this.isProcessing = true;

                 // Create Razorpay Order
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
                         this.orderId = data.order_id || 'order_mock';
                         this.signature = 'mock_signature';
                         this.$nextTick(() => {
                             document.getElementById('membership-form').submit();
                         });
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
                         handler: (response) => {
                             this.paymentId = response.razorpay_payment_id;
                             this.orderId = response.razorpay_order_id;
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

                     const rzp = new Razorpay(options);
                     rzp.open();
                 })
                 .catch(err => {
                     console.error('Payment error:', err);
                     this.isProcessing = false;
                     // Fallback submission
                     document.getElementById('membership-form').submit();
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
                                    Receive a fresh 500ml bottle of pure Haridwar Gangajal delivered directly to your doorstep every single month for 5 years (60 months total)—ensuring your home altar never runs out of sacred water. (Each month, your bottle carries <strong>₹0 product price</strong>, with only flat subsidized logistics applied.)
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
                                <h3 class="font-bold text-[#881337] text-sm sm:text-base">5-Year Price Lock & Convenience</h3>
                                <p class="text-xs text-[#3B2314]/85 leading-relaxed mt-1">
                                    A one-time nominal registration fee of <strong>₹{{ number_format($membershipFee) }}</strong> secures your membership for 60 months, saving you the hassle of repeatedly ordering or searching for authentic Gangajal.
                                    <span class="block text-[11px] text-amber-900/80 font-medium mt-1">
                                        *(Note: In case of any future changes in monthly courier/logistics rates, nominal charges may be revised with advance prior intimation to members.)*
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
                    <h4 class="font-serif font-bold text-[#881337] text-sm">Devotee Guarantee & Transparency</h4>
                    <p>
                        • <strong>Delivery Schedule:</strong> Once subscribed, you can claim your monthly 500ml bottle at any
                        time from your member portal, shipped directly via express courier with real-time tracking updates.
                    </p>
                    <p>
                        • <strong>Sanctity Assurance:</strong> Collected during Brahma Muhurta morning Aarti under
                        continuous Vedic recitation. Untouched by human hands and hermetically sealed.
                    </p>
                    <p>
                        • <strong>Eco-Clean Ganga Pledge:</strong> Your monthly deliveries directly aid our local Haridwar river waste removal and ghat conservation initiatives.
                    </p>
                    <p>
                        • <strong>Tax Invoicing:</strong> A formal GST invoice with SAC Code 9995 is generated immediately upon registration and available for download.
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
                        <span class="text-xs font-bold uppercase tracking-wider text-[#D97706]">One-Time Contribution</span>
                        <div class="mt-2 flex items-baseline justify-center gap-1">
                            <span class="text-4xl font-extrabold font-serif text-[#881337]">₹{{ number_format($membershipFee) }}</span>
                            <span class="text-xs font-bold text-stone-600">/ 5 Years</span>
                        </div>
                        <span class="inline-block mt-2 text-[11px] font-semibold text-[#0369A1] bg-[#0369A1]/10 border border-[#0369A1]/30 px-3 py-0.5 rounded-full">
                            Equivalent to ₹{{ number_format($membershipFee / 60, 2) }} / month • GST Included
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

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Select Payment Gateway</label>
                                    <div class="space-y-2 text-xs">
                                        <label class="flex items-center gap-2 p-2.5 border rounded-xl cursor-pointer transition"
                                               :class="paymentMethod === 'razorpay' ? 'border-[#D97706] bg-amber-50/60 font-bold' : 'border-slate-200 hover:bg-slate-50'">
                                            <input type="radio" x-model="paymentMethod" value="razorpay" class="text-[#D97706] focus:ring-[#D97706]">
                                            <div class="flex items-center justify-between w-full">
                                                <span class="text-slate-800">Razorpay (UPI, Cards, NetBanking)</span>
                                                <span class="text-[10px] text-amber-700 bg-amber-100 px-1.5 py-0.5 rounded font-bold">Fast</span>
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
                                            governing the 5-year tenure, monthly offerings, and digital delivery.
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
                                        <span x-show="!isProcessing">Pay ₹{{ number_format($membershipFee) }} & Activate 5-Year Membership</span>
                                        <span x-show="isProcessing" class="flex items-center gap-2">
                                            <svg class="animate-spin h-4 w-4 text-white" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                                            <span>Processing Payment...</span>
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

                <div class="text-xs text-slate-600 space-y-3 max-h-96 overflow-y-auto pr-2 leading-relaxed">
                    <p><strong>1. Membership Tenure:</strong> The Blu Bliss Sacred Devotee Membership is valid for exactly 5 continuous calendar years (60 months) from the date of activation.</p>
                    <p><strong>2. Monthly Sacred Gangajal Benefit:</strong> Active members are entitled to claim 1 complimentary (₹0 product cost) 500ml Haridwar Gangajal bottle per calendar month. A flat subsidized packing, handling, and delivery charge (currently ₹{{ number_format($shippingRate) }}) applies per dispatch. In case of any future changes in monthly courier/logistics rates, nominal charges may be revised with advance prior intimation to members.</p>
                    <p><strong>3. Sanctity Guarantee:</strong> Haridwar Bliss certifies that water is collected strictly from sacred Haridwar Ganga Ghats during auspicious Brahma Muhurta morning Aarti. Every bottle includes tamper-evident, leak-proof protective packaging and a verifiable batch lab assay.</p>
                    <p><strong>4. Fee & Invoicing:</strong> The ₹{{ number_format($membershipFee) }} one-time registration contribution is inclusive of 18% GST (SAC Code 9995). A downloadable digital tax invoice is generated upon confirmation.</p>
                    <p><strong>5. Sacred Kits & Cleanliness Pledge:</strong> Members receive preferred pricing and early access privileges on consecrated Vedic Puja Essentials, festive kits, and Haridwar Ghat Prasadam. Every monthly delivery directly supports our Eco-Clean Ganga Pledge toward river waste removal and environmental conservation in Haridwar.</p>
                    <p><strong>6. Non-Transferable:</strong> Membership privileges are attached to the registered devotee account and are non-transferable and non-refundable once activated.</p>
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

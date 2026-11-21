<x-app-layout>
    <!-- Alpine.js Page State for Dynamic Quantity & Price Calculation -->
    @php
        $defaultPackage = $packages->first();
        $packagesJson = $packages->map(function ($pkg) {
            return [
                'id' => $pkg->id,
                'name' => $pkg->name,
                'litres' => (float) $pkg->quantity_litres,
                'unit' => $pkg->unit,
                'container' => $pkg->container_type,
                'price' => (float) $pkg->price,
                'original_price' => $pkg->original_price ? (float) $pkg->original_price : null,
                'description' => $pkg->description,
            ];
        })->values();
    @endphp

    <div x-data="{
        packages: {{ $packagesJson->toJson() }},
        selectedPackageId: {{ old('package_id', $defaultPackage?->id ?? 1) }},
        quantityCount: {{ old('quantity_count', 1) }},
        paymentMethod: '{{ old('payment_method', 'razorpay') }}',
        isProcessing: false,
        razorpayPaymentId: '',
        razorpayOrderId: '',
        razorpaySignature: '',

        get currentPackage() {
            return this.packages.find(p => p.id === this.selectedPackageId) || this.packages[0] || {
                id: 1,
                name: '1 Litre Sacred Kumbh Ganga Jal',
                litres: 1,
                unit: 'Litre',
                container: 'Auspicious Sealed Bottle',
                price: 151,
                original_price: 250,
                description: 'Direct holy Haridwar Kumbh collection.'
            };
        },

        get totalLitres() {
            return (this.currentPackage.litres * this.quantityCount).toFixed(1).replace(/\.0$/, '');
        },

        get unitPrice() {
            return this.currentPackage.price;
        },

        get totalPrice() {
            return this.unitPrice * this.quantityCount;
        },

        selectPackage(id) {
            this.selectedPackageId = id;
        },

        incrementQty() {
            if (this.quantityCount < 100) this.quantityCount++;
        },

        decrementQty() {
            if (this.quantityCount > 1) this.quantityCount--;
        },

        initiateBookingPayment() {
            const form = document.getElementById('water-collection-form');
            if (!form) return;

            if (!form.reportValidity()) {
                return;
            }

            if (this.paymentMethod === 'cod') {
                form.submit();
                return;
            }

            this.isProcessing = true;

            const devoteeName = form.querySelector('[name=name]')?.value || '';
            const devoteeEmail = form.querySelector('[name=email]')?.value || '';
            const devoteeMobile = form.querySelector('[name=mobile]')?.value || '';

            fetch('{{ route('water-collection.razorpay.order') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    package_id: this.selectedPackageId,
                    quantity_count: this.quantityCount,
                    name: devoteeName,
                    email: devoteeEmail,
                    mobile: devoteeMobile
                })
            })
            .then(res => {
                if (!res.ok) throw new Error('Order creation failed');
                return res.json();
            })
            .then(data => {
                if (data.is_mock || typeof Razorpay === 'undefined') {
                    this.razorpayPaymentId = 'pay_kmb_' + Math.random().toString(36).substring(2, 10).toUpperCase();
                    this.razorpayOrderId = data.order_id || 'order_mock';
                    this.razorpaySignature = 'mock_signature';
                    this.$nextTick(() => {
                        form.submit();
                    });
                    return;
                }

                const options = {
                    key: data.key,
                    amount: data.amount,
                    currency: data.currency || 'INR',
                    name: data.name || 'Haridwar Bliss',
                    description: data.description || 'Haridwar Kumbh Water Collection',
                    order_id: data.order_id,
                    prefill: data.prefill,
                    theme: { color: '#881337' },
                    handler: (response) => {
                        this.razorpayPaymentId = response.razorpay_payment_id;
                        this.razorpayOrderId = response.razorpay_order_id;
                        this.razorpaySignature = response.razorpay_signature;
                        this.$nextTick(() => {
                            form.submit();
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
                console.error('Razorpay Error:', err);
                this.isProcessing = false;
                alert('Unable to connect with Razorpay. You can switch to Pay on Collection or try again.');
            });
        }
    }" class="bg-[#FAF7F2] text-slate-800 min-h-screen">

        <!-- 1. Sacred Hero Banner (Signature Maroon Card on Solid Light Orange Band) -->
        <div class="relative bg-[#FFA028] text-white overflow-hidden py-10 sm:py-16 border-b border-orange-600/40">
            <div class="absolute -top-24 -right-24 w-72 h-72 rounded-full bg-amber-300/20 blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-24 -left-24 w-72 h-72 rounded-full bg-sky-200/20 blur-3xl pointer-events-none"></div>

            <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-[#881337] border border-amber-300/40 rounded-2xl sm:rounded-3xl p-6 sm:p-10 shadow-xl text-center space-y-6">
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-amber-400/20 border border-amber-300/40 text-[#FDE68A] text-xs sm:text-sm font-semibold tracking-wide shadow-md">
                        <span>🕉️ ॐ गंगे च यमुने चैव गोदावरि सरस्वति</span>
                        <span class="hidden md:inline">|</span>
                        <span class="hidden md:inline font-normal">Haridwar Kumbh Mahotsav Sewa</span>
                    </div>

                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-serif font-extrabold text-[#FDE68A] italic tracking-tight leading-tight">
                        Haridwar Kumbh Water Collection Service
                        <span class="block text-xl sm:text-2xl lg:text-3xl font-semibold text-amber-200 mt-2 not-italic">
                            हरिद्वार महाकुम्भ पवित्र गंगाजल संग्रह एवं देवस्थान होम डिलीवरी
                        </span>
                    </h1>

                    <p class="max-w-3xl mx-auto text-sm sm:text-base text-white/95 leading-relaxed font-medium">
                        Direct sacred collection of 100% pure Haridwar Ganga Jal during auspicious Kumbh Mahotsav. Picked up from your Haridwar Hotel or Dharamshala, sanctified and sealed in food-grade containers, and delivered safely to your resident doorstep across India.
                    </p>
                </div>
            </div>
        </div>

                <!-- Value Proposition Highlights -->
                <div class="pt-4 grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4 max-w-4xl mx-auto text-left text-xs sm:text-sm">
                    <div class="bg-white/10 backdrop-blur-md rounded-xl p-3 sm:p-4 border border-amber-400/30 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-amber-500/30 text-amber-300 flex items-center justify-center shrink-0 text-xl">
                            🌊
                        </div>
                        <div>
                            <span class="block font-bold text-amber-200">100% Pure Gangajal</span>
                            <span class="text-[11px] text-amber-100/70">Kumbh Snan Muhurat</span>
                        </div>
                    </div>

                    <div class="bg-white/10 backdrop-blur-md rounded-xl p-3 sm:p-4 border border-amber-400/30 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-amber-500/30 text-amber-300 flex items-center justify-center shrink-0 text-xl">
                            🏨
                        </div>
                        <div>
                            <span class="block font-bold text-amber-200">Hotel Pickup</span>
                            <span class="text-[11px] text-amber-100/70">Any Haridwar stay</span>
                        </div>
                    </div>

                    <div class="bg-white/10 backdrop-blur-md rounded-xl p-3 sm:p-4 border border-amber-400/30 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-amber-500/30 text-amber-300 flex items-center justify-center shrink-0 text-xl">
                            🚚
                        </div>
                        <div>
                            <span class="block font-bold text-amber-200">Doorstep Delivery</span>
                            <span class="text-[11px] text-amber-100/70">Any resident address</span>
                        </div>
                    </div>

                    <div class="bg-white/10 backdrop-blur-md rounded-xl p-3 sm:p-4 border border-amber-400/30 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-amber-500/30 text-amber-300 flex items-center justify-center shrink-0 text-xl">
                            🔒
                        </div>
                        <div>
                            <span class="block font-bold text-amber-200">Tamper-Proof Seal</span>
                            <span class="text-[11px] text-amber-100/70">Vedic authenticity</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 2. Main Booking & Dynamic Pricing Layout -->
        <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-14">
            <!-- Flash Notifications -->
            @if(session('success'))
                <div class="mb-8 p-4 rounded-xl bg-emerald-50 border border-emerald-300 text-emerald-800 text-sm flex items-start gap-3 shadow-xs">
                    <svg class="w-5 h-5 text-emerald-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <span class="font-bold block">Jai Gange!</span>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-8 p-4 rounded-xl bg-rose-50 border border-rose-300 text-rose-800 text-sm shadow-xs">
                    <div class="font-bold flex items-center gap-2 mb-1 text-rose-900">
                        <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <span>Please correct the highlighted information below:</span>
                    </div>
                    <ul class="list-disc pl-5 space-y-0.5 text-xs text-rose-700">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

                <!-- Left Column: Step-by-Step Info & Dynamic Quantity Tier Cards (lg:col-span-7) -->
                <div class="lg:col-span-7 space-y-8">

                    <!-- How This Service Works -->
                    <div class="bg-white rounded-2xl p-6 sm:p-7 border border-amber-200/80 shadow-xs space-y-5">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-amber-100 text-[#881337] flex items-center justify-center font-bold text-lg shrink-0">
                                📿
                            </div>
                            <div>
                                <h2 class="text-lg font-bold text-slate-900">How Kumbh Water Collection Works</h2>
                                <p class="text-xs text-slate-500">Convenient, sacred, and 100% transparent process for pilgrims and devotees.</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-1">
                            <div class="p-3.5 rounded-xl bg-amber-50/50 border border-amber-200/60 space-y-1.5 text-xs">
                                <div class="w-6 h-6 rounded-full bg-[#881337] text-white font-bold flex items-center justify-center text-[11px]">1</div>
                                <span class="font-bold text-slate-900 block">Choose Quantity</span>
                                <p class="text-slate-600 leading-relaxed text-[11.5px]">Select your desired water quantity (1L, 2L, 5L, 10L) with live price transparency.</p>
                            </div>

                            <div class="p-3.5 rounded-xl bg-amber-50/50 border border-amber-200/60 space-y-1.5 text-xs">
                                <div class="w-6 h-6 rounded-full bg-[#881337] text-white font-bold flex items-center justify-center text-[11px]">2</div>
                                <span class="font-bold text-slate-900 block">Haridwar Pickup</span>
                                <p class="text-slate-600 leading-relaxed text-[11.5px]">Provide your Haridwar Hotel/Dharamshala where you stay or request our team to collect from Ghat.</p>
                            </div>

                            <div class="p-3.5 rounded-xl bg-amber-50/50 border border-amber-200/60 space-y-1.5 text-xs">
                                <div class="w-6 h-6 rounded-full bg-[#881337] text-white font-bold flex items-center justify-center text-[11px]">3</div>
                                <span class="font-bold text-slate-900 block">Doorstep Delivery</span>
                                <p class="text-slate-600 leading-relaxed text-[11.5px]">We seal, certify with Vedic sankalp, and courier directly to your resident address.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Dynamic Water Quantity & Pricing Tiers (Admin Controlled) -->
                    <div class="bg-white rounded-2xl p-6 sm:p-7 border border-amber-200/80 shadow-xs space-y-5">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 border-b border-slate-100 pb-4">
                            <div>
                                <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                                    <span>Select Sacred Water Quantity</span>
                                    <span class="text-[10px] bg-amber-100 text-amber-900 font-bold px-2 py-0.5 rounded-full uppercase tracking-wider">Dynamic Pricing</span>
                                </h3>
                                <p class="text-xs text-slate-500 mt-0.5">Choose an auspicious volume package. Rates are dynamically configured by administration.</p>
                            </div>

                            <div class="text-xs text-amber-800 font-semibold bg-amber-50 px-3 py-1.5 rounded-lg border border-amber-200 inline-flex items-center gap-1.5 self-start sm:self-auto">
                                <span>🕉️ Haridwar Kumbh Certified</span>
                            </div>
                        </div>

                        <!-- Package Selector Cards -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <template x-for="pkg in packages" :key="pkg.id">
                                <div @click="selectPackage(pkg.id)"
                                     :class="selectedPackageId === pkg.id
                                        ? 'border-amber-500 bg-amber-50/60 ring-2 ring-amber-500/20 shadow-md'
                                        : 'border-slate-200 hover:border-amber-300 hover:bg-slate-50/80'"
                                     class="relative cursor-pointer rounded-xl border p-4.5 transition-all text-left flex flex-col justify-between space-y-3">

                                    <div>
                                        <div class="flex items-start justify-between gap-2">
                                            <div>
                                                <h4 class="font-bold text-slate-900 text-sm leading-snug" x-text="pkg.name"></h4>
                                                <span class="text-[11px] text-amber-700 font-medium inline-block mt-0.5" x-text="pkg.container"></span>
                                            </div>

                                            <!-- Radio Indicator -->
                                            <div class="w-4 h-4 rounded-full border flex items-center justify-center shrink-0 mt-0.5"
                                                 :class="selectedPackageId === pkg.id ? 'border-amber-600 bg-amber-600' : 'border-slate-300 bg-white'">
                                                <div class="w-1.5 h-1.5 rounded-full bg-white" x-show="selectedPackageId === pkg.id"></div>
                                            </div>
                                        </div>

                                        <p class="text-xs text-slate-600 mt-2 line-clamp-2" x-text="pkg.description"></p>
                                    </div>

                                    <div class="pt-2 border-t border-slate-100 flex items-baseline justify-between">
                                        <div class="flex items-baseline gap-2">
                                            <span class="text-base font-extrabold text-[#881337]">₹<span x-text="pkg.price"></span></span>
                                            <template x-if="pkg.original_price && pkg.original_price > pkg.price">
                                                <span class="text-xs text-slate-400 line-through">₹<span x-text="pkg.original_price"></span></span>
                                            </template>
                                        </div>
                                        <span class="text-[11px] font-semibold text-slate-500" x-text="pkg.litres + ' ' + (pkg.unit || 'Litre')"></span>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <!-- Quantity Multiplier Box -->
                        <div class="pt-3 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-amber-50/40 p-4 rounded-xl border border-amber-200/50">
                            <div>
                                <span class="font-bold text-xs text-slate-800 block">Package Multiplier / Number of Containers</span>
                                <span class="text-[11px] text-slate-500">How many units of the selected package do you require?</span>
                            </div>

                            <div class="flex items-center gap-2">
                                <button type="button" @click="decrementQty()"
                                        class="w-8 h-8 rounded-lg bg-white border border-slate-300 text-slate-700 font-bold hover:bg-slate-100 flex items-center justify-center transition active:scale-95 cursor-pointer">
                                    -
                                </button>
                                <span class="w-10 text-center font-bold text-slate-900 text-sm" x-text="quantityCount"></span>
                                <button type="button" @click="incrementQty()"
                                        class="w-8 h-8 rounded-lg bg-white border border-slate-300 text-slate-700 font-bold hover:bg-slate-100 flex items-center justify-center transition active:scale-95 cursor-pointer">
                                    +
                                </button>
                            </div>
                        </div>

                        <!-- Live Calculation Summary Pill -->
                        <div class="bg-gradient-to-r from-amber-100 via-amber-50 to-orange-50 border border-amber-300/80 rounded-xl p-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 text-xs">
                            <div class="space-y-0.5">
                                <span class="text-slate-600 block">Calculated Total Sacred Volume:</span>
                                <span class="font-bold text-slate-900 text-sm text-[#881337]">
                                    <span x-text="totalLitres"></span> Litres Kumbh Ganga Jal
                                    (<span x-text="quantityCount"></span>x <span x-text="currentPackage.name"></span>)
                                </span>
                            </div>

                            <div class="sm:text-right">
                                <span class="text-slate-600 block">Estimated Holy Seva / Price:</span>
                                <span class="text-lg font-black text-[#881337]">₹<span x-text="totalPrice"></span>/-</span>
                            </div>
                        </div>
                    </div>

                    <!-- Trust Guarantees & Devotee FAQ Accordion -->
                    <div class="bg-white rounded-2xl p-6 sm:p-7 border border-amber-200/80 shadow-xs space-y-4">
                        <h3 class="text-base font-bold text-slate-900">Devotee Guarantees & Kumbh FAQs</h3>

                        <div class="divide-y divide-slate-100 text-xs">
                            <div class="py-3 space-y-1">
                                <h4 class="font-bold text-slate-800">Where in Haridwar will the Ganga Jal be collected or picked up?</h4>
                                <p class="text-slate-600 leading-relaxed">
                                    If you are visiting Haridwar and staying at any hotel, dharamshala, or ashram, our dedicated Sewa team will visit your location to safely collect your holy Kumbh water. We also provide direct sacred collection during auspicious snan muhurats on your behalf, sealed in certified containers and delivered safely to your home address.
                                </p>
                            </div>

                            <div class="py-3 space-y-1">
                                <h4 class="font-bold text-slate-800">Can you deliver to any state or pin code in India?</h4>
                                <p class="text-slate-600 leading-relaxed">
                                    Yes, we provide doorstep sacred logistics to all serviceable pincodes across India via express priority courier partners with full online batch tracking and SMS updates.
                                </p>
                            </div>

                            <div class="py-3 space-y-1">
                                <h4 class="font-bold text-slate-800">What kind of containers are used for shipping?</h4>
                                <p class="text-slate-600 leading-relaxed">
                                    We use 100% food-grade, leak-proof, hermetically sealed bottles and canisters certified for holy water storage to maintain pristine purity throughout the journey.
                                </p>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Right Column: The Lead Generation Form (lg:col-span-5) -->
                <div class="lg:col-span-5 sticky top-20">
                    <div class="bg-white rounded-2xl border-2 border-amber-400 shadow-xl overflow-hidden">

                        <!-- Form Header -->
                        <div style="background-color: #380B12; background-image: linear-gradient(135deg, #25050A 0%, #380B12 50%, #881337 100%); color: #ffffff;"
                             class="bg-[#380B12] text-white p-5 sm:p-6 text-center space-y-1">
                            <span class="inline-block text-xs font-bold uppercase tracking-wider text-amber-300" style="color: #FCD34D;">Haridwar Kumbh Sewa</span>
                            <h2 class="text-lg sm:text-xl font-extrabold text-white" style="color: #FFFFFF;">Book Kumbh Water Collection</h2>
                            <p class="text-xs text-amber-100 font-medium" style="color: #FEF3C7;">Fill your details below. Our Sewa coordinator will confirm promptly.</p>
                        </div>

                        <!-- The Lead Form -->
                        <form id="water-collection-form" method="POST" action="{{ route('water-collection.store') }}" class="p-6 space-y-4 text-xs">
                            @csrf

                            <!-- Hidden Form inputs synced with Alpine.js -->
                            <input type="hidden" name="package_id" :value="selectedPackageId">
                            <input type="hidden" name="quantity_count" :value="quantityCount">
                            <input type="hidden" name="payment_method" :value="paymentMethod">
                            <input type="hidden" name="razorpay_payment_id" :value="razorpayPaymentId">
                            <input type="hidden" name="razorpay_order_id" :value="razorpayOrderId">
                            <input type="hidden" name="razorpay_signature" :value="razorpaySignature">

                            <!-- Section 1: Devotee Contact Details -->
                            <div class="space-y-3">
                                <div class="flex items-center gap-2 text-slate-900 font-bold pb-1 border-b border-slate-100">
                                    <span class="w-5 h-5 rounded-full bg-amber-100 text-[#881337] flex items-center justify-center text-[10px]">1</span>
                                    <span>Devotee Personal Details</span>
                                </div>

                                <div>
                                    <label for="name" class="block font-semibold text-slate-700 mb-1">
                                        Full Name <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="text"
                                           name="name"
                                           id="name"
                                           value="{{ old('name') }}"
                                           required
                                           placeholder="e.g. Ramesh Kumar Sharma"
                                           class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300 focus:ring-1 focus:ring-amber-500 focus:border-amber-500 placeholder-slate-400" />
                                    @error('name')
                                        <p class="text-rose-600 text-[11px] mt-0.5">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div>
                                        <label for="mobile" class="block font-semibold text-slate-700 mb-1">
                                            Mobile / WhatsApp <span class="text-rose-500">*</span>
                                        </label>
                                        <input type="tel"
                                               name="mobile"
                                               id="mobile"
                                               value="{{ old('mobile') }}"
                                               required
                                               placeholder="e.g. 9876543210"
                                               class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300 focus:ring-1 focus:ring-amber-500 focus:border-amber-500 placeholder-slate-400" />
                                        @error('mobile')
                                            <p class="text-rose-600 text-[11px] mt-0.5">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="email" class="block font-semibold text-slate-700 mb-1">
                                            Email Address <span class="text-slate-400 font-normal">(Optional)</span>
                                        </label>
                                        <input type="email"
                                               name="email"
                                               id="email"
                                               value="{{ old('email') }}"
                                               placeholder="e.g. devotee@example.com"
                                               class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300 focus:ring-1 focus:ring-amber-500 focus:border-amber-500 placeholder-slate-400" />
                                        @error('email')
                                            <p class="text-rose-600 text-[11px] mt-0.5">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Section 2: Delivery Resident Address (Where to Deliver) -->
                            <div class="space-y-3 pt-2">
                                <div class="flex items-center gap-2 text-slate-900 font-bold pb-1 border-b border-slate-100">
                                    <span class="w-5 h-5 rounded-full bg-amber-100 text-[#881337] flex items-center justify-center text-[10px]">2</span>
                                    <span>Resident Address (Where to Deliver)</span>
                                </div>

                                <div>
                                    <label for="delivery_address" class="block font-semibold text-slate-700 mb-1">
                                        Resident Street Address <span class="text-rose-500">*</span>
                                    </label>
                                    <textarea name="delivery_address"
                                              id="delivery_address"
                                              rows="2"
                                              required
                                              placeholder="House / Flat No., Society / Street Name, Landmark"
                                              class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300 focus:ring-1 focus:ring-amber-500 focus:border-amber-500 placeholder-slate-400">{{ old('delivery_address') }}</textarea>
                                    @error('delivery_address')
                                        <p class="text-rose-600 text-[11px] mt-0.5">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="grid grid-cols-3 gap-2">
                                    <div>
                                        <label for="delivery_city" class="block font-semibold text-slate-700 mb-1">
                                            City
                                        </label>
                                        <input type="text"
                                               name="delivery_city"
                                               id="delivery_city"
                                               value="{{ old('delivery_city') }}"
                                               placeholder="e.g. Delhi / Lucknow"
                                               class="w-full px-2.5 py-2 text-xs rounded-lg border border-slate-300 focus:ring-1 focus:ring-amber-500 focus:border-amber-500 placeholder-slate-400" />
                                    </div>

                                    <div>
                                        <label for="delivery_state" class="block font-semibold text-slate-700 mb-1">
                                            State
                                        </label>
                                        <input type="text"
                                               name="delivery_state"
                                               id="delivery_state"
                                               value="{{ old('delivery_state') }}"
                                               placeholder="e.g. UP / Maharashtra"
                                               class="w-full px-2.5 py-2 text-xs rounded-lg border border-slate-300 focus:ring-1 focus:ring-amber-500 focus:border-amber-500 placeholder-slate-400" />
                                    </div>

                                    <div>
                                        <label for="delivery_pincode" class="block font-semibold text-slate-700 mb-1">
                                            PIN Code
                                        </label>
                                        <input type="text"
                                               name="delivery_pincode"
                                               id="delivery_pincode"
                                               value="{{ old('delivery_pincode') }}"
                                               placeholder="e.g. 110001"
                                               class="w-full px-2.5 py-2 text-xs rounded-lg border border-slate-300 focus:ring-1 focus:ring-amber-500 focus:border-amber-500 placeholder-slate-400" />
                                    </div>
                                </div>
                            </div>

                            <!-- Section 3: Hotel / Dharamshala in Haridwar (Where to Pick Up Jal) -->
                            <div class="space-y-3 pt-2">
                                <div class="flex items-center gap-2 text-slate-900 font-bold pb-1 border-b border-slate-100">
                                    <span class="w-5 h-5 rounded-full bg-amber-100 text-[#881337] flex items-center justify-center text-[10px]">3</span>
                                    <span>Haridwar Pickup Point (Hotel / Dharamshala)</span>
                                </div>

                                <div>
                                    <label for="hotel_or_dharamshala" class="block font-semibold text-slate-700 mb-1">
                                        Hotel / Dharamshala / Ashram Name <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="text"
                                           name="hotel_or_dharamshala"
                                           id="hotel_or_dharamshala"
                                           value="{{ old('hotel_or_dharamshala') }}"
                                           required
                                           placeholder="e.g. Hotel Ganga Kinare, Birla Dharamshala, or Direct Ghat Sewa"
                                           class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300 focus:ring-1 focus:ring-amber-500 focus:border-amber-500 placeholder-slate-400" />
                                    <span class="text-[10px] text-slate-400 block mt-0.5">
                                        Enter your Haridwar stay address, or specify "Direct Haridwar Ghat Collection" if you want our team to collect fresh on your behalf.
                                    </span>
                                    @error('hotel_or_dharamshala')
                                        <p class="text-rose-600 text-[11px] mt-0.5">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div>
                                        <label for="pickup_room_or_landmark" class="block font-semibold text-slate-700 mb-1">
                                            Room No. / Landmark in Haridwar
                                        </label>
                                        <input type="text"
                                               name="pickup_room_or_landmark"
                                               id="pickup_room_or_landmark"
                                               value="{{ old('pickup_room_or_landmark') }}"
                                               placeholder="e.g. Room 204 / Near Railway Station"
                                               class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300 focus:ring-1 focus:ring-amber-500 focus:border-amber-500 placeholder-slate-400" />
                                    </div>

                                    <div>
                                        <label for="pickup_date" class="block font-semibold text-slate-700 mb-1">
                                            Preferred Collection Date
                                        </label>
                                        <input type="date"
                                               name="pickup_date"
                                               id="pickup_date"
                                               value="{{ old('pickup_date', now()->format('Y-m-d')) }}"
                                               min="{{ now()->format('Y-m-d') }}"
                                               class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300 focus:ring-1 focus:ring-amber-500 focus:border-amber-500" />
                                    </div>
                                </div>
                            </div>

                            <!-- Section 4: Special Devotional Notes / Sankalp -->
                            <div class="space-y-2 pt-2">
                                <label for="notes" class="block font-semibold text-slate-700">
                                    Special Devotional Notes / Gotra / Sankalp <span class="text-slate-400 font-normal">(Optional)</span>
                                </label>
                                <textarea name="notes"
                                          id="notes"
                                          rows="2"
                                          placeholder="Mention family Gotra, auspicious snan muhurat request, or special instructions for packaging..."
                                          class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300 focus:ring-1 focus:ring-amber-500 focus:border-amber-500 placeholder-slate-400">{{ old('notes') }}</textarea>
                            </div>

                            <!-- Dynamic Summary Card inside Form -->
                            <div class="bg-amber-50/70 rounded-xl p-3.5 border border-amber-200/80 space-y-2">
                                <div class="flex items-center justify-between text-xs font-semibold text-slate-700">
                                    <span>Selected Package:</span>
                                    <span class="text-slate-900 font-bold" x-text="currentPackage.name"></span>
                                </div>
                                <div class="flex items-center justify-between text-xs text-slate-600">
                                    <span>Quantity Multiplier:</span>
                                    <span class="font-bold text-slate-800" x-text="quantityCount + ' Container(s)'"></span>
                                </div>
                                <div class="flex items-center justify-between text-xs text-slate-600">
                                    <span>Total Holy Volume:</span>
                                    <span class="font-bold text-amber-900" x-text="totalLitres + ' Litres'"></span>
                                </div>
                                <div class="pt-2 border-t border-amber-200 flex items-center justify-between font-bold text-sm">
                                    <span class="text-slate-900">Total Seva Contribution:</span>
                                    <span class="text-[#881337] text-base font-black">₹<span x-text="totalPrice"></span>/-</span>
                                </div>
                            </div>

                            <!-- Section 5: Choose Payment Gateway / Method -->
                            <div class="space-y-3 pt-2">
                                <div class="flex items-center gap-2 text-slate-900 font-bold pb-1 border-b border-slate-100">
                                    <span class="w-5 h-5 rounded-full bg-amber-100 text-[#881337] flex items-center justify-center text-[10px]">5</span>
                                    <span>Payment Method (Razorpay Gateway)</span>
                                </div>

                                <div class="space-y-2">
                                    <!-- Razorpay Online Option -->
                                    <label class="flex items-center gap-3 p-3 rounded-xl border-2 cursor-pointer transition"
                                           :class="paymentMethod === 'razorpay' ? 'border-[#881337] bg-rose-50/60 shadow-xs' : 'border-slate-200 hover:border-amber-300 bg-white'">
                                        <input type="radio" x-model="paymentMethod" value="razorpay" class="text-[#881337] focus:ring-[#881337]">
                                        <div class="flex-1 flex items-center justify-between">
                                            <div>
                                                <span class="font-bold text-slate-900 block">Pay Online via Razorpay</span>
                                                <span class="text-[11px] text-slate-500">UPI (GPay, PhonePe, Paytm), Cards, NetBanking</span>
                                            </div>
                                            <span class="text-[10px] font-bold bg-emerald-100 text-emerald-800 px-2 py-0.5 rounded-full uppercase">Instant</span>
                                        </div>
                                    </label>

                                    <!-- Pay on Collection (COD) Option -->
                                    <label class="flex items-center gap-3 p-3 rounded-xl border-2 cursor-pointer transition"
                                           :class="paymentMethod === 'cod' ? 'border-[#881337] bg-rose-50/60 shadow-xs' : 'border-slate-200 hover:border-amber-300 bg-white'">
                                        <input type="radio" x-model="paymentMethod" value="cod" class="text-[#881337] focus:ring-[#881337]">
                                        <div class="flex-1 flex items-center justify-between">
                                            <div>
                                                <span class="font-bold text-slate-900 block">Pay on Collection (Cash / UPI)</span>
                                                <span class="text-[11px] text-slate-500">Hand over seva offering when our team collects or delivers</span>
                                            </div>
                                            <span class="text-[10px] font-semibold bg-amber-100 text-amber-800 px-2 py-0.5 rounded-full uppercase">At Pickup</span>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <!-- Devotee Guarantee Checkbox -->
                            <div class="flex items-start gap-2 pt-1">
                                <input type="checkbox" id="devotee_consent" checked required
                                       class="mt-0.5 rounded border-slate-300 text-amber-600 focus:ring-amber-500" />
                                <label for="devotee_consent" class="text-[11px] text-slate-600">
                                    I confirm that this sacred Ganga Jal is required for devotional, snan, abhishek, and spiritual purposes.
                                </label>
                            </div>

                            <!-- Submit Button -->
                            <button type="button"
                                    @click="initiateBookingPayment()"
                                    :disabled="isProcessing"
                                    style="background-color: #881337 !important; background-image: linear-gradient(135deg, #B45309 0%, #881337 60%, #70102E 100%) !important; color: #FFFFFF !important;"
                                    :class="isProcessing ? 'opacity-70 cursor-not-allowed' : 'hover:bg-[#70102E] cursor-pointer active:scale-98'"
                                    class="w-full py-3.5 px-4 rounded-xl font-extrabold text-sm sm:text-base text-white bg-[#881337] shadow-lg shadow-amber-900/30 transition transform flex items-center justify-center gap-2">
                                <template x-if="!isProcessing">
                                    <div class="flex items-center justify-center gap-2 w-full text-white" style="color: #FFFFFF !important;">
                                        <span class="text-lg">🔱</span>
                                        <span class="font-extrabold tracking-wide text-white" style="color: #FFFFFF !important;">
                                            <span x-show="paymentMethod === 'razorpay'">Pay ₹<span x-text="totalPrice"></span> via Razorpay &amp; Book</span>
                                            <span x-show="paymentMethod === 'cod'" x-cloak>Confirm Booking (Pay on Collection)</span>
                                        </span>
                                        <span class="font-bold text-white" style="color: #FFFFFF !important;">→</span>
                                    </div>
                                </template>
                                <template x-if="isProcessing">
                                    <div class="flex items-center justify-center gap-2 text-white" style="color: #FFFFFF !important;">
                                        <svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                                        <span class="font-extrabold tracking-wide text-white" style="color: #FFFFFF !important;">Connecting to Razorpay...</span>
                                    </div>
                                </template>
                            </button>

                            <p class="text-center text-[10.5px] text-slate-500 pt-1">
                                Questions? Call / WhatsApp our Haridwar Kumbh Sewa Helpdesk at
                                <a href="tel:{{ preg_replace('/[^0-9+]/', '', $supportPhone) }}" class="font-bold text-[#881337] hover:underline">{{ $supportPhone }}</a>
                            </p>
                        </form>
                    </div>
                </div>

            </div>
        </section>

    </div>

    <!-- Razorpay Checkout Library -->
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
</x-app-layout>

<x-app-layout>
    <style>
        /* Generous, un-cramped line height specifically for Devanagari Hindi text to prevent vowel overlap */
        .hindi-text {
            line-height: 1.85 !important;
            letter-spacing: 0.012em;
        }

        .hindi-title {
            line-height: 1.42 !important;
            letter-spacing: 0.008em;
        }

        .hindi-subtitle {
            line-height: 1.78 !important;
            letter-spacing: 0.01em;
        }
    </style>

    <!-- Page Wrapper with Single-Page Alpine Language & Modal State (Default: 'en') -->
    <div x-data="{
        lang: (typeof window !== 'undefined' && localStorage.getItem('puja_lang')) ? localStorage.getItem('puja_lang') : 'en',
        setLang(l) {
            this.lang = l;
            try { localStorage.setItem('puja_lang', l); } catch(e) {}
        },
        isBookingModalOpen: {{ ($errors->any() || session('booking_success')) ? 'true' : 'false' }},
        selectedPackage: '{{ old('package', 'पैकेज 1: मंगल उत्सव एवं आयुष्मान संकल्प पूजा (₹5,100/-)') }}',
        pujaPaymentMethod: '{{ old('payment_method', 'razorpay') }}',
        isPujaProcessing: false,
        pujaPaymentId: '',
        pujaOrderId: '',
        pujaSignature: '',

        get currentPujaAmount() {
            return (this.selectedPackage.includes('11,000') || this.selectedPackage.includes('11000') || this.selectedPackage.includes('पैकेज 2')) ? 11000 : 5100;
        },

        openBookingModal(pkgName = null) {
            if (pkgName) {
                this.selectedPackage = pkgName;
            }
            this.isBookingModalOpen = true;
            document.body.classList.add('overflow-hidden');
        },
        closeBookingModal() {
            this.isBookingModalOpen = false;
            document.body.classList.remove('overflow-hidden');
        },

        initiatePujaPayment() {
            const form = document.getElementById('puja-booking-form');
            if (!form) return;

            if (!form.reportValidity()) {
                return;
            }

            this.isPujaProcessing = true;

            const devoteeName = form.querySelector('[name=devotee_name]')?.value || '';
            const phone = form.querySelector('[name=phone]')?.value || '';
            const email = form.querySelector('[name=email]')?.value || '';

            fetch('{{ route('online-puja.razorpay.order') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    package: this.selectedPackage,
                    devotee_name: devoteeName,
                    phone: phone,
                    email: email
                })
            })
            .then(res => {
                if (!res.ok) throw new Error('Order creation failed');
                return res.json();
            })
            .then(data => {
                if (data.is_mock || typeof Razorpay === 'undefined') {
                    this.pujaPaymentId = 'pay_PUJA_' + Math.random().toString(36).substring(2, 10).toUpperCase();
                    this.pujaOrderId = data.order_id || 'order_mock';
                    this.pujaSignature = 'mock_signature';
                    this.$nextTick(() => {
                        form.submit();
                    });
                    return;
                }

                const options = {
                    key: data.key,
                    amount: data.amount,
                    currency: data.currency || 'INR',
                    name: data.name || 'Haridwar Bliss - Vedic Puja',
                    description: data.description || this.selectedPackage,
                    order_id: data.order_id,
                    prefill: data.prefill,
                    theme: { color: '#881337' },
                    handler: (response) => {
                        this.pujaPaymentId = response.razorpay_payment_id;
                        this.pujaOrderId = response.razorpay_order_id;
                        this.pujaSignature = response.razorpay_signature;
                        this.$nextTick(() => {
                            form.submit();
                        });
                    },
                    modal: {
                        ondismiss: () => {
                            this.isPujaProcessing = false;
                        }
                    }
                };

                const rzp = new Razorpay(options);
                rzp.open();
            })
            .catch(err => {
                console.error('Puja Razorpay Error:', err);
                this.isPujaProcessing = false;
                alert('Razorpay payment gateway failed to initialize. Please try again.');
            });
        }
    }" @open-puja-booking.window="openBookingModal($event.detail?.package)"
        @keydown.escape.window="if(isBookingModalOpen) closeBookingModal()">

        <!-- Floating Quick Language Switcher (Only for this page, sticky at bottom-left) -->
        <div
            class="fixed bottom-6 left-6 z-40 hidden sm:flex items-center gap-2 p-1.5 bg-[#25050A]/90 text-white rounded-full border border-amber-400/50 shadow-2xl backdrop-blur-md">
            <span class="text-xs font-medium pl-3 text-amber-200/90 flex items-center gap-1.5 font-mono">
                <span>🌐</span>
                <span x-text="lang === 'en' ? 'Language:' : 'भाषा:'"></span>
            </span>
            <div class="flex items-center gap-1">
                <button type="button" @click="setLang('en')"
                    :class="lang === 'en' ? 'bg-[#D97706] text-white shadow-xs font-bold' : 'text-amber-100 hover:text-white font-medium'"
                    class="px-3 py-1 rounded-full text-xs transition cursor-pointer">
                    English
                </button>
                <button type="button" @click="setLang('hi')"
                    :class="lang === 'hi' ? 'bg-[#D97706] text-white shadow-xs font-bold' : 'text-amber-100 hover:text-white font-medium'"
                    class="px-3 py-1 rounded-full text-xs transition cursor-pointer">
                    हिन्दी
                </button>
            </div>
        </div>

        <!-- 1. Sacred Vedic Hero Banner -->
        <section
            class="relative bg-gradient-to-b from-[#25050A] via-[#380B12] to-[#25050A] text-white overflow-hidden py-14 sm:py-20 border-b border-amber-300/30">
            <!-- Authentic Haridwar Ganga Ghat Background Image -->
            <!-- <img src="{{ asset('images/haridwar-panorama-clocktower.jpg') }}" alt="Sacred Haridwar Ganga Ghats"
                class="absolute inset-0 w-full h-full object-cover opacity-25 object-center"> -->
            <!-- <div
                class="absolute inset-0 bg-gradient-to-t from-[#25050A] via-[#25050A]/75 to-transparent pointer-events-none">
            </div> -->

            <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-6">

                <!-- Language Switcher Bar in Hero Banner -->
                <div class="flex items-center justify-center">
                    <div
                        class="inline-flex items-center gap-2 p-1.5 bg-black/50 border border-amber-400/60 rounded-full backdrop-blur-md shadow-xl">
                        <span class="text-xs font-semibold pl-3 text-amber-200/90 flex items-center gap-1.5 font-mono">
                            <span>🌐</span>
                            <span x-show="lang === 'en'">Language / भाषा:</span>
                            <span x-show="lang === 'hi'" x-cloak>भाषा चयन / Language:</span>
                        </span>
                        <div class="flex items-center gap-1">
                            <button type="button" @click="setLang('en')"
                                :class="lang === 'en' ? 'bg-[#D97706] text-white shadow-md font-bold' : 'text-amber-200/80 hover:text-white font-medium'"
                                class="px-4 py-1.5 rounded-full text-xs sm:text-sm transition-all cursor-pointer flex items-center gap-1.5">
                                <span>English</span>
                            </button>
                            <button type="button" @click="setLang('hi')"
                                :class="lang === 'hi' ? 'bg-[#D97706] text-white shadow-md font-bold' : 'text-amber-200/80 hover:text-white font-medium'"
                                class="px-4 py-1.5 rounded-full text-xs sm:text-sm transition-all cursor-pointer flex items-center gap-1.5">
                                <span>हिन्दी</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Vedic Sloka Banner -->
                <div
                    class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs sm:text-sm font-semibold bg-[#D97706]/20 text-amber-200 border border-[#D97706]/40 backdrop-blur-xs shadow-inner max-w-2xl mx-auto">
                    <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse shrink-0"></span>
                    <span x-show="lang === 'en'" class="tracking-wide text-xs sm:text-[13px] leading-relaxed">
                        "O Holy Rivers Ganga, Yamuna, Godavari, Saraswati, Narmada, Sindhu, &amp; Kaveri: Bless this
                        water with your sacred presence."
                    </span>
                    <span x-show="lang === 'hi'" x-cloak
                        class="tracking-wide text-xs sm:text-[13px] leading-[1.8] font-medium">
                        गङ्गे च यमुने चैव गोदावरि सरस्वति । नर्मदे सिन्धु कावेरि जलेऽस्मिन् सन्निधिं कुरु ॥
                    </span>
                </div>

                <!-- Page Title -->
                <div class="space-y-3 max-w-3xl mx-auto">
                    <!-- English Title -->
                    <h1 x-show="lang === 'en'"
                        class="text-3xl sm:text-4xl lg:text-[32px] font-serif font-extrabold text-white tracking-tight leading-snug drop-shadow-[0_2px_4px_rgba(0,0,0,0.6)]">
                        Sacred Haridwar Ghats Online Puja &amp; Vedic Rituals
                    </h1>
                    <!-- Hindi Title with Fixed Generous Line Height -->
                    <h1 x-show="lang === 'hi'" x-cloak
                        class="text-3xl sm:text-3xl lg:text-[32px] font-serif font-extrabold text-white tracking-tight hindi-title drop-shadow-[0_2px_4px_rgba(0,0,0,0.6)]">
                        हरिद्वार पावन घाट ऑनलाइन पूजा एवं वैदिक अनुष्ठान
                    </h1>

                    <!-- English Subtitle -->
                    <p x-show="lang === 'en'"
                        class="text-sm sm:text-base text-amber-100/90 leading-relaxed font-normal max-w-2xl mx-auto">
                        Authentic personalized Vedic Sankalp performed in your name and Gotra directly from the sacred
                        Ganga Ghats of Haridwar by learned Vedic Acharyas. Includes 15–20 minute private live video
                        streaming, followed by consecrated Prasad and pure 500ml Blu Bliss Gangajal doorstep delivery.
                    </p>
                    <!-- Hindi Subtitle with Fixed Generous Line Height -->
                    <p x-show="lang === 'hi'" x-cloak
                        class="text-xs sm:text-sm text-amber-100/90 hindi-subtitle font-normal max-w-2xl mx-auto">
                        हरिद्वार के पावन गंगा घाटों से सीधे आपके नाम और गोत्र से वैदिक आचार्यों द्वारा संकल्पित लाइव
                        पूजन,
                        15–20 मिनट की व्यक्तिगत लाइव स्ट्रीमिंग, तथा घर पर अभिमंत्रित प्रसाद व 500ml गंगाजल की पावन
                        डिलीवरी।
                    </p>
                </div>

                <!-- 4 Trust Markers Grid -->
                <div class="pt-2  grid grid-cols-2 sm:grid-cols-4 gap-3 max-w-4xl mx-auto text-left hidden">
                    <!-- Marker 1 -->
                    <div class="p-3.5 bg-white/5 border border-amber-300/20 rounded-2xl backdrop-blur-xs">
                        <span class="text-xl">🪔</span>
                        <div x-show="lang === 'en'">
                            <h2 class="text-xs font-bold text-amber-300 mt-1">Personalized Sankalp</h2>
                            <p class="text-[11px] text-amber-100/80 leading-snug mt-0.5">Recitation of your Name, Gotra
                                &amp; Auspicious Occasion.</p>
                        </div>
                        <div x-show="lang === 'hi'" x-cloak>
                            <h2 class="text-xs font-bold text-amber-300 mt-1">व्यक्तिगत वैदिक संकल्प</h2>
                            <p class="text-[11px] text-amber-100/80 leading-[1.65] mt-0.5">नाम, गोत्र व जन्म/विवाह तिथि
                                का स्पष्ट उच्चारण।</p>
                        </div>
                    </div>

                    <!-- Marker 2 -->
                    <div class="p-3.5 bg-white/5 border border-amber-300/20 rounded-2xl backdrop-blur-xs">
                        <span class="text-xl">📹</span>
                        <div x-show="lang === 'en'">
                            <h2 class="text-xs font-bold text-amber-300 mt-1">15–20 Min Live Stream</h2>
                            <p class="text-[11px] text-amber-100/80 leading-snug mt-0.5">Live video stream on Zoom /
                                WhatsApp with family.</p>
                        </div>
                        <div x-show="lang === 'hi'" x-cloak>
                            <h2 class="text-xs font-bold text-amber-300 mt-1">15-20 Min लाइव स्ट्रीमिंग</h2>
                            <p class="text-[11px] text-amber-100/80 leading-[1.65] mt-0.5">Zoom / WhatsApp Video पर
                                सपरिवार सीधा दर्शन।</p>
                        </div>
                    </div>

                    <!-- Marker 3 -->
                    <div class="p-3.5 bg-white/5 border border-amber-300/20 rounded-2xl backdrop-blur-xs">
                        <span class="text-xl">📦</span>
                        <div x-show="lang === 'en'">
                            <h2 class="text-xs font-bold text-amber-300 mt-1">Blessed Prasad Delivery</h2>
                            <p class="text-[11px] text-amber-100/80 leading-snug mt-0.5">Blessed 500ml Gangajal, Raksha
                                Sutra &amp; Panchmeva.</p>
                        </div>
                        <div x-show="lang === 'hi'" x-cloak>
                            <h2 class="text-xs font-bold text-amber-300 mt-1">डोरस्टेप मंगल प्रसाद</h2>
                            <p class="text-[11px] text-amber-100/80 leading-[1.65] mt-0.5">अभिमंत्रित 500ml गंगाजल,
                                मौली, रोली व सूखा पंचमेवा।</p>
                        </div>
                    </div>

                    <!-- Marker 4 -->
                    <div class="p-3.5 bg-white/5 border border-amber-300/20 rounded-2xl backdrop-blur-xs">
                        <span class="text-xl">📜</span>
                        <div x-show="lang === 'en'">
                            <h2 class="text-xs font-bold text-amber-300 mt-1">Blessing Certificate</h2>
                            <p class="text-[11px] text-amber-100/80 leading-snug mt-0.5">Edited commemorative HD video
                                &amp; Certificate.</p>
                        </div>
                        <div x-show="lang === 'hi'" x-cloak>
                            <h2 class="text-xs font-bold text-amber-300 mt-1">डिजिटल संकल्प प्रमाण-पत्र</h2>
                            <p class="text-[11px] text-amber-100/80 leading-[1.65] mt-0.5">संपादित HD वीडियो क्लिप व
                                Blessing Certificate।</p>
                        </div>
                    </div>
                </div>

                <!-- Quick Action Buttons -->
                <div class="pt-2 flex flex-wrap items-center justify-center gap-3">
                    <a href="#puja-packages"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-[#D97706] hover:bg-[#F97316] text-white rounded-xl font-bold text-sm shadow-xl transition transform hover:-translate-y-0.5 cursor-pointer">
                        <span x-show="lang === 'en'">Explore Sacred Packages</span>
                        <span x-show="lang === 'hi'" x-cloak>पावन पूजा पैकेज देखें</span>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                        </svg>
                    </a>
                    <button type="button" @click="openBookingModal()"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-white/10 hover:bg-white/20 text-white border border-white/20 rounded-xl font-bold text-sm backdrop-blur-xs transition cursor-pointer">
                        <span>🕉️</span>
                        <span x-show="lang === 'en'">Book Your Sankalp Now</span>
                        <span x-show="lang === 'hi'" x-cloak>सीधा संकल्प फॉर्म भरें</span>
                    </button>
                </div>
            </div>
        </section>

        <!-- Main Body Container -->
        <div class="bg-[#FFFDF7] text-slate-800">

            <!-- 2. Sacred Puja Packages Section -->
            <section id="puja-packages"
                class="py-14 sm:py-20 border-b border-amber-200/60 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-12 space-y-3">
                    <span
                        class="inline-block px-3.5 py-1 rounded-full text-xs font-bold bg-[#D97706]/15 text-[#881337] border border-[#D97706]/30 uppercase tracking-wider font-mono">
                        Sacred Vedic Puja Offerings
                    </span>

                    <!-- English Section Title -->
                    <h2 x-show="lang === 'en'"
                        class="text-2xl sm:text-4xl font-serif font-extrabold text-slate-900 leading-snug">
                        Haridwar Sacred Ghat Special Puja Packages
                    </h2>
                    <!-- Hindi Section Title with Proper Line Height -->
                    <h2 x-show="lang === 'hi'" x-cloak
                        class="text-2xl sm:text-4xl font-serif font-extrabold text-slate-900 hindi-title">
                        हरिद्वार पावन घाट विशेष पूजा पैकेज
                    </h2>

                    <!-- English Subtitle -->
                    <p x-show="lang === 'en'" class="text-sm text-slate-600 leading-relaxed max-w-xl mx-auto">
                        Select the auspicious ritual tailored to your date and occasion. Every puja is consecrated
                        according to authentic Vedic scriptures by revered Haridwar Tirtha Purohits.
                    </p>
                    <!-- Hindi Subtitle with Proper Line Height -->
                    <p x-show="lang === 'hi'" x-cloak
                        class="text-xs sm:text-sm text-slate-600 hindi-text max-w-xl mx-auto">
                        अपनी तिथि और अवसर के अनुसार उपयुक्त अनुष्ठान का चयन करें। प्रत्येक पूजा हरिद्वार के विद्वान
                        तीर्थ पुरोहितों द्वारा वैदिक विधि से संपन्न की जाती है।
                    </p>
                </div>

                <!-- Two Puja Package Cards Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-stretch">

                    <!-- PACKAGE 1: Mangal Utsav & Ayushman Sankalp Puja -->
                    <div
                        class="relative bg-white rounded-3xl border-2 border-amber-200 hover:border-amber-400 p-6 sm:p-8 shadow-xl shadow-amber-950/5 hover:shadow-2xl hover:shadow-amber-900/10 transition-all duration-300 flex flex-col justify-between">
                        <!-- Top Ribbon & Heading -->
                        <div>
                            <div class="flex items-center justify-between gap-3 mb-4">
                                <span
                                    class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-900 border border-amber-200">
                                    <span>🎉</span>
                                    <span x-show="lang === 'en'">Family &amp; Personal Auspicious Occasions</span>
                                    <span x-show="lang === 'hi'" x-cloak>पारिवारिक व व्यक्तिगत शुभ अवसर</span>
                                </span>
                                <span class="text-xs font-bold text-slate-400 font-mono">Package 01</span>
                            </div>

                            <!-- Package 1 Title (English / Hindi) -->
                            <h3 x-show="lang === 'en'"
                                class="text-xl sm:text-2xl font-serif font-bold text-[#881337] leading-snug">
                                Package 1: "Mangal Utsav &amp; Ayushman Sankalp Puja"
                            </h3>
                            <h3 x-show="lang === 'hi'" x-cloak
                                class="text-xl sm:text-2xl font-serif font-bold text-[#881337] hindi-title">
                                पैकेज 1: "मंगल उत्सव एवं आयुष्मान संकल्प पूजा"
                            </h3>

                            <!-- Package 1 Subtitle -->
                            <p x-show="lang === 'en'" class="text-xs sm:text-sm text-slate-600 mt-1 font-medium">
                                (For Birthdays, Wedding Anniversaries &amp; Auspicious Family Milestones)
                            </p>
                            <p x-show="lang === 'hi'" x-cloak
                                class="text-xs sm:text-sm text-slate-600 mt-1 font-medium hindi-subtitle">
                                (जन्मदिन, विवाह वर्षगांठ एवं पारिवारिक शुभ अवसरों के लिए)
                            </p>

                            <!-- Pricing -->
                            <div class="mt-4 pb-5 border-b border-amber-100 flex items-baseline gap-2">
                                <span
                                    class="text-3xl sm:text-4xl font-serif font-extrabold text-[#D97706]">₹5,100</span>
                                <span x-show="lang === 'en'" class="text-xs text-slate-500 font-medium">/ Complete
                                    Ritual Dakshina</span>
                                <span x-show="lang === 'hi'" x-cloak class="text-xs text-slate-500 font-medium">/
                                    संपूर्ण अनुष्ठान दक्षिणा</span>
                            </div>

                            <!-- 1. Rituals Section -->
                            <div class="mt-6 space-y-4">
                                <h4
                                    class="text-xs font-bold uppercase tracking-wider text-[#881337] flex items-center gap-2 font-mono">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#D97706]"></span>
                                    <span x-show="lang === 'en'">Rituals Performed</span>
                                    <span x-show="lang === 'hi'" x-cloak>किए जाने वाले अनुष्ठान (Rituals)</span>
                                </h4>

                                <!-- English Rituals -->
                                <ul x-show="lang === 'en'"
                                    class="space-y-3 text-xs sm:text-[13px] text-slate-700 leading-relaxed">
                                    <li class="flex items-start gap-2.5">
                                        <span class="text-amber-600 mt-0.5 shrink-0">✦</span>
                                        <div>
                                            <strong class="text-slate-900 font-semibold">Personalized Vedic
                                                Sankalp:</strong>
                                            Solemn recitation of your (or couple/family's) full name, Gotra, and
                                            birth/anniversary date by learned Vedic Acharyas directly at Haridwar Ganga
                                            Ghat.
                                        </div>
                                    </li>
                                    <li class="flex items-start gap-2.5">
                                        <span class="text-amber-600 mt-0.5 shrink-0">✦</span>
                                        <div>
                                            <strong class="text-slate-900 font-semibold">Ayushya Sukta &amp; Saubhagya
                                                Chanting:</strong>
                                            Sacred Ayushya Sukta chanting for longevity &amp; vibrant health on
                                            birthdays; dedicated Maa Ganga &amp; Gauri-Shankar archana for marital bliss
                                            &amp; prosperity on anniversaries.
                                        </div>
                                    </li>
                                    <li class="flex items-start gap-2.5">
                                        <span class="text-amber-600 mt-0.5 shrink-0">✦</span>
                                        <div>
                                            <strong class="text-slate-900 font-semibold">Sacred Dugdhabhishek &amp; Deep
                                                Daan:</strong>
                                            Panchamrit and milk oblation to Holy Mother Ganga, floating of 21 pure ghee
                                            earthen lamps (Deep Daan), and dedicated Ganga Aarti.
                                        </div>
                                    </li>
                                </ul>

                                <!-- Hindi Rituals with Generous Line Height -->
                                <ul x-show="lang === 'hi'" x-cloak
                                    class="space-y-3.5 text-xs sm:text-[13px] text-slate-700 hindi-text">
                                    <li class="flex items-start gap-2.5">
                                        <span class="text-amber-600 mt-0.5 shrink-0">✦</span>
                                        <div>
                                            <strong class="text-slate-900 font-semibold">विशेष संकल्प:</strong>
                                            हरिद्वार के पावन घाट पर आपके (या दंपति/परिवार के) पूरे नाम, गोत्र और जन्म
                                            तिथि/वर्षगांठ के उच्चारण के साथ वैदिक आचार्यों द्वारा संकल्प।
                                        </div>
                                    </li>
                                    <li class="flex items-start gap-2.5">
                                        <span class="text-amber-600 mt-0.5 shrink-0">✦</span>
                                        <div>
                                            <strong class="text-slate-900 font-semibold">आयुष्य एवं सौभाग्य
                                                पाठ:</strong>
                                            जन्मदिन पर दीर्घायु और उत्तम स्वास्थ्य के लिए आयुष्य सूक्त पाठ, तथा विवाह
                                            वर्षगांठ पर अखंड सौभाग्य और सुख-समृद्धि के लिए माँ गंगा व गौरी-शंकर का विशेष
                                            पूजन।
                                        </div>
                                    </li>
                                    <li class="flex items-start gap-2.5">
                                        <span class="text-amber-600 mt-0.5 shrink-0">✦</span>
                                        <div>
                                            <strong class="text-slate-900 font-semibold">पावन दुग्धाभिषेक एवं
                                                दीपदान:</strong>
                                            माँ गंगा को पुष्प, दूध और शुद्ध घी के 21 दीपों का दीपदान तथा विशेष आरती।
                                        </div>
                                    </li>
                                </ul>
                            </div>

                            <!-- 2. Live Streaming Box -->
                            <div class="mt-6 p-4 bg-amber-50/60 rounded-2xl border border-amber-200/80 space-y-1.5">
                                <h4 class="text-xs font-bold text-[#881337] flex items-center gap-2">
                                    <span>📹</span>
                                    <span x-show="lang === 'en'">Live Video Streaming</span>
                                    <span x-show="lang === 'hi'" x-cloak>लाइव स्ट्रीमिंग (Live Streaming)</span>
                                </h4>
                                <p x-show="lang === 'en'" class="text-xs text-slate-700 leading-relaxed">
                                    <strong>15 to 20 minutes of private, interactive live video streaming</strong> (via
                                    Zoom, WhatsApp Video, or Private Link) so you and your family can witness the sacred
                                    ghat rituals and participate in the live Sankalp in real-time from home.
                                </p>
                                <p x-show="lang === 'hi'" x-cloak class="text-xs text-slate-700 hindi-text">
                                    पूजा के दौरान <strong>15 से 20 मिनट की व्यक्तिगत लाइव स्ट्रीमिंग</strong> (Zoom /
                                    WhatsApp Video / Private Link के माध्यम से), ताकि आप और आपका परिवार घर बैठे सीधे घाट
                                    के दर्शन और लाइव संकल्प में शामिल हो सकें।
                                </p>
                            </div>
                        </div>

                        <!-- CTA Actions (Triggers Popup Modal on Click) -->
                        <div class="mt-8 pt-6 border-t border-slate-100 flex flex-col sm:flex-row items-center gap-3">
                            <button type="button"
                                @click="openBookingModal('पैकेज 1: मंगल उत्सव एवं आयुष्मान संकल्प पूजा (₹5,100/-)')"
                                class="w-full sm:flex-1 py-3 px-5 bg-[#D97706] hover:bg-[#F97316] text-white rounded-xl font-bold text-xs sm:text-sm text-center shadow-lg transition transform hover:-translate-y-0.5 cursor-pointer flex items-center justify-center gap-2">
                                <span>🕉️</span>
                                <span x-show="lang === 'en'">Book Sankalp · Package 1 (@₹5,100)</span>
                                <span x-show="lang === 'hi'" x-cloak>संकल्प लें · Book Package 1 (@₹5,100)</span>
                            </button>
                            <a href="https://wa.me/917042482555?text={{ urlencode('Jai Gange! I want to inquire and book Package 1: Mangal Utsav & Ayushman Sankalp Puja (₹5,100).') }}"
                                target="_blank" rel="noopener"
                                class="w-full sm:w-auto py-3 px-4 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-xs inline-flex items-center justify-center gap-1.5 shadow-sm transition">
                                <span x-show="lang === 'en'">💬 Ask on WhatsApp</span>
                                <span x-show="lang === 'hi'" x-cloak>💬 WhatsApp पर पूछें</span>
                            </a>
                        </div>
                    </div>

                    <!-- PACKAGE 2: Maha-Tithi & Divine Festival Special Ritual -->
                    <div
                        class="relative bg-white rounded-3xl border-2 border-[#881337]/40 hover:border-[#881337] p-6 sm:p-8 shadow-xl shadow-rose-950/5 hover:shadow-2xl hover:shadow-rose-900/10 transition-all duration-300 flex flex-col justify-between">
                        <!-- Featured Badge -->
                        <div
                            class="absolute -top-3.5 right-6 bg-[#881337] text-white text-[11px] font-bold px-3 py-1 rounded-full shadow-md tracking-wide">
                            <span x-show="lang === 'en'">🌟 Rare Festival Ritual</span>
                            <span x-show="lang === 'hi'" x-cloak>🌟 महा-पर्व विशेष अनुष्ठान</span>
                        </div>

                        <!-- Top Ribbon & Heading -->
                        <div>
                            <div class="flex items-center justify-between gap-3 mb-4">
                                <span
                                    class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-rose-50 text-[#881337] border border-rose-200">
                                    <span>🔱</span>
                                    <span x-show="lang === 'en'">Auspicious Astrological Muhurtas &amp; Festivals</span>
                                    <span x-show="lang === 'hi'" x-cloak>दुर्लभ मुहूर्त एवं पावन पर्व</span>
                                </span>
                                <span class="text-xs font-bold text-slate-400 font-mono">Package 02</span>
                            </div>

                            <!-- Package 2 Title (English / Hindi) -->
                            <h3 x-show="lang === 'en'"
                                class="text-xl sm:text-2xl font-serif font-bold text-[#881337] leading-snug">
                                Package 2: "Maha-Tithi &amp; Divine Festival Special Ritual"
                            </h3>
                            <h3 x-show="lang === 'hi'" x-cloak
                                class="text-xl sm:text-2xl font-serif font-bold text-[#881337] hindi-title">
                                पैकेज 2: "महा-तिथि एवं पर्व विशेष दिव्य अनुष्ठान"
                            </h3>

                            <!-- Package 2 Subtitle -->
                            <p x-show="lang === 'en'" class="text-xs sm:text-sm text-slate-600 mt-1 font-medium">
                                (For Amavasya, Purnima, Eclipse Periods, Ekadashi &amp; Sacred Astrological Days)
                            </p>
                            <p x-show="lang === 'hi'" x-cloak
                                class="text-xs sm:text-sm text-slate-600 mt-1 font-medium hindi-subtitle">
                                (अमावस्या, पूर्णिमा, ग्रहण काल, एकादशी एवं अन्य विशेष पर्वों के लिए)
                            </p>

                            <!-- Pricing -->
                            <div class="mt-4 pb-5 border-b border-rose-100 flex items-baseline gap-2">
                                <span
                                    class="text-3xl sm:text-4xl font-serif font-extrabold text-[#881337]">₹11,000</span>
                                <span x-show="lang === 'en'" class="text-xs text-slate-500 font-medium">/ Complete
                                    Maha-Anushthan Dakshina</span>
                                <span x-show="lang === 'hi'" x-cloak class="text-xs text-slate-500 font-medium">/
                                    संपूर्ण महा-अनुष्ठान दक्षिणा</span>
                            </div>

                            <!-- 1. Rituals Section -->
                            <div class="mt-6 space-y-4">
                                <h4
                                    class="text-xs font-bold uppercase tracking-wider text-[#881337] flex items-center gap-2 font-mono">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#881337]"></span>
                                    <span x-show="lang === 'en'">Rituals Performed</span>
                                    <span x-show="lang === 'hi'" x-cloak>किए जाने वाले अनुष्ठान (Rituals)</span>
                                </h4>

                                <!-- English Rituals -->
                                <ul x-show="lang === 'en'"
                                    class="space-y-3 text-xs sm:text-[13px] text-slate-700 leading-relaxed">
                                    <li class="flex items-start gap-2.5">
                                        <span class="text-[#881337] mt-0.5 shrink-0">✦</span>
                                        <div>
                                            <strong class="text-slate-900 font-semibold">Festival-Specific Vedic
                                                Sankalp:</strong>
                                            Vedic rituals customized to the sacred day (e.g., Satyanarayan &amp; Ganga
                                            Puja on Purnima, Pitru Shanti on Amavasya, or Shuddhi &amp; planetary peace
                                            chanting during Eclipses).
                                        </div>
                                    </li>
                                    <li class="flex items-start gap-2.5">
                                        <span class="text-[#881337] mt-0.5 shrink-0">✦</span>
                                        <div>
                                            <strong class="text-slate-900 font-semibold">Planetary Remedy &amp; Energy
                                                Purification:</strong>
                                            Vedic mantra japa and consecrated Havan ahuti to alleviate planetary
                                            afflictions, anxiety, and negative household energies.
                                        </div>
                                    </li>
                                    <li class="flex items-start gap-2.5">
                                        <span class="text-[#881337] mt-0.5 shrink-0">✦</span>
                                        <div>
                                            <strong class="text-slate-900 font-semibold">Ganga Aarti &amp; Brahmin
                                                Oblation:</strong>
                                            Consecrated flower offerings, sacred Deep Daan lamps floating on the holy
                                            Ganges, and personal prayers offered by revered Brahmins.
                                        </div>
                                    </li>
                                </ul>

                                <!-- Hindi Rituals with Generous Line Height -->
                                <ul x-show="lang === 'hi'" x-cloak
                                    class="space-y-3.5 text-xs sm:text-[13px] text-slate-700 hindi-text">
                                    <li class="flex items-start gap-2.5">
                                        <span class="text-[#881337] mt-0.5 shrink-0">✦</span>
                                        <div>
                                            <strong class="text-slate-900 font-semibold">तिथि-विशेष संकल्प:</strong>
                                            संबंधित पर्व (जैसे पूर्णिमा पर सत्यनारायण व माँ गंगा पूजन, अमावस्या पर पितृ
                                            शांति व कष्ट निवारण संकल्प, या ग्रहण काल में शुद्धि व शांति पाठ) के अनुसार
                                            वैदिक विधि से पूजा।
                                        </div>
                                    </li>
                                    <li class="flex items-start gap-2.5">
                                        <span class="text-[#881337] mt-0.5 shrink-0">✦</span>
                                        <div>
                                            <strong class="text-slate-900 font-semibold">ग्रह दोष एवं नकारात्मकता
                                                निवारण:</strong>
                                            कुंडली के दोषों, मानसिक अशांति और घर की नकारात्मक ऊर्जा को दूर करने के लिए
                                            विशेष वैदिक मंत्र जाप और हवन आहुति।
                                        </div>
                                    </li>
                                    <li class="flex items-start gap-2.5">
                                        <span class="text-[#881337] mt-0.5 shrink-0">✦</span>
                                        <div>
                                            <strong class="text-slate-900 font-semibold">गंगा आरती एवं ब्रह्मलीन
                                                अर्पण:</strong>
                                            घाट पर ब्राह्मणों द्वारा आपके नाम से दीपदान, पुष्प अर्पण और विशेष प्रार्थना।
                                        </div>
                                    </li>
                                </ul>
                            </div>

                            <!-- 2. Live Streaming Box -->
                            <div class="mt-6 p-4 bg-rose-50/70 rounded-2xl border border-rose-200/80 space-y-1.5">
                                <h4 class="text-xs font-bold text-[#881337] flex items-center gap-2">
                                    <span>📹</span>
                                    <span x-show="lang === 'en'">Live Video Streaming</span>
                                    <span x-show="lang === 'hi'" x-cloak>लाइव स्ट्रीमिंग (Live Streaming)</span>
                                </h4>
                                <p x-show="lang === 'en'" class="text-xs text-slate-700 leading-relaxed">
                                    <strong>Direct high-definition live video streaming</strong> from Haridwar Ghat
                                    during the rare astrological window, letting you and your family immerse in the
                                    divine vibrations and sacred Aarti from anywhere in the world.
                                </p>
                                <p x-show="lang === 'hi'" x-cloak class="text-xs text-slate-700 hindi-text">
                                    पावन तिथि पर घाट से <strong>सीधी लाइव स्ट्रीमिंग (Live Streaming)</strong>, जिससे आप
                                    घर बैठे ही उस दुर्लभ मुहूर्त में घाट की दिव्य ऊर्जा और आरती के साक्षी बन सकें।
                                </p>
                            </div>
                        </div>

                        <!-- CTA Actions (Triggers Popup Modal on Click) -->
                        <div class="mt-8 pt-6 border-t border-slate-100 flex flex-col sm:flex-row items-center gap-3">
                            <button type="button"
                                @click="openBookingModal('पैकेज 2: महा-तिथि एवं पर्व विशेष दिव्य अनुष्ठान (₹11,000/-)')"
                                class="w-full sm:flex-1 py-3 px-5 bg-[#881337] hover:bg-[#701A28] text-white rounded-xl font-bold text-xs sm:text-sm text-center shadow-lg transition transform hover:-translate-y-0.5 cursor-pointer flex items-center justify-center gap-2">
                                <span>🔱</span>
                                <span x-show="lang === 'en'">Book Maha-Sankalp · Package 2 (@₹11,000)</span>
                                <span x-show="lang === 'hi'" x-cloak>महा-संकल्प लें · Book Package 2 (@₹11,000)</span>
                            </button>
                            <a href="https://wa.me/917042482555?text={{ urlencode('Jai Gange! I want to inquire about Package 2: Maha-Tithi & Divine Festival Special Ritual (₹11,000).') }}"
                                target="_blank" rel="noopener"
                                class="w-full sm:w-auto py-3 px-4 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-xs inline-flex items-center justify-center gap-1.5 shadow-sm transition">
                                <span x-show="lang === 'en'">💬 Ask on WhatsApp</span>
                                <span x-show="lang === 'hi'" x-cloak>💬 WhatsApp पर पूछें</span>
                            </a>
                        </div>
                    </div>

                </div>
            </section>

            <!-- 3. How It Works - 4 Steps -->
            <section class="py-14 sm:py-16 bg-[#FFFBEB] border-b border-amber-200/60">
                <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center max-w-2xl mx-auto mb-10 space-y-2">
                        <span class="text-xs font-extrabold text-[#881337] uppercase tracking-wider font-mono">Simple
                            &amp; Pure</span>

                        <!-- English Heading -->
                        <h2 x-show="lang === 'en'" class="text-2xl sm:text-3xl font-serif font-bold text-slate-900">
                            Simple Process from Sankalp to Prasad Delivery
                        </h2>
                        <!-- Hindi Heading with Proper Line Height -->
                        <h2 x-show="lang === 'hi'" x-cloak
                            class="text-2xl sm:text-3xl font-serif font-bold text-slate-900 hindi-title">
                            संकल्प से प्रसाद प्राप्ति तक की सरल प्रक्रिया
                        </h2>

                        <!-- Subtitle -->
                        <p x-show="lang === 'en'" class="text-xs sm:text-sm text-slate-600">
                            Book your authentic Vedic Sankalp from home in just a few minutes:
                        </p>
                        <p x-show="lang === 'hi'" x-cloak class="text-xs sm:text-sm text-slate-600 hindi-text">
                            घर बैठे कुछ ही मिनटों में अपना व्यक्तिगत वैदिक संकल्प बुक करें:
                        </p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        <!-- Step 1 -->
                        <div class="p-5 bg-white rounded-2xl border border-amber-200 shadow-sm space-y-2 relative">
                            <span
                                class="w-7 h-7 rounded-full bg-[#D97706] text-white text-xs font-bold flex items-center justify-center">1</span>
                            <div x-show="lang === 'en'">
                                <h4 class="text-sm font-bold text-slate-900">Enter Sankalp Details</h4>
                                <p class="text-xs text-slate-600 leading-relaxed">
                                    Select your puja package, enter the devotee's full name, Gotra, auspicious date, and
                                    postal delivery address.
                                </p>
                            </div>
                            <div x-show="lang === 'hi'" x-cloak>
                                <h4 class="text-sm font-bold text-slate-900">संकल्प विवरण दर्ज करें</h4>
                                <p class="text-xs text-slate-600 hindi-text">
                                    अपना पैकेज चुनें तथा यजमान का पूरा नाम, गोत्र, शुभ तिथि और प्रसाद भेजने का पता भरें।
                                </p>
                            </div>
                        </div>

                        <!-- Step 2 -->
                        <div class="p-5 bg-white rounded-2xl border border-amber-200 shadow-sm space-y-2 relative">
                            <span
                                class="w-7 h-7 rounded-full bg-[#D97706] text-white text-xs font-bold flex items-center justify-center">2</span>
                            <div x-show="lang === 'en'">
                                <h4 class="text-sm font-bold text-slate-900">Receive Live Stream Link</h4>
                                <p class="text-xs text-slate-600 leading-relaxed">
                                    Prior to the puja, our team sends your private Zoom / WhatsApp Video link via
                                    WhatsApp and email.
                                </p>
                            </div>
                            <div x-show="lang === 'hi'" x-cloak>
                                <h4 class="text-sm font-bold text-slate-900">लाइव स्ट्रीमिंग लिंक</h4>
                                <p class="text-xs text-slate-600 hindi-text">
                                    पूजा से पूर्व आपके WhatsApp और ईमेल पर लाइव घाट दर्शन व संकल्प का व्यक्तिगत Zoom /
                                    Video लिंक भेजा जाएगा।
                                </p>
                            </div>
                        </div>

                        <!-- Step 3 -->
                        <div class="p-5 bg-white rounded-2xl border border-amber-200 shadow-sm space-y-2 relative">
                            <span
                                class="w-7 h-7 rounded-full bg-[#D97706] text-white text-xs font-bold flex items-center justify-center">3</span>
                            <div x-show="lang === 'en'">
                                <h4 class="text-sm font-bold text-slate-900">Live Puja at Haridwar Ghat</h4>
                                <p class="text-xs text-slate-600 leading-relaxed">
                                    Vedic priests chant your personal Sankalp, followed by sacred Dugdhabhishek, Deep
                                    Daan, and Ganga Aarti.
                                </p>
                            </div>
                            <div x-show="lang === 'hi'" x-cloak>
                                <h4 class="text-sm font-bold text-slate-900">हरिद्वार घाट लाइव पूजन</h4>
                                <p class="text-xs text-slate-600 hindi-text">
                                    विद्वान पुरोहित आपके नाम-गोत्र से संकल्प लेंगे। माँ गंगा की विशेष आरती, दुग्धाभिषेक
                                    व दीपदान होगा।
                                </p>
                            </div>
                        </div>

                        <!-- Step 4 -->
                        <div class="p-5 bg-white rounded-2xl border border-amber-200 shadow-sm space-y-2 relative">
                            <span
                                class="w-7 h-7 rounded-full bg-[#D97706] text-white text-xs font-bold flex items-center justify-center">4</span>
                            <div x-show="lang === 'en'">
                                <h4 class="text-sm font-bold text-slate-900">Prasad &amp; Gangajal Delivery</h4>
                                <p class="text-xs text-slate-600 leading-relaxed">
                                    Post-puja, blessed 500ml Blu Bliss Gangajal, Raksha Sutra, and sacred dry fruit
                                    prasad are dispatched to your doorstep.
                                </p>
                            </div>
                            <div x-show="lang === 'hi'" x-cloak>
                                <h4 class="text-sm font-bold text-slate-900">प्रसाद व गंगाजल डिलीवरी</h4>
                                <p class="text-xs text-slate-600 hindi-text">
                                    पूजा उपरांत अभिमंत्रित 500ml Blu Bliss गंगाजल, मंगल रक्षा सूत्र व पावन प्रसाद बॉक्स
                                    आपके घर कूरियर होगा।
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 4. Dedicated Sacred Booking CTA Section (Triggers Popup Modal) -->
            <section id="booking-form" class="py-14 sm:py-20 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- If Booking Success exists from redirection, show confirmation banner -->
                @if (session('booking_success'))
                    <div class="p-6 sm:p-8 bg-emerald-50 border-2 border-emerald-300 rounded-3xl space-y-4 mb-8 shadow-md">
                        <div class="flex items-center gap-3 text-emerald-900">
                            <span class="text-3xl">🕉️</span>
                            <div>
                                <h4 class="font-bold text-lg font-serif">
                                    <span x-show="lang === 'en'">Your Online Puja Sankalp has been registered
                                        successfully!</span>
                                    <span x-show="lang === 'hi'" x-cloak>आपका ऑनलाइन पूजा संकल्प सफलतापूर्वक दर्ज कर लिया
                                        गया है!</span>
                                </h4>
                                <p class="text-xs sm:text-sm text-emerald-700 mt-1">
                                    <span x-show="lang === 'en'">Devotee:
                                        <strong>{{ session('booking_success')['devotee_name'] }}</strong> (Gotra:
                                        {{ session('booking_success')['gotra'] }}) · Auspicious Date:
                                        <strong>{{ session('booking_success')['puja_date'] }}</strong></span>
                                    <span x-show="lang === 'hi'" x-cloak>यजमान:
                                        <strong>{{ session('booking_success')['devotee_name'] }}</strong> (गोत्र:
                                        {{ session('booking_success')['gotra'] }}) · संकल्प तिथि:
                                        <strong>{{ session('booking_success')['puja_date'] }}</strong></span>
                                </p>
                            </div>
                        </div>
                        <p class="text-xs sm:text-sm text-slate-700 leading-relaxed">
                            <span x-show="lang === 'en'">Our Tirtha representative will connect with you on
                                <strong>{{ session('booking_success')['phone'] }}</strong> to confirm the exact Ghat Muhurta
                                and live stream link.</span>
                            <span x-show="lang === 'hi'" x-cloak class="hindi-text">हमारे तीर्थ प्रतिनिधि आपसे
                                <strong>{{ session('booking_success')['phone'] }}</strong> पर संपर्क कर घाट मुहूर्त और लाइव
                                स्ट्रीमिंग लिंक की पुष्टि करेंगे।</span>
                        </p>
                        <div class="pt-2 flex flex-wrap gap-3">
                            <a href="https://wa.me/917042482555?text={{ urlencode('Jai Gange! I have submitted the online puja booking form for ' . session('booking_success')['package'] . '. Devotee: ' . session('booking_success')['devotee_name'] . '. Please confirm the live stream schedule.') }}"
                                target="_blank" rel="noopener"
                                class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs sm:text-sm font-bold shadow-xs transition">
                                <span>💬 Confirm on WhatsApp Instantly</span>
                            </a>
                            <button type="button" @click="openBookingModal()"
                                class="px-5 py-2.5 bg-white border border-slate-300 text-slate-700 hover:bg-slate-50 rounded-xl text-xs sm:text-sm font-semibold transition">
                                <span x-show="lang === 'en'">View / Edit Sankalp Details</span>
                                <span x-show="lang === 'hi'" x-cloak>विवरण पुनः देखें / नया संकल्प</span>
                            </button>
                        </div>
                    </div>
                @endif

                <!-- Beautiful Sacred Booking Invitation Card -->
                <div
                    class="relative bg-gradient-to-br from-[#25050A] via-[#380B12] to-[#25050A] text-white rounded-3xl border-2 border-amber-300/40 p-8 sm:p-12 shadow-2xl text-center space-y-6 overflow-hidden">
                    <!-- Subtle Glow & Pattern -->
                    <div
                        class="absolute -right-16 -top-16 w-56 h-56 rounded-full bg-[#D97706]/15 blur-2xl pointer-events-none">
                    </div>
                    <div
                        class="absolute -left-16 -bottom-16 w-56 h-56 rounded-full bg-[#881337]/30 blur-2xl pointer-events-none">
                    </div>

                    <div class="relative space-y-3 max-w-2xl mx-auto">
                        <span
                            class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full text-xs font-bold bg-[#D97706]/20 text-amber-200 border border-[#D97706]/40 font-mono">
                            <span>🪔</span>
                            <span x-show="lang === 'en'">Vedic Sankalp Registration</span>
                            <span x-show="lang === 'hi'" x-cloak>पवित्र वैदिक संकल्प पंजीकरण</span>
                        </span>

                        <h2 x-show="lang === 'en'"
                            class="text-2xl sm:text-4xl font-serif font-extrabold text-white leading-snug">
                            Ready to Book Your Haridwar Ghat Puja?
                        </h2>
                        <h2 x-show="lang === 'hi'" x-cloak
                            class="text-2xl sm:text-4xl font-serif font-extrabold text-white hindi-title">
                            हरिद्वार पावन गंगा घाट पूजन संकल्प लें
                        </h2>

                        <p x-show="lang === 'en'" class="text-xs sm:text-sm text-amber-100/90 leading-relaxed">
                            Click below to open the official Sankalp booking form. Provide the devotee's Name, Gotra,
                            and preferred date to schedule your private live video stream and doorstep consecrated
                            prasad delivery.
                        </p>
                        <p x-show="lang === 'hi'" x-cloak
                            class="text-xs sm:text-sm text-amber-100/90 hindi-subtitle leading-relaxed">
                            नीचे दिए गए बटन पर क्लिक करके संकल्प फॉर्म खोलें। यजमान का पूरा नाम, गोत्र और पावन तिथि दर्ज
                            करें ताकि हरिद्वार गंगा घाट पर पुरोहितों द्वारा सटीक संकल्प लिया जा सके।
                        </p>
                    </div>

                    <!-- Trigger Button for Popup Modal -->
                    <div class="relative flex flex-col sm:flex-row items-center justify-center gap-4 pt-2">
                        <button type="button" @click="openBookingModal()"
                            class="w-full sm:w-auto px-8 py-4 bg-gradient-to-r from-[#D97706] to-[#F97316] hover:from-[#B45309] hover:to-[#EA580C] text-white font-bold text-sm sm:text-base rounded-2xl shadow-xl hover:shadow-amber-500/20 transition transform hover:-translate-y-0.5 cursor-pointer flex items-center justify-center gap-2.5">
                            <span class="text-lg">🕉️</span>
                            <span x-show="lang === 'en'">Open Online Puja Booking Form →</span>
                            <span x-show="lang === 'hi'" x-cloak>ऑनलाइन पूजा संकल्प फॉर्म खोलें →</span>
                        </button>

                        <a href="https://wa.me/917042482555?text={{ urlencode('Jai Gange! I need help booking an Online Puja at Haridwar Ghat.') }}"
                            target="_blank" rel="noopener"
                            class="w-full sm:w-auto px-6 py-4 bg-white/10 hover:bg-white/20 text-white border border-white/20 rounded-2xl font-bold text-xs sm:text-sm backdrop-blur-xs transition flex items-center justify-center gap-2">
                            <span>💬 WhatsApp Consultation</span>
                        </a>
                    </div>

                    <!-- Trust Points -->
                    <div
                        class="relative pt-4 border-t border-amber-300/20 flex flex-wrap items-center justify-center gap-6 text-[11px] text-amber-200/80">
                        <span class="flex items-center gap-1.5">
                            <span class="text-emerald-400">✓</span> 15-20 Min Private Live Video Stream
                        </span>
                        <span class="flex items-center gap-1.5">
                            <span class="text-emerald-400">✓</span> Consecrated 500ml Gangajal &amp; Prasad
                        </span>
                        <span class="flex items-center gap-1.5">
                            <span class="text-emerald-400">✓</span> Authentic Haridwar Tirtha Purohits
                        </span>
                    </div>
                </div>
            </section>

            <!-- 5. Frequently Asked Questions (FAQ) Section -->
            <section
                class="py-14 sm:py-16 border-t border-amber-200/60 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
                <div class="text-center space-y-2">
                    <span class="text-xs font-extrabold text-[#881337] uppercase tracking-wider font-mono">Devotee
                        Inquiries</span>

                    <!-- English FAQ Title -->
                    <h2 x-show="lang === 'en'" class="text-2xl sm:text-3xl font-serif font-bold text-slate-900">
                        Frequently Asked Questions (Online Puja FAQs)
                    </h2>
                    <!-- Hindi FAQ Title with Proper Line Height -->
                    <h2 x-show="lang === 'hi'" x-cloak
                        class="text-2xl sm:text-3xl font-serif font-bold text-slate-900 hindi-title">
                        ऑनलाइन पूजा संबंधित सामान्य प्रश्न (FAQs)
                    </h2>
                </div>

                <div class="space-y-3.5" x-data="{ activeFaq: null }">
                    <!-- FAQ 1 -->
                    <div class="border border-amber-200 rounded-2xl bg-white overflow-hidden shadow-xs">
                        <button type="button" @click="activeFaq = (activeFaq === 1 ? null : 1)"
                            class="w-full p-4 text-left font-bold text-sm text-slate-900 flex items-center justify-between gap-4 hover:bg-amber-50/50 transition">
                            <span x-show="lang === 'en'">1. How is the Sankalp performed if I do not know my
                                Gotra?</span>
                            <span x-show="lang === 'hi'" x-cloak class="hindi-title">1. यदि मुझे अपना गोत्र ज्ञात न हो
                                तो संकल्प कैसे लिया जाएगा?</span>
                            <span class="text-base font-mono text-[#D97706]"
                                x-text="activeFaq === 1 ? '−' : '+'">+</span>
                        </button>
                        <div x-show="activeFaq === 1" x-cloak
                            class="p-4 pt-0 text-xs text-slate-600 border-t border-amber-100">
                            <p x-show="lang === 'en'" class="leading-relaxed">
                                According to Vedic scriptures, if an individual does not know their ancestral Gotra,
                                they are considered spiritual descendants of Sage Kashyap. Their Sankalp is
                                traditionally and authentically solemnized under the 'Kashyap' Gotra, ensuring full
                                spiritual efficacy and scriptural validity.
                            </p>
                            <p x-show="lang === 'hi'" x-cloak class="hindi-text">
                                सनातन शास्त्रों के अनुसार यदि किसी व्यक्ति को अपना पैतृक गोत्र ज्ञात नहीं होता, तो वे
                                महर्षि कश्यप के वंशज माने जाते हैं और उनका संकल्प 'कश्यप' गोत्र से विधिपूर्वक संपन्न
                                कराया जाता है। यह पूर्णतः शास्त्रसम्मत और फलदायी है।
                            </p>
                        </div>
                    </div>

                    <!-- FAQ 2 -->
                    <div class="border border-amber-200 rounded-2xl bg-white overflow-hidden shadow-xs">
                        <button type="button" @click="activeFaq = (activeFaq === 2 ? null : 2)"
                            class="w-full p-4 text-left font-bold text-sm text-slate-900 flex items-center justify-between gap-4 hover:bg-amber-50/50 transition">
                            <span x-show="lang === 'en'">2. How and on which platform is the private live stream
                                conducted?</span>
                            <span x-show="lang === 'hi'" x-cloak class="hindi-title">2. लाइव स्ट्रीमिंग कैसे और किस
                                माध्यम से आयोजित की जाती है?</span>
                            <span class="text-base font-mono text-[#D97706]"
                                x-text="activeFaq === 2 ? '−' : '+'">+</span>
                        </button>
                        <div x-show="activeFaq === 2" x-cloak
                            class="p-4 pt-0 text-xs text-slate-600 border-t border-amber-100">
                            <p x-show="lang === 'en'" class="leading-relaxed">
                                Prior to the scheduled puja time, our team sends you a private Zoom, WhatsApp Video, or
                                dedicated streaming link. You and your family can join directly from your phone or
                                computer to witness the Vedic priests chanting your personalized Sankalp, offering
                                lamps, and performing the sacred rituals in real time.
                            </p>
                            <p x-show="lang === 'hi'" x-cloak class="hindi-text">
                                पूजा के निर्धारित समय से पूर्व आपको एक निजी Zoom / WhatsApp Video / Private Streaming
                                लिंक भेजा जाता है। आप और आपका परिवार अपने मोबाइल या लैपटॉप से जुड़कर पुरोहितों को लाइव
                                मंत्रोच्चार करते हुए देख सकते हैं तथा संकल्प के समय अपना हाथ जोड़कर सहभागिता कर सकते
                                हैं।
                            </p>
                        </div>
                    </div>

                    <!-- FAQ 3 -->
                    <div class="border border-amber-200 rounded-2xl bg-white overflow-hidden shadow-xs">
                        <button type="button" @click="activeFaq = (activeFaq === 3 ? null : 3)"
                            class="w-full p-4 text-left font-bold text-sm text-slate-900 flex items-center justify-between gap-4 hover:bg-amber-50/50 transition">
                            <span x-show="lang === 'en'">3. When will the blessed Prasad and 500ml Gangajal arrive at my
                                home?</span>
                            <span x-show="lang === 'hi'" x-cloak class="hindi-title">3. पूजा के बाद प्रसाद और 500ml
                                गंगाजल कितने दिनों में प्राप्त होगा?</span>
                            <span class="text-base font-mono text-[#D97706]"
                                x-text="activeFaq === 3 ? '−' : '+'">+</span>
                        </button>
                        <div x-show="activeFaq === 3" x-cloak
                            class="p-4 pt-0 text-xs text-slate-600 border-t border-amber-100">
                            <p x-show="lang === 'en'" class="leading-relaxed">
                                Within 24 hours of ritual completion, your sanctified Prasad Box (energized Raksha Sutra
                                thread, Roli, Akshat, and dry Panchmeva) along with the consecrated 500ml Blu Bliss
                                Gangajal bottle is dispatched in tamper-evident protective packaging. It typically
                                reaches your doorstep in 3 to 5 business days.
                            </p>
                            <p x-show="lang === 'hi'" x-cloak class="hindi-text">
                                अनुष्ठान संपन्न होने के 24 घंटे के भीतर आपका मंगल प्रसाद बॉक्स (अभिमंत्रित रक्षा सूत्र,
                                रोली, अक्षत, सूखा पंचमेवा) तथा 500ml शुद्ध Blu Bliss गंगाजल बोतल विशेष पैकेजिंग में
                                कूरियर द्वारा प्रेषित की जाती है। सामान्यतः 3 से 5 कार्यदिवसों में यह आपके पते पर पहुँच
                                जाती है।
                            </p>
                        </div>
                    </div>

                    <!-- FAQ 4 -->
                    <div class="border border-amber-200 rounded-2xl bg-white overflow-hidden shadow-xs">
                        <button type="button" @click="activeFaq = (activeFaq === 4 ? null : 4)"
                            class="w-full p-4 text-left font-bold text-sm text-slate-900 flex items-center justify-between gap-4 hover:bg-amber-50/50 transition">
                            <span x-show="lang === 'en'">4. Can the puja date be rescheduled later if needed?</span>
                            <span x-show="lang === 'hi'" x-cloak class="hindi-title">4. क्या पूजा की तिथि बाद में बदली
                                (Reschedule) जा सकती है?</span>
                            <span class="text-base font-mono text-[#D97706]"
                                x-text="activeFaq === 4 ? '−' : '+'">+</span>
                        </button>
                        <div x-show="activeFaq === 4" x-cloak
                            class="p-4 pt-0 text-xs text-slate-600 border-t border-amber-100">
                            <p x-show="lang === 'en'" class="leading-relaxed">
                                Yes. If you need to reschedule due to unforeseen circumstances, please notify our
                                helpline at least 24 hours prior to the scheduled puja time. While all online puja
                                bookings are 100% non-refundable once confirmed, free rescheduling to another date is
                                accommodated with 24 hours' advance notice.
                            </p>
                            <p x-show="lang === 'hi'" x-cloak class="hindi-text">
                                हाँ, यदि आपको किसी अपरिहार्य कारणवश तिथि बदलनी हो, तो कृपया निर्धारित पूजा समय से कम से
                                कम 24 घंटे पूर्व हमारे हेल्पलाइन नंबर पर सूचित करें। सभी ऑनलाइन पूजा शुल्क गैर-वापसी
                                योग्य (Non-Refundable) हैं, परंतु 24 घंटे पूर्व सूचना देने पर नई तिथि निर्धारित की जा
                                सकती है।
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Helpline Card -->
                <div
                    class="p-6 bg-[#F97316] text-white rounded-3xl border border-amber-300/30 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="space-y-1 text-center sm:text-left">
                        <h4 x-show="lang === 'en'" class="font-serif font-bold text-base text-amber-200">
                            Have specific questions or need guidance on auspicious Muhurtas?
                        </h4>
                        <h4 x-show="lang === 'hi'" x-cloak
                            class="font-serif font-bold text-base text-amber-200 hindi-title">
                            क्या आपके मन में कोई विशिष्ट प्रश्न या मुहूर्त संबंधी संशय है?
                        </h4>

                        <p x-show="lang === 'en'" class="text-xs text-white">
                            Speak directly with our Haridwar Tirtha Purohit coordination team or message us on WhatsApp.
                        </p>
                        <p x-show="lang === 'hi'" x-cloak class="text-xs text-white hindi-text">
                            हमारे तीर्थ पुरोहित समन्वय दल से सीधे बात करें या WhatsApp पर संदेश भेजें।
                        </p>
                    </div>

                    <div class="flex items-center gap-3 shrink-0">
                        <a href="tel:{{ str_replace(' ', '', $supportPhone) }}"
                            class="px-4 py-2 bg-white/10 hover:bg-white/20 text-white rounded-xl text-xs font-bold transition">
                            📞 {{ $supportPhone }}
                        </a>
                        <a href="https://wa.me/917042482555?text={{ urlencode('Jai Gange! I need guidance regarding Online Puja & Sacred Ghat Rituals.') }}"
                            target="_blank" rel="noopener"
                            class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow-xs transition">
                            💬 WhatsApp
                        </a>
                    </div>
                </div>
            </section>

        </div>

        <!-- ==================================================== -->
        <!-- RESPONSIVE ONLINE PUJA BOOKING MODAL POPUP (ON-CLICK) -->
        <!-- ==================================================== -->
        <div x-show="isBookingModalOpen" x-cloak class="relative z-50">

            <!-- Backdrop -->
            <div x-show="isBookingModalOpen" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0" @click="closeBookingModal()"
                class="fixed inset-0 bg-slate-950/75 backdrop-blur-xs transition-opacity"></div>

            <!-- Positioner -->
            <div x-show="isBookingModalOpen"
                class="fixed inset-0 z-50 overflow-y-auto p-3 sm:p-5 flex items-center justify-center">

                <div x-show="isBookingModalOpen" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 scale-95 translate-y-3"
                    x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                    x-transition:leave-end="opacity-0 scale-95 translate-y-3" @click.away="closeBookingModal()"
                    class="bg-white rounded-2xl sm:rounded-3xl shadow-2xl border-2 border-amber-300/80 w-full max-w-2xl sm:max-w-3xl overflow-hidden my-auto transform transition-all text-slate-800 flex flex-col max-h-[92vh]">

                    <!-- Modal Header (Sticky Top) -->
                    <div
                        class="px-5 sm:px-7 py-4 bg-gradient-to-r from-[#FFFDF7] via-amber-50 to-[#FFFDF7] border-b border-amber-200 flex items-center justify-between shrink-0">
                        <div class="flex items-center gap-3">
                            <span
                                class="w-10 h-10 rounded-xl bg-amber-500/15 text-amber-700 flex items-center justify-center shrink-0 border border-amber-500/30 text-xl shadow-inner">
                                🕉️
                            </span>
                            <div>
                                <h3 x-show="lang === 'en'"
                                    class="font-serif font-bold text-slate-900 text-base sm:text-lg leading-tight">
                                    Vedic Sankalp &amp; Online Puja Booking
                                </h3>
                                <h3 x-show="lang === 'hi'" x-cloak
                                    class="font-serif font-bold text-slate-900 text-base sm:text-lg hindi-title">
                                    वैदिक संकल्प एवं ऑनलाइन पूजा बुकिंग
                                </h3>
                                <p x-show="lang === 'en'" class="text-[11px] text-slate-500 mt-0.5">
                                    Live Haridwar Ghat streaming &amp; blessed doorstep prasad
                                </p>
                                <p x-show="lang === 'hi'" x-cloak
                                    class="text-[11px] text-slate-500 mt-0.5 hindi-subtitle">
                                    हरिद्वार गंगा घाट लाइव दर्शन एवं मंगल प्रसाद सेवा
                                </p>
                            </div>
                        </div>

                        <!-- Close Button -->
                        <button type="button" @click="closeBookingModal()"
                            class="p-2 text-slate-400 hover:text-slate-700 hover:bg-slate-100 rounded-full transition cursor-pointer"
                            aria-label="Close dialog">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Modal Scrollable Content -->
                    <div class="p-5 sm:p-7 overflow-y-auto space-y-6 text-sm">

                        <!-- Booking Success Notification (if redirected back after submit) -->
                        @if (session('booking_success'))
                            <div class="p-5 sm:p-6 bg-emerald-50 border-2 border-emerald-300 rounded-2xl space-y-3">
                                <div class="flex items-center gap-3 text-emerald-900">
                                    <span class="text-2xl">🕉️</span>
                                    <div>
                                        <h4 class="font-bold text-base">
                                            <span x-show="lang === 'en'">Your Online Puja Sankalp has been registered successfully!</span>
                                            <span x-show="lang === 'hi'" x-cloak>आपका ऑनलाइन पूजा संकल्प सफलतापूर्वक दर्ज कर लिया गया है!</span>
                                        </h4>
                                        <p class="text-xs text-emerald-700 mt-0.5">
                                            <span x-show="lang === 'en'">Devotee:
                                                <strong>{{ session('booking_success')['devotee_name'] }}</strong> (Gotra:
                                                {{ session('booking_success')['gotra'] }}) · Date:
                                                {{ session('booking_success')['puja_date'] }}</span>
                                            <span x-show="lang === 'hi'" x-cloak>यजमान:
                                                <strong>{{ session('booking_success')['devotee_name'] }}</strong> (गोत्र:
                                                {{ session('booking_success')['gotra'] }}) · संकल्प तिथि:
                                                {{ session('booking_success')['puja_date'] }}</span>
                                        </p>
                                        @if(!empty(session('booking_success')['payment_id']))
                                            <p class="text-[11px] text-emerald-800 font-mono mt-1">
                                                <span>✓ Paid Online via Razorpay · Payment ID: <strong>{{ session('booking_success')['payment_id'] }}</strong></span>
                                            </p>
                                        @endif
                                    </div>
                                </div>
                                <p class="text-xs text-slate-700 leading-relaxed">
                                    <span x-show="lang === 'en'">Our Tirtha representative will connect with you on
                                        <strong>{{ session('booking_success')['phone'] }}</strong> to confirm the exact Ghat
                                        Muhurta and live stream link.</span>
                                    <span x-show="lang === 'hi'" x-cloak class="hindi-text">हमारे तीर्थ प्रतिनिधि आपसे
                                        <strong>{{ session('booking_success')['phone'] }}</strong> पर संपर्क कर घाट मुहूर्त
                                        और लाइव स्ट्रीमिंग लिंक की पुष्टि करेंगे।</span>
                                </p>
                                <div class="pt-2 flex flex-wrap gap-2.5">
                                    <a href="https://wa.me/917042482555?text={{ urlencode('Jai Gange! I have submitted the online puja booking form for ' . session('booking_success')['package'] . '. Devotee: ' . session('booking_success')['devotee_name'] . '. Please confirm the live stream schedule.') }}"
                                        target="_blank" rel="noopener"
                                        class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold shadow-xs transition">
                                        <span>💬 WhatsApp Confirmation</span>
                                    </a>
                                    <button type="button" @click="closeBookingModal()"
                                        class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-lg text-xs font-semibold transition">
                                        Done
                                    </button>
                                </div>
                            </div>
                        @endif

                        <!-- Booking Form -->
                        <form id="puja-booking-form" method="POST" action="{{ route('online-puja.book') }}" class="space-y-5 text-sm">
                            @csrf

                            <!-- Hidden Payment Fields -->
                            <input type="hidden" name="payment_method" :value="pujaPaymentMethod">
                            <input type="hidden" name="razorpay_payment_id" :value="pujaPaymentId">
                            <input type="hidden" name="razorpay_order_id" :value="pujaOrderId">
                            <input type="hidden" name="razorpay_signature" :value="pujaSignature">

                            <!-- 1. Package Selection -->
                            <div>
                                <label
                                    class="block text-xs font-bold text-slate-800 uppercase tracking-wider mb-2 font-mono">
                                    <span x-show="lang === 'en'">Select Puja Package <span
                                            class="text-red-600">*</span></span>
                                    <span x-show="lang === 'hi'" x-cloak>पूजा पैकेज चयन (Select Puja Package) <span
                                            class="text-red-600">*</span></span>
                                </label>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <!-- Package 1 Radio Option -->
                                    <label
                                        class="flex items-start gap-3 p-3.5 rounded-xl border-2 transition cursor-pointer"
                                        :class="selectedPackage.includes('पैकेज 1') ? 'border-[#D97706] bg-amber-50/60 shadow-xs' : 'border-slate-200 hover:border-amber-200'">
                                        <input type="radio" name="package"
                                            value="पैकेज 1: मंगल उत्सव एवं आयुष्मान संकल्प पूजा (₹5,100/-)"
                                            x-model="selectedPackage" class="mt-1 text-[#D97706] focus:ring-[#D97706]">
                                        <div>
                                            <span x-show="lang === 'en'"
                                                class="block text-xs font-bold text-slate-900">Package 1: Mangal Utsav
                                                &amp; Ayushman Puja</span>
                                            <span x-show="lang === 'hi'" x-cloak
                                                class="block text-xs font-bold text-slate-900">पैकेज 1: मंगल उत्सव एवं
                                                आयुष्मान पूजा</span>

                                            <span x-show="lang === 'en'"
                                                class="block text-[11px] text-slate-500 mt-0.5">Birthdays, Anniversaries
                                                &amp; Milestones</span>
                                            <span x-show="lang === 'hi'" x-cloak
                                                class="block text-[11px] text-slate-500 mt-0.5">जन्मदिन, वर्षगांठ व
                                                पारिवारिक शुभ अवसर</span>

                                            <span
                                                class="block text-xs font-extrabold text-[#D97706] mt-1 font-serif">₹5,100/-</span>
                                        </div>
                                    </label>

                                    <!-- Package 2 Radio Option -->
                                    <label
                                        class="flex items-start gap-3 p-3.5 rounded-xl border-2 transition cursor-pointer"
                                        :class="selectedPackage.includes('पैकेज 2') ? 'border-[#881337] bg-rose-50/60 shadow-xs' : 'border-slate-200 hover:border-amber-200'">
                                        <input type="radio" name="package"
                                            value="पैकेज 2: महा-तिथि एवं पर्व विशेष दिव्य अनुष्ठान (₹11,000/-)"
                                            x-model="selectedPackage" class="mt-1 text-[#881337] focus:ring-[#881337]">
                                        <div>
                                            <span x-show="lang === 'en'"
                                                class="block text-xs font-bold text-slate-900">Package 2: Maha-Tithi
                                                &amp; Festival Ritual</span>
                                            <span x-show="lang === 'hi'" x-cloak
                                                class="block text-xs font-bold text-slate-900">पैकेज 2: महा-तिथि एवं
                                                पर्व अनुष्ठान</span>

                                            <span x-show="lang === 'en'"
                                                class="block text-[11px] text-slate-500 mt-0.5">Amavasya, Purnima,
                                                Eclipse &amp; Ekadashi</span>
                                            <span x-show="lang === 'hi'" x-cloak
                                                class="block text-[11px] text-slate-500 mt-0.5">अमावस्या, पूर्णिमा,
                                                ग्रहण व एकादशी</span>

                                            <span
                                                class="block text-xs font-extrabold text-[#881337] mt-1 font-serif">₹11,000/-</span>
                                        </div>
                                    </label>
                                </div>
                                @error('package')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <!-- 2. Sankalp Details (Name, Gotra, Date, Occasion) -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <!-- Devotee Name -->
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                        <span x-show="lang === 'en'">Devotee / Couple Full Name <span
                                                class="text-red-600">*</span></span>
                                        <span x-show="lang === 'hi'" x-cloak>यजमान का पूरा नाम (Devotee / Couple Name)
                                            <span class="text-red-600">*</span></span>
                                    </label>
                                    <input type="text" name="devotee_name"
                                        value="{{ old('devotee_name', auth()->user()?->name) }}" required
                                        :placeholder="lang === 'en' ? 'e.g. Rahul Sharma / Rahul & Priya Sharma' : 'उदा. राहुल शर्मा / राहुल व प्रिया शर्मा'"
                                        class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-sm focus:outline-none focus:border-[#D97706] focus:ring-1 focus:ring-[#D97706]">
                                    @error('devotee_name')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <!-- Gotra -->
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                        <span x-show="lang === 'en'">Gotra (Clan / Lineage) <span
                                                class="text-red-600">*</span></span>
                                        <span x-show="lang === 'hi'" x-cloak>गोत्र (Gotra) <span
                                                class="text-red-600">*</span></span>
                                    </label>
                                    <input type="text" name="gotra" value="{{ old('gotra') }}" required
                                        :placeholder="lang === 'en' ? 'e.g. Kashyap, Bharadwaj, Vashishta (Write Kashyap if unknown)' : 'उदा. कश्यप, भारद्वाज, वशिष्ठ (यदि ज्ञात न हो तो कश्यप लिखें)'"
                                        class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-sm focus:outline-none focus:border-[#D97706] focus:ring-1 focus:ring-[#D97706]">
                                    <p x-show="lang === 'en'" class="text-[10px] text-slate-500 mt-1">If your Gotra is
                                        unknown, as per Vedic scripture, 'Kashyap' Gotra is used for Sankalp.</p>
                                    <p x-show="lang === 'hi'" x-cloak
                                        class="text-[10px] text-slate-500 mt-1 leading-[1.6]">शास्त्रानुसार गोत्र ज्ञात
                                        न होने पर 'कश्यप' गोत्र से संकल्प लिया जाता है।</p>
                                    @error('gotra')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <!-- Auspicious Date -->
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                        <span x-show="lang === 'en'">Auspicious Puja Date <span
                                                class="text-red-600">*</span></span>
                                        <span x-show="lang === 'hi'" x-cloak>संकल्प की पावन तिथि (Auspicious Date) <span
                                                class="text-red-600">*</span></span>
                                    </label>
                                    <input type="date" name="puja_date"
                                        value="{{ old('puja_date', now()->addDays(2)->format('Y-m-d')) }}"
                                        min="{{ now()->addDay()->format('Y-m-d') }}" required
                                        class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-sm focus:outline-none focus:border-[#D97706] focus:ring-1 focus:ring-[#D97706]">
                                    @error('puja_date')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <!-- Occasion -->
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                        <span x-show="lang === 'en'">Occasion / Sacred Intention</span>
                                        <span x-show="lang === 'hi'" x-cloak>पावन अवसर / उद्देश्य (Occasion)</span>
                                    </label>
                                    <input type="text" name="occasion" value="{{ old('occasion') }}"
                                        :placeholder="lang === 'en' ? 'e.g. 40th Birthday / 15th Anniversary / Family Peace' : 'उदा. 40वां जन्मदिन / 15वीं विवाह वर्षगांठ / पितृ शांति'"
                                        class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-sm focus:outline-none focus:border-[#D97706] focus:ring-1 focus:ring-[#D97706]">
                                    @error('occasion')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <!-- 3. Contact Details (Phone/WhatsApp, Email) -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <!-- Phone -->
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                        <span x-show="lang === 'en'">Phone / WhatsApp Number (For Live Link) <span
                                                class="text-red-600">*</span></span>
                                        <span x-show="lang === 'hi'" x-cloak>फोन / WhatsApp नंबर (For Live Stream Link)
                                            <span class="text-red-600">*</span></span>
                                    </label>
                                    <input type="tel" name="phone" value="{{ old('phone', auth()->user()?->phone) }}"
                                        required placeholder="+91 7042482555"
                                        class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-sm font-mono focus:outline-none focus:border-[#D97706] focus:ring-1 focus:ring-[#D97706]">
                                    <p x-show="lang === 'en'" class="text-[10px] text-slate-500 mt-1">Live streaming
                                        video link and recording will be delivered to this number.</p>
                                    <p x-show="lang === 'hi'" x-cloak
                                        class="text-[10px] text-slate-500 mt-1 leading-[1.6]">इसी नंबर पर लाइव
                                        स्ट्रीमिंग लिंक और पूजा वीडियो भेजा जाएगा।</p>
                                    @error('phone')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <!-- Email -->
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                        <span x-show="lang === 'en'">Email Address (Optional)</span>
                                        <span x-show="lang === 'hi'" x-cloak>ईमेल पता (Email Address)</span>
                                    </label>
                                    <input type="email" name="email" value="{{ old('email', auth()->user()?->email) }}"
                                        placeholder="devotee@example.com"
                                        class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-sm focus:outline-none focus:border-[#D97706] focus:ring-1 focus:ring-[#D97706]">
                                    <p x-show="lang === 'en'" class="text-[10px] text-slate-500 mt-1">For digital
                                        blessing certificate and HD video backup delivery.</p>
                                    <p x-show="lang === 'hi'" x-cloak
                                        class="text-[10px] text-slate-500 mt-1 leading-[1.6]">डिजिटल संकल्प प्रमाण-पत्र
                                        और HD वीडियो बैकअप हेतु।</p>
                                    @error('email')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <!-- 4. Shipping Address for Consecrated Prasad Delivery -->
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                    <span x-show="lang === 'en'">Complete Prasad Postal Delivery Address (With Pincode)
                                        <span class="text-red-600">*</span></span>
                                    <span x-show="lang === 'hi'" x-cloak>प्रसाद डिलीवरी का पूरा पता (Complete Postal
                                        Address with Pincode) <span class="text-red-600">*</span></span>
                                </label>
                                <textarea name="shipping_address" rows="2" required
                                    :placeholder="lang === 'en' ? 'Flat/House No., Street/Society, Landmark, City, State, and 6-digit Pincode' : 'मकान संख्या, गली/सोसायटी, लैंडमार्क, शहर, राज्य एवं 6-अंकीय पिनकोड'"
                                    class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-sm focus:outline-none focus:border-[#D97706] focus:ring-1 focus:ring-[#D97706]">{{ old('shipping_address') }}</textarea>
                                <p x-show="lang === 'en'" class="text-[10px] text-slate-500 mt-0.5">Blessed 500ml Blu
                                    Bliss Gangajal and sacred Mangal Prasad box will be shipped here.</p>
                                <p x-show="lang === 'hi'" x-cloak
                                    class="text-[10px] text-slate-500 mt-0.5 leading-[1.6]">यहाँ Blu Bliss 500ml गंगाजल
                                    और मंगल प्रसाद बॉक्स सुरक्षित कूरियर किया जाएगा।</p>
                                @error('shipping_address')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <!-- 5. Special Notes -->
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                    <span x-show="lang === 'en'">Special Prayers / Instructions for Priests
                                        (Optional)</span>
                                    <span x-show="lang === 'hi'" x-cloak>विशेष मनोकामना / पुरोहितों के लिए निर्देश
                                        (Optional)</span>
                                </label>
                                <textarea name="special_instructions" rows="2"
                                    :placeholder="lang === 'en' ? 'Mention any specific family member names or prayer intentions for the priests to recite...' : 'यदि कोई विशिष्ट प्रार्थना या पारिवारिक सदस्यों के नाम संकल्प में सम्मिलित करने हों तो यहाँ लिखें...'"
                                    class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-sm focus:outline-none focus:border-[#D97706] focus:ring-1 focus:ring-[#D97706]">{{ old('special_instructions') }}</textarea>
                            </div>

                            <!-- 6. MANDATORY BILINGUAL CHECKOUT POLICY (From Word Document) -->
                            <div class="p-4 bg-amber-50/70 border-2 border-amber-300/80 rounded-2xl space-y-3">
                                <label class="flex items-start gap-3 cursor-pointer">
                                    <input type="checkbox" name="terms_agreed" value="1" required {{ old('terms_agreed') ? 'checked' : '' }}
                                        class="mt-1 rounded text-[#D97706] focus:ring-[#D97706] border-slate-400">
                                    <div class="space-y-2 text-xs text-slate-800 leading-relaxed">
                                        <!-- English Section -->
                                        <p class="font-medium text-slate-900 leading-relaxed">
                                            I agree to the <a href="{{ route('terms') }}" target="_blank"
                                                class="text-[#D97706] font-bold underline hover:text-[#881337]">Terms
                                                &amp; Conditions</a> &amp; <a href="{{ route('privacy') }}"
                                                target="_blank"
                                                class="text-[#D97706] font-bold underline hover:text-[#881337]">Privacy
                                                Policy</a>. I confirm that the Name, Gotra, and puja date provided are
                                            correct. I understand that all online puja packages and live streaming
                                            services are <strong>100% Non-Refundable</strong> once booked, and
                                            rescheduling requires at least 24 hours' prior notice.
                                        </p>
                                        <!-- Hindi Section with Proper Line Height -->
                                        <p class="text-slate-700 border-t border-amber-200/80 pt-2 hindi-text">
                                            मैं <a href="{{ route('terms') }}" target="_blank"
                                                class="text-[#D97706] font-bold underline hover:text-[#881337]">नियम एवं
                                                शर्तों (Terms &amp; Conditions)</a> से सहमत हूँ। मेरे द्वारा दिए गए नाम
                                            और गोत्र के अनुसार ही हरिद्वार घाट पर संकल्प लिया जाएगा। मुझे ज्ञात है कि
                                            सभी ऑनलाइन पूजा और लाइव स्ट्रीमिंग शुल्क <strong>100% गैर-वापसी योग्य
                                                (Non-Refundable)</strong> हैं।
                                        </p>
                                    </div>
                                </label>
                                <!-- Payment Method Section -->
                                <div class="p-3.5 bg-amber-50/70 border border-amber-200/80 rounded-xl space-y-2">
                                    <label class="block text-xs font-bold text-slate-800">
                                        <span x-show="lang === 'en'">Payment Gateway (Razorpay)</span>
                                        <span x-show="lang === 'hi'" x-cloak>भुगतान माध्यम (रेज़रपे गेटवे)</span>
                                    </label>
                                    <label class="flex items-center gap-3 p-2.5 rounded-lg border-2 border-[#D97706] bg-white cursor-pointer shadow-2xs">
                                        <input type="radio" x-model="pujaPaymentMethod" value="razorpay" class="text-[#D97706] focus:ring-[#D97706]">
                                        <div class="flex-1 flex items-center justify-between text-xs">
                                            <div>
                                                <span class="font-bold text-slate-900 block">Razorpay Gateway (UPI, Cards, NetBanking)</span>
                                                <span class="text-[11px] text-slate-500">100% Secure 256-bit encrypted transaction</span>
                                            </div>
                                            <span class="text-[10px] font-bold bg-emerald-100 text-emerald-800 px-2 py-0.5 rounded-full uppercase">Instant</span>
                                        </div>
                                    </label>
                                </div>

                                @error('terms_agreed')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <!-- Submit Actions -->
                            <div class="pt-2 flex flex-col sm:flex-row items-center justify-between gap-4">
                                <div class="flex items-center gap-2 text-xs text-slate-600">
                                    <span class="text-base text-emerald-600">🔒</span>
                                    <span x-show="lang === 'en'">Secure Vedic Sankalp Registration &amp; Haridwar Tirtha Service</span>
                                    <span x-show="lang === 'hi'" x-cloak class="hindi-text">सुरक्षित संकल्प पंजीकरण एवं हरिद्वार तीर्थ सेवा</span>
                                </div>
                                <button type="button"
                                    @click="initiatePujaPayment()"
                                    :disabled="isPujaProcessing"
                                    :class="isPujaProcessing ? 'opacity-70 cursor-not-allowed bg-slate-400' : 'bg-[#D97706] hover:bg-[#F97316] shadow-xl'"
                                    class="w-full sm:w-auto px-8 py-3.5 text-white font-bold text-sm rounded-xl transition transform hover:-translate-y-0.5 cursor-pointer flex items-center justify-center gap-2">
                                    <template x-if="!isPujaProcessing">
                                        <span>
                                            <span x-show="lang === 'en'">Pay ₹<span x-text="currentPujaAmount.toLocaleString()"></span> via Razorpay &amp; Book →</span>
                                            <span x-show="lang === 'hi'" x-cloak>₹<span x-text="currentPujaAmount.toLocaleString()"></span> रेज़रपे से भुगतान कर संकल्प बुक करें →</span>
                                        </span>
                                    </template>
                                    <template x-if="isPujaProcessing">
                                        <span class="flex items-center gap-2">
                                            <svg class="animate-spin h-4 w-4 text-white" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                                            <span x-show="lang === 'en'">Connecting to Razorpay...</span>
                                            <span x-show="lang === 'hi'" x-cloak>रेज़रपे से जुड़ रहे हैं...</span>
                                        </span>
                                    </template>
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Razorpay Checkout Library -->
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
</x-app-layout>
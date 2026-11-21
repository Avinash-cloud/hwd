<x-app-layout>
    <style>
        .hindi-text { line-height: 1.85 !important; letter-spacing: 0.012em; }
        .hindi-title { line-height: 1.42 !important; letter-spacing: 0.008em; }
    </style>

    <div x-data="{
        lang: (typeof window !== 'undefined' && localStorage.getItem('about_lang')) ? localStorage.getItem('about_lang') : 'en',
        setLang(l) {
            this.lang = l;
            try { localStorage.setItem('about_lang', l); } catch(e) {}
        }
    }" class="bg-[#FFFDF7] text-stone-700">

        <!-- 1. Hero (light, single accent) -->
        <section class="bg-amber-50 border-b border-amber-200">
            <div class="max-w-3xl mx-auto px-4 sm:px-6 py-14 sm:py-20 text-center space-y-5">

                <!-- Language switcher -->
                <div class="inline-flex items-center gap-1 p-1 bg-white border border-amber-200 rounded-full">
                    <button type="button" @click="setLang('en')"
                        :class="lang === 'en' ? 'bg-amber-600 text-white' : 'text-stone-600 hover:text-amber-700'"
                        class="px-4 py-1.5 rounded-full text-xs sm:text-sm font-medium transition cursor-pointer">
                        English
                    </button>
                    <button type="button" @click="setLang('hi')"
                        :class="lang === 'hi' ? 'bg-amber-600 text-white' : 'text-stone-600 hover:text-amber-700'"
                        class="px-4 py-1.5 rounded-full text-xs sm:text-sm font-medium transition cursor-pointer">
                        हिन्दी
                    </button>
                </div>

                <p class="text-xs sm:text-sm font-medium text-amber-700 tracking-wide">
                    Haridwar Bliss Food &amp; Beverages Private Limited
                </p>

                <div class="space-y-2">
                    <h1 x-show="lang === 'en'" class="text-3xl sm:text-5xl font-serif font-bold text-stone-900">
                        About Blu Bliss
                    </h1>
                    <h1 x-show="lang === 'hi'" x-cloak
                        class="text-3xl sm:text-4xl font-serif font-bold text-stone-900 hindi-title">
                        ब्लू ब्लिस (Blu Bliss) के बारे में
                    </h1>

                    <p x-show="lang === 'en'" class="text-base sm:text-lg font-serif italic text-amber-700">
                        From the Holy Ghats of Haridwar to Your Sacred Altar
                    </p>
                    <p x-show="lang === 'hi'" x-cloak class="text-base sm:text-lg font-serif text-amber-700 hindi-text">
                        हरिद्वार के पावन घाटों से सीधे आपके घर के मंदिर तक
                    </p>
                </div>

                <p x-show="lang === 'en'" class="text-sm sm:text-base text-stone-600 leading-relaxed">
                    Blu Bliss, an initiative by <strong>Haridwar Bliss Food &amp; Beverages Private Limited</strong>,
                    bridges timeless Vedic traditions and modern life. We bring the sanctity and authentic rituals of
                    Haridwar directly to your home.
                </p>
                <p x-show="lang === 'hi'" x-cloak class="text-sm text-stone-600 hindi-text">
                    <strong>Haridwar Bliss Food &amp; Beverages Private Limited</strong> का उपक्रम
                    <strong>ब्लू ब्लिस</strong> प्राचीन वैदिक परंपराओं और आधुनिक जीवनशैली के बीच एक सेतु है। हम हरिद्वार की
                    शुद्धता और प्रामाणिक अनुष्ठान सीधे आपके घर तक पहुँचाते हैं।
                </p>
            </div>
        </section>

        <div class="max-w-5xl mx-auto px-4 sm:px-6 py-14 space-y-16 text-sm leading-relaxed">

            <!-- 2. Our Story -->
            <section class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                <div class="space-y-4">
                    <h2 x-show="lang === 'en'" class="text-2xl sm:text-3xl font-serif font-bold text-stone-900">
                        Our Story &amp; Purpose
                    </h2>
                    <h2 x-show="lang === 'hi'" x-cloak
                        class="text-2xl sm:text-3xl font-serif font-bold text-stone-900 hindi-title">
                        हमारी कहानी और उद्देश्य
                    </h2>
                    <div class="w-12 h-0.5 bg-amber-600"></div>

                    <div x-show="lang === 'en'" class="space-y-3 text-stone-600">
                        <p>
                            In today’s fast-paced world, visiting the banks of Maa Ganga regularly isn’t always
                            possible. Yet the need for spiritual grounding and purity at home remains.
                        </p>
                        <p>
                            At <strong>Blu Bliss</strong>, we give every household access to pure Haridwar Gangajal and
                            genuine Vedic worship, delivered with transparency, hygiene, and devotion.
                        </p>
                    </div>

                    <div x-show="lang === 'hi'" x-cloak class="space-y-3 text-stone-600 hindi-text">
                        <p>
                            आज की व्यस्त जीवनशैली में हर किसी के लिए नियमित रूप से हरिद्वार आकर माँ गंगा के तट पर पूजन करना
                            संभव नहीं। किंतु घर में शुद्धि और आशीर्वाद की आवश्यकता सदैव बनी रहती है।
                        </p>
                        <p>
                            <strong>ब्लू ब्लिस</strong> प्रत्येक परिवार को शुद्धता, स्वच्छता और श्रद्धा के साथ हरिद्वार का
                            गंगाजल और प्रामाणिक वैदिक पूजा सेवाएँ घर बैठे उपलब्ध कराता है।
                        </p>
                    </div>
                </div>

                <img src="{{ asset('images/haridwar-temples.jpg') }}" alt="Sacred Temples at Haridwar Ganga Ghats"
                    class="w-full h-72 object-cover rounded-2xl border border-amber-200">
            </section>

            <!-- 3. What Makes Us Unique -->
            <section class="space-y-8">
                <div class="text-center space-y-2">
                    <h2 x-show="lang === 'en'" class="text-2xl sm:text-3xl font-serif font-bold text-stone-900">
                        What Makes Us Unique
                    </h2>
                    <h2 x-show="lang === 'hi'" x-cloak
                        class="text-2xl sm:text-3xl font-serif font-bold text-stone-900 hindi-title">
                        हमारी विशेषताएँ और प्रतिबद्धता
                    </h2>
                    <p x-show="lang === 'en'" class="text-stone-500">
                        Purity, reverence, and transparency in every drop.
                    </p>
                    <p x-show="lang === 'hi'" x-cloak class="text-stone-500 hindi-text">
                        हर बूंद में पूर्ण शुद्धता, मर्यादा और पारदर्शिता।
                    </p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                    <!-- Pillar 1 -->
                    <div class="bg-white p-6 rounded-2xl border border-amber-200 space-y-2">
                        <div class="text-2xl">🌅</div>
                        <h3 x-show="lang === 'en'" class="text-lg font-serif font-bold text-stone-900">
                            Collected at Brahma Muhurta
                        </h3>
                        <h3 x-show="lang === 'hi'" x-cloak class="text-lg font-serif font-bold text-stone-900 hindi-title">
                            ब्रह्म मुहूर्त में संकलन
                        </h3>
                        <p x-show="lang === 'en'" class="text-stone-600">
                            Our Gangajal is collected during the auspicious hours of Brahma Muhurta from the pure waters
                            of Haridwar, preserving its natural and spiritual essence.
                        </p>
                        <p x-show="lang === 'hi'" x-cloak class="text-stone-600 hindi-text">
                            हमारा गंगाजल हरिद्वार के पावन तट से ब्रह्म मुहूर्त के शुभ समय में संकलित किया जाता है, जिससे इसकी
                            पवित्रता अक्षुण्ण बनी रहती है।
                        </p>
                    </div>

                    <!-- Pillar 2 -->
                    <div class="bg-white p-6 rounded-2xl border border-amber-200 space-y-2">
                        <div class="text-2xl">🛡️</div>
                        <h3 x-show="lang === 'en'" class="text-lg font-serif font-bold text-stone-900">
                            Hygienic &amp; Tamper-Proof Packaging
                        </h3>
                        <h3 x-show="lang === 'hi'" x-cloak class="text-lg font-serif font-bold text-stone-900 hindi-title">
                            स्वच्छ एवं सुरक्षित पैकेजिंग
                        </h3>
                        <p x-show="lang === 'en'" class="text-stone-600">
                            Every bottle is processed with strict hygiene standards, with zero chemical interference,
                            keeping the sacred water pure for your daily rituals.
                        </p>
                        <p x-show="lang === 'hi'" x-cloak class="text-stone-600 hindi-text">
                            बिना किसी रासायनिक मिश्रण के, प्रत्येक बोतल को उच्च स्वच्छता मानकों के साथ सीलबंद किया जाता है।
                        </p>
                    </div>

                    <!-- Pillar 3 -->
                    <div class="bg-white p-6 rounded-2xl border border-amber-200 space-y-2">
                        <div class="text-2xl">🪔</div>
                        <h3 x-show="lang === 'en'" class="text-lg font-serif font-bold text-stone-900">
                            Live Ghat Pujas &amp; Vedic Essentials
                        </h3>
                        <h3 x-show="lang === 'hi'" x-cloak class="text-lg font-serif font-bold text-stone-900 hindi-title">
                            लाइव घाट पूजा एवं वैदिक सामग्री
                        </h3>
                        <p x-show="lang === 'en'" class="text-stone-600">
                            Live-streamed pujas from Haridwar ghats by learned Vedic Acharyas, with your Name and Gotra
                            Sankalp, followed by doorstep delivery of consecrated puja essentials and Prasadam.
                        </p>
                        <p x-show="lang === 'hi'" x-cloak class="text-stone-600 hindi-text">
                            विद्वान वैदिक आचार्यों द्वारा आपके नाम और गोत्र के संकल्प के साथ लाइव पूजा, और उसके बाद अभिमंत्रित
                            पूजा सामग्री व प्रसाद आपके घर तक।
                        </p>
                    </div>

                    <!-- Pillar 4 -->
                    <div class="bg-white p-6 rounded-2xl border border-amber-200 space-y-2">
                        <div class="text-2xl">🌿</div>
                        <h3 x-show="lang === 'en'" class="text-lg font-serif font-bold text-stone-900">
                            Social &amp; Environmental Pledge
                        </h3>
                        <h3 x-show="lang === 'hi'" x-cloak class="text-lg font-serif font-bold text-stone-900 hindi-title">
                            पर्यावरण एवं गंगा स्वच्छता संकल्प
                        </h3>
                        <p x-show="lang === 'en'" class="text-stone-600">
                            Devotion to Maa Ganga means protecting her. We remain committed to river cleanliness and
                            environmental sustainability.
                        </p>
                        <p x-show="lang === 'hi'" x-cloak class="text-stone-600 hindi-text">
                            माँ गंगा के प्रति सच्ची श्रद्धा उनकी स्वच्छता में निहित है। हम नदी संरक्षण और पर्यावरण के प्रति
                            प्रतिबद्ध हैं।
                        </p>
                    </div>
                </div>
            </section>

            <!-- 4. Mission -->
            <section class="bg-amber-50 border border-amber-200 rounded-2xl p-8 sm:p-10 text-center space-y-3">
                <h2 x-show="lang === 'en'" class="text-xl sm:text-2xl font-serif font-bold text-stone-900">
                    Our Mission
                </h2>
                <h2 x-show="lang === 'hi'" x-cloak class="text-xl sm:text-2xl font-serif font-bold text-stone-900 hindi-title">
                    हमारा लक्ष्य
                </h2>
                <p x-show="lang === 'en'" class="max-w-2xl mx-auto text-stone-600 italic">
                    “To be a trusted spiritual companion for families across India and the world, making daily worship,
                    special occasion pujas, and sacred Haridwar offerings effortless, authentic, and deeply fulfilling.”
                </p>
                <p x-show="lang === 'hi'" x-cloak class="max-w-2xl mx-auto text-stone-600 hindi-text">
                    “देश और दुनिया भर के श्रद्धालुओं के लिए एक विश्वसनीय आध्यात्मिक सहयोगी बनना, ताकि दैनिक पूजा और विशेष पर्वों
                    के अनुष्ठान पूर्ण श्रद्धा, पारदर्शिता और सहजता के साथ संपन्न हो सकें।”
                </p>
            </section>

            <!-- 5. Corporate & Contact -->
            <section class="bg-white border border-amber-200 rounded-2xl p-6 sm:p-8 space-y-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-4 border-b border-amber-100">
                    <h2 x-show="lang === 'en'" class="text-xl sm:text-2xl font-serif font-bold text-stone-900">
                        Corporate &amp; Contact Information
                    </h2>
                    <h2 x-show="lang === 'hi'" x-cloak class="text-xl sm:text-2xl font-serif font-bold text-stone-900 hindi-title">
                        कंपनी एवं संपर्क विवरण
                    </h2>
                    <span class="text-xs font-medium text-amber-800 bg-amber-50 border border-amber-200 rounded-full px-3 py-1 w-fit">
                        MCA Registered
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div>
                            <p class="text-xs uppercase tracking-wider text-stone-400 font-medium">
                                <span x-show="lang === 'en'">Registered Entity</span>
                                <span x-show="lang === 'hi'" x-cloak>पंजीकृत कंपनी का नाम</span>
                            </p>
                            <p class="font-semibold text-stone-900">Haridwar Bliss Food &amp; Beverages Private Limited</p>
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-wider text-stone-400 font-medium">
                                <span x-show="lang === 'en'">Brand</span>
                                <span x-show="lang === 'hi'" x-cloak>ब्रांड नाम</span>
                            </p>
                            <p class="font-semibold text-stone-900">
                                Blu Bliss <span class="text-xs font-normal text-stone-500">(ब्लू ब्लिस)</span>
                            </p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <p class="text-xs uppercase tracking-wider text-stone-400 font-medium">
                                <span x-show="lang === 'en'">Registered Office</span>
                                <span x-show="lang === 'hi'" x-cloak>पंजीकृत कार्यालय</span>
                            </p>
                            <p x-show="lang === 'en'" class="text-stone-800">
                                G-46, Shivalik Nagar, BHEL, Ranipur More, Haridwar, Uttarakhand – 249403, India
                            </p>
                            <p x-show="lang === 'hi'" x-cloak class="text-stone-800 hindi-text">
                                G-46, शिवालिक नगर, BHEL, रानीपुर मोड़, हरिद्वार, उत्तराखंड – 249403
                            </p>
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-wider text-stone-400 font-medium">
                                <span x-show="lang === 'en'">Helpline &amp; WhatsApp</span>
                                <span x-show="lang === 'hi'" x-cloak>सहायता एवं WhatsApp</span>
                            </p>
                            <div class="flex items-center gap-3 mt-1">
                                <a href="tel:+917042482555" class="font-semibold text-amber-700 hover:underline">
                                    +91 70424 82555
                                </a>
                                <a href="https://wa.me/917042482555?text={{ urlencode('Jai Gange! I would like to inquire about Blu Bliss products and rituals.') }}"
                                    target="_blank" rel="noopener"
                                    class="px-3 py-1 border border-amber-600 text-amber-700 hover:bg-amber-600 hover:text-white rounded-lg text-xs font-semibold transition">
                                    WhatsApp
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 6. CTA -->
            <section class="text-center space-y-4">
                <h3 x-show="lang === 'en'" class="text-xl sm:text-2xl font-serif font-bold text-stone-900">
                    Experience the Sanctity of Haridwar at Your Doorstep
                </h3>
                <h3 x-show="lang === 'hi'" x-cloak class="text-xl sm:text-2xl font-serif font-bold text-stone-900 hindi-title">
                    हरिद्वार की पवित्रता का अनुभव सीधे अपने घर पर करें
                </h3>
                <p x-show="lang === 'en'" class="max-w-xl mx-auto text-stone-600">
                    Explore our laboratory-tested Gangajal in brass, copper, and glass containers, or schedule a
                    personalized ghat puja.
                </p>
                <p x-show="lang === 'hi'" x-cloak class="max-w-xl mx-auto text-stone-600 hindi-text">
                    पीतल, तांबे एवं कांच की बोतलों में शुद्ध गंगाजल प्राप्त करें अथवा घाट पर लाइव वैदिक पूजा संकल्प बुक करें।
                </p>

                <div class="pt-2 flex flex-wrap items-center justify-center gap-3">
                    <a href="{{ route('products.index') }}"
                        class="px-6 py-3 bg-amber-600 hover:bg-amber-700 text-white rounded-xl font-semibold text-sm transition">
                        <span x-show="lang === 'en'">Explore Sacred Products →</span>
                        <span x-show="lang === 'hi'" x-cloak>पवित्र उत्पाद देखें →</span>
                    </a>
                    <a href="{{ route('online-puja') }}"
                        class="px-6 py-3 border border-amber-600 text-amber-700 hover:bg-amber-50 rounded-xl font-semibold text-sm transition">
                        <span x-show="lang === 'en'">Book Haridwar Live Puja →</span>
                        <span x-show="lang === 'hi'" x-cloak>ऑनलाइन पूजा संकल्प लें →</span>
                    </a>
                </div>
            </section>
        </div>
    </div>
</x-app-layout>
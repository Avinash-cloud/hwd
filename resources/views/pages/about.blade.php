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

    <!-- Page Wrapper with Single-Page Alpine Language State (Default: 'en') -->
    <div x-data="{
        lang: (typeof window !== 'undefined' && localStorage.getItem('about_lang')) ? localStorage.getItem('about_lang') : 'en',
        setLang(l) {
            this.lang = l;
            try { localStorage.setItem('about_lang', l); } catch(e) {}
        }
    }">

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

        <!-- 1. Sacred Heritage Hero Banner -->
        <div
            class="relative bg-gradient-to-b from-[#25050A] via-[#380B12] to-[#25050A] text-white overflow-hidden py-16 sm:py-24 border-b border-amber-300/30">
            <!-- Authentic Haridwar Panorama Background -->
            <img src="{{ asset('images/haridwar-panorama-clocktower.jpg') }}" alt="Sacred Haridwar Panorama"
                class="absolute inset-0 w-full h-full object-cover opacity-25 object-center">
            <!-- <div
                class="absolute inset-0 bg-gradient-to-t from-[#25050A] via-[#25050A]/80 to-transparent pointer-events-none">
            </div> -->

            <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-6">

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

                <!-- Entity & Initiative Pill -->
                <div
                    class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs sm:text-sm font-semibold bg-[#D97706]/20 text-amber-200 border border-[#D97706]/40 backdrop-blur-xs shadow-inner">
                    <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                    <span class="tracking-wide">Haridwar Bliss Food &amp; Beverages Private Limited · Sacred Vedic
                        Initiative</span>
                </div>

                <!-- Page Main Title -->
                <div class="space-y-3">
                    <h1 x-show="lang === 'en'"
                        class="text-3xl sm:text-5xl lg:text-[46px] font-serif font-extrabold text-white tracking-tight leading-tight drop-shadow-[0_2px_4px_rgba(0,0,0,0.6)]">
                        About Blu Bliss
                    </h1>
                    <h1 x-show="lang === 'hi'" x-cloak
                        class="text-3xl sm:text-4xl lg:text-[42px] font-serif font-extrabold text-white tracking-tight hindi-title drop-shadow-[0_2px_4px_rgba(0,0,0,0.6)]">
                        ब्लू ब्लिस (Blu Bliss) के बारे में
                    </h1>

                    <!-- Tagline -->
                    <p x-show="lang === 'en'" class="text-base sm:text-xl font-serif text-amber-300 italic font-medium">
                        From the Holy Ghats of Haridwar to Your Sacred Altar
                    </p>
                    <p x-show="lang === 'hi'" x-cloak
                        class="text-base sm:text-lg font-serif text-amber-300 hindi-subtitle font-medium">
                        हरिद्वार के पावन घाटों से सीधे आपके घर के मंदिर तक
                    </p>
                </div>

                <!-- Lead Narrative Paragraph -->
                <div class="max-w-3xl mx-auto">
                    <p x-show="lang === 'en'" class="text-sm sm:text-base text-slate-200 leading-relaxed font-normal">
                        Blu Bliss, an initiative by <strong>Haridwar Bliss Food &amp; Beverages Private
                            Limited</strong>, was born from a simple yet profound vision: to bridge the distance between
                        timeless Vedic traditions and modern lives. We bring the untouched sanctity, spiritual energy,
                        and authentic rituals of the sacred city of Haridwar directly to your home.
                    </p>
                    <p x-show="lang === 'hi'" x-cloak class="text-xs sm:text-sm text-slate-200 hindi-text font-normal">
                        <strong>Haridwar Bliss Food &amp; Beverages Private Limited</strong> का एक पवित्र उपक्रम,
                        <strong>ब्लू ब्लिस (Blu Bliss)</strong> एक सरल किंतु गहन उद्देश्य के साथ स्थापित किया गया
                        है—प्राचीन वैदिक परंपराओं और आधुनिक जीवनशैली के बीच एक आध्यात्मिक सेतु बनाना। हम पावन तीर्थ नगरी
                        हरिद्वार की दिव्य ऊर्जा, शुद्धता और प्रामाणिक अनुष्ठानों को सीधे आपके घर तक पहुँचाते हैं।
                    </p>
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="bg-[#FFFDF7] text-slate-700">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 space-y-20 text-sm leading-relaxed">

                <!-- 2. Our Story & Purpose -->
                <section class="grid grid-cols-1 md:grid-cols-12 gap-8 lg:gap-12 items-center">
                    <div class="md:col-span-7 space-y-4">
                        <span class="text-xs block font-extrabold text-[#881337] uppercase tracking-wider font-mono">
                            <span x-show="lang === 'en'">Origins &amp; Devotion</span>
                            <span x-show="lang === 'hi'" x-cloak>उद्भव एवं संकल्प</span>
                        </span>

                        <h2 x-show="lang === 'en' "
                            class="inline-block text-xl sm:text-3xl lg:text-4xl font-serif font-extrabold text-[#FDE68A] bg-[#881337] border border-amber-300/40 px-6 sm:px-8 py-2.5 sm:py-3 rounded-2xl sm:rounded-3xl shadow-xl leading-tight text-wrap">
                            Our Story &amp; Purpose
                        </h2>
                        <h2 x-show="lang === 'hi'" x-cloak
                            class="inline-block text-xl sm:text-3xl lg:text-4xl font-serif font-extrabold text-[#FDE68A] bg-[#881337] border border-amber-300/40 px-6 sm:px-8 py-2.5 sm:py-3 rounded-2xl sm:rounded-3xl shadow-xl leading-tight text-wrap">
                            हमारी कहानी और उद्देश्य
                        </h2>

                        <!-- English Text -->
                        <div x-show="lang === 'en'" class="space-y-3.5 text-slate-600 text-sm leading-relaxed">
                            <p>
                                In today’s fast-paced world, visiting the holy banks of Maa Ganga regularly isn't always
                                possible. Yet, the need for spiritual grounding, purity, and divine blessings in our
                                homes remains constant.
                            </p>
                            <p>
                                At <strong>Blu Bliss</strong>, we ensure that every household has uninterrupted access
                                to pure, authentic Haridwar Gangajal and genuine Vedic worship experiences—delivered
                                with complete transparency, hygiene, and devotion.
                            </p>
                        </div>

                        <!-- Hindi Text with Generous Line Height -->
                        <div x-show="lang === 'hi'" x-cloak
                            class="space-y-3.5 text-slate-600 text-xs sm:text-sm hindi-text">
                            <p>
                                आज की व्यस्त और भागदौड़ भरी जीवनशैली में हर किसी के लिए नियमित रूप से हरिद्वार आकर माँ
                                गंगा के तट पर स्नान और पूजन करना संभव नहीं हो पाता। किंतु, घर में सकारात्मक ऊर्जा,
                                शुद्धि और ईश्वर के आशीर्वाद की आवश्यकता सदैव बनी रहती है।
                            </p>
                            <p>
                                <strong>ब्लू ब्लिस</strong> यह सुनिश्चित करता है कि प्रत्येक परिवार को पूर्ण शुद्धता,
                                स्वच्छता और श्रद्धा के साथ हरिद्वार का पावन गंगाजल और प्रमाणिक वैदिक पूजा सेवाएँ घर बैठे
                                सहजता से प्राप्त हों।
                            </p>
                        </div>
                    </div>

                    <div class="md:col-span-5">
                        <div class="relative rounded-3xl overflow-hidden border-2 border-amber-200/80 shadow-xl group">
                            <img src="{{ asset('images/haridwar-temples.jpg') }}"
                                alt="Sacred Temples at Haridwar Ganga Ghats"
                                class="w-full h-80 object-cover transition transform duration-500 group-hover:scale-105">
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-slate-950/85 via-slate-950/20 to-transparent flex items-end p-5">
                                <div class="text-white space-y-1">
                                    <span
                                        class="text-[11px] font-bold text-amber-300 uppercase tracking-widest font-mono">Maa
                                        Ganga's Living Blessings</span>
                                    <p class="text-xs text-white/90 font-serif">Pristine sanctity straight from Haridwar
                                        Ghats</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- 3. What Makes Us Unique (4 Pillars Grid) -->
                <section class="space-y-8">
                    <div class="text-center max-w-2xl mx-auto space-y-2">
                        <span class="block text-xs font-mono uppercase font-bold text-[#D97706]">Our Sanctified
                            Protocol</span>
                        <h2 x-show="lang === 'en'"
                            class="inline-block text-xl sm:text-3xl lg:text-4xl font-serif font-extrabold text-[#FDE68A] bg-[#881337] border border-amber-300/40 px-6 sm:px-8 py-2.5 sm:py-3 rounded-2xl sm:rounded-3xl shadow-xl leading-tight text-wrap">
                            What Makes Us Unique
                        </h2>
                        <h2 x-show="lang === 'hi'" x-cloak
                            class="inline-block text-xl sm:text-3xl lg:text-4xl font-serif font-extrabold text-[#FDE68A] bg-[#881337] border border-amber-300/40 px-6 sm:px-8 py-2.5 sm:py-3 rounded-2xl sm:rounded-3xl shadow-xl leading-tight text-wrap">
                            हमारी विशेषताएँ और प्रतिबद्धता
                        </h2>
                        <p x-show="lang === 'en'" class="text-xs sm:text-sm text-slate-600">
                            Purity, reverence, and transparency in every drop and sacred offering.
                        </p>
                        <p x-show="lang === 'hi'" x-cloak class="text-xs sm:text-sm text-slate-600 hindi-text">
                            हर बूंद और पावन अनुष्ठान में पूर्ण शुद्धता, मर्यादा और पारदर्शिता।
                        </p>
                    </div>

                    <!-- 4 Feature Cards Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                        <!-- Pillar 1: Brahma Muhurta Collection -->
                        <div
                            class="bg-white p-6 sm:p-7 rounded-3xl border border-amber-200/80 shadow-md shadow-amber-950/5 hover:border-amber-400 transition-all space-y-3">
                            <div
                                class="w-12 h-12 rounded-2xl bg-amber-500/10 text-[#D97706] font-bold flex items-center justify-center text-2xl border border-amber-500/20">
                                🌅
                            </div>
                            <h3 x-show="lang === 'en'" class="text-base sm:text-lg font-serif font-bold text-slate-900">
                                Collected at Brahma Muhurta
                            </h3>
                            <h3 x-show="lang === 'hi'" x-cloak
                                class="text-base sm:text-lg font-serif font-bold text-slate-900 hindi-title">
                                ब्रह्म मुहूर्त में संकलन (Brahma Muhurta Collection)
                            </h3>

                            <p x-show="lang === 'en'" class="text-xs sm:text-[13px] text-slate-600 leading-relaxed">
                                Our Gangajal is sacredly collected during the auspicious hours of Brahma Muhurta from
                                the pure waters of Haridwar, preserving its natural and spiritual essence.
                            </p>
                            <p x-show="lang === 'hi'" x-cloak class="text-xs sm:text-[13px] text-slate-600 hindi-text">
                                हमारा गंगाजल विशेष रूप से हरिद्वार के पावन तट से ब्रह्म मुहूर्त के अत्यंत शुभ समय में
                                संकलित किया जाता है, जिससे इसकी प्राकृतिक और आध्यात्मिक पवित्रता अक्षुण्ण बनी रहती है।
                            </p>
                        </div>

                        <!-- Pillar 2: Hygienic & Tamper-Proof Packaging -->
                        <div
                            class="bg-white p-6 sm:p-7 rounded-3xl border border-amber-200/80 shadow-md shadow-amber-950/5 hover:border-amber-400 transition-all space-y-3">
                            <div
                                class="w-12 h-12 rounded-2xl bg-emerald-500/10 text-emerald-700 font-bold flex items-center justify-center text-2xl border border-emerald-500/20">
                                🛡️
                            </div>
                            <h3 x-show="lang === 'en'" class="text-base sm:text-lg font-serif font-bold text-slate-900">
                                Hygienic &amp; Tamper-Proof Packaging
                            </h3>
                            <h3 x-show="lang === 'hi'" x-cloak
                                class="text-base sm:text-lg font-serif font-bold text-slate-900 hindi-title">
                                स्वच्छ एवं सुरक्षित पैकेजिंग
                            </h3>

                            <p x-show="lang === 'en'" class="text-xs sm:text-[13px] text-slate-600 leading-relaxed">
                                Every bottle is processed with strict hygiene standards, ensuring zero chemical
                                interference while keeping the sacred water pure for your daily rituals.
                            </p>
                            <p x-show="lang === 'hi'" x-cloak class="text-xs sm:text-[13px] text-slate-600 hindi-text">
                                बिना किसी रासायनिक मिश्रण के, प्रत्येक बोतल को उच्च स्वच्छता मानकों के साथ सीलबंद किया
                                जाता है ताकि आपके दैनिक पूजन के लिए जल पूर्णतः शुद्ध रहे।
                            </p>
                        </div>

                        <!-- Pillar 3: Live Ghat Pujas & Vedic Essentials -->
                        <div
                            class="bg-white p-6 sm:p-7 rounded-3xl border border-amber-200/80 shadow-md shadow-amber-950/5 hover:border-amber-400 transition-all space-y-3">
                            <div
                                class="w-12 h-12 rounded-2xl bg-rose-500/10 text-[#881337] font-bold flex items-center justify-center text-2xl border border-rose-500/20">
                                🪔
                            </div>
                            <h3 x-show="lang === 'en'" class="text-base sm:text-lg font-serif font-bold text-slate-900">
                                Live Ghat Pujas &amp; Vedic Essentials
                            </h3>
                            <h3 x-show="lang === 'hi'" x-cloak
                                class="text-base sm:text-lg font-serif font-bold text-slate-900 hindi-title">
                                लाइव घाट पूजा एवं वैदिक सामग्री
                            </h3>

                            <p x-show="lang === 'en'" class="text-xs sm:text-[13px] text-slate-600 leading-relaxed">
                                Beyond Gangajal, we facilitate personalized live-streamed pujas from Haridwar ghats
                                performed by learned Vedic Acharyas—complete with your Name and Gotra Sankalp, followed
                                by doorstep delivery of consecrated Vedic Puja Essentials and Prasadam.
                            </p>
                            <p x-show="lang === 'hi'" x-cloak class="text-xs sm:text-[13px] text-slate-600 hindi-text">
                                गंगाजल के अतिरिक्त, हम हरिद्वार के पावन घाटों पर विद्वान वैदिक आचार्यों द्वारा आपके नाम
                                और गोत्र के संकल्प के साथ व्यक्तिगत लाइव स्ट्रीमिंग पूजा की सुविधा प्रदान करते हैं,
                                जिसके पश्चात अभिमंत्रित वैदिक पूजा सामग्री और प्रसाद आपके घर पहुँचाया जाता है।
                            </p>
                        </div>

                        <!-- Pillar 4: Conscious Social & Environmental Pledge -->
                        <div
                            class="bg-white p-6 sm:p-7 rounded-3xl border border-amber-200/80 shadow-md shadow-amber-950/5 hover:border-amber-400 transition-all space-y-3">
                            <div
                                class="w-12 h-12 rounded-2xl bg-sky-500/10 text-sky-700 font-bold flex items-center justify-center text-2xl border border-sky-500/20">
                                🌿
                            </div>
                            <h3 x-show="lang === 'en'" class="text-base sm:text-lg font-serif font-bold text-slate-900">
                                Conscious Social &amp; Environmental Pledge
                            </h3>
                            <h3 x-show="lang === 'hi'" x-cloak
                                class="text-base sm:text-lg font-serif font-bold text-slate-900 hindi-title">
                                पर्यावरण एवं गंगा स्वच्छता संकल्प
                            </h3>

                            <p x-show="lang === 'en'" class="text-xs sm:text-[13px] text-slate-600 leading-relaxed">
                                Devotion to Maa Ganga means protecting her. With every step we take, we remain deeply
                                committed to river cleanliness and environmental sustainability.
                            </p>
                            <p x-show="lang === 'hi'" x-cloak class="text-xs sm:text-[13px] text-slate-600 hindi-text">
                                माँ गंगा के प्रति सच्ची श्रद्धा उनकी स्वच्छता में निहित है। हमारा प्रत्येक कदम नदी
                                संरक्षण और पर्यावरण के प्रति हमारी जिम्मेदारी को दर्शाता है।
                            </p>
                        </div>

                    </div>
                </section>

                <!-- 4. Our Mission Banner -->
                <section
                    class="relative bg-gradient-to-r from-[#881337] via-[#991B1B] to-[#7F1D1D] rounded-3xl p-8 sm:p-12 text-white shadow-xl overflow-hidden">
                    <img src="{{ asset('images/haridwar-ghat-shrines.jpg') }}" alt="Sacred Haridwar Shrines"
                        class="absolute inset-0 w-full h-full object-cover opacity-15 mix-blend-overlay pointer-events-none">

                    <div class="relative z-10 max-w-3xl mx-auto text-center space-y-4">
                        <span
                            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-400/20 text-amber-200 border border-amber-400/40 uppercase tracking-wider font-mono">
                            <span>✨</span>
                            <span x-show="lang === 'en'">Our Mission</span>
                            <span x-show="lang === 'hi'" x-cloak>हमारा लक्ष्य (Our Mission)</span>
                        </span>

                        <h2 x-show="lang === 'en'"
                            class="text-2xl sm:text-3xl font-serif font-extrabold text-white leading-snug">
                            Guiding Faith with Uncompromised Purity
                        </h2>
                        <h2 x-show="lang === 'hi'" x-cloak
                            class="text-2xl sm:text-3xl font-serif font-extrabold text-white hindi-title">
                            पूर्ण पवित्रता और श्रद्धा के साथ सेवा
                        </h2>

                        <p x-show="lang === 'en'"
                            class="text-sm sm:text-base text-amber-100/95 leading-relaxed font-normal">
                            "To serve as a trusted spiritual companion for families across India and the world—making
                            daily worship, special occasion pujas, and sacred Haridwar offerings effortless, authentic,
                            and deeply fulfilling."
                        </p>
                        <p x-show="lang === 'hi'" x-cloak
                            class="text-xs sm:text-sm text-amber-100/95 hindi-text font-normal">
                            "देश और दुनिया भर के श्रद्धालुओं के लिए एक विश्वसनीय आध्यात्मिक सहयोगी बनना—ताकि दैनिक पूजा,
                            जन्मदिन, विवाह वर्षगांठ और विशेष पर्वों के अनुष्ठान पूर्ण श्रद्धा, पारदर्शिता और सहजता के
                            साथ संपन्न हो सकें।"
                        </p>
                    </div>
                </section>

                <!-- 5. Corporate & Contact Information Card -->
                <section
                    class="bg-white rounded-3xl border-2 border-amber-200/90 p-8 sm:p-10 shadow-lg shadow-amber-950/5 space-y-6">
                    <div
                        class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-amber-100 gap-2">
                        <div>
                            <span class="text-xs block font-mono uppercase font-bold text-[#D97706]">Official Corporate
                                Information</span>
                            <h2 x-show="lang === 'en'"
                            class="inline-block text-xl sm:text-3xl lg:text-4xl font-serif font-extrabold text-[#FDE68A] bg-[#881337] border border-amber-300/40 px-6 sm:px-8 py-2.5 sm:py-3 rounded-2xl sm:rounded-3xl shadow-xl leading-tight text-wrap"   
                            >
                                Corporate &amp; Contact Information
                            </h2>
                            <h2 x-show="lang === 'hi'" x-cloak
                            class="inline-block text-xl sm:text-3xl lg:text-4xl font-serif font-extrabold text-[#FDE68A] bg-[#881337] border border-amber-300/40 px-6 sm:px-8 py-2.5 sm:py-3 rounded-2xl sm:rounded-3xl shadow-xl leading-tight text-wrap"    
                            >
                                कंपनी एवं संपर्क विवरण
                            </h2>
                        </div>
                        <span
                            class="inline-flex items-center gap-1 text-xs font-semibold px-3 py-1 rounded-full bg-amber-50 text-amber-900 border border-amber-200 w-fit">
                            <span>🏛️</span>
                            <span>Ministry of Corporate Affairs (MCA) Registered</span>
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-xs sm:text-sm">
                        <!-- Entity Details -->
                        <div class="space-y-4">
                            <div class="p-4 bg-[#FFFDF7] rounded-2xl border border-amber-100 space-y-1">
                                <span class="text-[11px] font-bold text-slate-400 uppercase font-mono tracking-wider">
                                    <span x-show="lang === 'en'">Registered Entity Name</span>
                                    <span x-show="lang === 'hi'" x-cloak>पंजीकृत कंपनी का नाम</span>
                                </span>
                                <p class="text-sm sm:text-base font-bold text-slate-900">
                                    Haridwar Bliss Food &amp; Beverages Private Limited
                                </p>
                            </div>

                            <div class="p-4 bg-[#FFFDF7] rounded-2xl border border-amber-100 space-y-1">
                                <span class="text-[11px] font-bold text-slate-400 uppercase font-mono tracking-wider">
                                    <span x-show="lang === 'en'">Brand Identity</span>
                                    <span x-show="lang === 'hi'" x-cloak>ब्रांड नाम</span>
                                </span>
                                <p class="text-sm sm:text-base font-bold text-amber-800">
                                    Blu Bliss <span class="text-xs font-normal text-slate-500">(ब्लू ब्लिस)</span>
                                </p>
                            </div>
                        </div>

                        <!-- Address & Helpline -->
                        <div class="space-y-4">
                            <div class="p-4 bg-[#FFFDF7] rounded-2xl border border-amber-100 space-y-1">
                                <span class="text-[11px] font-bold text-slate-400 uppercase font-mono tracking-wider">
                                    <span x-show="lang === 'en'">Registered Office Address</span>
                                    <span x-show="lang === 'hi'" x-cloak>पंजीकृत कार्यालय</span>
                                </span>
                                <p x-show="lang === 'en'"
                                    class="text-xs sm:text-sm text-slate-800 font-medium leading-relaxed">
                                    G-46, Shivalik Nagar, BHEL, Ranipur More, Haridwar, Uttarakhand – 249403, India
                                </p>
                                <p x-show="lang === 'hi'" x-cloak
                                    class="text-xs sm:text-sm text-slate-800 font-medium hindi-text">
                                    G-46, शिवालिक नगर, BHEL, रानीपुर मोड़, हरिद्वार, उत्तराखंड – 249403
                                </p>
                            </div>

                            <div class="p-4 bg-[#FFFDF7] rounded-2xl border border-amber-100 space-y-2">
                                <span class="text-[11px] font-bold text-slate-400 uppercase font-mono tracking-wider">
                                    <span x-show="lang === 'en'">Devotee Helpline &amp; WhatsApp Support</span>
                                    <span x-show="lang === 'hi'" x-cloak>श्रद्धालु सहायता एवं WhatsApp</span>
                                </span>
                                <div class="flex items-center gap-3">
                                    <a href="tel:+917042482555"
                                        class="text-sm font-bold text-[#D97706] hover:underline font-mono">
                                        +91 70424 82555
                                    </a>
                                    <a href="https://wa.me/917042482555?text={{ urlencode('Jai Gange! I would like to inquire about Blu Bliss products and rituals.') }}"
                                        target="_blank" rel="noopener"
                                        class="px-3 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-semibold inline-flex items-center gap-1 shadow-xs transition">
                                        <span>💬 WhatsApp</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- 6. Explore Offerings CTA Strip -->
                <div
                    class="bg-gradient-to-r from-amber-500/15 via-amber-500/5 to-amber-500/15 border border-amber-300/60 rounded-3xl p-8 sm:p-10 text-center space-y-4">
                    <h3 x-show="lang === 'en'" class="text-xl sm:text-2xl font-serif font-bold text-slate-900">
                        Experience the Sanctity of Haridwar at Your Doorstep
                    </h3>
                    <h3 x-show="lang === 'hi'" x-cloak
                        class="text-xl sm:text-2xl font-serif font-bold text-slate-900 hindi-title">
                        हरिद्वार की पवित्रता का अनुभव सीधे अपने घर पर करें
                    </h3>

                    <p x-show="lang === 'en'" class="text-xs sm:text-sm text-slate-600 max-w-xl mx-auto">
                        Explore our laboratory-tested Gangajal in brass, copper, and glass containers, or schedule an
                        authentic personalized ghat puja.
                    </p>
                    <p x-show="lang === 'hi'" x-cloak
                        class="text-xs sm:text-sm text-slate-600 hindi-text max-w-xl mx-auto">
                        पीतल, तांबे एवं कांच की बोतलों में शुद्ध गंगाजल प्राप्त करें अथवा पावन घाट पर लाइव वैदिक पूजा
                        संकल्प बुक करें।
                    </p>

                    <div class="pt-3 flex flex-wrap items-center justify-center gap-4">
                        <a href="{{ route('products.index') }}"
                            class="px-6 py-3 bg-[#D97706] hover:bg-[#F97316] text-white rounded-xl font-bold text-xs sm:text-sm shadow-md transition transform hover:-translate-y-0.5">
                            <span x-show="lang === 'en'">Explore Sacred Products →</span>
                            <span x-show="lang === 'hi'" x-cloak>पवित्र उत्पाद देखें →</span>
                        </a>
                        <a href="{{ route('online-puja') }}"
                            class="px-6 py-3 bg-[#881337] hover:bg-[#701A28] text-white rounded-xl font-bold text-xs sm:text-sm shadow-md transition transform hover:-translate-y-0.5">
                            <span x-show="lang === 'en'">Book Haridwar Live Puja →</span>
                            <span x-show="lang === 'hi'" x-cloak>ऑनलाइन पूजा संकल्प लें →</span>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
<x-app-layout>
    <!-- 1. Sacred Vedic Hero Banner -->
    <section class="relative bg-gradient-to-b from-[#25050A] via-[#380B12] to-[#25050A] text-white overflow-hidden py-14 sm:py-20 border-b border-amber-300/30">
        <!-- Authentic Haridwar Ganga Ghat Background Image -->
        <img src="{{ asset('images/haridwar-panorama-clocktower.jpg') }}"
             alt="Sacred Haridwar Ganga Ghats"
             class="absolute inset-0 w-full h-full object-cover opacity-25 object-center">
        <div class="absolute inset-0 bg-gradient-to-t from-[#25050A] via-[#25050A]/75 to-transparent pointer-events-none"></div>

        <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-5">
            <!-- Vedic Sloka Banner -->
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs sm:text-sm font-semibold bg-[#D97706]/20 text-amber-200 border border-[#D97706]/40 backdrop-blur-xs shadow-inner">
                <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                <span class="tracking-wide">गङ्गे च यमुने चैव गोदावरि सरस्वति । नर्मदे सिन्धु कावेरि जलेऽस्मिन् सन्निधिं कुरु ॥</span>
            </div>

            <!-- Page Title -->
            <div class="space-y-2 max-w-3xl mx-auto">
                <h1 class="text-3xl sm:text-5xl lg:text-[46px] font-serif font-extrabold text-white tracking-tight leading-snug drop-shadow-[0_2px_4px_rgba(0,0,0,0.6)]">
                    हरिद्वार पावन घाट ऑनलाइन पूजा एवं वैदिक अनुष्ठान
                </h1>
                <p class="text-sm sm:text-base text-amber-100/90 leading-relaxed font-normal max-w-2xl mx-auto">
                    हरिद्वार के पावन गंगा घाटों से सीधे आपके नाम और गोत्र से वैदिक आचार्यों द्वारा संकल्पित लाइव पूजन,
                    15–20 मिनट की व्यक्तिगत लाइव स्ट्रीमिंग, तथा घर पर अभिमंत्रित प्रसाद व 500ml गंगाजल की पावन डिलीवरी।
                </p>
            </div>

            <!-- 4 Trust Markers Grid -->
            <div class="pt-4 grid grid-cols-2 sm:grid-cols-4 gap-3 max-w-4xl mx-auto text-left">
                <div class="p-3 bg-white/5 border border-amber-300/20 rounded-2xl backdrop-blur-xs">
                    <span class="text-xl">🪔</span>
                    <h2 class="text-xs font-bold text-amber-300 mt-1">व्यक्तिगत वैदिक संकल्प</h2>
                    <p class="text-[11px] text-amber-100/80 leading-snug mt-0.5">नाम, गोत्र व जन्म/विवाह तिथि का स्पष्ट उच्चारण।</p>
                </div>
                <div class="p-3 bg-white/5 border border-amber-300/20 rounded-2xl backdrop-blur-xs">
                    <span class="text-xl">📹</span>
                    <h2 class="text-xs font-bold text-amber-300 mt-1">15-20 Min लाइव स्ट्रीमिंग</h2>
                    <p class="text-[11px] text-amber-100/80 leading-snug mt-0.5">Zoom / WhatsApp Video पर सपरिवार सीधा दर्शन।</p>
                </div>
                <div class="p-3 bg-white/5 border border-amber-300/20 rounded-2xl backdrop-blur-xs">
                    <span class="text-xl">📦</span>
                    <h2 class="text-xs font-bold text-amber-300 mt-1">डोरस्टेप मंगल प्रसाद</h2>
                    <p class="text-[11px] text-amber-100/80 leading-snug mt-0.5">अभिमंत्रित 500ml गंगाजल, मौली, रोली व सूखा पंचमेवा।</p>
                </div>
                <div class="p-3 bg-white/5 border border-amber-300/20 rounded-2xl backdrop-blur-xs">
                    <span class="text-xl">📜</span>
                    <h2 class="text-xs font-bold text-amber-300 mt-1">डिजिटल संकल्प प्रमाण-पत्र</h2>
                    <p class="text-[11px] text-amber-100/80 leading-snug mt-0.5">संपादित HD वीडियो क्लिप व Blessing Certificate।</p>
                </div>
            </div>

            <!-- Quick Action Anchor -->
            <div class="pt-2 flex flex-wrap items-center justify-center gap-3">
                <a href="#puja-packages"
                   class="inline-flex items-center gap-2 px-6 py-3 bg-[#D97706] hover:bg-[#F97316] text-white rounded-xl font-bold text-sm shadow-xl transition transform hover:-translate-y-0.5">
                    <span>पावन पूजा पैकेज देखें</span>
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                    </svg>
                </a>
                <a href="#booking-form"
                   class="inline-flex items-center gap-2 px-6 py-3 bg-white/10 hover:bg-white/20 text-white border border-white/20 rounded-xl font-bold text-sm backdrop-blur-xs transition">
                    <span>सीधा संकल्प फॉर्म भरें</span>
                </a>
            </div>
        </div>
    </section>

    <!-- Main Container -->
    <div class="bg-[#FFFDF7] text-slate-800"
         x-data="{
             selectedPackage: 'पैकेज 1: मंगल उत्सव एवं आयुष्मान संकल्प पूजा (₹5,100/-)',
             choosePackage(pkgName) {
                 this.selectedPackage = pkgName;
                 document.getElementById('booking-form')?.scrollIntoView({ behavior: 'smooth' });
             }
         }">

        <!-- 2. Sacred Puja Packages Section -->
        <section id="puja-packages" class="py-14 sm:py-20 border-b border-amber-200/60 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-12 space-y-3">
                <span class="inline-block px-3.5 py-1 rounded-full text-xs font-bold bg-[#D97706]/15 text-[#881337] border border-[#D97706]/30 uppercase tracking-wider font-mono">
                    Sacred Vedic Puja Offerings
                </span>
                <h2 class="text-2xl sm:text-4xl font-serif font-extrabold text-slate-900 leading-snug">
                    हरिद्वार पावन घाट विशेष पूजा पैकेज
                </h2>
                <p class="text-sm text-slate-600 leading-relaxed max-w-xl mx-auto">
                    अपनी तिथि और अवसर के अनुसार उपयुक्त अनुष्ठान का चयन करें। प्रत्येक पूजा हरिद्वार के विद्वान तीर्थ पुरोहितों द्वारा वैदिक विधि से संपन्न की जाती है।
                </p>
            </div>

            <!-- Two Puja Package Cards Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-stretch">

                <!-- PACKAGE 1 -->
                <div class="relative bg-white rounded-3xl border-2 border-amber-200 hover:border-amber-400 p-6 sm:p-8 shadow-xl shadow-amber-950/5 hover:shadow-2xl hover:shadow-amber-900/10 transition-all duration-300 flex flex-col justify-between">
                    <!-- Top Ribbon -->
                    <div>
                        <div class="flex items-center justify-between gap-3 mb-4">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-900 border border-amber-200">
                                <span>🎉</span>
                                <span>पारिवारिक व व्यक्तिगत शुभ अवसर</span>
                            </span>
                            <span class="text-xs font-bold text-slate-400 font-mono">Package 01</span>
                        </div>

                        <!-- Title & Pricing -->
                        <h3 class="text-xl sm:text-2xl font-serif font-bold text-[#881337] leading-snug">
                            पैकेज 1: "मंगल उत्सव एवं आयुष्मान संकल्प पूजा"
                        </h3>
                        <p class="text-xs sm:text-sm text-slate-600 mt-1 font-medium">
                            (जन्मदिन, विवाह वर्षगांठ एवं पारिवारिक शुभ अवसरों के लिए)
                        </p>

                        <div class="mt-4 pb-5 border-b border-amber-100 flex items-baseline gap-2">
                            <span class="text-3xl sm:text-4xl font-serif font-extrabold text-[#D97706]">₹5,100</span>
                            <span class="text-xs text-slate-500 font-medium">/ संपूर्ण अनुष्ठान दक्षिणा</span>
                        </div>

                        <!-- 1. Rituals -->
                        <div class="mt-6 space-y-4">
                            <h4 class="text-xs font-bold uppercase tracking-wider text-[#881337] flex items-center gap-2 font-mono">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#D97706]"></span>
                                <span>Blu Bliss द्वारा किए जाने वाले अनुष्ठान (Rituals)</span>
                            </h4>
                            <ul class="space-y-3 text-xs sm:text-[13px] text-slate-700 leading-relaxed">
                                <li class="flex items-start gap-2.5">
                                    <span class="text-amber-600 mt-0.5 shrink-0">✦</span>
                                    <div>
                                        <strong class="text-slate-900 font-semibold">विशेष संकल्प:</strong>
                                        हरिद्वार के पावन घाट पर आपके (या दंपति/परिवार के) पूरे नाम, गोत्र और जन्म तिथि/वर्षगांठ के उच्चारण के साथ वैदिक आचार्यों द्वारा संकल्प।
                                    </div>
                                </li>
                                <li class="flex items-start gap-2.5">
                                    <span class="text-amber-600 mt-0.5 shrink-0">✦</span>
                                    <div>
                                        <strong class="text-slate-900 font-semibold">आयुष्य एवं सौभाग्य पाठ:</strong>
                                        जन्मदिन पर दीर्घायु और उत्तम स्वास्थ्य के लिए आयुष्य सूक्त पाठ, तथा विवाह वर्षगांठ पर अखंड सौभाग्य और सुख-समृद्धि के लिए माँ गंगा व गौरी-शंकर का विशेष पूजन।
                                    </div>
                                </li>
                                <li class="flex items-start gap-2.5">
                                    <span class="text-amber-600 mt-0.5 shrink-0">✦</span>
                                    <div>
                                        <strong class="text-slate-900 font-semibold">पावन दुग्धाभिषेक एवं दीपदान:</strong>
                                        माँ गंगा को पुष्प, दूध और शुद्ध घी के 21 दीपों का दीपदान तथा विशेष आरती।
                                    </div>
                                </li>
                            </ul>
                        </div>

                        <!-- 2. Live Streaming -->
                        <div class="mt-6 p-4 bg-amber-50/60 rounded-2xl border border-amber-200/80 space-y-1.5">
                            <h4 class="text-xs font-bold text-[#881337] flex items-center gap-2">
                                <span>📹</span>
                                <span>लाइव स्ट्रीमिंग (Live Streaming)</span>
                            </h4>
                            <p class="text-xs text-slate-700 leading-relaxed">
                                पूजा के दौरान <strong>15 से 20 मिनट की व्यक्तिगत लाइव स्ट्रीमिंग</strong> (Zoom / WhatsApp Video / Private Link के माध्यम से), ताकि आप और आपका परिवार घर बैठे सीधे घाट के दर्शन और लाइव संकल्प में शामिल हो सकें।
                            </p>
                        </div>

                        <!-- 3. Deliverables -->
                        <div class="mt-6 space-y-3">
                            <h4 class="text-xs font-bold uppercase tracking-wider text-[#881337] flex items-center gap-2 font-mono">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#D97706]"></span>
                                <span>डिवोटीज को क्या-क्या डिलीवर होगा (Deliverables)</span>
                            </h4>
                            <ul class="space-y-2 text-xs sm:text-[13px] text-slate-700 leading-relaxed">
                                <li class="flex items-start gap-2">
                                    <span class="text-emerald-600 font-bold">✓</span>
                                    <span><strong>लाइव पूजा लिंक और रिकॉर्डेड HD वीडियो:</strong> पूजा के तुरंत बाद संपूर्ण अनुष्ठान का संपादित (Edited) HD वीडियो।</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-emerald-600 font-bold">✓</span>
                                    <span><strong>Blu Bliss 500ml शुद्ध गंगाजल:</strong> पूजा में अभिमंत्रित की गई Blu Bliss की 500ml पवित्र गंगाजल की बोतल।</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-emerald-600 font-bold">✓</span>
                                    <span><strong>मंगल प्रसाद बॉक्स:</strong> अभिमंत्रित रक्षा सूत्र (मौली), अष्टगंध/रोली, चावल, और सूखा पंचमेवा प्रसाद कूरियर द्वारा सीधे आपके घर पर।</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-emerald-600 font-bold">✓</span>
                                    <span><strong>डिजिटल शुभकामना एवं संकल्प प्रमाण-पत्र:</strong> Blu Bliss की ओर से एक सुंदर डिजिटल Blessing Certificate।</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- CTA -->
                    <div class="mt-8 pt-6 border-t border-slate-100 flex flex-col sm:flex-row items-center gap-3">
                        <button type="button"
                                @click="choosePackage('पैकेज 1: मंगल उत्सव एवं आयुष्मान संकल्प पूजा (₹5,100/-)')"
                                class="w-full sm:flex-1 py-3 px-5 bg-[#D97706] hover:bg-[#F97316] text-white rounded-xl font-bold text-xs sm:text-sm text-center shadow-lg transition transform hover:-translate-y-0.5 cursor-pointer">
                            संकल्प लें · Book Package 1 (@₹5,100)
                        </button>
                        <a href="https://wa.me/917042482555?text={{ urlencode('जय गंगे! मैं पैकेज 1: मंगल उत्सव एवं आयुष्मान संकल्प पूजा (₹5100) की जानकारी व बुकिंग करना चाहता हूँ।') }}"
                           target="_blank"
                           rel="noopener"
                           class="w-full sm:w-auto py-3 px-4 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-xs inline-flex items-center justify-center gap-1.5 shadow-sm transition">
                            <span>💬 WhatsApp पर पूछें</span>
                        </a>
                    </div>
                </div>

                <!-- PACKAGE 2 -->
                <div class="relative bg-white rounded-3xl border-2 border-[#881337]/40 hover:border-[#881337] p-6 sm:p-8 shadow-xl shadow-rose-950/5 hover:shadow-2xl hover:shadow-rose-900/10 transition-all duration-300 flex flex-col justify-between">
                    <!-- Featured Badge -->
                    <div class="absolute -top-3.5 right-6 bg-[#881337] text-white text-[11px] font-bold px-3 py-1 rounded-full shadow-md tracking-wide">
                        🌟 महा-पर्व विशेष अनुष्ठान
                    </div>

                    <!-- Top Ribbon -->
                    <div>
                        <div class="flex items-center justify-between gap-3 mb-4">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-rose-50 text-[#881337] border border-rose-200">
                                <span>🔱</span>
                                <span>दुर्लभ मुहूर्त एवं पावन पर्व</span>
                            </span>
                            <span class="text-xs font-bold text-slate-400 font-mono">Package 02</span>
                        </div>

                        <!-- Title & Pricing -->
                        <h3 class="text-xl sm:text-2xl font-serif font-bold text-[#881337] leading-snug">
                            पैकेज 2: "महा-तिथि एवं पर्व विशेष दिव्य अनुष्ठान"
                        </h3>
                        <p class="text-xs sm:text-sm text-slate-600 mt-1 font-medium">
                            (अमावस्या, पूर्णिमा, ग्रहण काल, एकादशी एवं अन्य विशेष पर्वों के लिए)
                        </p>

                        <div class="mt-4 pb-5 border-b border-rose-100 flex items-baseline gap-2">
                            <span class="text-3xl sm:text-4xl font-serif font-extrabold text-[#881337]">₹11,000</span>
                            <span class="text-xs text-slate-500 font-medium">/ संपूर्ण महा-अनुष्ठान दक्षिणा</span>
                        </div>

                        <!-- 1. Rituals -->
                        <div class="mt-6 space-y-4">
                            <h4 class="text-xs font-bold uppercase tracking-wider text-[#881337] flex items-center gap-2 font-mono">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#881337]"></span>
                                <span>Blu Bliss द्वारा किए जाने वाले अनुष्ठान (Rituals)</span>
                            </h4>
                            <ul class="space-y-3 text-xs sm:text-[13px] text-slate-700 leading-relaxed">
                                <li class="flex items-start gap-2.5">
                                    <span class="text-[#881337] mt-0.5 shrink-0">✦</span>
                                    <div>
                                        <strong class="text-slate-900 font-semibold">तिथि-विशेष संकल्प:</strong>
                                        संबंधित पर्व (जैसे पूर्णिमा पर सत्यनारायण व माँ गंगा पूजन, अमावस्या पर पितृ शांति व कष्ट निवारण संकल्प, या ग्रहण काल में शुद्धि व शांति पाठ) के अनुसार वैदिक विधि से पूजा।
                                    </div>
                                </li>
                                <li class="flex items-start gap-2.5">
                                    <span class="text-[#881337] mt-0.5 shrink-0">✦</span>
                                    <div>
                                        <strong class="text-slate-900 font-semibold">ग्रह दोष एवं नकारात्मकता निवारण:</strong>
                                        कुंडली के दोषों, मानसिक अशांति और घर की नकारात्मक ऊर्जा को दूर करने के लिए विशेष वैदिक मंत्र जाप और हवन आहुति।
                                    </div>
                                </li>
                                <li class="flex items-start gap-2.5">
                                    <span class="text-[#881337] mt-0.5 shrink-0">✦</span>
                                    <div>
                                        <strong class="text-slate-900 font-semibold">गंगा आरती एवं ब्रह्मलीन अर्पण:</strong>
                                        घाट पर ब्राह्मणों द्वारा आपके नाम से दीपदान, पुष्प अर्पण और विशेष प्रार्थना।
                                    </div>
                                </li>
                            </ul>
                        </div>

                        <!-- 2. Live Streaming -->
                        <div class="mt-6 p-4 bg-rose-50/70 rounded-2xl border border-rose-200/80 space-y-1.5">
                            <h4 class="text-xs font-bold text-[#881337] flex items-center gap-2">
                                <span>📹</span>
                                <span>लाइव स्ट्रीमिंग (Live Streaming)</span>
                            </h4>
                            <p class="text-xs text-slate-700 leading-relaxed">
                                पावन तिथि पर घाट से <strong>सीधी लाइव स्ट्रीमिंग (Live Streaming)</strong>, जिससे आप घर बैठे ही उस दुर्लभ मुहूर्त में घाट की दिव्य ऊर्जा और आरती के साक्षी बन सकें।
                            </p>
                        </div>

                        <!-- 3. Deliverables -->
                        <div class="mt-6 space-y-3">
                            <h4 class="text-xs font-bold uppercase tracking-wider text-[#881337] flex items-center gap-2 font-mono">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#881337]"></span>
                                <span>डिवोटीज को क्या-क्या डिलीवर होगा (Deliverables)</span>
                            </h4>
                            <ul class="space-y-2 text-xs sm:text-[13px] text-slate-700 leading-relaxed">
                                <li class="flex items-start gap-2">
                                    <span class="text-emerald-600 font-bold">✓</span>
                                    <span><strong>लाइव स्ट्रीमिंग एक्सेस एवं वीडियो क्लिप:</strong> लाइव दर्शन के साथ-साथ WhatsApp पर पूजा का विशेष वीडियो।</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-emerald-600 font-bold">✓</span>
                                    <span><strong>अभिमंत्रित Blu Bliss 500ml गंगाजल:</strong> विशेष पर्व की ऊर्जा से अभिमंत्रित शुद्ध Blu Bliss गंगाजल, जिसका छिड़काव आप पूरे घर में कर सकते हैं।</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-emerald-600 font-bold">✓</span>
                                    <span><strong>रक्षा कवच एवं प्रसाद किट:</strong> अभिमंत्रित रक्षा सूत्र, भस्म/तिलक, और मंदिर का पावन प्रसाद आपके पते पर डोरस्टेप डिलीवरी।</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-emerald-600 font-bold">✓</span>
                                    <span><strong>डिजिटल अनुष्ठान प्रमाण-पत्र:</strong> संबंधित पर्व और आपके संकल्प विवरण के साथ प्रमाण-पत्र।</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- CTA -->
                    <div class="mt-8 pt-6 border-t border-slate-100 flex flex-col sm:flex-row items-center gap-3">
                        <button type="button"
                                @click="choosePackage('पैकेज 2: महा-तिथि एवं पर्व विशेष दिव्य अनुष्ठान (₹11,000/-)')"
                                class="w-full sm:flex-1 py-3 px-5 bg-[#881337] hover:bg-[#701A28] text-white rounded-xl font-bold text-xs sm:text-sm text-center shadow-lg transition transform hover:-translate-y-0.5 cursor-pointer">
                            महा-संकल्प लें · Book Package 2 (@₹11,000)
                        </button>
                        <a href="https://wa.me/917042482555?text={{ urlencode('जय गंगे! मैं पैकेज 2: महा-तिथि एवं पर्व विशेष दिव्य अनुष्ठान (₹11000) की बुकिंग एवं मुहूर्त पर चर्चा करना चाहता हूँ।') }}"
                           target="_blank"
                           rel="noopener"
                           class="w-full sm:w-auto py-3 px-4 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-xs inline-flex items-center justify-center gap-1.5 shadow-sm transition">
                            <span>💬 WhatsApp पर पूछें</span>
                        </a>
                    </div>
                </div>

            </div>
        </section>

        <!-- 3. How It Works - 4 Steps -->
        <section class="py-14 sm:py-16 bg-[#FFFBEB] border-b border-amber-200/60">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-2xl mx-auto mb-10 space-y-2">
                    <span class="text-xs font-extrabold text-[#881337] uppercase tracking-wider font-mono">Simple & Pure</span>
                    <h2 class="text-2xl sm:text-3xl font-serif font-bold text-slate-900">
                        संकल्प से प्रसाद प्राप्ति तक की सरल प्रक्रिया
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-600">
                        घर बैठे कुछ ही मिनटों में अपना व्यक्तिगत वैदिक संकल्प बुक करें:
                    </p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="p-5 bg-white rounded-2xl border border-amber-200 shadow-sm space-y-2 relative">
                        <span class="w-7 h-7 rounded-full bg-[#D97706] text-white text-xs font-bold flex items-center justify-center">1</span>
                        <h4 class="text-sm font-bold text-slate-900">संकल्प विवरण दर्ज करें</h4>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            अपना पैकेज चुनें तथा यजमान का पूरा नाम, गोत्र, शुभ तिथि और प्रसाद भेजने का पता भरें।
                        </p>
                    </div>

                    <div class="p-5 bg-white rounded-2xl border border-amber-200 shadow-sm space-y-2 relative">
                        <span class="w-7 h-7 rounded-full bg-[#D97706] text-white text-xs font-bold flex items-center justify-center">2</span>
                        <h4 class="text-sm font-bold text-slate-900">लाइव स्ट्रीमिंग लिंक</h4>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            पूजा से पूर्व आपके WhatsApp और ईमेल पर लाइव घाट दर्शन व संकल्प का व्यक्तिगत Zoom / Video लिंक भेजा जाएगा।
                        </p>
                    </div>

                    <div class="p-5 bg-white rounded-2xl border border-amber-200 shadow-sm space-y-2 relative">
                        <span class="w-7 h-7 rounded-full bg-[#D97706] text-white text-xs font-bold flex items-center justify-center">3</span>
                        <h4 class="text-sm font-bold text-slate-900">हरिद्वार घाट लाइव पूजन</h4>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            विद्वान पुरोहित आपके नाम-गोत्र से संकल्प लेंगे। माँ गंगा की विशेष आरती, दुग्धाभिषेक व दीपदान होगा।
                        </p>
                    </div>

                    <div class="p-5 bg-white rounded-2xl border border-amber-200 shadow-sm space-y-2 relative">
                        <span class="w-7 h-7 rounded-full bg-[#D97706] text-white text-xs font-bold flex items-center justify-center">4</span>
                        <h4 class="text-sm font-bold text-slate-900">प्रसाद व गंगाजल डिलीवरी</h4>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            पूजा उपरांत अभिमंत्रित 500ml Blu Bliss गंगाजल, मंगल रक्षा सूत्र व पावन प्रसाद बॉक्स आपके घर कूरियर होगा।
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- 4. Interactive Sankalp Booking Form & Exact Checkout Format -->
        <section id="booking-form" class="py-14 sm:py-20 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-3xl border-2 border-amber-200 p-6 sm:p-10 shadow-xl shadow-amber-950/5 space-y-6">

                <!-- Header of Form -->
                <div class="border-b border-amber-100 pb-5 text-center space-y-2">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-[#D97706]/15 text-[#881337] border border-[#D97706]/30 font-mono">
                        Online Puja Booking & Sankalp Form
                    </span>
                    <h2 class="text-2xl sm:text-3xl font-serif font-bold text-slate-900">
                        वैदिक संकल्प एवं बुकिंग विवरण
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-600 max-w-lg mx-auto">
                        कृपया अपना और अपने परिवार का सही नाम एवं गोत्र दर्ज करें ताकि हरिद्वार गंगा घाट पर पुरोहितों द्वारा सटीक संकल्प लिया जा सके।
                    </p>
                </div>

                @if (session('booking_success'))
                    <div class="p-6 bg-emerald-50 border-2 border-emerald-300 rounded-2xl space-y-3">
                        <div class="flex items-center gap-3 text-emerald-900">
                            <span class="text-2xl">🕉️</span>
                            <div>
                                <h4 class="font-bold text-base">आपका ऑनलाइन पूजा संकल्प सफलतापूर्वक दर्ज कर लिया गया है!</h4>
                                <p class="text-xs text-emerald-700 mt-0.5">
                                    यजमान: <strong>{{ session('booking_success')['devotee_name'] }}</strong> (गोत्र: {{ session('booking_success')['gotra'] }}) · संकल्प तिथि: {{ session('booking_success')['puja_date'] }}
                                </p>
                            </div>
                        </div>
                        <p class="text-xs text-slate-700 leading-relaxed">
                            हमारे तीर्थ प्रतिनिधि आपसे <strong>{{ session('booking_success')['phone'] }}</strong> पर संपर्क कर घाट मुहूर्त और लाइव स्ट्रीमिंग लिंक की पुष्टि करेंगे।
                        </p>
                        <div class="pt-2">
                            <a href="https://wa.me/917042482555?text={{ urlencode('जय गंगे! मैंने ऑनलाइन पूजा (' . session('booking_success')['package'] . ') के लिए संकल्प फॉर्म भर दिया है। यजमान: ' . session('booking_success')['devotee_name'] . '। कृपया लाइव स्ट्रीमिंग समय की पुष्टि करें।') }}"
                               target="_blank"
                               rel="noopener"
                               class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold shadow-xs transition">
                                <span>💬 WhatsApp पर तुरंत पुष्टि करें</span>
                            </a>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('online-puja.book') }}" class="space-y-6 text-sm">
                    @csrf

                    <!-- 1. Package Selection -->
                    <div>
                        <label class="block text-xs font-bold text-slate-800 uppercase tracking-wider mb-2 font-mono">
                            पूजा पैकेज चयन (Select Puja Package) <span class="text-red-600">*</span>
                        </label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <label class="flex items-start gap-3 p-3.5 rounded-xl border-2 transition cursor-pointer"
                                   :class="selectedPackage.includes('पैकेज 1') ? 'border-[#D97706] bg-amber-50/60 shadow-xs' : 'border-slate-200 hover:border-amber-200'">
                                <input type="radio"
                                       name="package"
                                       value="पैकेज 1: मंगल उत्सव एवं आयुष्मान संकल्प पूजा (₹5,100/-)"
                                       x-model="selectedPackage"
                                       class="mt-1 text-[#D97706] focus:ring-[#D97706]">
                                <div>
                                    <span class="block text-xs font-bold text-slate-900">पैकेज 1: मंगल उत्सव एवं आयुष्मान पूजा</span>
                                    <span class="block text-[11px] text-slate-500 mt-0.5">जन्मदिन, वर्षगांठ व पारिवारिक शुभ अवसर</span>
                                    <span class="block text-xs font-extrabold text-[#D97706] mt-1 font-serif">₹5,100/-</span>
                                </div>
                            </label>

                            <label class="flex items-start gap-3 p-3.5 rounded-xl border-2 transition cursor-pointer"
                                   :class="selectedPackage.includes('पैकेज 2') ? 'border-[#881337] bg-rose-50/60 shadow-xs' : 'border-slate-200 hover:border-amber-200'">
                                <input type="radio"
                                       name="package"
                                       value="पैकेज 2: महा-तिथि एवं पर्व विशेष दिव्य अनुष्ठान (₹11,000/-)"
                                       x-model="selectedPackage"
                                       class="mt-1 text-[#881337] focus:ring-[#881337]">
                                <div>
                                    <span class="block text-xs font-bold text-slate-900">पैकेज 2: महा-तिथि एवं पर्व अनुष्ठान</span>
                                    <span class="block text-[11px] text-slate-500 mt-0.5">अमावस्या, पूर्णिमा, ग्रहण व एकादशी</span>
                                    <span class="block text-xs font-extrabold text-[#881337] mt-1 font-serif">₹11,000/-</span>
                                </div>
                            </label>
                        </div>
                        @error('package') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- 2. Sankalp Details (Name, Gotra, Date, Occasion) -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                यजमान का पूरा नाम (Devotee / Couple Name) <span class="text-red-600">*</span>
                            </label>
                            <input type="text"
                                   name="devotee_name"
                                   value="{{ old('devotee_name', auth()->user()?->name) }}"
                                   required
                                   placeholder="उदा. राहुल शर्मा / राहुल व प्रिया शर्मा"
                                   class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-sm focus:outline-none focus:border-[#D97706] focus:ring-1 focus:ring-[#D97706]">
                            @error('devotee_name') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                गोत्र (Gotra) <span class="text-red-600">*</span>
                            </label>
                            <input type="text"
                                   name="gotra"
                                   value="{{ old('gotra') }}"
                                   required
                                   placeholder="उदा. कश्यप, भारद्वाज, वशिष्ठ (यदि ज्ञात न हो तो 'कश्यप' लिखें)"
                                   class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-sm focus:outline-none focus:border-[#D97706] focus:ring-1 focus:ring-[#D97706]">
                            <p class="text-[10px] text-slate-500 mt-1">शास्त्रानुसार गोत्र ज्ञात न होने पर 'कश्यप' गोत्र से संकल्प लिया जाता है।</p>
                            @error('gotra') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                संकल्प की पावन तिथि (Auspicious Date) <span class="text-red-600">*</span>
                            </label>
                            <input type="date"
                                   name="puja_date"
                                   value="{{ old('puja_date', now()->addDays(2)->format('Y-m-d')) }}"
                                   min="{{ now()->addDay()->format('Y-m-d') }}"
                                   required
                                   class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-sm focus:outline-none focus:border-[#D97706] focus:ring-1 focus:ring-[#D97706]">
                            @error('puja_date') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                पावन अवसर / उद्देश्य (Occasion)
                            </label>
                            <input type="text"
                                   name="occasion"
                                   value="{{ old('occasion') }}"
                                   placeholder="उदा. 40वां जन्मदिन / 15वीं विवाह वर्षगांठ / पितृ शांति"
                                   class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-sm focus:outline-none focus:border-[#D97706] focus:ring-1 focus:ring-[#D97706]">
                            @error('occasion') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- 3. Contact Details (Phone/WhatsApp, Email) -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                फोन / WhatsApp नंबर (For Live Stream Link) <span class="text-red-600">*</span>
                            </label>
                            <input type="tel"
                                   name="phone"
                                   value="{{ old('phone', auth()->user()?->phone) }}"
                                   required
                                   placeholder="+91 9876543210"
                                   class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-sm font-mono focus:outline-none focus:border-[#D97706] focus:ring-1 focus:ring-[#D97706]">
                            <p class="text-[10px] text-slate-500 mt-1">इसी नंबर पर लाइव स्ट्रीमिंग लिंक और पूजा वीडियो भेजा जाएगा।</p>
                            @error('phone') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                ईमेल पता (Email Address)
                            </label>
                            <input type="email"
                                   name="email"
                                   value="{{ old('email', auth()->user()?->email) }}"
                                   placeholder="devotee@example.com"
                                   class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-sm focus:outline-none focus:border-[#D97706] focus:ring-1 focus:ring-[#D97706]">
                            <p class="text-[10px] text-slate-500 mt-1">डिजिटल संकल्प प्रमाण-पत्र और HD वीडियो बैकअप हेतु।</p>
                            @error('email') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- 4. Shipping Address for Consecrated Prasad Delivery -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">
                            प्रसाद डिलीवरी का पूरा पता (Complete Postal Address with Pincode) <span class="text-red-600">*</span>
                        </label>
                        <textarea name="shipping_address"
                                  rows="2"
                                  required
                                  placeholder="मकान संख्या, गली/सोसायटी, लैंडमार्क, शहर, राज्य एवं 6-अंकीय पिनकोड"
                                  class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-sm focus:outline-none focus:border-[#D97706] focus:ring-1 focus:ring-[#D97706]">{{ old('shipping_address') }}</textarea>
                        <p class="text-[10px] text-slate-500 mt-0.5">यहाँ Blu Bliss 500ml गंगाजल और मंगल प्रसाद बॉक्स सुरक्षित कूरियर किया जाएगा।</p>
                        @error('shipping_address') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- 5. Special Notes -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">
                            विशेष मनोकामना / पुरोहितों के लिए निर्देश (Optional)
                        </label>
                        <textarea name="special_instructions"
                                  rows="2"
                                  placeholder="यदि कोई विशिष्ट प्रार्थना या पारिवारिक सदस्यों के नाम संकल्प में सम्मिलित करने हों तो यहाँ लिखें..."
                                  class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-sm focus:outline-none focus:border-[#D97706] focus:ring-1 focus:ring-[#D97706]">{{ old('special_instructions') }}</textarea>
                    </div>

                    <!-- 6. MANDATORY BILINGUAL CHECKOUT POLICY (From Word Document) -->
                    <div class="p-4 bg-amber-50/70 border-2 border-amber-300/80 rounded-2xl space-y-3">
                        <label class="flex items-start gap-3 cursor-pointer">
                            <input type="checkbox"
                                   name="terms_agreed"
                                   value="1"
                                   required
                                   {{ old('terms_agreed') ? 'checked' : '' }}
                                   class="mt-1 rounded text-[#D97706] focus:ring-[#D97706] border-slate-400">
                            <div class="space-y-2 text-xs text-slate-800 leading-relaxed">
                                <!-- English Section -->
                                <p class="font-medium text-slate-900">
                                    I agree to the <a href="{{ route('terms') }}" target="_blank" class="text-[#D97706] font-bold underline hover:text-[#881337]">Terms &amp; Conditions</a> &amp; <a href="{{ route('privacy') }}" target="_blank" class="text-[#D97706] font-bold underline hover:text-[#881337]">Privacy Policy</a>. I confirm that the Name, Gotra, and puja date provided are correct. I understand that all online puja packages and live streaming services are <strong>100% Non-Refundable</strong> once booked, and rescheduling requires at least 24 hours' prior notice.
                                </p>
                                <!-- Hindi Section -->
                                <p class="text-slate-700 border-t border-amber-200/80 pt-2">
                                    मैं <a href="{{ route('terms') }}" target="_blank" class="text-[#D97706] font-bold underline hover:text-[#881337]">नियम एवं शर्तों (Terms &amp; Conditions)</a> से सहमत हूँ। मेरे द्वारा दिए गए नाम और गोत्र के अनुसार ही हरिद्वार घाट पर संकल्प लिया जाएगा। मुझे ज्ञात है कि सभी ऑनलाइन पूजा और लाइव स्ट्रीमिंग शुल्क <strong>100% गैर-वापसी योग्य (Non-Refundable)</strong> हैं।
                                </p>
                            </div>
                        </label>
                        @error('terms_agreed') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Submit Actions -->
                    <div class="pt-2 flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div class="flex items-center gap-2 text-xs text-slate-600">
                            <span class="text-base text-emerald-600">🔒</span>
                            <span>सुरक्षित संकल्प पंजीकरण एवं हरिद्वार तीर्थ सेवा</span>
                        </div>
                        <button type="submit"
                                class="w-full sm:w-auto px-8 py-3.5 bg-[#D97706] hover:bg-[#F97316] text-white font-bold text-sm rounded-xl shadow-xl transition transform hover:-translate-y-0.5 cursor-pointer">
                            वैदिक संकल्प व पूजा बुक करें →
                        </button>
                    </div>
                </form>

            </div>
        </section>

        <!-- 5. Frequently Asked Questions (FAQ) Section -->
        <section class="py-14 sm:py-16 border-t border-amber-200/60 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <div class="text-center space-y-2">
                <span class="text-xs font-extrabold text-[#881337] uppercase tracking-wider font-mono">Devotee Inquiries</span>
                <h2 class="text-2xl sm:text-3xl font-serif font-bold text-slate-900">
                    ऑनलाइन पूजा संबंधित सामान्य प्रश्न (FAQs)
                </h2>
            </div>

            <div class="space-y-3" x-data="{ activeFaq: null }">
                <!-- FAQ 1 -->
                <div class="border border-amber-200 rounded-2xl bg-white overflow-hidden shadow-xs">
                    <button type="button"
                            @click="activeFaq = (activeFaq === 1 ? null : 1)"
                            class="w-full p-4 text-left font-bold text-sm text-slate-900 flex items-center justify-between gap-4 hover:bg-amber-50/50 transition">
                        <span>1. यदि मुझे अपना गोत्र ज्ञात न हो तो संकल्प कैसे लिया जाएगा?</span>
                        <span class="text-base font-mono text-[#D97706]" x-text="activeFaq === 1 ? '−' : '+'">+</span>
                    </button>
                    <div x-show="activeFaq === 1" x-cloak class="p-4 pt-0 text-xs text-slate-600 leading-relaxed border-t border-amber-100">
                        सनातन शास्त्रों के अनुसार यदि किसी व्यक्ति को अपना पैतृक गोत्र ज्ञात नहीं होता, तो वे महर्षि कश्यप के वंशज माने जाते हैं और उनका संकल्प 'कश्यप' गोत्र से विधिपूर्वक संपन्न कराया जाता है। यह पूर्णतः शास्त्रसम्मत और फलदायी है।
                    </div>
                </div>

                <!-- FAQ 2 -->
                <div class="border border-amber-200 rounded-2xl bg-white overflow-hidden shadow-xs">
                    <button type="button"
                            @click="activeFaq = (activeFaq === 2 ? null : 2)"
                            class="w-full p-4 text-left font-bold text-sm text-slate-900 flex items-center justify-between gap-4 hover:bg-amber-50/50 transition">
                        <span>2. लाइव स्ट्रीमिंग कैसे और किस माध्यम से आयोजित की जाती है?</span>
                        <span class="text-base font-mono text-[#D97706]" x-text="activeFaq === 2 ? '−' : '+'">+</span>
                    </button>
                    <div x-show="activeFaq === 2" x-cloak class="p-4 pt-0 text-xs text-slate-600 leading-relaxed border-t border-amber-100">
                        पूजा के निर्धारित समय से पूर्व आपको एक निजी Zoom / WhatsApp Video / Private Streaming लिंक भेजा जाता है। आप और आपका परिवार अपने मोबाइल या लैपटॉप से जुड़कर पुरोहितों को लाइव मंत्रोच्चार करते हुए देख सकते हैं तथा संकल्प के समय अपना हाथ जोड़कर सहभागिता कर सकते हैं।
                    </div>
                </div>

                <!-- FAQ 3 -->
                <div class="border border-amber-200 rounded-2xl bg-white overflow-hidden shadow-xs">
                    <button type="button"
                            @click="activeFaq = (activeFaq === 3 ? null : 3)"
                            class="w-full p-4 text-left font-bold text-sm text-slate-900 flex items-center justify-between gap-4 hover:bg-amber-50/50 transition">
                        <span>3. पूजा के बाद प्रसाद और 500ml गंगाजल कितने दिनों में प्राप्त होगा?</span>
                        <span class="text-base font-mono text-[#D97706]" x-text="activeFaq === 3 ? '−' : '+'">+</span>
                    </button>
                    <div x-show="activeFaq === 3" x-cloak class="p-4 pt-0 text-xs text-slate-600 leading-relaxed border-t border-amber-100">
                        अनुष्ठान संपन्न होने के 24 घंटे के भीतर आपका मंगल प्रसाद बॉक्स (अभिमंत्रित रक्षा सूत्र, रोली, अक्षत, सूखा पंचमेवा) तथा 500ml शुद्ध Blu Bliss गंगाजल बोतल विशेष पैकेजिंग में कूरियर द्वारा प्रेषित की जाती है। सामान्यतः 3 से 5 कार्यदिवसों में यह आपके पते पर पहुँच जाती है।
                    </div>
                </div>

                <!-- FAQ 4 -->
                <div class="border border-amber-200 rounded-2xl bg-white overflow-hidden shadow-xs">
                    <button type="button"
                            @click="activeFaq = (activeFaq === 4 ? null : 4)"
                            class="w-full p-4 text-left font-bold text-sm text-slate-900 flex items-center justify-between gap-4 hover:bg-amber-50/50 transition">
                        <span>4. क्या पूजा की तिथि बाद में बदली (Reschedule) जा सकती है?</span>
                        <span class="text-base font-mono text-[#D97706]" x-text="activeFaq === 4 ? '−' : '+'">+</span>
                    </button>
                    <div x-show="activeFaq === 4" x-cloak class="p-4 pt-0 text-xs text-slate-600 leading-relaxed border-t border-amber-100">
                        हाँ, यदि आपको किसी अपरिहार्य कारणवश तिथि बदलनी हो, तो कृपया निर्धारित पूजा समय से कम से कम 24 घंटे पूर्व हमारे हेल्पलाइन नंबर पर सूचित करें। सभी ऑनलाइन पूजा शुल्क गैर-वापसी योग्य (Non-Refundable) हैं, परंतु 24 घंटे पूर्व सूचना देने पर नई तिथि निर्धारित की जा सकती है।
                    </div>
                </div>
            </div>

            <!-- Helpline Card -->
            <div class="p-6 bg-[#25050A] text-white rounded-3xl border border-amber-300/30 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="space-y-1 text-center sm:text-left">
                    <h4 class="font-serif font-bold text-base text-amber-200">क्या आपके मन में कोई विशिष्ट प्रश्न या मुहूर्त संबंधी संशय है?</h4>
                    <p class="text-xs text-slate-300">हमारे तीर्थ पुरोहित समन्वय दल से सीधे बात करें या WhatsApp पर संदेश भेजें।</p>
                </div>
                <div class="flex items-center gap-3 shrink-0">
                    <a href="tel:{{ str_replace(' ', '', $supportPhone) }}"
                       class="px-4 py-2 bg-white/10 hover:bg-white/20 text-white rounded-xl text-xs font-bold transition">
                        📞 {{ $supportPhone }}
                    </a>
                    <a href="https://wa.me/917042482555?text={{ urlencode('जय गंगे! मैं ऑनलाइन पूजा संबंधी मार्गदर्शन लेना चाहता हूँ।') }}"
                       target="_blank"
                       rel="noopener"
                       class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow-xs transition">
                        💬 WhatsApp
                    </a>
                </div>
            </div>
        </section>

    </div>
</x-app-layout>

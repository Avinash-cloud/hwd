<x-app-layout>
    <!-- Sacred Heritage Hero Banner with Haridwar Panorama -->
    <div class="relative bg-stone-900 text-white overflow-hidden py-16 sm:py-24 border-b border-amber-200/40">
        <img src="{{ asset('images/haridwar-panorama-clocktower.jpg') }}"
             alt="Sacred Haridwar Panorama"
             class="absolute inset-0 w-full h-full object-cover opacity-35 object-center">
        <div class="absolute inset-0 bg-gradient-to-t from-stone-950 via-stone-950/70 to-stone-900/60"></div>

        <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-4">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-[#D97706]/30 text-amber-300 border border-[#D97706]/50 backdrop-blur-xs">
                <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                <span>Sacred Heritage · Haridwar Sourcing</span>
            </span>
            <h1 class="text-3xl sm:text-5xl font-serif font-extrabold text-white tracking-tight">
                The Divine Sanctity of Haridwar Gangajal
            </h1>
            <p class="text-sm sm:text-base text-slate-200 max-w-2xl mx-auto leading-relaxed">
                Why water collected directly from sacred Haridwar Ganga Ghats remains spiritually vital, medically pure, and sacred forever.
            </p>
        </div>
    </div>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-14 space-y-16 text-slate-700 leading-relaxed text-sm">
        
        <!-- 1. Sacred Heritage of Haridwar -->
        <section class="grid grid-cols-1 md:grid-cols-12 gap-8 items-center">
            <div class="md:col-span-7 space-y-4">
                <span class="text-xs font-extrabold text-[#881337] uppercase tracking-wider font-mono">Puranic Origin</span>
                <h2 class="text-2xl sm:text-3xl font-serif font-bold text-slate-900">The Sacred Heritage of Haridwar</h2>
                <p>
                    Haridwar ("Gateway to the Gods") is revered as one of the holiest places on earth.
                    According to the ancient Puranas, during the Samudra Manthan (churning of the cosmic ocean), drops of
                    the celestial Amrit (divine nectar) fell at this holy pilgrimage site along the banks of Maa Ganga.
                </p>
                <p>
                    For millennia, saints, rishis, and millions of devotees have performed pilgrimage to these sacred ghats, where
                    Maa Ganga enters the plains from the high Himalayas with pristine spiritual vigor and divine consciousness.
                </p>
            </div>
            <div class="md:col-span-5">
                <div class="relative rounded-3xl overflow-hidden border-2 border-amber-200/80 shadow-xl group">
                    <img src="{{ asset('images/haridwar-temples.jpg') }}"
                         alt="Sacred Temples at Haridwar"
                         class="w-full h-72 object-cover transition transform duration-500 group-hover:scale-105">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent flex items-end p-4">
                        <span class="text-xs font-bold text-white bg-[#881337]/90 px-3 py-1 rounded-lg backdrop-blur-xs">
                            Sacred Haridwar Ghats
                        </span>
                    </div>
                </div>
            </div>
        </section>

        <!-- 2. Sacred Sourcing Process -->
        <section class="bg-amber-50/70 border border-amber-200/80 p-8 sm:p-10 rounded-3xl space-y-6 shadow-xs">
            <div class="text-center max-w-2xl mx-auto">
                <span class="text-xs font-mono uppercase font-bold text-[#D97706]">Our Sanctified Protocol</span>
                <h3 class="text-2xl font-serif font-bold text-slate-900 mt-1">Untouched by Human Hands · Our Sacred Process</h3>
                <p class="text-xs text-slate-600 mt-1">Bottled with utmost Vedic purity straight from Haridwar morning Aarti.</p>
            </div>

            <!-- Process Visual Strip -->
            <div class="relative rounded-2xl overflow-hidden border border-amber-300/60 shadow-sm">
                <img src="{{ asset('images/haridwar-ghat-shrines.jpg') }}"
                     alt="Haridwar Shrines during Vedic Prayers"
                     class="w-full h-52 sm:h-64 object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/85 via-slate-950/30 to-transparent flex items-end p-4 sm:p-6">
                    <div class="text-white text-xs space-y-1">
                        <span class="font-bold text-amber-300 uppercase tracking-widest text-[10px]">Auspicious Sourcing Point</span>
                        <p class="text-white/90 font-serif text-sm">Collected at Brahma Muhurta (04:45 AM) during morning Ganga Aarti chanting</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-2">
                <div class="bg-white p-5 rounded-2xl border border-amber-200/60 space-y-2">
                    <div class="w-8 h-8 rounded-lg bg-[#D97706]/10 text-[#D97706] font-bold flex items-center justify-center text-xs">01</div>
                    <strong class="font-bold text-slate-900 block font-serif">Brahma Muhurta Collection</strong>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Collected in the auspicious pre-dawn hour (04:00 AM – 05:30 AM) while continuous Vedic hymns
                        resonate across Haridwar.
                    </p>
                </div>
                <div class="bg-white p-5 rounded-2xl border border-amber-200/60 space-y-2">
                    <div class="w-8 h-8 rounded-lg bg-[#D97706]/10 text-[#D97706] font-bold flex items-center justify-center text-xs">02</div>
                    <strong class="font-bold text-slate-900 block font-serif">Natural Micro-Filtration</strong>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Passed through multi-stage natural micron sediment filters that remove river silt while
                        retaining 100% natural mineral ions.
                    </p>
                </div>
                <div class="bg-white p-5 rounded-2xl border border-amber-200/60 space-y-2">
                    <div class="w-8 h-8 rounded-lg bg-[#D97706]/10 text-[#D97706] font-bold flex items-center justify-center text-xs">03</div>
                    <strong class="font-bold text-slate-900 block font-serif">Hermetic UV Sealing</strong>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Bottled immediately in UV-sterilized containers and sealed tamper-evident without human contact,
                        assigned a traceable QR code.
                    </p>
                </div>
            </div>
        </section>

        <!-- 3. Scientific Proof & Why Gangajal Never Spoils -->
        <section class="grid grid-cols-1 md:grid-cols-12 gap-8 items-center">
            <div class="md:col-span-5 order-2 md:order-1">
                <div class="relative rounded-3xl overflow-hidden border-2 border-amber-200/80 shadow-xl group">
                    <img src="{{ asset('images/ganga-holy-waters.jpg') }}"
                         alt="Living Holy Ganga River Waters"
                         class="w-full h-72 object-cover transition transform duration-500 group-hover:scale-105">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent flex items-end p-4">
                        <span class="text-xs font-bold text-white bg-sky-800/90 px-3 py-1 rounded-lg backdrop-blur-xs">
                            Pristine Living Flow of Maa Ganga
                        </span>
                    </div>
                </div>
            </div>
            <div class="md:col-span-7 space-y-4 order-1 md:order-2">
                <span class="text-xs font-extrabold text-[#0369A1] uppercase tracking-wider font-mono">Peer-Reviewed Research</span>
                <h2 class="text-2xl sm:text-3xl font-serif font-bold text-slate-900">Why Gangajal Never Spoils</h2>
                <p>
                    Scientific studies published by international river research institutes confirm that water from sacred Haridwar
                    Ganga Ghats contains unique bacteriophages that naturally destroy harmful microbes and prevent putrefaction.
                </p>
                <p>
                    Rich in natural dissolved oxygen and trace minerals from high-altitude Himalayan medicinal herbs,
                    Gangajal retains its freshness indefinitely when kept in a clean sanctuary. Laboratory tests confirm an alkaline pH of 7.8 and 0 CFU pathogen contamination.
                </p>
            </div>
        </section>

        <!-- Call to Action -->
        <div class="relative overflow-hidden bg-gradient-to-r from-[#881337] via-[#991B1B] to-[#7F1D1D] rounded-3xl p-8 sm:p-12 text-white text-center space-y-4 shadow-xl">
            <img src="{{ asset('images/haridwar-ghat-wide.jpg') }}"
                 alt="Haridwar Ghat Wide View"
                 class="absolute inset-0 w-full h-full object-cover opacity-20 mix-blend-overlay pointer-events-none">
            <div class="relative z-10 max-w-2xl mx-auto space-y-4">
                <h3 class="text-2xl sm:text-3xl font-serif font-bold text-white">
                    Bring Maa Ganga's Blessings to Your Home Altar
                </h3>
                <p class="text-xs sm:text-sm text-white/90">
                    Join thousands of devotees across India receiving monthly consecrated Haridwar Gangajal at their doorstep.
                </p>
                <div class="pt-2">
                    <a href="{{ route('membership.join') }}"
                        class="inline-flex items-center gap-2 px-8 py-4 bg-white text-slate-900 hover:bg-amber-50 font-bold text-sm rounded-2xl shadow-lg transition transform hover:-translate-y-0.5">
                        <span>Join the Haridwar Bliss 5-Year Devotee Circle</span>
                        <span>→</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
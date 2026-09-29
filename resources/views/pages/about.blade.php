<x-app-layout>
    <div class="bg-[#FFFDF7] py-12 border-b border-amber-200">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <span class="text-xs font-bold uppercase tracking-widest text-[#D97706] font-mono">Sacred Heritage</span>
            <h1 class="text-4xl sm:text-5xl font-serif font-extrabold text-slate-900 mt-2">
                The Sanctity of Brahmakund Gangajal
            </h1>
            <p class="text-sm sm:text-base text-slate-600 mt-3 max-w-2xl mx-auto leading-relaxed">
                Why water collected directly from the Brahmakund at Har Ki Pauri in Haridwar remains spiritually vital
                and pure forever.
            </p>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-14 space-y-12 text-slate-700 leading-relaxed text-sm">
        <section class="space-y-4">
            <h2 class="text-2xl font-serif font-bold text-slate-900">The Divine Legend of Har Ki Pauri</h2>
            <p>
                Har Ki Pauri ("Steps of Lord Shiva") in Haridwar is revered as one of the holiest river ghats on earth.
                According to the ancient Puranas, during the Samudra Manthan (churning of the cosmic ocean), drops of
                the celestial Amrit (divine nectar) fell at this exact sanctum known as <strong>Brahmakund</strong>.
            </p>
            <p>
                For millennia, saints, rishis, and millions of devotees have performed pilgrimage to Brahmakund, where
                Maa Ganga enters the plains from the high Himalayas with pristine spiritual vigor.
            </p>
        </section>

        <section class="bg-amber-50/60 border border-amber-200 p-8 rounded-3xl space-y-4">
            <h3 class="text-xl font-serif font-bold text-slate-900">Untouched by Human Hands · Our Sacred Process</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-2">
                <div class="space-y-2">
                    <strong class="font-bold text-slate-900 block font-serif">1. Brahma Muhurta Collection</strong>
                    <p class="text-xs text-slate-600">
                        Collected in the auspicious pre-dawn hour (04:00 AM – 05:30 AM) while continuous Vedic hymns
                        resonate across Har Ki Pauri.
                    </p>
                </div>
                <div class="space-y-2">
                    <strong class="font-bold text-slate-900 block font-serif">2. Micro-Filtration</strong>
                    <p class="text-xs text-slate-600">
                        Passed through multi-stage natural micron sediment filters that remove river silt while
                        retaining 100% natural mineral ions.
                    </p>
                </div>
                <div class="space-y-2">
                    <strong class="font-bold text-slate-900 block font-serif">3. Hermetic UV Sealing</strong>
                    <p class="text-xs text-slate-600">
                        Bottled immediately in UV-sterilized containers and sealed tamper-evident without human contact,
                        assigned a traceable QR code.
                    </p>
                </div>
            </div>
        </section>

        <section class="space-y-4">
            <h2 class="text-2xl font-serif font-bold text-slate-900">Why Gangajal Never Spoils</h2>
            <p>
                Scientific studies published by international river research institutes confirm that water from Har Ki
                Pauri contains unique bacteriophages that naturally destroy harmful microbes and prevent putrefaction.
                Rich in natural dissolved oxygen and trace minerals from high-altitude Himalayan medicinal herbs,
                Gangajal retains its freshness indefinitely when kept in a clean sanctuary.
            </p>
        </section>

        <div class="text-center pt-6 border-t border-slate-200">
            <a href="{{ route('membership.join') }}"
                class="inline-flex items-center gap-2 px-8 py-4 bg-[#D97706] hover:bg-[#F97316] text-white font-bold text-sm rounded-2xl shadow-lg transition">
                <span>Join the Haridwar Bliss 5-Year Devotee Circle</span>
                <span>→</span>
            </a>
        </div>
    </div>
</x-app-layout>
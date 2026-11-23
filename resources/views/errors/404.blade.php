<x-app-layout>
    <div class="min-h-[75vh] bg-gradient-to-b from-[#FFFDF7] via-amber-50/40 to-[#FFFDF7] text-stone-800 flex flex-col justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto w-full text-center space-y-8">
            
            <!-- Sacred Vedic Header & Badge -->
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-amber-100 border border-amber-300/80 text-amber-900 text-xs sm:text-sm font-medium tracking-wide shadow-xs">
                <span class="text-amber-600 font-serif">ॐ</span>
                <span>Haridwar Bliss &bull; Sacred Vedic Essentials</span>
                <span class="text-amber-600 font-serif">ॐ</span>
            </div>

            <!-- Visual 404 Display with Sacred Water Motif -->
            <div class="relative py-4">
                <!-- Ambient glow effect -->
                <div class="absolute inset-0 flex items-center justify-center -z-10">
                    <div class="w-64 h-64 sm:w-80 sm:h-80 bg-amber-400/20 rounded-full blur-3xl pointer-events-none"></div>
                </div>

                <!-- Big 404 Number -->
                <div class="font-serif italic text-7xl sm:text-9xl font-black text-amber-700/90 tracking-tight select-none">
                    4<span class="inline-block transform hover:scale-110 transition-transform duration-300 text-amber-600">0</span>4
                </div>

                <!-- Sacred Kalash / River Ganga Icon -->
                <div class="mt-2 flex justify-center items-center gap-3">
                    <div class="h-0.5 w-12 sm:w-20 bg-gradient-to-r from-transparent to-amber-400"></div>
                    <div class="w-12 h-12 rounded-full bg-amber-500 text-white flex items-center justify-center shadow-md ring-4 ring-amber-200/70">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <!-- River Waves / Drop icon -->
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c-4.97 4.97-6 8.5-6 11.5a6 6 0 0012 0c0-3-1.03-6.53-6-11.5z" />
                        </svg>
                    </div>
                    <div class="h-0.5 w-12 sm:w-20 bg-gradient-to-l from-transparent to-amber-400"></div>
                </div>
            </div>

            <!-- Page Titles and Explanations -->
            <div class="space-y-3">
                <h1 class="text-3xl sm:text-5xl font-serif font-bold text-stone-900 tracking-tight">
                    Divine Path Not Found
                </h1>
                <p class="text-lg sm:text-xl font-serif text-amber-800 italic">
                    पृष्ठ नहीं मिला &bull; यह पावन मार्ग उपलब्ध नहीं है
                </p>
                <p class="max-w-2xl mx-auto text-sm sm:text-base text-stone-600 leading-relaxed">
                    The sacred offering or page you are seeking seems to have wandered away, been renamed, or is temporarily unavailable. 
                    Let us guide you back to the holy banks of Haridwar.
                </p>
            </div>

            <!-- Primary Action Buttons -->
            <div class="flex flex-wrap items-center justify-center gap-3 pt-2">
                <a href="{{ route('home') }}"
                    class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-amber-600 hover:bg-amber-700 text-white text-sm sm:text-base font-semibold shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5 focus:outline-hidden focus:ring-2 focus:ring-amber-500 focus:ring-offset-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span>Return to Home (मुख्य पृष्ठ)</span>
                </a>

                <a href="{{ route('products.index') }}"
                    class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-white hover:bg-amber-50 border-2 border-amber-500/80 text-amber-900 text-sm sm:text-base font-semibold shadow-xs hover:shadow-md transition-all duration-200 transform hover:-translate-y-0.5 focus:outline-hidden focus:ring-2 focus:ring-amber-500 focus:ring-offset-2">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    <span>Sacred Offerings (गंगाजल उत्पाद)</span>
                </a>

                <a href="{{ route('contact') }}"
                    class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-amber-100/70 hover:bg-amber-200/80 text-amber-900 text-sm sm:text-base font-medium transition-all duration-200">
                    <svg class="w-5 h-5 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <span>Devotee Support (सहायता)</span>
                </a>
            </div>

            <!-- Helpful Navigation Grid -->
            <div class="pt-8 border-t border-amber-200/80">
                <p class="text-xs uppercase tracking-wider font-semibold text-stone-500 mb-4">
                    Explore Sacred Portals &bull; पावन अनुभाग
                </p>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-left">
                    <a href="{{ route('membership.join') }}" 
                        class="p-3.5 bg-white border border-amber-200 rounded-xl hover:border-amber-400 hover:shadow-sm transition-all group">
                        <div class="text-amber-700 text-xs font-semibold group-hover:text-amber-600">Devotee Membership</div>
                        <div class="text-stone-900 text-sm font-medium mt-0.5">5-Year Sacred Plan</div>
                    </a>

                    <a href="{{ route('water-collection') }}" 
                        class="p-3.5 bg-white border border-amber-200 rounded-xl hover:border-amber-400 hover:shadow-sm transition-all group">
                        <div class="text-amber-700 text-xs font-semibold group-hover:text-amber-600">Lab Certified</div>
                        <div class="text-stone-900 text-sm font-medium mt-0.5">Water Collection</div>
                    </a>

                    <a href="{{ route('online-puja') }}" 
                        class="p-3.5 bg-white border border-amber-200 rounded-xl hover:border-amber-400 hover:shadow-sm transition-all group">
                        <div class="text-amber-700 text-xs font-semibold group-hover:text-amber-600">Vedic Rituals</div>
                        <div class="text-stone-900 text-sm font-medium mt-0.5">Online Puja</div>
                    </a>

                    <a href="{{ route('faqs') }}" 
                        class="p-3.5 bg-white border border-amber-200 rounded-xl hover:border-amber-400 hover:shadow-sm transition-all group">
                        <div class="text-amber-700 text-xs font-semibold group-hover:text-amber-600">Help &amp; Answers</div>
                        <div class="text-stone-900 text-sm font-medium mt-0.5">Devotee FAQs</div>
                    </a>
                </div>
            </div>

            <!-- Devotee Helpdesk Contact Info -->
            <div class="pt-4 text-xs text-stone-500">
                Need immediate assistance? Contact our Devotee Care team at 
                <a href="tel:+917042482555" class="text-amber-700 font-bold hover:underline">+91 7042482555</a> 
                or email 
                <a href="mailto:sdenterprisesghaziabad@gmail.com" class="text-amber-700 font-bold hover:underline">sdenterprisesghaziabad@gmail.com</a>
            </div>

        </div>
    </div>
</x-app-layout>

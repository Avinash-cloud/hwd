<x-app-layout>
    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-[#FFFDF7] pt-8 pb-20 lg:pt-14 lg:pb-28 border-b border-[#D97706]/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-4xl mx-auto">
                <!-- Batch Authenticity Pill -->
                @if ($latestBatch)
                    <div
                        class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-bold bg-[#D97706]/10 text-[#D97706] border border-[#D97706]/30 shadow-xs mb-6">
                        <span class="inline-block w-2.5 h-2.5 rounded-full bg-[#0369A1] animate-pulse"></span>
                        <span>Latest Consecrated Batch #{{ $latestBatch->batch_number }} · Sourced from Brahmakund,
                            Haridwar</span>
                    </div>
                @endif

                <!-- Sacred Headline -->
                <h1
                    class="text-4xl sm:text-5xl lg:text-6xl font-serif font-extrabold text-slate-900 tracking-tight leading-tight">
                    Pure, Sacred <span class="text-[#D97706]">Brahmakund Gangajal</span> Delivered to Your Home
                </h1>

                <!-- Subtitle -->
                <p class="mt-6 text-lg sm:text-xl text-slate-600 max-w-2xl mx-auto leading-relaxed">
                    Collected at Brahma Muhurta during morning Ganga Aarti from Har Ki Pauri. Untouched by human hands,
                    hermetically sealed, lab certified, and trackable via unique batch QR codes.
                </p>

                <!-- CTA Action Group -->
                <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="{{ route('membership.join') }}"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-4 text-base font-bold text-white bg-[#D97706] hover:bg-[#F97316] rounded-2xl shadow-xl transition transform hover:-translate-y-0.5">
                        <span>Join 5-Year Membership (₹{{ number_format($membershipFee) }})</span>
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                    <a href="{{ route('products.index') }}"
                        class="w-full sm:w-auto inline-flex items-center justify-center px-7 py-4 text-base font-bold text-slate-800 bg-white hover:bg-[#FFFDF7] border border-[#D97706]/30 rounded-2xl shadow-sm transition">
                        Explore Sacred Catalog
                    </a>
                </div>

                <!-- 3 Sacred Guarantees -->
                <div
                    class="mt-12 pt-8 border-t border-[#D97706]/20 grid grid-cols-1 sm:grid-cols-3 gap-6 text-slate-700 text-sm font-semibold">
                    <div class="flex items-center justify-center gap-2">
                        <svg class="w-5 h-5 text-[#D97706]" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                        <span>100% Brahmakund Origin</span>
                    </div>
                    <div class="flex items-center justify-center gap-2">
                        <svg class="w-5 h-5 text-[#0369A1]" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                        <span>Lab Tested & Micro-Filtered</span>
                    </div>
                    <div class="flex items-center justify-center gap-2">
                        <svg class="w-5 h-5 text-[#991B1B]" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z" />
                            <path fill-rule="evenodd"
                                d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z"
                                clip-rule="evenodd" />
                        </svg>
                        <span>QR Code Batch Traceability</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Membership Journey Explainer Section -->
    <section class="py-16 bg-white border-y border-[#D97706]/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-14">
                <span class="text-xs font-bold uppercase tracking-widest text-[#D97706] font-mono">The Haridwar Bliss
                    Journey</span>
                <h2 class="mt-2 text-3xl sm:text-4xl font-serif font-extrabold text-slate-900">How the 5-Year Sacred
                    Subscription Works</h2>
                <p class="mt-4 text-slate-600">Enjoy monthly deliveries of consecrated Gangajal at your doorstep without
                    repeated ordering friction.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Step 1 -->
                <div
                    class="bg-[#FFFDF7] border border-[#D97706]/20 rounded-3xl p-8 relative hover:shadow-lg transition">
                    <div
                        class="w-12 h-12 rounded-2xl bg-[#D97706] text-white font-bold font-serif text-xl flex items-center justify-center mb-6 shadow-md">
                        1
                    </div>
                    <h3 class="text-xl font-serif font-bold text-slate-900 mb-2">Join for
                        ₹{{ number_format($membershipFee) }} (5 Years)</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        A single one-time contribution of ₹500 activates your 5-year membership credentials, granting
                        you exclusive ordering access for sacred offerings.
                    </p>
                </div>

                <!-- Step 2 -->
                <div
                    class="bg-[#FFFDF7] border border-[#D97706]/20 rounded-3xl p-8 relative hover:shadow-lg transition">
                    <div
                        class="w-12 h-12 rounded-2xl bg-[#F97316] text-white font-bold font-serif text-xl flex items-center justify-center mb-6 shadow-md">
                        2
                    </div>
                    <h3 class="text-xl font-serif font-bold text-slate-900 mb-2">1st Monthly Bottle is FREE</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        As a valued member, each monthly order includes your first 500ml Gangajal bottle for <strong>₹0
                            product price</strong> (only a flat ₹{{ number_format($shippingRate) }} shipping fee
                        applies).
                    </p>
                </div>

                <!-- Step 3 -->
                <div
                    class="bg-[#FFFDF7] border border-[#D97706]/20 rounded-3xl p-8 relative hover:shadow-lg transition">
                    <div
                        class="w-12 h-12 rounded-2xl bg-[#881337] text-white font-bold font-serif text-xl flex items-center justify-center mb-6 shadow-md">
                        3
                    </div>
                    <h3 class="text-xl font-serif font-bold text-slate-900 mb-2">Scan & Verify Authenticity</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        Scan the unique QR code on your dispatched order to see the exact collection timestamp at Har Ki
                        Pauri, Aarti video, and laboratory test results.
                    </p>
                </div>
            </div>

            <div class="mt-12 text-center">
                <a href="{{ route('membership.join') }}"
                    class="inline-flex items-center gap-2 text-sm font-bold text-[#D97706] hover:text-[#F97316] underline underline-offset-4">
                    <span>Learn more about membership privileges and terms</span>
                    <span>→</span>
                </a>
            </div>
        </div>
    </section>

    <!-- Featured Products Section -->
    <section class="py-16 bg-stone-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-4">
                <div>
                    <span class="text-xs font-bold uppercase tracking-widest text-[#D97706] font-mono">Consecrated
                        Offerings</span>
                    <h2 class="mt-1 text-3xl font-serif font-extrabold text-slate-900">Featured Sacred Products</h2>
                    <p class="mt-2 text-sm text-slate-600">Strictly reserved for genuine spiritual worship and ritual
                        purity.</p>
                </div>
                <a href="{{ route('products.index') }}"
                    class="inline-flex items-center gap-1 text-sm font-bold text-[#D97706] hover:underline">
                    View All Products ({{ $featuredProducts->count() + $featuredGangajal->count() }}) →
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Gangajal Special Card -->
                @foreach ($featuredGangajal as $product)
                    <div
                        class="bg-white rounded-3xl border-2 border-[#D97706]/40 shadow-md shadow-slate-900/5 overflow-hidden flex flex-col justify-between group hover:border-[#D97706] transition">
                        <div>
                            <!-- Badge -->
                            <div
                                class="bg-[#D97706] text-white px-4 py-2 text-xs font-extrabold uppercase tracking-wider flex items-center justify-between">
                                <span>🕉️ 100% Brahmakund Origin</span>
                                <span>Member 1st Bottle: ₹0</span>
                            </div>

                            <div class="p-6">
                                <div
                                    class="w-full h-48 bg-[#FFFDF7] border border-[#D97706]/20 rounded-2xl flex items-center justify-center mb-6 overflow-hidden">
                                    <div class="text-center p-4">
                                        <div class="w-16 h-16 mx-auto mb-2 text-[#D97706]">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-full h-full"
                                                stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
                                            </svg>
                                        </div>
                                        <span
                                            class="text-xs font-bold text-[#881337] uppercase tracking-widest font-mono">{{ $product->volume_ml }}ml
                                            Hermetic Flask</span>
                                    </div>
                                </div>

                                <h3
                                    class="font-serif font-bold text-xl text-slate-900 mb-2 group-hover:text-[#D97706] transition">
                                    <a href="{{ route('products.show', $product->slug) }}">{{ $product->name }}</a>
                                </h3>

                                <p class="text-xs text-slate-600 line-clamp-2 leading-relaxed mb-4">
                                    {{ $product->short_description }}
                                </p>

                                <div class="bg-[#FFFDF7] p-3 rounded-xl border border-[#D97706]/20 mb-4 text-xs space-y-1">
                                    <div class="flex justify-between">
                                        <span class="text-slate-600">Regular Price:</span>
                                        <span
                                            class="line-through text-slate-400 font-semibold">₹{{ number_format($product->price, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between font-bold text-[#881337]">
                                        <span>5-Yr Member Price:</span>
                                        <span class="text-[#0369A1]">₹0 (1st Bottle / Mo)</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="p-6 pt-0">
                            <a href="{{ route('products.show', $product->slug) }}"
                                class="w-full inline-flex items-center justify-center py-3 px-4 rounded-xl text-sm font-bold text-white bg-[#D97706] hover:bg-[#F97316] shadow transition">
                                View Sanctity Details & Order
                            </a>
                        </div>
                    </div>
                @endforeach

                <!-- Other Products -->
                @foreach ($featuredProducts as $product)
                    <div
                        class="bg-white rounded-3xl border border-[#D97706]/20 shadow-sm overflow-hidden flex flex-col justify-between hover:shadow-md transition group">
                        <div class="p-6">
                            <div
                                class="w-full h-48 bg-stone-100/70 rounded-2xl flex items-center justify-center mb-6 overflow-hidden">
                                <div class="text-center p-4">
                                    <div class="w-14 h-14 mx-auto mb-2 text-[#D97706]">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-full h-full"
                                            stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15.362 5.214A8.252 8.252 0 0112 21 8.25 8.25 0 016.038 7.048 8.287 8.287 0 009 9.6a8.983 8.983 0 013.361-6.867 8.21 8.21 0 003 2.48z" />
                                        </svg>
                                    </div>
                                    <span
                                        class="text-xs font-bold text-[#881337] uppercase tracking-widest font-mono">{{ $product->category->name }}</span>
                                </div>
                            </div>

                            <h3
                                class="font-serif font-bold text-lg text-slate-900 mb-2 group-hover:text-[#D97706] transition">
                                <a href="{{ route('products.show', $product->slug) }}">{{ $product->name }}</a>
                            </h3>

                            <p class="text-xs text-slate-600 line-clamp-2 leading-relaxed mb-4">
                                {{ $product->short_description }}
                            </p>

                            <div class="flex items-center justify-between pt-2 border-t border-slate-100">
                                <div>
                                    <span
                                        class="text-lg font-bold text-slate-900">₹{{ number_format($product->member_price ?? $product->price, 2) }}</span>
                                    @if ($product->member_price && $product->member_price < $product->price)
                                        <span
                                            class="text-xs line-through text-slate-400 ms-1">₹{{ number_format($product->price, 2) }}</span>
                                    @endif
                                </div>
                                <span
                                    class="text-[11px] font-bold text-[#D97706] bg-[#D97706]/10 px-2 py-0.5 rounded-md border border-[#D97706]/30">
                                    Member Discount
                                </span>
                            </div>
                        </div>

                        <div class="p-6 pt-0">
                            <a href="{{ route('products.show', $product->slug) }}"
                                class="w-full inline-flex items-center justify-center py-2.5 px-4 rounded-xl text-xs font-bold text-slate-800 bg-[#FFFDF7] hover:bg-[#D97706]/10 border border-[#D97706]/30 transition">
                                View Details
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Purity & Batch Traceability Section -->
    <section class="py-16 bg-slate-950 text-white relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="space-y-6">
                    <span class="text-xs font-bold uppercase tracking-widest text-[#D97706] font-mono">Sacred
                        Authenticity</span>
                    <h2 class="text-3xl sm:text-4xl font-serif font-extrabold text-white leading-tight">
                        Laboratory Tested Purity & Full Batch Traceability
                    </h2>
                    <p class="text-sm text-slate-300 leading-relaxed">
                        Every order dispatched by Haridwar Bliss is certified under an authorized batch. By scanning the
                        QR code on your holy water container, you can access the live inspection certificate and Vedic
                        Aarti collection video recorded at Har Ki Pauri.
                    </p>

                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 pt-4 text-center">
                        <div class="bg-slate-800/80 p-4 rounded-2xl border border-slate-700">
                            <p class="text-2xl font-extrabold text-[#D97706]">pH 7.8</p>
                            <p class="text-xs text-slate-400 mt-1">Natural Alkaline Balance</p>
                        </div>
                        <div class="bg-slate-800/80 p-4 rounded-2xl border border-slate-700">
                            <p class="text-2xl font-extrabold text-[#0369A1]">Zero</p>
                            <p class="text-xs text-slate-400 mt-1">Coliform & Pathogens</p>
                        </div>
                        <div class="bg-slate-800/80 p-4 rounded-2xl border border-slate-700">
                            <p class="text-2xl font-extrabold text-[#0369A1]">100%</p>
                            <p class="text-xs text-slate-400 mt-1">Natural Minerals</p>
                        </div>
                    </div>

                    <div class="pt-2">
                        <a href="{{ route('verify.batch', 'HB-VERIFY-2026-SAMPLE') }}"
                            class="inline-flex items-center gap-2 px-6 py-3 rounded-xl text-sm font-bold text-white bg-[#D97706] hover:bg-[#F97316] transition">
                            <span>Test Sample QR Verification Portal</span>
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                        </a>
                    </div>
                </div>

                <div class="bg-slate-800/90 border border-slate-700 p-8 rounded-3xl shadow-2xl relative">
                    <div class="flex items-center justify-between pb-6 border-b border-slate-700">
                        <div>
                            <span class="text-xs font-mono uppercase text-[#D97706] font-bold">Haridwar Bliss
                                Traceability Seal</span>
                            <h3 class="text-lg font-serif font-bold text-white mt-0.5">Brahmakund Batch
                                #HB-GANG-2026-001</h3>
                        </div>
                        <div
                            class="w-12 h-12 rounded-xl bg-[#D97706]/20 border border-[#D97706]/40 flex items-center justify-center text-[#D97706]">
                            🕉️
                        </div>
                    </div>

                    <div class="py-6 space-y-4 text-xs">
                        <div class="flex justify-between">
                            <span class="text-slate-400">Sourcing Ghat:</span>
                            <span class="font-bold text-white text-right">Har Ki Pauri, Brahmakund, Haridwar</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400">Collection Auspicious Time:</span>
                            <span class="font-bold text-white text-right">Brahma Muhurta (04:45 AM) during Morning
                                Aarti</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400">Bottling Technique:</span>
                            <span class="font-bold text-[#0369A1]">Touchless Ultra-Violet Hermetic Capping</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400">Lab Analysis Status:</span>
                            <span class="font-bold text-[#0369A1]">Passed All Potability & Mineral Standards</span>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-700 text-center">
                        <p class="text-[11px] text-slate-400 mb-3">Unique QR code generated on every invoice & bottle
                            package</p>
                        <div class="inline-block p-2 bg-white rounded-xl shadow-inner">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=120x120&data={{ urlencode(url('/verify/HB-VERIFY-2026-SAMPLE')) }}"
                                alt="Sample QR" class="w-24 h-24" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Customer Reviews / Testimonials -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-14">
                <span class="text-xs font-bold uppercase tracking-widest text-[#D97706] font-mono">Devotee
                    Blessings</span>
                <h2 class="mt-2 text-3xl sm:text-4xl font-serif font-extrabold text-slate-900">What Devotees Say Across
                    India</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-sm">
                <div class="bg-[#FFFDF7] p-8 rounded-3xl border border-[#D97706]/20 space-y-4">
                    <div class="text-[#D97706] flex gap-1">★★★★★</div>
                    <p class="text-slate-700 leading-relaxed italic">
                        "Living in Chennai, getting fresh holy water from Har Ki Pauri every month seemed impossible
                        until we joined Haridwar Bliss. The QR code showing the exact morning collection video brought
                        tears of devotion to our family."
                    </p>
                    <div>
                        <p class="font-bold text-slate-900">Dr. K. S. Narayanan</p>
                        <p class="text-xs text-slate-500">Chennai · 5-Year Member</p>
                    </div>
                </div>

                <div class="bg-[#FFFDF7] p-8 rounded-3xl border border-[#D97706]/20 space-y-4">
                    <div class="text-[#D97706] flex gap-1">★★★★★</div>
                    <p class="text-slate-700 leading-relaxed italic">
                        "The packaging is immaculate. Clean, touch-free hermetic seals with zero leakage. The first
                        monthly bottle being free with only shipping charges is an incredible blessing for daily morning
                        Shiva Abhishek."
                    </p>
                    <div>
                        <p class="font-bold text-slate-900">Smt. Meenakshi Agrawal</p>
                        <p class="text-xs text-slate-500">Ahmedabad · 5-Year Member</p>
                    </div>
                </div>

                <div class="bg-[#FFFDF7] p-8 rounded-3xl border border-[#D97706]/20 space-y-4">
                    <div class="text-[#D97706] flex gap-1">★★★★★</div>
                    <p class="text-slate-700 leading-relaxed italic">
                        "The brass panchamukhi aarti diya and pure sandalwood dhoop sticks transform our pooja altar
                        into Har Ki Pauri every evening. Truly authentic Vedic service."
                    </p>
                    <div>
                        <p class="font-bold text-slate-900">Shri Rajesh Vardhan</p>
                        <p class="text-xs text-slate-500">Bengaluru · 5-Year Member</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Membership Call To Action Banner -->
    <section class="py-16 bg-[#881337] text-white">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-6">
            <h2 class="text-3xl sm:text-4xl font-serif font-extrabold tracking-tight">
                Bring the Divine Sanctity of Haridwar into Your Home
            </h2>
            <p class="text-white/90 max-w-2xl mx-auto text-base sm:text-lg leading-relaxed">
                Join our sacred 5-year devotee membership for a one-time fee of ₹{{ number_format($membershipFee) }} and
                receive consecrated Brahmakund Gangajal at your doorstep every month.
            </p>
            <div class="pt-4 flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('membership.join') }}"
                    class="w-full sm:w-auto px-8 py-4 bg-white text-slate-900 hover:bg-[#FFFDF7] rounded-2xl font-bold text-base shadow-xl transition">
                    Activate 5-Year Membership (₹{{ number_format($membershipFee) }})
                </a>
                <a href="{{ route('about') }}"
                    class="w-full sm:w-auto px-8 py-4 bg-[#991B1B] hover:bg-[#D97706] border border-white/20 rounded-2xl font-bold text-base text-white transition">
                    Read Brahmakund Story
                </a>
            </div>
        </div>
    </section>
</x-app-layout>
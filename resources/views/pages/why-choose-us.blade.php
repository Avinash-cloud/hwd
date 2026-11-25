<x-app-layout>
    <div class="bg-[#FFFDF7] py-12 border-b border-amber-200">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <span class="text-xs font-bold uppercase tracking-widest text-[#D97706] font-mono">
                Sacred Sourcing &amp; Devotional Commitment
            </span>
            <h1 class="text-3xl sm:text-4xl font-serif font-extrabold text-slate-900 mt-2">
                Why Choose Blu Bliss
            </h1>
            <p class="text-sm text-slate-600 mt-3 max-w-2xl mx-auto leading-relaxed">
                Authentic Haridwar Gangajal sealed in food-grade {{ $monthlyVolume ?? 500 }} ml PET bottles and delivered
                every month through our transparent {{ $validityYears ?? 5 }}-year ({{ ($validityYears ?? 5) * 12 }}-month) devotional membership.
            </p>
        </div>
    </div>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-10">
        <!-- Transparent Membership Summary -->
        <div class="bg-[#FEF4CF] border-2 border-amber-300 rounded-2xl p-6 shadow-xs">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-center">
                <div class="bg-white/85 rounded-xl p-4 border border-amber-200">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-amber-800 block">
                        One-Time Commitment Fee
                    </span>
                    <span class="text-2xl font-serif font-extrabold text-[#881337] mt-1 block">
                        ₹{{ number_format($membershipFee ?? 500) }}
                    </span>
                    <span class="text-[11px] text-slate-600 block mt-0.5">
                        For {{ $validityYears ?? 5 }} Years ({{ ($validityYears ?? 5) * 12 }} Months, Non-Refundable)
                    </span>
                </div>
                <div class="bg-white/85 rounded-xl p-4 border border-amber-200">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-amber-800 block">
                        Monthly Auto-Delivery
                    </span>
                    <span class="text-2xl font-serif font-extrabold text-[#881337] mt-1 block">
                        ₹{{ number_format($monthlyAutoDebit ?? $shippingRate ?? 149) }}/month
                    </span>
                    <span class="text-[11px] text-slate-600 block mt-0.5">
                        Incl. GST ({{ $monthlyVolume ?? 500 }} ml PET Bottle + Doorstep Delivery)
                    </span>
                </div>
                <div class="bg-white/85 rounded-xl p-4 border border-amber-200">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-amber-800 block">
                        After {{ ($validityYears ?? 5) * 12 }} Months
                    </span>
                    <span class="text-2xl font-serif font-extrabold text-[#881337] mt-1 block">
                        Auto-Renewed
                    </span>
                    <span class="text-[11px] text-slate-600 block mt-0.5">
                        Automatic renewal via your active mandate — no extra commitment fee
                    </span>
                </div>
            </div>
        </div>

        <!-- 5 Core Benefit Blocks -->
        <section aria-labelledby="benefits-heading" class="space-y-6">
            <div class="text-center">
                <h2 id="benefits-heading" class="text-2xl font-serif font-bold text-slate-900">
                    Five Pillars of Every Monthly Sacred Delivery
                </h2>
                <p class="text-xs text-slate-600 mt-1">
                    Designed for devotion, convenience, and family traditions across India.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Benefit 1: Purity -->
                <article class="bg-white rounded-2xl border border-amber-200 p-6 shadow-xs flex flex-col justify-between space-y-4">
                    <div class="space-y-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider bg-amber-100 text-amber-900">
                            01 · Purity
                        </span>
                        <h3 class="text-lg font-serif font-bold text-[#881337]">
                            Purity Guaranteed
                        </h3>
                        <p class="text-xs text-slate-700 leading-relaxed">
                            Sourced directly from Haridwar and hygienically packed in tamper-evident, sealed
                            <strong>{{ $monthlyVolume ?? 500 }} ml PET bottles</strong> so your daily rituals and family
                            pujas always begin with authentic Haridwar Gangajal.
                        </p>
                    </div>
                    <p class="text-xs font-semibold italic text-amber-900 pt-3 border-t border-amber-100">
                        &ldquo;Purity guaranteed &mdash; authentic Haridwar Gangajal for your sacred altar.&rdquo;
                    </p>
                </article>

                <!-- Benefit 2: Auto-Delivery -->
                <article class="bg-white rounded-2xl border border-amber-200 p-6 shadow-xs flex flex-col justify-between space-y-4">
                    <div class="space-y-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider bg-amber-100 text-amber-900">
                            02 · Auto-Delivery
                        </span>
                        <h3 class="text-lg font-serif font-bold text-[#881337]">
                            Auto-Delivery Subscription
                        </h3>
                        <p class="text-xs text-slate-700 leading-relaxed">
                            Once enrolled, you never have to remember to reorder every month. Your scheduled
                            <strong>{{ $monthlyVolume ?? 500 }} ml PET bottle</strong> is automatically processed and
                            dispatched every month at ₹{{ number_format($monthlyAutoDebit ?? $shippingRate ?? 149) }}/month
                            (inclusive of GST).
                        </p>
                    </div>
                    <p class="text-xs font-semibold italic text-amber-900 pt-3 border-t border-amber-100">
                        &ldquo;Give the hassle of worship logistics to us &mdash; you just focus on devotion.&rdquo;
                    </p>
                </article>

                <!-- Benefit 3: Virtue plus Cleanliness -->
                <article class="bg-white rounded-2xl border border-amber-200 p-6 shadow-xs flex flex-col justify-between space-y-4">
                    <div class="space-y-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider bg-amber-100 text-amber-900">
                            03 · Virtue + Cleanliness
                        </span>
                        <h3 class="text-lg font-serif font-bold text-[#881337]">
                            Virtue + Cleanliness Double Benefit
                        </h3>
                        <p class="text-xs text-slate-700 leading-relaxed">
                            Our service celebrates both spiritual devotion at home and mindful reverence for the sacred
                            Ganga&mdash;encouraging clean, respectful worship habits and awareness for keeping sacred
                            river spaces clean.
                        </p>
                    </div>
                    <p class="text-xs font-semibold italic text-amber-900 pt-3 border-t border-amber-100">
                        &ldquo;Devotion and sacred stewardship moving hand in hand.&rdquo;
                    </p>
                </article>

                <!-- Benefit 4: Pan-India Delivery -->
                <article class="bg-white rounded-2xl border border-amber-200 p-6 shadow-xs flex flex-col justify-between space-y-4">
                    <div class="space-y-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider bg-amber-100 text-amber-900">
                            04 · Pan-India Delivery
                        </span>
                        <h3 class="text-lg font-serif font-bold text-[#881337]">
                            Zero Logistics Hassle Across India
                        </h3>
                        <p class="text-xs text-slate-700 leading-relaxed">
                            We deliver nationwide to devotee homes in sturdy, food-grade
                            <strong>{{ $monthlyVolume ?? 500 }} ml PET bottles</strong> through trusted courier partners,
                            complete with tracking updates and responsive address-change support (with 15 days&rsquo; prior notice).
                        </p>
                    </div>
                    <p class="text-xs font-semibold italic text-amber-900 pt-3 border-t border-amber-100">
                        &ldquo;Haridwar at Your Doorstep.&rdquo;
                    </p>
                </article>

                <!-- Benefit 5: Family & Tradition -->
                <article class="md:col-span-2 bg-[#FFF8E1] rounded-2xl border border-amber-300 p-6 shadow-xs flex flex-col justify-between space-y-4">
                    <div class="space-y-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider bg-amber-200/80 text-[#881337]">
                            05 · Family &amp; Tradition
                        </span>
                        <h3 class="text-lg font-serif font-bold text-[#881337]">
                            Family Well-Being &amp; Living Sanskar
                        </h3>
                        <p class="text-xs text-slate-700 leading-relaxed">
                            A convenient, sacred <strong>{{ $monthlyVolume ?? 500 }} ml PET bottle</strong> keeps elders and
                            younger generations connected to timeless Sanatani traditions every single day. Share the living
                            story of Ganga Maiya with children at your home altar&mdash;keeping timeless sanskar alive for
                            <strong>about ₹{{ max(1, (int) round(($monthlyAutoDebit ?? $shippingRate ?? 149) / 30)) }} per day</strong>
                            (₹{{ number_format($monthlyAutoDebit ?? $shippingRate ?? 149) }}/month inclusive of GST).
                        </p>
                    </div>
                    <p class="text-xs font-semibold italic text-[#881337] pt-3 border-t border-amber-200">
                        &ldquo;Stay connected to your roots, for about ₹{{ max(1, (int) round(($monthlyAutoDebit ?? $shippingRate ?? 149) / 30)) }} per day.&rdquo;
                    </p>
                </article>
            </div>
        </section>

        <!-- Actions & Policy Links -->
        <div class="bg-white rounded-2xl border border-amber-200 p-6 sm:p-8 flex flex-col sm:flex-row items-center justify-between gap-4 shadow-xs">
            <div class="space-y-1 text-center sm:text-left">
                <h2 class="text-base font-serif font-bold text-slate-900">
                    Begin Your {{ $validityYears ?? 5 }}-Year Sacred Membership
                </h2>
                <p class="text-xs text-slate-600">
                    Review our <a href="{{ route('terms') }}" class="text-amber-700 font-semibold underline hover:text-amber-900">Terms &amp; Conditions</a>
                    and <a href="{{ route('cancellation-policy') }}" class="text-amber-700 font-semibold underline hover:text-amber-900">Cancellation Policy</a> anytime.
                </p>
            </div>
            <div class="flex flex-wrap items-center justify-center gap-3 shrink-0">
                <a href="{{ route('membership.join') }}"
                   class="px-5 py-2.5 bg-[#881337] hover:bg-[#6B0E29] text-white text-xs font-bold rounded-xl shadow-xs transition">
                    Join Membership (₹{{ number_format($membershipFee ?? 500) }})
                </a>
                <a href="{{ route('products.index') }}"
                   class="px-5 py-2.5 bg-amber-100 hover:bg-amber-200 text-amber-950 text-xs font-bold rounded-xl border border-amber-300 transition">
                    Browse Catalog
                </a>
            </div>
        </div>
    </div>
</x-app-layout>

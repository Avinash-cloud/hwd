<x-app-layout>
    <div class="bg-[#FFFDF7] py-12 border-b border-[#D97706]/30">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <span class="text-xs font-bold uppercase tracking-widest text-[#D97706] font-mono">Sacred
                Subscription</span>
            <h1 class="text-4xl sm:text-5xl font-serif font-extrabold text-slate-900 mt-2">
                Haridwar Bliss 5-Year Devotee Membership
            </h1>
            <p class="mt-4 text-base sm:text-lg text-slate-600 max-w-2xl mx-auto leading-relaxed">
                Connect your household directly to the divine blessings of Maa Ganga at Har Ki Pauri with uninterrupted
                monthly sacred deliveries for 5 full years.
            </p>
        </div>
    </div>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left 2 Cols: Privileges & How it works -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Privileges Card -->
                <div class="bg-white rounded-3xl border border-[#D97706]/20 p-8 shadow-xs space-y-6">
                    <h2 class="text-2xl font-serif font-bold text-slate-900">Exclusive 5-Year Membership Benefits</h2>

                    <div class="space-y-4">
                        <div class="flex items-start gap-4">
                            <div
                                class="w-10 h-10 rounded-xl bg-[#D97706]/10 text-[#D97706] flex items-center justify-center shrink-0 font-bold">
                                1
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-900 text-base">Free Monthly Sacred Gangajal Bottle</h3>
                                <p class="text-xs text-slate-600 leading-relaxed mt-1">
                                    Each month, your first 500ml Brahmakund Gangajal bottle carries a <strong>₹0 product
                                        price</strong> (only a flat delivery fee of ₹{{ number_format($shippingRate) }}
                                    applies).
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div
                                class="w-10 h-10 rounded-xl bg-[#D97706]/10 text-[#D97706] flex items-center justify-center shrink-0 font-bold">
                                2
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-900 text-base">5 Full Years of Validity (60 Months)</h3>
                                <p class="text-xs text-slate-600 leading-relaxed mt-1">
                                    No recurring annual fees or monthly renewal charges. A single one-time contribution
                                    of ₹{{ number_format($membershipFee) }} covers your membership status for 5 years.
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div
                                class="w-10 h-10 rounded-xl bg-[#D97706]/10 text-[#D97706] flex items-center justify-center shrink-0 font-bold">
                                3
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-900 text-base">Unbroken Batch Purity Verification</h3>
                                <p class="text-xs text-slate-600 leading-relaxed mt-1">
                                    Every dispatched bottle includes a scannable QR code encoding the exact collection
                                    timestamp at Har Ki Pauri, Aarti video link, and laboratory test report.
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div
                                class="w-10 h-10 rounded-xl bg-[#D97706]/10 text-[#D97706] flex items-center justify-center shrink-0 font-bold">
                                4
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-900 text-base">Member-Exclusive Pricing on Pooja Ware
                                </h3>
                                <p class="text-xs text-slate-600 leading-relaxed mt-1">
                                    Enjoy preferred member discounts on handcrafted brass diyas, pure copper kalash
                                    vessels, and natural temple dhoop sticks.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Terms & FAQ accordion snippet -->
                <div class="bg-[#FFFDF7] rounded-3xl border border-[#D97706]/20 p-6 space-y-3 text-xs text-slate-600">
                    <h4 class="font-serif font-bold text-slate-900 text-sm">Devotee Guarantee & Transparency</h4>
                    <p>
                        • <strong>Delivery Schedule:</strong> Once subscribed, you can claim your monthly bottle at any
                        time from your member portal, shipped directly via Shiprocket.
                    </p>
                    <p>
                        • <strong>Sanctity Assurance:</strong> Collected during Brahma Muhurta morning Aarti under
                        continuous Vedic recitation. Untouched by human hands.
                    </p>
                </div>
            </div>

            <!-- Right Col: Activation Form / Summary -->
            <div class="space-y-6">
                <div
                    class="bg-white border-2 border-[#D97706]/40 rounded-3xl p-8 shadow-lg shadow-slate-900/5 relative overflow-hidden">
                    <div
                        class="bg-[#D97706] text-white text-center py-2 px-4 -mx-8 -mt-8 mb-6 font-bold text-xs uppercase tracking-widest">
                        Auspicious Devotee Offering
                    </div>

                    <div class="text-center pb-6 border-b border-slate-100">
                        <span class="text-xs font-bold uppercase tracking-wider text-[#D97706]">One-Time
                            Contribution</span>
                        <div class="mt-2 flex items-baseline justify-center gap-1">
                            <span
                                class="text-4xl font-extrabold font-serif text-slate-900">₹{{ number_format($membershipFee) }}</span>
                            <span class="text-xs font-bold text-slate-500">/ 5 Years</span>
                        </div>
                        <span
                            class="inline-block mt-2 text-[11px] font-semibold text-[#0369A1] bg-[#0369A1]/10 border border-[#0369A1]/30 px-3 py-0.5 rounded-full">
                            Equivalent to ₹8.33 / month
                        </span>
                    </div>

                    @auth
                        @if (Auth::user()->hasActiveMembership())
                            <div class="py-6 text-center space-y-3">
                                <div
                                    class="w-12 h-12 rounded-full bg-[#0369A1]/10 text-[#0369A1] flex items-center justify-center mx-auto">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <h4 class="font-serif font-bold text-base text-slate-900">You Are Already a Sacred Member!</h4>
                                <p class="text-xs text-slate-500">
                                    Your 5-Year Membership is currently active. You can claim your monthly Gangajal bottle right
                                    now.
                                </p>
                                <a href="{{ route('dashboard') }}"
                                    class="w-full inline-block py-3 bg-[#D97706] hover:bg-[#F97316] text-white font-bold text-xs rounded-xl shadow">
                                    Go to Member Dashboard
                                </a>
                            </div>
                        @else
                            <form method="POST" action="{{ route('membership.subscribe') }}" class="py-6 space-y-4">
                                @csrf
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Subscriber Name</label>
                                    <input type="text" value="{{ Auth::user()->name }}" readonly
                                        class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl text-slate-500" />
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Registered Email</label>
                                    <input type="email" value="{{ Auth::user()->email }}" readonly
                                        class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl text-slate-500" />
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Select Payment Gateway</label>
                                    <div class="space-y-2 text-xs">
                                        <label
                                            class="flex items-center gap-2 p-2.5 border border-[#D97706]/30 rounded-xl bg-[#FFFDF7] cursor-pointer">
                                            <input type="radio" name="payment_method" value="razorpay" checked
                                                class="text-[#D97706] focus:ring-[#D97706]">
                                            <span class="font-bold text-slate-800">Razorpay / UPI / NetBanking</span>
                                        </label>
                                        <label
                                            class="flex items-center gap-2 p-2.5 border border-slate-200 rounded-xl hover:bg-slate-50 cursor-pointer">
                                            <input type="radio" name="payment_method" value="mock"
                                                class="text-[#D97706] focus:ring-[#D97706]">
                                            <span class="font-bold text-slate-700">Instant Dev Activation (Sandbox)</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="pt-2">
                                    <button type="submit"
                                        class="w-full py-3.5 px-4 rounded-xl text-sm font-bold text-white bg-[#D97706] hover:bg-[#F97316] shadow-lg transition">
                                        Pay ₹{{ number_format($membershipFee) }} & Activate 5-Year Membership
                                    </button>
                                </div>

                                <p class="text-[10px] text-center text-slate-400">
                                    By clicking, you accept Haridwar Bliss Membership Terms and digital sanctification
                                    protocols.
                                </p>
                            </form>
                        @endif
                    @else
                        <div class="py-6 space-y-4 text-center">
                            <p class="text-xs text-slate-600">
                                Please sign in or register your account to attach your 5-year sacred subscription.
                            </p>
                            <a href="{{ route('register') }}"
                                class="w-full inline-block py-3.5 px-4 bg-[#D97706] hover:bg-[#F97316] text-white font-bold text-xs rounded-xl shadow">
                                Create Account & Join (₹{{ number_format($membershipFee) }})
                            </a>
                            <a href="{{ route('login') }}"
                                class="w-full inline-block py-2.5 px-4 bg-white border border-[#D97706]/30 text-slate-800 font-bold text-xs rounded-xl hover:bg-[#FFFDF7]">
                                Already Registered? Sign In
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
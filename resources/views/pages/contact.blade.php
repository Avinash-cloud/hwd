<x-app-layout>
    <div class="bg-[#FFFDF7] py-12 border-b border-amber-200">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <span class="text-xs font-bold uppercase tracking-widest text-[#D97706] font-mono">Devotee Assistance</span>
            <h1 class="text-4xl font-serif font-extrabold text-slate-900 mt-2">
                Connect with Haridwar Bliss
            </h1>
            <p class="text-sm text-slate-600 mt-2">
                Our support team and seva coordinators in Haridwar are here to assist with any questions about your
                membership or shipments.
            </p>
        </div>
    </div>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Contact Info & Map Details -->
            <div class="space-y-8">
                <div class="bg-white rounded-3xl border border-amber-200/80 p-8 shadow-xs space-y-6">
                    <h2 class="text-xl font-serif font-bold text-slate-900">Headquarters & Holy Sourcing Sanctum</h2>

                    <div class="space-y-4 text-xs">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-xl bg-amber-100/70 text-[#D97706] flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <div>
                                <strong class="font-bold text-slate-900 block">Sourcing Sanctum:</strong>
                                <p class="text-slate-600 mt-0.5">Brahmakund Ghat, Har Ki Pauri, Haridwar, Uttarakhand -
                                    249401, India</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-xl bg-amber-100/70 text-[#D97706] flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                            </div>
                            <div>
                                <strong class="font-bold text-slate-900 block">Devotee Care Helpline:</strong>
                                <p class="text-slate-600 mt-0.5">{{ $supportPhone }} (Mon - Sat, 09:00 AM - 06:00 PM)
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-xl bg-amber-100/70 text-[#D97706] flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <strong class="font-bold text-slate-900 block">Official Support Email:</strong>
                                <p class="text-slate-600 mt-0.5">{{ $supportEmail }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Har Ki Pauri Map Embed Visual -->
                <div class="bg-slate-900 rounded-3xl border border-slate-800 p-6 text-white text-center space-y-3">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-800 text-xs">
                        <span class="inline-flex items-center gap-1.5 font-bold text-[#D97706] font-mono">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            </svg>
                            <span>Google Maps Satellite Reference</span>
                        </span>
                        <span class="text-slate-400 text-[11px]">Brahmakund Coordinates: 29.9567° N, 78.1708° E</span>
                    </div>
                    <div
                        class="w-full h-48 bg-slate-800 rounded-2xl flex items-center justify-center text-xs text-slate-400 border border-slate-700">
                        <div class="text-center p-4">
                            <p class="font-serif font-bold text-base text-[#D97706]">Har Ki Pauri, Brahmakund</p>
                            <p class="text-xs text-slate-400 mt-1">Haridwar, Uttarakhand, India</p>
                            <a href="https://maps.google.com/?q=Har+Ki+Pauri+Haridwar" target="_blank"
                                class="mt-3 inline-block px-4 py-1.5 bg-[#D97706]/20 text-[#D97706] border border-[#D97706]/40 rounded-xl font-bold text-xs hover:bg-[#D97706]/30 transition">
                                Open in Google Maps ↗
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="bg-white rounded-3xl border border-amber-200/80 p-8 shadow-xs space-y-6">
                <h2 class="text-xl font-serif font-bold text-slate-900">Send Us a Message</h2>
                <form method="POST" action="{{ route('contact.submit') }}" class="space-y-4 text-xs">
                    @csrf
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Your Full Name *</label>
                        <input type="text" name="name" required
                            class="w-full px-3 py-2 border border-slate-300 rounded-xl" placeholder="John Doe" />
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Email Address *</label>
                        <input type="email" name="email" required
                            class="w-full px-3 py-2 border border-slate-300 rounded-xl"
                            placeholder="devotee@example.com" />
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Subject *</label>
                        <input type="text" name="subject" required
                            class="w-full px-3 py-2 border border-slate-300 rounded-xl"
                            placeholder="Membership inquiry or Delivery question" />
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Your Message *</label>
                        <textarea name="message" rows="4" required
                            class="w-full px-3 py-2 border border-slate-300 rounded-xl"
                            placeholder="How may our Haridwar team serve you?"></textarea>
                    </div>

                    <button type="submit"
                        class="w-full py-3.5 bg-[#D97706] hover:bg-[#F97316] text-white font-bold text-sm rounded-xl shadow-md transition">
                        Submit Inquiry
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
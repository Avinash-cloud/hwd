<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Haridwar Bliss') }} - Sacred Brahmakund Gangajal & Vedic Essentials</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body
    class="font-sans text-slate-900 antialiased bg-stone-50 min-h-full flex flex-col selection:bg-[#D97706] selection:text-white">
    @include('layouts.navigation')

    <!-- Flash Messages -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full mt-4">
        @if (session('success'))
            <div
                class="rounded-xl bg-[#0369A1]/10 border border-[#0369A1]/30 p-4 mb-4 text-sm text-[#0369A1] flex items-center gap-3 shadow-xs">
                <svg class="w-5 h-5 text-[#0369A1] shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (session('warning'))
            <div
                class="rounded-xl bg-[#D97706]/10 border border-[#D97706]/30 p-4 mb-4 text-sm text-[#D97706] flex items-center gap-3 shadow-xs">
                <svg class="w-5 h-5 text-[#D97706] shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                        clip-rule="evenodd" />
                </svg>
                <span>{{ session('warning') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div
                class="rounded-xl bg-[#991B1B]/10 border border-[#991B1B]/30 p-4 mb-4 text-sm text-[#991B1B] flex items-center gap-3 shadow-xs">
                <svg class="w-5 h-5 text-[#991B1B] shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                        clip-rule="evenodd" />
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if (session('info'))
            <div
                class="rounded-xl bg-[#0369A1]/10 border border-[#0369A1]/30 p-4 mb-4 text-sm text-[#0369A1] flex items-center gap-3 shadow-xs">
                <svg class="w-5 h-5 text-[#0369A1] shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                        clip-rule="evenodd" />
                </svg>
                <span>{{ session('info') }}</span>
            </div>
        @endif
    </div>

    <!-- Page Heading -->
    @isset($header)
        <header class="bg-white border-b border-[#D97706]/20">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
    @endisset

    <!-- Page Content -->
    <main class="flex-grow">
        {{ $slot }}
    </main>

    <!-- Sacred Footer -->
    <footer class="bg-slate-900 text-slate-300 border-t border-slate-800 mt-20 pt-16 pb-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10 pb-12 border-b border-slate-800">
                <!-- Col 1: Brand & Origin -->
                <div class="space-y-4 md:col-span-1">
                    <div class="flex items-center gap-3">
                        <x-application-logo class="w-10 h-10 rounded-xl" />
                        <div>
                            <span class="block font-serif font-bold text-xl text-white">Haridwar Bliss</span>
                            <span class="block text-[10px] uppercase font-bold tracking-widest text-[#D97706]">Purity &
                                Devotion</span>
                        </div>
                    </div>
                    <p class="text-sm text-slate-400 leading-relaxed">
                        Delivering certified pure, untouched Gangajal collected at Brahma Muhurta directly from
                        Brahmakund at Har Ki Pauri to your sacred altar.
                    </p>
                    <div class="text-xs text-[#D97706] flex items-center gap-2">
                        <span>📍 Har Ki Pauri, Haridwar, Uttarakhand - 249401</span>
                    </div>
                </div>

                <!-- Col 2: Quick Links -->
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-widest text-[#D97706] mb-4 font-mono">Sacred
                        Offerings</h4>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="{{ route('products.index', ['type' => 'gangajal']) }}"
                                class="hover:text-white transition">Brahmakund Gangajal (500ml)</a></li>
                        <li><a href="{{ route('products.index', ['type' => 'gangajal']) }}"
                                class="hover:text-white transition">Copper Cask Edition (1 Litre)</a></li>
                        <li><a href="{{ route('products.index', ['category' => 'brass-pooja-ware']) }}"
                                class="hover:text-white transition">Handcrafted Brass Aarti Diya</a></li>
                        <li><a href="{{ route('products.index', ['category' => 'dhoop-incense']) }}"
                                class="hover:text-white transition">Vedic Temple Dhoop & Havan Samagri</a></li>
                        <li><a href="{{ route('products.index', ['category' => 'divine-gifts-prasad']) }}"
                                class="hover:text-white transition">Consecrated Rudraksha Mala</a></li>
                    </ul>
                </div>

                <!-- Col 3: Membership & Verification -->
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-widest text-[#D97706] mb-4 font-mono">Devotee Care
                    </h4>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="{{ route('membership.join') }}"
                                class="text-[#D97706] font-semibold hover:text-white transition">5-Year Sacred
                                Membership (₹500)</a></li>
                        <li><a href="{{ route('verify.batch', 'HB-VERIFY-2026-SAMPLE') }}"
                                class="hover:text-white transition">Batch QR Verification & Lab Tests</a></li>
                        <li><a href="{{ route('about') }}" class="hover:text-white transition">Sanctity of Brahmakund
                                Water</a></li>
                        <li><a href="{{ route('faqs') }}" class="hover:text-white transition">Frequently Asked
                                Questions</a></li>
                        <li><a href="{{ route('contact') }}" class="hover:text-white transition">Contact Haridwar
                                Headquarters</a></li>
                    </ul>
                </div>

                <!-- Col 4: Contact & Sanctity Assurance -->
                <div class="space-y-3">
                    <h4 class="text-xs font-bold uppercase tracking-widest text-[#D97706] mb-4 font-mono">Sanctity
                        Assurance</h4>
                    <div class="bg-slate-800/80 p-4 rounded-xl border border-slate-700/80 space-y-2">
                        <div class="flex items-center gap-2 text-xs font-bold text-[#D97706]">
                            <svg class="w-4 h-4 text-[#0369A1]" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span>100% Hermetically Sealed</span>
                        </div>
                        <p class="text-xs text-slate-400 leading-normal">
                            Untouched by human hands from sacred collection to tamper-evident capping. Every shipment
                            includes a verifiable batch QR code.
                        </p>
                    </div>
                    <div class="text-xs text-slate-400">
                        Email: <a href="mailto:bliss@haridwarbliss.com"
                            class="text-[#D97706] hover:underline">bliss@haridwarbliss.com</a>
                    </div>
                </div>
            </div>

            <div class="pt-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-500">
                <p>&copy; {{ date('Y') }} Haridwar Bliss. All rights reserved. Haridwar, Uttarakhand.</p>
                <div class="flex items-center gap-6">
                    <a href="{{ route('terms') }}" class="hover:text-slate-300 transition">Terms & Conditions</a>
                    <a href="{{ route('privacy') }}" class="hover:text-slate-300 transition">Privacy Policy</a>
                    <a href="{{ route('faqs') }}" class="hover:text-slate-300 transition">FAQs</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Slide-over Ajax Cart Drawer -->
    <x-cart-drawer />
</body>

</html>
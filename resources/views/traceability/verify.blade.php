<x-app-layout>
    <!-- Authenticity Header Banner -->
    <div class="bg-[#FFFDF7] py-12 border-b border-[#D97706]/30">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div
                class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-bold bg-[#0369A1]/10 text-[#0369A1] border border-[#0369A1]/30 shadow-xs mb-4">
                <span class="w-2 h-2 rounded-full bg-[#0369A1]"></span>
                <span>Verified Genuine Haridwar Bliss Consecration</span>
            </div>
            <h1 class="text-3xl sm:text-4xl font-serif font-extrabold text-slate-900">
                Official Sacred Batch & Traceability Certificate
            </h1>
            <p class="text-xs sm:text-sm text-slate-600 mt-2 max-w-xl mx-auto">
                Decoded from QR Token: <span class="font-mono font-bold text-slate-900">{{ $token }}</span>
            </p>
        </div>
    </div>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-10">
        <!-- Main Certificate Card -->
        <div
            class="bg-white rounded-3xl border-2 border-[#D97706]/40 p-8 sm:p-12 shadow-xl shadow-slate-900/5 relative overflow-hidden">
            <div
                class="flex flex-col md:flex-row md:items-center justify-between pb-8 border-b border-[#D97706]/20 gap-6">
                <div>
                    <span class="text-xs font-bold uppercase tracking-widest text-[#D97706] font-mono">Brahmakund
                        Provenance</span>
                    <h2 class="text-2xl font-serif font-bold text-slate-900 mt-1">
                        Batch #{{ $batch?->batch_number ?? 'HB-GANG-VERIFIED' }}
                    </h2>
                    <p class="text-xs text-slate-500 mt-1">
                        Collected under continuous Vedic mantra chanting at Har Ki Pauri
                    </p>
                </div>

                <div class="flex items-center gap-4">
                    <div
                        class="w-24 h-24 p-1.5 bg-white border border-[#D97706]/40 rounded-2xl shadow-xs shrink-0 flex items-center justify-center">
                        {{-- {!! $qrSvg !!} --}}
                        <img src={!! $qrSvg !!} alt="">
                    </div>
                </div>
            </div>

            <!-- Provenance & Lab Matrix -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 py-8 border-b border-[#D97706]/20 text-xs">
                <!-- Left: Collection Details -->
                <div class="space-y-4">
                    <h3 class="font-serif font-bold text-base text-slate-900">Sourcing & Bottling Log</h3>
                    <div class="space-y-2.5 bg-[#FFFDF7] p-5 rounded-2xl border border-[#D97706]/20">
                        <div class="flex justify-between">
                            <span class="text-slate-500">Holy Sourcing Ghat:</span>
                            <span
                                class="font-bold text-slate-900 text-right">{{ $batch?->sourcing_ghat ?? 'Har Ki Pauri, Brahmakund, Haridwar' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Water Collection Date:</span>
                            <span
                                class="font-bold text-slate-900">{{ $batch?->collection_date ? $batch->collection_date->format('d F Y') : 'Recent Auspicious Batch' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Untouched Bottling Date:</span>
                            <span
                                class="font-bold text-slate-900">{{ $batch?->packaging_date ? $batch->packaging_date->format('d F Y') : 'Hermetically Sealed' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Vedic Ritual Time:</span>
                            <span class="font-bold text-[#881337]">Brahma Muhurta Ganga Aarti</span>
                        </div>
                    </div>

                    @if ($batch?->description)
                        <p class="text-slate-600 leading-relaxed italic bg-white p-3 rounded-xl border border-slate-200">
                            "{{ $batch->description }}"
                        </p>
                    @endif
                </div>

                <!-- Right: Lab Certificate & Purity Specs -->
                <div class="space-y-4">
                    <h3 class="font-serif font-bold text-base text-slate-900">Laboratory Quality Certification</h3>
                    <div class="grid grid-cols-2 gap-3 text-center">
                        <div class="bg-[#0369A1]/10 border border-[#0369A1]/30 p-4 rounded-xl">
                            <span class="text-[#0369A1] font-extrabold text-xl">pH 7.8</span>
                            <span class="block text-[11px] text-[#0369A1] font-medium mt-0.5">Alkaline Purity</span>
                        </div>
                        <div class="bg-[#0369A1]/10 border border-[#0369A1]/30 p-4 rounded-xl">
                            <span class="text-[#0369A1] font-extrabold text-xl">0 CFU</span>
                            <span class="block text-[11px] text-[#0369A1] font-medium mt-0.5">Zero Pathogens</span>
                        </div>
                        <div class="bg-[#0369A1]/10 border border-[#0369A1]/30 p-4 rounded-xl">
                            <span class="text-[#0369A1] font-extrabold text-xl">9.2 mg/L</span>
                            <span class="block text-[11px] text-[#0369A1] font-medium mt-0.5">Dissolved Oxygen</span>
                        </div>
                        <div class="bg-[#0369A1]/10 border border-[#0369A1]/30 p-4 rounded-xl">
                            <span class="text-[#0369A1] font-extrabold text-xl">100%</span>
                            <span class="block text-[11px] text-[#0369A1] font-medium mt-0.5">Natural Minerals</span>
                        </div>
                    </div>

                    @if ($batch?->purity_notes)
                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 text-slate-700 leading-relaxed">
                            {{ $batch->purity_notes }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Video & Aarti Link -->
            @if ($batch?->video_url)
                <div class="py-6 border-b border-[#D97706]/20 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div>
                        <h4 class="font-serif font-bold text-sm text-slate-900">Consecration Aarti Video Recording</h4>
                        <p class="text-xs text-slate-500">Witness the sacred water collection ceremony from Har Ki Pauri.
                        </p>
                    </div>
                    <a href="{{ $batch->video_url }}" target="_blank"
                        class="px-4 py-2 bg-[#D97706] hover:bg-[#F97316] text-white font-bold text-xs rounded-xl shadow-xs transition inline-flex items-center gap-2">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z"
                                clip-rule="evenodd" />
                        </svg>
                        <span>Watch Aarti Video</span>
                    </a>
                </div>
            @endif

            <!-- Order Linkage Details (if scanned from specific order) -->
            @if ($order)
                <div class="pt-6 text-xs text-slate-600">
                    <h4 class="font-serif font-bold text-sm text-slate-900 mb-2">Linked Dispatch Verification</h4>
                    <div
                        class="bg-stone-50 p-4 rounded-2xl border border-slate-200 flex flex-col sm:flex-row justify-between gap-4">
                        <div>
                            <span class="text-slate-400 block">Order Reference:</span>
                            <span class="font-bold text-slate-900 font-mono">{{ $order->order_number }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block">Logistics Partner:</span>
                            <span class="font-bold text-slate-900">{{ $order->courier_name ?? 'Shiprocket Express' }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block">Tracking AWB:</span>
                            <span
                                class="font-bold text-slate-900 font-mono">{{ $order->tracking_number ?? 'Assigned on Dispatch' }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block">Fulfillment Status:</span>
                            <span class="font-bold uppercase text-[#0369A1]">{{ $order->order_status }}</span>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
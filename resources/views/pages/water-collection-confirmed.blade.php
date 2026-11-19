<x-app-layout>
    <div class="bg-[#FAF7F2] min-h-screen py-12 sm:py-16">
        <div class="max-w-2xl mx-auto px-4 sm:px-6">

            <!-- Card -->
            <div class="bg-white rounded-2xl border-2 border-amber-300 shadow-xl overflow-hidden text-center">

                <!-- Header -->
                <div style="background-color: #25050A; background-image: linear-gradient(180deg, #25050A 0%, #3B0813 100%); color: #ffffff;"
                     class="bg-[#25050A] text-white p-6 sm:p-8 space-y-3">
                    <div class="w-14 h-14 rounded-full bg-amber-500/20 text-amber-300 border border-amber-400/40 flex items-center justify-center mx-auto text-2xl shadow-inner">
                        🔱
                    </div>

                    <div class="inline-block px-3 py-1 rounded-full bg-amber-400/20 text-amber-200 text-xs font-semibold uppercase tracking-wider" style="color: #FDE68A;">
                        Jai Gange · Kumbh Sewa Request Confirmed
                    </div>

                    <h1 class="text-2xl sm:text-3xl font-extrabold text-white" style="color: #FFFFFF;">
                        Water Collection Registered
                    </h1>

                    <p class="text-xs sm:text-sm text-amber-100/90 max-w-md mx-auto" style="color: #FEF3C7;">
                        Your sacred Haridwar Kumbh Ganga Jal collection request has been safely received by our Haridwar Bliss Sewa Team.
                    </p>
                </div>

                <!-- Body Details -->
                <div class="p-6 sm:p-8 space-y-6 text-left text-xs">

                    <!-- Reference Number Box -->
                    <div class="bg-amber-50 rounded-xl p-4 border border-amber-200 text-center space-y-1">
                        <span class="text-slate-500 text-[11px] uppercase tracking-wider font-semibold block">Devotee Lead Reference #</span>
                        <span class="text-xl sm:text-2xl font-black text-[#881337] tracking-wider font-mono block">
                            {{ $lead->lead_number }}
                        </span>
                        <span class="text-[11px] text-slate-500 block">
                            Please save this reference number for all future inquiries.
                        </span>
                    </div>

                    <!-- Details Table -->
                    <div class="space-y-4 divide-y divide-slate-100">
                        <div class="pt-2 flex justify-between gap-4">
                            <span class="text-slate-500 font-medium">Devotee Name:</span>
                            <span class="text-slate-900 font-bold text-right">{{ $lead->name }}</span>
                        </div>

                        <div class="pt-3 flex justify-between gap-4">
                            <span class="text-slate-500 font-medium">Mobile / WhatsApp:</span>
                            <span class="text-slate-900 font-bold text-right">{{ $lead->mobile }}</span>
                        </div>

                        @if($lead->email)
                            <div class="pt-3 flex justify-between gap-4">
                                <span class="text-slate-500 font-medium">Email:</span>
                                <span class="text-slate-900 text-right">{{ $lead->email }}</span>
                            </div>
                        @endif

                        <div class="pt-3 flex justify-between gap-4">
                            <span class="text-slate-500 font-medium">Haridwar Pickup Point (Hotel/Dharamshala):</span>
                            <span class="text-slate-900 font-semibold text-right max-w-xs">
                                {{ $lead->hotel_or_dharamshala }}
                                @if($lead->pickup_room_or_landmark)
                                    <span class="block text-[11px] text-slate-500">({{ $lead->pickup_room_or_landmark }})</span>
                                @endif
                            </span>
                        </div>

                        <div class="pt-3 flex justify-between gap-4">
                            <span class="text-slate-500 font-medium">Doorstep Delivery Address:</span>
                            <span class="text-slate-900 font-semibold text-right max-w-xs">
                                {{ $lead->delivery_address }}
                                @if($lead->delivery_city || $lead->delivery_state || $lead->delivery_pincode)
                                    <span class="block text-[11px] text-slate-500">
                                        {{ implode(', ', array_filter([$lead->delivery_city, $lead->delivery_state, $lead->delivery_pincode])) }}
                                    </span>
                                @endif
                            </span>
                        </div>

                        <div class="pt-3 flex justify-between gap-4">
                            <span class="text-slate-500 font-medium">Water Package & Volume:</span>
                            <span class="text-[#881337] font-bold text-right">
                                {{ $lead->water_quantity }}
                            </span>
                        </div>

                        @if($lead->pickup_date)
                            <div class="pt-3 flex justify-between gap-4">
                                <span class="text-slate-500 font-medium">Scheduled Pickup Date:</span>
                                <span class="text-slate-900 font-bold text-right">
                                    {{ $lead->pickup_date->format('M d, Y') }}
                                </span>
                            </div>
                        @endif

                        @if($lead->notes)
                            <div class="pt-3 flex justify-between gap-4">
                                <span class="text-slate-500 font-medium">Devotional Notes / Gotra:</span>
                                <span class="text-slate-700 italic text-right max-w-xs">{{ $lead->notes }}</span>
                            </div>
                        @endif

                        <div class="pt-3 flex justify-between items-center gap-4">
                            <span class="text-slate-500 font-medium">Payment Method:</span>
                            <span class="font-bold text-slate-800 text-right">
                                @if($lead->payment_method === 'razorpay')
                                    <span>Razorpay Online (UPI / Card / NetBanking)</span>
                                @else
                                    <span>Pay on Collection (Cash / UPI at Pickup)</span>
                                @endif
                            </span>
                        </div>

                        <div class="pt-3 flex justify-between items-center gap-4">
                            <span class="text-slate-500 font-medium">Payment Status:</span>
                            <div class="text-right">
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold border uppercase tracking-wider {{ $lead->payment_status_badge_classes }}">
                                    @if($lead->payment_status === 'paid')
                                        ✓ Paid Online via Razorpay
                                    @elseif($lead->payment_status === 'cod')
                                        Pay at Pickup (COD)
                                    @else
                                        {{ ucfirst($lead->payment_status ?? 'Pending') }}
                                    @endif
                                </span>
                                @if($lead->razorpay_payment_id)
                                    <div class="text-[10px] text-slate-500 font-mono mt-0.5">
                                        ID: {{ $lead->razorpay_payment_id }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="pt-4 flex justify-between items-baseline gap-4 border-t-2 border-amber-200">
                            <span class="text-sm font-bold text-slate-900">Total Seva Contribution:</span>
                            <span class="text-xl font-black text-[#881337]">₹{{ number_format($lead->total_price, 2) }}/-</span>
                        </div>
                    </div>

                    <!-- Next Steps Box -->
                    <div class="p-4 rounded-xl bg-blue-50/70 border border-blue-200 text-blue-900 space-y-1.5 text-xs">
                        <span class="font-bold flex items-center gap-1.5">
                            <span>📞</span>
                            <span>What happens next?</span>
                        </span>
                        <p class="leading-relaxed text-blue-800 text-[11.5px]">
                            Our Haridwar Sewa coordinator is reviewing your pickup location. We will contact you via WhatsApp / Phone shortly to coordinate the collection timing, container seals, and dispatch tracking details.
                        </p>
                    </div>

                    <!-- Actions -->
                    <div class="pt-2 flex flex-col sm:flex-row gap-3">
                        @php
                            $whatsAppText = urlencode("Jai Gange! I have submitted a Kumbh Water Collection request (Ref #{$lead->lead_number}) for {$lead->name} from {$lead->hotel_or_dharamshala}. Please assist.");
                            $cleanPhone = preg_replace('/[^0-9]/', '', $supportPhone);
                        @endphp
                        <a href="https://wa.me/{{ $cleanPhone }}?text={{ $whatsAppText }}"
                           target="_blank"
                           class="flex-1 py-2.5 px-4 rounded-xl text-center font-bold text-white bg-emerald-600 hover:bg-emerald-700 shadow-md transition flex items-center justify-center gap-2">
                            <span>💬 Connect on WhatsApp</span>
                        </a>

                        <a href="{{ route('home') }}"
                           class="flex-1 py-2.5 px-4 rounded-xl text-center font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 transition flex items-center justify-center">
                            <span>Return to Storefront</span>
                        </a>
                    </div>

                </div>

            </div>
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <div class="bg-[#FFFDF7] py-12 border-b border-amber-200">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <span class="text-xs font-bold uppercase tracking-widest text-[#D97706] font-mono">Knowledge &
                Assistance</span>
            <h1 class="text-4xl font-serif font-extrabold text-slate-900 mt-2">
                Frequently Asked Questions
            </h1>
            <p class="text-sm text-slate-600 mt-2">
                Everything you need to know about the Haridwar Bliss 5-Year Sacred Membership and monthly Gangajal
                deliveries.
            </p>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-6">
        <div class="bg-white rounded-2xl border border-amber-200 p-6 shadow-xs space-y-2">
            <h3 class="font-serif font-bold text-base text-slate-900">1. What is the 5-Year Blu Bliss Membership?
            </h3>
            <p class="text-xs text-slate-600 leading-relaxed">
                The membership is a 5-year (60 months) service commitment with a one-time non-refundable commitment fee of ₹{{ number_format($membershipFee) }}. The primary benefit of the membership is the automatic monthly ordering and delivery of the active 500 ml PET bottle of pure Haridwar Gangajal from our membership catalog directly to your doorstep at the set monthly charge of ₹{{ number_format($monthlyAutoDebit ?? $shippingRate) }}/month (Product: ₹{{ number_format($monthlyProductPrice ?? 0) }} + Delivery: ₹{{ number_format($monthlyDeliveryCharge ?? $shippingRate) }}, including GST). Upon completion of 60 months, your membership renews automatically via your active recurring payment mandate with no extra commitment fee unless cancelled. See our <a href="{{ route('cancellation-policy') }}" class="text-amber-700 font-semibold underline hover:text-amber-900">Service Cancellation Policy</a>.
            </p>
        </div>

        <div class="bg-white rounded-2xl border border-amber-200 p-6 shadow-xs space-y-2">
            <h3 class="font-serif font-bold text-base text-slate-900">2. How does the monthly auto-delivery work?
            </h3>
            <p class="text-xs text-slate-600 leading-relaxed">
                For active members, the system automatically creates and schedules your monthly 500ml pure Gangajal bottle dispatch every 30 days at the set monthly auto-debit charge of ₹{{ number_format($monthlyAutoDebit ?? $shippingRate) }}/month (Product: ₹{{ number_format($monthlyProductPrice ?? 0) }} + Delivery: ₹{{ number_format($monthlyDeliveryCharge ?? $shippingRate) }}) via your authorized recurring payment mandate. You can also order additional bottles and items from our store catalog anytime at exclusive member prices.
            </p>
        </div>

        <div class="bg-white rounded-2xl border border-amber-200 p-6 shadow-xs space-y-2">
            <h3 class="font-serif font-bold text-base text-slate-900">3. Can non-members buy products?</h3>
            <p class="text-xs text-slate-600 leading-relaxed">
                Non-members are welcome to browse our entire catalog, inspect lab test reports, and view Haridwar
                Aarti recordings. However, as per our sanctification guidelines and client SOW, ordering sacred
                offerings is restricted exclusively to active members to preserve sacred accountability and priority
                logistics.
            </p>
        </div>

        <div class="bg-white rounded-2xl border border-amber-200 p-6 shadow-xs space-y-2">
            <h3 class="font-serif font-bold text-base text-slate-900">4. How do I verify the authenticity of my Gangajal
                bottle?</h3>
            <p class="text-xs text-slate-600 leading-relaxed">
                Every bottle dispatched has a unique QR code on its label and tax invoice. Scan it with any smartphone
                to view the exact date and Brahma Muhurta hour the batch was filled at Haridwar Ganga Ghats, the laboratory
                mineral assay certificate, and the consecration Aarti video.
            </p>
        </div>

        <div class="bg-white rounded-2xl border border-amber-200 p-6 shadow-xs space-y-2">
            <h3 class="font-serif font-bold text-base text-slate-900">5. How long can Gangajal be stored in my pooja
                altar?</h3>
            <p class="text-xs text-slate-600 leading-relaxed">
                Due to its natural bactericidal properties and high mineral content, pure Gangajal never spoils. When
                stored away from direct sunlight in a clean environment, it remains pure and sacred indefinitely.
            </p>
        </div>

        <div class="bg-white rounded-2xl border border-amber-200 p-6 shadow-xs space-y-2">
            <h3 class="font-serif font-bold text-base text-slate-900">6. Which courier service handles the shipments?
            </h3>
            <p class="text-xs text-slate-600 leading-relaxed">
                All packages are dispatched via Shiprocket's premier express couriers (BlueDart, Delhivery, DTDC) with
                live tracking number updates sent directly to your registered phone and email.
            </p>
        </div>
    </div>
</x-app-layout>
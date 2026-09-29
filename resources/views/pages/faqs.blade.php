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
            <h3 class="font-serif font-bold text-base text-slate-900">1. What is the 5-Year Haridwar Bliss Membership?
            </h3>
            <p class="text-xs text-slate-600 leading-relaxed">
                The membership is a one-time sacred subscription of ₹{{ number_format($membershipFee) }} that gives your
                family 5 full years (60 months) of direct access to consecrated Brahmakund holy water. Each month, your
                first bottle of Gangajal is provided at <strong>₹0 product price</strong>, and you pay only the flat
                shipping delivery fee of ₹{{ number_format($shippingRate) }}.
            </p>
        </div>

        <div class="bg-white rounded-2xl border border-amber-200 p-6 shadow-xs space-y-2">
            <h3 class="font-serif font-bold text-base text-slate-900">2. How does the monthly pricing automation work?
            </h3>
            <p class="text-xs text-slate-600 leading-relaxed">
                When an active member places an order:
                <br>• <strong>1 Bottle:</strong> Product Price = ₹0 (Free), Shipping =
                ₹{{ number_format($shippingRate) }} flat.
                <br>• <strong>2 or More Bottles:</strong> 1st bottle is ₹0, additional bottles are charged at the
                regular member rate, and shipping remains ₹{{ number_format($shippingRate) }} flat per order.
            </p>
        </div>

        <div class="bg-white rounded-2xl border border-amber-200 p-6 shadow-xs space-y-2">
            <h3 class="font-serif font-bold text-base text-slate-900">3. Can non-members buy products?</h3>
            <p class="text-xs text-slate-600 leading-relaxed">
                Non-members are welcome to browse our entire catalog, inspect lab test reports, and view Brahmakund
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
                to view the exact date and Brahma Muhurta hour the batch was filled at Har Ki Pauri, the laboratory
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
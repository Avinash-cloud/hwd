<x-app-layout>
    <div class="bg-[#FFFDF7] py-12 border-b border-amber-200">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <span class="text-xs font-bold uppercase tracking-widest text-[#D97706] font-mono">
                Razorpay &amp; NimbusPost Operations
            </span>
            <h1 class="text-center text-3xl sm:text-4xl font-serif font-extrabold text-slate-900 mt-2">
                Payment &amp; Delivery Policy
            </h1>
            <p class="text-xs sm:text-sm text-slate-600 mt-2">
                M/s Haridwar Bliss Food &amp; Beverages Pvt. Ltd. (Blu Bliss) &bull; Secure Payments via Razorpay &bull; Logistics via NimbusPost
            </p>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-8 text-xs text-slate-700 leading-relaxed">
        <!-- Key Figures Highlight -->
        <div class="bg-[#FEF4CF] border-2 border-amber-300 rounded-2xl p-6 shadow-xs">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-center">
                <div class="bg-white/90 rounded-xl p-4 border border-amber-200">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-amber-800 block">
                        Payment Gateway &amp; Mandates
                    </span>
                    <span class="text-xl font-serif font-extrabold text-[#881337] mt-1 block">
                        Razorpay (INR)
                    </span>
                    <span class="text-[11px] text-slate-600 block mt-0.5">
                        One-time orders, Subscriptions &amp; {{ $validityYears }}-Year Commitment (₹{{ number_format($membershipFee) }})
                    </span>
                </div>
                <div class="bg-white/90 rounded-xl p-4 border border-amber-200">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-amber-800 block">
                        Monthly Auto-Delivery Billing
                    </span>
                    <span class="text-xl font-serif font-extrabold text-[#881337] mt-1 block">
                        ₹{{ number_format($monthlyAutoDebit) }}/month
                    </span>
                    <span class="text-[11px] text-slate-600 block mt-0.5">
                        Incl. GST &bull; Auto-renewed after {{ $validityYears * 12 }} months via active mandate
                    </span>
                </div>
                <div class="bg-white/90 rounded-xl p-4 border border-amber-200">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-amber-800 block">
                        Logistics &amp; Dispatch Partner
                    </span>
                    <span class="text-xl font-serif font-extrabold text-[#881337] mt-1 block">
                        NimbusPost
                    </span>
                    <span class="text-[11px] text-slate-600 block mt-0.5">
                        Dispatch in 24&ndash;48 hrs &bull; Standard ₹{{ number_format($shippingRate) }} / Member {{ ($memberShippingRate ?? 0) <= 0 ? 'FREE' : '₹' . number_format($memberShippingRate) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Section 1: Payment & Subscription Policy (Razorpay) -->
        <section class="bg-white rounded-2xl border border-amber-200 p-6 sm:p-8 shadow-xs space-y-5">
            <div class="border-b border-amber-100 pb-4">
                <span class="text-[11px] font-bold uppercase tracking-wider text-amber-700 font-mono">
                    Powered by Razorpay Payment Gateway &amp; Subscriptions
                </span>
                <h2 class="text-xl font-serif font-bold text-slate-900 mt-1">
                    1. Payment &amp; Subscription Billing Policy (Razorpay)
                </h2>
            </div>

            <ol class="list-decimal pl-5 space-y-3 text-slate-700">
                <li>
                    <strong>Accepted Payment Modes (Razorpay):</strong> All online payments&mdash;including sacred catalog
                    orders, {{ $validityYears }}-Year Membership enrolments, monthly subscription auto-debits, Haridwar
                    Ghat E-Puja bookings, and Kumbh Water Collection bookings&mdash;are processed securely in Indian Rupees
                    (INR / ₹) via <strong>Razorpay</strong>. Supported methods include UPI, Credit Cards, Debit Cards,
                    NetBanking, supported digital wallets, and Razorpay Recurring E-Mandates. Cash on Delivery (COD) may
                    additionally be offered at checkout for eligible standard store orders.
                </li>
                <li>
                    <strong>Recurring Membership Subscriptions (Razorpay Subscriptions):</strong>
                    Enrolling in the {{ $validityYears }}-Year ({{ $validityYears * 12 }}-month) Sacred Membership requires
                    the one-time non-refundable Commitment Fee of <strong>₹{{ number_format($membershipFee) }}</strong>
                    and authorizes a monthly recurring subscription mandate via Razorpay for <strong>₹{{ number_format($monthlyAutoDebit) }}/month</strong>
                    (configured as Product: ₹{{ number_format($monthlyProductPrice) }} + Delivery: ₹{{ number_format($monthlyDeliveryCharge) }},
                    inclusive of applicable GST). Each recurring billing cycle triggers automated order generation and
                    doorstep dispatch without manual re-ordering.
                </li>
                <li>
                    <strong>Automatic Tenure Renewal:</strong> Upon completion of the {{ $validityYears * 12 }}-month
                    membership period, active subscriptions renew automatically through your authorized Razorpay recurring
                    mandate with no extra Commitment Fee, unless cancelled prior in accordance with our
                    <a href="{{ route('cancellation-policy') }}" class="text-amber-700 font-semibold underline hover:text-amber-900">Service Cancellation Policy</a>.
                </li>
                <li>
                    <strong>Payment Security &amp; Signature Verification:</strong> All Razorpay checkout transactions,
                    recurring mandate authorizations, and webhook notifications undergo cryptographic <strong>HMAC-SHA256</strong>
                    signature verification prior to order confirmation or membership activation. Haridwar Bliss Food &amp;
                    Beverages Pvt. Ltd. does not store raw card numbers, CVV codes, or UPI credentials on its servers.
                </li>
                <li>
                    <strong>Failed, Pending, or Duplicate Payments:</strong> An order or booking is confirmed only upon
                    verified payment confirmation from Razorpay. If your bank account is debited for a failed or duplicate
                    transaction, do not initiate repeat payments; email <a href="mailto:{{ $supportEmail }}" class="text-amber-700 font-semibold underline hover:text-amber-900">{{ $supportEmail }}</a>
                    with your payment reference ID. Verified duplicate/failed captures are refunded to the original payment
                    source within <strong>5 to 7 working days</strong> per Razorpay banking timelines.
                </li>
                <li>
                    <strong>Subscription Payment Failures &amp; Halted Mandates:</strong> If a monthly Razorpay auto-debit
                    attempt fails, automated notifications are triggered and Razorpay retries per mandate rules. After repeated
                    consecutive failures or an exhausted retry cycle, the subscription status moves to <em>past due</em> /
                    <em>halted</em> until the devotee updates or re-authorizes payment details.
                </li>
                <li>
                    <strong>GST &amp; Tax Invoices:</strong> All listed prices are inclusive of applicable GST (HSN
                    <span class="font-mono">22019010</span> / SAC <span class="font-mono">9995</span> as applicable), with
                    tax breakdowns (<span class="font-mono">CGST + SGST</span> / <span class="font-mono">IGST</span>)
                    provided on downloadable tax invoices in your account dashboard and order confirmation emails.
                </li>
            </ol>
        </section>

        <!-- Section 2: Shipping & Delivery Policy (NimbusPost) -->
        <section class="bg-white rounded-2xl border border-amber-200 p-6 sm:p-8 shadow-xs space-y-5">
            <div class="border-b border-amber-100 pb-4">
                <span class="text-[11px] font-bold uppercase tracking-wider text-amber-700 font-mono">
                    Powered by NimbusPost Multi-Courier Logistics
                </span>
                <h2 class="text-xl font-serif font-bold text-slate-900 mt-1">
                    2. Shipping &amp; Delivery Policy (NimbusPost)
                </h2>
            </div>

            <ol class="list-decimal pl-5 space-y-3 text-slate-700">
                <li>
                    <strong>Fulfillment Aggregator &amp; Dispatch Center:</strong> All sacred Gangajal bottles, puja kits,
                    monthly subscription deliveries, and eligible Prasad parcels are shipped across India via
                    <strong>NimbusPost</strong> from our Haridwar fulfillment hub (G-46, Shivalik Nagar, BHEL, Ranipur
                    More, Haridwar, Uttarakhand - 249403). NimbusPost selects an optimal express or surface courier partner
                    (such as BlueDart, Delhivery, DTDC, Xpressbees, or equivalent network carriers) based on destination
                    PIN code serviceability.
                </li>
                <li>
                    <strong>Dispatch &amp; Delivery Timelines:</strong>
                    <ul class="list-disc pl-5 mt-2 space-y-1.5 text-slate-600">
                        <li><strong>Catalog &amp; Monthly Membership Orders:</strong> Orders are typically processed and booked on NimbusPost with an Air Waybill (AWB) within <strong>24 to 48 hours</strong> (1 to 2 working days) of payment confirmation. Standard Pan-India delivery takes <strong>3 to 5 working days</strong>, while remote, hilly, or North-Eastern PIN codes may take <strong>5 to 7 working days</strong>.</li>
                        <li><strong>Haridwar Ghat E-Puja Prasad / Gangajal Dispatch:</strong> For E-Puja packages that include sacred Prasad or Gangajal, dispatch via NimbusPost is initiated within <strong>3 to 5 working days</strong> after ritual completion.</li>
                    </ul>
                </li>
                <li>
                    <strong>Shipping Charges:</strong> Standard checkout delivery is <strong>₹{{ number_format($shippingRate) }}</strong>
                    per order for non-members/guests. Active {{ $validityYears }}-Year Members receive the configured member
                    delivery benefit (currently <strong>{{ ($memberShippingRate ?? 0) <= 0 ? 'FREE' : '₹' . number_format($memberShippingRate) }}</strong>
                    on store catalog orders), while automated monthly membership dispatches are billed under the consolidated
                    monthly charge (<strong>₹{{ number_format($monthlyAutoDebit) }}/month</strong>). All shipping rates
                    reflect live platform settings configured by the administration.
                </li>
                <li>
                    <strong>Live AWB Tracking &amp; Batch Provenance:</strong> Upon shipment booking through NimbusPost,
                    your courier partner name and <strong>AWB Tracking Number</strong> are emailed to your registered
                    email address and synchronized live on your order tracking page via NimbusPost webhooks
                    (<em>Booked</em>, <em>In Transit</em>, <em>Out for Delivery</em>, <em>Delivered</em>). Packaged
                    Gangajal shipments also carry a tamper-evident <strong>Batch QR Code</strong> for provenance verification.
                </li>
                <li>
                    <strong>Address Accuracy &amp; 15-Day Notice for Address Changes:</strong> Customers are responsible
                    for providing a complete street address, accurate 6-digit PIN code, and active mobile number. Members
                    requiring a delivery address update for upcoming monthly shipments must notify support at least
                    <strong>15 days prior</strong> to the next scheduled dispatch cycle.
                </li>
                <li>
                    <strong>Undelivered Shipments (NDR), Return to Origin (RTO) &amp; Force Majeure:</strong> If delivery
                    fails due to recipient unavailability, incorrect/incomplete address, or unreachability, the assigned
                    courier conducts Non-Delivery Report (NDR) re-attempt steps. Shipments returned to origin (RTO) due to
                    customer-side unavailability may require updated address verification and re-shipping charges before
                    re-dispatch. Transit timelines may occasionally be impacted by extreme weather, festivals/traffic
                    restrictions in Haridwar, or force majeure events beyond our control.
                </li>
            </ol>
        </section>

        <!-- Section 3: Cancellations, Jurisdiction & Support -->
        <section class="bg-[#FFF8E1] rounded-2xl border border-amber-300 p-6 sm:p-8 shadow-xs space-y-4">
            <h2 class="text-lg font-serif font-bold text-[#881337]">
                3. Order Cancellations, Governing Law &amp; Support
            </h2>
            <p class="text-slate-700">
                Catalog orders may be cancelled prior to courier dispatch from your order page or by contacting support;
                once dispatched via NimbusPost, cancellations follow our
                <a href="{{ route('cancellation-policy') }}" class="text-amber-800 font-semibold underline hover:text-amber-950">Service &amp; E-Puja Cancellation Policy</a>.
                All payment, refund, and delivery disputes are subject to the exclusive jurisdiction of
                <strong>Haridwar District Court, Uttarakhand</strong>.
            </p>
            <div class="bg-white rounded-xl border border-amber-200 p-4 space-y-1.5">
                <p><strong>Company:</strong> M/s Haridwar Bliss Food &amp; Beverages Pvt. Ltd. (Blu Bliss)</p>
                <p><strong>Dispatch &amp; Registered Office:</strong> G-46, Shivalik Nagar, BHEL, Ranipur More, Haridwar, Uttarakhand - 249403, India</p>
                <p>
                    <strong>Support Email:</strong>
                    <a href="mailto:{{ $supportEmail }}" class="text-amber-700 font-semibold underline hover:text-amber-900">{{ $supportEmail }}</a>
                </p>
                <p>
                    <strong>Support Phone:</strong>
                    <a href="tel:{{ preg_replace('/[^0-9+]/', '', $supportPhone) }}" class="text-amber-700 font-semibold underline hover:text-amber-900">{{ $supportPhone }}</a>
                    (Mon &ndash; Sat, 09:00 AM &ndash; 06:00 PM)
                </p>
            </div>
            <div class="pt-2 flex flex-wrap gap-4 text-xs">
                <a href="{{ route('terms') }}" class="text-amber-800 font-semibold underline hover:text-amber-950">Terms &amp; Conditions</a>
                <a href="{{ route('privacy') }}" class="text-amber-800 font-semibold underline hover:text-amber-950">Privacy Policy</a>
                <a href="{{ route('cancellation-policy') }}" class="text-amber-800 font-semibold underline hover:text-amber-950">Cancellation Policy</a>
                <a href="{{ route('membership.join') }}" class="text-amber-800 font-semibold underline hover:text-amber-950">5-Year Membership</a>
            </div>
        </section>
    </div>
</x-app-layout>

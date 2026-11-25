<x-app-layout>
    <div class="bg-[#FFFDF7] py-12 border-b border-amber-200">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <span class="text-xs font-bold uppercase tracking-widest text-[#D97706] font-mono">
                Transparent Devotee Policies
            </span>
            <h1 class="text-3xl sm:text-4xl font-serif font-extrabold text-slate-900 mt-2">
                Service &amp; E-Puja Cancellation Policy
            </h1>
            <p class="text-xs sm:text-sm text-slate-600 mt-2">
                M/s Haridwar Bliss Food &amp; Beverages Pvt. Ltd. (Blu Bliss) &bull; Updated 17 November 2026
            </p>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-8 text-xs text-slate-700 leading-relaxed">
        <!-- Section 1: Membership Service Cancellation Policy -->
        <section class="bg-white rounded-2xl border border-amber-200 p-6 sm:p-8 shadow-xs space-y-5">
            <div class="border-b border-amber-100 pb-4">
                <span class="text-[11px] font-bold uppercase tracking-wider text-amber-700 font-mono">
                    Membership &amp; Monthly Gangajal Delivery
                </span>
                <h2 class="text-xl font-serif font-bold text-slate-900 mt-1">
                    1. Service Cancellation Policy
                </h2>
            </div>

            <ol class="list-decimal pl-5 space-y-3 text-slate-700">
                <li>
                    <strong>Discontinuation Notice:</strong> The Customer may discontinue the service in any month by
                    giving written notice or e-mail to
                    <a href="mailto:{{ $supportEmail }}" class="text-amber-700 font-semibold underline hover:text-amber-900">{{ $supportEmail }}</a>.
                </li>
                <li>
                    <strong>Non-Refundable Commitment Fee:</strong> The one-time Commitment Fee of
                    <strong>₹{{ number_format($membershipFee) }}</strong> is non-refundable under any circumstances.
                </li>
                <li>
                    <strong>Adjustment of Advance Monthly Fees:</strong> Any monthly fee paid in advance
                    (currently <strong>₹{{ number_format($monthlyAutoDebit) }}/month</strong>, inclusive of GST) shall be
                    adjusted in the subsequent month&rsquo;s bill.
                </li>
                <li>
                    <strong>Automatic Renewal After {{ $validityYears * 12 }} Months ({{ $validityYears }} Years):</strong>
                    Upon completion of {{ $validityYears * 12 }} months, your membership renews automatically through your
                    active recurring subscription mandate with no additional Commitment Fee.
                </li>
                <li>
                    <strong>Opting Out of Auto-Renewal:</strong> Customers who do not wish to auto-renew may discontinue
                    the membership before the renewal cycle by giving written notice or e-mail to
                    <a href="mailto:{{ $supportEmail }}" class="text-amber-700 font-semibold underline hover:text-amber-900">{{ $supportEmail }}</a>.
                </li>
            </ol>
        </section>

        <!-- Section 2: E-Puja Cancellation, Rescheduling & Refund Policy -->
        <section class="bg-white rounded-2xl border border-amber-200 p-6 sm:p-8 shadow-xs space-y-5">
            <div class="border-b border-amber-100 pb-4">
                <span class="text-[11px] font-bold uppercase tracking-wider text-amber-700 font-mono">
                    Haridwar Ghat Ritual Bookings
                </span>
                <h2 class="text-xl font-serif font-bold text-slate-900 mt-1">
                    2. E-Puja Cancellation, Rescheduling &amp; Refund Policy
                </h2>
            </div>

            <ol class="list-decimal pl-5 space-y-3 text-slate-700">
                <li>
                    <strong>Cancellation More Than 48 Hours Before Scheduled Puja:</strong> Eligible for a refund after deducting applicable payment gateway and processing charges, processed within
                    <strong>5 to 7 working days</strong>.
                </li>
                <li>
                    <strong>Cancellation Between 48 Hours and 24 Hours Before Scheduled Puja:</strong> Eligible for a
                    <strong>50% refund</strong> of the booking amount, processed within
                    <strong>5 to 7 working days</strong>.
                </li>
                <li>
                    <strong>Cancellation Less Than 24 Hours Before Scheduled Puja or No-Show:</strong>
                    <strong>No refund</strong> (100% Non-Refundable), as priest allocation and Sankalp ritual arrangements
                    are finalized prior to the ceremony.
                </li>
                <li>
                    <strong>Cancellation by Company:</strong> If the Company cancels due to inclement weather, river-ghat
                    restrictions, or unforeseen events, the devotee may choose either a mutually convenient alternative
                    auspicious date or a <strong>100% full refund</strong> within <strong>5 to 7 working days</strong>.
                </li>
                <li>
                    <strong>Rescheduling &amp; Sankalp Updates:</strong> Rescheduling requests must be made at least
                    <strong>24 hours</strong> prior to the scheduled slot (subject to slot availability). Any correction
                    to Sankalp details (names or gotra) must be submitted at least <strong>6 hours</strong> prior to the
                    ritual. Prasad &amp; Gangajal dispatch, where included, is initiated within
                    <strong>3 to 5 working days</strong> after the Puja.
                </li>
            </ol>
        </section>

        <!-- Section 3: Governing Law & Devotee Support -->
        <section class="bg-[#FFF8E1] rounded-2xl border border-amber-300 p-6 sm:p-8 shadow-xs space-y-4">
            <h2 class="text-lg font-serif font-bold text-[#881337]">
                3. Jurisdiction &amp; Devotee Cancellation Support
            </h2>
            <p class="text-slate-700">
                All disputes arising out of service or booking cancellations shall be governed by Indian law and subject to
                the exclusive jurisdiction of <strong>Haridwar District Court, Uttarakhand</strong>.
            </p>
            <div class="bg-white rounded-xl border border-amber-200 p-4 space-y-1.5">
                <p><strong>Company:</strong> M/s Haridwar Bliss Food &amp; Beverages Pvt. Ltd. (Blu Bliss)</p>
                <p><strong>Registered Office:</strong> G-46, Shivalik Nagar, BHEL, Ranipur More, Haridwar, Uttarakhand - 249403, India</p>
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
                <a href="{{ route('payment-delivery-policy') }}" class="text-amber-800 font-semibold underline hover:text-amber-950">Payment &amp; Delivery Policy</a>
                <a href="{{ route('online-puja') }}" class="text-amber-800 font-semibold underline hover:text-amber-950">Haridwar Ghat E-Puja</a>
                <a href="{{ route('membership.join') }}" class="text-amber-800 font-semibold underline hover:text-amber-950">5-Year Membership</a>
            </div>
        </section>
    </div>
</x-app-layout>

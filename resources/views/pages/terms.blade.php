<x-app-layout>
    <!-- Header Banner (Signature Maroon Hero Card on Solid Orange Band) -->
    <x-page-hero bandBg="bg-[#FFA028]"
                 badge="Legal & Service Policy Documentation"
                 title="Terms & Conditions"
                 subtitle="M/s HARIDWAR BLISS FOOD & BEVERAGES PVT. LTD. · Ganga Jal Seva Commitment Plans & Website Use">
        <x-slot:extra>
            <span class="inline-block text-[11px] text-amber-200/90 font-mono px-3 py-1 rounded-full bg-[#6B0E29] border border-amber-300/30">
                Effective Date: 17 November 2026
            </span>
        </x-slot:extra>
    </x-page-hero>

    <!-- Main Content Container -->
    <div class="bg-[#FFFDF7] py-12 sm:py-16 text-slate-800">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">

            <!-- Welcome Preamble Card -->
            <div class="bg-white rounded-3xl border-2 border-amber-200/80 p-6 sm:p-10 shadow-xl shadow-amber-950/5 space-y-4 text-xs sm:text-sm text-slate-700 leading-relaxed">
                <p class="font-medium text-slate-900 leading-normal">
                    Welcome to the official website of <strong>M/s HARIDWAR BLISS FOOD &amp; BEVERAGES PVT. LTD.</strong> These Terms &amp; Conditions govern your access to and use of our website, products, services, subscriptions and Ganga Jal Seva commitment plans.
                </p>
                <p>
                    By accessing our website, submitting an enquiry, placing an order, enrolling in a service plan or making a payment, you acknowledge that you have read and understood these Terms &amp; Conditions and agree to be bound by the applicable provisions.
                </p>
                <div class="p-4 bg-amber-50/70 border border-amber-200 rounded-2xl text-xs text-amber-900 font-medium">
                    ⚠️ If you do not agree with these Terms &amp; Conditions, please do not proceed with an online purchase or service enrolment.
                </div>
            </div>

            <!-- Quick Financial Summary Highlight Box -->
            <div class="bg-gradient-to-r from-amber-50 via-[#FFFBEB] to-amber-50 rounded-3xl border-2 border-[#D97706]/40 p-6 sm:p-8 shadow-md">
                <div class="flex items-center gap-2 mb-4 text-[#881337] font-serif font-bold text-base sm:text-lg">
                    <span class="text-xl">🪔</span>
                    <span>Five-Year Ganga Jal Seva Key Financial Terms</span>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
                    <div class="p-4 bg-white rounded-2xl border border-amber-200 shadow-2xs space-y-1">
                        <span class="text-[10px] uppercase font-bold text-slate-500 font-mono tracking-wider">One-Time Commitment Fee</span>
                        <div class="text-2xl font-serif font-extrabold text-[#D97706]">₹{{ number_format($membershipFee ?? 500) }}</div>
                        <p class="text-[11px] text-slate-600">Payable at enrolment towards the {{ $validityYears ?? 5 }}-year service commitment (non-refundable, subject to applicable law).</p>
                    </div>
                    <div class="p-4 bg-white rounded-2xl border border-amber-200 shadow-2xs space-y-1">
                        <span class="text-[10px] uppercase font-bold text-slate-500 font-mono tracking-wider">Monthly Service Charge</span>
                        <div class="text-2xl font-serif font-extrabold text-[#881337]">₹{{ number_format($monthlyAutoDebit ?? $shippingRate ?? 149) }} / mo</div>
                        <p class="text-[11px] text-slate-600">
                            Per {{ $monthlyVolume ?? 500 }} ml PET bottle, inclusive of applicable GST, auto-debited via your recurring mandate.
                            @if(($monthlyProductPrice ?? 0) > 0)
                                <span class="block text-[10px] text-slate-500 font-mono mt-0.5">(Product: ₹{{ number_format($monthlyProductPrice) }} + Delivery: ₹{{ number_format($monthlyDeliveryCharge ?? $shippingRate ?? 99) }})</span>
                            @endif
                        </p>
                    </div>
                    <div class="p-4 bg-white rounded-2xl border border-amber-200 shadow-2xs space-y-1">
                        <span class="text-[10px] uppercase font-bold text-slate-500 font-mono tracking-wider">After {{ ($validityYears ?? 5) * 12 }} Months</span>
                        <div class="text-2xl font-serif font-extrabold text-emerald-700">Auto-Renewed</div>
                        <p class="text-[11px] text-slate-600">Automatically renewed for the next {{ $validityYears ?? 5 }}-year tenure via your active mandate with no additional commitment fee.</p>
                    </div>
                </div>
            </div>

            <!-- Detailed 24 Clauses Container -->
            <div class="bg-white rounded-3xl border border-amber-200 p-6 sm:p-12 shadow-xl shadow-amber-950/5 space-y-10 text-xs sm:text-sm text-slate-700 leading-relaxed">

                <!-- 1. About the Company -->
                <section class="space-y-3 pb-8 border-b border-amber-100">
                    <h2 class="text-base sm:text-lg font-serif font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-full bg-[#D97706]/15 text-[#881337] flex items-center justify-center font-mono text-xs">1</span>
                        <span>About the Company</span>
                    </h2>
                    <p>
                        <strong>M/s HARIDWAR BLISS FOOD &amp; BEVERAGES PVT. LTD.</strong> (hereinafter referred to as "Company", "We", "Us" or "Our") provides Ganga Jal Seva services, including the supply and delivery of Ganga Jal sourced from Haridwar.
                    </p>
                    <p>
                        Our objective is to provide customers with a convenient and organised service for receiving Ganga Jal at their registered delivery addresses.
                    </p>
                    <p>
                        For customer support, enquiries, complaints and service-related communication, you may contact:
                    </p>
                    <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200 text-xs text-slate-700 space-y-1">
                        <div>Email: <a href="mailto:sales@haridwarbliss.com" class="text-[#D97706] font-bold hover:underline font-mono">sales@haridwarbliss.com</a></div>
                    </div>
                </section>

                <!-- 2. Scope of Services -->
                <section class="space-y-3 pb-8 border-b border-amber-100">
                    <h2 class="text-base sm:text-lg font-serif font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-full bg-[#D97706]/15 text-[#881337] flex items-center justify-center font-mono text-xs">2</span>
                        <span>Scope of Services</span>
                    </h2>
                    <p>The Company offers Ganga Jal Seva services through its website and other authorised channels.</p>
                    <p>Under the five-year commitment service plan:</p>
                    <ol class="list-decimal pl-5 space-y-2 text-slate-700">
                        <li>The Company provides Ganga Jal sourced from Haridwar.</li>
                        <li>The standard service product covered by the agreement is a <strong>{{ $monthlyVolume ?? 500 }} ml PET bottle</strong>.</li>
                        <li>The service is intended to provide one delivery per month to the customer's registered delivery address.</li>
                        <li>The service period is <strong>{{ ($validityYears ?? 5) * 12 }} consecutive months</strong>, subject to the terms of the applicable service agreement.</li>
                        <li>Customers must provide accurate contact and delivery information when registering for the service.</li>
                    </ol>
                    <p class="text-xs text-slate-500">
                        The exact service, quantity, pricing and applicable conditions will be determined by the plan selected and the agreement accepted by the customer.
                    </p>
                </section>

                <!-- 3. Customer Registration and Information -->
                <section class="space-y-3 pb-8 border-b border-amber-100">
                    <h2 class="text-base sm:text-lg font-serif font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-full bg-[#D97706]/15 text-[#881337] flex items-center justify-center font-mono text-xs">3</span>
                        <span>Customer Registration and Information</span>
                    </h2>
                    <p>Customers may be required to provide personal and contact information to register for a service, submit an enquiry or complete a purchase.</p>
                    <p>The information may include:</p>
                    <ul class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs text-slate-700 pl-5 list-disc">
                        <li>Full name.</li>
                        <li>Mobile number.</li>
                        <li>Email address.</li>
                        <li>Complete delivery address.</li>
                        <li>Billing information.</li>
                        <li>Payment confirmation and transaction reference.</li>
                        <li>Other information reasonably required to provide the requested service.</li>
                    </ul>
                    <p>Customers are responsible for ensuring that all information submitted is accurate, complete and up to date.</p>
                    <p>The Company shall not be responsible for delivery failures or communication problems arising directly from incorrect or incomplete information supplied by the customer.</p>
                    <p>Customers must promptly notify the Company if their registered contact or delivery details change.</p>
                </section>

                <!-- 4. Five-Year Ganga Jal Seva Commitment Plan -->
                <section class="space-y-4 pb-8 border-b border-amber-100">
                    <h2 class="text-base sm:text-lg font-serif font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-full bg-[#D97706]/15 text-[#881337] flex items-center justify-center font-mono text-xs">4</span>
                        <span>Five-Year Ganga Jal Seva Commitment Plan</span>
                    </h2>
                    <p>The five-year Ganga Jal Seva plan operates under a service commitment of {{ ($validityYears ?? 5) * 12 }} months.</p>
                    <p>The principal financial terms are:</p>

                    <div class="space-y-3">
                        <div class="p-4 bg-amber-50/60 rounded-2xl border border-amber-200 space-y-1.5">
                            <h3 class="font-bold text-slate-900 text-xs sm:text-sm">A. One-Time Commitment Fee</h3>
                            <p class="text-xs text-slate-700">
                                The customer is required to pay a one-time commitment fee of <strong>₹{{ number_format($membershipFee ?? 500) }}</strong> at the time of enrolment.
                            </p>
                            <p class="text-xs text-slate-700">
                                This fee is charged towards the {{ $validityYears ?? 5 }}-year service commitment and is non-refundable, subject to applicable consumer protection laws and any refund required by law.
                            </p>
                        </div>

                        <div class="p-4 bg-amber-50/60 rounded-2xl border border-amber-200 space-y-1.5">
                            <h3 class="font-bold text-slate-900 text-xs sm:text-sm">B. Monthly Service Charge</h3>
                            <p class="text-xs text-slate-700">
                                The monthly service charge is <strong>₹{{ number_format($monthlyAutoDebit ?? $shippingRate ?? 149) }} per {{ $monthlyVolume ?? 500 }} ml bottle</strong>, inclusive of applicable GST, as specified in the service agreement.
                                @if(($monthlyProductPrice ?? 0) > 0)
                                    <span class="text-slate-600">(Configured as Product: ₹{{ number_format($monthlyProductPrice) }} + Delivery: ₹{{ number_format($monthlyDeliveryCharge ?? $shippingRate ?? 99) }}.)</span>
                                @endif
                            </p>
                        </div>

                        <div class="p-4 bg-amber-50/60 rounded-2xl border border-amber-200 space-y-1.5">
                            <h3 class="font-bold text-slate-900 text-xs sm:text-sm">C. Payment Schedule</h3>
                            <ul class="list-disc pl-5 text-xs text-slate-700 space-y-1">
                                <li>The one-time commitment fee (₹{{ number_format($membershipFee ?? 500) }}) must be paid at the time of enrolment.</li>
                                <li>The monthly service charge (₹{{ number_format($monthlyAutoDebit ?? $shippingRate ?? 149) }}) is debited automatically each monthly billing cycle through your authorized recurring payment mandate.</li>
                                <li>Applicable taxes will be charged in accordance with prevailing laws.</li>
                                <li>A valid tax invoice will be issued for applicable transactions.</li>
                            </ul>
                            <p class="text-xs text-slate-600 pt-1">The customer should retain payment confirmations and invoices for future reference.</p>
                        </div>
                    </div>
                </section>

                <!-- 5. Service Duration and Renewal -->
                <section class="space-y-3 pb-8 border-b border-amber-100">
                    <h2 class="text-base sm:text-lg font-serif font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-full bg-[#D97706]/15 text-[#881337] flex items-center justify-center font-mono text-xs">5</span>
                        <span>Service Duration and Renewal</span>
                    </h2>
                    <p>
                        The {{ $validityYears ?? 5 }}-year commitment plan shall remain valid for <strong>{{ ($validityYears ?? 5) * 12 }} months</strong> from its commencement date as specified in the applicable service agreement.
                    </p>
                    <p>Upon completion of the {{ ($validityYears ?? 5) * 12 }}-month period:</p>
                    <ol class="list-decimal pl-5 space-y-1.5 text-xs text-slate-700">
                        <li>Your {{ $validityYears ?? 5 }}-year membership is <strong>automatically renewed</strong> via your active recurring subscription mandate for the subsequent tenure without interruption.</li>
                        <li>No additional {{ $validityYears ?? 5 }}-year commitment fee is charged on automatic renewal.</li>
                        <li>Monthly doorstep Ganga Jal deliveries continue uninterrupted at your configured monthly billing rate (subject to 30 days prior written notice in the event of any future price revision).</li>
                        <li>Customers who do not wish to auto-renew may request discontinuation prior to renewal in accordance with the Company's <a href="{{ route('cancellation-policy') }}" class="text-[#D97706] font-bold hover:underline">service cancellation policy</a>.</li>
                    </ol>
                    <p class="text-xs text-slate-500">
                        Automatic renewal remains active alongside your recurring mandate unless cancelled by the customer or discontinued under these Terms.
                    </p>
                </section>

                <!-- 6. Delivery Terms -->
                <section class="space-y-3 pb-8 border-b border-amber-100">
                    <h2 class="text-base sm:text-lg font-serif font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-full bg-[#D97706]/15 text-[#881337] flex items-center justify-center font-mono text-xs">6</span>
                        <span>Delivery Terms</span>
                    </h2>
                    <p>The Company will arrange delivery of the subscribed Ganga Jal service to the delivery address provided by the customer.</p>
                    <p>Customers must ensure that:</p>
                    <ul class="list-disc pl-5 space-y-1 text-xs text-slate-700">
                        <li>The delivery address is complete and accurate.</li>
                        <li>The registered mobile number is active.</li>
                        <li>Someone is available to receive the delivery where necessary.</li>
                        <li>Any change in delivery address is communicated at least <strong>15 days in advance</strong>.</li>
                    </ul>
                    <p>
                        Delivery schedules may be affected by operational limitations, transportation disruptions, weather conditions, natural calamities, government restrictions or other circumstances beyond the Company's reasonable control.
                    </p>
                    <p>
                        The Company will make reasonable efforts to communicate significant delivery interruptions and resume the service when circumstances permit.
                    </p>
                    <p>
                        If a delivery is delayed or missed, the customer may contact customer support for assistance.
                    </p>
                </section>

                <!-- 7. Customer Responsibilities -->
                <section class="space-y-3 pb-8 border-b border-amber-100">
                    <h2 class="text-base sm:text-lg font-serif font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-full bg-[#D97706]/15 text-[#881337] flex items-center justify-center font-mono text-xs">7</span>
                        <span>Customer Responsibilities</span>
                    </h2>
                    <p>By enrolling in the service, customers agree to:</p>
                    <ol class="list-decimal pl-5 space-y-1.5 text-xs text-slate-700">
                        <li>Provide correct registration, billing and delivery details.</li>
                        <li>Pay the applicable commitment fee and monthly charges within the prescribed time.</li>
                        <li>Maintain an active contact number and provide updated contact information.</li>
                        <li>Notify the Company at least 15 days before changing the delivery address.</li>
                        <li>Cooperate with reasonable delivery arrangements.</li>
                        <li>Use the supplied product responsibly and in accordance with its packaging and handling instructions.</li>
                        <li>Avoid providing false information or misusing the Company's website or services.</li>
                        <li>Communicate service-related complaints through the Company's official support channel (<a href="mailto:sales@haridwarbliss.com" class="text-[#D97706] font-bold hover:underline">sales@haridwarbliss.com</a>).</li>
                    </ol>
                    <p>
                        The customer is responsible for keeping payment records and ensuring that payments are made within the applicable due dates.
                    </p>
                </section>

                <!-- 8. Payment Terms and Failed Transactions -->
                <section class="space-y-3 pb-8 border-b border-amber-100">
                    <h2 class="text-base sm:text-lg font-serif font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-full bg-[#D97706]/15 text-[#881337] flex items-center justify-center font-mono text-xs">8</span>
                        <span>Payment Terms and Failed Transactions</span>
                    </h2>
                    <p>Payments must be made through the payment methods made available by the Company.</p>
                    <p>A payment will be considered successful only when the transaction is confirmed by the applicable payment system and the Company receives the relevant payment confirmation.</p>
                    <p>In case of a failed, pending, duplicated or incorrectly processed transaction, customers should contact the Company with the relevant transaction details.</p>
                    <p>Where an amount has been debited but the order or enrolment has not been confirmed, the Company will examine the transaction and coordinate with the relevant payment service provider, where necessary.</p>
                    <p>Customers should not make repeated payments for the same transaction without first checking its status.</p>
                    <p>Any refund arising from a duplicate or erroneous payment will be handled after verification and in accordance with applicable law and the relevant payment provider's procedures.</p>
                </section>

                <!-- 9. Non-Refundable Commitment Fee -->
                <section class="space-y-3 pb-8 border-b border-amber-100">
                    <h2 class="text-base sm:text-lg font-serif font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-full bg-[#D97706]/15 text-[#881337] flex items-center justify-center font-mono text-xs">9</span>
                        <span>Non-Refundable Commitment Fee</span>
                    </h2>
                    <p>The one-time commitment fee (₹{{ number_format($membershipFee ?? 500) }}) is associated with the {{ $validityYears ?? 5 }}-year Ganga Jal Seva arrangement.</p>
                    <p>If a customer voluntarily discontinues the service before completion of the commitment period, the commitment fee will not ordinarily be refunded.</p>
                    <p>However, nothing in this clause is intended to exclude any refund, remedy or consumer right that cannot lawfully be excluded under applicable Indian law.</p>
                    <p>The non-refundable nature of the commitment fee does not automatically make separately paid monthly charges non-refundable in every situation.</p>
                    <p>Any advance monthly payment will be dealt with in accordance with the applicable cancellation and adjustment policy.</p>
                </section>

                <!-- 10. Cancellation and Discontinuation -->
                <section id="cancellation" class="space-y-3 pb-8 border-b border-amber-100">
                    <h2 class="text-base sm:text-lg font-serif font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-full bg-[#D97706]/15 text-[#881337] flex items-center justify-center font-mono text-xs">10</span>
                        <span>Cancellation and Discontinuation</span>
                    </h2>
                    <p>
                        Customers wishing to discontinue their monthly service may submit a written cancellation request or send an email to:
                        <a href="mailto:sales@haridwarbliss.com" class="text-[#D97706] font-bold hover:underline font-mono">sales@haridwarbliss.com</a>.
                    </p>
                    <p>The request should contain the customer's registered name, mobile number and sufficient information to identify the service subscription.</p>
                    <p>The Company may contact the customer to verify the request before processing cancellation.</p>
                    <p>The following conditions apply:</p>
                    <ol class="list-decimal pl-5 space-y-1 text-xs text-slate-700">
                        <li>Customers may request discontinuation of the service in accordance with the cancellation policy.</li>
                        <li>The one-time commitment fee is ordinarily non-refundable.</li>
                        <li>Monthly charges already paid in advance will be adjusted in accordance with the applicable billing and cancellation terms.</li>
                        <li>Any outstanding monthly payment must be settled.</li>
                        <li>Cancellation does not remove any payment obligation that became due before the effective cancellation date.</li>
                        <li>Any statutory consumer rights and remedies remain unaffected.</li>
                    </ol>
                    <p>
                        For complete details, customers should also refer to the Company's <a href="{{ route('cancellation-policy') }}" class="text-[#D97706] font-bold hover:underline">Service Cancellation Policy</a>.
                    </p>
                </section>

                <!-- 11. Price Changes and Service Modifications -->
                <section class="space-y-3 pb-8 border-b border-amber-100">
                    <h2 class="text-base sm:text-lg font-serif font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-full bg-[#D97706]/15 text-[#881337] flex items-center justify-center font-mono text-xs">11</span>
                        <span>Price Changes and Service Modifications</span>
                    </h2>
                    <p>The Company may revise service prices where reasonably necessary due to changes in operational expenses, supply costs, taxes, transportation expenses or other relevant business circumstances.</p>
                    <p>Where a price revision affects an existing service agreement, the Company will provide at least <strong>30 days' prior written notice</strong>, subject to the applicable agreement and law.</p>
                    <p>If the customer does not agree with a proposed price revision, the customer may request termination of the service.</p>
                    <p>The commitment fee shall remain subject to its non-refundable conditions, except where a refund or other remedy is required under applicable law.</p>
                    <p>Changes in statutory taxes will be applied as required by law.</p>
                </section>

                <!-- 12. Product Quality, Packaging and Handling -->
                <section class="space-y-3 pb-8 border-b border-amber-100">
                    <h2 class="text-base sm:text-lg font-serif font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-full bg-[#D97706]/15 text-[#881337] flex items-center justify-center font-mono text-xs">12</span>
                        <span>Product Quality, Packaging and Handling</span>
                    </h2>
                    <p>The Company will make reasonable efforts to supply Ganga Jal in the stated packaging and maintain appropriate handling and delivery practices.</p>
                    <p>Customers should inspect the packaging upon receiving the product.</p>
                    <p>If a bottle is found damaged, leaking, improperly sealed or otherwise unsuitable upon delivery, the customer should notify the Company promptly with relevant details and supporting photographs, where available, at <a href="mailto:sales@haridwarbliss.com" class="text-[#D97706] font-bold hover:underline">sales@haridwarbliss.com</a>.</p>
                    <p>The Company will review such complaints and determine an appropriate resolution in accordance with applicable law and the circumstances of the complaint.</p>
                    <p>Customers should store and handle the product responsibly and follow any instructions provided on its packaging.</p>
                </section>

                <!-- 13. Temporary Service Interruptions and Force Majeure -->
                <section class="space-y-3 pb-8 border-b border-amber-100">
                    <h2 class="text-base sm:text-lg font-serif font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-full bg-[#D97706]/15 text-[#881337] flex items-center justify-center font-mono text-xs">13</span>
                        <span>Temporary Service Interruptions and Force Majeure</span>
                    </h2>
                    <p>The Company shall not be considered in breach of its obligations to the extent that performance is prevented or materially delayed by circumstances beyond its reasonable control.</p>
                    <p>Such circumstances may include:</p>
                    <ul class="list-disc pl-5 space-y-1 text-xs text-slate-700">
                        <li>Natural disasters, floods, earthquakes or severe weather.</li>
                        <li>Government orders, legal restrictions or regulatory actions.</li>
                        <li>Transportation and logistics disruptions.</li>
                        <li>Public emergencies or civil disturbances.</li>
                        <li>Strikes, supply-chain interruptions or other events beyond reasonable control.</li>
                    </ul>
                    <p>The Company will make reasonable efforts to minimise the impact of such circumstances and restore the affected services as soon as reasonably practicable.</p>
                    <p>Any payments or service adjustments arising from an interruption will be handled in accordance with the applicable agreement and law.</p>
                </section>

                <!-- 14. Website Use and Prohibited Activities -->
                <section class="space-y-3 pb-8 border-b border-amber-100">
                    <h2 class="text-base sm:text-lg font-serif font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-full bg-[#D97706]/15 text-[#881337] flex items-center justify-center font-mono text-xs">14</span>
                        <span>Website Use and Prohibited Activities</span>
                    </h2>
                    <p>Customers agree to use the website only for lawful purposes.</p>
                    <p>You must not:</p>
                    <ol class="list-decimal pl-5 space-y-1 text-xs text-slate-700">
                        <li>Use the website for fraudulent or misleading activities.</li>
                        <li>Attempt to gain unauthorised access to the website or its systems.</li>
                        <li>Interfere with the website's security or functionality.</li>
                        <li>Upload malicious software or harmful code.</li>
                        <li>Copy, reproduce or commercially exploit website content without permission.</li>
                        <li>Submit false registration, payment or delivery information.</li>
                        <li>Use the Company's name, branding or materials in a misleading manner.</li>
                    </ol>
                    <p>The Company reserves the right to restrict access where there is reasonable evidence of misuse, fraud or unlawful activity.</p>
                </section>

                <!-- 15. Intellectual Property -->
                <section class="space-y-3 pb-8 border-b border-amber-100">
                    <h2 class="text-base sm:text-lg font-serif font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-full bg-[#D97706]/15 text-[#881337] flex items-center justify-center font-mono text-xs">15</span>
                        <span>Intellectual Property</span>
                    </h2>
                    <p>All original website content, including text, graphics, logos, designs, photographs, branding, page layouts and other materials owned or licensed by the Company, is protected under applicable intellectual property laws.</p>
                    <p>No person may reproduce, distribute, modify, publish or commercially exploit such materials without prior written authorisation from the relevant rights holder.</p>
                    <p>Unauthorised use may result in appropriate legal action.</p>
                </section>

                <!-- 16. Third-Party Services and External Links -->
                <section class="space-y-3 pb-8 border-b border-amber-100">
                    <h2 class="text-base sm:text-lg font-serif font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-full bg-[#D97706]/15 text-[#881337] flex items-center justify-center font-mono text-xs">16</span>
                        <span>Third-Party Services and External Links</span>
                    </h2>
                    <p>The website may use or provide access to third-party services, including payment processing, communication, hosting or other technical services.</p>
                    <p>External websites may have their own terms, privacy policies and security practices.</p>
                    <p>The Company does not control the independent policies or operations of third-party websites and services.</p>
                    <p>Customers should review the relevant third-party terms before using those services.</p>
                    <p>This clause does not limit any responsibility imposed on the Company under applicable law.</p>
                </section>

                <!-- 17. Limitation of Liability -->
                <section class="space-y-3 pb-8 border-b border-amber-100">
                    <h2 class="text-base sm:text-lg font-serif font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-full bg-[#D97706]/15 text-[#881337] flex items-center justify-center font-mono text-xs">17</span>
                        <span>Limitation of Liability</span>
                    </h2>
                    <p>The Company shall take reasonable care in providing its services.</p>
                    <p>To the extent permitted by applicable law, the Company shall not be liable for indirect or consequential losses arising from circumstances beyond its reasonable control.</p>
                    <p>Nothing in these Terms &amp; Conditions shall exclude or limit liability for fraud, wilful misconduct, gross negligence where liability cannot lawfully be excluded, or any consumer right or remedy that cannot be waived under Indian law.</p>
                    <p>The Company remains responsible for its obligations under the applicable service agreement and relevant laws.</p>
                </section>

                <!-- 18. Privacy and Protection of Personal Information -->
                <section class="space-y-3 pb-8 border-b border-amber-100">
                    <h2 class="text-base sm:text-lg font-serif font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-full bg-[#D97706]/15 text-[#881337] flex items-center justify-center font-mono text-xs">18</span>
                        <span>Privacy and Protection of Personal Information</span>
                    </h2>
                    <p>
                        The Company respects the privacy of customers and handles personal information in accordance with its <a href="{{ route('privacy') }}" class="text-[#D97706] font-bold hover:underline">Privacy Policy</a> and applicable Indian data protection laws.
                    </p>
                    <p>Information provided by customers may be used for:</p>
                    <ul class="list-disc pl-5 space-y-1 text-xs text-slate-700">
                        <li>Processing service registrations and orders.</li>
                        <li>Arranging deliveries.</li>
                        <li>Processing and verifying payments.</li>
                        <li>Issuing invoices.</li>
                        <li>Responding to enquiries and complaints.</li>
                        <li>Sending service-related updates.</li>
                        <li>Meeting legal, accounting and regulatory obligations.</li>
                    </ul>
                    <p>
                        For further information, please read our <a href="{{ route('privacy') }}" class="text-[#D97706] font-bold hover:underline">Privacy Policy</a>.
                    </p>
                </section>

                <!-- 19. Grievances and Customer Support -->
                <section class="space-y-3 pb-8 border-b border-amber-100">
                    <h2 class="text-base sm:text-lg font-serif font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-full bg-[#D97706]/15 text-[#881337] flex items-center justify-center font-mono text-xs">19</span>
                        <span>Grievances and Customer Support</span>
                    </h2>
                    <p>Customers may contact the Company regarding:</p>
                    <ul class="list-disc pl-5 space-y-1 text-xs text-slate-700">
                        <li>Registration and enrolment.</li>
                        <li>Delivery delays or missing deliveries.</li>
                        <li>Payment and invoice issues.</li>
                        <li>Product quality concerns.</li>
                        <li>Cancellation requests.</li>
                        <li>Refund or payment adjustment queries.</li>
                        <li>Privacy-related concerns.</li>
                        <li>Other service-related complaints.</li>
                    </ul>
                    <div class="p-4 bg-amber-50/70 border border-amber-200 rounded-2xl space-y-1">
                        <span class="text-xs font-bold text-[#881337] block">Official Customer Support and Grievance Email:</span>
                        <div class="text-xs text-slate-800">
                            Email: <a href="mailto:sales@haridwarbliss.com" class="text-[#D97706] font-bold hover:underline font-mono">sales@haridwarbliss.com</a>
                        </div>
                        <div class="text-xs text-slate-800">
                            Support Phone: <a href="tel:+917042482555" class="text-[#D97706] font-bold hover:underline font-mono">+91 7042482555</a>
                        </div>
                    </div>
                    <p>The Company will acknowledge and review complaints and make reasonable efforts to resolve them within the time required under applicable law.</p>
                </section>

                <!-- 20. Amendments to These Terms -->
                <section class="space-y-3 pb-8 border-b border-amber-100">
                    <h2 class="text-base sm:text-lg font-serif font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-full bg-[#D97706]/15 text-[#881337] flex items-center justify-center font-mono text-xs">20</span>
                        <span>Amendments to These Terms</span>
                    </h2>
                    <p>The Company may revise these Terms &amp; Conditions to reflect changes in its services, operations, applicable laws or business practices.</p>
                    <p>Updated terms will be published on the website with an appropriate effective or updated date.</p>
                    <p>Changes will not retrospectively remove rights that customers have already acquired or override an existing service agreement, except where permitted or required by law.</p>
                    <p>Customers are encouraged to review this page periodically.</p>
                </section>

                <!-- 21. Governing Law and Jurisdiction -->
                <section class="space-y-3 pb-8 border-b border-amber-100">
                    <h2 class="text-base sm:text-lg font-serif font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-full bg-[#D97706]/15 text-[#881337] flex items-center justify-center font-mono text-xs">21</span>
                        <span>Governing Law and Jurisdiction</span>
                    </h2>
                    <p>These Terms &amp; Conditions shall be governed by the laws of India.</p>
                    <p>
                        Disputes arising in connection with the five-year Ganga Jal Seva service agreement shall be subject to the jurisdiction provisions specified in the applicable agreement, which identifies the <strong>Haridwar District Court, Uttarakhand</strong>.
                    </p>
                    <p>Nothing in this clause shall prevent a consumer from exercising any non-waivable right to approach a competent consumer commission or other authority under applicable law.</p>
                </section>

                <!-- 22. Severability -->
                <section class="space-y-3 pb-8 border-b border-amber-100">
                    <h2 class="text-base sm:text-lg font-serif font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-full bg-[#D97706]/15 text-[#881337] flex items-center justify-center font-mono text-xs">22</span>
                        <span>Severability</span>
                    </h2>
                    <p>If any provision of these Terms &amp; Conditions is held invalid, unlawful or unenforceable, the remaining provisions shall continue to remain effective to the extent permitted by law.</p>
                </section>

                <!-- 23. Entire Agreement -->
                <section class="space-y-3 pb-8 border-b border-amber-100">
                    <h2 class="text-base sm:text-lg font-serif font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-full bg-[#D97706]/15 text-[#881337] flex items-center justify-center font-mono text-xs">23</span>
                        <span>Entire Agreement</span>
                    </h2>
                    <p>
                        These Terms &amp; Conditions, together with the applicable service agreement, <a href="{{ route('cancellation-policy') }}" class="text-[#D97706] font-bold hover:underline">cancellation policy</a>, <a href="{{ route('payment-delivery-policy') }}" class="text-[#D97706] font-bold hover:underline">payment &amp; delivery policy</a> and <a href="{{ route('privacy') }}" class="text-[#D97706] font-bold hover:underline">Privacy Policy</a>, constitute the terms governing the customer's use of the website and the relevant services.
                    </p>
                    <p>Where a specific written service agreement contains service-specific provisions, those provisions shall govern the relevant service to the extent of any inconsistency, subject always to applicable law.</p>
                </section>

                <!-- 24. Contact Information -->
                <section class="space-y-3">
                    <h2 class="text-base sm:text-lg font-serif font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-full bg-[#D97706]/15 text-[#881337] flex items-center justify-center font-mono text-xs">24</span>
                        <span>Contact Information</span>
                    </h2>
                    <p>For any questions, concerns, clarification or assistance regarding these Terms &amp; Conditions, please contact:</p>
                    <div class="p-5 bg-[#25050A] text-white rounded-2xl border border-amber-300/30 space-y-2 text-xs">
                        <div class="font-bold text-amber-200 text-sm">M/s HARIDWAR BLISS FOOD &amp; BEVERAGES PVT. LTD.</div>
                        <div class="text-slate-300">CIN: U11040UT2025PTC019429</div>
                        <div>Email: <a href="mailto:sales@haridwarbliss.com" class="text-amber-300 font-bold hover:underline font-mono">sales@haridwarbliss.com</a></div>
                        <div>Website: <a href="{{ url('/') }}" class="text-amber-300 hover:underline">{{ url('/') }}</a></div>
                        <div class="text-slate-300 pt-1">G-46, Shivalik Nagar, BHEL, Ranipur More, Haridwar, Uttarakhand, India - 249403</div>
                    </div>
                    <p class="text-xs text-slate-500 pt-2 italic text-center">
                        Thank you for choosing our Ganga Jal Seva services.
                    </p>
                </section>

            </div>

            <!-- Back to Home / Browse Offerings -->
            <div class="text-center pt-4">
                <a href="{{ route('home') }}"
                   class="inline-flex items-center gap-2 px-6 py-2.5 bg-[#D97706] hover:bg-[#F97316] text-white rounded-xl text-xs font-bold transition shadow-xs">
                    <span>← Return to Home</span>
                </a>
            </div>

        </div>
    </div>
</x-app-layout>

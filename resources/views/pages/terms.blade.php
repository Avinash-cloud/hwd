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
                    Welcome to the official website of <strong>M/s HARIDWAR BLISS FOOD &amp; BEVERAGES PVT. LTD.</strong> These Terms &amp; Conditions govern your access to and use of our website, products, services, subscriptions, and Ganga Jal Seva commitment plans.
                </p>
                <p>
                    By accessing our website, submitting an enquiry, placing an order, enrolling in a service plan, or making a payment, you acknowledge that you have read, understood, and agree to be bound by these Terms &amp; Conditions and applicable legal provisions.
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
                        <span class="text-[10px] uppercase font-bold text-slate-500 font-mono tracking-wider">One-Time Fee</span>
                        <div class="text-2xl font-serif font-extrabold text-[#D97706]">₹{{ number_format($membershipFee ?? 797) }}</div>
                        <p class="text-[11px] text-slate-600">Payable once at enrolment towards the {{ $validityYears ?? 5 }}-year commitment (Non-refundable).</p>
                    </div>
                    <div class="p-4 bg-white rounded-2xl border border-amber-200 shadow-2xs space-y-1">
                        <span class="text-[10px] uppercase font-bold text-slate-500 font-mono tracking-wider">Monthly Service Charge</span>
                        <div class="text-2xl font-serif font-extrabold text-[#881337]">₹{{ number_format($monthlyAutoDebit ?? $shippingRate ?? 149) }} / mo</div>
                        <p class="text-[11px] text-slate-600">Per {{ $monthlyVolume ?? 500 }} ml bottle delivery, inclusive of applicable GST, payable by the 5th of each month.</p>
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
                        <strong>M/s HARIDWAR BLISS FOOD &amp; BEVERAGES PVT. LTD.</strong> (hereinafter referred to as "Company", "We", "Us" or "Our") provides Ganga Jal Seva services, including the authentic supply and doorstep delivery of pure Ganga Jal sourced directly from sacred Haridwar ghats.
                    </p>
                    <p>
                        Our primary objective is to provide devotees and customers with a convenient, reliable, and organised service for receiving consecrated Ganga Jal at their registered delivery addresses across India.
                    </p>
                    <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200 text-xs text-slate-700 space-y-1">
                        <span class="font-bold text-slate-900 block">Official Support &amp; Communication Channel:</span>
                        <div>Email: <a href="mailto:sales@haridwarbliss.com" class="text-[#D97706] font-bold hover:underline font-mono">sales@haridwarbliss.com</a></div>
                    </div>
                </section>

                <!-- 2. Scope of Services -->
                <section class="space-y-3 pb-8 border-b border-amber-100">
                    <h2 class="text-base sm:text-lg font-serif font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-full bg-[#D97706]/15 text-[#881337] flex items-center justify-center font-mono text-xs">2</span>
                        <span>Scope of Services</span>
                    </h2>
                    <p>The Company offers Ganga Jal Seva services through its website and other authorised channels. Under the five-year commitment service plan:</p>
                    <ol class="list-decimal pl-5 space-y-2 text-slate-700">
                        <li>The Company provides Ganga Jal sourced directly from Haridwar.</li>
                        <li>The standard service product covered by the agreement is a <strong>{{ $monthlyVolume ?? 500 }} ml PET bottle</strong>.</li>
                        <li>The service is intended to provide one (1) delivery per month to the customer's registered delivery address.</li>
                        <li>The service commitment period is <strong>{{ ($validityYears ?? 5) * 12 }} ({{ ($validityYears ?? 5) * 12 }}) consecutive months</strong>, subject to the terms of the applicable service agreement.</li>
                        <li>Customers must provide accurate contact and delivery information when registering for the service.</li>
                    </ol>
                    <p class="text-xs text-slate-500">
                        The exact service, quantity, pricing, and applicable conditions will be determined by the plan selected and the agreement accepted by the customer.
                    </p>
                </section>

                <!-- 3. Customer Registration and Information -->
                <section class="space-y-3 pb-8 border-b border-amber-100">
                    <h2 class="text-base sm:text-lg font-serif font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-full bg-[#D97706]/15 text-[#881337] flex items-center justify-center font-mono text-xs">3</span>
                        <span>Customer Registration and Information</span>
                    </h2>
                    <p>Customers may be required to provide personal and contact information to register for a service, submit an enquiry, or complete a purchase. The information may include:</p>
                    <ul class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs text-slate-700 pl-4 list-disc">
                        <li>Full name</li>
                        <li>Active mobile number</li>
                        <li>Email address</li>
                        <li>Complete postal delivery address with Pincode</li>
                        <li>Billing information</li>
                        <li>Payment confirmation and transaction reference</li>
                        <li>Other information reasonably required to fulfill the requested service</li>
                    </ul>
                    <p>
                        Customers are responsible for ensuring that all submitted information is accurate, complete, and up to date. The Company shall not be responsible for delivery failures, delays, or communication problems arising directly from incorrect or incomplete information supplied by the customer. Customers must promptly notify the Company if their registered contact or delivery details change.
                    </p>
                </section>

                <!-- 4. Five-Year Ganga Jal Seva Commitment Plan -->
                <section class="space-y-4 pb-8 border-b border-amber-100">
                    <h2 class="text-base sm:text-lg font-serif font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-full bg-[#D97706]/15 text-[#881337] flex items-center justify-center font-mono text-xs">4</span>
                        <span>Five-Year Ganga Jal Seva Commitment Plan</span>
                    </h2>
                    <p>The five-year Ganga Jal Seva plan operates under a service commitment of 60 consecutive months. The principal financial terms are:</p>

                    <div class="space-y-3">
                        <div class="p-4 bg-amber-50/60 rounded-2xl border border-amber-200">
                            <h3 class="font-bold text-slate-900 text-xs sm:text-sm">A. One-Time Commitment Fee: ₹{{ number_format($membershipFee ?? 797) }}</h3>
                            <p class="mt-1 text-xs text-slate-700">
                                The customer is required to pay a one-time commitment fee of <strong>₹{{ number_format($membershipFee ?? 797) }}</strong> at the time of enrolment. This fee is charged towards the {{ $validityYears ?? 5 }}-year service commitment and is non-refundable, subject to applicable consumer protection laws and any refund required by law.
                            </p>
                        </div>

                        <div class="p-4 bg-amber-50/60 rounded-2xl border border-amber-200">
                            <h3 class="font-bold text-slate-900 text-xs sm:text-sm">B. Monthly Service Charge: ₹{{ number_format($monthlyAutoDebit ?? $shippingRate ?? 149) }} per Bottle</h3>
                            <p class="mt-1 text-xs text-slate-700">
                                The monthly service charge is <strong>₹{{ number_format($monthlyAutoDebit ?? $shippingRate ?? 149) }} per {{ $monthlyVolume ?? 500 }} ml bottle</strong>, inclusive of applicable GST, as specified in the service agreement.
                            </p>
                        </div>

                        <div class="p-4 bg-amber-50/60 rounded-2xl border border-amber-200">
                            <h3 class="font-bold text-slate-900 text-xs sm:text-sm">C. Payment Schedule</h3>
                            <ul class="list-disc pl-5 mt-1 text-xs text-slate-700 space-y-1">
                                <li>The one-time commitment fee (₹{{ number_format($membershipFee ?? 797) }}) must be paid at the time of initial enrolment.</li>
                                <li>The monthly service charge (₹{{ number_format($monthlyAutoDebit ?? $shippingRate ?? 149) }}) must be paid by the <strong>5th day of each calendar month</strong>.</li>
                                <li>Applicable taxes will be charged in accordance with prevailing laws.</li>
                                <li>A valid tax invoice will be issued for all applicable transactions. The customer should retain payment confirmations and invoices for future reference.</li>
                            </ul>
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
                        The {{ $validityYears ?? 5 }}-year commitment plan shall remain valid for <strong>{{ ($validityYears ?? 5) * 12 }} consecutive months</strong> from its commencement date as specified in the applicable service agreement. Upon completion of the {{ ($validityYears ?? 5) * 12 }}-month period:
                    </p>
                    <ol class="list-decimal pl-5 space-y-1.5 text-xs text-slate-700">
                        <li>The existing {{ $validityYears ?? 5 }}-year agreement shall automatically conclude.</li>
                        <li><strong>No additional {{ $validityYears ?? 5 }}-year commitment fee</strong> shall be charged merely for continuing the monthly service.</li>
                        <li>Customers wishing to continue receiving Ganga Jal may do so under the applicable continuation arrangement.</li>
                        <li>The continuation monthly service charge stated in the Company's service cancellation policy is <strong>₹{{ number_format($continuationCharge ?? 85) }} plus applicable GST</strong>.</li>
                        <li>A fresh agreement may be executed if the Company and customer mutually agree to a new {{ $validityYears ?? 5 }}-year commitment.</li>
                    </ol>
                    <p class="text-xs text-slate-500">
                        Renewal or continuation is subject to service availability and confirmation by the Company.
                    </p>
                </section>

                <!-- 6. Delivery Terms -->
                <section class="space-y-3 pb-8 border-b border-amber-100">
                    <h2 class="text-base sm:text-lg font-serif font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-full bg-[#D97706]/15 text-[#881337] flex items-center justify-center font-mono text-xs">6</span>
                        <span>Delivery Terms</span>
                    </h2>
                    <p>
                        The Company will arrange delivery of the subscribed Ganga Jal service to the delivery address provided by the customer. Customers must ensure that:
                    </p>
                    <ul class="list-disc pl-5 space-y-1 text-xs text-slate-700">
                        <li>The delivery address is complete and accurate.</li>
                        <li>The registered mobile number remains active to receive courier coordination calls and OTPs.</li>
                        <li>An authorized recipient is available to receive the delivery where necessary.</li>
                        <li>Any change in delivery address is communicated to customer support at least <strong>15 days in advance</strong>.</li>
                    </ul>
                    <p>
                        Delivery schedules may occasionally be affected by operational limitations, transportation disruptions, extreme weather conditions, natural calamities, governmental restrictions, or force majeure events beyond the Company's reasonable control. The Company will make reasonable efforts to communicate significant delivery interruptions and resume service promptly when conditions permit.
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
                        <li>Provide correct registration, billing, and delivery details.</li>
                        <li>Pay the applicable commitment fee (₹{{ number_format($membershipFee ?? 797) }}) and monthly charges (₹{{ number_format($monthlyAutoDebit ?? $shippingRate ?? 149) }}) within the prescribed time.</li>
                        <li>Maintain an active contact number and provide updated contact information.</li>
                        <li>Notify the Company at least 15 days before changing the delivery address.</li>
                        <li>Cooperate with reasonable delivery arrangements.</li>
                        <li>Use the supplied product responsibly and in accordance with its packaging and handling instructions.</li>
                        <li>Avoid providing false information or misusing the Company's website or services.</li>
                        <li>Communicate service-related complaints through the Company's official support channel (<a href="mailto:sales@haridwarbliss.com" class="text-[#D97706] font-bold">sales@haridwarbliss.com</a>).</li>
                    </ol>
                </section>

                <!-- 8. Payment Terms and Failed Transactions -->
                <section class="space-y-3 pb-8 border-b border-amber-100">
                    <h2 class="text-base sm:text-lg font-serif font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-full bg-[#D97706]/15 text-[#881337] flex items-center justify-center font-mono text-xs">8</span>
                        <span>Payment Terms and Failed Transactions</span>
                    </h2>
                    <p>
                        Payments must be made through authorized payment methods made available by the Company (UPI, Net Banking, Cards, Payment Gateway). A payment is considered successful only when the transaction is confirmed by the payment gateway and the Company receives the relevant payment confirmation.
                    </p>
                    <p>
                        In case of a failed, pending, duplicated, or incorrectly processed transaction, customers should contact the Company with transaction details (UTR/transaction ID). Where an amount has been debited but enrolment has not been confirmed, the Company will coordinate with the relevant payment service provider to reconcile the transaction. Any refund arising from a duplicate or erroneous payment will be handled after verification in accordance with payment gateway timelines.
                    </p>
                </section>

                <!-- 9. Non-Refundable Commitment Fee -->
                <section class="space-y-3 pb-8 border-b border-amber-100">
                    <h2 class="text-base sm:text-lg font-serif font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-full bg-[#D97706]/15 text-[#881337] flex items-center justify-center font-mono text-xs">9</span>
                        <span>Non-Refundable Commitment Fee</span>
                    </h2>
                    <p>
                        The one-time commitment fee of <strong>₹{{ number_format($membershipFee ?? 797) }}</strong> is associated with the {{ $validityYears ?? 5 }}-year Ganga Jal Seva arrangement. If a customer voluntarily discontinues the service before completion of the {{ ($validityYears ?? 5) * 12 }}-month commitment period, the commitment fee will not ordinarily be refunded.
                    </p>
                    <p class="text-xs text-slate-600">
                        However, nothing in this clause is intended to exclude any refund, remedy, or statutory consumer right that cannot lawfully be excluded under applicable Indian consumer protection laws. The non-refundable nature of the commitment fee does not automatically make separately paid advance monthly charges non-refundable in every situation.
                    </p>
                </section>

                <!-- 10. Cancellation and Discontinuation -->
                <section class="space-y-3 pb-8 border-b border-amber-100">
                    <h2 class="text-base sm:text-lg font-serif font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-full bg-[#D97706]/15 text-[#881337] flex items-center justify-center font-mono text-xs">10</span>
                        <span>Cancellation and Discontinuation</span>
                    </h2>
                    <p>
                        Customers wishing to discontinue their monthly service may submit a written cancellation request or email to:
                        <a href="mailto:sales@haridwarbliss.com" class="text-[#D97706] font-bold font-mono">sales@haridwarbliss.com</a>.
                        The request should contain the customer's registered name, registered mobile number, and sufficient information to identify the service subscription.
                    </p>
                    <div class="space-y-2 text-xs text-slate-700">
                        <p>The following conditions apply:</p>
                        <ol class="list-decimal pl-5 space-y-1">
                            <li>Customers may request discontinuation in accordance with the cancellation policy.</li>
                            <li>The one-time commitment fee (₹{{ number_format($membershipFee ?? 797) }}) is ordinarily non-refundable.</li>
                            <li>Monthly charges already paid in advance will be adjusted in accordance with applicable billing and cancellation terms.</li>
                            <li>Any outstanding monthly payment must be settled prior to account closure.</li>
                            <li>Cancellation does not remove payment obligations that became due before the effective cancellation date.</li>
                            <li>Statutory consumer rights and remedies remain unaffected.</li>
                        </ol>
                    </div>
                </section>

                <!-- 11. Price Changes and Service Modifications -->
                <section class="space-y-3 pb-8 border-b border-amber-100">
                    <h2 class="text-base sm:text-lg font-serif font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-full bg-[#D97706]/15 text-[#881337] flex items-center justify-center font-mono text-xs">11</span>
                        <span>Price Changes and Service Modifications</span>
                    </h2>
                    <p>
                        The Company may revise service prices where reasonably necessary due to changes in operational expenses, supply costs, transportation expenses, statutory levies, or relevant business circumstances.
                    </p>
                    <p>
                        Where a price revision affects an existing service agreement, the Company will provide at least <strong>30 days' prior written notice</strong>. If the customer does not agree with a proposed price revision, the customer may request termination of the service. Changes in statutory taxes will be applied automatically as required by law.
                    </p>
                </section>

                <!-- 12. Product Quality, Packaging and Handling -->
                <section class="space-y-3 pb-8 border-b border-amber-100">
                    <h2 class="text-base sm:text-lg font-serif font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-full bg-[#D97706]/15 text-[#881337] flex items-center justify-center font-mono text-xs">12</span>
                        <span>Product Quality, Packaging and Handling</span>
                    </h2>
                    <p>
                        The Company takes meticulous care to supply pure Haridwar Ganga Jal in hygienic, sealed packaging adhering to appropriate handling and cold delivery practices.
                    </p>
                    <p>
                        Customers must inspect the packaging upon receipt. If a bottle is found damaged, leaking, improperly sealed, or otherwise unsuitable upon delivery, the customer should notify the Company promptly (within 48 hours) at <a href="mailto:sales@haridwarbliss.com" class="text-[#D97706] font-bold">sales@haridwarbliss.com</a> with supporting photographs. The Company will examine the matter and arrange a replacement or appropriate resolution.
                    </p>
                </section>

                <!-- 13. Temporary Service Interruptions and Force Majeure -->
                <section class="space-y-3 pb-8 border-b border-amber-100">
                    <h2 class="text-base sm:text-lg font-serif font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-full bg-[#D97706]/15 text-[#881337] flex items-center justify-center font-mono text-xs">13</span>
                        <span>Temporary Service Interruptions and Force Majeure</span>
                    </h2>
                    <p>
                        The Company shall not be considered in breach of its obligations to the extent that performance is prevented or materially delayed by circumstances beyond its reasonable control, including natural calamities, severe weather, governmental orders, transport and logistics disruptions, strikes, or public emergencies. The Company will make reasonable efforts to minimise disruptions and resume service as soon as practicable.
                    </p>
                </section>

                <!-- 14. Website Use and Prohibited Activities -->
                <section class="space-y-3 pb-8 border-b border-amber-100">
                    <h2 class="text-base sm:text-lg font-serif font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-full bg-[#D97706]/15 text-[#881337] flex items-center justify-center font-mono text-xs">14</span>
                        <span>Website Use and Prohibited Activities</span>
                    </h2>
                    <p>Customers agree to use the website only for lawful purposes. You must not:</p>
                    <ol class="list-decimal pl-5 space-y-1 text-xs text-slate-700">
                        <li>Use the website for fraudulent, deceptive, or misleading activities.</li>
                        <li>Attempt to gain unauthorised access to the website, server accounts, or connected databases.</li>
                        <li>Interfere with the website's security, architecture, or performance.</li>
                        <li>Upload malicious software, viruses, or harmful code.</li>
                        <li>Copy, reproduce, scrape, or commercially exploit website content without prior written permission.</li>
                        <li>Submit false registration, payment, or delivery information.</li>
                        <li>Misuse the Company's name, brand marks, or religious credentials in a misleading manner.</li>
                    </ol>
                </section>

                <!-- 15. Intellectual Property -->
                <section class="space-y-3 pb-8 border-b border-amber-100">
                    <h2 class="text-base sm:text-lg font-serif font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-full bg-[#D97706]/15 text-[#881337] flex items-center justify-center font-mono text-xs">15</span>
                        <span>Intellectual Property</span>
                    </h2>
                    <p>
                        All original website content, including text, graphics, logos, brand emblems, packaging designs, photographs, audio-visual clips, page layouts, and software owned or licensed by the Company, is protected under applicable Indian and international intellectual property laws. No person may reproduce, distribute, modify, or commercially exploit such materials without prior written authorization.
                    </p>
                </section>

                <!-- 16. Third-Party Services and External Links -->
                <section class="space-y-3 pb-8 border-b border-amber-100">
                    <h2 class="text-base sm:text-lg font-serif font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-full bg-[#D97706]/15 text-[#881337] flex items-center justify-center font-mono text-xs">16</span>
                        <span>Third-Party Services and External Links</span>
                    </h2>
                    <p>
                        The website may integrate third-party services for payment processing (e.g., Razorpay), logistics/courier dispatch (e.g., Shiprocket), SMS alerts, and video streaming. Customers should review the relevant third-party terms and privacy policies before using those services.
                    </p>
                </section>

                <!-- 17. Limitation of Liability -->
                <section class="space-y-3 pb-8 border-b border-amber-100">
                    <h2 class="text-base sm:text-lg font-serif font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-full bg-[#D97706]/15 text-[#881337] flex items-center justify-center font-mono text-xs">17</span>
                        <span>Limitation of Liability</span>
                    </h2>
                    <p>
                        The Company exercises reasonable commercial care in providing its sacred services. To the extent permitted by applicable law, the Company shall not be liable for indirect, incidental, or consequential losses arising from circumstances beyond its reasonable control. Nothing in these Terms shall exclude or limit liability for fraud, gross negligence where liability cannot lawfully be excluded, or non-waivable statutory consumer remedies under Indian law.
                    </p>
                </section>

                <!-- 18. Privacy and Protection of Personal Information -->
                <section class="space-y-3 pb-8 border-b border-amber-100">
                    <h2 class="text-base sm:text-lg font-serif font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-full bg-[#D97706]/15 text-[#881337] flex items-center justify-center font-mono text-xs">18</span>
                        <span>Privacy and Protection of Personal Information</span>
                    </h2>
                    <p>
                        The Company respects customer privacy and handles personal data in accordance with our <a href="{{ route('privacy') }}" class="text-[#D97706] font-bold hover:underline">Privacy Policy</a> and the Digital Personal Data Protection Act (DPDPA) and prevailing Indian data protection regulations. Personal information is collected strictly for processing subscriptions, coordinating shipments, issuing tax invoices, and delivering customer support.
                    </p>
                </section>

                <!-- 19. Grievances and Customer Support -->
                <section class="space-y-3 pb-8 border-b border-amber-100">
                    <h2 class="text-base sm:text-lg font-serif font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-full bg-[#D97706]/15 text-[#881337] flex items-center justify-center font-mono text-xs">19</span>
                        <span>Grievances and Customer Support</span>
                    </h2>
                    <p>Customers may reach out to customer support regarding registration, delivery status, payment confirmations, product packaging, cancellation requests, or privacy queries.</p>
                    <div class="p-4 bg-amber-50/70 border border-amber-200 rounded-2xl space-y-1">
                        <span class="text-xs font-bold text-[#881337] block">Official Grievance &amp; Customer Support Contact:</span>
                        <div class="text-xs text-slate-800">
                            Email: <a href="mailto:sales@haridwarbliss.com" class="text-[#D97706] font-bold hover:underline font-mono">sales@haridwarbliss.com</a>
                        </div>
                        <div class="text-xs text-slate-800">
                            Support Phone: <a href="tel:+917042482555" class="text-[#D97706] font-bold hover:underline font-mono">+91 7042482555</a>
                        </div>
                    </div>
                </section>

                <!-- 20. Amendments to These Terms -->
                <section class="space-y-3 pb-8 border-b border-amber-100">
                    <h2 class="text-base sm:text-lg font-serif font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-full bg-[#D97706]/15 text-[#881337] flex items-center justify-center font-mono text-xs">20</span>
                        <span>Amendments to These Terms</span>
                    </h2>
                    <p>
                        The Company may revise these Terms &amp; Conditions periodically to reflect changes in our services, statutory mandates, or operating practices. Updated terms will be published on this page with the revised Effective Date. Customers are encouraged to review these Terms periodically.
                    </p>
                </section>

                <!-- 21. Governing Law and Jurisdiction -->
                <section class="space-y-3 pb-8 border-b border-amber-100">
                    <h2 class="text-base sm:text-lg font-serif font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-full bg-[#D97706]/15 text-[#881337] flex items-center justify-center font-mono text-xs">21</span>
                        <span>Governing Law and Jurisdiction</span>
                    </h2>
                    <p>
                        These Terms &amp; Conditions shall be governed by and construed in accordance with the laws of India. Disputes arising in connection with the five-year Ganga Jal Seva service agreement shall be subject to the jurisdiction provisions specified in the applicable agreement, identifying the <strong>Ghaziabad District Court, Uttar Pradesh</strong>. Nothing in this clause prevents a consumer from approaching a competent consumer commission under the Consumer Protection Act.
                    </p>
                </section>

                <!-- 22. Severability -->
                <section class="space-y-3 pb-8 border-b border-amber-100">
                    <h2 class="text-base sm:text-lg font-serif font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-full bg-[#D97706]/15 text-[#881337] flex items-center justify-center font-mono text-xs">22</span>
                        <span>Severability</span>
                    </h2>
                    <p>
                        If any provision of these Terms is deemed unlawful, void, or unenforceable by a court of competent jurisdiction, that provision shall be deemed severable from these Terms and shall not affect the validity and enforceability of the remaining provisions.
                    </p>
                </section>

                <!-- 23. Entire Agreement -->
                <section class="space-y-3 pb-8 border-b border-amber-100">
                    <h2 class="text-base sm:text-lg font-serif font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-full bg-[#D97706]/15 text-[#881337] flex items-center justify-center font-mono text-xs">23</span>
                        <span>Entire Agreement</span>
                    </h2>
                    <p>
                        These Terms &amp; Conditions, together with the applicable service agreement, cancellation policy, payment terms, and Privacy Policy, constitute the entire agreement governing customer engagement with Haridwar Bliss services.
                    </p>
                </section>

                <!-- 24. Contact Information -->
                <section class="space-y-3">
                    <h2 class="text-base sm:text-lg font-serif font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-full bg-[#D97706]/15 text-[#881337] flex items-center justify-center font-mono text-xs">24</span>
                        <span>Contact Information</span>
                    </h2>
                    <p>For any questions, concerns, clarifications, or assistance regarding these Terms &amp; Conditions, please contact:</p>
                    <div class="p-5 bg-[#25050A] text-white rounded-2xl border border-amber-300/30 space-y-2 text-xs">
                        <div class="font-bold text-amber-200 text-sm">M/s HARIDWAR BLISS FOOD &amp; BEVERAGES PVT. LTD.</div>
                        <div class="text-slate-300">CIN: U11040UT2025PTC019429</div>
                        <div>Email: <a href="mailto:sales@haridwarbliss.com" class="text-amber-300 font-bold hover:underline font-mono">sales@haridwarbliss.com</a></div>
                        <div>Website: <a href="{{ url('/') }}" class="text-amber-300 hover:underline">{{ url('/') }}</a></div>
                        <div class="text-slate-300 pt-1">G-46, Shivalik Nagar, BHEL, Ranipur More, Haridwar, Uttarakhand, India - 249403</div>
                    </div>
                    <p class="text-xs text-slate-500 pt-2 italic text-center">
                        Thank you for choosing our sacred Ganga Jal Seva services.
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

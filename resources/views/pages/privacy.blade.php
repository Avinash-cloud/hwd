<x-app-layout>
    <!-- Header Banner (Signature Maroon Hero Card on Solid Orange Band) -->
    <x-page-hero bandBg="bg-[#FFA028]"
                 badge="Data Protection & Devotee Privacy"
                 title="Privacy Policy"
                 subtitle="M/s HARIDWAR BLISS FOOD & BEVERAGES PVT. LTD. · Commitment to Responsible Data Handling">
        <x-slot:extra>
            <span class="inline-block text-[11px] text-amber-200/90 font-mono px-3 py-1 rounded-full bg-[#6B0E29] border border-amber-300/30">
                Effective Date: 17 November 2026
            </span>
        </x-slot:extra>
    </x-page-hero>

    <!-- Main Content Container -->
    <div class="bg-[#FFFDF7] py-12 sm:py-16 text-slate-800">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">

            <!-- Preamble Card -->
            <div class="bg-white rounded-3xl border-2 border-amber-200/80 p-6 sm:p-10 shadow-xl shadow-amber-950/5 space-y-4 text-xs sm:text-sm text-slate-700 leading-relaxed">
                <p class="font-medium text-slate-900 leading-normal">
                    <strong>M/s HARIDWAR BLISS FOOD &amp; BEVERAGES PVT. LTD.</strong> ("Company", "We", "Us" or "Our") respects the privacy of individuals who visit our website, contact us, register for our services or purchase our Ganga Jal Seva plans.
                </p>
                <p>
                    This Privacy Policy explains how we collect, use, process, store, protect and disclose personal information in connection with our website and services.
                </p>
                <p>
                    We are committed to handling personal information responsibly, transparently and in accordance with applicable Indian data protection and privacy laws.
                </p>
                <p class="text-xs text-slate-500">
                    By accessing our website or voluntarily providing your personal information, you acknowledge that you have read this Privacy Policy.
                </p>
            </div>

            <!-- Highlights Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
                <div class="p-5 bg-white rounded-2xl border border-amber-200 shadow-2xs space-y-2">
                    <span class="text-2xl">🔒</span>
                    <h3 class="font-bold text-slate-900 text-sm">No Sale of Personal Data</h3>
                    <p class="text-[11px] text-slate-600 leading-relaxed">We do not sell customers' personal information as a business activity.</p>
                </div>
                <div class="p-5 bg-white rounded-2xl border border-amber-200 shadow-2xs space-y-2">
                    <span class="text-2xl">📦</span>
                    <h3 class="font-bold text-slate-900 text-sm">Purpose-Driven Handling</h3>
                    <p class="text-[11px] text-slate-600 leading-relaxed">Information is collected solely to operate our website, fulfil Ganga Jal Seva deliveries, and meet legal obligations.</p>
                </div>
                <div class="p-5 bg-white rounded-2xl border border-amber-200 shadow-2xs space-y-2">
                    <span class="text-2xl">📜</span>
                    <h3 class="font-bold text-slate-900 text-sm">Devotee Privacy Rights</h3>
                    <p class="text-[11px] text-slate-600 leading-relaxed">Rights to access, correction, completion, erasure, consent withdrawal, grievance redressal, and nomination under applicable law.</p>
                </div>
            </div>

            <!-- Detailed 19 Clauses Container -->
            <div class="bg-white rounded-3xl border border-amber-200 p-6 sm:p-12 shadow-xl shadow-amber-950/5 space-y-10 text-xs sm:text-sm text-slate-700 leading-relaxed">

                <!-- 1. About Us -->
                <section class="space-y-3 pb-8 border-b border-amber-100">
                    <h2 class="text-base sm:text-lg font-serif font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-full bg-[#D97706]/15 text-[#881337] flex items-center justify-center font-mono text-xs">1</span>
                        <span>About Us</span>
                    </h2>
                    <p>
                        <strong>M/s HARIDWAR BLISS FOOD &amp; BEVERAGES PVT. LTD.</strong> provides Ganga Jal Seva services, including the supply and monthly delivery of Ganga Jal sourced from Haridwar.
                    </p>
                    <p>This Privacy Policy applies to personal information collected through:</p>
                    <ul class="list-disc pl-5 space-y-1 text-slate-700">
                        <li>Our official website (<a href="{{ url('/') }}" class="text-[#D97706] font-bold hover:underline">{{ url('/') }}</a>).</li>
                        <li>Online registration and service enrolment.</li>
                        <li>Order and payment processes.</li>
                        <li>Customer support enquiries.</li>
                        <li>Email and other communication channels.</li>
                        <li>Service-related interactions.</li>
                    </ul>
                    <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200 text-xs text-slate-700 space-y-1">
                        <span class="font-bold text-slate-900 block">For privacy-related questions, requests or complaints, contact:</span>
                        <div>Email: <a href="mailto:sales@haridwarbliss.com" class="text-[#D97706] font-bold hover:underline font-mono">sales@haridwarbliss.com</a></div>
                    </div>
                </section>

                <!-- 2. Personal Information We Collect -->
                <section class="space-y-4 pb-8 border-b border-amber-100">
                    <h2 class="text-base sm:text-lg font-serif font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-full bg-[#D97706]/15 text-[#881337] flex items-center justify-center font-mono text-xs">2</span>
                        <span>Personal Information We Collect</span>
                    </h2>
                    <p>
                        We collect only information that is reasonably relevant to operating our website, providing our services, communicating with customers and fulfilling legal obligations. Depending on how you interact with us, the information collected may include the following:
                    </p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="p-4 bg-amber-50/50 rounded-2xl border border-amber-200 space-y-1.5">
                            <h3 class="font-bold text-slate-900 text-xs sm:text-sm">A. Personal Identification Information</h3>
                            <p class="text-[11px] text-slate-600">When you register for a service or make an enquiry, we may collect:</p>
                            <ul class="list-disc pl-4 space-y-0.5 text-xs text-slate-600">
                                <li>Full name.</li>
                                <li>Contact details.</li>
                                <li>Email address.</li>
                                <li>Mobile number.</li>
                                <li>Information voluntarily supplied during customer communication.</li>
                            </ul>
                            <p class="text-[10px] text-slate-500 pt-1">We will not request sensitive identification documents unless there is a lawful and necessary reason to do so.</p>
                        </div>

                        <div class="p-4 bg-amber-50/50 rounded-2xl border border-amber-200 space-y-1.5">
                            <h3 class="font-bold text-slate-900 text-xs sm:text-sm">B. Delivery and Address Information</h3>
                            <p class="text-[11px] text-slate-600">To provide monthly Ganga Jal deliveries, we may collect:</p>
                            <ul class="list-disc pl-4 space-y-0.5 text-xs text-slate-600">
                                <li>House or building number.</li>
                                <li>Street and locality.</li>
                                <li>City and district.</li>
                                <li>State.</li>
                                <li>Postal PIN code.</li>
                                <li>Other delivery instructions supplied by the customer.</li>
                            </ul>
                            <p class="text-[10px] text-slate-500 pt-1">This information is used to identify the delivery location and coordinate the service.</p>
                        </div>

                        <div class="p-4 bg-amber-50/50 rounded-2xl border border-amber-200 space-y-1.5">
                            <h3 class="font-bold text-slate-900 text-xs sm:text-sm">C. Order and Subscription Information</h3>
                            <p class="text-[11px] text-slate-600">We may maintain records relating to:</p>
                            <ul class="list-disc pl-4 space-y-0.5 text-xs text-slate-600">
                                <li>Service plan selected.</li>
                                <li>Registration and enrolment date.</li>
                                <li>Service commencement date.</li>
                                <li>Subscription duration.</li>
                                <li>Monthly billing information.</li>
                                <li>Delivery records.</li>
                                <li>Cancellation requests.</li>
                                <li>Customer preferences communicated to us.</li>
                                <li>Service-related complaints and resolutions.</li>
                            </ul>
                        </div>

                        <div class="p-4 bg-amber-50/50 rounded-2xl border border-amber-200 space-y-1.5">
                            <h3 class="font-bold text-slate-900 text-xs sm:text-sm">D. Payment and Transaction Information</h3>
                            <p class="text-[11px] text-slate-600">When you make a payment, we may collect or receive:</p>
                            <ul class="list-disc pl-4 space-y-0.5 text-xs text-slate-600">
                                <li>Transaction reference number.</li>
                                <li>Payment confirmation.</li>
                                <li>Payment amount.</li>
                                <li>Payment date and time.</li>
                                <li>Invoice information.</li>
                                <li>Payment status.</li>
                                <li>Other information necessary to reconcile a transaction.</li>
                            </ul>
                            <p class="text-[10px] text-slate-500 pt-1">Where online payments are processed through a third-party payment service provider, payment information may also be collected and processed by that provider under its applicable terms and privacy policy. Customers should refer to the relevant payment provider's privacy policy for details of its independent processing practices.</p>
                        </div>

                        <div class="p-4 bg-amber-50/50 rounded-2xl border border-amber-200 space-y-1.5">
                            <h3 class="font-bold text-slate-900 text-xs sm:text-sm">E. Communication Information</h3>
                            <p class="text-[11px] text-slate-600">If you contact us, we may retain:</p>
                            <ul class="list-disc pl-4 space-y-0.5 text-xs text-slate-600">
                                <li>Emails and correspondence.</li>
                                <li>Customer support requests.</li>
                                <li>Complaint details.</li>
                                <li>Enquiry information.</li>
                                <li>Attachments or documents voluntarily submitted.</li>
                                <li>Records of actions taken to resolve a request.</li>
                            </ul>
                            <p class="text-[10px] text-slate-500 pt-1">This helps us maintain customer service records and respond to future enquiries.</p>
                        </div>

                        <div class="p-4 bg-amber-50/50 rounded-2xl border border-amber-200 space-y-1.5">
                            <h3 class="font-bold text-slate-900 text-xs sm:text-sm">F. Technical and Website Usage Information</h3>
                            <p class="text-[11px] text-slate-600">When you visit our website, certain technical information may be collected automatically by the website infrastructure or service providers. Depending on the website's configuration, this may include:</p>
                            <ul class="list-disc pl-4 space-y-0.5 text-xs text-slate-600">
                                <li>Internet Protocol (IP) address.</li>
                                <li>Browser type and version.</li>
                                <li>Device information.</li>
                                <li>Operating system.</li>
                                <li>Pages visited.</li>
                                <li>Date and time of access.</li>
                                <li>Basic website interaction information.</li>
                                <li>Technical error and diagnostic information.</li>
                            </ul>
                            <p class="text-[10px] text-slate-500 pt-1">Such information may be used to maintain website functionality, security and performance.</p>
                        </div>
                    </div>
                </section>

                <!-- 3. How We Use Your Personal Information -->
                <section class="space-y-4 pb-8 border-b border-amber-100">
                    <h2 class="text-base sm:text-lg font-serif font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-full bg-[#D97706]/15 text-[#881337] flex items-center justify-center font-mono text-xs">3</span>
                        <span>How We Use Your Personal Information</span>
                    </h2>
                    <p>We use personal information for legitimate and disclosed business purposes, including:</p>

                    <div class="space-y-3 text-xs">
                        <div class="p-3.5 bg-amber-50/40 rounded-xl border border-amber-200/80 space-y-1">
                            <h3 class="font-bold text-slate-900">A. Service Registration and Management</h3>
                            <ul class="list-disc pl-5 space-y-0.5 text-slate-700">
                                <li>Registering customers for Ganga Jal Seva.</li>
                                <li>Creating and maintaining service records.</li>
                                <li>Verifying registration details.</li>
                                <li>Managing five-year commitment plans.</li>
                                <li>Maintaining customer subscription information.</li>
                            </ul>
                        </div>

                        <div class="p-3.5 bg-amber-50/40 rounded-xl border border-amber-200/80 space-y-1">
                            <h3 class="font-bold text-slate-900">B. Order Processing and Delivery</h3>
                            <ul class="list-disc pl-5 space-y-0.5 text-slate-700">
                                <li>Confirming customer orders.</li>
                                <li>Identifying delivery addresses.</li>
                                <li>Coordinating monthly deliveries.</li>
                                <li>Communicating delivery-related information.</li>
                                <li>Managing delivery changes and service requests.</li>
                            </ul>
                        </div>

                        <div class="p-3.5 bg-amber-50/40 rounded-xl border border-amber-200/80 space-y-1">
                            <h3 class="font-bold text-slate-900">C. Payment and Accounting</h3>
                            <ul class="list-disc pl-5 space-y-0.5 text-slate-700">
                                <li>Confirming payments.</li>
                                <li>Processing applicable transactions.</li>
                                <li>Maintaining billing records.</li>
                                <li>Generating tax invoices.</li>
                                <li>Reconciling payment information.</li>
                                <li>Meeting accounting and tax requirements.</li>
                            </ul>
                        </div>

                        <div class="p-3.5 bg-amber-50/40 rounded-xl border border-amber-200/80 space-y-1">
                            <h3 class="font-bold text-slate-900">D. Customer Support</h3>
                            <ul class="list-disc pl-5 space-y-0.5 text-slate-700">
                                <li>Responding to customer enquiries.</li>
                                <li>Handling complaints.</li>
                                <li>Resolving payment-related issues.</li>
                                <li>Processing cancellation requests.</li>
                                <li>Communicating service interruptions.</li>
                                <li>Providing assistance with subscriptions and deliveries.</li>
                            </ul>
                        </div>

                        <div class="p-3.5 bg-amber-50/40 rounded-xl border border-amber-200/80 space-y-1">
                            <h3 class="font-bold text-slate-900">E. Service Communication</h3>
                            <p class="text-slate-600">We may use your contact information to communicate important information about:</p>
                            <ul class="list-disc pl-5 space-y-0.5 text-slate-700">
                                <li>Registration confirmation.</li>
                                <li>Payment acknowledgements.</li>
                                <li>Monthly service charges.</li>
                                <li>Delivery updates.</li>
                                <li>Changes to service arrangements.</li>
                                <li>Subscription expiry and renewal.</li>
                                <li>Policy updates.</li>
                                <li>Customer support responses.</li>
                            </ul>
                        </div>

                        <div class="p-3.5 bg-amber-50/40 rounded-xl border border-amber-200/80 space-y-1">
                            <h3 class="font-bold text-slate-900">F. Website Security and Improvement</h3>
                            <p class="text-slate-600">We may use technical information to:</p>
                            <ul class="list-disc pl-5 space-y-0.5 text-slate-700">
                                <li>Maintain website functionality.</li>
                                <li>Identify technical errors.</li>
                                <li>Prevent fraudulent activities.</li>
                                <li>Detect suspicious or unauthorised access.</li>
                                <li>Improve website performance.</li>
                                <li>Protect our systems and customers.</li>
                            </ul>
                        </div>

                        <div class="p-3.5 bg-amber-50/40 rounded-xl border border-amber-200/80 space-y-1">
                            <h3 class="font-bold text-slate-900">G. Legal and Regulatory Compliance</h3>
                            <p class="text-slate-700">We may process and retain information when necessary to comply with applicable laws, tax requirements, lawful government requests, accounting obligations and other legal responsibilities.</p>
                        </div>
                    </div>
                </section>

                <!-- 4. Legal Basis for Processing Personal Information -->
                <section class="space-y-3 pb-8 border-b border-amber-100">
                    <h2 class="text-base sm:text-lg font-serif font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-full bg-[#D97706]/15 text-[#881337] flex items-center justify-center font-mono text-xs">4</span>
                        <span>Legal Basis for Processing Personal Information</span>
                    </h2>
                    <p>We process personal information in accordance with applicable law.</p>
                    <p>Depending on the circumstances, processing may be based on:</p>
                    <ol class="list-decimal pl-5 space-y-1.5 text-slate-700">
                        <li>Your consent.</li>
                        <li>Your request to register for or receive our services.</li>
                        <li>The need to perform an agreement or take steps requested by you before entering into an agreement, where recognised by applicable law.</li>
                        <li>Compliance with legal and regulatory obligations.</li>
                        <li>Other lawful grounds permitted under applicable Indian law.</li>
                    </ol>
                    <p>Where consent is required, we will seek it through an appropriate process and explain the purpose for which the information is collected.</p>
                    <p class="text-xs text-slate-600">You may withdraw consent where processing is based on consent, subject to legal requirements and any consequences of withdrawal for the services you have requested.</p>
                </section>

                <!-- 5. Sharing and Disclosure of Personal Information -->
                <section class="space-y-3 pb-8 border-b border-amber-100">
                    <h2 class="text-base sm:text-lg font-serif font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-full bg-[#D97706]/15 text-[#881337] flex items-center justify-center font-mono text-xs">5</span>
                        <span>Sharing and Disclosure of Personal Information</span>
                    </h2>
                    <p class="font-semibold text-slate-900">We do not sell customers' personal information as a business activity.</p>
                    <p>We may share relevant information with third parties only where reasonably necessary for providing services, operating our website, fulfilling legal obligations or protecting legitimate interests.</p>
                    <p>Such recipients may include:</p>
                    <div class="space-y-2 text-xs text-slate-700">
                        <div>
                            <h3 class="font-bold text-slate-900">A. Delivery and Logistics Partners</h3>
                            <p>Relevant customer details, particularly names, mobile numbers and delivery addresses, may be shared with delivery personnel or logistics providers to complete deliveries.</p>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-900">B. Payment Service Providers</h3>
                            <p>Transaction-related information may be shared with payment processors, banks or other relevant financial service providers to process, verify and reconcile payments.</p>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-900">C. Website Hosting and Technology Providers</h3>
                            <p>Technical information may be processed by hosting providers, infrastructure providers and other technology service providers to operate, maintain and secure the website.</p>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-900">D. Professional and Business Service Providers</h3>
                            <p>Where necessary, information may be shared with authorised accountants, auditors, legal advisers or other professional service providers.</p>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-900">E. Government and Regulatory Authorities</h3>
                            <p>We may disclose personal information where required by law, court orders, legal proceedings, government requests or applicable regulatory requirements.</p>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-900">F. Business Transfers</h3>
                            <p>In the event of a merger, restructuring, acquisition or transfer of business operations, relevant information may be transferred as part of the transaction, subject to applicable legal requirements and appropriate safeguards.</p>
                        </div>
                    </div>
                    <p class="text-xs text-slate-600">We expect relevant service providers to handle personal information responsibly and only for authorised purposes.</p>
                </section>

                <!-- 6. Cookies and Similar Technologies -->
                <section class="space-y-3 pb-8 border-b border-amber-100">
                    <h2 class="text-base sm:text-lg font-serif font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-full bg-[#D97706]/15 text-[#881337] flex items-center justify-center font-mono text-xs">6</span>
                        <span>Cookies and Similar Technologies</span>
                    </h2>
                    <p>Our website or its service providers may use cookies and similar technologies to support website operations and improve the browsing experience.</p>
                    <p>Cookies are small data files that may be stored on your device when you visit a website. Depending on their configuration, cookies may be used for:</p>
                    <ul class="list-disc pl-5 space-y-1 text-slate-700 text-xs">
                        <li>Essential website functionality.</li>
                        <li>Maintaining user preferences.</li>
                        <li>Website performance monitoring.</li>
                        <li>Understanding website usage.</li>
                        <li>Security and fraud prevention.</li>
                    </ul>
                    <p>You may manage or disable cookies through your browser settings.</p>
                    <p>Please note that disabling certain cookies may affect the functionality or performance of some website features.</p>
                    <p class="text-xs text-slate-600">Where required by applicable law, we will obtain the necessary consent before using non-essential cookies or similar technologies.</p>
                </section>

                <!-- 7. Data Retention -->
                <section class="space-y-3 pb-8 border-b border-amber-100">
                    <h2 class="text-base sm:text-lg font-serif font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-full bg-[#D97706]/15 text-[#881337] flex items-center justify-center font-mono text-xs">7</span>
                        <span>Data Retention</span>
                    </h2>
                    <p>We retain personal information only for as long as reasonably necessary to fulfil the purposes for which it was collected, unless a longer retention period is required or permitted by law.</p>
                    <p>Retention periods may depend on the nature of the information and the applicable legal requirements. Examples include:</p>
                    <ul class="list-disc pl-5 space-y-1 text-slate-700 text-xs">
                        <li>Customer registration records may be retained during the active service relationship.</li>
                        <li>Subscription and service agreement records may be retained to manage the 60-month commitment and related matters.</li>
                        <li>Transaction and invoice records may be retained to comply with accounting and tax requirements.</li>
                        <li>Customer correspondence may be retained to address complaints and maintain service history.</li>
                        <li>Technical logs may be retained for appropriate operational and security purposes.</li>
                    </ul>
                    <p class="text-xs text-slate-600">
                        When information is no longer required, we will take reasonable steps to delete it or otherwise dispose of it securely, subject to applicable legal retention requirements.
                    </p>
                </section>

                <!-- 8. Data Security -->
                <section class="space-y-3 pb-8 border-b border-amber-100">
                    <h2 class="text-base sm:text-lg font-serif font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-full bg-[#D97706]/15 text-[#881337] flex items-center justify-center font-mono text-xs">8</span>
                        <span>Data Security</span>
                    </h2>
                    <p>We take reasonable technical and organisational measures to protect personal information against unauthorised access, disclosure, alteration, loss, misuse or destruction.</p>
                    <p>Depending on the systems and services used, such measures may include:</p>
                    <ul class="list-disc pl-5 space-y-1 text-slate-700 text-xs">
                        <li>Appropriate access restrictions.</li>
                        <li>Secure website and hosting configurations.</li>
                        <li>Restricted access to customer information.</li>
                        <li>Administrative and operational safeguards.</li>
                        <li>Reasonable monitoring of security-related activities.</li>
                        <li>Secure handling of business and transaction records.</li>
                    </ul>
                    <p>However, no website, electronic transmission or storage system can be guaranteed to be completely secure.</p>
                    <p class="text-xs text-slate-600">Customers should also protect their own devices, email accounts and communication channels and avoid sharing confidential information with unauthorised persons.</p>
                </section>

                <!-- 9. Your Privacy Rights -->
                <section class="space-y-3 pb-8 border-b border-amber-100">
                    <h2 class="text-base sm:text-lg font-serif font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-full bg-[#D97706]/15 text-[#881337] flex items-center justify-center font-mono text-xs">9</span>
                        <span>Your Privacy Rights</span>
                    </h2>
                    <p>Subject to applicable law, you may have rights relating to your personal information, including:</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                            <h3 class="font-bold text-slate-900">A. Right to Access Information</h3>
                            <p class="mt-0.5 text-slate-600">You may request information about the personal data we process about you and the purposes for which it is processed, as provided under applicable law.</p>
                        </div>
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                            <h3 class="font-bold text-slate-900">B. Right to Correction</h3>
                            <p class="mt-0.5 text-slate-600">You may request correction of inaccurate or misleading personal information.</p>
                        </div>
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                            <h3 class="font-bold text-slate-900">C. Right to Completion</h3>
                            <p class="mt-0.5 text-slate-600">Where applicable, you may request completion of incomplete personal information maintained by us.</p>
                        </div>
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                            <h3 class="font-bold text-slate-900">D. Right to Erasure</h3>
                            <p class="mt-0.5 text-slate-600">You may request erasure of personal information where it is no longer required for the purpose for which it was collected, subject to legal retention requirements and other lawful grounds for retaining it.</p>
                        </div>
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                            <h3 class="font-bold text-slate-900">E. Right to Withdraw Consent</h3>
                            <p class="mt-0.5 text-slate-600">Where we process personal information based on consent, you may withdraw that consent using the appropriate communication channel. Withdrawal of consent does not affect the lawfulness of processing carried out before the withdrawal.</p>
                        </div>
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                            <h3 class="font-bold text-slate-900">F. Right to Grievance Redressal</h3>
                            <p class="mt-0.5 text-slate-600">You may raise a complaint if you believe that your personal information has been processed in violation of applicable privacy requirements.</p>
                        </div>
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 sm:col-span-2">
                            <h3 class="font-bold text-slate-900">G. Right to Nominate</h3>
                            <p class="mt-0.5 text-slate-600">Where provided under applicable law, you may nominate another individual to exercise your rights in the event of your death or incapacity.</p>
                        </div>
                    </div>
                    <p class="text-xs text-slate-600 pt-1">
                        To exercise any applicable right, contact us at: <a href="mailto:sales@haridwarbliss.com" class="text-[#D97706] font-bold font-mono hover:underline">sales@haridwarbliss.com</a>. We may request reasonable information to verify your identity before acting on a request. We will respond within the time prescribed by applicable law.
                    </p>
                </section>

                <!-- 10. Withdrawal of Consent and Service Communication -->
                <section class="space-y-3 pb-8 border-b border-amber-100">
                    <h2 class="text-base sm:text-lg font-serif font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-full bg-[#D97706]/15 text-[#881337] flex items-center justify-center font-mono text-xs">10</span>
                        <span>Withdrawal of Consent and Service Communication</span>
                    </h2>
                    <p>Customers may withdraw consent for processing activities that rely on consent by contacting the Company.</p>
                    <p>However, withdrawal may affect our ability to provide certain services.</p>
                    <p>For example, if you withdraw permission for the use of your delivery address, we may be unable to arrange further deliveries.</p>
                    <p>Withdrawal of consent does not automatically cancel contractual payment obligations, erase records required by law or terminate other lawful processing activities.</p>
                    <p>Service-related communications necessary for managing an active subscription, fulfilling legal obligations or responding to customer requests may continue where legally permitted.</p>
                </section>

                <!-- 11. Marketing and Promotional Communication -->
                <section class="space-y-3 pb-8 border-b border-amber-100">
                    <h2 class="text-base sm:text-lg font-serif font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-full bg-[#D97706]/15 text-[#881337] flex items-center justify-center font-mono text-xs">11</span>
                        <span>Marketing and Promotional Communication</span>
                    </h2>
                    <p>We may occasionally communicate information about our services, offers, updates or other relevant business announcements.</p>
                    <p>Promotional communication will be undertaken in accordance with applicable consent and communication requirements.</p>
                    <p>
                        You may opt out of promotional communications by using an available unsubscribe mechanism or contacting us at our official email address (<a href="mailto:sales@haridwarbliss.com" class="text-[#D97706] font-bold hover:underline">sales@haridwarbliss.com</a>).
                    </p>
                    <p>Opting out of promotional communication will not prevent us from sending essential service, billing, delivery, security or legally required notifications.</p>
                </section>

                <!-- 12. Children's Privacy -->
                <section class="space-y-3 pb-8 border-b border-amber-100">
                    <h2 class="text-base sm:text-lg font-serif font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-full bg-[#D97706]/15 text-[#881337] flex items-center justify-center font-mono text-xs">12</span>
                        <span>Children's Privacy</span>
                    </h2>
                    <p>Our website and Ganga Jal Seva services are intended for use by individuals who can legally enter into the relevant service arrangement.</p>
                    <p>We do not knowingly seek to collect children's personal information for independent marketing or unrelated purposes.</p>
                    <p>Where personal information relating to a child is required to be processed, we will follow applicable legal requirements, including any required parental or lawful guardian consent.</p>
                    <p>If you believe that a child has provided personal information to us without the required authorisation, please contact us so that the matter can be reviewed.</p>
                </section>

                <!-- 13. Third-Party Websites and Services -->
                <section class="space-y-3 pb-8 border-b border-amber-100">
                    <h2 class="text-base sm:text-lg font-serif font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-full bg-[#D97706]/15 text-[#881337] flex items-center justify-center font-mono text-xs">13</span>
                        <span>Third-Party Websites and Services</span>
                    </h2>
                    <p>Our website may contain links to third-party websites or use external services for payment processing, website hosting, communication or other operational purposes.</p>
                    <p>We do not control the privacy practices, content or security arrangements of independently operated third-party websites.</p>
                    <p>When you access an external website or service, its own privacy policy and terms may apply.</p>
                    <p>We recommend reviewing the relevant policies before submitting personal information to a third party.</p>
                </section>

                <!-- 14. International Processing and Transfers -->
                <section class="space-y-3 pb-8 border-b border-amber-100">
                    <h2 class="text-base sm:text-lg font-serif font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-full bg-[#D97706]/15 text-[#881337] flex items-center justify-center font-mono text-xs">14</span>
                        <span>International Processing and Transfers</span>
                    </h2>
                    <p>Some technology or infrastructure service providers may process information using systems located outside your state or country.</p>
                    <p>Where personal information is transferred or processed outside India, the Company will follow applicable Indian legal requirements and any restrictions or safeguards imposed by the competent authorities.</p>
                    <p>We will not knowingly transfer personal information in violation of applicable law.</p>
                </section>

                <!-- 15. Personal Data Breach and Security Incidents -->
                <section class="space-y-3 pb-8 border-b border-amber-100">
                    <h2 class="text-base sm:text-lg font-serif font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-full bg-[#D97706]/15 text-[#881337] flex items-center justify-center font-mono text-xs">15</span>
                        <span>Personal Data Breach and Security Incidents</span>
                    </h2>
                    <p>If a personal data breach occurs, we will take reasonable steps to investigate, contain and address the incident.</p>
                    <p>Where notification is required under applicable law, we will provide the necessary information to the relevant individuals and authorities within the prescribed timeframe.</p>
                    <p>Our response may include securing affected systems, assessing the nature of the incident and taking steps to reduce potential harm.</p>
                </section>

                <!-- 16. Changes to This Privacy Policy -->
                <section class="space-y-3 pb-8 border-b border-amber-100">
                    <h2 class="text-base sm:text-lg font-serif font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-full bg-[#D97706]/15 text-[#881337] flex items-center justify-center font-mono text-xs">16</span>
                        <span>Changes to This Privacy Policy</span>
                    </h2>
                    <p>We may update this Privacy Policy from time to time to reflect:</p>
                    <ul class="list-disc pl-5 space-y-1 text-slate-700 text-xs">
                        <li>Changes in our business operations.</li>
                        <li>Changes in the services we provide.</li>
                        <li>Updates to website functionality.</li>
                        <li>Changes in technology or security practices.</li>
                        <li>Changes in applicable privacy and data protection laws.</li>
                    </ul>
                    <p>The updated version will be published on this page with its revised effective date.</p>
                    <p>We encourage customers to review this page periodically.</p>
                    <p>Where a change requires renewed consent or other specific notification under applicable law, we will follow the relevant requirements.</p>
                </section>

                <!-- 17. Grievance Redressal and Privacy Requests -->
                <section class="space-y-3 pb-8 border-b border-amber-100">
                    <h2 class="text-base sm:text-lg font-serif font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-full bg-[#D97706]/15 text-[#881337] flex items-center justify-center font-mono text-xs">17</span>
                        <span>Grievance Redressal and Privacy Requests</span>
                    </h2>
                    <p>
                        For privacy-related enquiries, complaints, access requests, correction requests, deletion requests or withdrawal of consent, please contact:
                    </p>
                    <div class="p-4 bg-amber-50/70 border border-amber-200 rounded-2xl space-y-1 text-xs">
                        <div class="font-bold text-[#881337]">Grievance Officer · Haridwar Bliss</div>
                        <div>M/s HARIDWAR BLISS FOOD &amp; BEVERAGES PVT. LTD.</div>
                        <div>Email: <a href="mailto:sales@haridwarbliss.com" class="text-[#D97706] font-bold hover:underline font-mono">sales@haridwarbliss.com</a></div>
                        <div>Phone: <a href="tel:+917042482555" class="text-[#D97706] font-bold hover:underline font-mono">+91 7042482555</a></div>
                    </div>
                    <p>Please include sufficient information to identify your request. Do not send passwords, complete payment card details or unnecessary sensitive documents through ordinary email.</p>
                    <p>The Company will review and respond to privacy-related requests in accordance with applicable law.</p>
                    <p>If you are dissatisfied with the response, you may exercise any further complaint or grievance rights available under applicable Indian data protection law.</p>
                </section>

                <!-- 18. Governing Law -->
                <section class="space-y-3 pb-8 border-b border-amber-100">
                    <h2 class="text-base sm:text-lg font-serif font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-full bg-[#D97706]/15 text-[#881337] flex items-center justify-center font-mono text-xs">18</span>
                        <span>Governing Law</span>
                    </h2>
                    <p>This Privacy Policy shall be governed by the applicable laws of India, including applicable data protection and privacy legislation.</p>
                    <p>Any dispute concerning this Privacy Policy shall be addressed in accordance with applicable Indian law and the jurisdiction of the competent authorities.</p>
                    <p>Nothing in this Privacy Policy shall restrict any statutory rights available to individuals.</p>
                </section>

                <!-- 19. Contact Us -->
                <section class="space-y-3">
                    <h2 class="text-base sm:text-lg font-serif font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-full bg-[#D97706]/15 text-[#881337] flex items-center justify-center font-mono text-xs">19</span>
                        <span>Contact Us</span>
                    </h2>
                    <p>If you have questions about this Privacy Policy, our data handling practices or the information we hold about you, please contact us.</p>
                    <div class="p-5 bg-[#25050A] text-white rounded-2xl border border-amber-300/30 space-y-2 text-xs">
                        <div class="font-bold text-amber-200 text-sm">Company: M/s HARIDWAR BLISS FOOD &amp; BEVERAGES PVT. LTD.</div>
                        <div class="text-slate-300">CIN: U11040UT2025PTC019429</div>
                        <div>Email: <a href="mailto:sales@haridwarbliss.com" class="text-amber-300 font-bold hover:underline font-mono">sales@haridwarbliss.com</a></div>
                        <div>Website: <a href="{{ url('/') }}" class="text-amber-300 hover:underline">{{ url('/') }}</a></div>
                        <div class="text-slate-300 pt-1">G-46, Shivalik Nagar, BHEL, Ranipur More, Haridwar, Uttarakhand, India - 249403</div>
                    </div>
                    <p class="text-xs text-slate-500 pt-2 italic text-center">
                        We appreciate your trust and are committed to handling your personal information responsibly.
                    </p>
                </section>

            </div>

            <!-- Return Link -->
            <div class="text-center pt-4">
                <a href="{{ route('home') }}"
                   class="inline-flex items-center gap-2 px-6 py-2.5 bg-[#D97706] hover:bg-[#F97316] text-white rounded-xl text-xs font-bold transition shadow-xs">
                    <span>← Return to Home</span>
                </a>
            </div>

        </div>
    </div>
</x-app-layout>

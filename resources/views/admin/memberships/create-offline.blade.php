<x-admin-layout title="Onboard Offline Members">
    <x-slot name="header">Onboard Offline Members</x-slot>

    <div class="max-w-5xl mx-auto space-y-8">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-xl font-semibold text-slate-900">Enroll Offline Pre-Website Members</h1>
                <p class="text-xs text-slate-500 mt-0.5">
                    Record members who already paid their commitment fee offline. They receive portal access without paying the joining fee again and can activate Razorpay auto-debit from their Dashboard.
                </p>
            </div>
            <a href="{{ route('admin.memberships.index') }}"
               class="px-3.5 py-2 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 rounded-lg text-xs font-semibold transition inline-flex items-center gap-1.5 self-start">
                <span>&larr; Back to Memberships</span>
            </a>
        </div>

        @if (session('error'))
            <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-xs text-rose-800 font-medium">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            <!-- Left 2 Cols: Single Offline Member Form -->
            <div class="lg:col-span-2 bg-white border border-slate-200 rounded-xl p-6 sm:p-8 shadow-xs">
                <div class="pb-4 mb-5 border-b border-slate-100">
                    <h2 class="text-base font-semibold text-slate-900">1. Add Single Offline Member</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Creates or updates the member account, default address, backdated membership record, and GST invoice.</p>
                </div>

                <form method="POST" action="{{ route('admin.memberships.offline.store') }}" class="space-y-6 text-xs">
                    @csrf

                    <!-- Devotee Details -->
                    <div class="space-y-4">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-amber-800">Devotee Account &amp; Contact</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Full Name *</label>
                                <input type="text" name="name" value="{{ old('name') }}" required placeholder="e.g. Rameshwar Tiwari"
                                       class="w-full px-3 py-2 border border-slate-300 rounded-lg text-slate-900 focus:border-amber-600 focus:ring-1 focus:ring-amber-600" />
                                @error('name') <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Email Address *</label>
                                <input type="email" name="email" value="{{ old('email') }}" required placeholder="devotee@example.com"
                                       class="w-full px-3 py-2 border border-slate-300 rounded-lg text-slate-900 focus:border-amber-600 focus:ring-1 focus:ring-amber-600" />
                                @error('email') <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Mobile Phone *</label>
                                <input type="text" name="phone" value="{{ old('phone') }}" required placeholder="e.g. 9876543210"
                                       class="w-full px-3 py-2 border border-slate-300 rounded-lg text-slate-900 focus:border-amber-600 focus:ring-1 focus:ring-amber-600" />
                                @error('phone') <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Delivery Address -->
                    <div class="pt-4 border-t border-slate-100 space-y-4">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-amber-800">Doorstep Delivery Address</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="sm:col-span-2">
                                <label class="block font-semibold text-slate-700 mb-1">Address Line 1 *</label>
                                <input type="text" name="address_line1" value="{{ old('address_line1') }}" required placeholder="House No, Street, Area"
                                       class="w-full px-3 py-2 border border-slate-300 rounded-lg text-slate-900 focus:border-amber-600 focus:ring-1 focus:ring-amber-600" />
                                @error('address_line1') <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block font-semibold text-slate-700 mb-1">Address Line 2 / Landmark</label>
                                <input type="text" name="address_line2" value="{{ old('address_line2') }}" placeholder="Apartment, Near Temple (Optional)"
                                       class="w-full px-3 py-2 border border-slate-300 rounded-lg text-slate-900 focus:border-amber-600 focus:ring-1 focus:ring-amber-600" />
                            </div>
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">City *</label>
                                <input type="text" name="city" value="{{ old('city') }}" required placeholder="Haridwar"
                                       class="w-full px-3 py-2 border border-slate-300 rounded-lg text-slate-900 focus:border-amber-600 focus:ring-1 focus:ring-amber-600" />
                                @error('city') <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">State *</label>
                                <input type="text" name="state" value="{{ old('state') }}" required placeholder="Uttarakhand"
                                       class="w-full px-3 py-2 border border-slate-300 rounded-lg text-slate-900 focus:border-amber-600 focus:ring-1 focus:ring-amber-600" />
                                @error('state') <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">PIN Code *</label>
                                <input type="text" name="postal_code" value="{{ old('postal_code') }}" required placeholder="249401"
                                       class="w-full px-3 py-2 border border-slate-300 rounded-lg font-mono text-slate-900 focus:border-amber-600 focus:ring-1 focus:ring-amber-600" />
                                @error('postal_code') <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Offline Payment & Tenure -->
                    <div class="pt-4 border-t border-slate-100 space-y-4">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-amber-800">Offline Payment &amp; Membership Dates</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Original Join Date *</label>
                                <input type="date" name="starts_at" value="{{ old('starts_at', now()->format('Y-m-d')) }}" required
                                       class="w-full px-3 py-2 border border-slate-300 rounded-lg font-mono text-slate-900 focus:border-amber-600 focus:ring-1 focus:ring-amber-600" />
                                @error('starts_at') <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Commitment Fee Paid (₹) *</label>
                                <input type="number" step="0.01" min="0" name="fee_paid" value="{{ old('fee_paid', $membershipFee) }}" required
                                       class="w-full px-3 py-2 border border-slate-300 rounded-lg font-mono text-slate-900 focus:border-amber-600 focus:ring-1 focus:ring-amber-600" />
                                @error('fee_paid') <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Validity (Years) *</label>
                                <input type="number" min="1" max="20" name="validity_years" value="{{ old('validity_years', $validityYears) }}" required
                                       class="w-full px-3 py-2 border border-slate-300 rounded-lg font-mono text-slate-900 focus:border-amber-600 focus:ring-1 focus:ring-amber-600" />
                                @error('validity_years') <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Offline Mode *</label>
                                <select name="payment_method" required
                                        class="w-full px-3 py-2 border border-slate-300 rounded-lg text-slate-900 focus:border-amber-600 focus:ring-1 focus:ring-amber-600">
                                    <option value="cash" @selected(old('payment_method', 'cash') === 'cash')>Cash</option>
                                    <option value="upi" @selected(old('payment_method') === 'upi')>Direct UPI / QR</option>
                                    <option value="bank_transfer" @selected(old('payment_method') === 'bank_transfer')>Bank Transfer (NEFT/IMPS)</option>
                                    <option value="cheque" @selected(old('payment_method') === 'cheque')>Cheque / DD</option>
                                    <option value="offline" @selected(old('payment_method') === 'offline')>Other Offline Receipt</option>
                                </select>
                            </div>
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Receipt / UTR Ref</label>
                                <input type="text" name="payment_id" value="{{ old('payment_id') }}" placeholder="e.g. RCPT-2025-104"
                                       class="w-full px-3 py-2 border border-slate-300 rounded-lg font-mono text-slate-900 focus:border-amber-600 focus:ring-1 focus:ring-amber-600" />
                            </div>
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Custom Invoice No.</label>
                                <input type="text" name="invoice_number" value="{{ old('invoice_number') }}" placeholder="Auto-generated if blank"
                                       class="w-full px-3 py-2 border border-slate-300 rounded-lg font-mono text-slate-900 focus:border-amber-600 focus:ring-1 focus:ring-amber-600" />
                                @error('invoice_number') <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <!-- Prepaid Months & Dispatch Options -->
                        <div class="p-4 bg-amber-50/60 border border-amber-200 rounded-xl space-y-3">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block font-semibold text-slate-800 mb-1">Offline Prepaid Months (Optional)</label>
                                    <input type="number" min="0" max="60" name="prepaid_months" value="{{ old('prepaid_months', 0) }}"
                                           class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg font-mono text-slate-900 focus:border-amber-600 focus:ring-1 focus:ring-amber-600" />
                                    <span class="text-[11px] text-slate-500 block mt-1">Leave 0 if only the commitment fee was paid offline.</span>
                                </div>
                                <div>
                                    <label class="block font-semibold text-slate-800 mb-1">Or Exact Prepaid Until Date</label>
                                    <input type="date" name="prepaid_until" value="{{ old('prepaid_until') }}"
                                           class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg font-mono text-slate-900 focus:border-amber-600 focus:ring-1 focus:ring-amber-600" />
                                    <span class="text-[11px] text-slate-500 block mt-1">Overrides months; current monthly rate ₹{{ number_format($monthlyBilling['total_monthly_charge']) }}/mo.</span>
                                </div>
                            </div>

                            <div class="flex flex-col sm:flex-row gap-4 pt-1">
                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" name="dispatch_current_month" value="1" @checked(old('dispatch_current_month'))
                                           class="rounded text-amber-600 border-slate-300 focus:ring-amber-600" />
                                    <span class="font-medium text-slate-800">Dispatch current month&rsquo;s Gangajal bottle now via NimbusPost</span>
                                </label>
                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" name="send_welcome_email" value="1" @checked(old('send_welcome_email', true))
                                           class="rounded text-amber-600 border-slate-300 focus:ring-amber-600" />
                                    <span class="font-medium text-slate-800">Email portal welcome &amp; password-setup link</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex justify-end">
                        <button type="submit"
                                class="px-5 py-2.5 bg-amber-600 hover:bg-amber-700 text-white font-semibold rounded-lg shadow-xs transition">
                            Save Offline Membership
                        </button>
                    </div>
                </form>
            </div>

            <!-- Right Col: Bulk CSV Import Card -->
            <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-xs space-y-5">
                <div class="pb-3 border-b border-slate-100">
                    <h2 class="text-base font-semibold text-slate-900">2. Bulk CSV Import</h2>
                    <p class="text-xs text-slate-500 mt-1">
                        Import multiple pre-website members from a CSV file in one batch. Already-active emails are safely skipped.
                    </p>
                </div>

                <a href="{{ route('admin.memberships.offline.template') }}"
                   class="w-full py-2 px-3 bg-slate-50 hover:bg-slate-100 border border-slate-300 text-slate-800 rounded-lg text-xs font-semibold transition flex items-center justify-center gap-2">
                    <span>Download CSV Sample Template</span>
                </a>

                <form method="POST" action="{{ route('admin.memberships.offline.import') }}" enctype="multipart/form-data" class="space-y-4 text-xs">
                    @csrf

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1.5">Upload CSV File *</label>
                        <input type="file" name="csv_file" accept=".csv,.txt" required
                               class="block w-full text-xs text-slate-600 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-amber-50 file:text-amber-800 hover:file:bg-amber-100 border border-slate-300 rounded-lg p-1" />
                        @error('csv_file') <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2 pt-1">
                        <label class="flex items-start gap-2 cursor-pointer">
                            <input type="checkbox" name="send_welcome_email" value="1" checked
                                   class="mt-0.5 rounded text-amber-600 border-slate-300 focus:ring-amber-600" />
                            <span class="text-slate-700">Send welcome &amp; password-setup email to newly created members</span>
                        </label>
                        <label class="flex items-start gap-2 cursor-pointer">
                            <input type="checkbox" name="dispatch_current_month" value="1"
                                   class="mt-0.5 rounded text-amber-600 border-slate-300 focus:ring-amber-600" />
                            <span class="text-slate-700">Create &amp; book current month&rsquo;s NimbusPost dispatch for imported rows</span>
                        </label>
                    </div>

                    <button type="submit"
                            class="w-full py-2.5 px-4 bg-[#881337] hover:bg-[#6B0E29] text-white font-semibold rounded-lg shadow-xs transition">
                        Import Offline Members CSV
                    </button>
                </form>

                <div class="p-3 bg-slate-50 border border-slate-200 rounded-lg text-[11px] text-slate-600 space-y-1">
                    <p class="font-semibold text-slate-800">Required CSV Columns:</p>
                    <p class="font-mono break-all text-[10px]">name, email, phone, address_line1, city, state, postal_code</p>
                    <p class="font-semibold text-slate-800 pt-1">Optional Columns:</p>
                    <p class="font-mono break-all text-[10px]">address_line2, starts_at (YYYY-MM-DD), fee_paid, validity_years, payment_method, payment_id, invoice_number, prepaid_months</p>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>

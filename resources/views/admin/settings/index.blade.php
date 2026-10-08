<x-admin-layout title="Platform Settings">
    <x-slot name="header">Settings</x-slot>

    <div class="max-w-3xl bg-white border border-slate-200 rounded-xl p-6 sm:p-8">
        <div class="mb-6 pb-5 border-b border-slate-100">
            <h1 class="text-lg font-semibold text-slate-900">Platform & Pricing Settings</h1>
            <p class="text-xs text-slate-500 mt-0.5">Manage devotee membership contribution terms, default shipping charges, and automated benefit rules.</p>
        </div>

        <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6 text-xs text-slate-700">
            @csrf

            <!-- Membership & Delivery Pricing Section -->
            <div class="space-y-4">
                <h2 class="text-sm font-semibold text-slate-900">Membership & Shipping Rules</h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-medium text-slate-700 mb-1">One-Time Membership Fee (INR) *</label>
                        <input type="number"
                               step="0.01"
                               name="membership_fee"
                               value="{{ old('membership_fee', $settings['membership_fee']) }}"
                               required
                               class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 font-mono focus:border-amber-600 focus:ring-1 focus:ring-amber-600" />
                        <span class="text-[11px] text-slate-500 mt-1 block">Default: ₹500.00 for 5 years</span>
                    </div>

                    <div>
                        <label class="block font-medium text-slate-700 mb-1">Membership Validity Duration (Years) *</label>
                        <input type="number"
                               name="membership_validity_years"
                               value="{{ old('membership_validity_years', $settings['membership_validity_years']) }}"
                               required
                               class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 font-mono focus:border-amber-600 focus:ring-1 focus:ring-amber-600" />
                        <span class="text-[11px] text-slate-500 mt-1 block">Standard tenure: 5 Years</span>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-medium text-slate-700 mb-1">Flat Shipping Rate per Order (INR) *</label>
                        <input type="number"
                               step="0.01"
                               name="shipping_charge"
                               value="{{ old('shipping_charge', $settings['shipping_charge']) }}"
                               required
                               class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 font-mono focus:border-amber-600 focus:ring-1 focus:ring-amber-600" />
                        <span class="text-[11px] text-slate-500 mt-1 block">Default flat fee: ₹149.00</span>
                    </div>

                    <div class="flex items-center pt-2">
                        <label class="w-full flex items-start gap-3 p-3 bg-slate-50 border border-slate-200 rounded-lg cursor-pointer hover:border-slate-300 transition">
                            <input type="checkbox"
                                   name="free_first_bottle_enabled"
                                   value="1"
                                   {{ $settings['free_first_bottle_enabled'] === '1' ? 'checked' : '' }}
                                   class="mt-0.5 rounded text-amber-600 focus:ring-amber-600 border-slate-300">
                            <div>
                                <span class="text-slate-900 block font-medium text-xs">1st Monthly Bottle Free</span>
                                <span class="text-[11px] text-slate-500">Active 5-year members receive 1 bottle complimentary (₹0) per monthly cycle.</span>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Devotee Support & Provenance Metadata Section -->
            <div class="pt-5 border-t border-slate-100 space-y-4">
                <h2 class="text-sm font-semibold text-slate-900">Devotee Support & Provenance Defaults</h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-medium text-slate-700 mb-1">Devotee Care Phone Number *</label>
                        <input type="text"
                               name="support_phone"
                               value="{{ old('support_phone', $settings['support_phone']) }}"
                               required
                               class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-800 focus:border-amber-600 focus:ring-1 focus:ring-amber-600" />
                    </div>
                    <div>
                        <label class="block font-medium text-slate-700 mb-1">Official Contact Email *</label>
                        <input type="email"
                               name="support_email"
                               value="{{ old('support_email', $settings['support_email']) }}"
                               required
                               class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-800 focus:border-amber-600 focus:ring-1 focus:ring-amber-600" />
                    </div>
                </div>

                <div>
                    <label class="block font-medium text-slate-700 mb-1">Default Holy Sourcing Ghat *</label>
                    <input type="text"
                           name="ghat_location_default"
                           value="{{ old('ghat_location_default', $settings['ghat_location_default']) }}"
                           required
                           class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-800 focus:border-amber-600 focus:ring-1 focus:ring-amber-600" />
                </div>
            </div>

            <div class="pt-5 border-t border-slate-100 flex justify-end">
                <button type="submit" class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white font-medium rounded-lg text-xs shadow-xs transition">
                    Save Settings
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>

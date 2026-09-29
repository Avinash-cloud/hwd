<x-admin-layout title="Dynamic Pricing Settings">
    <x-slot name="header">
        Dynamic Pricing & Platform Rules
    </x-slot>

    <div class="max-w-3xl mx-auto bg-slate-950/80 border border-slate-800 rounded-3xl p-8 shadow-sm">
        <div class="mb-6 pb-4 border-b border-slate-800">
            <h2 class="text-xl font-serif font-bold text-white">No-Code Pricing & Automation Rules</h2>
            <p class="text-xs text-slate-400 mt-1">Adjust membership subscription terms, shipping rates, and free monthly bottle logic without code modifications.</p>
        </div>

        <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6 text-xs text-slate-300">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block font-bold text-white mb-1">One-Time Membership Contribution (INR) *</label>
                    <input type="number" step="0.01" name="membership_fee" value="{{ old('membership_fee', $settings['membership_fee']) }}" required class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white font-mono text-sm" />
                    <span class="text-[10px] text-slate-500 mt-1 block">Default: ₹500.00 for 5 years</span>
                </div>

                <div>
                    <label class="block font-bold text-white mb-1">Membership Validity Duration (Years) *</label>
                    <input type="number" name="membership_validity_years" value="{{ old('membership_validity_years', $settings['membership_validity_years']) }}" required class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white font-mono text-sm" />
                    <span class="text-[10px] text-slate-500 mt-1 block">Default: 5 Years</span>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block font-bold text-white mb-1">Flat Shipping Rate per Order (INR) *</label>
                    <input type="number" step="0.01" name="shipping_charge" value="{{ old('shipping_charge', $settings['shipping_charge']) }}" required class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white font-mono text-sm" />
                    <span class="text-[10px] text-slate-500 mt-1 block">Default: ₹149.00 flat</span>
                </div>

                <div class="flex items-center pt-5">
                    <label class="flex items-start gap-3 p-3 bg-slate-900 border border-slate-800 rounded-xl cursor-pointer">
                        <input type="checkbox" name="free_first_bottle_enabled" value="1" {{ $settings['free_first_bottle_enabled'] === '1' ? 'checked' : '' }} class="mt-0.5 rounded text-[#D97706] focus:ring-[#D97706]">
                        <div>
                            <strong class="text-white block font-bold">1st Monthly Bottle Free Logic</strong>
                            <span class="text-[10px] text-slate-400">When enabled, active members get their 1st Gangajal bottle free (₹0) in every order.</span>
                        </div>
                    </label>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-800 space-y-4">
                <h3 class="font-serif font-bold text-sm text-white">Devotee Support & Provenance Metadata</h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label class="block font-bold text-white mb-1">Devotee Care Phone</label>
                        <input type="text" name="support_phone" value="{{ old('support_phone', $settings['support_phone']) }}" required class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white" />
                    </div>
                    <div>
                        <label class="block font-bold text-white mb-1">Official Contact Email</label>
                        <input type="email" name="support_email" value="{{ old('support_email', $settings['support_email']) }}" required class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white" />
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-white mb-1">Default Holy Sourcing Ghat</label>
                    <input type="text" name="ghat_location_default" value="{{ old('ghat_location_default', $settings['ghat_location_default']) }}" required class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white" />
                </div>
            </div>

            <div class="pt-6 border-t border-slate-800 flex justify-end">
                <button type="submit" class="px-6 py-2.5 bg-[#D97706] hover:bg-[#F97316] text-white font-bold rounded-xl shadow-xs transition">
                    Save Configuration Changes
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>

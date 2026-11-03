<x-admin-layout title="Create Coupon">
    <x-slot name="header">Create Coupon</x-slot>

    <div class="mb-6">
        <a href="{{ route('admin.coupons.index') }}" class="text-xs text-slate-500 hover:text-slate-800 transition inline-flex items-center gap-1 font-medium">
            ← Back to Coupons
        </a>
        <h1 class="text-xl font-semibold text-slate-900 mt-1">Create Promotional Coupon</h1>
        <p class="text-xs text-slate-500 mt-0.5">Define promo code rules, discount mechanics, and usage limits.</p>
    </div>

    <div class="max-w-2xl bg-white border border-slate-200 rounded-xl p-6 shadow-xs">
        <form method="POST" action="{{ route('admin.coupons.store') }}" class="space-y-5 text-xs text-slate-700">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold text-slate-800 mb-1">Coupon Code *</label>
                    <input type="text"
                           name="code"
                           value="{{ old('code') }}"
                           required
                           placeholder="e.g. BLISS20 or GANGA100"
                           class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs font-mono uppercase text-slate-900 focus:border-amber-600 focus:ring-1 focus:ring-amber-600" />
                    @error('code') <span class="text-rose-600 text-[11px]">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block font-semibold text-slate-800 mb-1">Coupon Name / Campaign</label>
                    <input type="text"
                           name="name"
                           value="{{ old('name') }}"
                           placeholder="e.g. Navratri Special Offer"
                           class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 focus:border-amber-600 focus:ring-1 focus:ring-amber-600" />
                </div>
            </div>

            <div>
                <label class="block font-semibold text-slate-800 mb-1">Description / Notes</label>
                <input type="text"
                       name="description"
                       value="{{ old('description') }}"
                       placeholder="e.g. 20% off for festival devotees"
                       class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 focus:border-amber-600 focus:ring-1 focus:ring-amber-600" />
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block font-semibold text-slate-800 mb-1">Discount Type *</label>
                    <select name="discount_type" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 focus:border-amber-600 focus:ring-1 focus:ring-amber-600">
                        <option value="percentage" {{ old('discount_type') === 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                        <option value="fixed" {{ old('discount_type') === 'fixed' ? 'selected' : '' }}>Fixed Amount (₹)</option>
                    </select>
                </div>

                <div>
                    <label class="block font-semibold text-slate-800 mb-1">Discount Value *</label>
                    <input type="number"
                           step="0.01"
                           min="0.01"
                           name="discount_value"
                           value="{{ old('discount_value') }}"
                           required
                           placeholder="e.g. 20 (for 20%) or 100"
                           class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 font-mono focus:border-amber-600 focus:ring-1 focus:ring-amber-600" />
                </div>

                <div>
                    <label class="block font-semibold text-slate-800 mb-1">Max Discount (₹)</label>
                    <input type="number"
                           step="0.01"
                           name="max_discount"
                           value="{{ old('max_discount') }}"
                           placeholder="Optional max cap"
                           class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 font-mono focus:border-amber-600 focus:ring-1 focus:ring-amber-600" />
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold text-slate-800 mb-1">Minimum Order Spend (₹)</label>
                    <input type="number"
                           step="0.01"
                           name="min_spend"
                           value="{{ old('min_spend') }}"
                           placeholder="e.g. 499.00"
                           class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 font-mono focus:border-amber-600 focus:ring-1 focus:ring-amber-600" />
                </div>

                <div>
                    <label class="block font-semibold text-slate-800 mb-1">Usage Limit (Times)</label>
                    <input type="number"
                           name="usage_limit"
                           value="{{ old('usage_limit') }}"
                           placeholder="Leave empty for unlimited"
                           class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 font-mono focus:border-amber-600 focus:ring-1 focus:ring-amber-600" />
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold text-slate-800 mb-1">Valid From</label>
                    <input type="datetime-local"
                           name="starts_at"
                           value="{{ old('starts_at') }}"
                           class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 focus:border-amber-600 focus:ring-1 focus:ring-amber-600" />
                </div>

                <div>
                    <label class="block font-semibold text-slate-800 mb-1">Expires On</label>
                    <input type="datetime-local"
                           name="expires_at"
                           value="{{ old('expires_at') }}"
                           class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 focus:border-amber-600 focus:ring-1 focus:ring-amber-600" />
                </div>
            </div>

            <div class="pt-2">
                <label class="inline-flex items-center gap-2 cursor-pointer">
                    <input type="checkbox"
                           name="is_active"
                           value="1"
                           {{ old('is_active', true) ? 'checked' : '' }}
                           class="w-4 h-4 text-amber-600 rounded border-slate-300 focus:ring-amber-500">
                    <span class="text-xs font-semibold text-slate-800">Coupon is Active Immediately</span>
                </label>
            </div>

            <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
                <a href="{{ route('admin.coupons.index') }}" class="px-4 py-2 border border-slate-300 rounded-lg text-slate-700 hover:bg-slate-50 transition">
                    Cancel
                </a>
                <button type="submit" class="px-5 py-2 bg-amber-600 hover:bg-amber-700 text-white font-medium rounded-lg text-xs shadow-xs transition">
                    Create Coupon
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>


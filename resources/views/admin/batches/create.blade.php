<x-admin-layout title="Create Batch">
    <x-slot name="header">Record Batch</x-slot>

    <div class="mb-5">
        <a href="{{ route('admin.batches.index') }}" class="text-xs text-slate-500 hover:text-slate-800 transition inline-flex items-center gap-1 font-medium">
            ← Back to Batches
        </a>
    </div>

    <div class="max-w-3xl bg-white border border-slate-200 rounded-xl p-6 sm:p-8">
        <div class="mb-6 pb-5 border-b border-slate-100">
            <h1 class="text-lg font-semibold text-slate-900">Record New Consecrated Batch</h1>
            <p class="text-xs text-slate-500 mt-0.5">Configure holy provenance credentials, lab purity notes, and sacred ceremony recordings.</p>
        </div>

        <form method="POST" action="{{ route('admin.batches.store') }}" class="space-y-5 text-xs text-slate-700">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-medium text-slate-700 mb-1">Batch Code *</label>
                    <input type="text"
                           name="batch_number"
                           value="HB-GANG-{{ date('Y') }}-{{ str_pad(\App\Models\Batch::count() + 1, 3, '0', STR_PAD_LEFT) }}"
                           required
                           class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 font-mono focus:border-amber-600 focus:ring-1 focus:ring-amber-600" />
                </div>

                <div>
                    <label class="block font-medium text-slate-700 mb-1">Batch Status *</label>
                    <select name="status" required class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-800 focus:border-amber-600 focus:ring-1 focus:ring-amber-600">
                        <option value="ready">Ready for Dispensing</option>
                        <option value="dispatched">Dispatched</option>
                        <option value="archived">Archived / Depleted</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block font-medium text-slate-700 mb-1">Holy Sourcing Ghat *</label>
                <input type="text"
                       name="sourcing_ghat"
                       value="Har Ki Pauri, Brahmakund, Haridwar, Uttarakhand"
                       required
                       class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-800 focus:border-amber-600 focus:ring-1 focus:ring-amber-600" />
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-medium text-slate-700 mb-1">Water Collection Date *</label>
                    <input type="date"
                           name="collection_date"
                           value="{{ date('Y-m-d') }}"
                           required
                           class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-800 focus:border-amber-600 focus:ring-1 focus:ring-amber-600" />
                </div>
                <div>
                    <label class="block font-medium text-slate-700 mb-1">Packaging / Seal Date *</label>
                    <input type="date"
                           name="packaging_date"
                           value="{{ date('Y-m-d') }}"
                           required
                           class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-800 focus:border-amber-600 focus:ring-1 focus:ring-amber-600" />
                </div>
            </div>

            <div>
                <label class="block font-medium text-slate-700 mb-1">Ganga Aarti & Ceremony Description</label>
                <textarea name="description"
                          rows="3"
                          class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-800 placeholder-slate-400 focus:border-amber-600 focus:ring-1 focus:ring-amber-600"
                          placeholder="Brahma Muhurta morning Aarti sacred collection at Har Ki Pauri..."></textarea>
            </div>

            <div>
                <label class="block font-medium text-slate-700 mb-1">Lab Testing & Purity Assay Notes</label>
                <textarea name="purity_notes"
                          rows="3"
                          class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-800 placeholder-slate-400 focus:border-amber-600 focus:ring-1 focus:ring-amber-600"
                          placeholder="pH 7.8, Zero Coliform, High Dissolved Oxygen, 100% natural Himalayan minerals..."></textarea>
            </div>

            <div>
                <label class="block font-medium text-slate-700 mb-1">Aarti Collection Video URL</label>
                <input type="url"
                       name="video_url"
                       class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-800 placeholder-slate-400 focus:border-amber-600 focus:ring-1 focus:ring-amber-600"
                       placeholder="https://www.youtube.com/watch?v=..." />
            </div>

            <div class="flex items-center justify-end gap-3 pt-5 border-t border-slate-100">
                <a href="{{ route('admin.batches.index') }}" class="px-4 py-2 rounded-lg border border-slate-300 text-slate-700 hover:bg-slate-50 transition text-xs font-medium">
                    Cancel
                </a>
                <button type="submit" class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white font-medium rounded-lg text-xs shadow-xs transition">
                    Save Sacred Batch
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>

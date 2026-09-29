<x-admin-layout title="Create Batch">
    <x-slot name="header">
        Record New Consecrated Gangajal Batch
    </x-slot>

    <div class="max-w-3xl mx-auto bg-slate-950/80 border border-slate-800 rounded-3xl p-8 shadow-sm">
        <form method="POST" action="{{ route('admin.batches.store') }}" class="space-y-6 text-xs text-slate-300">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block font-bold text-white mb-1">Batch Code * (e.g. HB-GANG-2026-003)</label>
                    <input type="text" name="batch_number" value="HB-GANG-{{ date('Y') }}-{{ str_pad(\App\Models\Batch::count() + 1, 3, '0', STR_PAD_LEFT) }}" required class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white font-mono" />
                </div>

                <div>
                    <label class="block font-bold text-white mb-1">Batch Status *</label>
                    <select name="status" required class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white">
                        <option value="ready">Ready for Dispensing</option>
                        <option value="dispatched">Dispatched</option>
                        <option value="archived">Archived / Depleted</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block font-bold text-white mb-1">Holy Sourcing Ghat *</label>
                <input type="text" name="sourcing_ghat" value="Har Ki Pauri, Brahmakund, Haridwar, Uttarakhand" required class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white" />
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block font-bold text-white mb-1">Water Collection Date *</label>
                    <input type="date" name="collection_date" value="{{ date('Y-m-d') }}" required class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white" />
                </div>
                <div>
                    <label class="block font-bold text-white mb-1">Packaging / Hermetic Seal Date *</label>
                    <input type="date" name="packaging_date" value="{{ date('Y-m-d') }}" required class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white" />
                </div>
            </div>

            <div>
                <label class="block font-bold text-white mb-1">Ganga Aarti & Collection Ceremony Description</label>
                <textarea name="description" rows="2" class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white" placeholder="Brahma Muhurta morning Aarti sacred collection at Har Ki Pauri..."></textarea>
            </div>

            <div>
                <label class="block font-bold text-white mb-1">Lab Testing & Purity Assay Notes</label>
                <textarea name="purity_notes" rows="2" class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white" placeholder="pH 7.8, Zero Coliform, High Dissolved Oxygen, 100% natural Himalayan minerals..."></textarea>
            </div>

            <div>
                <label class="block font-bold text-white mb-1">Aarti Collection Video URL (YouTube / Cloud Link)</label>
                <input type="url" name="video_url" class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white" placeholder="https://www.youtube.com/watch?v=..." />
            </div>

            <div class="flex justify-end gap-3 pt-6 border-t border-slate-800">
                <a href="{{ route('admin.batches.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-700 text-slate-300 hover:text-white">Cancel</a>
                <button type="submit" class="px-6 py-2.5 bg-[#D97706] hover:bg-[#F97316] text-white font-bold rounded-xl shadow-xs transition">
                    Save Sacred Batch
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>

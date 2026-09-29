<x-admin-layout title="Edit Product">
    <x-slot name="header">
        Edit: {{ $product->name }}
    </x-slot>

    <div class="max-w-4xl mx-auto bg-slate-950/80 border border-slate-800 rounded-3xl p-8 shadow-sm">
        <form method="POST" action="{{ route('admin.products.update', $product->id) }}" class="space-y-6 text-xs text-slate-300">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block font-bold text-white mb-1">Category *</label>
                    <select name="category_id" required class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white">
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" {{ $product->category_id === $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-bold text-white mb-1">Assign Consecrated Batch</label>
                    <select name="batch_id" class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white">
                        <option value="">-- None / Non-Gangajal Item --</option>
                        @foreach ($batches as $batch)
                            <option value="{{ $batch->id }}" {{ $product->batch_id === $batch->id ? 'selected' : '' }}>{{ $batch->batch_number }} ({{ $batch->sourcing_ghat }})</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block font-bold text-white mb-1">Product Name *</label>
                <input type="text" name="name" value="{{ old('name', $product->name) }}" required class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white" />
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div>
                    <label class="block font-bold text-white mb-1">Regular Price (INR) *</label>
                    <input type="number" step="0.01" name="price" value="{{ old('price', $product->price) }}" required class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white" />
                </div>
                <div>
                    <label class="block font-bold text-white mb-1">Member Price (INR)</label>
                    <input type="number" step="0.01" name="member_price" value="{{ old('member_price', $product->member_price) }}" class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white" />
                </div>
                <div>
                    <label class="block font-bold text-white mb-1">Available Stock *</label>
                    <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" required class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white" />
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div>
                    <label class="block font-bold text-white mb-1">SKU</label>
                    <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white" />
                </div>
                <div>
                    <label class="block font-bold text-white mb-1">Volume (ml) - if Gangajal</label>
                    <input type="number" name="volume_ml" value="{{ old('volume_ml', $product->volume_ml) }}" class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white" />
                </div>
                <div class="flex items-center gap-4 pt-6">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_gangajal" value="1" {{ $product->is_gangajal ? 'checked' : '' }} class="rounded text-[#D97706] focus:ring-[#D97706]">
                        <span class="text-white font-bold">Is Gangajal?</span>
                    </label>
                </div>
            </div>

            <div>
                <label class="block font-bold text-white mb-1">Short Description</label>
                <input type="text" name="short_description" value="{{ old('short_description', $product->short_description) }}" class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white" />
            </div>

            <div>
                <label class="block font-bold text-white mb-1">Detailed Description</label>
                <textarea name="description" rows="4" class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white">{{ old('description', $product->description) }}</textarea>
            </div>

            <div>
                <label class="block font-bold text-white mb-1">Purity Details & Lab Notes</label>
                <textarea name="purity_details" rows="2" class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white">{{ old('purity_details', $product->purity_details) }}</textarea>
            </div>

            <div class="flex items-center gap-6 pt-4 border-t border-slate-800">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ $product->is_active ? 'checked' : '' }} class="rounded text-[#D97706]">
                    <span class="text-white">Active for sale</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_featured" value="1" {{ $product->is_featured ? 'checked' : '' }} class="rounded text-[#D97706]">
                    <span class="text-white">Feature on Home Page</span>
                </label>
            </div>

            <div class="flex justify-end gap-3 pt-6 border-t border-slate-800">
                <a href="{{ route('admin.products.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-700 text-slate-300 hover:text-white">Cancel</a>
                <button type="submit" class="px-6 py-2.5 bg-[#D97706] hover:bg-[#F97316] text-white font-bold rounded-xl shadow-xs transition">
                    Update Product
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>

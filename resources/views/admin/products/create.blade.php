<x-admin-layout title="Create Product">
    <x-slot name="header">
        Products
    </x-slot>

    <div class="max-w-3xl mx-auto">
        <div class="mb-6">
            <h1 class="text-xl font-semibold text-slate-900">Add Product</h1>
            <p class="text-xs text-slate-500 mt-0.5">Configure pricing, stock inventory, details, and catalog visibility.</p>
        </div>

        <div class="bg-white border border-slate-200 rounded-xl p-6 sm:p-8 shadow-xs">
            <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" class="space-y-6 text-sm"
                  x-data="{
                      previewUrl: '',
                      imageSource: 'file',
                      handleFileSelect(e) {
                          const file = e.target.files[0];
                          if (file) {
                              this.previewUrl = URL.createObjectURL(file);
                          }
                      }
                  }">
                @csrf

                <!-- Section: Basic Information -->
                <div>
                    <h2 class="text-sm font-semibold text-slate-900 mb-3">Basic Information</h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Category *</label>
                            <select name="category_id" required class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-sm text-slate-800 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500">
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Batch Assignment (Optional)</label>
                            <select name="batch_id" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-sm text-slate-800 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500">
                                <option value="">None / Non-liquid item</option>
                                @foreach ($batches as $batch)
                                    <option value="{{ $batch->id }}" {{ old('batch_id') == $batch->id ? 'selected' : '' }}>
                                        {{ $batch->batch_number }} ({{ $batch->sourcing_ghat }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Product Name *</label>
                            <input type="text"
                                   name="name"
                                   value="{{ old('name') }}"
                                   required
                                   placeholder="e.g. Pure Brahmakund Gangajal (500ml)"
                                   class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500" />
                            @error('name') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">SKU</label>
                            <input type="text"
                                   name="sku"
                                   value="{{ old('sku') }}"
                                   placeholder="HB-GJ-500"
                                   class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500" />
                        </div>
                    </div>
                </div>

                <!-- Section: Pricing & Stock -->
                <div class="pt-5 border-t border-slate-200">
                    <h2 class="text-sm font-semibold text-slate-900 mb-3">Pricing & Inventory</h2>

                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Sale Price (INR) *</label>
                            <input type="number"
                                   step="0.01"
                                   name="price"
                                   value="{{ old('price') }}"
                                   required
                                   placeholder="149.00"
                                   class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-sm text-slate-900 font-mono focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500" />
                            @error('price') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Compare Price (MRP)</label>
                            <input type="number"
                                   step="0.01"
                                   name="compare_price"
                                   value="{{ old('compare_price') }}"
                                   placeholder="249.00"
                                   class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-sm text-slate-900 font-mono focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500" />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Member Price</label>
                            <input type="number"
                                   step="0.01"
                                   name="member_price"
                                   value="{{ old('member_price') }}"
                                   placeholder="0 for monthly bottle"
                                   class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-sm text-slate-900 font-mono focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500" />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Stock Count *</label>
                            <input type="number"
                                   name="stock"
                                   value="{{ old('stock', 100) }}"
                                   required
                                   class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-sm text-slate-900 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Promotional Badge</label>
                            <input type="text"
                                   name="badge"
                                   value="{{ old('badge') }}"
                                   placeholder="e.g. 20% OFF or Best Value"
                                   class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500" />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Volume (ml)</label>
                            <input type="number"
                                   name="volume_ml"
                                   value="{{ old('volume_ml') }}"
                                   placeholder="500"
                                   class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500" />
                        </div>

                        <div class="flex items-center pt-2 sm:pt-6">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox"
                                       name="is_gangajal"
                                       value="1"
                                       {{ old('is_gangajal') ? 'checked' : '' }}
                                       class="rounded text-amber-600 focus:ring-amber-500 border-slate-300">
                                <span class="text-sm text-slate-700 font-medium">Sacred Gangajal</span>
                            </label>
                        </div>

                        <div class="flex items-center pt-2 sm:pt-6">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox"
                                       name="is_member_free_eligible"
                                       value="1"
                                       {{ old('is_member_free_eligible') ? 'checked' : '' }}
                                       class="rounded text-amber-600 focus:ring-amber-500 border-slate-300">
                                <span class="text-sm text-slate-700 font-medium">Monthly Member Free Eligible</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Section: Media -->
                <div class="pt-5 border-t border-slate-200">
                    <div class="flex items-center justify-between mb-2">
                        <div>
                            <h2 class="text-sm font-semibold text-slate-900">Product Image</h2>
                            <span class="block text-xs text-slate-500">Upload a photo from your computer or specify an image URL</span>
                        </div>
                        <div class="flex rounded-md bg-slate-100 p-0.5 text-xs">
                            <button type="button"
                                    @click="imageSource = 'file'"
                                    :class="imageSource === 'file' ? 'bg-white text-slate-900 shadow-xs font-medium' : 'text-slate-600'"
                                    class="px-2.5 py-1 rounded transition">
                                File
                            </button>
                            <button type="button"
                                    @click="imageSource = 'url'"
                                    :class="imageSource === 'url' ? 'bg-white text-slate-900 shadow-xs font-medium' : 'text-slate-600'"
                                    class="px-2.5 py-1 rounded transition">
                                URL
                            </button>
                        </div>
                    </div>

                    <div x-show="imageSource === 'file'">
                        <input type="file"
                               name="image_file"
                               accept="image/*"
                               @change="handleFileSelect($event)"
                               class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border file:border-slate-300 file:text-xs file:font-medium file:bg-white file:text-slate-700 hover:file:bg-slate-50 file:cursor-pointer" />
                        @error('image_file') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div x-show="imageSource === 'url'">
                        <input type="text"
                               name="image_url"
                               x-model="previewUrl"
                               value="{{ old('image_url') }}"
                               placeholder="https://... or images/ganga-holy-waters.jpg"
                               class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500" />
                        @error('image_url') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div x-show="previewUrl" class="mt-3 flex items-center gap-3">
                        <div class="w-14 h-14 rounded-lg overflow-hidden border border-slate-200 bg-slate-50 shrink-0">
                            <img :src="previewUrl" alt="Preview" class="w-full h-full object-cover">
                        </div>
                        <span class="text-xs text-slate-500">Selected image preview</span>
                    </div>
                </div>

                <!-- Section: Descriptions -->
                <div class="pt-5 border-t border-slate-200 space-y-4">
                    <h2 class="text-sm font-semibold text-slate-900">Descriptions & Details</h2>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Short Summary (Catalog Cards)</label>
                        <input type="text"
                               name="short_description"
                               value="{{ old('short_description') }}"
                               placeholder="e.g. Direct Brahmakund collection hermetically sealed with batch QR verification."
                               class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Full Product Description</label>
                        <textarea name="description"
                                  rows="3"
                                  placeholder="Provide detailed description, Vedic significance, and origin details..."
                                  class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500">{{ old('description') }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Purity Notes / Lab Assay</label>
                            <textarea name="purity_details"
                                      rows="2"
                                      placeholder="e.g. pH 7.8, untouched bottling, certified potability..."
                                      class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500">{{ old('purity_details') }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Devotional Benefits</label>
                            <textarea name="benefits"
                                      rows="2"
                                      placeholder="e.g. Essential for Rudra Abhishek, sacred home purification..."
                                      class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500">{{ old('benefits') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Section: Visibility Flags -->
                <div class="pt-5 border-t border-slate-200">
                    <div class="flex flex-wrap items-center gap-6">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="rounded text-amber-600 focus:ring-amber-500 border-slate-300">
                            <span class="text-sm text-slate-700">Active for sale</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} class="rounded text-amber-600 focus:ring-amber-500 border-slate-300">
                            <span class="text-sm text-slate-700">Feature on homepage</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_bestseller" value="1" {{ old('is_bestseller') ? 'checked' : '' }} class="rounded text-amber-600 focus:ring-amber-500 border-slate-300">
                            <span class="text-sm text-slate-700">Mark as bestseller</span>
                        </label>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-end gap-3 pt-5 border-t border-slate-200">
                    <a href="{{ route('admin.products.index') }}" class="px-4 py-2 rounded-lg border border-slate-300 text-slate-700 hover:bg-slate-50 transition text-sm font-medium">Cancel</a>
                    <button type="submit" class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white font-medium text-sm rounded-lg shadow-xs transition">
                        Save Product
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>

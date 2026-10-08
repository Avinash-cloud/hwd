<x-admin-layout title="Edit Product">
    <x-slot name="header">
        Edit Product: {{ $product->name }}
    </x-slot>

    <div class="max-w-4xl mx-auto bg-slate-950/80 border border-slate-800 rounded-3xl p-6 sm:p-8 shadow-sm">
        <div class="mb-6 pb-6 border-b border-slate-800 flex justify-between items-center">
            <div>
                <h2 class="text-lg font-serif font-bold text-white">Edit: {{ $product->name }}</h2>
                <p class="text-xs text-slate-400 mt-1">Update pricing, inventory, descriptions, photos, and homepage badges.</p>
            </div>
            <a href="{{ route('products.show', $product->slug) }}" target="_blank" class="text-xs text-amber-400 hover:text-amber-300 underline font-semibold">
                View on Store ↗
            </a>
        </div>

        <form method="POST" action="{{ route('admin.products.update', $product->id) }}" enctype="multipart/form-data" class="space-y-6 text-xs text-slate-300"
              x-data="{
                  previewUrl: '{{ $product->image_url }}',
                  imageSource: 'file',
                  handleFileSelect(e) {
                      const file = e.target.files[0];
                      if (file) {
                          this.previewUrl = URL.createObjectURL(file);
                      }
                  }
              }">
            @csrf
            @method('PUT')

            <!-- Category & Batch -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block font-bold text-white mb-1">Category *</label>
                    <select name="category_id" required class="w-full px-3.5 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-white focus:border-[#D97706]">
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id') <p class="text-red-400 text-[11px] mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block font-bold text-white mb-1">Assign Consecrated Batch (For Gangajal)</label>
                    <select name="batch_id" class="w-full px-3.5 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-white focus:border-[#D97706]">
                        <option value="">-- None / Non-Gangajal Item --</option>
                        @foreach ($batches as $batch)
                            <option value="{{ $batch->id }}" {{ old('batch_id', $product->batch_id) == $batch->id ? 'selected' : '' }}>
                                {{ $batch->batch_number }} ({{ $batch->sourcing_ghat }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Product Name & SKU -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div class="sm:col-span-2">
                    <label class="block font-bold text-white mb-1">Product Name *</label>
                    <input type="text"
                           name="name"
                           value="{{ old('name', $product->name) }}"
                           required
                           class="w-full px-3.5 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-white focus:border-[#D97706]" />
                    @error('name') <p class="text-red-400 text-[11px] mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block font-bold text-white mb-1">SKU / Item Code</label>
                    <input type="text"
                           name="sku"
                           value="{{ old('sku', $product->sku) }}"
                           class="w-full px-3.5 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-white focus:border-[#D97706]" />
                </div>
            </div>

            <!-- Pricing & Inventory -->
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 bg-slate-900/40 p-5 rounded-2xl border border-slate-800">
                <div>
                    <label class="block font-bold text-white mb-1">Sale Price (INR) *</label>
                    <input type="number"
                           step="0.01"
                           name="price"
                           value="{{ old('price', $product->price) }}"
                           required
                           class="w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-xl text-white font-mono" />
                    @error('price') <p class="text-red-400 text-[11px] mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block font-bold text-white mb-1">Original MRP (Compare)</label>
                    <input type="number"
                           step="0.01"
                           name="compare_price"
                           value="{{ old('compare_price', $product->compare_price) }}"
                           class="w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-xl text-white font-mono" />
                    <span class="block text-[10px] text-slate-500 mt-0.5">Calculates % discount</span>
                </div>

                <div>
                    <label class="block font-bold text-white mb-1">Devotee Member Price</label>
                    <input type="number"
                           step="0.01"
                           name="member_price"
                           value="{{ old('member_price', $product->member_price) }}"
                           placeholder="0 for 1st Gangajal"
                           class="w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-xl text-white font-mono text-[#0369A1]" />
                    <span class="block text-[10px] text-slate-500 mt-0.5">Exclusive rate for 5-yr members</span>
                </div>

                <div>
                    <label class="block font-bold text-white mb-1">Stock Count *</label>
                    <input type="number"
                           name="stock"
                           value="{{ old('stock', $product->stock) }}"
                           required
                           class="w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-xl text-white" />
                </div>
            </div>

            <!-- Gangajal & Volume & Promotional Badge -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div>
                    <label class="block font-bold text-white mb-1">Promotional Badge</label>
                    <input type="text"
                           name="badge"
                           value="{{ old('badge', $product->badge) }}"
                           placeholder="e.g. 30% OFF or Bestseller"
                           class="w-full px-3.5 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-white focus:border-[#D97706]" />
                </div>

                <div>
                    <label class="block font-bold text-white mb-1">Volume (ml) - if Liquid / Gangajal</label>
                    <input type="number"
                           name="volume_ml"
                           value="{{ old('volume_ml', $product->volume_ml) }}"
                           placeholder="500"
                           class="w-full px-3.5 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-white focus:border-[#D97706]" />
                </div>

                <div class="flex items-center pt-6">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox"
                               name="is_gangajal"
                               value="1"
                               {{ old('is_gangajal', $product->is_gangajal) ? 'checked' : '' }}
                               class="rounded text-[#0369A1] focus:ring-[#0369A1]">
                        <span class="text-white font-bold">Is Sacred Gangajal?</span>
                    </label>
                </div>
            </div>

            <!-- Product Image Upload or URL -->
            <div class="bg-slate-900/60 p-5 rounded-2xl border border-slate-800 space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="block font-bold text-white">Product Photo</span>
                        <span class="block text-[11px] text-slate-400">Upload a replacement image or specify an image URL</span>
                    </div>

                    <!-- Source Selector -->
                    <div class="flex rounded-lg bg-slate-950 p-1 border border-slate-800 text-[11px]">
                        <button type="button"
                                @click="imageSource = 'file'"
                                :class="imageSource === 'file' ? 'bg-[#D97706] text-white font-bold' : 'text-slate-400 hover:text-white'"
                                class="px-2.5 py-1 rounded-md transition">
                            Upload File
                        </button>
                        <button type="button"
                                @click="imageSource = 'url'"
                                :class="imageSource === 'url' ? 'bg-[#D97706] text-white font-bold' : 'text-slate-400 hover:text-white'"
                                class="px-2.5 py-1 rounded-md transition">
                            Image URL / Preset
                        </button>
                    </div>
                </div>

                <!-- Option A: File Upload -->
                <div x-show="imageSource === 'file'" class="space-y-3">
                    <input type="file"
                           name="image_file"
                           accept="image/*"
                           @change="handleFileSelect($event)"
                           class="w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-xl text-slate-300 file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-[#D97706] file:text-white hover:file:bg-[#F97316] file:cursor-pointer" />
                    <p class="text-[11px] text-slate-500">Leave blank to retain current image</p>
                    @error('image_file') <p class="text-red-400 text-[11px] mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Option B: Image URL -->
                <div x-show="imageSource === 'url'" class="space-y-2">
                    <input type="text"
                           name="image_url"
                           x-model="previewUrl"
                           value="{{ old('image_url', $product->image) }}"
                           placeholder="https://... or images/ganga-holy-waters.jpg"
                           class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-white focus:border-[#D97706]" />
                    <p class="text-[11px] text-slate-500">Current stored value: <code class="text-amber-400">{{ $product->image ?: 'None' }}</code></p>
                    @error('image_url') <p class="text-red-400 text-[11px] mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Live Preview -->
                <div class="mt-3">
                    <span class="block text-[11px] font-bold text-slate-400 mb-1.5">Photo Preview:</span>
                    <div class="w-32 h-32 rounded-xl overflow-hidden border border-slate-700 bg-slate-950 relative">
                        <img :src="previewUrl" alt="{{ $product->name }}" class="w-full h-full object-cover">
                    </div>
                </div>
            </div>

            <!-- Short Description -->
            <div>
                <label class="block font-bold text-white mb-1">Short Summary (Shown in catalog cards)</label>
                <input type="text"
                       name="short_description"
                       value="{{ old('short_description', $product->short_description) }}"
                       class="w-full px-3.5 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-white focus:border-[#D97706]" />
            </div>

            <!-- Detailed Description -->
            <div>
                <label class="block font-bold text-white mb-1">Full Product Description</label>
                <textarea name="description"
                          rows="4"
                          class="w-full px-3.5 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-white focus:border-[#D97706]">{{ old('description', $product->description) }}</textarea>
            </div>

            <!-- Purity Details & Benefits -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block font-bold text-white mb-1">Purity Details & Lab Test Notes</label>
                    <textarea name="purity_details"
                              rows="2"
                              class="w-full px-3.5 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-white focus:border-[#D97706]">{{ old('purity_details', $product->purity_details) }}</textarea>
                </div>
                <div>
                    <label class="block font-bold text-white mb-1">Devotional Benefits</label>
                    <textarea name="benefits"
                              rows="2"
                              class="w-full px-3.5 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-white focus:border-[#D97706]">{{ old('benefits', $product->benefits) }}</textarea>
                </div>
            </div>

            <!-- Visibility & Flags -->
            <div class="flex flex-wrap items-center gap-8 pt-4 border-t border-slate-800">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }} class="rounded text-[#D97706]">
                    <span class="text-white font-bold">Active for Sale</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }} class="rounded text-amber-500">
                    <span class="text-amber-300 font-bold">★ Feature on Homepage</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_bestseller" value="1" {{ old('is_bestseller', $product->is_bestseller) ? 'checked' : '' }} class="rounded text-rose-500">
                    <span class="text-rose-300 font-bold">🔥 Mark as Bestseller</span>
                </label>
            </div>

            <!-- Actions -->
            <div class="flex justify-end gap-3 pt-6 border-t border-slate-800">
                <a href="{{ route('admin.products.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-700 text-slate-300 hover:text-white transition">Cancel</a>
                <button type="submit" class="px-6 py-2.5 bg-[#D97706] hover:bg-[#F97316] text-white font-bold rounded-xl shadow-md transition">
                    Update Product
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>

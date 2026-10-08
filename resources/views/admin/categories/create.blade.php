<x-admin-layout title="Create Category">
    <x-slot name="header">
        Create Catalog Category
    </x-slot>

    <div class="max-w-3xl mx-auto bg-slate-950/80 border border-slate-800 rounded-3xl p-6 sm:p-8 shadow-sm">
        <div class="mb-6 pb-6 border-b border-slate-800">
            <h2 class="text-lg font-serif font-bold text-white">Add New Offering Category</h2>
            <p class="text-xs text-slate-400 mt-1">
                Define category taxonomy, parent hierarchy, circular story imagery, and icon.
            </p>
        </div>

        <form method="POST" action="{{ route('admin.categories.store') }}" enctype="multipart/form-data" class="space-y-6 text-xs text-slate-300"
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

            <!-- Parent Category Selection -->
            <div>
                <label class="block font-bold text-white mb-1">Parent Category</label>
                <select name="parent_id" class="w-full px-3.5 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-white focus:border-[#D97706]">
                    <option value="">-- None (Top-Level / Root Category) --</option>
                    @foreach ($rootCategories as $rcat)
                        <option value="{{ $rcat->id }}" {{ old('parent_id') == $rcat->id ? 'selected' : '' }}>
                            Child of: {{ $rcat->name }}
                        </option>
                    @endforeach
                </select>
                <p class="text-[11px] text-slate-500 mt-1">Leave empty if this is a main root category.</p>
                @error('parent_id') <p class="text-red-400 text-[11px] mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Name, Icon, Slug -->
            <div class="grid grid-cols-1 sm:grid-cols-12 gap-6">
                <div class="sm:col-span-6">
                    <label class="block font-bold text-white mb-1">Category Name *</label>
                    <input type="text"
                           name="name"
                           value="{{ old('name') }}"
                           required
                           placeholder="e.g. Pure Desi Cow Ghee"
                           class="w-full px-3.5 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-white focus:border-[#D97706]" />
                    @error('name') <p class="text-red-400 text-[11px] mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="sm:col-span-2">
                    <label class="block font-bold text-white mb-1">Icon / Emoji</label>
                    <input type="text"
                           name="icon"
                           value="{{ old('icon', '🪔') }}"
                           placeholder="🪔"
                           class="w-full px-3.5 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-white text-center focus:border-[#D97706]" />
                    @error('icon') <p class="text-red-400 text-[11px] mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="sm:col-span-4">
                    <label class="block font-bold text-white mb-1">Custom Slug (Optional)</label>
                    <input type="text"
                           name="slug"
                           value="{{ old('slug') }}"
                           placeholder="auto-generated-from-name"
                           class="w-full px-3.5 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-white focus:border-[#D97706]" />
                    @error('slug') <p class="text-red-400 text-[11px] mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Description -->
            <div>
                <label class="block font-bold text-white mb-1">Short Description</label>
                <textarea name="description"
                          rows="3"
                          placeholder="Brief description of the offerings and spiritual significance in this collection..."
                          class="w-full px-3.5 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-white focus:border-[#D97706]">{{ old('description') }}</textarea>
                @error('description') <p class="text-red-400 text-[11px] mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Category Image (File Upload or URL) -->
            <div class="bg-slate-900/60 p-5 rounded-2xl border border-slate-800 space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="block font-bold text-white">Category Photo / Story Thumbnail</span>
                        <span class="block text-[11px] text-slate-400">Used in circular category navigation on homepage and catalog banner</span>
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
                    @error('image_file') <p class="text-red-400 text-[11px] mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Option B: Image URL -->
                <div x-show="imageSource === 'url'" class="space-y-2">
                    <input type="text"
                           name="image_url"
                           x-model="previewUrl"
                           value="{{ old('image_url') }}"
                           placeholder="https://... or images/har-ki-pauri-temples.jpg"
                           class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-white focus:border-[#D97706]" />
                    @error('image_url') <p class="text-red-400 text-[11px] mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Live Preview -->
                <div x-show="previewUrl" class="mt-3">
                    <span class="block text-[11px] font-bold text-slate-400 mb-1.5">Circular Preview:</span>
                    <div class="w-20 h-20 rounded-full overflow-hidden border-2 border-amber-500 bg-slate-950 relative p-0.5">
                        <img :src="previewUrl" alt="Preview" class="w-full h-full object-cover rounded-full">
                    </div>
                </div>
            </div>

            <!-- Sort Order & Settings -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pt-2">
                <div>
                    <label class="block font-bold text-white mb-1">Display Sort Order</label>
                    <input type="number"
                           name="sort_order"
                           value="{{ old('sort_order', $nextSortOrder) }}"
                           class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white font-mono" />
                    <p class="text-[10px] text-slate-500 mt-1">Lower numbers appear first</p>
                </div>

                <div class="space-y-3 pt-4">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox"
                               name="is_featured"
                               value="1"
                               {{ old('is_featured') ? 'checked' : '' }}
                               class="rounded text-amber-500 focus:ring-amber-500">
                        <span class="text-amber-300 font-bold">★ Feature in Homepage "Sacred Collections"</span>
                    </label>

                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox"
                               name="is_active"
                               value="1"
                               {{ old('is_active', true) ? 'checked' : '' }}
                               class="rounded text-[#D97706] focus:ring-[#D97706]">
                        <span class="text-white font-bold">Active & Visible in Store</span>
                    </label>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-end gap-3 pt-6 border-t border-slate-800">
                <a href="{{ route('admin.categories.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-700 text-slate-300 hover:text-white transition">Cancel</a>
                <button type="submit" class="px-6 py-2.5 bg-[#D97706] hover:bg-[#F97316] text-white font-bold rounded-xl shadow-md transition">
                    Save Category
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>

<x-admin-layout title="Create Homepage Slide">
    <x-slot name="header">
        Create Homepage Slide
    </x-slot>

    <div class="max-w-4xl mx-auto bg-slate-950/80 border border-slate-800 rounded-3xl p-6 sm:p-8 shadow-sm">
        <div class="mb-6 pb-6 border-b border-slate-800">
            <h2 class="text-lg font-serif font-bold text-white">Add New Promotional Slide</h2>
            <p class="text-xs text-slate-400 mt-1">
                Configure the banner visuals, sacred slogans, and buttons displayed on the public storefront homepage hero carousel.
            </p>
        </div>

        <form method="POST" action="{{ route('admin.sliders.store') }}" enctype="multipart/form-data" class="space-y-6 text-xs text-slate-300"
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

            <!-- Slogan & Heading -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div class="sm:col-span-2">
                    <label class="block font-bold text-white mb-1">Slide Title / Main Heading *</label>
                    <input type="text"
                           name="title"
                           value="{{ old('title') }}"
                           required
                           placeholder="e.g. Pure, Untouched Gangajal for Your Daily Abhishek"
                           class="w-full px-3.5 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-white focus:border-[#D97706] focus:ring-1 focus:ring-[#D97706]" />
                    @error('title')
                        <p class="text-red-400 text-[11px] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block font-bold text-white mb-1">Top Pill / Kicker Slogan</label>
                    <input type="text"
                           name="subtitle"
                           value="{{ old('subtitle') }}"
                           placeholder="e.g. Directly from Har Ki Pauri · Brahmakund"
                           class="w-full px-3.5 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-white focus:border-[#D97706] focus:ring-1 focus:ring-[#D97706]" />
                    @error('subtitle')
                        <p class="text-red-400 text-[11px] mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Description -->
            <div>
                <label class="block font-bold text-white mb-1">Slide Description / Subtext</label>
                <textarea name="description"
                          rows="3"
                          placeholder="e.g. Collected at Brahma Muhurta during morning Ganga Aarti from Har Ki Pauri. Hermetically sealed, lab tested, and trackable via unique batch QR codes."
                          class="w-full px-3.5 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-white focus:border-[#D97706] focus:ring-1 focus:ring-[#D97706]">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-400 text-[11px] mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Image Upload or URL Selection -->
            <div class="bg-slate-900/60 p-5 rounded-2xl border border-slate-800 space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="block font-bold text-white">Slide Background Image *</span>
                        <span class="block text-[11px] text-slate-400">High-resolution banner (recommended: 1920x800px or 1600x700px)</span>
                    </div>

                    <!-- Toggle Mode -->
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
                    @error('image_file')
                        <p class="text-red-400 text-[11px] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Option B: Image URL -->
                <div x-show="imageSource === 'url'" class="space-y-2">
                    <input type="text"
                           name="image_url"
                           x-model="previewUrl"
                           value="{{ old('image_url') }}"
                           placeholder="https://... or images/haridwar-panorama-clocktower.jpg"
                           class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-white focus:border-[#D97706] focus:ring-1 focus:ring-[#D97706]" />
                    <p class="text-[11px] text-slate-500">
                        Quick presets: <code class="text-amber-400">images/haridwar-panorama-clocktower.jpg</code>, <code class="text-amber-400">images/brahmakund-ghat-shrines.jpg</code>, <code class="text-amber-400">images/ganga-holy-waters.jpg</code>, <code class="text-amber-400">images/har-ki-pauri-temples.jpg</code>
                    </p>
                    @error('image_url')
                        <p class="text-red-400 text-[11px] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Live Preview -->
                <div x-show="previewUrl" class="mt-3">
                    <span class="block text-[11px] font-bold text-slate-400 mb-1.5">Live Preview:</span>
                    <div class="h-36 rounded-xl overflow-hidden border border-slate-700 bg-slate-950 relative">
                        <img :src="previewUrl" alt="Preview" class="w-full h-full object-cover">
                    </div>
                </div>
            </div>

            <!-- Call to Action Buttons -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 bg-slate-900/40 p-5 rounded-2xl border border-slate-800">
                <!-- Primary Button -->
                <div class="space-y-3">
                    <span class="block font-bold text-[#D97706]">Primary Button (Gold Solid CTA)</span>
                    <div>
                        <label class="block text-slate-400 mb-1">Button Text</label>
                        <input type="text"
                               name="button_text"
                               value="{{ old('button_text', 'Join 5-Year Membership') }}"
                               placeholder="e.g. Join 5-Year Membership"
                               class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white" />
                    </div>
                    <div>
                        <label class="block text-slate-400 mb-1">Button Link URL</label>
                        <input type="text"
                               name="button_link"
                               value="{{ old('button_link', '/membership/join') }}"
                               placeholder="e.g. /membership/join or /products"
                               class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white" />
                    </div>
                </div>

                <!-- Secondary Button -->
                <div class="space-y-3">
                    <span class="block font-bold text-slate-300">Secondary Button (Translucent Outline CTA)</span>
                    <div>
                        <label class="block text-slate-400 mb-1">Button Text</label>
                        <input type="text"
                               name="secondary_button_text"
                               value="{{ old('secondary_button_text', 'Explore Catalog') }}"
                               placeholder="e.g. Explore Holy Waters"
                               class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white" />
                    </div>
                    <div>
                        <label class="block text-slate-400 mb-1">Button Link URL</label>
                        <input type="text"
                               name="secondary_button_link"
                               value="{{ old('secondary_button_link', '/products') }}"
                               placeholder="e.g. /products?category=sacred-gangajal"
                               class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white" />
                    </div>
                </div>
            </div>

            <!-- Settings: Badge Color & Sort Order -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 pt-2">
                <div>
                    <label class="block font-bold text-white mb-1">Badge Color Accent</label>
                    <select name="badge_color" class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white">
                        <option value="amber" {{ old('badge_color') == 'amber' ? 'selected' : '' }}>Sacred Amber / Saffron</option>
                        <option value="rose" {{ old('badge_color') == 'rose' ? 'selected' : '' }}>Temple Maroon / Rose</option>
                        <option value="sky" {{ old('badge_color') == 'sky' ? 'selected' : '' }}>Holy Ganga Blue / Azure</option>
                        <option value="emerald" {{ old('badge_color') == 'emerald' ? 'selected' : '' }}>Auspicious Emerald</option>
                    </select>
                </div>

                <div>
                    <label class="block font-bold text-white mb-1">Display Sort Order</label>
                    <input type="number"
                           name="sort_order"
                           value="{{ old('sort_order', $nextSortOrder) }}"
                           class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white" />
                    <p class="text-[10px] text-slate-500 mt-1">Lower numbers appear first</p>
                </div>

                <div class="flex items-center pt-5">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox"
                               name="is_active"
                               value="1"
                               {{ old('is_active', true) ? 'checked' : '' }}
                               class="rounded text-[#D97706] focus:ring-[#D97706]">
                        <span class="text-white font-bold">Active & Published on Homepage</span>
                    </label>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end gap-3 pt-6 border-t border-slate-800">
                <a href="{{ route('admin.sliders.index') }}"
                   class="px-5 py-2.5 rounded-xl border border-slate-700 text-slate-300 hover:text-white transition">
                    Cancel
                </a>
                <button type="submit"
                        class="px-6 py-2.5 bg-[#D97706] hover:bg-[#F97316] text-white font-bold rounded-xl shadow-md transition">
                    Save & Publish Slide
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>

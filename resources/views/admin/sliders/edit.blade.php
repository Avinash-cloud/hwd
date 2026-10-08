<x-admin-layout title="Edit Homepage Slide">
    <x-slot name="header">
        Hero Sliders
    </x-slot>

    <div class="max-w-3xl mx-auto">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-xl font-semibold text-slate-900">Edit Slide: #{{ $slider->id }}</h1>
                <p class="text-xs text-slate-500 mt-0.5">
                    Update graphics, titles, promotional text, or destination links.
                </p>
            </div>
            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $slider->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-600 border border-slate-200' }}">
                {{ $slider->is_active ? 'Currently Live' : 'Draft' }}
            </span>
        </div>

        <div class="bg-white border border-slate-200 rounded-xl p-6 sm:p-8 shadow-xs">
            <form method="POST" action="{{ route('admin.sliders.update', $slider->id) }}" enctype="multipart/form-data" class="space-y-6 text-sm"
                  x-data="{
                      previewUrl: '{{ $slider->image_url }}',
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

                <!-- Section: Content -->
                <div>
                    <h2 class="text-sm font-semibold text-slate-900 mb-3">Slide Content</h2>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Slide Title *</label>
                            <input type="text"
                                   name="title"
                                   value="{{ old('title', $slider->title) }}"
                                   required
                                   class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-sm text-slate-900 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500" />
                            @error('title') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Kicker Slogan (Pill)</label>
                            <input type="text"
                                   name="subtitle"
                                   value="{{ old('subtitle', $slider->subtitle) }}"
                                   placeholder="e.g. From Har Ki Pauri"
                                   class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-sm text-slate-900 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500" />
                            @error('subtitle') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Description / Subtext</label>
                        <textarea name="description"
                                  rows="2"
                                  class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-sm text-slate-900 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500">{{ old('description', $slider->description) }}</textarea>
                        @error('description') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Section: Media -->
                <div class="pt-5 border-t border-slate-200">
                    <div class="flex items-center justify-between mb-2">
                        <div>
                            <h2 class="text-sm font-semibold text-slate-900">Background Image</h2>
                            <span class="block text-xs text-slate-500">Upload replacement image or provide URL</span>
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
                        <p class="text-xs text-slate-400 mt-1">Leave blank to retain current image</p>
                        @error('image_file') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div x-show="imageSource === 'url'">
                        <input type="text"
                               name="image_url"
                               x-model="previewUrl"
                               value="{{ old('image_url', $slider->image) }}"
                               class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-sm text-slate-900 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500" />
                        <p class="text-xs text-slate-500 mt-1">Current stored path: <code>{{ $slider->image }}</code></p>
                        @error('image_url') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div x-show="previewUrl" class="mt-3">
                        <div class="h-28 rounded-lg overflow-hidden border border-slate-200 bg-slate-900 relative">
                            <img :src="previewUrl" alt="Preview" class="w-full h-full object-cover">
                        </div>
                    </div>
                </div>

                <!-- Section: CTA Buttons -->
                <div class="pt-5 border-t border-slate-200">
                    <h2 class="text-sm font-semibold text-slate-900 mb-3">Call to Action Buttons</h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Primary Button Text</label>
                            <input type="text"
                                   name="button_text"
                                   value="{{ old('button_text', $slider->button_text) }}"
                                   class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-sm text-slate-900 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Primary Destination URL</label>
                            <input type="text"
                                   name="button_link"
                                   value="{{ old('button_link', $slider->button_link) }}"
                                   class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-sm text-slate-900 font-mono focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Secondary Button Text (Optional)</label>
                            <input type="text"
                                   name="secondary_button_text"
                                   value="{{ old('secondary_button_text', $slider->secondary_button_text) }}"
                                   class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-sm text-slate-900 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Secondary Destination URL</label>
                            <input type="text"
                                   name="secondary_button_link"
                                   value="{{ old('secondary_button_link', $slider->secondary_button_link) }}"
                                   class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-sm text-slate-900 font-mono focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500" />
                        </div>
                    </div>
                </div>

                <!-- Section: Settings & Ordering -->
                <div class="pt-5 border-t border-slate-200">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Badge Accent</label>
                            <select name="badge_color" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-sm text-slate-800 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500">
                                <option value="amber" {{ old('badge_color', $slider->badge_color) == 'amber' ? 'selected' : '' }}>Saffron Amber</option>
                                <option value="rose" {{ old('badge_color', $slider->badge_color) == 'rose' ? 'selected' : '' }}>Vermilion Rose</option>
                                <option value="sky" {{ old('badge_color', $slider->badge_color) == 'sky' ? 'selected' : '' }}>Ganga Sky Blue</option>
                                <option value="emerald" {{ old('badge_color', $slider->badge_color) == 'emerald' ? 'selected' : '' }}>Tulsi Green</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Display Sort Order</label>
                            <input type="number"
                                   name="sort_order"
                                   value="{{ old('sort_order', $slider->sort_order) }}"
                                   class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-sm text-slate-900 font-mono focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500" />
                        </div>

                        <div class="flex items-center pt-2 sm:pt-6">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox"
                                       name="is_active"
                                       value="1"
                                       {{ old('is_active', $slider->is_active) ? 'checked' : '' }}
                                       class="rounded text-amber-600 focus:ring-amber-500 border-slate-300">
                                <span class="text-sm text-slate-700">Live on Storefront</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-end gap-3 pt-5 border-t border-slate-200">
                    <a href="{{ route('admin.sliders.index') }}" class="px-4 py-2 rounded-lg border border-slate-300 text-slate-700 hover:bg-slate-50 transition text-sm font-medium">Cancel</a>
                    <button type="submit" class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white font-medium text-sm rounded-lg shadow-xs transition">
                        Update Slide
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>

<x-admin-layout title="Create Homepage Slide">
    <x-slot name="header">
        Hero Sliders
    </x-slot>

    <div class="max-w-3xl mx-auto">
        <div class="mb-6">
            <h1 class="text-xl font-semibold text-slate-900">Add Slide</h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Configure visuals, headlines, and call-to-action buttons for the homepage carousel.
            </p>
        </div>

        <div class="bg-white border border-slate-200 rounded-xl p-6 sm:p-8 shadow-xs">
            <form method="POST" action="{{ route('admin.sliders.store') }}" enctype="multipart/form-data" class="space-y-6 text-sm"
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

                <!-- Section: Content -->
                <div>
                    <h2 class="text-sm font-semibold text-slate-900 mb-3">Slide Content</h2>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Slide Title</label>
                            <input type="text"
                                   name="title"
                                   value="{{ old('title') }}"
                                   placeholder="e.g. Pure, Untouched Gangajal for Your Daily Abhishek"
                                   class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500" />
                            @error('title') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Kicker Slogan (Pill)</label>
                            <input type="text"
                                   name="subtitle"
                                   value="{{ old('subtitle') }}"
                                   placeholder="e.g. From Har Ki Pauri"
                                   class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500" />
                            @error('subtitle') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Description / Subtext</label>
                        <textarea name="description"
                                  rows="2"
                                  placeholder="e.g. Collected at Brahma Muhurta during morning Ganga Aarti from Har Ki Pauri..."
                                  class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500">{{ old('description') }}</textarea>
                        @error('description') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Section: Media Configuration (Images, Videos & Responsive Dimensions) -->
                <div class="pt-5 border-t border-slate-200"
                     x-data="{
                         mediaType: '{{ old('media_type', 'image') }}',
                         imageSource: 'file',
                         tabletSource: 'file',
                         mobileSource: 'file',
                         videoSource: 'url'
                     }">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h2 class="text-sm font-semibold text-slate-900">Hero Media Type</h2>
                            <p class="text-xs text-slate-500">Choose whether this hero slide showcases an image sequence or a video background.</p>
                        </div>
                        <div class="flex rounded-lg bg-slate-100 p-1 text-xs">
                            <button type="button"
                                    @click="mediaType = 'image'"
                                    :class="mediaType === 'image' ? 'bg-white text-slate-900 font-bold shadow-xs' : 'text-slate-600'"
                                    class="px-3 py-1.5 rounded-md transition">
                                🖼️ Image
                            </button>
                            <button type="button"
                                    @click="mediaType = 'video'"
                                    :class="mediaType === 'video' ? 'bg-white text-slate-900 font-bold shadow-xs' : 'text-slate-600'"
                                    class="px-3 py-1.5 rounded-md transition">
                                🎥 Video
                            </button>
                        </div>
                    </div>
                    <input type="hidden" name="media_type" :value="mediaType">

                    <!-- Video Fields -->
                    <div x-show="mediaType === 'video'" class="space-y-4 p-4 bg-amber-50/50 rounded-xl border border-amber-200/60 mb-4">
                        <div class="flex items-center justify-between">
                            <label class="text-xs font-bold text-slate-800">Background Video (MP4)</label>
                            <div class="flex rounded-md bg-white p-0.5 text-xs border border-slate-200">
                                <button type="button" @click="videoSource = 'url'" :class="videoSource === 'url' ? 'bg-amber-100 font-bold text-amber-900' : 'text-slate-600'" class="px-2 py-0.5 rounded">URL</button>
                                <button type="button" @click="videoSource = 'file'" :class="videoSource === 'file' ? 'bg-amber-100 font-bold text-amber-900' : 'text-slate-600'" class="px-2 py-0.5 rounded">Upload File</button>
                            </div>
                        </div>

                        <div x-show="videoSource === 'url'">
                            <input type="text"
                                   name="video_url"
                                   value="{{ old('video_url') }}"
                                   placeholder="https://.../haridwar_aarti.mp4"
                                   class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs font-mono text-slate-900 focus:outline-none focus:border-amber-500">
                            <p class="text-[11px] text-slate-500 mt-1">Direct link to MP4 video. Will autoplay muted on slide transition with devotee unmute control.</p>
                        </div>

                        <div x-show="videoSource === 'file'">
                            <input type="file"
                                   name="video_file"
                                   accept="video/mp4,video/webm"
                                   class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border file:border-slate-300 file:text-xs file:font-medium file:bg-white file:text-slate-700 hover:file:bg-slate-50 file:cursor-pointer">
                            <p class="text-[11px] text-slate-500 mt-1">Maximum 50MB MP4 format recommended for quick mobile streaming.</p>
                        </div>
                    </div>

                    <!-- Recommended Dimensions Guidance Box -->
                    <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl mb-4 text-xs text-slate-600 space-y-1">
                        <strong class="text-slate-900 font-semibold block">Recommended Aspect Ratios & Resolutions:</strong>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 pt-1 font-mono text-[11px]">
                            <span class="p-1.5 bg-white rounded border border-slate-200">🖥️ Desktop: 1920 × 700 px</span>
                            <span class="p-1.5 bg-white rounded border border-slate-200">📱 Tablet: 1024 × 600 px</span>
                            <span class="p-1.5 bg-white rounded border border-slate-200">📱 Mobile: 750 × 1000 px</span>
                        </div>
                    </div>

                    <!-- Desktop Image (Also serves as Video Poster) -->
                    <div class="space-y-2 mb-4">
                        <div class="flex items-center justify-between">
                            <label class="text-xs font-bold text-slate-800">
                                <span x-text="mediaType === 'video' ? 'Video Poster / Desktop Fallback Image' : 'Desktop Image (1920×700)'"></span>
                            </label>
                            <div class="flex rounded-md bg-slate-100 p-0.5 text-xs">
                                <button type="button" @click="imageSource = 'file'" :class="imageSource === 'file' ? 'bg-white font-bold text-slate-900 shadow-xs' : 'text-slate-600'" class="px-2 py-0.5 rounded">File</button>
                                <button type="button" @click="imageSource = 'url'" :class="imageSource === 'url' ? 'bg-white font-bold text-slate-900 shadow-xs' : 'text-slate-600'" class="px-2 py-0.5 rounded">URL</button>
                            </div>
                        </div>

                        <div x-show="imageSource === 'file'">
                            <input type="file"
                                   name="image_file"
                                   accept="image/*"
                                   @change="handleFileSelect($event)"
                                   class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border file:border-slate-300 file:text-xs file:font-medium file:bg-white file:text-slate-700 hover:file:bg-slate-50 file:cursor-pointer" />
                        </div>

                        <div x-show="imageSource === 'url'">
                            <input type="text"
                                   name="image_url"
                                   x-model="previewUrl"
                                   value="{{ old('image_url') }}"
                                   placeholder="https://... or images/haridwar-panorama-clocktower.jpg"
                                   class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs font-mono text-slate-900 focus:outline-none focus:border-amber-500" />
                        </div>
                    </div>

                    <!-- Responsive Tablet & Mobile Media -->
                    <div class="pt-3 border-t border-slate-100" x-data="{ showResponsive: false }">
                        <button type="button"
                                @click="showResponsive = !showResponsive"
                                class="text-xs font-bold text-[#D97706] hover:underline flex items-center gap-1">
                            <span x-text="showResponsive ? '▲ Hide Responsive Breakpoints (Tablet & Mobile)' : '▼ Add Specific Tablet (1024×600) & Mobile (750×1000) Images'"></span>
                        </button>

                        <div x-show="showResponsive" class="mt-3 space-y-4 pt-3 border-t border-slate-100">
                            <!-- Tablet Image -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 p-3 bg-stone-50 rounded-xl border border-stone-200">
                                <div>
                                    <label class="block text-xs font-bold text-slate-800 mb-1">Tablet Image File (1024×600 px)</label>
                                    <input type="file" name="tablet_image_file" accept="image/*" class="w-full text-xs text-slate-500 file:mr-2 file:py-1 file:px-2 file:rounded file:border file:border-slate-300 file:text-xs file:bg-white">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-800 mb-1">Or Tablet Image URL</label>
                                    <input type="text" name="tablet_image_url" value="{{ old('tablet_image_url') }}" placeholder="https://..." class="w-full px-2.5 py-1.5 bg-white border border-slate-300 rounded text-xs">
                                </div>
                            </div>

                            <!-- Mobile Image -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 p-3 bg-stone-50 rounded-xl border border-stone-200">
                                <div>
                                    <label class="block text-xs font-bold text-slate-800 mb-1">Mobile Image File (750×1000 px portrait)</label>
                                    <input type="file" name="mobile_image_file" accept="image/*" class="w-full text-xs text-slate-500 file:mr-2 file:py-1 file:px-2 file:rounded file:border file:border-slate-300 file:text-xs file:bg-white">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-800 mb-1">Or Mobile Image URL</label>
                                    <input type="text" name="mobile_image_url" value="{{ old('mobile_image_url') }}" placeholder="https://..." class="w-full px-2.5 py-1.5 bg-white border border-slate-300 rounded text-xs">
                                </div>
                            </div>
                        </div>
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
                                   value="{{ old('button_text', 'Explore Sacred Collection') }}"
                                   class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-sm text-slate-900 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Primary Destination URL</label>
                            <input type="text"
                                   name="button_link"
                                   value="{{ old('button_link', '/products') }}"
                                   class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-sm text-slate-900 font-mono focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Secondary Button Text (Optional)</label>
                            <input type="text"
                                   name="secondary_button_text"
                                   value="{{ old('secondary_button_text') }}"
                                   placeholder="e.g. Join 5-Yr Devotee Club"
                                   class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-sm text-slate-900 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Secondary Destination URL</label>
                            <input type="text"
                                   name="secondary_button_link"
                                   value="{{ old('secondary_button_link') }}"
                                   placeholder="e.g. /membership"
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
                                <option value="amber" {{ old('badge_color') == 'amber' ? 'selected' : '' }}>Saffron Amber</option>
                                <option value="rose" {{ old('badge_color') == 'rose' ? 'selected' : '' }}>Vermilion Rose</option>
                                <option value="sky" {{ old('badge_color') == 'sky' ? 'selected' : '' }}>Ganga Sky Blue</option>
                                <option value="emerald" {{ old('badge_color') == 'emerald' ? 'selected' : '' }}>Tulsi Green</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Display Sort Order</label>
                            <input type="number"
                                   name="sort_order"
                                   value="{{ old('sort_order', 1) }}"
                                   class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-sm text-slate-900 font-mono focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500" />
                        </div>

                        <div class="flex items-center pt-2 sm:pt-6">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox"
                                       name="is_active"
                                       value="1"
                                       {{ old('is_active', true) ? 'checked' : '' }}
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
                        Save Slide
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>

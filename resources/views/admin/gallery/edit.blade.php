<x-admin-layout title="Edit Media">
    <x-slot name="header">Edit Media</x-slot>

    <div class="max-w-3xl mx-auto">
        <div class="mb-5 flex items-center justify-between">
            <a href="{{ route('admin.gallery.index') }}" class="text-xs text-slate-500 hover:text-slate-800 transition inline-flex items-center gap-1 font-medium">
                ← Back to Media Gallery
            </a>
            <a href="{{ route('gallery.index') }}" target="_blank" class="text-xs text-slate-600 hover:text-slate-900 font-medium">
                View Public Gallery ↗
            </a>
        </div>

        <div class="bg-white border border-slate-200 rounded-xl p-6 sm:p-8 shadow-xs">
            <div class="mb-6 pb-5 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h1 class="text-lg font-semibold text-slate-900">Edit Media: {{ Str::limit($gallery->title, 40) }}</h1>
                    <p class="text-xs text-slate-500 mt-0.5">
                        {{ $gallery->location }} · {{ ucfirst($gallery->media_type) }} format
                    </p>
                </div>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium uppercase {{ $gallery->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-600 border border-slate-200' }}">
                    {{ $gallery->is_active ? 'Live' : 'Hidden' }}
                </span>
            </div>

            <form method="POST"
                  action="{{ route('admin.gallery.update', $gallery->id) }}"
                  enctype="multipart/form-data"
                  class="space-y-6 text-xs text-slate-700"
                  x-data="{
                      mediaType: '{{ $gallery->media_type }}',
                      imageSource: 'file',
                      previewUrl: '{{ $gallery->image_url }}',
                      handleFileSelect(e) {
                          const file = e.target.files[0];
                          if (file) {
                              this.previewUrl = URL.createObjectURL(file);
                          }
                      }
                  }">
                @csrf
                @method('PUT')

                <!-- Title & Caption -->
                <div class="space-y-4">
                    <div>
                        <label class="block font-medium text-slate-700 mb-1">Title *</label>
                        <input type="text"
                               name="title"
                               value="{{ old('title', $gallery->title) }}"
                               required
                               class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-sm text-slate-900 focus:outline-none focus:border-amber-600 focus:ring-1 focus:ring-amber-600" />
                        @error('title')
                            <p class="text-[11px] text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block font-medium text-slate-700 mb-1">Devotional Caption / Context</label>
                        <textarea name="caption"
                                  rows="2"
                                  class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-amber-600 focus:ring-1 focus:ring-amber-600">{{ old('caption', $gallery->caption) }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block font-medium text-slate-700 mb-1">Media Type *</label>
                            <select name="media_type"
                                    x-model="mediaType"
                                    class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-800 focus:outline-none focus:border-amber-600 focus:ring-1 focus:ring-amber-600">
                                <option value="image" {{ $gallery->media_type === 'image' ? 'selected' : '' }}>Still Photo</option>
                                <option value="video" {{ $gallery->media_type === 'video' ? 'selected' : '' }}>Aarti / Ceremony Video</option>
                            </select>
                        </div>

                        <div>
                            <label class="block font-medium text-slate-700 mb-1">Category *</label>
                            <input type="text"
                                   name="category"
                                   list="gallery-categories"
                                   value="{{ old('category', $gallery->category) }}"
                                   required
                                   class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-800 focus:outline-none focus:border-amber-600 focus:ring-1 focus:ring-amber-600" />
                            <datalist id="gallery-categories">
                                @foreach ($existingCategories as $cat)
                                    <option value="{{ $cat }}">
                                @endforeach
                            </datalist>
                        </div>

                        <div>
                            <label class="block font-medium text-slate-700 mb-1">Holy Location</label>
                            <input type="text"
                                   name="location"
                                   value="{{ old('location', $gallery->location) }}"
                                   class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-800 focus:outline-none focus:border-amber-600 focus:ring-1 focus:ring-amber-600" />
                        </div>
                    </div>

                    <!-- Video URL input (conditional) -->
                    <div x-show="mediaType === 'video'" style="{{ $gallery->media_type === 'video' ? '' : 'display: none;' }}">
                        <label class="block font-medium text-slate-700 mb-1">Video Streaming URL</label>
                        <input type="url"
                               name="video_url"
                               value="{{ old('video_url', $gallery->video_url) }}"
                               placeholder="https://www.youtube.com/watch?v=..."
                               class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-amber-600 focus:ring-1 focus:ring-amber-600" />
                    </div>
                </div>

                <!-- Media Upload -->
                <div class="pt-5 border-t border-slate-100">
                    <div class="flex items-center justify-between mb-2">
                        <div>
                            <span class="block text-xs font-semibold text-slate-800">Media Cover Image</span>
                            <span class="block text-[11px] text-slate-500">Upload replacement file or update image URL</span>
                        </div>
                        <div class="flex rounded-md bg-slate-100 p-0.5 text-xs">
                            <button type="button"
                                    @click="imageSource = 'file'"
                                    :class="imageSource === 'file' ? 'bg-white text-slate-900 shadow-xs font-medium' : 'text-slate-600'"
                                    class="px-2.5 py-1 rounded transition">
                                Upload File
                            </button>
                            <button type="button"
                                    @click="imageSource = 'url'"
                                    :class="imageSource === 'url' ? 'bg-white text-slate-900 shadow-xs font-medium' : 'text-slate-600'"
                                    class="px-2.5 py-1 rounded transition">
                                Image URL
                            </button>
                        </div>
                    </div>

                    <div x-show="imageSource === 'file'">
                        <input type="file"
                               name="image_file"
                               accept="image/*"
                               @change="handleFileSelect($event)"
                               class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border file:border-slate-300 file:text-xs file:font-medium file:bg-white file:text-slate-700 hover:file:bg-slate-50 file:cursor-pointer" />
                    </div>

                    <div x-show="imageSource === 'url'" style="display: none;">
                        <input type="text"
                               name="image_url"
                               x-model="previewUrl"
                               value="{{ old('image_url', $gallery->image) }}"
                               class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-amber-600 focus:ring-1 focus:ring-amber-600" />
                    </div>

                    <div x-show="previewUrl" class="mt-3 flex items-center gap-3">
                        <div class="w-24 h-16 rounded-lg overflow-hidden border border-slate-200 bg-slate-50 shrink-0">
                            <img :src="previewUrl" alt="Preview" class="w-full h-full object-cover">
                        </div>
                        <span class="text-[11px] text-slate-500">Current media cover preview</span>
                    </div>
                </div>

                <!-- Sort Order & Active Controls -->
                <div class="pt-5 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-6">
                        <div>
                            <label class="block font-medium text-slate-700 mb-1">Display Sort Order</label>
                            <input type="number"
                                   name="sort_order"
                                   value="{{ old('sort_order', $gallery->sort_order) }}"
                                   class="w-24 px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-800 font-mono focus:outline-none focus:border-amber-600 focus:ring-1 focus:ring-amber-600" />
                        </div>

                        <label class="flex items-center gap-2 cursor-pointer pt-4">
                            <input type="checkbox"
                                   name="is_active"
                                   value="1"
                                   {{ old('is_active', $gallery->is_active) ? 'checked' : '' }}
                                   class="rounded text-amber-600 focus:ring-amber-600 border-slate-300">
                            <span class="text-xs font-medium text-slate-800">Visible on public gallery</span>
                        </label>
                    </div>

                    <div class="flex items-center gap-3">
                        <a href="{{ route('admin.gallery.index') }}" class="px-4 py-2 rounded-lg border border-slate-300 text-slate-700 hover:bg-slate-50 transition text-xs font-medium">
                            Cancel
                        </a>
                        <button type="submit" class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white font-medium text-xs rounded-lg shadow-xs transition">
                            Save Changes
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>

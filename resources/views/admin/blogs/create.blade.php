<x-admin-layout title="Write Article">
    <x-slot name="header">Write Article</x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="mb-5 flex items-center justify-between">
            <a href="{{ route('admin.blogs.index') }}" class="text-xs text-slate-500 hover:text-slate-800 transition inline-flex items-center gap-1 font-medium">
                ← Back to Articles
            </a>
        </div>

        <div class="bg-white border border-slate-200 rounded-xl p-6 sm:p-8 shadow-xs">
            <div class="mb-6 pb-5 border-b border-slate-100">
                <h1 class="text-lg font-semibold text-slate-900">Write New Spiritual Article</h1>
                <p class="text-xs text-slate-500 mt-0.5">
                    Share Vedic teachings, holy Gangajal provenance insights, or pooja ritual instructions with devotees.
                </p>
            </div>

            <form method="POST"
                  action="{{ route('admin.blogs.store') }}"
                  enctype="multipart/form-data"
                  class="space-y-6 text-xs text-slate-700"
                  x-data="{
                      imageSource: 'file',
                      previewUrl: '',
                      handleFileSelect(e) {
                          const file = e.target.files[0];
                          if (file) {
                              this.previewUrl = URL.createObjectURL(file);
                          }
                      }
                  }">
                @csrf

                <!-- Title & Slug -->
                <div class="space-y-4">
                    <div>
                        <label class="block font-medium text-slate-700 mb-1">Article Title *</label>
                        <input type="text"
                               name="title"
                               value="{{ old('title') }}"
                               required
                               placeholder="e.g. The Sacred Significance of Brahmakund at Har Ki Pauri"
                               class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:border-amber-600 focus:ring-1 focus:ring-amber-600" />
                        @error('title')
                            <p class="text-[11px] text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block font-medium text-slate-700 mb-1">Category *</label>
                            <input type="text"
                                   name="category"
                                   list="category-suggestions"
                                   value="{{ old('category', 'Ganga Sanctity') }}"
                                   required
                                   placeholder="e.g. Ganga Sanctity"
                                   class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-800 focus:outline-none focus:border-amber-600 focus:ring-1 focus:ring-amber-600" />
                            <datalist id="category-suggestions">
                                @foreach ($existingCategories as $cat)
                                    <option value="{{ $cat }}">
                                @endforeach
                                <option value="Ganga Sanctity">
                                <option value="Rituals & Traditions">
                                <option value="Vedic Health">
                                <option value="Spiritual Wisdom">
                            </datalist>
                        </div>

                        <div>
                            <label class="block font-medium text-slate-700 mb-1">Author Name *</label>
                            <input type="text"
                                   name="author_name"
                                   value="{{ old('author_name', 'Acharya Vidyadhar Shastri') }}"
                                   required
                                   class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-800 focus:outline-none focus:border-amber-600 focus:ring-1 focus:ring-amber-600" />
                        </div>

                        <div>
                            <label class="block font-medium text-slate-700 mb-1">Estimated Reading Time (Minutes)</label>
                            <input type="number"
                                   name="reading_time_minutes"
                                   value="{{ old('reading_time_minutes', 5) }}"
                                   min="1"
                                   max="60"
                                   class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-800 focus:outline-none focus:border-amber-600 focus:ring-1 focus:ring-amber-600" />
                        </div>
                    </div>

                    <div>
                        <label class="block font-medium text-slate-700 mb-1">Custom Slug (Optional)</label>
                        <input type="text"
                               name="slug"
                               value="{{ old('slug') }}"
                               placeholder="leave-blank-to-auto-generate"
                               class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-800 font-mono placeholder-slate-400 focus:outline-none focus:border-amber-600 focus:ring-1 focus:ring-amber-600" />
                    </div>
                </div>

                <!-- Excerpt -->
                <div>
                    <label class="block font-medium text-slate-700 mb-1">Short Summary / Excerpt</label>
                    <textarea name="excerpt"
                              rows="2"
                              placeholder="A brief 1-2 sentence overview shown in article preview cards and meta descriptions..."
                              class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-amber-600 focus:ring-1 focus:ring-amber-600">{{ old('excerpt') }}</textarea>
                </div>

                <!-- Content -->
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label class="block font-medium text-slate-700">Full Article Content (HTML / Text) *</label>
                        <span class="text-[11px] text-slate-400">Supports standard HTML tags (&lt;p&gt;, &lt;h3&gt;, &lt;blockquote&gt;, &lt;ul&gt;)</span>
                    </div>
                    <textarea name="content"
                              rows="12"
                              required
                              placeholder="Write your devotional article here. You can format it using paragraphs, subheadings, and quote blocks..."
                              class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-lg text-xs font-mono text-slate-900 placeholder-slate-400 focus:outline-none focus:border-amber-600 focus:ring-1 focus:ring-amber-600 leading-relaxed">{{ old('content') }}</textarea>
                    @error('content')
                        <p class="text-[11px] text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Featured Image -->
                <div class="pt-5 border-t border-slate-100">
                    <div class="flex items-center justify-between mb-2">
                        <div>
                            <span class="block text-xs font-semibold text-slate-800">Featured Article Cover Image</span>
                            <span class="block text-[11px] text-slate-500">Banner displayed at the top of the article and on catalog cards</span>
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
                               value="{{ old('image_url') }}"
                               placeholder="https://... or images/har-ki-pauri-temples.jpg"
                               class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-amber-600 focus:ring-1 focus:ring-amber-600" />
                    </div>

                    <div x-show="previewUrl" class="mt-3 flex items-center gap-3">
                        <div class="w-20 h-14 rounded-lg overflow-hidden border border-slate-200 bg-slate-50 shrink-0">
                            <img :src="previewUrl" alt="Preview" class="w-full h-full object-cover">
                        </div>
                        <span class="text-[11px] text-slate-500">Selected cover preview</span>
                    </div>
                </div>

                <!-- Publishing Controls -->
                <div class="pt-5 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-6">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox"
                                   name="is_published"
                                   value="1"
                                   checked
                                   class="rounded text-amber-600 focus:ring-amber-600 border-slate-300">
                            <span class="text-xs font-medium text-slate-800">Publish immediately</span>
                        </label>

                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox"
                                   name="is_featured"
                                   value="1"
                                   {{ old('is_featured') ? 'checked' : '' }}
                                   class="rounded text-amber-600 focus:ring-amber-600 border-slate-300">
                            <span class="text-xs font-medium text-slate-800">Feature as Hero Spotlight</span>
                        </label>
                    </div>

                    <div class="flex items-center gap-3">
                        <a href="{{ route('admin.blogs.index') }}" class="px-4 py-2 rounded-lg border border-slate-300 text-slate-700 hover:bg-slate-50 transition text-xs font-medium">
                            Cancel
                        </a>
                        <button type="submit" class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white font-medium text-xs rounded-lg shadow-xs transition">
                            Save & Publish
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>

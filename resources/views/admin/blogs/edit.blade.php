<x-admin-layout title="Edit Article">
    <x-slot name="header">Edit Article</x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="mb-5 flex items-center justify-between">
            <a href="{{ route('admin.blogs.index') }}" class="text-xs text-slate-500 hover:text-slate-800 transition inline-flex items-center gap-1 font-medium">
                ← Back to Articles
            </a>
            <a href="{{ route('blogs.show', $blog->slug) }}" target="_blank" class="text-xs text-slate-600 hover:text-slate-900 font-medium">
                View Public Article ↗
            </a>
        </div>

        <div class="bg-white border border-slate-200 rounded-xl p-6 sm:p-8 shadow-xs">
            <div class="mb-6 pb-5 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h1 class="text-lg font-semibold text-slate-900">Edit Article: {{ Str::limit($blog->title, 40) }}</h1>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Published on {{ $blog->published_at ? $blog->published_at->format('M d, Y') : 'Draft' }} · {{ $blog->views_count }} views recorded
                    </p>
                </div>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium uppercase {{ $blog->is_published ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-600 border border-slate-200' }}">
                    {{ $blog->is_published ? 'Published' : 'Draft' }}
                </span>
            </div>

            <form method="POST"
                  action="{{ route('admin.blogs.update', $blog->id) }}"
                  enctype="multipart/form-data"
                  class="space-y-6 text-xs text-slate-700"
                  x-data="{
                      imageSource: 'file',
                      previewUrl: '{{ $blog->image_url }}',
                      handleFileSelect(e) {
                          const file = e.target.files[0];
                          if (file) {
                              this.previewUrl = URL.createObjectURL(file);
                          }
                      }
                  }">
                @csrf
                @method('PUT')

                <!-- Title & Slug -->
                <div class="space-y-4">
                    <div>
                        <label class="block font-medium text-slate-700 mb-1">Article Title *</label>
                        <input type="text"
                               name="title"
                               value="{{ old('title', $blog->title) }}"
                               required
                               class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-sm text-slate-900 focus:outline-none focus:border-amber-600 focus:ring-1 focus:ring-amber-600" />
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
                                   value="{{ old('category', $blog->category) }}"
                                   required
                                   class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-800 focus:outline-none focus:border-amber-600 focus:ring-1 focus:ring-amber-600" />
                            <datalist id="category-suggestions">
                                @foreach ($existingCategories as $cat)
                                    <option value="{{ $cat }}">
                                @endforeach
                            </datalist>
                        </div>

                        <div>
                            <label class="block font-medium text-slate-700 mb-1">Author Name *</label>
                            <input type="text"
                                   name="author_name"
                                   value="{{ old('author_name', $blog->author_name) }}"
                                   required
                                   class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-800 focus:outline-none focus:border-amber-600 focus:ring-1 focus:ring-amber-600" />
                        </div>

                        <div>
                            <label class="block font-medium text-slate-700 mb-1">Reading Time (Minutes)</label>
                            <input type="number"
                                   name="reading_time_minutes"
                                   value="{{ old('reading_time_minutes', $blog->reading_time_minutes) }}"
                                   min="1"
                                   max="60"
                                   class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-800 focus:outline-none focus:border-amber-600 focus:ring-1 focus:ring-amber-600" />
                        </div>
                    </div>

                    <div>
                        <label class="block font-medium text-slate-700 mb-1">URL Slug *</label>
                        <input type="text"
                               name="slug"
                               value="{{ old('slug', $blog->slug) }}"
                               required
                               class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-800 font-mono focus:outline-none focus:border-amber-600 focus:ring-1 focus:ring-amber-600" />
                    </div>
                </div>

                <!-- Excerpt -->
                <div>
                    <label class="block font-medium text-slate-700 mb-1">Short Summary / Excerpt</label>
                    <textarea name="excerpt"
                              rows="2"
                              class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-amber-600 focus:ring-1 focus:ring-amber-600">{{ old('excerpt', $blog->excerpt) }}</textarea>
                </div>

                <!-- Content -->
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label class="block font-medium text-slate-700">Full Article Content (HTML / Text) *</label>
                        <span class="text-[11px] text-slate-400">Supports standard HTML tags</span>
                    </div>
                    <textarea name="content"
                              rows="12"
                              required
                              class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-lg text-xs font-mono text-slate-900 placeholder-slate-400 focus:outline-none focus:border-amber-600 focus:ring-1 focus:ring-amber-600 leading-relaxed">{{ old('content', $blog->content) }}</textarea>
                    @error('content')
                        <p class="text-[11px] text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Featured Image -->
                <div class="pt-5 border-t border-slate-100">
                    <div class="flex items-center justify-between mb-2">
                        <div>
                            <span class="block text-xs font-semibold text-slate-800">Cover Image</span>
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
                               value="{{ old('image_url', $blog->image) }}"
                               placeholder="https://... or images/haridwar-temples.jpg"
                               class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-amber-600 focus:ring-1 focus:ring-amber-600" />
                    </div>

                    <div x-show="previewUrl" class="mt-3 flex items-center gap-3">
                        <div class="w-20 h-14 rounded-lg overflow-hidden border border-slate-200 bg-slate-50 shrink-0">
                            <img :src="previewUrl" alt="Preview" class="w-full h-full object-cover">
                        </div>
                        <span class="text-[11px] text-slate-500">Current cover image</span>
                    </div>
                </div>

                <!-- Publishing Controls -->
                <div class="pt-5 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-6">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox"
                                   name="is_published"
                                   value="1"
                                   {{ old('is_published', $blog->is_published) ? 'checked' : '' }}
                                   class="rounded text-amber-600 focus:ring-amber-600 border-slate-300">
                            <span class="text-xs font-medium text-slate-800">Published live</span>
                        </label>

                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox"
                                   name="is_featured"
                                   value="1"
                                   {{ old('is_featured', $blog->is_featured) ? 'checked' : '' }}
                                   class="rounded text-amber-600 focus:ring-amber-600 border-slate-300">
                            <span class="text-xs font-medium text-slate-800">Feature as Hero Spotlight</span>
                        </label>
                    </div>

                    <div class="flex items-center gap-3">
                        <a href="{{ route('admin.blogs.index') }}" class="px-4 py-2 rounded-lg border border-slate-300 text-slate-700 hover:bg-slate-50 transition text-xs font-medium">
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

<x-admin-layout title="Create Category">
    <x-slot name="header">
        Categories
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <div class="mb-6">
            <h1 class="text-xl font-semibold text-slate-900">Add New Offering Category</h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Define taxonomy name, parent hierarchy, thumbnail image, and visibility.
            </p>
        </div>

        <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-xs">
            <form method="POST" action="{{ route('admin.categories.store') }}" enctype="multipart/form-data" class="space-y-5 text-sm"
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
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Parent Category</label>
                    <select name="parent_id" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-sm text-slate-800 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500">
                        <option value="">None (Top-Level / Root Category)</option>
                        @foreach ($rootCategories as $rcat)
                            <option value="{{ $rcat->id }}" {{ old('parent_id') == $rcat->id ? 'selected' : '' }}>
                                Child of: {{ $rcat->name }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-slate-500 mt-1">Leave empty if this is a top-level category.</p>
                    @error('parent_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Name, Icon, Slug -->
                <div class="grid grid-cols-1 sm:grid-cols-12 gap-4">
                    <div class="sm:col-span-6">
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Category Name *</label>
                        <input type="text"
                               name="name"
                               value="{{ old('name') }}"
                               required
                               placeholder="e.g. Hawan Samagri"
                               class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500" />
                        @error('name') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Icon</label>
                        <input type="text"
                               name="icon"
                               value="{{ old('icon', '🪔') }}"
                               class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-sm text-center text-slate-900 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500" />
                        @error('icon') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-4">
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Slug (Optional)</label>
                        <input type="text"
                               name="slug"
                               value="{{ old('slug') }}"
                               placeholder="auto-generated"
                               class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500" />
                        @error('slug') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Short Description</label>
                    <textarea name="description"
                              rows="2"
                              placeholder="Brief description of the items in this collection..."
                              class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500">{{ old('description') }}</textarea>
                    @error('description') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Media Upload -->
                <div class="pt-4 border-t border-slate-200">
                    <div class="flex items-center justify-between mb-2">
                        <div>
                            <span class="block text-sm font-medium text-slate-700">Category Photo</span>
                            <span class="block text-xs text-slate-500">Thumbnail displayed in circular collections</span>
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
                               placeholder="https://... or images/har-ki-pauri-temples.jpg"
                               class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500" />
                        @error('image_url') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div x-show="previewUrl" class="mt-3 flex items-center gap-3">
                        <div class="w-12 h-12 rounded-lg overflow-hidden border border-slate-200 bg-slate-50 shrink-0">
                            <img :src="previewUrl" alt="Preview" class="w-full h-full object-cover">
                        </div>
                        <span class="text-xs text-slate-500">Selected image preview</span>
                    </div>
                </div>

                <!-- Sort Order & Settings -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-4 border-t border-slate-200">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Display Sort Order</label>
                        <input type="number"
                               name="sort_order"
                               value="{{ old('sort_order', $nextSortOrder) }}"
                               class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-sm text-slate-900 font-mono focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500" />
                    </div>

                    <div class="space-y-2 pt-2 sm:pt-6">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox"
                                   name="is_featured"
                                   value="1"
                                   {{ old('is_featured') ? 'checked' : '' }}
                                   class="rounded text-amber-600 focus:ring-amber-500 border-slate-300">
                            <span class="text-sm text-slate-700">Feature in Homepage stories</span>
                        </label>

                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox"
                                   name="is_active"
                                   value="1"
                                   {{ old('is_active', true) ? 'checked' : '' }}
                                   class="rounded text-amber-600 focus:ring-amber-500 border-slate-300">
                            <span class="text-sm text-slate-700">Active & visible in catalog</span>
                        </label>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-end gap-3 pt-5 border-t border-slate-200">
                    <a href="{{ route('admin.categories.index') }}" class="px-4 py-2 rounded-lg border border-slate-300 text-slate-700 hover:bg-slate-50 transition text-sm font-medium">Cancel</a>
                    <button type="submit" class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white font-medium text-sm rounded-lg shadow-xs transition">
                        Save Category
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>

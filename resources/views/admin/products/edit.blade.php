<x-admin-layout title="Edit Product">
    <x-slot name="header">
        Products
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-xl font-semibold text-slate-900">Edit Product: {{ $product->name }}</h1>
                <p class="text-xs text-slate-500 mt-0.5">Update pricing, inventory, descriptions, and media gallery.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('products.show', $product->slug) }}" target="_blank" class="px-3 py-1.5 rounded-lg border border-amber-300 text-amber-700 hover:bg-amber-50 text-xs font-medium transition flex items-center gap-1.5">
                    <span>View on Store</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                </a>
                <a href="{{ route('admin.products.index') }}" class="text-xs text-slate-500 hover:text-slate-800 transition">
                    ← Back to Products List
                </a>
            </div>
        </div>

        <div class="bg-white border border-slate-200 rounded-xl p-5 sm:p-7 shadow-xs"
             x-data="{
                 activeTab: 'general',
                 tabsOrder: ['general', 'pricing', 'media', 'details'],
                 previewUrl: '{{ $product->image_url }}',
                 imageSource: '{{ (str_starts_with($product->image ?? '', 'http') || str_starts_with($product->image ?? '', '/')) ? 'url' : 'file' }}',
                 videoSource: 'url',
                 videoUrl: '{{ old('video_url', $product->video_url ?? '') }}',
                 galleryPreviews: [],
                 setTab(tab) {
                     this.activeTab = tab;
                     try {
                         localStorage.setItem('admin_product_form_tab', tab);
                         if (window.location.hash !== '#' + tab) {
                             window.history.replaceState(null, null, '#' + tab);
                         }
                     } catch (e) {}
                 },
                 prevTab() {
                     const idx = this.tabsOrder.indexOf(this.activeTab);
                     if (idx > 0) {
                         this.setTab(this.tabsOrder[idx - 1]);
                     }
                 },
                 nextTab() {
                     const idx = this.tabsOrder.indexOf(this.activeTab);
                     if (idx < this.tabsOrder.length - 1) {
                         this.setTab(this.tabsOrder[idx + 1]);
                     }
                 },
                 handleFileSelect(e) {
                     const file = e.target.files[0];
                     if (file) {
                         this.previewUrl = URL.createObjectURL(file);
                     }
                 },
                 handleGallerySelect(e) {
                     this.galleryPreviews = [];
                     const files = e.target.files;
                     if (files) {
                         for (let i = 0; i < files.length; i++) {
                             this.galleryPreviews.push(URL.createObjectURL(files[i]));
                         }
                     }
                 },
                 init() {
                     const validTabs = ['general', 'pricing', 'media', 'details'];
                     const hash = window.location.hash ? window.location.hash.substring(1) : '';
                     let savedTab = '';
                     try {
                         savedTab = localStorage.getItem('admin_product_form_tab') || '';
                     } catch (e) {}

                     @if ($errors->hasAny(['name', 'sku', 'category_id', 'batch_id', 'is_active', 'is_featured', 'is_bestseller']))
                         this.setTab('general');
                     @elseif ($errors->hasAny(['price', 'compare_price', 'member_price', 'stock', 'badge', 'volume_ml', 'is_gangajal', 'is_member_free_eligible']))
                         this.setTab('pricing');
                     @elseif ($errors->hasAny(['image_file', 'image_url', 'gallery_files', 'gallery_files.*', 'gallery_urls', 'video_file', 'video_url', 'remove_video']))
                         this.setTab('media');
                     @elseif ($errors->hasAny(['short_description', 'description', 'purity_details', 'benefits']))
                         this.setTab('details');
                     @else
                         if (validTabs.includes(hash)) {
                             this.setTab(hash);
                         } else if (validTabs.includes(savedTab)) {
                             this.setTab(savedTab);
                         } else {
                             this.setTab('general');
                         }
                     @endif
                 }
             }">

            <!-- Tab Navigation Bar -->
            <div class="border-b border-slate-200 mb-6">
                <nav class="flex space-x-1 sm:space-x-3 overflow-x-auto no-scrollbar -mb-px" aria-label="Product Tabs">
                    <!-- Tab 1: General Info -->
                    <button type="button"
                            @click="setTab('general')"
                            :class="activeTab === 'general' ? 'border-amber-600 text-amber-700 bg-amber-50/70 font-semibold' : 'border-transparent text-slate-500 hover:text-slate-800 hover:border-slate-300 font-medium'"
                            class="flex items-center gap-2 py-3 px-3.5 border-b-2 text-xs sm:text-sm rounded-t-lg transition whitespace-nowrap">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        <span>1. General Info</span>
                        @if ($errors->hasAny(['name', 'sku', 'category_id', 'batch_id', 'is_active', 'is_featured', 'is_bestseller']))
                            <span class="w-2 h-2 rounded-full bg-rose-500" title="Validation errors in this tab"></span>
                        @endif
                    </button>

                    <!-- Tab 2: Pricing & Stock -->
                    <button type="button"
                            @click="setTab('pricing')"
                            :class="activeTab === 'pricing' ? 'border-amber-600 text-amber-700 bg-amber-50/70 font-semibold' : 'border-transparent text-slate-500 hover:text-slate-800 hover:border-slate-300 font-medium'"
                            class="flex items-center gap-2 py-3 px-3.5 border-b-2 text-xs sm:text-sm rounded-t-lg transition whitespace-nowrap">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>2. Pricing & Stock</span>
                        @if ($errors->hasAny(['price', 'compare_price', 'member_price', 'stock', 'badge', 'volume_ml', 'is_gangajal', 'is_member_free_eligible']))
                            <span class="w-2 h-2 rounded-full bg-rose-500" title="Validation errors in this tab"></span>
                        @endif
                    </button>

                    <!-- Tab 3: Media & Gallery -->
                    <button type="button"
                            @click="setTab('media')"
                            :class="activeTab === 'media' ? 'border-amber-600 text-amber-700 bg-amber-50/70 font-semibold' : 'border-transparent text-slate-500 hover:text-slate-800 hover:border-slate-300 font-medium'"
                            class="flex items-center gap-2 py-3 px-3.5 border-b-2 text-xs sm:text-sm rounded-t-lg transition whitespace-nowrap">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span>3. Media & Gallery</span>
                        @if ($errors->hasAny(['image_file', 'image_url', 'gallery_files', 'gallery_files.*', 'gallery_urls', 'video_file', 'video_url', 'remove_video']))
                            <span class="w-2 h-2 rounded-full bg-rose-500" title="Validation errors in this tab"></span>
                        @endif
                    </button>

                    <!-- Tab 4: Descriptions & Details -->
                    <button type="button"
                            @click="setTab('details')"
                            :class="activeTab === 'details' ? 'border-amber-600 text-amber-700 bg-amber-50/70 font-semibold' : 'border-transparent text-slate-500 hover:text-slate-800 hover:border-slate-300 font-medium'"
                            class="flex items-center gap-2 py-3 px-3.5 border-b-2 text-xs sm:text-sm rounded-t-lg transition whitespace-nowrap">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span>4. Descriptions & Details</span>
                        @if ($errors->hasAny(['short_description', 'description', 'purity_details', 'benefits']))
                            <span class="w-2 h-2 rounded-full bg-rose-500" title="Validation errors in this tab"></span>
                        @endif
                    </button>
                </nav>
            </div>

            <form method="POST" action="{{ route('admin.products.update', $product->id) }}" enctype="multipart/form-data" class="space-y-6 text-sm">
                @csrf
                @method('PUT')

                <!-- TAB 1: GENERAL INFORMATION -->
                <div x-show="activeTab === 'general'" x-cloak class="space-y-6">
                    <div>
                        <h2 class="text-sm font-semibold text-slate-900 mb-1">General Information</h2>
                        <p class="text-xs text-slate-500 mb-4">Update product category, identity name, SKU identifier, and store visibility.</p>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1.5">Category *</label>
                                <select name="category_id" required class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-sm text-slate-800 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500">
                                    @foreach ($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
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
                                        <option value="{{ $batch->id }}" {{ old('batch_id', $product->batch_id) == $batch->id ? 'selected' : '' }}>
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
                                       value="{{ old('name', $product->name) }}"
                                       required
                                       class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-sm text-slate-900 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500" />
                                @error('name') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1.5">SKU</label>
                                <input type="text"
                                       name="sku"
                                       value="{{ old('sku', $product->sku) }}"
                                       class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-sm text-slate-900 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500" />
                            </div>
                        </div>
                    </div>

                    <div class="pt-5 border-t border-slate-200">
                        <label class="block text-sm font-semibold text-slate-900 mb-3">Store Visibility & Catalog Badging</label>
                        <div class="flex flex-wrap items-center gap-6">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }} class="rounded text-amber-600 focus:ring-amber-500 border-slate-300">
                                <span class="text-sm text-slate-700 font-medium">Active for sale</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }} class="rounded text-amber-600 focus:ring-amber-500 border-slate-300">
                                <span class="text-sm text-slate-700 font-medium">Feature on homepage</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="is_bestseller" value="1" {{ old('is_bestseller', $product->is_bestseller) ? 'checked' : '' }} class="rounded text-amber-600 focus:ring-amber-500 border-slate-300">
                                <span class="text-sm text-slate-700 font-medium">Mark as bestseller</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- TAB 2: PRICING & INVENTORY -->
                <div x-show="activeTab === 'pricing'" x-cloak class="space-y-6">
                    <div>
                        <h2 class="text-sm font-semibold text-slate-900 mb-1">Pricing & Inventory</h2>
                        <p class="text-xs text-slate-500 mb-4">Configure retail price, strike-through MRP, stock units, and membership privileges.</p>

                        <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1.5">Sale Price (INR) *</label>
                                <input type="number"
                                       step="0.01"
                                       name="price"
                                       value="{{ old('price', $product->price) }}"
                                       required
                                       class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-sm text-slate-900 font-mono focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500" />
                                @error('price') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1.5">Compare Price (MRP)</label>
                                <input type="number"
                                       step="0.01"
                                       name="compare_price"
                                       value="{{ old('compare_price', $product->compare_price) }}"
                                       class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-sm text-slate-900 font-mono focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500" />
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1.5">Member Price</label>
                                <input type="number"
                                       step="0.01"
                                       name="member_price"
                                       value="{{ old('member_price', $product->member_price) }}"
                                       class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-sm text-slate-900 font-mono focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500" />
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1.5">Stock Count *</label>
                                <input type="number"
                                       name="stock"
                                       value="{{ old('stock', $product->stock) }}"
                                       required
                                       class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-sm text-slate-900 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1.5">Promotional Badge</label>
                                <input type="text"
                                       name="badge"
                                       value="{{ old('badge', $product->badge) }}"
                                       placeholder="e.g. 20% OFF or Best Value"
                                       class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-sm text-slate-900 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500" />
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1.5">Volume (ml)</label>
                                <input type="number"
                                       name="volume_ml"
                                       value="{{ old('volume_ml', $product->volume_ml) }}"
                                       class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-sm text-slate-900 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500" />
                            </div>
                        </div>

                        <div class="pt-5 border-t border-slate-200 mt-5">
                            <label class="block text-sm font-semibold text-slate-900 mb-3">Product Classification Toggles</label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <label class="flex items-start gap-2.5 p-3 rounded-lg border border-slate-200 bg-slate-50/50 hover:bg-slate-50 cursor-pointer transition">
                                    <input type="checkbox"
                                           name="is_gangajal"
                                           value="1"
                                           {{ old('is_gangajal', $product->is_gangajal) ? 'checked' : '' }}
                                           class="rounded text-amber-600 focus:ring-amber-500 border-slate-300 mt-0.5">
                                    <div>
                                        <span class="text-sm font-semibold text-slate-800 block">Sacred Gangajal</span>
                                        <span class="text-xs text-slate-500">Subject to Gangajal batch lab reports and verification seals.</span>
                                    </div>
                                </label>

                                <label class="flex items-start gap-2.5 p-3 rounded-lg border border-slate-200 bg-slate-50/50 hover:bg-slate-50 cursor-pointer transition">
                                    <input type="checkbox"
                                           name="is_member_free_eligible"
                                           value="1"
                                           {{ old('is_member_free_eligible', $product->is_member_free_eligible) ? 'checked' : '' }}
                                           class="rounded text-amber-600 focus:ring-amber-500 border-slate-300 mt-0.5">
                                    <div>
                                        <span class="text-sm font-semibold text-slate-800 block">Monthly Member Free Eligible</span>
                                        <span class="text-xs text-slate-500">Allowed for subscriber free monthly quota claiming.</span>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TAB 3: MEDIA & GALLERY -->
                <div x-show="activeTab === 'media'" x-cloak class="space-y-6">
                    <div>
                        <h2 class="text-sm font-semibold text-slate-900 mb-1">Product Media & Gallery</h2>
                        <p class="text-xs text-slate-500 mb-4">Manage primary cover photo, additional gallery images, and product showcase video.</p>
                    </div>

                    <!-- 1. Primary Cover Photo -->
                    <div class="bg-slate-50/70 border border-slate-200 rounded-xl p-4">
                        <div class="flex items-center justify-between mb-2">
                            <div>
                                <label class="text-xs font-bold text-slate-800 uppercase tracking-wider">Primary Cover Image *</label>
                                <span class="block text-[11px] text-slate-500">Main photo shown across catalog cards and cart</span>
                            </div>
                            <div class="flex rounded-md bg-white border border-slate-200 p-0.5 text-xs shadow-2xs">
                                <button type="button"
                                        @click="imageSource = 'file'"
                                        :class="imageSource === 'file' ? 'bg-amber-100 text-[#D97706] font-bold' : 'text-slate-600'"
                                        class="px-2.5 py-1 rounded transition">
                                    Upload File
                                </button>
                                <button type="button"
                                        @click="imageSource = 'url'"
                                        :class="imageSource === 'url' ? 'bg-amber-100 text-[#D97706] font-bold' : 'text-slate-600'"
                                        class="px-2.5 py-1 rounded transition">
                                    Direct URL
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
                                   value="{{ old('image_url', $product->image) }}"
                                   placeholder="https://... or /images/products/ghee-diya-batti.jpg"
                                   class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-sm text-slate-900 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500" />
                            <p class="text-xs text-slate-500 mt-1">Stored path: <code>{{ $product->image ?: 'None' }}</code></p>
                            @error('image_url') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div x-show="previewUrl" class="mt-3 flex items-center gap-3">
                            <div class="w-16 h-16 rounded-lg overflow-hidden border border-slate-300 bg-white shrink-0 shadow-xs">
                                <img :src="previewUrl" alt="Current Preview" class="w-full h-full object-cover">
                            </div>
                            <span class="text-xs text-slate-600 font-medium">Cover photo</span>
                        </div>
                    </div>

                    <!-- 2. Additional Gallery Images (Multiple Photos) -->
                    <div class="bg-slate-50/70 border border-slate-200 rounded-xl p-4 space-y-4">
                        <div>
                            <label class="text-xs font-bold text-slate-800 uppercase tracking-wider">Additional Gallery Photos</label>
                            <span class="block text-[11px] text-slate-500">Thumbnail carousel photos displayed on the product details page</span>
                        </div>

                        <!-- Existing Gallery Images -->
                        @if (!empty($product->gallery) && is_array($product->gallery) && count($product->gallery) > 0)
                            <div>
                                <span class="text-xs font-semibold text-slate-700 block mb-2">Existing Gallery Photos (Uncheck to remove):</span>
                                <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-5 gap-3">
                                    @foreach ($product->gallery as $index => $item)
                                        @php
                                            $itemUrl = (str_starts_with($item, 'http') || str_starts_with($item, '/')) ? $item : (Storage::disk('public')->exists($item) ? Storage::disk('public')->url($item) : asset($item));
                                        @endphp
                                        <label class="relative group cursor-pointer block border border-slate-200 rounded-xl p-1 bg-white hover:border-amber-400 transition shadow-2xs">
                                            <div class="w-full aspect-square rounded-lg overflow-hidden bg-slate-50">
                                                <img src="{{ $itemUrl }}" class="w-full h-full object-cover">
                                            </div>
                                            <div class="mt-1.5 flex items-center justify-between px-1">
                                                <span class="text-[10px] text-slate-500 truncate max-w-[80px]">Photo #{{ $index + 1 }}</span>
                                                <input type="checkbox"
                                                       name="keep_gallery[]"
                                                       value="{{ $item }}"
                                                       checked
                                                       class="rounded text-amber-600 focus:ring-amber-500 border-slate-300"
                                                       title="Keep this image">
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Add New Gallery Photos -->
                        <div class="pt-2 border-t border-slate-200">
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Add New Gallery Files</label>
                            <input type="file"
                                   name="gallery_files[]"
                                   multiple
                                   accept="image/*"
                                   @change="handleGallerySelect($event)"
                                   class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border file:border-slate-300 file:text-xs file:font-medium file:bg-white file:text-slate-700 hover:file:bg-slate-50 file:cursor-pointer" />
                            <span class="text-[11px] text-slate-400 mt-0.5 block">You can select multiple files to append to the gallery (JPG, PNG, WebP up to 5MB each)</span>
                            @error('gallery_files.*') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div x-show="galleryPreviews.length > 0" class="pt-2">
                            <span class="text-xs font-semibold text-slate-700 block mb-2">New Gallery Previews:</span>
                            <div class="flex flex-wrap gap-2">
                                <template x-for="(img, idx) in galleryPreviews" :key="idx">
                                    <div class="w-16 h-16 rounded-lg border border-slate-300 overflow-hidden bg-white shadow-xs">
                                        <img :src="img" class="w-full h-full object-cover">
                                    </div>
                                </template>
                            </div>
                        </div>

                        <div class="pt-2 border-t border-slate-200">
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Or Append Gallery Image URLs (one per line)</label>
                            <textarea name="gallery_urls"
                                      rows="2"
                                      placeholder="https://example.com/extra-photo.jpg&#10;/images/products/sample.jpg"
                                      class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs font-mono text-slate-900 placeholder-slate-400 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500">{{ old('gallery_urls') }}</textarea>
                        </div>
                    </div>

                    <!-- 3. Product Video -->
                    <div class="bg-slate-50/70 border border-slate-200 rounded-xl p-4 space-y-3">
                        <div class="flex items-center justify-between">
                            <div>
                                <label class="text-xs font-bold text-slate-800 uppercase tracking-wider">Product Video / Sacred Darshan</label>
                                <span class="block text-[11px] text-slate-500">Provide a YouTube / Vimeo URL or upload an MP4 clip</span>
                            </div>
                            <div class="flex rounded-md bg-white border border-slate-200 p-0.5 text-xs shadow-2xs">
                                <button type="button"
                                        @click="videoSource = 'url'"
                                        :class="videoSource === 'url' ? 'bg-amber-100 text-[#D97706] font-bold' : 'text-slate-600'"
                                        class="px-2.5 py-1 rounded transition">
                                    Video URL
                                </button>
                                <button type="button"
                                        @click="videoSource = 'file'"
                                        :class="videoSource === 'file' ? 'bg-amber-100 text-[#D97706] font-bold' : 'text-slate-600'"
                                        class="px-2.5 py-1 rounded transition">
                                    Upload MP4
                                </button>
                            </div>
                        </div>

                        @if ($product->video_url)
                            <div class="p-3 bg-white border border-amber-200 rounded-lg flex items-center justify-between text-xs">
                                <div class="flex items-center gap-2 min-w-0">
                                    <span class="w-6 h-6 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center font-bold text-xs shrink-0">▶</span>
                                    <div class="truncate">
                                        <span class="font-bold text-slate-800 block">Current Product Video:</span>
                                        <span class="text-slate-500 font-mono text-[11px] truncate block">{{ $product->video_url }}</span>
                                    </div>
                                </div>
                                <label class="flex items-center gap-1.5 text-rose-600 font-bold hover:text-rose-700 cursor-pointer shrink-0 ml-3">
                                    <input type="checkbox" name="remove_video" value="1" class="rounded text-rose-600 focus:ring-rose-500 border-slate-300">
                                    <span>Remove</span>
                                </label>
                            </div>
                        @endif

                        <div x-show="videoSource === 'url'">
                            <input type="text"
                                   name="video_url"
                                   x-model="videoUrl"
                                   value="{{ old('video_url', $product->video_url) }}"
                                   placeholder="https://www.youtube.com/watch?v=... or https://youtu.be/... or https://vimeo.com/..."
                                   class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500" />
                            <span class="text-[11px] text-slate-400 mt-1 block">Supports YouTube, Vimeo, or direct MP4 video URLs.</span>
                            @error('video_url') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div x-show="videoSource === 'file'">
                            <input type="file"
                                   name="video_file"
                                   accept="video/mp4,video/quicktime,video/webm"
                                   class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border file:border-slate-300 file:text-xs file:font-medium file:bg-white file:text-slate-700 hover:file:bg-slate-50 file:cursor-pointer" />
                            <span class="text-[11px] text-slate-400 mt-1 block">Upload replacement MP4, MOV, WebM up to 50MB</span>
                            @error('video_file') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- TAB 4: DESCRIPTIONS & DETAILS -->
                <div x-show="activeTab === 'details'" x-cloak class="space-y-6">
                    <div>
                        <h2 class="text-sm font-semibold text-slate-900 mb-1">Descriptions & Sacred Details</h2>
                        <p class="text-xs text-slate-500 mb-4">Detailed specifications, Vedic significance, benefits, and laboratory assay notes.</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Short Summary (Catalog Cards)</label>
                        <input type="text"
                               name="short_description"
                               value="{{ old('short_description', $product->short_description) }}"
                               class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-sm text-slate-900 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Full Product Description</label>
                        <textarea name="description"
                                  rows="4"
                                  class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-sm text-slate-900 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500">{{ old('description', $product->description) }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Purity Notes / Lab Assay</label>
                            <textarea name="purity_details"
                                      rows="3"
                                      class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-sm text-slate-900 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500">{{ old('purity_details', $product->purity_details) }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Devotional Benefits</label>
                            <textarea name="benefits"
                                      rows="3"
                                      class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-sm text-slate-900 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500">{{ old('benefits', $product->benefits) }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Shared Bottom Actions & Step Navigation -->
                <div class="flex items-center justify-between pt-6 border-t border-slate-200 mt-6">
                    <div class="flex items-center gap-2">
                        <button type="button"
                                x-show="activeTab !== 'general'"
                                @click="prevTab()"
                                class="px-3.5 py-2 rounded-lg border border-slate-300 text-slate-700 hover:bg-slate-50 transition text-xs sm:text-sm font-medium flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                            <span>Previous Step</span>
                        </button>
                        <a href="{{ route('admin.products.index') }}" class="px-3.5 py-2 rounded-lg border border-transparent text-slate-500 hover:text-slate-800 hover:bg-slate-100 transition text-xs sm:text-sm font-medium">Cancel</a>
                    </div>

                    <div class="flex items-center gap-3">
                        <button type="button"
                                x-show="activeTab !== 'details'"
                                @click="nextTab()"
                                class="px-4 py-2 rounded-lg bg-slate-800 hover:bg-slate-900 text-white transition text-xs sm:text-sm font-medium flex items-center gap-1.5 shadow-xs">
                            <span>Next Step</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>

                        <button type="submit" class="px-5 py-2 bg-amber-600 hover:bg-amber-700 text-white font-medium text-xs sm:text-sm rounded-lg shadow-xs transition flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Update Product</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>

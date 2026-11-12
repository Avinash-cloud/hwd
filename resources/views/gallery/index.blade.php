<x-app-layout>
    <div x-data="{
        activeModal: false,
        modalTitle: '',
        modalCaption: '',
        modalLocation: '',
        modalImage: '',
        modalVideoUrl: '',
        modalType: 'image',
        openModal(item) {
            this.modalTitle = item.title;
            this.modalCaption = item.caption || '';
            this.modalLocation = item.location || '';
            this.modalImage = item.imageUrl;
            this.modalVideoUrl = item.videoUrl || '';
            this.modalType = item.mediaType;
            this.activeModal = true;
        },
        closeModal() {
            this.activeModal = false;
            this.modalVideoUrl = '';
        }
    }">
        <!-- 1. Hero Header -->
        <section class="relative bg-gradient-to-b from-amber-900/10 via-amber-500/5 to-transparent pt-12 pb-10 border-b border-amber-200/40">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-widest bg-amber-100 text-amber-900 border border-amber-300 mb-3">
                    <span>Sacred Darshan & Provenance</span>
                </span>
                <h1 class="font-serif font-extrabold text-3xl sm:text-5xl text-slate-900 tracking-tight">
                    Sacred Haridwar Darshan Gallery
                </h1>
                <p class="max-w-2xl mx-auto text-sm sm:text-base text-slate-600 mt-3 leading-relaxed">
                    Witness the sacred beauty of Haridwar Maha Aarti, dawn Ganga water collection ceremonies, and our hermetically sealed bottling process.
                </p>

                <!-- Filter Tabs -->
                <div class="flex items-center justify-center gap-2 flex-wrap mt-8">
                    <a href="{{ route('gallery.index') }}"
                       class="px-4 py-1.5 rounded-full text-xs font-bold transition {{ !request('category') && !request('type') ? 'bg-[#881337] text-white shadow-xs' : 'bg-white hover:bg-amber-50 text-slate-700 border border-amber-200' }}">
                        All Media
                    </a>
                    <a href="{{ route('gallery.index', ['type' => 'image']) }}"
                       class="px-4 py-1.5 rounded-full text-xs font-bold transition {{ request('type') === 'image' ? 'bg-[#881337] text-white shadow-xs' : 'bg-white hover:bg-amber-50 text-slate-700 border border-amber-200' }}">
                        📷 Photos Only
                    </a>
                    <a href="{{ route('gallery.index', ['type' => 'video']) }}"
                       class="px-4 py-1.5 rounded-full text-xs font-bold transition {{ request('type') === 'video' ? 'bg-[#881337] text-white shadow-xs' : 'bg-white hover:bg-amber-50 text-slate-700 border border-amber-200' }}">
                        🎥 Videos & Aarti
                    </a>
                    @foreach ($categories as $cat)
                        <a href="{{ route('gallery.index', ['category' => $cat]) }}"
                           class="px-4 py-1.5 rounded-full text-xs font-bold transition {{ request('category') === $cat ? 'bg-[#881337] text-white shadow-xs' : 'bg-white hover:bg-amber-50 text-slate-700 border border-amber-200' }}">
                            {{ $cat }}
                        </a>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- 2. Gallery Grid -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            @if ($items->isEmpty())
                <div class="text-center py-20 bg-white rounded-3xl border border-dashed border-amber-300 p-8">
                    <span class="text-4xl block mb-2">📸</span>
                    <h3 class="font-serif font-bold text-lg text-slate-800">No media items found</h3>
                    <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">
                        Clear active filters to view all photographs and Aarti recordings.
                    </p>
                    <a href="{{ route('gallery.index') }}" class="inline-block mt-4 px-4 py-2 bg-[#D97706] text-white text-xs font-bold rounded-xl">
                        View Complete Gallery
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach ($items as $item)
                        @php
                            $jsonItem = json_encode([
                                'title' => $item->title,
                                'caption' => $item->caption,
                                'location' => $item->location,
                                'imageUrl' => $item->image_url,
                                'videoUrl' => $item->video_url,
                                'mediaType' => $item->media_type,
                            ]);
                        @endphp
                        <div class="group relative bg-white rounded-2xl overflow-hidden border border-amber-200/70 shadow-xs hover:shadow-lg transition flex flex-col justify-between cursor-pointer"
                             @click='openModal({!! $jsonItem !!})'>
                            <!-- Thumbnail Area -->
                            <div class="relative h-60 overflow-hidden bg-slate-100">
                                <img src="{{ $item->image_url }}"
                                     alt="{{ $item->title }}"
                                     class="w-full h-full object-cover transform group-hover:scale-105 transition duration-500">

                                <!-- Type Badge & Overlay -->
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-80 group-hover:opacity-90 transition"></div>

                                <div class="absolute top-3 left-3">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-white/95 text-[#D97706] shadow-xs">
                                        {{ $item->category }}
                                    </span>
                                </div>

                                @if ($item->media_type === 'video')
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <div class="w-12 h-12 rounded-full bg-amber-500/90 text-white flex items-center justify-center shadow-lg transform group-hover:scale-110 transition">
                                            <svg class="w-5 h-5 ml-0.5" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M6.3 2.841A1.5 1.5 0 004 4.11v11.78a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/>
                                            </svg>
                                        </div>
                                    </div>
                                @endif

                                <!-- Bottom Info in Overlay -->
                                <div class="absolute bottom-3 left-3 right-3 text-white">
                                    <h3 class="font-serif font-bold text-sm leading-snug line-clamp-2 drop-shadow">
                                        {{ $item->title }}
                                    </h3>
                                    <span class="text-[11px] text-amber-200 flex items-center gap-1 mt-1 font-medium">
                                        <svg class="w-3 h-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <span class="truncate">{{ $item->location }}</span>
                                    </span>
                                </div>
                            </div>

                            <!-- Small Caption Bar -->
                            @if ($item->caption)
                                <div class="p-3 text-[11px] text-slate-600 line-clamp-2 leading-relaxed bg-white">
                                    {{ $item->caption }}
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if ($items->hasPages())
                    <div class="mt-12">
                        {{ $items->links() }}
                    </div>
                @endif
            @endif
        </div>

        <!-- 3. Devotee Call to Action Section -->
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
            <div class="bg-gradient-to-r from-amber-900 to-slate-900 rounded-3xl p-8 sm:p-12 text-white text-center relative overflow-hidden shadow-xl">
                <div class="relative z-10 max-w-2xl mx-auto space-y-4">
                    <span class="text-xs font-bold uppercase tracking-widest text-amber-300">Direct From Sacred Haridwar</span>
                    <h2 class="font-serif font-extrabold text-2xl sm:text-4xl leading-tight">
                        Experience Divine Sanctity at Your Home Altar
                    </h2>
                    <p class="text-slate-300 text-xs sm:text-sm leading-relaxed">
                        Join our 5-Year Sacred Devotee Membership to receive regular ₹0 monthly shipments of certified, untouched Haridwar Gangajal.
                    </p>
                    <div class="pt-4 flex flex-wrap items-center justify-center gap-4">
                        <a href="{{ route('membership.join') }}"
                           class="px-6 py-3 bg-[#D97706] hover:bg-amber-600 text-white font-bold text-xs sm:text-sm rounded-xl transition shadow-lg transform hover:-translate-y-0.5">
                            Join Devotee Membership (₹500)
                        </a>
                        <a href="{{ route('products.index') }}"
                           class="px-6 py-3 bg-white/10 hover:bg-white/20 text-white font-bold text-xs sm:text-sm rounded-xl transition border border-white/20">
                            Explore Sacred Catalog →
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- 4. Alpine.js Interactive Lightbox Modal -->
        <div x-show="activeModal"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-xs"
             style="display: none;"
             @keydown.escape.window="closeModal()">
            <div class="bg-white rounded-2xl overflow-hidden max-w-3xl w-full shadow-2xl relative"
                 @click.away="closeModal()">
                <!-- Close Button -->
                <button type="button"
                        @click="closeModal()"
                        class="absolute top-3 right-3 z-10 w-8 h-8 rounded-full bg-slate-900/60 hover:bg-slate-900 text-white flex items-center justify-center transition">
                    ✕
                </button>

                <!-- Media Viewer -->
                <div class="bg-black max-h-[70vh] flex items-center justify-center overflow-hidden">
                    <template x-if="modalType === 'video' && modalVideoUrl">
                        <div class="w-full aspect-video">
                            <iframe :src="modalVideoUrl.includes('watch?v=') ? modalVideoUrl.replace('watch?v=', 'embed/') : modalVideoUrl"
                                    class="w-full h-full"
                                    frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                    allowfullscreen></iframe>
                        </div>
                    </template>
                    <template x-if="modalType === 'image' || !modalVideoUrl">
                        <img :src="modalImage" :alt="modalTitle" class="w-full max-h-[70vh] object-contain">
                    </template>
                </div>

                <!-- Media Description -->
                <div class="p-6">
                    <div class="flex items-center justify-between text-xs text-slate-500 mb-1">
                        <span class="font-bold text-[#D97706]" x-text="modalLocation"></span>
                        <span class="uppercase font-semibold" x-text="modalType"></span>
                    </div>
                    <h3 class="font-serif font-bold text-xl text-slate-900" x-text="modalTitle"></h3>
                    <p class="text-xs text-slate-600 mt-2 leading-relaxed" x-text="modalCaption"></p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

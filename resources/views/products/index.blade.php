<x-app-layout>
    <div class="bg-[#FFFDF7] border-b border-[#D97706]/30 py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <span class="text-xs font-bold uppercase tracking-widest text-[#D97706] font-mono">Devotional Offerings</span>
            <h1 class="text-3xl sm:text-4xl font-serif font-extrabold text-slate-900 mt-1">Sacred Catalog & Pooja Essentials</h1>
            <p class="text-sm text-slate-600 mt-2 max-w-2xl">
                Authentic Brahmakund Gangajal, consecrated brass ware, and natural Himalayan incense blessed at Har Ki Pauri, Haridwar.
            </p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <!-- Filter & Search Bar -->
        <div class="flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-4 mb-8">
            <!-- Category Pills -->
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('products.index') }}" class="px-4 py-2 rounded-xl text-xs font-bold transition {{ !request()->has('category') && !request()->has('type') ? 'bg-[#D97706] text-white shadow-xs' : 'bg-white text-slate-700 border border-[#D97706]/30 hover:bg-[#FFFDF7]' }}">
                    All Offerings
                </a>
                <a href="{{ route('products.index', ['type' => 'gangajal']) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition {{ request()->get('type') === 'gangajal' ? 'bg-[#D97706] text-white shadow-xs' : 'bg-white text-slate-700 border border-[#D97706]/30 hover:bg-[#FFFDF7]' }}">
                    Sacred Gangajal Only
                </a>
                @foreach ($categories as $category)
                    <a href="{{ route('products.index', ['category' => $category->slug]) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition {{ request()->get('category') === $category->slug ? 'bg-[#D97706] text-white shadow-xs' : 'bg-white text-slate-700 border border-[#D97706]/30 hover:bg-[#FFFDF7]' }}">
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>

            <!-- Search and Sort -->
            <form method="GET" action="{{ route('products.index') }}" class="flex items-center gap-2">
                @if (request()->has('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                @if (request()->has('type'))
                    <input type="hidden" name="type" value="{{ request('type') }}">
                @endif
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search sacred items..." class="px-3.5 py-2 text-xs border border-[#D97706]/40 rounded-xl focus:ring-[#D97706] focus:border-[#D97706] w-48 sm:w-64" />
                <button type="submit" class="px-4 py-2 bg-[#D97706] hover:bg-[#F97316] text-white text-xs font-bold rounded-xl shadow-xs transition">
                    Search
                </button>
            </form>
        </div>

        <!-- Non-Member Reminder Banner -->
        @if (!Auth::check() || !Auth::user()->hasActiveMembership())
            <div class="mb-8 p-4 rounded-2xl bg-[#FFFDF7] border border-[#D97706]/30 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs shadow-xs">
                <div class="flex items-center gap-3">
                    <span class="text-xl">🕉️</span>
                    <div>
                        <strong class="font-bold text-slate-900 block sm:inline">Exclusive Member Privileges:</strong>
                        <span class="text-slate-700">Non-members may browse our catalog. To place orders and receive your free monthly Gangajal bottle, join our 5-Year Sacred Membership for ₹{{ number_format($membershipFee) }}.</span>
                    </div>
                </div>
                <a href="{{ route('membership.join') }}" class="shrink-0 px-4 py-2 bg-[#D97706] hover:bg-[#F97316] text-white font-bold rounded-xl shadow-xs transition">
                    Join Membership →
                </a>
            </div>
        @endif

        <!-- Products Grid -->
        @if ($products->isEmpty())
            <div class="text-center py-20 bg-white rounded-3xl border border-[#D97706]/30 p-8">
                <p class="text-lg font-serif font-bold text-slate-800">No sacred offerings match your query.</p>
                <p class="text-xs text-slate-500 mt-2">Try clearing your filters or search terms.</p>
                <a href="{{ route('products.index') }}" class="mt-4 inline-block px-4 py-2 bg-[#D97706] text-white text-xs font-bold rounded-xl">
                    Reset Filters
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($products as $product)
                    <div class="bg-white rounded-3xl border border-[#D97706]/20 shadow-sm overflow-hidden flex flex-col justify-between hover:shadow-md transition group">
                        <div class="p-6">
                            @if ($product->is_gangajal)
                                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-extrabold bg-[#D97706]/10 text-[#D97706] border border-[#D97706]/30 mb-3">
                                    <span>🕉️ Brahmakund Certified</span>
                                    <span>·</span>
                                    <span>{{ $product->volume_ml }}ml</span>
                                </div>
                            @else
                                <span class="inline-block text-[10px] uppercase font-bold text-[#D97706] tracking-wider mb-3">
                                    {{ $product->category->name }}
                                </span>
                            @endif

                            <div class="w-full h-44 bg-stone-100/70 rounded-2xl flex items-center justify-center mb-5 overflow-hidden">
                                <div class="text-center p-4">
                                    <div class="w-12 h-12 mx-auto mb-1 text-[#D97706]">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-full h-full" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-.778.099-1.533.284-2.253" />
                                        </svg>
                                    </div>
                                    <span class="text-[11px] font-bold text-slate-700">{{ $product->sku }}</span>
                                </div>
                            </div>

                            <h2 class="font-serif font-bold text-lg text-slate-900 group-hover:text-[#D97706] transition leading-snug mb-2">
                                <a href="{{ route('products.show', $product->slug) }}">{{ $product->name }}</a>
                            </h2>

                            <p class="text-xs text-slate-600 line-clamp-2 leading-relaxed mb-4">
                                {{ $product->short_description }}
                            </p>

                            <div class="bg-[#FFFDF7] p-3 rounded-xl border border-[#D97706]/20 text-xs space-y-1">
                                <div class="flex justify-between">
                                    <span class="text-slate-600">Standard Price:</span>
                                    <span class="font-bold text-slate-800">₹{{ number_format($product->price, 2) }}</span>
                                </div>
                                <div class="flex justify-between font-bold text-[#881337]">
                                    <span>Member Price:</span>
                                    @if ($product->is_gangajal)
                                        <span class="text-[#0369A1]">₹0 (1st Bottle / Mo)</span>
                                    @else
                                        <span class="text-[#0369A1]">₹{{ number_format($product->member_price ?? $product->price, 2) }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="p-6 pt-0">
                            <a href="{{ route('products.show', $product->slug) }}" class="w-full inline-flex items-center justify-center py-2.5 px-4 rounded-xl text-xs font-bold text-white bg-[#D97706] hover:bg-[#F97316] shadow-xs transition">
                                Inspect Sanctity & Order
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-12">
                {{ $products->links() }}
            </div>
        @endif
    </div>
</x-app-layout>

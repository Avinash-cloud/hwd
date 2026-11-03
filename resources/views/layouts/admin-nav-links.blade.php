<nav class="p-3 space-y-4 text-xs font-medium">
    <!-- Operations Group -->
    <div>
        <span class="block px-3 pb-1 text-[11px] uppercase font-semibold tracking-wider text-slate-400">
            Operations
        </span>
        <div class="space-y-0.5">
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.dashboard') ? 'bg-amber-50 text-amber-900 font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('admin.dashboard') ? 'text-amber-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('admin.orders.index') }}"
               class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.orders.*') ? 'bg-amber-50 text-amber-900 font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('admin.orders.*') ? 'text-amber-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
                <span>Orders & Dispatch</span>
            </a>

            <a href="{{ route('admin.batches.index') }}"
               class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.batches.*') ? 'bg-amber-50 text-amber-900 font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('admin.batches.*') ? 'text-amber-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
                <span>Batches & Traceability</span>
            </a>
        </div>
    </div>

    <!-- Catalog Group -->
    <div>
        <span class="block px-3 pb-1 text-[11px] uppercase font-semibold tracking-wider text-slate-400">
            Catalog & Content
        </span>
        <div class="space-y-0.5">
            <a href="{{ route('admin.products.index') }}"
               class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.products.*') ? 'bg-amber-50 text-amber-900 font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('admin.products.*') ? 'text-amber-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                <span>Products & Inventory</span>
            </a>

            <a href="{{ route('admin.reviews.index') }}"
               class="flex items-center justify-between px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.reviews.*') ? 'bg-amber-50 text-amber-900 font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                <div class="flex items-center gap-2.5">
                    <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('admin.reviews.*') ? 'text-amber-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                    </svg>
                    <span>Product Reviews</span>
                </div>
                @php
                    $navPendingReviews = \App\Models\Review::where('status', 'pending')->count();
                @endphp
                @if($navPendingReviews > 0)
                    <span class="px-1.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-600 text-white leading-none">{{ $navPendingReviews }}</span>
                @endif
            </a>

            <a href="{{ route('admin.categories.index') }}"
               class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.categories.*') ? 'bg-amber-50 text-amber-900 font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('admin.categories.*') ? 'text-amber-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
                <span>Categories & Stories</span>
            </a>

            <a href="{{ route('admin.sliders.index') }}"
               class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.sliders.*') ? 'bg-amber-50 text-amber-900 font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('admin.sliders.*') ? 'text-amber-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span>Hero Sliders</span>
            </a>

            <a href="{{ route('admin.blogs.index') }}"
               class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.blogs.*') ? 'bg-amber-50 text-amber-900 font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('admin.blogs.*') ? 'text-amber-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                </svg>
                <span>Blogs & Wisdom</span>
            </a>

            <a href="{{ route('admin.gallery.index') }}"
               class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.gallery.*') ? 'bg-amber-50 text-amber-900 font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('admin.gallery.*') ? 'text-amber-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                </svg>
                <span>Sacred Gallery</span>
            </a>
        </div>
    </div>

    <!-- Management Group -->
    <div>
        <span class="block px-3 pb-1 text-[11px] uppercase font-semibold tracking-wider text-slate-400">
            Management
        </span>
        <div class="space-y-0.5">
            <a href="{{ route('admin.memberships.index') }}"
               class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.memberships.*') && !request()->routeIs('admin.membership-benefits.*') ? 'bg-amber-50 text-amber-900 font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('admin.memberships.*') && !request()->routeIs('admin.membership-benefits.*') ? 'text-amber-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <span>5-Yr Memberships</span>
            </a>

            <a href="{{ route('admin.membership-benefits.index') }}"
               class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.membership-benefits.*') ? 'bg-amber-50 text-amber-900 font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('admin.membership-benefits.*') ? 'text-amber-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                </svg>
                <span>Monthly Benefits</span>
            </a>

            <a href="{{ route('admin.coupons.index') }}"
               class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.coupons.*') ? 'bg-amber-50 text-amber-900 font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('admin.coupons.*') ? 'text-amber-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                </svg>
                <span>Coupons & Promo</span>
            </a>

            <a href="{{ route('admin.contact-inquiries.index') }}"
               class="flex items-center justify-between px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.contact-inquiries.*') ? 'bg-amber-50 text-amber-900 font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                <div class="flex items-center gap-2.5">
                    <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('admin.contact-inquiries.*') ? 'text-amber-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                    </svg>
                    <span>Contact Queries</span>
                </div>
                @php
                    $navUnreadInquiries = \App\Models\ContactInquiry::where('status', 'unread')->count();
                @endphp
                @if($navUnreadInquiries > 0)
                    <span class="px-1.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-600 text-white leading-none">{{ $navUnreadInquiries }}</span>
                @endif
            </a>

            <a href="{{ route('admin.settings.index') }}"
               class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.settings.*') ? 'bg-amber-50 text-amber-900 font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('admin.settings.*') ? 'text-amber-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span>Pricing Rules</span>
            </a>

            <a href="{{ route('admin.reports.index') }}"
               class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.reports.*') ? 'bg-amber-50 text-amber-900 font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('admin.reports.*') ? 'text-amber-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span>Reports & GST Export</span>
            </a>
        </div>
    </div>
</nav>

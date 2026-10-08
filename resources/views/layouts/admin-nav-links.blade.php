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
        </div>
    </div>

    <!-- Management Group -->
    <div>
        <span class="block px-3 pb-1 text-[11px] uppercase font-semibold tracking-wider text-slate-400">
            Management
        </span>
        <div class="space-y-0.5">
            <a href="{{ route('admin.memberships.index') }}"
               class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.memberships.*') ? 'bg-amber-50 text-amber-900 font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('admin.memberships.*') ? 'text-amber-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <span>5-Yr Memberships</span>
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

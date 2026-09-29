<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-900">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? 'Admin Console' }} - Haridwar Bliss</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=bebas-neue:400|poppins:400,500,600,700,800|figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-slate-100 min-h-full flex">
        <!-- Admin Sidebar -->
        <aside class="w-64 bg-slate-950 border-r border-slate-800 flex flex-col justify-between shrink-0 hidden md:flex min-h-screen">
            <div>
                <!-- Brand Header -->
                <div class="h-18 flex items-center gap-3 px-6 border-b border-slate-800/80">
                    <x-application-logo class="w-9 h-9 rounded-xl shadow" />
                    <div>
                        <span class="block font-serif font-bold text-lg text-white">Haridwar Bliss</span>
                        <span class="block text-[10px] uppercase font-bold tracking-widest text-[#D97706]">Admin Console</span>
                    </div>
                </div>

                <!-- Navigation Links -->
                <nav class="p-4 space-y-1.5 text-sm font-medium">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('admin.dashboard') ? 'bg-[#D97706]/20 text-[#D97706] font-bold border border-[#D97706]/40' : 'text-slate-400 hover:text-white hover:bg-slate-900' }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        Dashboard
                    </a>

                    <a href="{{ route('admin.orders.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('admin.orders.*') ? 'bg-[#D97706]/20 text-[#D97706] font-bold border border-[#D97706]/40' : 'text-slate-400 hover:text-white hover:bg-slate-900' }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        Orders & Dispatch
                    </a>

                    <a href="{{ route('admin.batches.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('admin.batches.*') ? 'bg-[#D97706]/20 text-[#D97706] font-bold border border-[#D97706]/40' : 'text-slate-400 hover:text-white hover:bg-slate-900' }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        Batches & Traceability
                    </a>

                    <a href="{{ route('admin.products.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('admin.products.*') ? 'bg-[#D97706]/20 text-[#D97706] font-bold border border-[#D97706]/40' : 'text-slate-400 hover:text-white hover:bg-slate-900' }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                        Products & Inventory
                    </a>

                    <a href="{{ route('admin.memberships.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('admin.memberships.*') ? 'bg-[#D97706]/20 text-[#D97706] font-bold border border-[#D97706]/40' : 'text-slate-400 hover:text-white hover:bg-slate-900' }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        5-Yr Memberships
                    </a>

                    <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('admin.settings.*') ? 'bg-[#D97706]/20 text-[#D97706] font-bold border border-[#D97706]/40' : 'text-slate-400 hover:text-white hover:bg-slate-900' }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Pricing & Settings
                    </a>

                    <a href="{{ route('admin.reports.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('admin.reports.*') ? 'bg-[#D97706]/20 text-[#D97706] font-bold border border-[#D97706]/40' : 'text-slate-400 hover:text-white hover:bg-slate-900' }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Reports & GST Export
                    </a>
                </nav>
            </div>

            <!-- Footer links -->
            <div class="p-4 border-t border-slate-800 space-y-2 text-xs">
                <a href="{{ route('home') }}" class="flex items-center gap-2 text-slate-400 hover:text-[#D97706] transition py-1.5">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                    View Live Public Site
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left flex items-center gap-2 text-[#991B1B] hover:text-[#F97316] transition py-1.5">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        Sign Out of Admin
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden bg-slate-900">
            <!-- Top Mobile Bar -->
            <header class="h-16 bg-slate-950 border-b border-slate-800 flex items-center justify-between px-4 sm:px-8">
                <div class="flex items-center gap-3">
                    <span class="text-xs font-bold uppercase tracking-widest text-[#D97706] bg-[#D97706]/10 border border-[#D97706]/30 px-2.5 py-1 rounded-md">
                        Haridwar Bliss Admin
                    </span>
                    <h1 class="text-base font-bold text-white hidden sm:inline">{{ $header ?? 'Overview' }}</h1>
                </div>

                <div class="flex items-center gap-4 text-xs">
                    <span class="text-slate-400">Logged in as: <strong class="text-white">{{ Auth::user()->name }}</strong></span>
                    <a href="{{ route('home') }}" class="px-3 py-1 rounded-lg bg-slate-800 text-slate-300 hover:text-white border border-slate-700 transition">
                        Back to Store
                    </a>
                </div>
            </header>

            <!-- Alerts -->
            <div class="px-4 sm:px-8 pt-6 max-w-7xl mx-auto w-full">
                @if (session('success'))
                    <div class="rounded-xl bg-[#0369A1]/20 border border-[#0369A1]/40 p-4 mb-4 text-sm text-[#0369A1]">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="rounded-xl bg-[#991B1B]/20 border border-[#991B1B]/40 p-4 mb-4 text-sm text-[#991B1B]">
                        {{ session('error') }}
                    </div>
                @endif
            </div>

            <!-- Page Body -->
            <main class="flex-1 overflow-y-auto px-4 sm:px-8 py-6 max-w-7xl mx-auto w-full">
                {{ $slot }}
            </main>
        </div>
    </body>
</html>

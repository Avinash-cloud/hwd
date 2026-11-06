<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? 'Admin Console' }} - Haridwar Bliss</title>
        <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=poppins:400,500,600,700&display=swap" rel="stylesheet" />
        <link href="https://fonts.cdnfonts.com/css/liberation-serif" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="admin-shell font-sans antialiased text-slate-800 bg-slate-50 min-h-full flex selection:bg-amber-600 selection:text-white"
          x-data="{ mobileSidebarOpen: false }">

        <!-- Mobile Sidebar Backdrop -->
        <div x-show="mobileSidebarOpen"
             x-transition:enter="transition-opacity ease-linear duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-slate-900/40 z-40 lg:hidden"
             @click="mobileSidebarOpen = false"
             style="display: none;"></div>

        <!-- Mobile Drawer Sidebar -->
        <div x-show="mobileSidebarOpen"
             x-transition:enter="transition ease-in-out duration-200 transform"
             x-transition:enter-start="-translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in-out duration-200 transform"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="-translate-x-full"
             class="fixed inset-y-0 left-0 w-64 bg-white border-r border-slate-200 z-50 flex flex-col justify-between lg:hidden shadow-lg"
             style="display: none;">
            <div>
                <!-- Brand Header -->
                <div class="h-14 flex items-center justify-between px-5 border-b border-slate-200">
                    <div class="flex items-center gap-2.5">
                        <x-application-logo class="w-8 h-8 rounded-lg object-cover ring-1 ring-slate-200" />
                        <div>
                            <span class="block font-semibold text-sm text-slate-900 leading-tight">Blu Bliss</span>
                            <span class="block text-[11px] text-slate-500 font-medium">Admin Portal</span>
                        </div>
                    </div>
                    <button @click="mobileSidebarOpen = false" class="text-slate-400 hover:text-slate-600 p-1 rounded-md">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Navigation List -->
                @include('layouts.admin-nav-links')
            </div>

            <!-- Mobile Footer -->
            <div class="p-4 border-t border-slate-200 space-y-1 text-xs">
                <a href="{{ route('home') }}" class="flex items-center gap-2 px-3 py-2 text-slate-600 hover:text-slate-900 hover:bg-slate-100 rounded-lg transition font-medium">
                    <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                    <span>View Storefront ↗</span>
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 text-slate-600 hover:text-red-600 hover:bg-red-50 rounded-lg transition font-medium">
                        <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        <span>Sign Out</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Desktop Admin Sidebar -->
        <aside class="w-60 bg-white border-r border-slate-200 flex flex-col justify-between shrink-0 hidden lg:flex min-h-screen z-20">
            <div class="overflow-y-auto">
                <!-- Brand Header -->
                <div class="h-14 flex items-center gap-2.5 px-5 border-b border-slate-200 bg-white">
                    <x-application-logo class="w-8 h-8 rounded-lg object-cover ring-1 ring-slate-200" />
                    <div class="min-w-0">
                        <span class="block font-semibold text-sm text-slate-900 leading-tight truncate">Blu Bliss</span>
                        <span class="block text-[11px] text-slate-500 font-medium">Admin Portal</span>
                    </div>
                </div>

                <!-- Navigation List -->
                @include('layouts.admin-nav-links')
            </div>

            <!-- Desktop Footer -->
            <div class="p-3.5 border-t border-slate-200 bg-white text-xs space-y-2">
                <!-- Logged in Admin Profile snippet -->
                <div class="flex items-center gap-2.5 px-2.5 py-2 rounded-lg bg-slate-50 border border-slate-200">
                    <div class="w-7 h-7 rounded-md bg-amber-600 text-white font-medium text-xs flex items-center justify-center shrink-0">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <span class="block text-xs font-semibold text-slate-900 truncate">{{ Auth::user()->name }}</span>
                        <span class="block text-[11px] text-slate-500">Administrator</span>
                    </div>
                </div>

                <div class="flex items-center justify-between px-1">
                    <a href="{{ route('home') }}" class="flex items-center gap-1.5 text-slate-600 hover:text-slate-900 transition text-xs font-medium py-1">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                        <span>Storefront ↗</span>
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center gap-1 text-slate-500 hover:text-red-600 transition text-xs font-medium py-1">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0 overflow-y-auto bg-slate-50 min-h-screen">
            <!-- Top Bar -->
            <header class="sticky top-0 z-10 bg-white border-b border-slate-200 h-14 flex items-center justify-between px-4 sm:px-8">
                <div class="flex items-center gap-3">
                    <!-- Mobile Hamburger -->
                    <button @click="mobileSidebarOpen = true"
                            class="p-1.5 -ml-1.5 rounded-md text-slate-500 hover:text-slate-700 hover:bg-slate-100 lg:hidden"
                            aria-label="Open navigation menu">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>

                    <div class="flex items-center gap-2 text-xs text-slate-500 font-medium">
                        <span>Admin</span>
                        <span>/</span>
                        <span class="text-slate-800 font-semibold">{{ $header ?? 'Console' }}</span>
                    </div>
                </div>

                <div class="flex items-center gap-4 text-xs">
                    <a href="{{ route('home') }}"
                       target="_blank"
                       class="hidden sm:inline-flex items-center gap-1.5 text-slate-600 hover:text-slate-900 transition font-medium">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                        <span>Visit Store ↗</span>
                    </a>

                    <div class="flex items-center gap-2 pl-3 border-l border-slate-200">
                        <div class="w-6 h-6 rounded-full bg-slate-200 text-slate-700 font-medium text-[11px] flex items-center justify-center">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <span class="text-slate-700 font-medium hidden md:inline">{{ Auth::user()->name }}</span>
                    </div>
                </div>
            </header>

            <!-- Alerts -->
            <div class="px-4 sm:px-8 pt-6 max-w-7xl mx-auto w-full">
                @if (session('success'))
                    <div class="rounded-lg bg-emerald-50 border border-emerald-200 p-3.5 mb-5 text-sm text-emerald-800 flex items-center gap-2.5">
                        <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <span class="font-medium text-xs sm:text-sm">{{ session('success') }}</span>
                    </div>
                @endif

                @if (session('warning'))
                    <div class="rounded-lg bg-amber-50 border border-amber-200 p-3.5 mb-5 text-sm text-amber-800 flex items-center gap-2.5">
                        <svg class="w-4 h-4 text-amber-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        <span class="font-medium text-xs sm:text-sm">{{ session('warning') }}</span>
                    </div>
                @endif

                @if (session('error'))
                    <div class="rounded-lg bg-red-50 border border-red-200 p-3.5 mb-5 text-sm text-red-800 flex items-center gap-2.5">
                        <svg class="w-4 h-4 text-red-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                        <span class="font-medium text-xs sm:text-sm">{{ session('error') }}</span>
                    </div>
                @endif
            </div>

            <!-- Page Body -->
            <main class="flex-1 px-4 sm:px-8 py-6 max-w-7xl mx-auto w-full">
                {{ $slot }}
            </main>
        </div>
    </body>
</html>

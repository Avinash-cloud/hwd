<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Haridwar Bliss') }} - Sacred Portal</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-slate-900 antialiased bg-[#FFFDF7] min-h-full flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md text-center">
            <a href="/" class="inline-flex flex-col items-center group transition">
                <x-application-logo class="w-14 h-14 shadow-lg rounded-2xl transition transform group-hover:scale-105" />
                <span class="mt-3 text-2xl font-extrabold tracking-tight text-slate-900 font-serif">Haridwar Bliss</span>
                <span class="text-xs uppercase font-bold tracking-widest text-[#D97706] mt-0.5">Sacred Brahmakund Gangajal & Vedic Essentials</span>
            </a>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md px-4 sm:px-0">
            <div class="bg-white py-8 px-6 shadow-xl shadow-slate-900/5 border border-[#D97706]/30 rounded-2xl sm:px-10">
                {{ $slot }}
            </div>

            <div class="mt-6 text-center">
                <a href="/" class="inline-flex items-center text-sm font-medium text-slate-500 hover:text-[#D97706] transition">
                    <svg class="w-4 h-4 mr-1.5 transition-transform group-hover:-translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Haridwar Bliss Homepage
                </a>
            </div>
        </div>
    </body>
</html>

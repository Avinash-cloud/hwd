@props([
    'badge' => null,
    'title' => '',
    'subtitle' => null,
    'bandBg' => 'bg-[#FFA028]',
])

<div class="{{ $bandBg }} border-b border-orange-600/40 py-8 sm:py-12 relative overflow-hidden">
    <!-- Subtle sacred aura glows -->
    <div class="absolute -top-24 -right-24 w-72 h-72 rounded-full bg-amber-300/20 blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-24 -left-24 w-72 h-72 rounded-full bg-sky-200/20 blur-3xl pointer-events-none"></div>

    <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Signature Maroon Hero Card (Matches Membership Plan card) -->
        <div class="bg-[#881337] border border-amber-300/40 rounded-2xl sm:rounded-3xl p-6 sm:p-10 shadow-xl text-center relative overflow-hidden space-y-4">
            
            {{-- Breadcrumb slot --}}
            @if (isset($breadcrumbs))
                <div class="flex items-center justify-center gap-2 text-xs text-amber-200/90 font-medium overflow-x-auto whitespace-nowrap">
                    {{ $breadcrumbs }}
                </div>
            @endif

            {{-- Optional Badge / Pill --}}
            @if ($badge || isset($badgeSlot))
                <div class="flex justify-center">
                    @if (isset($badgeSlot))
                        {{ $badgeSlot }}
                    @else
                        <div class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full text-xs font-bold bg-amber-400/20 text-[#FDE68A] border border-amber-300/40 shadow-xs">
                            <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                            <span>{{ $badge }}</span>
                        </div>
                    @endif
                </div>
            @endif

            {{-- Main Title --}}
            <h1 class="text-center text-2xl sm:text-4xl lg:text-5xl font-serif font-extrabold text-[#FDE68A] italic leading-tight tracking-tight drop-shadow-sm">
                {{ $title }}
            </h1>

            {{-- Subtitle / Description --}}
            @if ($subtitle || isset($description))
                <div class="text-xs sm:text-base text-white/95 max-w-2xl mx-auto font-medium leading-relaxed drop-shadow-2xs">
                    {{ $description ?? $subtitle }}
                </div>
            @endif

            {{-- Extra slot (e.g. actions, offerings count, language switcher) --}}
            @if (isset($extra))
                <div class="pt-2">
                    {{ $extra }}
                </div>
            @endif

        </div>
    </div>
</div>


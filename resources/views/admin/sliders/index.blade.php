<x-admin-layout title="Manage Hero Sliders">
    <x-slot name="header">
        Homepage Hero Promotional Slides
    </x-slot>

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h2 class="text-xl font-serif font-bold text-white">Homepage Slider Manager ({{ $sliders->total() }})</h2>
            <p class="text-xs text-slate-400 mt-1">
                Customize banners, promotional slogans, background imagery, and call-to-action buttons shown on the public homepage.
            </p>
        </div>
        <a href="{{ route('admin.sliders.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#D97706] hover:bg-[#F97316] text-white font-bold text-xs rounded-xl shadow-md transition">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            <span>+ Add New Slide</span>
        </a>
    </div>

    <div class="bg-slate-950/80 border border-slate-800 rounded-3xl p-6 shadow-sm overflow-x-auto">
        @if ($sliders->isEmpty())
            <div class="text-center py-12 text-slate-400">
                <p class="text-sm">No slides currently exist. Click "+ Add New Slide" above to create one.</p>
            </div>
        @else
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="text-slate-400 border-b border-slate-800 font-mono text-[11px]">
                        <th class="pb-3 w-16">Preview</th>
                        <th class="pb-3">Title & Slogan Badge</th>
                        <th class="pb-3">Call To Action</th>
                        <th class="pb-3 text-center">Order</th>
                        <th class="pb-3 text-center">Status</th>
                        <th class="pb-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80">
                    @foreach ($sliders as $slide)
                        <tr class="hover:bg-slate-900/40 transition">
                            <!-- Image Preview -->
                            <td class="py-3.5 pr-4">
                                <div class="w-20 h-12 rounded-xl overflow-hidden bg-slate-900 border border-slate-700 shadow-xs relative shrink-0">
                                    <img src="{{ $slide->image_url }}"
                                         alt="{{ $slide->title }}"
                                         class="w-full h-full object-cover">
                                </div>
                            </td>

                            <!-- Title & Subtitle Badge -->
                            <td class="py-3.5 pr-4 max-w-sm">
                                @if ($slide->subtitle)
                                    <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#D97706]/20 text-amber-300 border border-[#D97706]/40 mb-1">
                                        {{ $slide->subtitle }}
                                    </span>
                                @endif
                                <h3 class="font-bold text-white text-xs leading-snug line-clamp-1">
                                    {{ $slide->title }}
                                </h3>
                                @if ($slide->description)
                                    <p class="text-[11px] text-slate-400 line-clamp-1 mt-0.5">
                                        {{ $slide->description }}
                                    </p>
                                @endif
                            </td>

                            <!-- CTA Buttons -->
                            <td class="py-3.5 pr-4 space-y-1">
                                @if ($slide->button_text)
                                    <div class="flex items-center gap-1.5 text-[11px] text-slate-300">
                                        <span class="px-1.5 py-0.5 rounded bg-slate-800 font-mono text-[10px] text-amber-400">Primary:</span>
                                        <span class="font-semibold">{{ $slide->button_text }}</span>
                                    </div>
                                @endif
                                @if ($slide->secondary_button_text)
                                    <div class="flex items-center gap-1.5 text-[11px] text-slate-400">
                                        <span class="px-1.5 py-0.5 rounded bg-slate-800 font-mono text-[10px] text-slate-300">Sec:</span>
                                        <span>{{ $slide->secondary_button_text }}</span>
                                    </div>
                                @endif
                                @if (! $slide->button_text && ! $slide->secondary_button_text)
                                    <span class="text-slate-500 italic text-[11px]">No CTA buttons</span>
                                @endif
                            </td>

                            <!-- Sort Order -->
                            <td class="py-3.5 text-center font-mono text-slate-300">
                                <span class="px-2 py-1 rounded-lg bg-slate-900 border border-slate-800">
                                    #{{ $slide->sort_order }}
                                </span>
                            </td>

                            <!-- Status Toggle -->
                            <td class="py-3.5 text-center">
                                <form method="POST" action="{{ route('admin.sliders.toggle', $slide->id) }}" class="inline">
                                    @csrf
                                    <button type="submit"
                                            class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold transition {{ $slide->is_active ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/40 hover:bg-emerald-500/30' : 'bg-slate-800 text-slate-400 border border-slate-700 hover:bg-slate-700' }}"
                                            title="Click to toggle status">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $slide->is_active ? 'bg-emerald-400 animate-pulse' : 'bg-slate-500' }}"></span>
                                        <span>{{ $slide->is_active ? 'Active' : 'Hidden' }}</span>
                                    </button>
                                </form>
                            </td>

                            <!-- Actions -->
                            <td class="py-3.5 text-right space-x-3 whitespace-nowrap">
                                <a href="{{ route('admin.sliders.edit', $slide->id) }}"
                                   class="inline-flex items-center gap-1 text-[#D97706] hover:text-[#F97316] font-bold transition">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    <span>Edit</span>
                                </a>

                                <form method="POST" action="{{ route('admin.sliders.destroy', $slide->id) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            onclick="return confirm('Are you sure you want to delete this slide?')"
                                            class="inline-flex items-center gap-1 text-[#991B1B] hover:text-red-400 font-bold transition">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        <span>Delete</span>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-6">
                {{ $sliders->links() }}
            </div>
        @endif
    </div>
</x-admin-layout>

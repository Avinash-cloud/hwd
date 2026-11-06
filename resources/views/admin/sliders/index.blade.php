<x-admin-layout title="Manage Hero Sliders">
    <x-slot name="header">
        Hero Sliders
    </x-slot>

    <!-- Page Title & Actions -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h1 class="text-xl font-semibold text-slate-900">Hero Sliders ({{ $sliders->total() }})</h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Homepage Slider Manager — Manage promotional banners, background graphics, and call-to-action buttons for the homepage carousel.
            </p>
        </div>
        <a href="{{ route('admin.sliders.create') }}"
           class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-amber-600 hover:bg-amber-700 text-white text-xs font-medium rounded-lg transition shadow-xs">
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            <span>Add Slide</span>
        </a>
    </div>

    <div class="bg-white border border-slate-200 rounded-xl overflow-hidden">
        @if ($sliders->isEmpty())
            <div class="text-center py-12 text-slate-400">
                <p class="text-sm">No slides currently exist. Click "Add Slide" above to create one.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="bg-slate-50/75 border-b border-slate-200 text-slate-600 font-semibold uppercase text-[11px] tracking-wider">
                            <th class="py-2.5 px-4 w-20">Preview</th>
                            <th class="py-2.5 px-4">Title & Subtitle</th>
                            <th class="py-2.5 px-4">Call To Action</th>
                            <th class="py-2.5 px-4 text-center">Order</th>
                            <th class="py-2.5 px-4 text-center">Status</th>
                            <th class="py-2.5 px-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($sliders as $slide)
                            <tr class="hover:bg-slate-50/60 transition">
                                <!-- Image Preview -->
                                <td class="py-3 px-4">
                                    <div class="w-16 h-10 rounded-md overflow-hidden bg-slate-100 border border-slate-200 shrink-0">
                                        <img src="{{ $slide->image_url }}"
                                             alt="{{ $slide->title }}"
                                             class="w-full h-full object-cover">
                                    </div>
                                </td>

                                <!-- Title & Subtitle Badge -->
                                <td class="py-3 px-4 max-w-sm">
                                    @if ($slide->subtitle)
                                        <span class="inline-block px-1.5 py-0.5 rounded text-[10px] font-medium bg-amber-50 text-amber-800 border border-amber-200 mb-0.5">
                                            {{ $slide->subtitle }}
                                        </span>
                                    @endif
                                    <h2 class="font-medium text-slate-900 text-xs leading-snug line-clamp-1">
                                        {{ $slide->title ?: 'Untitled Slide #' . $slide->id }}
                                    </h2>
                                    @if ($slide->description)
                                        <p class="text-[11px] text-slate-500 line-clamp-1 mt-0.5">
                                            {{ $slide->description }}
                                        </p>
                                    @endif
                                </td>

                                <!-- CTA Buttons -->
                                <td class="py-3 px-4 space-y-0.5">
                                    @if ($slide->button_text)
                                        <div class="flex items-center gap-1.5 text-xs text-slate-700">
                                            <span class="text-slate-400 font-mono text-[10px]">Btn:</span>
                                            <span>{{ $slide->button_text }}</span>
                                        </div>
                                    @endif
                                    @if ($slide->secondary_button_text)
                                        <div class="flex items-center gap-1.5 text-xs text-slate-500">
                                            <span class="text-slate-400 font-mono text-[10px]">Sec:</span>
                                            <span>{{ $slide->secondary_button_text }}</span>
                                        </div>
                                    @endif
                                    @if (! $slide->button_text && ! $slide->secondary_button_text)
                                        <span class="text-slate-400 italic text-xs">None</span>
                                    @endif
                                </td>

                                <!-- Sort Order -->
                                <td class="py-3 px-4 text-center font-mono text-slate-500">
                                    #{{ $slide->sort_order }}
                                </td>

                                <!-- Status Toggle -->
                                <td class="py-3 px-4 text-center">
                                    <form method="POST" action="{{ route('admin.sliders.toggle', $slide->id) }}" class="inline">
                                        @csrf
                                        <button type="submit"
                                                class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded text-[10px] font-medium transition {{ $slide->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-500 border border-slate-200' }}"
                                                title="Click to toggle status">
                                            <span class="w-1.5 h-1.5 rounded-full {{ $slide->is_active ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                                            <span>{{ $slide->is_active ? 'Live' : 'Draft' }}</span>
                                        </button>
                                    </form>
                                </td>

                                <!-- Actions -->
                                <td class="py-3 px-4 text-right space-x-2.5 whitespace-nowrap">
                                    <a href="{{ route('admin.sliders.edit', $slide->id) }}" class="text-amber-700 hover:text-amber-800 font-medium">Edit</a>
                                    <form method="POST" action="{{ route('admin.sliders.destroy', $slide->id) }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="text-red-600 hover:text-red-700 font-medium"
                                                onclick="return confirm('Are you sure you want to delete slide: {{ addslashes($slide->title) }}?')">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-3.5 border-t border-slate-200">
                {{ $sliders->links() }}
            </div>
        @endif
    </div>
</x-admin-layout>

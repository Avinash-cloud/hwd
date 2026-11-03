<x-admin-layout title="Contact Inquiries">
    <x-slot name="header">Contact Inquiries</x-slot>

    <!-- Page Header & Filters -->
    <div class="mb-6 space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-xl font-semibold text-slate-900">Devotee Inquiries & Messages</h1>
                <p class="text-xs text-slate-500 mt-0.5">Queries and support requests submitted via the public contact portal.</p>
            </div>

            <!-- Search Form -->
            <form method="GET" action="{{ route('admin.contact-inquiries.index') }}" class="flex items-center gap-2">
                <input type="hidden" name="status" value="{{ request('status') }}">
                <div class="relative">
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Search devotee, subject, email..."
                           class="w-64 pl-9 pr-3 py-1.5 text-xs bg-white border border-slate-300 rounded-lg text-slate-800 placeholder-slate-400 focus:border-amber-600 focus:ring-1 focus:ring-amber-600" />
                    <svg class="w-4 h-4 text-slate-400 absolute left-2.5 top-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <button type="submit" class="px-3.5 py-1.5 bg-slate-900 hover:bg-slate-800 text-white text-xs font-medium rounded-lg transition">
                    Search
                </button>
                @if(request('search'))
                    <a href="{{ route('admin.contact-inquiries.index', ['status' => request('status')]) }}" class="px-2 py-1.5 text-slate-500 hover:text-slate-800 text-xs">
                        Clear
                    </a>
                @endif
            </form>
        </div>

        <!-- Status Filter Tabs -->
        <div class="flex items-center gap-1.5 border-b border-slate-200 pb-3">
            <a href="{{ route('admin.contact-inquiries.index', ['search' => request('search')]) }}"
               class="px-3 py-1.5 rounded-lg text-xs font-medium transition {{ !request('status') ? 'bg-amber-50 text-amber-900 font-semibold border border-amber-200' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                All ({{ $inquiries->total() }})
            </a>
            <a href="{{ route('admin.contact-inquiries.index', ['status' => 'unread', 'search' => request('search')]) }}"
               class="px-3 py-1.5 rounded-lg text-xs font-medium transition flex items-center gap-1.5 {{ request('status') === 'unread' ? 'bg-amber-50 text-amber-900 font-semibold border border-amber-200' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                <span>Unread</span>
                @if($unreadCount > 0)
                    <span class="px-1.5 py-0.2 rounded-full text-[10px] font-bold bg-amber-600 text-white">{{ $unreadCount }}</span>
                @endif
            </a>
            <a href="{{ route('admin.contact-inquiries.index', ['status' => 'read', 'search' => request('search')]) }}"
               class="px-3 py-1.5 rounded-lg text-xs font-medium transition {{ request('status') === 'read' ? 'bg-amber-50 text-amber-900 font-semibold border border-amber-200' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                Read
            </a>
            <a href="{{ route('admin.contact-inquiries.index', ['status' => 'replied', 'search' => request('search')]) }}"
               class="px-3 py-1.5 rounded-lg text-xs font-medium transition {{ request('status') === 'replied' ? 'bg-amber-50 text-amber-900 font-semibold border border-amber-200' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                Replied
            </a>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 font-semibold text-[11px] uppercase tracking-wider">
                        <th class="py-3 px-4">Date</th>
                        <th class="py-3 px-4">Devotee</th>
                        <th class="py-3 px-4">Subject</th>
                        <th class="py-3 px-4">Preview</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($inquiries as $inquiry)
                        <tr class="hover:bg-slate-50/80 transition-colors {{ $inquiry->status === 'unread' ? 'bg-amber-50/30 font-semibold' : '' }}">
                            <td class="py-3 px-4 text-slate-600 whitespace-nowrap">
                                {{ $inquiry->created_at?->format('M d, Y h:i A') ?? 'N/A' }}
                            </td>
                            <td class="py-3 px-4">
                                <div class="text-slate-900">{{ $inquiry->name }}</div>
                                <div class="text-[11px] text-slate-500 font-normal">{{ $inquiry->email }}</div>
                                @if($inquiry->phone)
                                    <div class="text-[10px] text-slate-400 font-mono font-normal">{{ $inquiry->phone }}</div>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-slate-900">
                                <a href="{{ route('admin.contact-inquiries.show', $inquiry->id) }}" class="hover:text-amber-700 hover:underline">
                                    {{ $inquiry->subject }}
                                </a>
                            </td>
                            <td class="py-3 px-4 text-slate-500 font-normal max-w-xs truncate">
                                {{ Str::limit($inquiry->message, 80) }}
                            </td>
                            <td class="py-3 px-4">
                                @if ($inquiry->status === 'unread')
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase bg-amber-100 text-amber-800 border border-amber-300">
                                        Unread
                                    </span>
                                @elseif ($inquiry->status === 'replied')
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        Replied
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-medium uppercase bg-slate-100 text-slate-700 border border-slate-200">
                                        Read
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-right space-x-2 whitespace-nowrap">
                                <a href="{{ route('admin.contact-inquiries.show', $inquiry->id) }}" class="text-amber-700 hover:text-amber-800 font-medium">
                                    View
                                </a>
                                <form method="POST" action="{{ route('admin.contact-inquiries.destroy', $inquiry->id) }}" class="inline" onsubmit="return confirm('Delete this inquiry?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-slate-400 hover:text-rose-600 transition">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-400">
                                No contact inquiries found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($inquiries->hasPages())
            <div class="p-4 border-t border-slate-200 bg-white">
                {{ $inquiries->links() }}
            </div>
        @endif
    </div>
</x-admin-layout>

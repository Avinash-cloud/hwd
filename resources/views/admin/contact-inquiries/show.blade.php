<x-admin-layout title="Inquiry from {{ $inquiry->name }}">
    <x-slot name="header">Inquiry Details</x-slot>

    <!-- Top Navigation / Title -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('admin.contact-inquiries.index') }}" class="text-xs text-slate-500 hover:text-slate-800 transition inline-flex items-center gap-1 font-medium">
                    ← Back to Inquiries
                </a>
            </div>
            <h1 class="text-xl font-semibold text-slate-900">{{ $inquiry->subject }}</h1>
            <p class="text-xs text-slate-500 mt-0.5">Received on {{ $inquiry->created_at?->format('M d, Y \a\t h:i A') ?? 'N/A' }}</p>
        </div>

        <form method="POST" action="{{ route('admin.contact-inquiries.destroy', $inquiry->id) }}" onsubmit="return confirm('Delete this inquiry permanently?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="px-3 py-1.5 bg-white text-rose-600 border border-rose-200 hover:bg-rose-50 rounded-lg text-xs font-medium transition">
                Delete Inquiry
            </button>
        </form>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 text-xs">
        <!-- Message Details (2 cols) -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-xs space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h2 class="text-sm font-semibold text-slate-900">Message Content</h2>
                    @if ($inquiry->status === 'unread')
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-amber-100 text-amber-800 border border-amber-300">
                            Unread
                        </span>
                    @elseif ($inquiry->status === 'replied')
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-emerald-50 text-emerald-700 border border-emerald-200">
                            Replied
                        </span>
                    @else
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-medium uppercase bg-slate-100 text-slate-700 border border-slate-200">
                            Read
                        </span>
                    @endif
                </div>

                <div class="p-4 bg-slate-50 rounded-xl border border-slate-100 text-slate-800 leading-relaxed whitespace-pre-line text-sm">
                    {{ $inquiry->message }}
                </div>

                <div class="pt-2 flex items-center gap-3">
                    <a href="mailto:{{ $inquiry->email }}?subject=Re: {{ rawurlencode($inquiry->subject) }}"
                       class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-lg font-medium text-xs transition inline-flex items-center gap-2">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <span>Reply via Email</span>
                    </a>
                    @if($inquiry->phone)
                        <a href="tel:{{ $inquiry->phone }}"
                           class="px-4 py-2 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 rounded-lg font-medium text-xs transition inline-flex items-center gap-2">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            <span>Call Devotee</span>
                        </a>
                    @endif
                </div>
            </div>

            <!-- Admin Status & Notes Update -->
            <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-xs">
                <h2 class="text-sm font-semibold text-slate-900 pb-3 border-b border-slate-100 mb-4">Internal Workflow & Notes</h2>
                <form method="POST" action="{{ route('admin.contact-inquiries.update', $inquiry->id) }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block font-medium text-slate-700 mb-1">Status</label>
                        <select name="status" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs">
                            <option value="unread" {{ $inquiry->status === 'unread' ? 'selected' : '' }}>Unread</option>
                            <option value="read" {{ $inquiry->status === 'read' ? 'selected' : '' }}>Read</option>
                            <option value="replied" {{ $inquiry->status === 'replied' ? 'selected' : '' }}>Replied</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-medium text-slate-700 mb-1">Admin Notes</label>
                        <textarea name="admin_notes" rows="3" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs"
                                  placeholder="Record response notes or actions taken...">{{ old('admin_notes', $inquiry->admin_notes) }}</textarea>
                    </div>

                    <div class="pt-2 flex justify-end">
                        <button type="submit" class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white font-medium rounded-lg text-xs shadow-xs transition">
                            Save Notes & Status
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Right Side: Devotee Info -->
        <div class="space-y-6">
            <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-xs space-y-3">
                <h2 class="text-sm font-semibold text-slate-900 pb-2 border-b border-slate-100">Devotee Information</h2>
                <div class="space-y-2 text-slate-700">
                    <div>
                        <span class="text-slate-400 block text-[11px]">Full Name</span>
                        <span class="font-medium text-slate-900 text-sm">{{ $inquiry->name }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block text-[11px]">Email Address</span>
                        <a href="mailto:{{ $inquiry->email }}" class="text-amber-700 hover:underline font-medium">
                            {{ $inquiry->email }}
                        </a>
                    </div>
                    <div>
                        <span class="text-slate-400 block text-[11px]">Phone</span>
                        <span class="font-mono text-slate-900">{{ $inquiry->phone ?? 'Not provided' }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block text-[11px]">Submission Time</span>
                        <span class="text-slate-600">{{ $inquiry->created_at?->format('M d, Y h:i A') ?? 'N/A' }}</span>
                    </div>
                    @if($inquiry->replied_at)
                        <div>
                            <span class="text-slate-400 block text-[11px]">Marked Replied At</span>
                            <span class="text-emerald-700">{{ $inquiry->replied_at->format('M d, Y h:i A') }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>

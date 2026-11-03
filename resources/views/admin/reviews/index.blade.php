<x-admin-layout title="Product Reviews">
    <x-slot name="header">Product Reviews</x-slot>

    <div x-data="{
        selectedReviews: [],
        allIds: {{ json_encode($reviews->pluck('id')->toArray()) }},
        toggleAll() {
            if (this.selectedReviews.length === this.allIds.length) {
                this.selectedReviews = [];
            } else {
                this.selectedReviews = [...this.allIds];
            }
        }
    }" class="space-y-4 mb-8">

        <!-- Header & Search -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-xl font-semibold text-slate-900">Devotee Product Reviews</h1>
                <p class="text-xs text-slate-500 mt-0.5">Moderate, verify, and publish customer ratings and feedback from verified purchasers.</p>
            </div>

            <!-- Search Form -->
            <form method="GET" action="{{ route('admin.reviews.index') }}" class="flex items-center gap-2">
                <input type="hidden" name="status" value="{{ $currentStatus }}">
                <div class="relative">
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Search devotee, product, comment..."
                           class="w-64 pl-9 pr-3 py-1.5 text-xs bg-white border border-slate-300 rounded-lg text-slate-800 placeholder-slate-400 focus:border-amber-600 focus:ring-1 focus:ring-amber-600" />
                    <svg class="w-4 h-4 text-slate-400 absolute left-2.5 top-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <button type="submit" class="px-3.5 py-1.5 bg-slate-900 hover:bg-slate-800 text-white text-xs font-medium rounded-lg transition">
                    Search
                </button>
                @if(request('search'))
                    <a href="{{ route('admin.reviews.index', ['status' => $currentStatus]) }}" class="px-2 py-1.5 text-slate-500 hover:text-slate-800 text-xs">
                        Clear
                    </a>
                @endif
            </form>
        </div>

        <!-- Status Filter Tabs -->
        <div class="flex flex-wrap items-center gap-2 border-b border-slate-200 pb-3 text-xs">
            <!-- Pending Reviews Tab -->
            <a href="{{ route('admin.reviews.index', ['status' => 'pending', 'search' => request('search')]) }}"
               class="px-3 py-1.5 rounded-lg font-medium transition flex items-center gap-1.5 {{ $currentStatus === 'pending' ? 'bg-amber-500 text-white font-bold shadow-xs' : 'text-slate-700 hover:text-slate-900 hover:bg-slate-100 bg-white border border-slate-200' }}">
                <span>Pending Moderation</span>
                <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $currentStatus === 'pending' ? 'bg-white text-amber-700 font-extrabold' : 'bg-amber-100 text-amber-800 font-bold' }}">
                    {{ $counts['pending'] }}
                </span>
            </a>

            <!-- Approved Reviews Tab -->
            <a href="{{ route('admin.reviews.index', ['status' => 'approved', 'search' => request('search')]) }}"
               class="px-3 py-1.5 rounded-lg font-medium transition flex items-center gap-1.5 {{ $currentStatus === 'approved' ? 'bg-slate-900 text-white font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                <span>Approved</span>
                <span class="text-[11px] {{ $currentStatus === 'approved' ? 'text-slate-300' : 'text-slate-400' }}">({{ $counts['approved'] }})</span>
            </a>

            <!-- Rejected Reviews Tab -->
            <a href="{{ route('admin.reviews.index', ['status' => 'rejected', 'search' => request('search')]) }}"
               class="px-3 py-1.5 rounded-lg font-medium transition flex items-center gap-1.5 {{ $currentStatus === 'rejected' ? 'bg-slate-900 text-white font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                <span>Rejected</span>
                <span class="text-[11px] {{ $currentStatus === 'rejected' ? 'text-slate-300' : 'text-slate-400' }}">({{ $counts['rejected'] }})</span>
            </a>

            <!-- All Reviews Tab -->
            <a href="{{ route('admin.reviews.index', ['status' => 'all', 'search' => request('search')]) }}"
               class="px-3 py-1.5 rounded-lg font-medium transition {{ $currentStatus === 'all' ? 'bg-slate-900 text-white font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                All Reviews ({{ $counts['all'] }})
            </a>
        </div>

        <!-- Bulk Action Toolbar -->
        <div x-show="selectedReviews.length > 0"
             x-transition
             x-cloak
             class="p-4 bg-amber-50 border border-amber-300 rounded-xl flex flex-col md:flex-row md:items-center justify-between gap-4 text-xs shadow-sm">
            <div class="flex items-center gap-2">
                <span class="w-6 h-6 rounded-full bg-amber-600 text-white flex items-center justify-center font-bold text-xs" x-text="selectedReviews.length"></span>
                <span class="font-bold text-slate-800">Reviews selected for bulk moderation</span>
            </div>

            <form method="POST" action="{{ route('admin.reviews.bulkUpdate') }}" class="flex flex-wrap items-center gap-3">
                @csrf
                <template x-for="id in selectedReviews" :key="id">
                    <input type="hidden" name="review_ids[]" :value="id">
                </template>

                <select name="bulk_action" required class="text-xs bg-white border border-slate-300 rounded-lg px-3 py-1.5 text-slate-800 focus:ring-1 focus:ring-amber-500">
                    <option value="">Select Bulk Action...</option>
                    <option value="approve">Approve & Publish Selected</option>
                    <option value="reject">Reject Selected</option>
                    <option value="delete">Permanently Delete Selected</option>
                </select>

                <button type="submit"
                        onclick="return confirm('Apply bulk action to selected reviews?')"
                        class="px-4 py-1.5 bg-amber-600 hover:bg-amber-700 text-white font-bold rounded-lg transition shadow-xs">
                    Apply Action
                </button>
            </form>
        </div>

        <!-- Reviews Table Card -->
        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 font-semibold uppercase tracking-wider text-[11px]">
                            <th class="py-3 px-3 w-10 text-center">
                                <input type="checkbox"
                                       @click="toggleAll()"
                                       :checked="selectedReviews.length === allIds.length && allIds.length > 0"
                                       class="w-4 h-4 text-amber-600 rounded border-slate-300 focus:ring-amber-500">
                            </th>
                            <th class="py-3 px-4">Devotee Customer</th>
                            <th class="py-3 px-4">Product</th>
                            <th class="py-3 px-4">Rating & Review</th>
                            <th class="py-3 px-4">Verified Order</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4 text-right">Moderation Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($reviews as $rev)
                            <tr class="hover:bg-slate-50/80 transition-colors {{ $rev->status === 'pending' ? 'bg-amber-50/20' : '' }}">
                                <!-- Checkbox -->
                                <td class="py-3 px-3 text-center">
                                    <input type="checkbox"
                                           :value="{{ $rev->id }}"
                                           x-model="selectedReviews"
                                           class="w-4 h-4 text-amber-600 rounded border-slate-300 focus:ring-amber-500">
                                </td>

                                <!-- Customer -->
                                <td class="py-3 px-4">
                                    <div class="font-semibold text-slate-900">{{ $rev->user->name ?? 'Devotee' }}</div>
                                    <div class="text-[11px] text-slate-500">{{ $rev->user->email ?? '—' }}</div>
                                    @if ($rev->is_verified_purchase)
                                        <div class="mt-1">
                                            <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                                <svg class="w-3 h-3 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                                <span>Verified Purchase</span>
                                            </span>
                                        </div>
                                    @endif
                                </td>

                                <!-- Product -->
                                <td class="py-3 px-4">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $rev->product->image_url }}" alt="{{ $rev->product->name }}" class="w-10 h-10 rounded-lg object-cover border border-slate-200 shrink-0">
                                        <div>
                                            <a href="{{ route('products.show', $rev->product->slug) }}" target="_blank" class="font-bold text-slate-900 hover:text-amber-700 hover:underline line-clamp-1">
                                                {{ $rev->product->name }}
                                            </a>
                                            <span class="text-[11px] text-slate-400 block">{{ $rev->product->category->name ?? 'Sacred Item' }}</span>
                                        </div>
                                    </div>
                                </td>

                                <!-- Rating & Review Content -->
                                <td class="py-3 px-4 max-w-sm">
                                    <div class="flex items-center gap-1 text-amber-500 mb-1">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <svg class="w-3.5 h-3.5 {{ $i <= $rev->rating ? 'fill-current' : 'text-slate-200 fill-current' }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        @endfor
                                        <span class="text-[11px] font-bold text-slate-700 ml-1">({{ $rev->rating }}/5)</span>
                                    </div>

                                    @if ($rev->title)
                                        <h6 class="font-bold text-slate-900 leading-snug">{{ $rev->title }}</h6>
                                    @endif

                                    <p class="text-slate-600 mt-0.5 line-clamp-3 leading-relaxed whitespace-pre-line">{{ $rev->comment }}</p>

                                    <div class="text-[10px] text-slate-400 mt-1">
                                        Submitted {{ $rev->created_at->format('M d, Y h:i A') }}
                                        @if ($rev->approved_at)
                                            · Approved {{ $rev->approved_at->format('M d, Y') }}
                                        @endif
                                    </div>

                                    @if ($rev->admin_notes)
                                        <div class="mt-1 text-[10px] text-rose-700 bg-rose-50 p-1.5 rounded border border-rose-200">
                                            <strong>Moderator Note:</strong> {{ $rev->admin_notes }}
                                        </div>
                                    @endif
                                </td>

                                <!-- Order # -->
                                <td class="py-3 px-4 font-mono">
                                    @if ($rev->order)
                                        <a href="{{ route('admin.orders.show', $rev->order->id) }}" class="text-amber-700 font-bold hover:underline block">
                                            {{ $rev->order->order_number }}
                                        </a>
                                        <span class="text-[10px] text-slate-400 block">{{ $rev->order->created_at->format('M d, Y') }}</span>
                                    @else
                                        <span class="text-slate-400 italic">No order linked</span>
                                    @endif
                                </td>

                                <!-- Status Badge -->
                                <td class="py-3 px-4">
                                    @if ($rev->status === 'pending')
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase bg-amber-50 text-amber-700 border border-amber-300">
                                            Pending
                                        </span>
                                    @elseif ($rev->status === 'approved')
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            Approved
                                        </span>
                                    @elseif ($rev->status === 'rejected')
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-rose-50 text-rose-700 border border-rose-200">
                                            Rejected
                                        </span>
                                    @endif
                                </td>

                                <!-- Moderation Actions -->
                                <td class="py-3 px-4 text-right space-x-1.5 whitespace-nowrap">
                                    @if ($rev->status !== 'approved')
                                        <form method="POST" action="{{ route('admin.reviews.approve', $rev->id) }}" class="inline">
                                            @csrf
                                            <button type="submit"
                                                    class="px-2.5 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-bold text-[11px] shadow-xs transition">
                                                Approve
                                            </button>
                                        </form>
                                    @endif

                                    @if ($rev->status !== 'rejected')
                                        <form method="POST" action="{{ route('admin.reviews.reject', $rev->id) }}" class="inline" onsubmit="return confirm('Reject this review from devotee?');">
                                            @csrf
                                            <button type="submit"
                                                    class="px-2.5 py-1 bg-rose-600 hover:bg-rose-700 text-white rounded-lg font-bold text-[11px] shadow-xs transition">
                                                Reject
                                            </button>
                                        </form>
                                    @endif

                                    <form method="POST" action="{{ route('admin.reviews.destroy', $rev->id) }}" class="inline" onsubmit="return confirm('Delete this review permanently?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="px-2 py-1 text-slate-400 hover:text-rose-700 font-bold text-[11px] transition">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center text-slate-400">
                                    <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center mx-auto mb-3 text-slate-400">
                                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                        </svg>
                                    </div>
                                    <p class="text-sm font-semibold text-slate-700">No reviews found</p>
                                    <p class="text-xs text-slate-500 mt-1">There are no devotee reviews matching this filter.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($reviews->hasPages())
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-200">
                    {{ $reviews->links() }}
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>


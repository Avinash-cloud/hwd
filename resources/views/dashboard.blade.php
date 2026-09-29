<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <span class="text-xs font-bold uppercase tracking-widest text-[#D97706] font-mono">Devotee Portal</span>
                <h1 class="text-2xl font-serif font-extrabold text-slate-900 mt-1">My Account & Sacred Deliveries</h1>
            </div>

            @if ($membership && $membership->isActive())
                <div
                    class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-[#D97706]/10 text-[#D97706] border border-[#D97706]/30 text-xs font-bold">
                    <span class="w-2 h-2 rounded-full bg-[#0369A1] animate-pulse"></span>
                    <span>5-Year Sacred Membership Active</span>
                </div>
            @endif
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-10">
        <!-- Top Membership Card & 1-Click Monthly Order -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Col 1 & 2: Membership Status Card -->
            <div
                class="lg:col-span-2 bg-[#881337] rounded-3xl p-8 text-white shadow-xl relative overflow-hidden flex flex-col justify-between">
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-xs uppercase font-bold tracking-widest text-amber-200 font-mono">Haridwar
                            Bliss Sanctity Pass</span>
                        <span class="text-2xl">🕉️</span>
                    </div>

                    <div>
                        <h3 class="text-2xl sm:text-3xl font-serif font-bold text-white">{{ $user->name }}</h3>
                        <p class="text-xs text-amber-100 mt-0.5">{{ $user->email }} ·
                            {{ $user->phone ?? 'Phone not set' }}</p>
                    </div>

                    @if ($membership && $membership->isActive())
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 pt-4 border-t border-white/20 text-xs">
                            <div>
                                <span class="text-amber-200 block text-[11px]">Started On</span>
                                <strong class="text-white">{{ $membership->starts_at->format('M d, Y') }}</strong>
                            </div>
                            <div>
                                <span class="text-amber-200 block text-[11px]">Valid Until</span>
                                <strong class="text-white">{{ $membership->expires_at->format('M d, Y') }}</strong>
                            </div>
                            <div>
                                <span class="text-amber-200 block text-[11px]">Days Remaining</span>
                                <strong
                                    class="text-[#F97316] font-mono text-sm">{{ (int) now()->diffInDays($membership->expires_at) }}
                                    Days</strong>
                            </div>
                        </div>
                    @else
                        <div class="pt-4 border-t border-white/20 text-xs space-y-3">
                            <p class="text-amber-100">
                                You do not currently have an active 5-year membership. Join today to unlock free monthly
                                Brahmakund Gangajal deliveries.
                            </p>
                            <a href="{{ route('membership.join') }}"
                                class="inline-block px-5 py-2.5 bg-white text-slate-900 hover:bg-[#FFFDF7] rounded-xl font-bold text-xs shadow transition">
                                Activate 5-Year Membership (₹{{ number_format($membershipFee) }}) →
                            </a>
                        </div>
                    @endif
                </div>

                <div
                    class="mt-6 pt-4 border-t border-white/10 flex items-center justify-between text-[11px] text-amber-200">
                    <span>100% Hermetically Sealed · Direct from Har Ki Pauri</span>
                    <span>Brahmakund Consecration</span>
                </div>
            </div>

            <!-- Col 3: 1-Click Monthly Claim Card -->
            <div
                class="bg-white rounded-3xl border border-[#D97706]/30 p-6 sm:p-8 shadow-xs flex flex-col justify-between space-y-4">
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-widest text-[#D97706] font-mono">Monthly
                        Privilege</span>
                    <h3 class="text-xl font-serif font-bold text-slate-900 mt-1">Claim Your Sacred Gangajal</h3>
                    <p class="text-xs text-slate-600 mt-2 leading-relaxed">
                        @if ($membership && $membership->isActive())
                            Your first 500ml Brahmakund Gangajal bottle is <strong>₹0 product price</strong> (only a flat
                            ₹{{ number_format($shippingRate) }} shipping fee applies).
                        @else
                            Available exclusively to active 5-year members.
                        @endif
                    </p>
                </div>

                @if ($monthlyGangajal)
                    @if ($membership && $membership->isActive())
                        <form method="POST" action="{{ route('cart.add') }}">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $monthlyGangajal->id }}">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit"
                                class="w-full py-3.5 px-4 rounded-xl font-bold text-xs text-white bg-[#D97706] hover:bg-[#F97316] shadow-md transition">
                                Claim Monthly Free Bottle (₹0) →
                            </button>
                        </form>
                    @else
                        <a href="{{ route('membership.join') }}"
                            class="w-full text-center block py-3.5 px-4 rounded-xl font-bold text-xs text-white bg-[#D97706] hover:bg-[#F97316] transition">
                            Join for ₹{{ number_format($membershipFee) }} to Claim
                        </a>
                    @endif
                @endif
            </div>
        </div>

        <!-- Orders & Addresses Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            <!-- Left 2 Cols: Recent Orders -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-3xl border border-[#D97706]/30 p-8 shadow-xs space-y-6">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-serif font-bold text-slate-900">Recent Sacred Deliveries</h2>
                        <span class="text-xs text-slate-400">Total: {{ $user->orders()->count() }} orders</span>
                    </div>

                    @if ($orders->isEmpty())
                        <div class="text-center py-10 text-xs text-slate-500">
                            You have not placed any orders yet. Click "Claim Monthly Sacred Gangajal" above to receive your
                            first delivery!
                        </div>
                    @else
                        <div class="divide-y divide-slate-100">
                            @foreach ($orders as $order)
                                <div class="py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                    <div class="space-y-1">
                                        <div class="flex items-center gap-3">
                                            <a href="{{ route('orders.show', $order->order_number) }}"
                                                class="font-mono font-bold text-sm text-[#D97706] hover:underline">
                                                #{{ $order->order_number }}
                                            </a>
                                            <span
                                                class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $order->order_status === 'delivered' ? 'bg-[#0369A1]/10 text-[#0369A1]' : 'bg-[#D97706]/10 text-[#D97706]' }}">
                                                {{ $order->order_status }}
                                            </span>
                                        </div>
                                        <p class="text-xs text-slate-500">
                                            {{ $order->created_at->format('M d, Y') }} · {{ $order->items->count() }} item(s) ·
                                            Batch: {{ $order->batch?->batch_number ?? 'Har Ki Pauri' }}
                                        </p>
                                    </div>

                                    <div class="flex items-center gap-3">
                                        <span
                                            class="font-serif font-bold text-sm text-slate-900">₹{{ number_format($order->total_amount, 2) }}</span>
                                        <a href="{{ route('orders.show', $order->order_number) }}"
                                            class="px-3 py-1.5 bg-[#FFFDF7] hover:bg-[#D97706]/10 text-[#D97706] text-xs font-bold rounded-lg border border-[#D97706]/30 transition">
                                            Details & QR
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right Col: Address Book -->
            <div class="space-y-6">
                <div class="bg-white rounded-3xl border border-[#D97706]/30 p-8 shadow-xs space-y-6">
                    <h3 class="text-lg font-serif font-bold text-slate-900">Saved Delivery Addresses</h3>

                    @if ($addresses->isNotEmpty())
                        <div class="space-y-3">
                            @foreach ($addresses as $addr)
                                <div
                                    class="p-4 rounded-2xl border {{ $addr->is_default ? 'border-[#D97706] bg-[#FFFDF7]' : 'border-slate-200' }} text-xs space-y-1 relative">
                                    @if ($addr->is_default)
                                        <span
                                            class="absolute top-3 right-3 text-[10px] font-bold px-2 py-0.5 rounded bg-[#D97706]/20 text-[#D97706]">Default</span>
                                    @endif
                                    <strong class="font-bold text-slate-900 block">{{ $addr->recipient_name }}</strong>
                                    <p class="text-slate-600">{{ $addr->address_line1 }}</p>
                                    <p class="text-slate-600">{{ $addr->city }}, {{ $addr->state }} - {{ $addr->postal_code }}
                                    </p>
                                    <p class="text-slate-500">Phone: {{ $addr->phone }}</p>

                                    <form method="POST" action="{{ route('addresses.delete', $addr->id) }}" class="pt-2">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-[#991B1B] text-[11px] hover:underline"
                                            onclick="return confirm('Remove address?')">Delete</button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-xs text-slate-400">No saved addresses yet.</p>
                    @endif

                    <!-- Add Address Toggle -->
                    <details class="text-xs">
                        <summary class="font-bold text-[#D97706] cursor-pointer hover:underline">+ Add New Address
                        </summary>
                        <form method="POST" action="{{ route('addresses.store') }}"
                            class="mt-4 space-y-3 pt-4 border-t border-slate-100">
                            @csrf
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Recipient Name</label>
                                <input type="text" name="recipient_name" required
                                    class="w-full text-xs px-3 py-2 border border-slate-300 rounded-xl" />
                            </div>
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Phone</label>
                                <input type="text" name="phone" required
                                    class="w-full text-xs px-3 py-2 border border-slate-300 rounded-xl" />
                            </div>
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Address Line 1</label>
                                <input type="text" name="address_line1" required
                                    class="w-full text-xs px-3 py-2 border border-slate-300 rounded-xl" />
                            </div>
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="block font-semibold text-slate-700 mb-1">City</label>
                                    <input type="text" name="city" required
                                        class="w-full text-xs px-3 py-2 border border-slate-300 rounded-xl" />
                                </div>
                                <div>
                                    <label class="block font-semibold text-slate-700 mb-1">State</label>
                                    <input type="text" name="state" required
                                        class="w-full text-xs px-3 py-2 border border-slate-300 rounded-xl" />
                                </div>
                            </div>
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Postal Code</label>
                                <input type="text" name="postal_code" required
                                    class="w-full text-xs px-3 py-2 border border-slate-300 rounded-xl" />
                            </div>
                            <button type="submit"
                                class="w-full py-2 bg-[#D97706] hover:bg-[#F97316] text-white font-bold text-xs rounded-xl shadow transition">
                                Save Address
                            </button>
                        </form>
                    </details>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
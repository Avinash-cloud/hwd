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
                    <span>Blu Bliss 5-Year Sacred Membership Active</span>
                </div>
            @endif
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-10">
        <!-- Top Membership Card & 1-Click Monthly Order -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Col 1 & 2: Membership Status Card -->
            <div
                class="lg:col-span-2 bg-[#881337] border border-amber-300/40 rounded-3xl p-8 text-white shadow-xl relative overflow-hidden flex flex-col justify-between">
                <div class="relative z-10 space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-xs uppercase font-bold tracking-widest text-amber-200 font-mono">Blu Bliss Sanctity Pass</span>
                        <div class="w-8 h-8 rounded-full bg-white/10 border border-amber-300/30 flex items-center justify-center text-amber-300">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-2xl sm:text-3xl font-serif font-bold text-white">{{ $user->name }}</h3>
                        <p class="text-xs text-amber-100 mt-0.5">{{ $user->email }} ·
                            {{ $user->phone ?? 'Phone not set' }}</p>
                    </div>

                    @if ($membership && $membership->isActive())
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 pt-4 border-t border-white/20 text-xs">
                            <div>
                                <span class="text-amber-200 block text-[11px]">Started On</span>
                                <strong class="text-white">{{ $membership->starts_at->format('M d, Y') }}</strong>
                            </div>
                            <div>
                                <span class="text-amber-200 block text-[11px]">Valid Until</span>
                                <strong class="text-white">{{ $membership->expires_at->format('M d, Y') }}</strong>
                            </div>
                            <div>
                                <span class="text-amber-200 block text-[11px]">5-Year Renewal</span>
                                <strong class="text-emerald-300 font-bold">
                                    @if ($activeSubscription && $activeSubscription->isRazorpayMandate())
                                        Auto-Renewing
                                    @elseif ($activeSubscription && $activeSubscription->isOfflinePrepaid())
                                        Offline Prepaid
                                    @else
                                        Active (Mandate Pending)
                                    @endif
                                </strong>
                            </div>
                            <div>
                                <span class="text-amber-200 block text-[11px]">Days Remaining</span>
                                <strong
                                    class="text-[#F97316] font-mono text-sm">{{ (int) now()->diffInDays($membership->expires_at) }}
                                    Days</strong>
                            </div>
                        </div>

                        <div class="pt-3 flex flex-wrap items-center gap-3">
                            <a href="{{ route('membership.invoice', $membership->id) }}" target="_blank"
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white/15 hover:bg-white/25 text-white text-xs font-semibold backdrop-blur-xs transition">
                                <svg class="w-3.5 h-3.5 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <span>Download Membership Tax Invoice</span>
                            </a>
                            @if ($activeSubscription && $activeSubscription->isRazorpayMandate())
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-emerald-500/20 border border-emerald-400/40 text-emerald-200 text-xs font-semibold">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                    <span>Recurring Auto-Debit Active (₹{{ number_format($activeSubscription->billing_amount, 0) }}/mo)</span>
                                </span>
                            @elseif ($activeSubscription && $activeSubscription->isOfflinePrepaid())
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-amber-400/20 border border-amber-300/40 text-amber-100 text-xs font-semibold">
                                    <span>Offline Prepaid{{ $activeSubscription->prepaid_until ? ' Until ' . $activeSubscription->prepaid_until->format('d M Y') : '' }}</span>
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-amber-400/20 border border-amber-300/40 text-amber-100 text-xs font-semibold">
                                    <span>Offline Member &middot; Mandate Not Linked Yet</span>
                                </span>
                            @endif
                        </div>
                    @else
                        <div class="pt-4 border-t border-white/20 text-xs space-y-3">
                            <p class="text-amber-100">
                                You do not currently have an active 5-year membership. Join today for uninterrupted monthly
                                Haridwar Gangajal doorstep auto-deliveries.
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
                    <span>100% Hermetically Sealed · Sacred Haridwar · Eco-Clean Pledge</span>
                    <span>Brahma Muhurta Consecration</span>
                </div>
            </div>

            <!-- Col 3: 1-Click Monthly Claim Card -->
            <div
                class="bg-white rounded-3xl border border-[#D97706]/30 p-6 sm:p-8 shadow-xs flex flex-col justify-between space-y-4">
                <div>
                    <div class="relative h-28 rounded-2xl overflow-hidden mb-3 border border-amber-200/80">
                        <img src="{{ asset('images/ganga-holy-waters.jpg') }}" alt="Holy Ganga Waters" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent flex items-end p-2.5">
                            <span class="text-[10px] font-bold text-amber-300">Sacred Haridwar Sourced</span>
                        </div>
                    </div>
                    <span class="text-[11px] font-bold uppercase tracking-widest text-[#D97706] font-mono">Monthly
                        Privilege</span>
                    <h3 class="text-xl font-serif font-bold text-slate-900 mt-1">Monthly Gangajal Supply</h3>
                    <p class="text-xs text-slate-600 mt-2 leading-relaxed">
                        @if ($membership && $membership->isActive())
                            Your monthly 500ml pure Haridwar Gangajal supply is billed at <strong>₹{{ number_format($monthlyAutoDebit ?? $shippingRate) }}/month</strong> (including GST).
                        @else
                            Available to 5-year members with dedicated monthly doorstep delivery.
                        @endif
                    </p>
                </div>

                @if ($membership && $membership->isActive())
                    @if ($activeSubscription && $activeSubscription->isRazorpayMandate())
                        <div class="p-3.5 rounded-2xl bg-emerald-50/90 border border-emerald-200/90 space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 uppercase">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                    <span>Auto-Debit Active</span>
                                </span>
                                <span class="text-xs font-bold text-emerald-900 font-mono">₹{{ number_format($activeSubscription->billing_amount, 0) }}/mo</span>
                            </div>
                            <p class="text-[11px] text-emerald-950 leading-relaxed">
                                Uninterrupted monthly 500ml Gangajal supply auto-dispatches directly to your doorstep via NimbusPost.
                            </p>
                            <div class="text-[10px] text-emerald-900/80 pt-1.5 border-t border-emerald-200/70 flex justify-between">
                                <span>Next Auto-Dispatch:</span>
                                <strong>{{ $activeSubscription->next_billing_date?->format('M d, Y') ?? 'Every 30 Days' }}</strong>
                            </div>
                        </div>
                    @else
                        <div class="p-4 rounded-2xl bg-amber-50 border border-amber-300 space-y-3"
                             x-data="{
                                 mandateOpen: false,
                                 termsAccepted: false,
                                 isProcessing: false,
                                 paymentMethod: '{{ ($razorpayIsDummy ?? false) ? 'mock' : 'razorpay' }}',
                                 paymentId: '',
                                 subscriptionId: '',
                                 signature: '',
                                 shippingPhone: '{{ old('shipping_phone', $defaultAddress?->phone ?? $user->phone ?? '') }}',
                                 shippingAddress: '{{ old('shipping_address', $defaultAddress?->address_line1 ?? '') }}',
                                 shippingCity: '{{ old('shipping_city', $defaultAddress?->city ?? '') }}',
                                 shippingState: '{{ old('shipping_state', $defaultAddress?->state ?? '') }}',
                                 shippingPincode: '{{ old('shipping_pincode', $defaultAddress?->postal_code ?? '') }}',
                                 startMandate() {
                                     if (!this.termsAccepted) {
                                         alert('Please accept the recurring delivery terms first.');
                                         return;
                                     }
                                     if (!this.shippingPhone.trim() || !this.shippingAddress.trim() || !this.shippingCity.trim() || !this.shippingState.trim() || !this.shippingPincode.trim()) {
                                         alert('Please complete your doorstep delivery address.');
                                         return;
                                     }
                                     if (this.paymentMethod === 'mock') {
                                         this.paymentId = 'pay_HB_MANDATE_' + Math.random().toString(36).substring(2, 10).toUpperCase();
                                         this.subscriptionId = 'sub_mock_' + Math.random().toString(36).substring(2, 14);
                                         this.signature = 'mock_signature';
                                         this.$nextTick(() => document.getElementById('activate-mandate-form').submit());
                                         return;
                                     }
                                     this.isProcessing = true;
                                     fetch('{{ route('membership.mandate.razorpay.order') }}', {
                                         method: 'POST',
                                         headers: {
                                             'Content-Type': 'application/json',
                                             'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                         }
                                     })
                                     .then(res => res.json())
                                     .then(data => {
                                         if (data.error) {
                                             alert(data.error);
                                             this.isProcessing = false;
                                             return;
                                         }
                                         if (data.is_mock || typeof Razorpay === 'undefined') {
                                             this.paymentId = 'pay_HB_MANDATE_' + Math.random().toString(36).substring(2, 10).toUpperCase();
                                             this.subscriptionId = data.subscription_id;
                                             this.signature = 'mock_signature';
                                             this.$nextTick(() => document.getElementById('activate-mandate-form').submit());
                                             return;
                                         }
                                         const rzp = new Razorpay({
                                             key: data.key,
                                             name: data.name,
                                             description: data.description,
                                             subscription_id: data.subscription_id,
                                             prefill: data.prefill,
                                             theme: { color: '#D97706' },
                                             handler: (response) => {
                                                 this.paymentId = response.razorpay_payment_id;
                                                 this.subscriptionId = response.razorpay_subscription_id || data.subscription_id;
                                                 this.signature = response.razorpay_signature;
                                                 this.$nextTick(() => document.getElementById('activate-mandate-form').submit());
                                             },
                                             modal: { ondismiss: () => { this.isProcessing = false; } }
                                         });
                                         rzp.open();
                                     })
                                     .catch(() => { this.isProcessing = false; });
                                 }
                             }">
                            <div class="flex items-center justify-between">
                                <span class="text-[11px] font-bold uppercase tracking-wider text-[#881337]">
                                    ₹0 Joining Fee &bull; Mandate Setup
                                </span>
                                @if ($activeSubscription && $activeSubscription->hasActivePrepaidCoverage())
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800">
                                        Prepaid till {{ $activeSubscription->prepaid_until->format('d M Y') }}
                                    </span>
                                @endif
                            </div>
                            <p class="text-[11px] text-slate-700 leading-relaxed">
                                Your joining commitment is already active! Link Razorpay AutoPay for automated monthly Gangajal dispatches at ₹{{ number_format($monthlyAutoDebit ?? $shippingRate) }}/mo.
                            </p>
                            <button type="button" @click="mandateOpen = !mandateOpen"
                                    class="w-full py-2.5 px-3 rounded-xl bg-[#881337] hover:bg-[#6B0E29] text-white text-xs font-bold transition shadow-xs">
                                <span x-text="mandateOpen ? 'Hide Auto-Debit Setup' : 'Activate Monthly Auto-Delivery (₹0 Joining Fee)'"></span>
                            </button>

                            <form x-show="mandateOpen" x-cloak id="activate-mandate-form" method="POST" action="{{ route('membership.mandate.activate') }}" class="space-y-2.5 pt-2 border-t border-amber-200 text-xs">
                                @csrf
                                <input type="hidden" name="payment_method" :value="paymentMethod">
                                <input type="hidden" name="razorpay_payment_id" :value="paymentId">
                                <input type="hidden" name="razorpay_subscription_id" :value="subscriptionId">
                                <input type="hidden" name="razorpay_signature" :value="signature">

                                <input type="text" name="shipping_phone" x-model="shippingPhone" required placeholder="Delivery Phone"
                                       class="w-full px-2.5 py-1.5 text-xs bg-white border border-slate-300 rounded-lg" />
                                <input type="text" name="shipping_address" x-model="shippingAddress" required placeholder="Street / House Address"
                                       class="w-full px-2.5 py-1.5 text-xs bg-white border border-slate-300 rounded-lg" />
                                <div class="grid grid-cols-3 gap-1.5">
                                    <input type="text" name="shipping_city" x-model="shippingCity" required placeholder="City"
                                           class="w-full px-2 py-1.5 text-xs bg-white border border-slate-300 rounded-lg" />
                                    <input type="text" name="shipping_state" x-model="shippingState" required placeholder="State"
                                           class="w-full px-2 py-1.5 text-xs bg-white border border-slate-300 rounded-lg" />
                                    <input type="text" name="shipping_pincode" x-model="shippingPincode" required placeholder="PIN"
                                           class="w-full px-2 py-1.5 text-xs bg-white border border-slate-300 rounded-lg font-mono" />
                                </div>
                                <label class="flex items-start gap-2 cursor-pointer text-[11px] text-slate-700">
                                    <input type="checkbox" name="terms_accepted" value="1" x-model="termsAccepted" required class="mt-0.5 rounded text-amber-600 border-slate-300" />
                                    <span>
                                        I authorize recurring ₹{{ number_format($monthlyAutoDebit ?? $shippingRate) }}/mo billing per
                                        <a href="{{ route('payment-delivery-policy') }}" class="text-amber-700 font-semibold underline">Payment &amp; Delivery Policy</a>.
                                    </span>
                                </label>
                                <button type="button" @click="startMandate()" :disabled="isProcessing"
                                        class="w-full py-2.5 px-3 bg-[#D97706] hover:bg-amber-700 text-white font-bold text-xs rounded-xl transition">
                                    <span x-show="!isProcessing">Proceed to Authorize AutoPay &rarr;</span>
                                    <span x-show="isProcessing">Authorizing Mandate...</span>
                                </button>
                            </form>
                        </div>
                    @endif

                    @if ($monthlyGangajal)
                        <form method="POST" action="{{ route('cart.add') }}">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $monthlyGangajal->id }}">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit"
                                class="w-full py-3 px-4 rounded-xl font-bold text-xs text-[#881337] bg-amber-50 hover:bg-amber-100 border border-amber-300 shadow-xs transition flex items-center justify-center gap-1.5">
                                <span>Order Additional Bottle (₹{{ number_format($monthlyGangajal->price, 0) }})</span>
                                <span>→</span>
                            </button>
                        </form>
                    @endif
                @else
                    @if ($monthlyGangajal)
                        <a href="{{ route('membership.join') }}"
                            class="w-full text-center block py-3.5 px-4 rounded-xl font-bold text-xs text-white bg-[#D97706] hover:bg-[#F97316] transition">
                            Join 5-Year Membership (₹{{ number_format($membershipFee) }})
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
                                            Batch: {{ $order->batch?->batch_number ?? 'Haridwar Batch' }}
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

    @if ($membership && $membership->isActive() && (! $activeSubscription || ! $activeSubscription->isRazorpayMandate()))
        <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    @endif
</x-app-layout>
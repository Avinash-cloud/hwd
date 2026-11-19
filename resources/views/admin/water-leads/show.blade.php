<x-admin-layout title="Lead #{{ $lead->lead_number }}">
    <x-slot name="header">Lead #{{ $lead->lead_number }}</x-slot>

    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.water-collection-leads.index') }}" class="text-xs text-slate-500 hover:text-slate-800">
                    ← Back to Kumbh Leads
                </a>
            </div>
            <h1 class="text-xl font-bold text-slate-900 mt-1 flex items-center gap-3">
                <span>Lead #{{ $lead->lead_number }}</span>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold border uppercase tracking-wider {{ $lead->status_badge_classes }}">
                    {{ $lead->status }}
                </span>
            </h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Received on {{ $lead->created_at?->format('l, F j, Y \a\t g:i A') }}
            </p>
        </div>

        @php
            $cleanPhone = preg_replace('/[^0-9]/', '', $lead->mobile);
            $whatsAppMsg = urlencode("Jai Gange! Calling from Haridwar Bliss Sewa regarding your Kumbh Water Collection request Ref #{$lead->lead_number}.");
        @endphp
        <div class="flex items-center gap-2">
            <a href="https://wa.me/{{ $cleanPhone }}?text={{ $whatsAppMsg }}" target="_blank"
               class="px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-lg shadow-xs transition flex items-center gap-1.5">
                <span>💬 WhatsApp Devotee</span>
            </a>
            <a href="tel:{{ $cleanPhone }}"
               class="px-3.5 py-2 bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold rounded-lg shadow-xs transition flex items-center gap-1.5">
                <span>📞 Call {{ $lead->mobile }}</span>
            </a>
        </div>
    </div>

    <!-- Main Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start text-xs">

        <!-- Left Details (8 cols) -->
        <div class="lg:col-span-8 space-y-6">

            <!-- Devotee & Pickup Coordinates Card -->
            <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-xs space-y-5">
                <h2 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-2 flex items-center gap-2">
                    <span class="text-amber-600 font-bold">1.</span>
                    <span>Devotee & Haridwar Pickup Point</span>
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <span class="text-slate-500 font-medium block">Devotee Full Name:</span>
                        <span class="text-sm font-bold text-slate-900">{{ $lead->name }}</span>
                    </div>

                    <div>
                        <span class="text-slate-500 font-medium block">Mobile / WhatsApp:</span>
                        <span class="text-sm font-bold text-slate-900 font-mono">{{ $lead->mobile }}</span>
                    </div>

                    @if($lead->email)
                        <div>
                            <span class="text-slate-500 font-medium block">Email:</span>
                            <span class="text-slate-900 font-mono">{{ $lead->email }}</span>
                        </div>
                    @endif

                    <div>
                        <span class="text-slate-500 font-medium block">Scheduled Collection Date:</span>
                        <span class="text-sm font-bold text-amber-800">
                            {{ $lead->pickup_date ? $lead->pickup_date->format('M d, Y') : 'Not specified' }}
                        </span>
                    </div>
                </div>

                <!-- Haridwar Pickup Location Highlights -->
                <div class="p-4 rounded-xl bg-amber-50/70 border border-amber-200/80 space-y-2">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-amber-900 block">
                        🏨 Haridwar Hotel / Dharamshala Pickup Location
                    </span>
                    <div class="text-sm font-bold text-slate-900">
                        {{ $lead->hotel_or_dharamshala }}
                    </div>
                    @if($lead->pickup_room_or_landmark)
                        <div class="text-xs text-slate-600">
                            <span class="font-semibold text-slate-700">Room / Landmark:</span> {{ $lead->pickup_room_or_landmark }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Resident Delivery Address Card -->
            <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-xs space-y-3">
                <h2 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-2 flex items-center gap-2">
                    <span class="text-amber-600 font-bold">2.</span>
                    <span>Resident Delivery Address (Where to Deliver)</span>
                </h2>

                <div class="p-3.5 bg-slate-50 rounded-lg text-slate-800 leading-relaxed font-medium">
                    {{ $lead->delivery_address }}
                </div>

                <div class="grid grid-cols-3 gap-3 pt-1">
                    <div>
                        <span class="text-slate-500 font-medium block">City:</span>
                        <span class="text-slate-900 font-bold">{{ $lead->delivery_city ?? 'N/A' }}</span>
                    </div>
                    <div>
                        <span class="text-slate-500 font-medium block">State:</span>
                        <span class="text-slate-900 font-bold">{{ $lead->delivery_state ?? 'N/A' }}</span>
                    </div>
                    <div>
                        <span class="text-slate-500 font-medium block">PIN Code:</span>
                        <span class="text-slate-900 font-bold font-mono">{{ $lead->delivery_pincode ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>

            <!-- Water Quantity & Dynamic Price Breakdown -->
            <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-xs space-y-4">
                <h2 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-2 flex items-center gap-2">
                    <span class="text-amber-600 font-bold">3.</span>
                    <span>Water Quantity & Dynamic Pricing Breakdown</span>
                </h2>

                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-slate-500 font-semibold border-b border-slate-200 pb-2">
                                <th class="py-2">Package Name</th>
                                <th class="py-2">Water Volume</th>
                                <th class="py-2">Unit Price</th>
                                <th class="py-2">Quantity Count</th>
                                <th class="py-2 text-right">Total Amount</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium">
                            <tr>
                                <td class="py-3 font-bold text-slate-900">{{ $lead->package_name }}</td>
                                <td class="py-3 text-[#881337] font-semibold">{{ $lead->water_quantity }}</td>
                                <td class="py-3">₹{{ number_format($lead->unit_price, 2) }}</td>
                                <td class="py-3 font-mono font-bold">{{ $lead->quantity_count }}</td>
                                <td class="py-3 text-right font-black text-slate-900 text-sm">
                                    ₹{{ number_format($lead->total_price, 2) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                @if($lead->notes)
                    <div class="pt-3 border-t border-slate-100 space-y-1">
                        <span class="text-slate-500 font-semibold block">Devotee Special Notes / Gotra / Sankalp:</span>
                        <div class="p-3 bg-amber-50/50 rounded-lg text-slate-700 italic border border-amber-200/50">
                            {{ $lead->notes }}
                        </div>
                    </div>
                @endif
            </div>

        </div>

        <!-- Right Side: Status Updater & Staff Notes (4 cols) -->
        <div class="lg:col-span-4 space-y-6">

            <!-- Payment Gateway Details Card -->
            <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-xs space-y-4">
                <h2 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-2 flex items-center justify-between">
                    <span>Payment Gateway Status</span>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold border uppercase tracking-wider {{ $lead->payment_status_badge_classes }}">
                        {{ $lead->payment_status }}
                    </span>
                </h2>

                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-slate-500 font-medium">Payment Gateway:</span>
                        <span class="font-bold text-slate-900">
                            {{ $lead->payment_method === 'razorpay' ? 'Razorpay Online' : 'Pay on Collection (COD)' }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-slate-500 font-medium">Total Amount:</span>
                        <span class="text-base font-extrabold text-[#881337]">
                            ₹{{ number_format($lead->total_price, 2) }}
                        </span>
                    </div>

                    @if($lead->razorpay_payment_id)
                        <div class="pt-2 border-t border-slate-100 space-y-1">
                            <span class="text-slate-500 font-medium block">Razorpay Payment ID:</span>
                            <span class="font-mono text-slate-900 bg-slate-50 px-2 py-1 rounded block select-all">
                                {{ $lead->razorpay_payment_id }}
                            </span>
                        </div>
                    @endif

                    @if($lead->razorpay_order_id)
                        <div class="space-y-1">
                            <span class="text-slate-500 font-medium block">Razorpay Order ID:</span>
                            <span class="font-mono text-slate-900 bg-slate-50 px-2 py-1 rounded block select-all">
                                {{ $lead->razorpay_order_id }}
                            </span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Update Lead Status Card -->
            <div class="bg-white rounded-xl border-2 border-amber-300 p-6 shadow-xs space-y-4">
                <h2 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-2">
                    Manage Lead Status & Coordination
                </h2>

                <form method="POST" action="{{ route('admin.water-collection-leads.update', $lead) }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="status" class="block font-semibold text-slate-700 mb-1">
                            Current Status
                        </label>
                        <select name="status" id="status" class="w-full text-xs rounded-lg border-slate-300 focus:ring-1 focus:ring-amber-500 focus:border-amber-500 font-semibold">
                            <option value="pending" {{ $lead->status === 'pending' ? 'selected' : '' }}>⏳ Pending Review</option>
                            <option value="contacted" {{ $lead->status === 'contacted' ? 'selected' : '' }}>📞 Contacted Devotee</option>
                            <option value="scheduled" {{ $lead->status === 'scheduled' ? 'selected' : '' }}>🗓️ Pickup Scheduled</option>
                            <option value="collected" {{ $lead->status === 'collected' ? 'selected' : '' }}>🌊 Water Collected</option>
                            <option value="dispatched" {{ $lead->status === 'dispatched' ? 'selected' : '' }}>🚚 Dispatched to Courier</option>
                            <option value="delivered" {{ $lead->status === 'delivered' ? 'selected' : '' }}>✅ Delivered to Resident</option>
                            <option value="cancelled" {{ $lead->status === 'cancelled' ? 'selected' : '' }}>❌ Cancelled</option>
                        </select>
                    </div>

                    <div>
                        <label for="pickup_date" class="block font-semibold text-slate-700 mb-1">
                            Scheduled Collection Date
                        </label>
                        <input type="date"
                               name="pickup_date"
                               id="pickup_date"
                               value="{{ old('pickup_date', $lead->pickup_date?->format('Y-m-d')) }}"
                               class="w-full text-xs rounded-lg border-slate-300 focus:ring-1 focus:ring-amber-500 focus:border-amber-500" />
                    </div>

                    <div>
                        <label for="admin_notes" class="block font-semibold text-slate-700 mb-1">
                            Internal Staff Notes & Tracking AWB
                        </label>
                        <textarea name="admin_notes"
                                  id="admin_notes"
                                  rows="4"
                                  placeholder="Add internal notes, courier tracking number, hotel coordinator name..."
                                  class="w-full text-xs rounded-lg border-slate-300 focus:ring-1 focus:ring-amber-500 focus:border-amber-500 placeholder-slate-400">{{ old('admin_notes', $lead->admin_notes) }}</textarea>
                    </div>

                    <button type="submit"
                            class="w-full py-2.5 px-4 rounded-lg bg-amber-600 hover:bg-amber-700 text-white font-bold transition shadow-xs cursor-pointer">
                        Update Lead Information
                    </button>
                </form>
            </div>

            <!-- Metadata info -->
            <div class="bg-white rounded-xl border border-slate-200 p-4 space-y-2 text-[11px] text-slate-500">
                <span class="font-bold text-slate-700 block">System Audit Info</span>
                <div>IP Address: <span class="font-mono text-slate-800">{{ $lead->ip_address ?? 'N/A' }}</span></div>
                <div>Created: <span class="text-slate-800">{{ $lead->created_at?->diffForHumans() }}</span></div>
                <div>Last Updated: <span class="text-slate-800">{{ $lead->updated_at?->diffForHumans() }}</span></div>
            </div>

        </div>

    </div>
</x-admin-layout>

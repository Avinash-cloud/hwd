<x-admin-layout title="Water Collection Pricing & Tiers">
    <x-slot name="header">Water Collection Pricing & Tiers</x-slot>

    <div x-data="{
        isCreateModalOpen: false,
        isEditModalOpen: false,
        editPackage: {
            id: null,
            name: '',
            quantity_litres: 1,
            unit: 'Litre',
            container_type: '',
            price: 0,
            original_price: null,
            badge: '',
            description: '',
            sort_order: 0,
            is_active: true
        },
        openEdit(pkg) {
            this.editPackage = JSON.parse(JSON.stringify(pkg));
            this.isEditModalOpen = true;
        }
    }" class="space-y-6">

        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.water-collection-leads.index') }}" class="text-xs text-slate-500 hover:text-slate-800">
                        ← Back to Kumbh Water Leads
                    </a>
                </div>
                <h1 class="text-xl font-bold text-slate-900 mt-1 flex items-center gap-2">
                    <span>Water Quantity & Dynamic Pricing Tiers</span>
                    <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-amber-100 text-amber-800">
                        {{ $packages->count() }} Tiers
                    </span>
                </h1>
                <p class="text-xs text-slate-500 mt-0.5">
                    Configure the water quantities, packaging types, and dynamic prices displayed on the public Kumbh lead generation page.
                </p>
            </div>

            <div class="flex items-center gap-2">
                <button type="button" @click="isCreateModalOpen = true"
                        class="px-4 py-2 bg-gradient-to-r from-amber-600 to-[#881337] hover:from-amber-700 hover:to-[#70102E] text-white text-xs font-bold rounded-lg shadow-sm transition flex items-center gap-1.5 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Add New Water Quantity & Price Tier</span>
                </button>
            </div>
        </div>

        <!-- Flash messages -->
        @if(session('success'))
            <div class="p-3.5 rounded-xl bg-emerald-50 border border-emerald-300 text-emerald-800 text-xs font-semibold flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="p-3.5 rounded-xl bg-rose-50 border border-rose-300 text-rose-800 text-xs font-semibold">
                <ul class="list-disc pl-4 space-y-0.5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Packages Table -->
        <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-xs">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 font-semibold text-[11px] uppercase tracking-wider">
                            <th class="py-3 px-4">Order</th>
                            <th class="py-3 px-4">Tier / Package Name</th>
                            <th class="py-3 px-4">Water Quantity</th>
                            <th class="py-3 px-4">Container Type</th>
                            <th class="py-3 px-4">Dynamic Price</th>
                            <th class="py-3 px-4">Original Price</th>
                            <th class="py-3 px-4">Badge</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($packages as $pkg)
                            <tr class="hover:bg-slate-50/80 transition-colors {{ !$pkg->is_active ? 'opacity-60 bg-slate-50/50' : '' }}">
                                <td class="py-3 px-4 font-mono font-bold text-slate-500">
                                    {{ $pkg->sort_order }}
                                </td>

                                <td class="py-3 px-4">
                                    <div class="font-bold text-slate-900">{{ $pkg->name }}</div>
                                    @if($pkg->description)
                                        <div class="text-[11px] text-slate-500 max-w-xs truncate">{{ $pkg->description }}</div>
                                    @endif
                                </td>

                                <td class="py-3 px-4 whitespace-nowrap">
                                    <span class="font-bold text-[#881337] text-sm">
                                        {{ (float)$pkg->quantity_litres }} {{ $pkg->unit }}
                                    </span>
                                </td>

                                <td class="py-3 px-4 text-slate-700 whitespace-nowrap">
                                    {{ $pkg->container_type }}
                                </td>

                                <td class="py-3 px-4 whitespace-nowrap">
                                    <span class="font-black text-slate-900 text-sm">
                                        ₹{{ number_format($pkg->price, 2) }}
                                    </span>
                                </td>

                                <td class="py-3 px-4 whitespace-nowrap text-slate-400">
                                    @if($pkg->original_price)
                                        <span class="line-through">₹{{ number_format($pkg->original_price, 2) }}</span>
                                    @else
                                        -
                                    @endif
                                </td>

                                <td class="py-3 px-4 whitespace-nowrap">
                                    @if($pkg->badge)
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-900 border border-amber-300 uppercase tracking-wider">
                                            {{ $pkg->badge }}
                                        </span>
                                    @else
                                        <span class="text-slate-400 text-[11px]">None</span>
                                    @endif
                                </td>

                                <td class="py-3 px-4 whitespace-nowrap">
                                    <form method="POST" action="{{ route('admin.water-collection-packages.toggle', $pkg) }}" class="inline">
                                        @csrf
                                        <button type="submit"
                                                class="px-2 py-0.5 rounded-full text-[10px] font-bold border transition cursor-pointer {{ $pkg->is_active ? 'bg-emerald-100 text-emerald-800 border-emerald-300 hover:bg-emerald-200' : 'bg-slate-100 text-slate-600 border-slate-300 hover:bg-slate-200' }}">
                                            {{ $pkg->is_active ? 'Active' : 'Inactive' }}
                                        </button>
                                    </form>
                                </td>

                                <td class="py-3 px-4 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-2">
                                        <button type="button"
                                                @click="openEdit({{ json_encode($pkg) }})"
                                                class="px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-md transition text-xs cursor-pointer">
                                            Edit
                                        </button>

                                        <form method="POST" action="{{ route('admin.water-collection-packages.destroy', $pkg) }}"
                                              onsubmit="return confirm('Delete water package tier \'{{ $pkg->name }}\'?');"
                                              class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1 text-slate-400 hover:text-rose-600 transition cursor-pointer" title="Delete Tier">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="py-8 text-center text-slate-500">
                                    No water packages configured. Click above to add your first tier.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal: Add New Tier -->
        <div x-show="isCreateModalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
            <div @click.away="isCreateModalOpen = false" class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl space-y-4 border border-slate-200 text-xs">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="text-sm font-bold text-slate-900">Add New Water Quantity & Dynamic Price Tier</h3>
                    <button type="button" @click="isCreateModalOpen = false" class="text-slate-400 hover:text-slate-600 text-lg cursor-pointer">&times;</button>
                </div>

                <form method="POST" action="{{ route('admin.water-collection-packages.store') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Package Name <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" required placeholder="e.g. 5 Litres Maha Kumbh Pavitra Canister"
                               class="w-full text-xs rounded-lg border-slate-300 focus:ring-1 focus:ring-amber-500 focus:border-amber-500" />
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Water Quantity (Numeric) <span class="text-rose-500">*</span></label>
                            <input type="number" step="0.1" min="0.1" name="quantity_litres" required value="1"
                                   class="w-full text-xs rounded-lg border-slate-300 focus:ring-1 focus:ring-amber-500 focus:border-amber-500" />
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Unit <span class="text-rose-500">*</span></label>
                            <input type="text" name="unit" required value="Litres" placeholder="Litre / Litres / Canister"
                                   class="w-full text-xs rounded-lg border-slate-300 focus:ring-1 focus:ring-amber-500 focus:border-amber-500" />
                        </div>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Container Type <span class="text-rose-500">*</span></label>
                        <input type="text" name="container_type" required placeholder="e.g. Heavy-Duty Food Grade Canister / Auspicious Sealed Bottle"
                               class="w-full text-xs rounded-lg border-slate-300 focus:ring-1 focus:ring-amber-500 focus:border-amber-500" />
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Dynamic Selling Price (₹) <span class="text-rose-500">*</span></label>
                            <input type="number" step="1" min="0" name="price" required placeholder="e.g. 501"
                                   class="w-full text-xs rounded-lg border-slate-300 focus:ring-1 focus:ring-amber-500 focus:border-amber-500" />
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Original Price (₹) <span class="text-slate-400 font-normal">(Optional)</span></label>
                            <input type="number" step="1" min="0" name="original_price" placeholder="e.g. 850"
                                   class="w-full text-xs rounded-lg border-slate-300 focus:ring-1 focus:ring-amber-500 focus:border-amber-500" />
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Badge Tag</label>
                            <input type="text" name="badge" placeholder="e.g. Most Popular / Devotee Choice"
                                   class="w-full text-xs rounded-lg border-slate-300 focus:ring-1 focus:ring-amber-500 focus:border-amber-500" />
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Sort Order</label>
                            <input type="number" name="sort_order" value="0" min="0"
                                   class="w-full text-xs rounded-lg border-slate-300 focus:ring-1 focus:ring-amber-500 focus:border-amber-500" />
                        </div>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Devotional Description</label>
                        <textarea name="description" rows="2" placeholder="Brief description of collection muhurat, sealing, and blessings..."
                                  class="w-full text-xs rounded-lg border-slate-300 focus:ring-1 focus:ring-amber-500 focus:border-amber-500"></textarea>
                    </div>

                    <div class="flex items-center gap-2 pt-1">
                        <input type="checkbox" name="is_active" id="create_active" value="1" checked
                               class="rounded border-slate-300 text-amber-600 focus:ring-amber-500" />
                        <label for="create_active" class="text-xs font-semibold text-slate-700">Active (Visible on public booking page)</label>
                    </div>

                    <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-2">
                        <button type="button" @click="isCreateModalOpen = false" class="px-3.5 py-2 text-slate-600 hover:bg-slate-100 rounded-lg font-semibold cursor-pointer">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-lg font-bold shadow-xs cursor-pointer">
                            Create Package Tier
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal: Edit Tier -->
        <div x-show="isEditModalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
            <div @click.away="isEditModalOpen = false" class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl space-y-4 border border-slate-200 text-xs">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="text-sm font-bold text-slate-900">Edit Water Quantity & Dynamic Price Tier</h3>
                    <button type="button" @click="isEditModalOpen = false" class="text-slate-400 hover:text-slate-600 text-lg cursor-pointer">&times;</button>
                </div>

                <form :action="'{{ url('admin/water-collection-packages') }}/' + editPackage.id" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Package Name <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" x-model="editPackage.name" required
                               class="w-full text-xs rounded-lg border-slate-300 focus:ring-1 focus:ring-amber-500 focus:border-amber-500" />
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Water Quantity (Numeric) <span class="text-rose-500">*</span></label>
                            <input type="number" step="0.1" min="0.1" name="quantity_litres" x-model="editPackage.quantity_litres" required
                                   class="w-full text-xs rounded-lg border-slate-300 focus:ring-1 focus:ring-amber-500 focus:border-amber-500" />
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Unit <span class="text-rose-500">*</span></label>
                            <input type="text" name="unit" x-model="editPackage.unit" required
                                   class="w-full text-xs rounded-lg border-slate-300 focus:ring-1 focus:ring-amber-500 focus:border-amber-500" />
                        </div>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Container Type <span class="text-rose-500">*</span></label>
                        <input type="text" name="container_type" x-model="editPackage.container_type" required
                               class="w-full text-xs rounded-lg border-slate-300 focus:ring-1 focus:ring-amber-500 focus:border-amber-500" />
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Dynamic Selling Price (₹) <span class="text-rose-500">*</span></label>
                            <input type="number" step="1" min="0" name="price" x-model="editPackage.price" required
                                   class="w-full text-xs rounded-lg border-slate-300 focus:ring-1 focus:ring-amber-500 focus:border-amber-500" />
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Original Price (₹)</label>
                            <input type="number" step="1" min="0" name="original_price" x-model="editPackage.original_price"
                                   class="w-full text-xs rounded-lg border-slate-300 focus:ring-1 focus:ring-amber-500 focus:border-amber-500" />
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Badge Tag</label>
                            <input type="text" name="badge" x-model="editPackage.badge"
                                   class="w-full text-xs rounded-lg border-slate-300 focus:ring-1 focus:ring-amber-500 focus:border-amber-500" />
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Sort Order</label>
                            <input type="number" name="sort_order" x-model="editPackage.sort_order" min="0"
                                   class="w-full text-xs rounded-lg border-slate-300 focus:ring-1 focus:ring-amber-500 focus:border-amber-500" />
                        </div>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Devotional Description</label>
                        <textarea name="description" x-model="editPackage.description" rows="2"
                                  class="w-full text-xs rounded-lg border-slate-300 focus:ring-1 focus:ring-amber-500 focus:border-amber-500"></textarea>
                    </div>

                    <div class="flex items-center gap-2 pt-1">
                        <input type="checkbox" name="is_active" id="edit_active" value="1" :checked="editPackage.is_active"
                               class="rounded border-slate-300 text-amber-600 focus:ring-amber-500" />
                        <label for="edit_active" class="text-xs font-semibold text-slate-700">Active (Visible on public booking page)</label>
                    </div>

                    <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-2">
                        <button type="button" @click="isEditModalOpen = false" class="px-3.5 py-2 text-slate-600 hover:bg-slate-100 rounded-lg font-semibold cursor-pointer">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-lg font-bold shadow-xs cursor-pointer">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-admin-layout>

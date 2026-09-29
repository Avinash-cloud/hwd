<x-admin-layout title="Manage Products">
    <x-slot name="header">
        Products & Offerings Catalog
    </x-slot>

    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-serif font-bold text-white">All Products ({{ $products->total() }})</h2>
        <a href="{{ route('admin.products.create') }}" class="px-4 py-2 bg-[#D97706] hover:bg-[#F97316] text-white font-bold text-xs rounded-xl shadow-xs transition">
            + Add New Product
        </a>
    </div>

    <div class="bg-slate-950/80 border border-slate-800 rounded-3xl p-6 shadow-sm overflow-x-auto">
        <table class="w-full text-left text-xs">
            <thead>
                <tr class="text-slate-400 border-b border-slate-800 font-mono text-[11px]">
                    <th class="pb-3">Product Name</th>
                    <th class="pb-3">Category</th>
                    <th class="pb-3">Type</th>
                    <th class="pb-3">Regular Price</th>
                    <th class="pb-3">Member Price</th>
                    <th class="pb-3">Stock</th>
                    <th class="pb-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-800/80">
                @foreach ($products as $p)
                    <tr>
                        <td class="py-3 font-bold text-white">
                            {{ $p->name }}
                            @if ($p->sku)
                                <span class="block text-[10px] text-slate-500 font-mono">{{ $p->sku }}</span>
                            @endif
                        </td>
                        <td class="py-3 text-slate-300">{{ $p->category->name }}</td>
                        <td class="py-3">
                            @if ($p->is_gangajal)
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-[#0369A1]/20 text-[#0369A1] border border-[#0369A1]/40">
                                    Gangajal ({{ $p->volume_ml }}ml)
                                </span>
                            @else
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-800 text-slate-300">
                                    Spiritual Item
                                </span>
                            @endif
                        </td>
                        <td class="py-3 font-mono">₹{{ number_format($p->price, 2) }}</td>
                        <td class="py-3 font-mono text-[#0369A1]">
                            @if ($p->is_gangajal)
                                Free 1st / Mo
                            @else
                                ₹{{ number_format($p->member_price ?? $p->price, 2) }}
                            @endif
                        </td>
                        <td class="py-3">{{ $p->stock }} units</td>
                        <td class="py-3 text-right space-x-3">
                            <a href="{{ route('admin.products.edit', $p->id) }}" class="text-[#D97706] hover:underline">Edit</a>
                            <form method="POST" action="{{ route('admin.products.destroy', $p->id) }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-[#991B1B] hover:text-[#881337] hover:underline" onclick="return confirm('Delete this product?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-6">
            {{ $products->links() }}
        </div>
    </div>
</x-admin-layout>

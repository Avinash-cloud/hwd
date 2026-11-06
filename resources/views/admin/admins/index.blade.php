<x-admin-layout title="Admin Staff & Role Management">
    <x-slot name="header">
        Admin User Management
    </x-slot>

    <!-- Page Title & Actions -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h1 class="text-xl font-semibold text-slate-900">Admin Staff & Roles ({{ $admins->total() }})</h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Manage administrative accounts, role assignments, and granular permission access across the platform.
            </p>
        </div>
        <a href="{{ route('admin.admins.create') }}"
           class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-amber-600 hover:bg-amber-700 text-white text-xs font-medium rounded-lg transition shadow-xs">
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            <span>Create Admin User</span>
        </a>
    </div>

    <!-- Search Bar -->
    <div class="bg-white border border-slate-200 rounded-xl p-4 mb-6">
        <form method="GET" action="{{ route('admin.admins.index') }}" class="flex items-center gap-3">
            <div class="relative flex-1">
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Search admin staff by name, email, or phone..."
                       class="w-full pl-9 pr-4 py-2 text-xs border border-slate-300 rounded-lg focus:border-amber-500 focus:ring-1 focus:ring-amber-500" />
            </div>
            <button type="submit" class="px-4 py-2 bg-slate-900 text-white rounded-lg text-xs font-medium hover:bg-slate-800 transition">
                Filter
            </button>
            @if(request('search'))
                <a href="{{ route('admin.admins.index') }}" class="px-3 py-2 text-xs text-slate-500 hover:text-slate-800 transition">
                    Clear
                </a>
            @endif
        </form>
    </div>

    <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-xs">
        @if ($admins->isEmpty())
            <div class="text-center py-12 text-slate-400">
                <p class="text-sm">No admin users found matching criteria.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="bg-slate-50/75 border-b border-slate-200 text-slate-600 font-semibold uppercase text-[11px] tracking-wider">
                            <th class="py-2.5 px-4">Admin Name & Details</th>
                            <th class="py-2.5 px-4">Email & Phone</th>
                            <th class="py-2.5 px-4">Assigned Roles</th>
                            <th class="py-2.5 px-4 text-center">Status</th>
                            <th class="py-2.5 px-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($admins as $admin)
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="py-3 px-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-amber-100 text-amber-800 font-bold flex items-center justify-center shrink-0 text-xs">
                                            {{ strtoupper(substr($admin->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <strong class="font-bold text-slate-900 block text-xs">{{ $admin->name }}</strong>
                                            @if($admin->id === 1)
                                                <span class="inline-block px-1.5 py-0.2 rounded text-[9px] font-mono bg-purple-50 text-purple-700 border border-purple-200">Root SuperAdmin</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <td class="py-3 px-4 text-slate-600">
                                    <span class="font-mono block text-slate-900">{{ $admin->email }}</span>
                                    <span class="text-[11px] text-slate-400 font-mono">{{ $admin->phone ?? 'No phone' }}</span>
                                </td>

                                <td class="py-3 px-4">
                                    <div class="flex flex-wrap gap-1">
                                        @forelse ($admin->roles as $role)
                                            <span class="inline-block px-2 py-0.5 rounded text-[10px] font-semibold bg-slate-100 text-slate-800 border border-slate-200">
                                                {{ $role->name }}
                                            </span>
                                        @empty
                                            <span class="text-slate-400 italic text-[11px]">No specific roles</span>
                                        @endforelse
                                    </div>
                                </td>

                                <td class="py-3 px-4 text-center">
                                    @if ($admin->is_active ?? true)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Disabled
                                        </span>
                                    @endif
                                </td>

                                <td class="py-3 px-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <!-- Edit -->
                                        <a href="{{ route('admin.admins.edit', $admin->id) }}"
                                           class="p-1.5 text-slate-500 hover:text-amber-700 hover:bg-slate-100 rounded-md transition"
                                           title="Edit Admin User & Roles">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>

                                        @if(auth()->id() !== $admin->id)
                                            <!-- Toggle Active -->
                                            <form method="POST" action="{{ route('admin.admins.toggle-active', $admin->id) }}" class="inline">
                                                @csrf
                                                <button type="submit"
                                                        class="p-1.5 text-slate-500 hover:text-slate-800 hover:bg-slate-100 rounded-md transition"
                                                        title="{{ ($admin->is_active ?? true) ? 'Deactivate Account' : 'Activate Account' }}">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                                    </svg>
                                                </button>
                                            </form>

                                            @if($admin->id !== 1)
                                                <!-- Delete -->
                                                <form method="POST" action="{{ route('admin.admins.destroy', $admin->id) }}"
                                                      class="inline"
                                                      onsubmit="return confirm('Are you sure you want to remove admin access for {{ addslashes($admin->name) }}?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-md transition"
                                                            title="Delete Admin">
                                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                        </svg>
                                                    </button>
                                                </form>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-4 py-3 border-t border-slate-200">
                {{ $admins->links() }}
            </div>
        @endif
    </div>
</x-admin-layout>


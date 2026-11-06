<x-admin-layout title="Create Admin Staff User">
    <x-slot name="header">
        Admin User Management
    </x-slot>

    <div class="max-w-3xl mx-auto">
        <div class="mb-6">
            <h1 class="text-xl font-semibold text-slate-900">Create Admin Staff</h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Grant dashboard access with dedicated credentials and specific role-based permissions.
            </p>
        </div>

        <div class="bg-white border border-slate-200 rounded-xl p-6 sm:p-8 shadow-xs">
            <form method="POST" action="{{ route('admin.admins.store') }}" class="space-y-6 text-sm">
                @csrf

                <!-- Basic Profile Information -->
                <div>
                    <h2 class="text-sm font-semibold text-slate-900 mb-3">Admin Staff Details</h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Staff Full Name *</label>
                            <input type="text"
                                   name="name"
                                   value="{{ old('name') }}"
                                   required
                                   placeholder="e.g. Ramesh Sharma"
                                   class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-sm text-slate-900 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500" />
                            @error('name') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Official Email Address *</label>
                            <input type="email"
                                   name="email"
                                   value="{{ old('email') }}"
                                   required
                                   placeholder="ramesh@haridwarbliss.com"
                                   class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-sm text-slate-900 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500" />
                            @error('email') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Contact Phone Number</label>
                            <input type="text"
                                   name="phone"
                                   value="{{ old('phone') }}"
                                   placeholder="+91 9876543210"
                                   class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-sm text-slate-900 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500" />
                            @error('phone') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="flex items-center pt-2 sm:pt-6">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox"
                                       name="is_active"
                                       value="1"
                                       {{ old('is_active', true) ? 'checked' : '' }}
                                       class="rounded text-amber-600 focus:ring-amber-500 border-slate-300">
                                <span class="text-sm font-semibold text-slate-800">Account Active & Enabled</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Password Credentials -->
                <div class="pt-5 border-t border-slate-200">
                    <h2 class="text-sm font-semibold text-slate-900 mb-3">Security & Password</h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Initial Password *</label>
                            <input type="password"
                                   name="password"
                                   required
                                   placeholder="Minimum 8 characters"
                                   class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-sm text-slate-900 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500" />
                            @error('password') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Confirm Password *</label>
                            <input type="password"
                                   name="password_confirmation"
                                   required
                                   placeholder="Repeat password"
                                   class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-sm text-slate-900 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500" />
                        </div>
                    </div>
                </div>

                <!-- Role Assignments -->
                <div class="pt-5 border-t border-slate-200">
                    <div class="mb-3">
                        <h2 class="text-sm font-semibold text-slate-900">Role & Permission Assignments</h2>
                        <p class="text-xs text-slate-500">Select one or more administrative roles to grant access to corresponding modules.</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach ($roles as $role)
                            <label class="p-3.5 rounded-xl border border-slate-200 bg-slate-50/50 hover:bg-white hover:border-amber-300 cursor-pointer transition flex items-start gap-3">
                                <input type="checkbox"
                                       name="roles[]"
                                       value="{{ $role->id }}"
                                       {{ in_array($role->id, old('roles', [])) ? 'checked' : '' }}
                                       class="mt-1 rounded text-amber-600 focus:ring-amber-500 border-slate-300">
                                <div>
                                    <strong class="font-bold text-slate-900 block text-xs">{{ $role->name }}</strong>
                                    <p class="text-[11px] text-slate-500 mt-0.5">{{ $role->description }}</p>
                                    @if ($role->permissions->isNotEmpty())
                                        <div class="flex flex-wrap gap-1 mt-2">
                                            @foreach ($role->permissions as $perm)
                                                <span class="px-1.5 py-0.2 rounded text-[9px] bg-slate-200 text-slate-700 font-mono">{{ $perm->name }}</span>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </label>
                        @endforeach
                    </div>
                    @error('roles') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-end gap-3 pt-5 border-t border-slate-200">
                    <a href="{{ route('admin.admins.index') }}" class="px-4 py-2 rounded-lg border border-slate-300 text-slate-700 hover:bg-slate-50 transition text-xs font-semibold">Cancel</a>
                    <button type="submit" class="px-5 py-2.5 bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs rounded-lg shadow-xs transition">
                        Create Admin Staff
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>


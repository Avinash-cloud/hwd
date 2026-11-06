<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    /**
     * Display a listing of admin users.
     */
    public function index(Request $request): View
    {
        $query = User::with('roles')->where('is_admin', true);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search): void {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $admins = $query->latest('id')->paginate(15)->withQueryString();

        return view('admin.admins.index', compact('admins'));
    }

    /**
     * Show the form for creating a new admin user.
     */
    public function create(): View
    {
        $roles = Role::with('permissions')->orderBy('name')->get();

        return view('admin.admins.create', compact('roles'));
    }

    /**
     * Store a newly created admin user in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => ['required', 'confirmed', Password::defaults()],
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
            'is_active' => 'nullable|boolean',
        ]);

        $admin = User::create([
            'name' => $validated['name'],
            'email' => strtolower(trim($validated['email'])),
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
            'is_admin' => true,
            'is_active' => $request->boolean('is_active', true),
            'email_verified_at' => now(),
        ]);

        if (! empty($validated['roles'])) {
            $admin->roles()->sync($validated['roles']);
        }

        return redirect()->route('admin.admins.index')->with('success', "Admin user '{$admin->name}' created successfully.");
    }

    /**
     * Show the form for editing the specified admin user.
     */
    public function edit(User $admin): View
    {
        abort_unless($admin->is_admin, 404);

        $roles = Role::with('permissions')->orderBy('name')->get();
        $adminRoleIds = $admin->roles->pluck('id')->toArray();

        return view('admin.admins.edit', compact('admin', 'roles', 'adminRoleIds'));
    }

    /**
     * Update the specified admin user in storage.
     */
    public function update(Request $request, User $admin): RedirectResponse
    {
        abort_unless($admin->is_admin, 404);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$admin->id,
            'phone' => 'nullable|string|max:20',
            'password' => ['nullable', 'confirmed', Password::defaults()],
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
            'is_active' => 'nullable|boolean',
        ]);

        // Safety: Ensure user does not deactivate their own active session
        $currentUser = $request->user();
        $isActive = $request->boolean('is_active', true);
        if ($currentUser && $currentUser->id === $admin->id && ! $isActive) {
            return back()->withInput()->with('error', 'You cannot deactivate your own administrative account.');
        }

        $updateData = [
            'name' => $validated['name'],
            'email' => strtolower(trim($validated['email'])),
            'phone' => $validated['phone'] ?? null,
            'is_active' => $isActive,
        ];

        if (! empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $admin->update($updateData);

        // Sync roles (ensure super_admin is not removed from own account if you are current user)
        $roleIds = $validated['roles'] ?? [];
        if ($currentUser && $currentUser->id === $admin->id && $admin->hasRole('super_admin')) {
            $superAdminRole = Role::where('slug', 'super_admin')->first();
            if ($superAdminRole && ! in_array($superAdminRole->id, $roleIds)) {
                $roleIds[] = $superAdminRole->id;
            }
        }

        $admin->roles()->sync($roleIds);

        return redirect()->route('admin.admins.index')->with('success', "Admin user '{$admin->name}' updated successfully.");
    }

    /**
     * Toggle active status.
     */
    public function toggleActive(Request $request, User $admin): RedirectResponse
    {
        abort_unless($admin->is_admin, 404);

        if ($request->user() && $request->user()->id === $admin->id) {
            return back()->with('error', 'You cannot deactivate your own administrative account.');
        }

        $admin->update(['is_active' => ! $admin->is_active]);

        $statusText = $admin->is_active ? 'activated' : 'deactivated';

        return back()->with('success', "Admin '{$admin->name}' has been {$statusText}.");
    }

    /**
     * Remove the specified admin user.
     */
    public function destroy(Request $request, User $admin): RedirectResponse
    {
        abort_unless($admin->is_admin, 404);

        if ($request->user() && $request->user()->id === $admin->id) {
            return back()->with('error', 'You cannot delete your own administrative account.');
        }

        if ($admin->id === 1) {
            return back()->with('error', 'The primary root super admin cannot be deleted.');
        }

        $admin->roles()->detach();
        $admin->delete();

        return redirect()->route('admin.admins.index')->with('success', "Admin user '{$admin->name}' removed.");
    }
}

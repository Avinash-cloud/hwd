<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            ['name' => 'Manage Products & Variants', 'slug' => 'manage_products', 'category' => 'catalog'],
            ['name' => 'Manage Orders & Dispatch', 'slug' => 'manage_orders', 'category' => 'orders'],
            ['name' => 'Manage Memberships & Subscriptions', 'slug' => 'manage_memberships', 'category' => 'memberships'],
            ['name' => 'Manage Homepage Hero Slider', 'slug' => 'manage_sliders', 'category' => 'content'],
            ['name' => 'Manage Admin Users & Roles', 'slug' => 'manage_admins', 'category' => 'administration'],
            ['name' => 'Manage Site Settings', 'slug' => 'manage_settings', 'category' => 'administration'],
            ['name' => 'Manage Devotee Reviews', 'slug' => 'manage_reviews', 'category' => 'community'],
            ['name' => 'Manage Sacred Batches', 'slug' => 'manage_batches', 'category' => 'sanctity'],
        ];

        $createdPermissions = [];
        foreach ($permissions as $p) {
            $createdPermissions[$p['slug']] = Permission::updateOrCreate(
                ['slug' => $p['slug']],
                ['name' => $p['name'], 'category' => $p['category']]
            );
        }

        $superAdminRole = Role::updateOrCreate(
            ['slug' => 'super_admin'],
            ['name' => 'Super Administrator', 'description' => 'Full unconstrained administrative access across all system capabilities.']
        );
        $superAdminRole->permissions()->sync(array_values(array_map(fn ($p) => $p->id, $createdPermissions)));

        $orderManagerRole = Role::updateOrCreate(
            ['slug' => 'order_manager'],
            ['name' => 'Order & Logistics Manager', 'description' => 'Process orders, assign tracking numbers, and manage sacred ghat batches.']
        );
        $orderManagerRole->permissions()->sync([
            $createdPermissions['manage_orders']->id,
            $createdPermissions['manage_batches']->id,
        ]);

        $catalogManagerRole = Role::updateOrCreate(
            ['slug' => 'catalog_manager'],
            ['name' => 'Catalog & Content Manager', 'description' => 'Create and edit products, variants, media galleries, hero sliders, and reviews.']
        );
        $catalogManagerRole->permissions()->sync([
            $createdPermissions['manage_products']->id,
            $createdPermissions['manage_sliders']->id,
            $createdPermissions['manage_reviews']->id,
        ]);

        $memberManagerRole = Role::updateOrCreate(
            ['slug' => 'member_manager'],
            ['name' => 'Devotee & Membership Manager', 'description' => 'Manage 5-year memberships, invoices, and recurring subscriptions.']
        );
        $memberManagerRole->permissions()->sync([
            $createdPermissions['manage_memberships']->id,
        ]);

        // Assign super_admin role to any existing admin user
        $admins = User::where('is_admin', true)->get();
        foreach ($admins as $admin) {
            if (! $admin->roles()->where('slug', 'super_admin')->exists()) {
                $admin->roles()->attach($superAdminRole->id);
            }
        }
    }
}

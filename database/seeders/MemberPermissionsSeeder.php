<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Seeder;

class MemberPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Create the permissions
        $permissions = [
            'view-any Member',
            'view Member',
            'create Member',
            'update Member',
            'delete Member',
            // 'restore Member', etc. if needed
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm], ['guard_name' => 'web']);
        }

        // Optionally assign them to a role, e.g. "Admin" or "Super Admin"
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $adminRole->givePermissionTo($permissions);

        // If you want "Member" role to do some but not all, do so:
        $memberRole = Role::firstOrCreate(['name' => 'Member']);
        $memberRole->givePermissionTo(['view-any Member', 'view Member']);
    }
}

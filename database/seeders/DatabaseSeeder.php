<?php

namespace Database\Seeders;

use App\Models\Instance;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $instance = Instance::create(['name' => 'Main Instance']);

        $role = Role::create(['name' => 'Super Admin']);

        Role::create(['name' => 'Admin']);
        
        Role::create(['name' => 'Staff']);

        $user = User::create([
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('password'),
            'instance_id' => $instance->id,
        ]);

        $user->assignRole($role);
    }
}

<?php

namespace Database\Seeders;

use App\Enums\Roles;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $user = Role::create(['name' => Roles::USER]);
        $admin = Role::create(['name' => Roles::ADMIN]);
        $owner = Role::create(['name' => Roles::OWNER]);
        $superAdmin = Role::create(['name' => Roles::SUPER_ADMIN]);

        Permission::create(['name' => 'create gyms']);

        $owner->givePermissionTo('create gyms');
        $superAdmin->givePermissionTo('create gyms');
    }
}

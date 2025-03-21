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
        Permission::create(['name' => 'create memberships']);
        Permission::create(['name' => 'update memberships']);

        $owner->givePermissionTo('create gyms');
        $owner->givePermissionTo('create memberships');
        $owner->givePermissionTo('update memberships');

        $superAdmin->givePermissionTo('create gyms');
        $superAdmin->givePermissionTo('create memberships');
        $superAdmin->givePermissionTo('update memberships');
    }
}

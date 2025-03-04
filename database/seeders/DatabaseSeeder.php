<?php

namespace Database\Seeders;

use App\Models\Member;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Member::factory(10)->create();

        $this->call([
            RolePermissionSeeder::class,
            UserSeeder::class,
            SubscriptionSeeder::class,
        ]);
    }
}

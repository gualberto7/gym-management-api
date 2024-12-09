<?php

namespace Database\Seeders;

use App\Models\Gym;
use App\Models\Member;
use Illuminate\Database\Seeder;

class SubscriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $gym = Gym::where('name', 'Gym 1')->first();

        $membership = $gym->memberships->first();

        $members = Member::factory(5)->create();

        $members->each(function ($member) use ($gym, $membership) {
            $gym->subscriptions()->create([
                'start_date' => now(),
                'end_date' => now()->addMonths(1),
                'member_id' => $member->id,
                'membership_id' => $membership->id
            ]);
        });
    }
}

<?php

namespace Tests;

use App\Models\Gym;
use App\Models\User;
use App\Models\Member;
use App\Models\Membership;
use App\Models\Subscription;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    public function createUserGymMembership($role = 'user')
    {
        $user = User::factory()->create(['role' => $role]);
        $gym = Gym::factory()->create(['user_id' => $user->id]);
        $membership = Membership::factory()->create(['gym_id' => $gym->id]);

        return compact('user', 'gym', 'membership');
    }

    public function createUserGymMembershipAndSubscription($role = 'user')
    {
        $data = $this->createUserGymMembership($role);
        $member = Member::factory()->create();
        $subscription = Subscription::factory()->create([
            'gym_id' => $data['gym']->id,
            'member_id' => $member->id,
            'membership_id' => $data['membership']->id,
        ]);

        return array_merge($data, compact('member', 'subscription'));
    }
}

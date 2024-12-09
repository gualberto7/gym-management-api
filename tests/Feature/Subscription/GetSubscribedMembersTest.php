<?php

use App\Http\Resources\SubscriptionResource;
use App\Models\Gym;
use App\Models\User;
use App\Models\Member;
use App\Models\Membership;
use App\Models\Subscription;

test('guest users cannot request subscribed members', function () {
    $response = $this->getJson(route('api.subscribed-members.index'));

    $response->assertStatus(401);
});

test('role user cannot request subscribed members', function () {
    $user = User::factory()->create(['role' => 'user']);
    $gym = Gym::factory()->create(['user_id' => $user->id]);
    $membership = Membership::factory()->create(['gym_id' => $gym->id]);
    $member = Member::factory()->create();
    Subscription::factory()->create([
        'gym_id' => $gym->id,
        'member_id' => $member->id,
        'membership_id' => $membership->id,
    ]);

    $response = $this->actingAs($user)->getJson(route('api.subscribed-members.index'));
    $response->assertStatus(403);
});

// Valid for roles 'owner', 'admin'
test('owners and admins can request subscribed members', function () {
    $user = User::factory()->create(['role' => 'owner']);
    $gym = Gym::factory()->create(['user_id' => $user->id]);
    $membership = Membership::factory()->create(['gym_id' => $gym->id]);
    $member = Member::factory()->create();
    Subscription::factory()->create([
        'gym_id' => $gym->id,
        'member_id' => $member->id,
        'membership_id' => $membership->id,
    ]);

    $response = $this->actingAs($user)->getJson(route('api.subscribed-members.index'));

    $response->assertStatus(200);
    $response->assertJsonStructure([
        'data' => [
            '*' => [
                'id',
                'member',
                'email',
                'membership',
                'start_date',
                'end_date',
            ],
        ],
    ]);
});

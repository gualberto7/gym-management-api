<?php

use App\Models\Member;
use App\Models\Subscription;

test('guest users cannot subscribe members', function () {
    $response = $this->postJson(route('api.subscriptions.store', 123));

    $response->assertStatus(401);
});

test('role user cannot subscribe members', function () {
    $data = createUserGymMembership();
    $member = Member::factory()->create();
    $subscription = Subscription::factory()->raw([
        'member_id' => $member->id,
        'membership_id' => $data['membership']->id,
    ]);

    $response = $this->actingAs($data['user'])
        ->postJson(route('api.subscriptions.store', $data['gym']->id), $subscription);

    $response->assertStatus(403);
});

test('admin can subscribe members', function () {
    $data = createUserGymMembership('admin');
    $member = Member::factory()->create();
    $subscription = Subscription::factory()->raw([
        'member_id' => $member->id,
        'membership_id' => $data['membership']->id,
    ]);

    $response = $this->actingAs($data['user'])
        ->postJson(route('api.subscriptions.store', $data['gym']->id), $subscription);

    $response->assertStatus(201);
});

test('admins can subscribe member only for their assigned gym', function () {
    $data = createUserGymMembership('admin');
    $member = Member::factory()->create();
    $gym = App\Models\Gym::factory()->create();
    $subscription = Subscription::factory()->raw([
        'member_id' => $member->id,
        'membership_id' => $data['membership']->id,
    ]);

    $response = $this->actingAs($data['user'])
        ->postJson(route('api.subscriptions.store', $gym->id), $subscription);

    $response->assertStatus(403);
});

test('owner can subscribe members', function () {
    $data = createUserGymMembership('owner');
    $member = Member::factory()->create();
    $subscription = Subscription::factory()->raw([
        'gym_id' => $data['gym']->id,
        'member_id' => $member->id,
        'membership_id' => $data['membership']->id,
    ]);

    $response = $this->actingAs($data['user'])
        ->postJson(route('api.subscriptions.store', $data['gym']->id), $subscription);

    $response->assertStatus(201);
});

test('owner can subscribe member only for their assigned gym', function () {
    $data = createUserGymMembership('owner');
    $member = Member::factory()->create();
    $gym = App\Models\Gym::factory()->create();
    $subscription = Subscription::factory()->raw([
        'member_id' => $member->id,
        'membership_id' => $data['membership']->id,
    ]);

    $response = $this->actingAs($data['user'])
        ->postJson(route('api.subscriptions.store', $gym->id), $subscription);

    $response->assertStatus(403);
});

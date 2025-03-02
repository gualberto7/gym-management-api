<?php

use App\Models\Member;
use App\Models\Subscription;

test('guest users cannot subscribe members', function () {
    $response = $this->postJson(route('api.subscribed-members.store'));

    $response->assertStatus(401);
});

test('role user cannot subscribe members', function () {
    $data = $this->createUserGymMembership();
    $member = Member::factory()->create();
    $subscription = Subscription::factory()->raw([
        'gym_id' => $data['gym']->id,
        'member_id' => $member->id,
        'membership_id' => $data['membership']->id,
    ]);

    $response = $this->actingAs($data['user'])
        ->postJson(route('api.subscribed-members.store'), $subscription);

    $response->assertStatus(403);
});

test('admin can subscribe members', function () {
    $data = $this->createUserGymMembership('admin');
    $member = Member::factory()->create();

    $subscription = Subscription::factory()->raw([
        'start_date' => now()->format('Y-m-d'),
        'end_date' => now()->addMonth()->format('Y-m-d'),
        'gym_id' => $data['gym']->id,
        'member_id' => $member->id,
        'membership_id' => $data['membership']->id,
    ]);

    $response = $this->actingAs($data['user'])
        ->postJson(route('api.subscribed-members.store'), $subscription);

    $response->assertStatus(201);
});

test('owner can subscribe members', function () {
    $data = $this->createUserGymMembership('owner');
    $member = Member::factory()->create();

    $subscription = Subscription::factory()->raw([
        'start_date' => now()->format('Y-m-d'),
        'end_date' => now()->addMonth()->format('Y-m-d'),
        'gym_id' => $data['gym']->id,
        'member_id' => $member->id,
        'membership_id' => $data['membership']->id,
    ]);

    $response = $this->actingAs($data['user'])
        ->postJson(route('api.subscribed-members.store'), $subscription);

    $response->assertStatus(201);
});

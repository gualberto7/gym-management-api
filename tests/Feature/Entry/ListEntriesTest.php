<?php

use App\Models\Entry;
use App\Models\Gym;
use App\Models\User;
use App\Models\Member;

test('guest users cannot list entries', function () {
    $response = $this->getJson(route('api.entries.index'));

    $response->assertStatus(401);
});

test('admins can list entries of their members', function () {
    $user = User::factory()->create(['role' => 'admin']);
    Gym::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)
        ->getJson(route('api.entries.index'));

    $response->assertStatus(200);
});

test('owners can list entries of their members', function () {
    $user = User::factory()->create(['role' => 'owner']);
    Gym::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)
        ->getJson(route('api.entries.index'));

    $response->assertStatus(200);
});

test("verify entries belongs to owner's / admins gyms", function () {
    $data = $this->createUserGymMembershipAndSubscription('owner');
    Entry::factory()->create(['gym_id' => $data['gym']->id, 'member_id' => $data['member']->id]);

    $data1 = $this->createUserGymMembershipAndSubscription('owner');
    Entry::factory()->create(['gym_id' => $data1['gym']->id, 'member_id' => $data1['member']->id]);

    $response = $this->actingAs($data['user'])
        ->getJson(route('api.entries.index'));

    $response->assertStatus(200);
    $response->assertJsonCount(1, 'data');
});

test('verify entries response data structure', function () {
    $data = $this->createUserGymMembershipAndSubscription('owner');
    Entry::factory()->create(['gym_id' => $data['gym']->id, 'member_id' => $data['member']->id]);

    $response = $this->actingAs($data['user'])
        ->getJson(route('api.entries.index'));

    $response->assertJsonStructure([
        'data' => [
            '*' => [
                'id',
                'member_name',
                'member_phone',
                'created_at',
                'created_by',
            ],
        ],
    ]);
});

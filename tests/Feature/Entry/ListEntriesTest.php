<?php

use App\Models\Entry;
use App\Models\Gym;
use App\Models\User;
use App\Models\Member;

test('guest users cannot list entries', function () {
    $response = $this->getJson(route('api.entries.index', 123));

    $response->assertStatus(401);
});

test('users cannot list entries', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->getJson(route('api.entries.index', 123));

    $response->assertStatus(403);
});

test('admins can list entries of their assigned gym', function () {
    $data = createUserGymMembershipAndSubscription('admin');
    Entry::factory(2)->create(['gym_id' => $data['gym']->id]);

    $this->actingAs($data['user'])
        ->getJson(route('api.entries.index', $data['gym']->id))
        ->assertStatus(200);

    $gym = Gym::factory()->create();
    $this->actingAs($data['user'])
        ->getJson(route('api.entries.index', $gym->id))
        ->assertStatus(403);
});

test('owners can list entries of their members', function () {
    $data = createUserGymMembershipAndSubscription('owner');

    $this->actingAs($data['user'])
        ->getJson(route('api.entries.index', $data['gym']->id))
        ->assertStatus(200);

    $gym = Gym::factory()->create();
    $this->actingAs($data['user'])
        ->getJson(route('api.entries.index', $gym->id))
        ->assertStatus(403);
});

test('verify entries response data structure', function () {
    $data = createUserGymMembershipAndSubscription('owner');
    Entry::factory()->create(['gym_id' => $data['gym']->id, 'member_id' => $data['member']->id]);

    $response = $this->actingAs($data['user'])
        ->getJson(route('api.entries.index', $data['gym']->id));

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

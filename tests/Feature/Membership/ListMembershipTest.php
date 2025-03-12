<?php

use App\Models\Gym;
use App\Models\User;
use App\Models\Membership;

test('guests users cannot request memberships', function () {
    $response = $this->getJson(route('api.memberships.index', 123));

    $response->assertStatus(401);
});

test('users cannot request memberships from gyms they do not own', function () {
    $user = User::factory()->create();
    $gym = Gym::factory()->create();

    $response = $this->actingAs($user)->getJson(route('api.memberships.index', $gym->id));

    $response->assertStatus(403);
});

test('admins  users can request memberships', function () {
    $data = createUserGymMembership('admin');

    $response = $this->actingAs($data['user'])->getJson(route('api.memberships.index', $data['gym']->id));

    $response->assertStatus(200);
});

test('admins can requests memberships only from their assigned gym', function () {
    $data = createUserGymMembership('admin');
    $gym = Gym::factory()->create();

    $response = $this->actingAs($data['user'])->getJson(route('api.memberships.index', $gym->id));

    $response->assertStatus(403);
});

test('owners users can request memberships', function () {
    $data = createUserGymMembership('owner');
    Membership::factory(2)->create(['gym_id' => $data['gym']->id]);

    $response = $this->actingAs($data['user'])->getJson(route('api.memberships.index', $data['gym']->id));

    $response->assertStatus(200);
    $response->assertJsonCount(3);
});

test('owners can requests memberships only from their gyms', function () {
    $data = createUserGymMembership('owner');
    $gym = Gym::factory()->create();

    $response = $this->actingAs($data['user'])->getJson(route('api.memberships.index', $gym->id));

    $response->assertStatus(403);
});

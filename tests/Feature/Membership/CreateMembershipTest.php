<?php

use App\Models\Gym;
use App\Models\User;
use App\Models\Membership;

test('guests users cannot create memberships', function () {
    $response = $this->postJson(route('api.memberships.store', 123), []);

    $response->assertStatus(401);
});

test('role users cannot create memberships', function () {
    $user = User::factory()->create();
    $membership = Membership::factory()->raw();

    $response = $this->actingAs($user)->postJson(route('api.memberships.store', 123), $membership);

    $response->assertStatus(403);
});

test('role admins cannot create memberships', function () {
    $data = createUserGymMembership('admin');
    $membership = Membership::factory()->raw(['gym_id' => $data['gym']->id]);

    $response = $this->actingAs($data['user'])
        ->postJson(route('api.memberships.store', $data['gym']->id), $membership);

    $response->assertStatus(403);
});

test('role admins with permission can create memberships', function () {
    $data = createUserGymMembership('admin');
    $data['user']->givePermissionTo('create memberships');
    $membership = Membership::factory()->raw(['gym_id' => $data['gym']->id]);

    $response = $this->actingAs($data['user'])
        ->postJson(route('api.memberships.store', $data['gym']->id), $membership);

    $response->assertStatus(201);
    $response->assertJson($membership);
});

test('role owners can create memberships', function () {
    $data = createUserGymMembership('owner');
    $membership = Membership::factory()->raw(['gym_id' => $data['gym']->id]);

    $response = $this->actingAs($data['user'])
        ->postJson(route('api.memberships.store', $data['gym']->id), $membership);

    $response->assertStatus(201);
    $response->assertJson($membership);
});

test('role owners can create memberships only in their gyms', function () {
    $data = createUserGymMembership('owner');
    $membership = Membership::factory()->raw(['gym_id' => Gym::factory()->create()->id]);
    $gym = Gym::factory()->create();

    $response = $this->actingAs($data['user'])
        ->postJson(route('api.memberships.store', $gym->id), $membership);

    $response->assertStatus(403);
});

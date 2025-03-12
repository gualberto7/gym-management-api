<?php

use App\Models\Gym;
use App\Models\User;
use App\Models\Membership;

test('guests users cannot update memberships', function () {
    $response = $this->putJson(route('api.memberships.update', [
        'gymId' => 123,
        'membership' => 123,
    ]), []);

    $response->assertStatus(401);
});

test('role users cannot update memberships', function () {
    $user = User::factory()->create();
    $membership = Membership::factory()->create();

    $response = $this->actingAs($user)->putJson(route('api.memberships.update', [
        'gymId' => 123,
        'membership' => $membership->id,
    ]), ["name" => "New Name"]);

    $response->assertStatus(403);
});

test('role admins cannot admins memberships', function () {
    $data = createUserGymMembership('admin');
    $membership = Membership::factory()->create(['gym_id' => $data['gym']->id]);

    $response = $this->actingAs($data['user'])
        ->putJson(route('api.memberships.update', [
            'gymId' => $data['gym']->id,
            'membership' => $data['membership']->id,
        ]), ["name" => "New Name"]);

    $response->assertStatus(403);
});

test('role admins with permission can update memberships', function () {
    $data = createUserGymMembership('admin');
    $data['user']->givePermissionTo('update memberships');

    $response = $this->actingAs($data['user'])
        ->putJson(route('api.memberships.update', [
            'gymId' => $data['gym']->id,
            'membership' => $data['membership']->id,
        ]), ["name" => "New Name"]);

    $response->assertStatus(200);
    $response->assertJson(["name" => "New Name"]);
});

test('role owners can create memberships', function () {
    $data = createUserGymMembership('owner');

    $response = $this->actingAs($data['user'])
        ->putJson(route('api.memberships.update', [
            'gymId' => $data['gym']->id,
            'membership' => $data['membership']->id,
        ]), ["name" => "New Name"]);

    $response->assertStatus(200);
    $response->assertJson(["name" => "New Name"]);
});

test('role owners can create memberships only in their gyms', function () {
    $data = createUserGymMembership('owner');
    $gym = Gym::factory()->create();

    $response = $this->actingAs($data['user'])
        ->putJson(route('api.memberships.update', [
            'gymId' => $gym->id,
            'membership' => $data['membership']->id,
        ]), ["name" => "New Name"]);

    $response->assertStatus(403);
});

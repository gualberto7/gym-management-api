<?php

use App\Enums\Roles;
use App\Models\Gym;
use App\Models\User;

test('guests users cannot list gyms', function () {
    $response = $this->getJson(route('api.gyms.index'));

    $response->assertStatus(401);
});

test('users cannot list gyms', function () {
    $user = User::factory()->create();
    addRole($user, Roles::USER);

    $response = $this->actingAs($user)
        ->getJson(route('api.gyms.index'));

    $response->assertStatus(403);
});

test('admins can list gyms', function () {
    $user = User::factory()->create();
    addRole($user, Roles::ADMIN);

    $response = $this->actingAs($user)
        ->getJson(route('api.gyms.index'));

    $response->assertStatus(200);
});

test('owners can list gyms', function () {
    $user = User::factory()->create();
    addRole($user, Roles::OWNER);

    $response = $this->actingAs($user)
        ->getJson(route('api.gyms.index'));

    $response->assertStatus(200);
});

test('admins can list only their assigned gym', function () {
    $user = User::factory()->create();
    $gym = Gym::factory()->create(['name' => 'test admin gym']);
    $user->update(['assigned_gym' => $gym->id]);
    addRole($user, Roles::ADMIN);

    $response = $this->actingAs($user)
        ->getJson(route('api.gyms.index'));

    $response->assertStatus(200);
    $response->assertJsonCount(1);
    $response->assertJsonFragment(['name' => 'test admin gym']);
});

test('owners can list only their assigned gym', function () {
    $user = User::factory()->create();
    Gym::factory()->create(['name' => 'test owner gym', 'user_id' => $user->id]);
    Gym::factory()->create(['name' => 'test owner gym 2', 'user_id' => $user->id]);
    Gym::factory()->create(['name' => 'test owner gym 2']);
    addRole($user, Roles::OWNER);

    $response = $this->actingAs($user)
        ->getJson(route('api.gyms.index'));

    $response->assertStatus(200);
    $response->assertJsonCount(2);
    $response->assertJsonFragment(['name' => 'test owner gym']);
    $response->assertJsonFragment(['name' => 'test owner gym 2']);
});

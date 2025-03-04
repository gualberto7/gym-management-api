<?php

use App\Models\Gym;
use App\Models\User;
use App\Enums\Roles;

test('guest users cannot create gyms', function () {
    $response = $this->postJson(route('api.gyms.store'), []);

    $response->assertStatus(401);
});

test('admins cannot create gyms', function () {
    $user = User::factory()->create();
    addRole($user, Roles::ADMIN);
    $gym = Gym::factory()->raw();

    $response = $this->actingAs($user)
        ->postJson(route('api.gyms.store'), $gym);

    $response->assertStatus(403);
});

test('users cannot create gyms', function () {
    $user = User::factory()->create();
    addRole($user, Roles::USER);
    $gym = Gym::factory()->raw();

    $response = $this->actingAs($user)
        ->postJson(route('api.gyms.store'), $gym);

    $response->assertStatus(403);
});

test('owners can create gyms', function () {
    $user = User::factory()->create();
    addRole($user, Roles::OWNER);
    $gym = Gym::factory()->raw();

    $response = $this->actingAs($user)
        ->postJson(route('api.gyms.store'), $gym);

    $response->assertStatus(201);
});

test('super admins can create gyms', function () {
    $user = User::factory()->create();
    addRole($user, Roles::SUPER_ADMIN);
    $gym = Gym::factory()->raw();

    $response = $this->actingAs($user)
        ->postJson(route('api.gyms.store'), $gym);

    $response->assertStatus(201);
});

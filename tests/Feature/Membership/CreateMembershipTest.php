<?php

use App\Models\Membership;
use App\Models\User;

test('guests users cannot create memberships', function () {
    $response = $this->postJson(route('api.memberships.store'), []);

    $response->assertStatus(401);
});

test('authenticated users can create memberships', function () {
    $user = User::factory()->create();
    $membership = Membership::factory()->make();

    $response = $this->actingAs($user)->postJson(route('api.memberships.store'), $membership->toArray());

    $response->assertStatus(201);
    $response->assertJson($membership->toArray());
});

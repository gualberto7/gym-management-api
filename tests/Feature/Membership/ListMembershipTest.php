<?php

use App\Models\Membership;
use App\Models\User;

test('guests users cannot request memberships', function () {
    $response = $this->getJson(route('api.memberships.index'));

    $response->assertStatus(401);
});

test('authenticated users can request memberships', function () {
    $user = User::factory()->create();
    $memberships = Membership::factory(3)->create();

    $response = $this->actingAs($user)->getJson(route('api.memberships.index'));

    $response->assertStatus(200);
    $response->assertJson($memberships->toArray());
});

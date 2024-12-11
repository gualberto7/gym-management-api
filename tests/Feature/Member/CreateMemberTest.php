<?php

use App\Models\User;
use App\Models\Member;

test('guest user cannot create members', function () {
    $response = $this->postJson(route('api.members.store'), []);

    $response->assertStatus(401);
});

test('authenticated users can create members', function () {
    $user = User::factory()->create();
    $member = Member::factory()->make();

    $response = $this->actingAs($user)->postJson(route('api.members.store'), $member->toArray());

    $response->assertStatus(201);
    $response->assertJson($member->toArray());
});

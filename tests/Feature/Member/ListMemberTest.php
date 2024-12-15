<?php

use App\Models\User;
use App\Models\Member;

test('guests users cannot request members', function () {
    $response = $this->getJson(route('api.members.index'));

    $response->assertStatus(401);
});

test('authenticated users can request members', function () {
    $user = User::factory()->create();
    $members = Member::factory(3)->create();

    $response = $this->actingAs($user)->getJson(route('api.members.index'));

    $response->assertStatus(200);
    $response->assertJson([
        'data' => [
            ['name' => $members[0]->name],
            ['name' => $members[1]->name],
            ['name' => $members[2]->name],
        ]
    ]);
});

test('authenticated users find one member by CI', function () {
    $user = User::factory()->create();
    Member::factory(2)->create();
    $member = Member::factory()->create(['ci' => '1234567']);

    $response = $this->actingAs($user)
        ->getJson(route('api.members.find', ['ci' => $member->ci]));

    $response->assertStatus(200);
    $response->assertJson(['name' => $member->name]);
});

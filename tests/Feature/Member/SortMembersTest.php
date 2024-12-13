<?php

use App\Models\Member;
use App\Models\User;

test('can sort members by name', function () {
    Member::factory()->create(['name' => 'Julian']);
    Member::factory()->create(['name' => 'Albert']);
    Member::factory()->create(['name' => 'Mario']);

    $response = $this->actingAs(User::factory()->create())
        ->getJson(route('api.members.index', ['sort' => 'name']));

    $response->assertStatus(200);
    $response->assertSeeInOrder([
        'Albert',
        'Julian',
        'Mario'
    ]);
});

test('can sort members by name descending', function () {
    Member::factory()->create(['name' => 'Julian']);
    Member::factory()->create(['name' => 'Albert']);
    Member::factory()->create(['name' => 'Mario']);

    $response = $this->actingAs(User::factory()->create())
        ->getJson(route('api.members.index', ['sort' => '-name']));

    $response->assertStatus(200);
    $response->assertSeeInOrder([
        'Mario',
        'Julian',
        'Albert'
    ]);
});

test('cannot sort by unknow field', function () {
    Member::factory(3)->create();

    $response = $this->actingAs(User::factory()->create())
        ->getJson(route('api.members.index', ['sort' => 'unknown']));

    $response->assertStatus(400);
});

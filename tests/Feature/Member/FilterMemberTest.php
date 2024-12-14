<?php

use App\Models\Member;
use App\Models\User;

test('can filter members by name', function () {
    Member::factory()->create(['name' => 'John Doe']);
    Member::factory()->create(['name' => 'Aaron Doe']);

    $url = route('api.members.index', [
        'filter' => [
            'name' => 'John'
        ]
    ]);

    $response = $this->actingAs(User::factory()->create())->getJson($url);

    $response->assertJsonCount(1, 'data')
        ->assertSee('John Doe')
        ->assertDontSee('Aaron Doe');
});

test('can filter members by ci', function () {
    Member::factory()->create(['name' => 'John Doe', 'ci' => '7526839']);
    Member::factory()->create(['name' => 'Aaron Doe', 'ci' => '6758786']);

    $url = route('api.members.index', [
        'filter' => [
            'ci' => '7526'
        ]
    ]);

    $response = $this->actingAs(User::factory()->create())->getJson($url);

    $response->assertJsonCount(1, 'data')
        ->assertSee('John Doe')
        ->assertDontSee('Aaron Doe');
});

test('can paginate filtered members', function () {
    Member::factory(6)->create();

    $url = route('api.members.index', [
        'filter[name]' => 'John',
        'page' => [
            'size' => 1,
            'number' => 2
        ]
    ]);

    $response = $this->actingAs(User::factory()->create())->getJson($url);
    $firstLink = urldecode($response['links'][1]['url']);

    $this->assertStringContainsString('filter[name]=John', $firstLink);
});

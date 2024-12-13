<?php

use App\Models\Member;
use App\Models\User;

test('can paginate members', function () {
    $members = Member::factory()->count(6)->create();

    $route = route('api.members.index', [
        'page' => [
            'size' => 2,
            'number' => 2
        ]
    ]);

    $response = $this->actingAs(User::factory()->create())->getJson($route);

    $response->assertSee([
        $members[2]->name,
        $members[3]->name
    ]);

    $response->assertDontSee([
        $members[0]->name,
        $members[1]->name,
        $members[4]->name,
        $members[5]->name
    ]);

    $response->assertJsonStructure([
        'links',
        'current_page',
        'from',
        'last_page',
        'path',
        'per_page',
        'to',
        'total'
    ]);
});

test('can sort and paginate memners', function () {
    Member::factory()->create(['name' => 'Julian']);
    Member::factory()->create(['name' => 'Albert']);
    Member::factory()->create(['name' => 'Mario']);

    $route = route('api.members.index', [
        'sort' => 'name',
        'page' => [
            'size' => 1,
            'number' => 2
        ]
    ]);

    $response = $this->actingAs(User::factory()->create())->getJson($route);

    $response->assertSee([
        'Julian'
    ]);

    $response->assertDontSee([
        'Albert',
        'Mario'
    ]);

    //dd($response['links']);

    $firstLink = urldecode($response['links'][0]['url']);

    $this->assertStringContainsString('page[number]=1', $firstLink);
    $this->assertStringContainsString('sort=name', $firstLink);
});

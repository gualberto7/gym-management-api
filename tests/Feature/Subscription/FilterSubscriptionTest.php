<?php

use App\Models\Member;
use App\Models\Subscription;

test('can filter by start_date field', function () {
    $data = createUserGymMembership('admin');
    $startDate = now()->format('Y-m-d');

    Subscription::factory()->create([
        'gym_id' => $data['gym']->id,
        'start_date' => $startDate
    ]);
    Subscription::factory(2)->create([
        'gym_id' => $data['gym']->id,
        'start_date' => now()->subDays(2)->format('Y-m-d')
    ]);

    $url = route('api.subscriptions.index', [
        $data['gym']->id,
        'filter' => [
            'start_date' => $startDate
        ]
    ]);

    $this->actingAs($data['user'])->getJson($url)
        ->assertStatus(200)
        ->assertJsonCount(1, 'data');
});

test('can filter by end_date field', function () {
    $data = createUserGymMembership('admin');
    $endDate = now()->format('Y-m-d');

    Subscription::factory()->create([
        'gym_id' => $data['gym']->id,
        'end_date' => $endDate
    ]);
    Subscription::factory(2)->create([
        'gym_id' => $data['gym']->id,
        'end_date' => now()->subDays(2)->format('Y-m-d')
    ]);

    $url = route('api.subscriptions.index', [
        $data['gym']->id,
        'filter' => [
            'end_date' => $endDate
        ]
    ]);

    $this->actingAs($data['user'])->getJson($url)
        ->assertStatus(200)
        ->assertJsonCount(1, 'data');
});

test('can filter by member.name field', function () {
    $data = createUserGymMembership('admin');
    $member = Member::factory()->create(['name' => 'John Doe']);

    Subscription::factory()->create([
        'gym_id' => $data['gym']->id,
        'member_id' => $member->id
    ]);
    Subscription::factory(2)->create([
        'gym_id' => $data['gym']->id
    ]);

    $url = route('api.subscriptions.index', [
        $data['gym']->id,
        'filter' => [
            'member.name' => $member->name
        ]
    ]);

    $this->actingAs($data['user'])->getJson($url)
        ->assertStatus(200)
        ->assertJsonCount(1, 'data')
        ->assertSee($member->name);
});

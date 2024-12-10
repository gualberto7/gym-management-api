<?php

test('guest users cannot request subscribed members', function () {
    $response = $this->getJson(route('api.subscribed-members.index'));

    $response->assertStatus(401);
});

test('role user cannot request subscribed members', function () {
    $data = $this->createUserGymMembershipAndSubscription();

    $response = $this->actingAs($data['user'])
        ->getJson(route('api.subscribed-members.index'));

    $response->assertStatus(403);
});

test('admin can request subscribed members', function () {
    $data = $this->createUserGymMembershipAndSubscription('admin');

    $response = $this->actingAs($data['user'])
        ->getJson(route('api.subscribed-members.index'));

    $response->assertStatus(200);
    $response->assertJsonStructure([
        'data' => [
            '*' => [
                'id',
                'member',
                'email',
                'membership',
                'start_date',
                'end_date',
            ],
        ],
    ]);
});

test('owner can request subscribed members', function () {
    $data = $this->createUserGymMembershipAndSubscription('owner');

    $response = $this->actingAs($data['user'])
        ->getJson(route('api.subscribed-members.index'));

    $response->assertStatus(200);
    $response->assertJsonStructure([
        'data' => [
            '*' => [
                'id',
                'member',
                'email',
                'membership',
                'start_date',
                'end_date',
            ],
        ],
    ]);
});

<?php

test('guest users cannot request subscribed members', function () {
    $response = $this->getJson(route('api.subscribed-members.index'));

    $response->assertStatus(401);
});

test('role user cannot request subscribed members', function () {
    $data = $this->createUserGymMembershipAndSubscription();

    $this->actingAs($data['user'])
        ->getJson(route('api.subscribed-members.index'))
        ->assertStatus(403);
});

test('admin can request subscribed members', function () {
    $data = $this->createUserGymMembershipAndSubscription('admin');

    $this->actingAs($data['user'])
        ->getJson(route('api.subscribed-members.index'))
        ->assertStatus(200);

});

test('owner can request subscribed members', function () {
    $data = $this->createUserGymMembershipAndSubscription('owner');

    $this->actingAs($data['user'])
        ->getJson(route('api.subscribed-members.index'))
        ->assertStatus(200);
});

test('verify response structure when requesting subscribed members', function () {
    $data = $this->createUserGymMembershipAndSubscription('owner');

    $response = $this->actingAs($data['user'])
        ->getJson(route('api.subscribed-members.index'));

    $response->assertJsonStructure([
        'data' => [
            '*' => [
                'id',
                'start_date',
                'end_date',
                'member' => [
                    'id',
                    'name',
                    'email',
                    'phone',
                ],
                'membership' => [
                    'id',
                    'name',
                ],
            ],
        ],
    ]);
});

test('verify subscription members is paginated', function () {
    $data = $this->createUserGymMembershipAndSubscription('owner');

    $response = $this->actingAs($data['user'])
        ->getJson(route('api.subscribed-members.index'));

    $response->assertStatus(200);
    $response->assertJsonStructure([
        'data',
        'links' => [
            'first',
            'last',
            'prev',
            'next',
        ],
        'meta' => [
            'current_page',
            'from',
            'last_page',
            'path',
            'per_page',
            'to',
            'total',
            'links' => [
                '*' => [
                    'url',
                    'label',
                    'active',
                ],
            ],
        ],
    ]);
});

<?php

use App\Models\Gym;

test('guest users cannot request subscribed members', function () {
    $response = $this->getJson(route('api.subscriptions.index', '1'));

    $response->assertStatus(401);
});

test('role user cannot request subscribed members', function () {
    $data = createUserGymMembershipAndSubscription();

    $this->actingAs($data['user'])
        ->getJson(route('api.subscriptions.index', $data['gym']->id))
        ->assertStatus(403);
});

test('admin can request subscribed members', function () {
    $data = createUserGymMembershipAndSubscription('admin');

    $this->actingAs($data['user'])
        ->getJson(route('api.subscriptions.index', $data['gym']->id))
        ->assertStatus(200);

});

test('admins can request subscription only of their assigned gym', function () {
    $data = createUserGymMembershipAndSubscription('admin');
    $gym = Gym::factory()->create();

    $this->actingAs($data['user'])
        ->getJson(route('api.subscriptions.index', $gym->id))
        ->assertStatus(403);
});

test('owner can request subscribed members', function () {
    $data = createUserGymMembershipAndSubscription('owner');

    $this->actingAs($data['user'])
        ->getJson(route('api.subscriptions.index', $data['gym']->id))
        ->assertStatus(200);
});

test('owners can request subscription only of their gym', function () {
    $data = createUserGymMembershipAndSubscription('owner');
    $gym = Gym::factory()->create();

    $this->actingAs($data['user'])
        ->getJson(route('api.subscriptions.index', $gym->id))
        ->assertStatus(403);
});

test('verify response structure when requesting subscribed members', function () {
    $data = createUserGymMembershipAndSubscription('owner');

    $response = $this->actingAs($data['user'])
        ->getJson(route('api.subscriptions.index', $data['gym']->id));

    $response->assertJsonStructure([
        'data' => [
            '*' => [
                'id',
                'start_date',
                'end_date',
                'status',
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
    $data = createUserGymMembershipAndSubscription('owner');

    $response = $this->actingAs($data['user'])
        ->getJson(route('api.subscriptions.index', $data['gym']->id));

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

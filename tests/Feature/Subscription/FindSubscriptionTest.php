<?php

use App\Models\Member;

test('guests users cannot find subscriptions', function () {
    $response = $this->getJson(route('api.subscriptions.show', ['gymId' => '2','ci' => '123456']));

    $response->assertStatus(401);
});

test('admins can find a subscription', function () {
    $data = $this->createUserGymMembershipAndSubscription('admin');

    $response = $this->actingAs($data['user'])
        ->getJson(route('api.subscriptions.show', ['gymId' => $data['gym']->id, 'ci' => $data['member']->ci]));

    $response->assertStatus(200);
});

test('owners can find a subscription', function () {
    $data = $this->createUserGymMembershipAndSubscription('admin');

    $response = $this->actingAs($data['user'])
        ->getJson(route('api.subscriptions.show', ['gymId' => $data['gym']->id, 'ci' => $data['member']->ci]));

    $response->assertStatus(200);
});

test('verify the response structure when finding a subscription', function () {
    $data = $this->createUserGymMembershipAndSubscription('admin');

    $response = $this->actingAs($data['user'])
        ->getJson(route('api.subscriptions.show', ['gymId' => $data['gym']->id, 'ci' => $data['member']->ci]));

    $response->assertJsonStructure([
        'data' => [
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
    ]);
});

test('it fails if the member does not exist', function () {
    $data = $this->createUserGymMembershipAndSubscription('admin');

    $response = $this->actingAs($data['user'])
        ->getJson(route('api.subscriptions.show', ['gymId' => $data['gym']->id, 'ci' => '123456']));

    $response->assertStatus(404);
    $response->assertJsonPath('message', 'No hay registros para CI: 123456');
});

test('it fails if the member does not have a subscrition', function () {
    $data = $this->createUserGymMembership('admin');
    $member = Member::factory()->create();

    $response = $this->actingAs($data['user'])
        ->getJson(route('api.subscriptions.show', ['gymId' => $data['gym']->id, 'ci' => $member->ci]));

    $response->assertStatus(404);
    $response->assertJsonPath('message', 'Este usuario no tiene una subscripción');
});

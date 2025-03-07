<?php

use App\Models\Gym;
use App\Models\User;

test('guest users cannot add entries', function () {
    $response = $this->postJson(route('api.entries.store', 123), []);

    $response->assertStatus(401);
});

/**
 * Role user cannot create entries
 *
 * GIVEN I am a user
 * WHEN I try to create an entry
 * THEN I should see an 403 unauthorized error
 */
test('role users cannot add entries', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->postJson(route('api.entries.store', 123), []);

    $response->assertStatus(403);
});

/**
 * Admins users can add entries
 *
 * GIVEN I am an admin user
 * WHEN I create an entry
 * THEN I should see the entry in the database
 */
test('admins users can add entries', function () {
    $data = createUserGymMembershipAndSubscription('admin');

    $response = $this->actingAs($data['user'])
        ->postJson(route('api.entries.store', $data['gym']->id), [
            'member_id' => $data['member']->id,
        ]);

    $response->assertStatus(201);
    $this->assertDatabaseHas('entries', [
        'member_id' => $data['member']->id,
        'gym_id' => $data['gym']->id,
        'created_by' => $data['user']->name,
    ]);
});

/**
 * Owners users can add entries
 *
 * GIVEN I am an owner user
 * WHEN I create an entry
 * THEN I should see the entry in the database
 */
test('owners users can add entries', function () {
    $data = createUserGymMembershipAndSubscription('owner');

    $response = $this->actingAs($data['user'])
        ->postJson(route('api.entries.store', $data['gym']->id), [
            'member_id' => $data['member']->id,
        ]);

    $response->assertStatus(201);
    $this->assertDatabaseHas('entries', [
        'member_id' => $data['member']->id,
        'gym_id' => $data['gym']->id,
        'created_by' => $data['user']->name,
    ]);
});

/**
 * Admins can create entries only for their assigned gym
 *
 * GIVEN I am an admin user
 * AND I have a gym assigned
 * WHEN I create an entry for a gym that is not assigned to me
 * THEN I should see an 403 unauthorized error
 */
test('admins can create entries only for their assigned gym', function () {
    $data = createUserGymMembershipAndSubscription('admin');
    $gym = Gym::factory()->create();

    $response = $this->actingAs($data['user'])
        ->postJson(route('api.entries.store', $gym->id), [
            'member_id' => $data['member']->id,
        ]);

    $response->assertStatus(403);
});

/**
 * Owners can create entries only for their own gym
 *
 * GIVEN I am an owner user
 * AND I have a gym
 * WHEN I create an entry for other gym
 * THEN I should see an 403 unauthorized error
 */
test('owners can create entries only for their own gym', function () {
    $data = createUserGymMembershipAndSubscription('owner');
    $gym = Gym::factory()->create();

    $response = $this->actingAs($data['user'])
        ->postJson(route('api.entries.store', $gym->id), [
            'member_id' => $data['member']->id,
        ]);

    $response->assertStatus(403);
});

// Validation Rules

/**
 * Member ID is required
 *
 * GIVEN I am an admin user
 * WHEN I try to create an entry without member_id
 * THEN I should see a validation error
 */
test('member_id is required', function () {
    $data = createUserGymMembershipAndSubscription('owner');

    $response = $this->actingAs($data['user'])
        ->postJson(route('api.entries.store', $data['gym']->id), ['member_id' => '']);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors('member_id');
});

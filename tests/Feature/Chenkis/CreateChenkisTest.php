<?php

use App\Models\Gym;
use App\Models\Member;
use App\Models\User;

test('guest users cannot add chenkis', function () {
    $response = $this->postJson(route('api.chenkis.store'), []);

    $response->assertStatus(401);
});

test('admins users can add chenkis', function () {
    $user = User::factory()->create(['role' => 'admin']);
    $gym = Gym::factory()->create(['user_id' => $user->id]);
    $member = Member::factory()->create();

    $response = $this->actingAs($user)
        ->postJson(route('api.chenkis.store'), [
            'member_id' => $member->id,
            'gym_id' => $gym->id,
            'registred_by' => $user->name,
        ]);

    $response->assertStatus(201);
    $this->assertDatabaseHas('chenkis', [
        'member_id' => $member->id,
        'gym_id' => $gym->id,
        'registred_by' => $user->name,
    ]);
});

test('owners users can add chenkis', function () {
    $user = User::factory()->create(['role' => 'owner']);
    $gym = Gym::factory()->create(['user_id' => $user->id]);
    $member = Member::factory()->create();

    $response = $this->actingAs($user)
        ->postJson(route('api.chenkis.store'), [
            'member_id' => $member->id,
            'gym_id' => $gym->id,
            'registred_by' => $user->name,
        ]);

    $response->assertStatus(201);
    $this->assertDatabaseHas('chenkis', [
        'member_id' => $member->id,
        'gym_id' => $gym->id,
        'registred_by' => $user->name,
    ]);
});

// Validation Rules

test('member_id is required', function () {
    $user = User::factory()->create(['role' => 'admin']);
    $gym = Gym::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)
        ->postJson(route('api.chenkis.store'), [
            'gym_id' => $gym->id,
            'registred_by' => $user->name,
        ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors('member_id');
});

test('gym_id is required', function () {
    $user = User::factory()->create(['role' => 'admin']);
    $member = Member::factory()->create();

    $response = $this->actingAs($user)
        ->postJson(route('api.chenkis.store'), [
            'member_id' => $member->id,
            'registred_by' => $user->name,
        ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors('gym_id');
});

test('registred_by is required', function () {
    $user = User::factory()->create(['role' => 'admin']);
    $gym = Gym::factory()->create(['user_id' => $user->id]);
    $member = Member::factory()->create();

    $response = $this->actingAs($user)
        ->postJson(route('api.chenkis.store'), [
            'member_id' => $member->id,
            'gym_id' => $gym->id,
        ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors('registred_by');
});

test('registred_by must be string', function () {
    $user = User::factory()->create(['role' => 'admin']);
    $gym = Gym::factory()->create(['user_id' => $user->id]);
    $member = Member::factory()->create();

    $response = $this->actingAs($user)
        ->postJson(route('api.chenkis.store'), [
            'member_id' => $member->id,
            'gym_id' => $gym->id,
            'registred_by' => 123,
        ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors('registred_by');
});

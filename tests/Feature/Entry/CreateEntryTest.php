<?php

use App\Models\Gym;
use App\Models\Member;
use App\Models\User;

test('guest users cannot add entries', function () {
    $response = $this->postJson(route('api.entries.store'), []);

    $response->assertStatus(401);
});

test('admins users can add entries', function () {
    $user = User::factory()->create(['role' => 'admin']);
    $gym = Gym::factory()->create(['user_id' => $user->id]);
    $member = Member::factory()->create();

    $response = $this->actingAs($user)
        ->postJson(route('api.entries.store'), [
            'member_id' => $member->id,
            'gym_id' => $gym->id,
            'created_by' => $user->name,
        ]);

    $response->assertStatus(201);
    $this->assertDatabaseHas('entries', [
        'member_id' => $member->id,
        'gym_id' => $gym->id,
        'created_by' => $user->name,
    ]);
});

test('owners users can add entries', function () {
    $user = User::factory()->create(['role' => 'owner']);
    $gym = Gym::factory()->create(['user_id' => $user->id]);
    $member = Member::factory()->create();

    $response = $this->actingAs($user)
        ->postJson(route('api.entries.store'), [
            'member_id' => $member->id,
            'gym_id' => $gym->id,
            'created_by' => $user->name,
        ]);

    $response->assertStatus(201);
    $this->assertDatabaseHas('entries', [
        'member_id' => $member->id,
        'gym_id' => $gym->id,
        'created_by' => $user->name,
    ]);
});

// Validation Rules

test('member_id is required', function () {
    $user = User::factory()->create(['role' => 'admin']);
    $gym = Gym::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)
        ->postJson(route('api.entries.store'), [
            'gym_id' => $gym->id,
            'created_by' => $user->name,
        ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors('member_id');
});

test('gym_id is required', function () {
    $user = User::factory()->create(['role' => 'admin']);
    $member = Member::factory()->create();

    $response = $this->actingAs($user)
        ->postJson(route('api.entries.store'), [
            'member_id' => $member->id,
            'created_by' => $user->name,
        ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors('gym_id');
});

test('created_by is required', function () {
    $user = User::factory()->create(['role' => 'admin']);
    $gym = Gym::factory()->create(['user_id' => $user->id]);
    $member = Member::factory()->create();

    $response = $this->actingAs($user)
        ->postJson(route('api.entries.store'), [
            'member_id' => $member->id,
            'gym_id' => $gym->id,
        ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors('created_by');
});

test('created_by must be string', function () {
    $user = User::factory()->create(['role' => 'admin']);
    $gym = Gym::factory()->create(['user_id' => $user->id]);
    $member = Member::factory()->create();

    $response = $this->actingAs($user)
        ->postJson(route('api.entries.store'), [
            'member_id' => $member->id,
            'gym_id' => $gym->id,
            'created_by' => 123,
        ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors('created_by');
});

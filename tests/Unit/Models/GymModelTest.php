<?php

use App\Models\Gym;
use App\Models\User;
use App\Models\Membership;

test('gym belongs to a user', function () {
    $user = User::factory()->create();
    $gym = Gym::factory()->create(['user_id' => $user->id]);

    expect($gym->user->is($user))->toBeTrue();
});

test('gym has many memberships', function () {
    $gym = Gym::factory()->create();
    Membership::factory(2)->create(['gym_id' => $gym->id]);

    expect($gym->memberships->first())->toBeInstanceOf(Membership::class);
});

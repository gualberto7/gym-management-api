<?php

use App\Models\Entry;
use App\Models\Gym;
use App\Models\User;
use App\Models\Membership;
use App\Models\Subscription;

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

test('gym has many subscriptions', function () {
    $gym = Gym::factory()->create();
    $subscription = Subscription::factory()->create(['gym_id' => $gym->id]);

    expect($gym->subscriptions->first()->is($subscription))->toBeTrue();
});

test('gym has many entries', function () {
    $gym = Gym::factory()->create();
    Entry::factory(2)->create(['gym_id' => $gym->id]);

    expect($gym->entries->first())->toBeInstanceOf(Entry::class);
});

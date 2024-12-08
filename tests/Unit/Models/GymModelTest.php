<?php

use App\Models\Gym;
use App\Models\User;

test('gym belongs to a user', function () {
    $user = User::factory()->create();
    $gym = Gym::factory()->create(['user_id' => $user->id]);

    expect($gym->user->is($user))->toBeTrue();
});

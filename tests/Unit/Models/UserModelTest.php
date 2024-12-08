<?php

use App\Models\Gym;
use App\Models\User;

test('user has many gyms', function () {
    $user = User::factory()->create();
    Gym::factory(2)->create(['user_id' => $user->id]);

    expect($user->gyms->first())->toBeInstanceOf(Gym::class);
});

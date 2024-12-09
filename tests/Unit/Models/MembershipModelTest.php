<?php

use App\Models\Gym;
use App\Models\Membership;

test('membership belongs to a gym', function () {
    $gym = Gym::factory()->create();
    $membership = Membership::factory()->create(['gym_id' => $gym->id]);

    expect($membership->gym->is($gym))->toBeTrue();
});

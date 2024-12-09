<?php

use App\Models\Gym;
use App\Models\Membership;
use App\Models\Subscription;

test('membership belongs to a gym', function () {
    $gym = Gym::factory()->create();
    $membership = Membership::factory()->create(['gym_id' => $gym->id]);

    expect($membership->gym->is($gym))->toBeTrue();
});

test('membership has a subscriptions', function () {
    $membership = Membership::factory(2)->create();
    Subscription::factory()->create(['membership_id' => $membership[0]->id]);
    $this->assertInstanceOf(Subscription::class, $membership[0]->subscriptions[0]);
});

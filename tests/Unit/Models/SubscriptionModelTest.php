<?php

use App\Models\Gym;
use App\Models\Member;
use App\Models\Membership;
use App\Models\Subscription;

test('subscription has a member', function () {
    $subscription = Subscription::factory()->create();
    $this->assertInstanceOf(Member::class, $subscription->member);
});

test('subscription belongs to a gym', function () {
    $subscription = Subscription::factory()->create();
    $this->assertInstanceOf(Gym::class, $subscription->gym);
});

test('subscription belongs to a membership', function () {
    $subscription = Subscription::factory()->create();
    $this->assertInstanceOf(Membership::class, $subscription->membership);
});

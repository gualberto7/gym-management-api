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

test('it should return the correct status', function () {
    $subscription = Subscription::factory()->create(['end_date' => now()->subDays(5)]);
    $this->assertEquals('active', $subscription->getStatus());

    $subscription = Subscription::factory()->create(['end_date' => now()->subDays(2)]);
    $this->assertEquals('upcoming', $subscription->getStatus());

    $subscription = Subscription::factory()->create(['end_date' => now()->addDays(1)]);
    $this->assertEquals('expired', $subscription->getStatus());
});
